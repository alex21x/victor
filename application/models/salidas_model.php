<?php
class Salidas_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function select($idSalida='')
    {
        if($idSalida=='')
        {
            $rsSalidas = $this->db->from("salidas")
                                  ->get()
                                  ->result();
            return $rsSalidas;                     
        }
        $rsSalida = $this->db->from("salidas")
                              ->where("sal_id", $idSalida)
                              ->get()
                              ->row();
        return $rsSalida; 
    }
    public function getMainList()
    {
        $select = $this->db->from("salidas as sal")
                           ->join("almacenes as alm", "sal.sal_almacen_id=alm.alm_id", "left")
                           ->where("sal.sal_estado", ST_ACTIVO)
                           ->order_by('sal.sal_id', "desc");

        if($_POST['almacen'] != '')
        {
            $select->like("alm.alm_nombre", $_POST['almacen']);
        }
        if($_POST['fecha'] != '')
        {
            $select->where("alm_fecha", (new DateTime($_POST['fecha']))->format('Y-m-d'));
        }
        /*obetemos la cantidad de registros*/
        $selectCount = clone $select;
        $rsCount = $selectCount->get()
                               ->result();
        $rows = count($rsCount);
        /*obtenemo los ingresos*/
        $select->limit($_POST['pageSize'], $_POST['skip']);
        $rsSalidas = $select->get()
                            ->result();

        foreach($rsSalidas as $salida)
        {
        	//formateamos fecha
        	$salida->sal_fecha = (new DateTime($salida->sal_fecha))->format('d/m/Y');
        	$salida->sal_editar = "<a class='btn btn-default btn-xs btn_modificar_salida' data-id='{$salida->sal_id}' data-toggle='modal' data-target='#myModal'><i class='glyphicon glyphicon-pencil'></i></a>";
        	$salida->sal_eliminar = "<a class='btn btn-danger btn-xs btn_eliminar_salida' data-id='{$salida->sal_id}' data-msg='Desea eliminar salida: {$salida->sal_codigo}?'><i class='glyphicon glyphicon-remove'></i></a>";
        }

       	$datos = [
       				'data' => $rsSalidas,
       				'rows' => $rows
       			 ];

        return $datos;                   	
    }	

    public function getMainListDetail()
    {
    	$sal_id = $_POST['sal_id'];
    	$where = "WHERE sald_salida_id = {$sal_id}";

    	/*total de registros*/
     	$sqlRows = "SELECT * FROM salidas_detalle ".$where." ORDER BY sald_id DESC";
        $queryRows = $this->db->query($sqlRows);
        $rows = count($queryRows->result());   	
        /*lista de salidas_detalle*/
        $sql = "SELECT * FROM salidas_detalle AS sd INNER JOIN productos AS p ON sd.sald_producto_id=p.prod_id ".$where." ORDER BY sald_id DESC LIMIT {$_POST['skip']},{$_POST['pageSize']}";//2:estado_Activo
        $query = $this->db->query($sql);
        $rsSalidaDetalle = $query->result(); 
        foreach($rsSalidaDetalle as $item)
        {
            $item->sald_eliminar = '<button type="button" class="btn btn-danger btn-xs btn_eliminar_detalle" data-id="'.$item->sald_id.'" data-msg="Desea eliminar producto: '.$item->prod_nombre.'"><i class="glyphicon glyphicon-remove"></i></button>';
        }

       	$datos = [
       				'data' => $rsSalidaDetalle,
       				'rows' => $rows
       			 ];

        return $datos;         
    }

    public function guardarSalida()
    {
        if($_POST['sal_id'] != '')
        {
            $dataUpdate = [
                            'sal_fecha'         => $_POST['fecha_salida'],
                            'sal_observacion' => $_POST['observacion']
                          ];
            $this->db->where('sal_id', $_POST['sal_id']);
            $this->db->update('salidas', $dataUpdate); 
            $salidaId = $_POST['sal_id'];                        
        }else
        {
            $codigo = rand(100000, 999999);
            $dataInsert = [
                            'sal_fecha'       => $_POST['fecha_salida'],
                            'sal_observacion' => $_POST['observacion'],
                            'sal_almacen_id'  => $_POST['almacen'],
                            'sal_codigo'      => $codigo,
                            'sal_estado'      => ST_ACTIVO
                          ];
            $this->db->insert('salidas', $dataInsert); 
            $salidaId = $this->db->insert_id();              
        } 
        
        return $salidaId;       
    }
    public function guardarProductoSalida()
    {

        $salidaId = $_POST['sal_id'];
        /*validamos que la cantidad requerida este disponible*/
        $rsEjemplares = $this->db->from("ejemplar")
                                 ->where("ejm_producto_id", $_POST['producto'])
                                 ->where("ejm_estado", ST_PRODUCTO_DISPONIBLE)
                                 ->limit($_POST['cantidad'])
                                 ->get()
                                 ->result();

        if(count($rsEjemplares) < $_POST['cantidad'])
        {
        	return false;
        }         

    	//guardamos el detalle(producto)
    	$dataInsertDetalle = [
    							'sald_salida_id'   => $salidaId,
    							'sald_producto_id' => $_POST['producto'],
    							'sald_cantidad'    => $_POST['cantidad'],
    						 ];

    	$this->db->insert('salidas_detalle', $dataInsertDetalle);

    	/*cambiamos de estado los productos que se daran de baja*/
    	foreach($rsEjemplares as $ejemplar)
    	{
    		$dataUpdate = [
    						"ejm_estado" => ST_PRODUCTO_BAJA
    					  ];
    		$this->db->where("ejm_id", $ejemplar->ejm_id);
    		$this->db->update("ejemplar", $dataUpdate);			  
    	}

    	//actualizamos stock del producto
        $rsEjemplares = $this->db->from("ejemplar")
                                 ->where("ejm_producto_id", $_POST['producto'])
                                 ->where("ejm_estado", ST_PRODUCTO_DISPONIBLE)
                                 ->get()
                                 ->result();

    	$stockActual = count($rsEjemplares);
    	$stockActual+=$_POST['cantidad'];

    	//actualizamos stock
    	$dataUpdate = [
    					'prod_stock' => $stockActual
    				  ]; 
    	$this->db->where('prod_id',$_POST['producto']);
    	$this->db->update('productos', $dataUpdate);              
    	return true;
    }

    public function eliminarDetalleSalida($idDetalle)
    {
        /*obtenemos el detalle de salida*/
        $rsDetalle = $this->db->from("salidas_detalle")
                              ->where("sald_id", $idDetalle)
                              ->get()
                              ->row();
        /*obtenemos todos los ejemplares que son de ese producto*/
        $rsEjemplares = $this->db->from("ejemplar")
                                 ->where("ejm_producto_id",$rsDetalle->sald_producto_id)
                                 ->where("ejm_estado", ST_PRODUCTO_BAJA)
                                 ->limit($rsDetalle->sald_cantidad)
                                 ->get()
                                 ->result();

        /*volvemos los ejemplares a estado activo*/
        foreach($rsEjemplares as $ejemplar)
        {
        	$dataUpdate = [
        					"ejm_estado" => ST_PRODUCTO_DISPONIBLE
        				  ];

        	$this->db->where("ejm_id", $ejemplar->ejm_id);
        	$this->db->update("ejemplar", $dataUpdate);			  
        } 

        /*eliminamos el detalle*/
        $this->db->delete("salidas_detalle",["sald_id"=>$idDetalle]);

        return true;                        

    }

    public function eliminarSalida($idSalida)
    {
        /*primero eliminamos todos los detalles de esa salida*/
        $rsDetalle = $this->db->from("salidas_detalle")
                              ->where("sald_salida_id", $idSalida)
                              ->get()
                              ->result();
        if($rsDetalle)
        {
            foreach($rsDetalle as $detalle)
            {
                /*eliminamos cada detalle*/
                $this->eliminarDetalleSalida($detalle->sald_id);
            }
        } 

        /*eliminamos la salida */
        $this->db->delete("salidas", ['sal_id'=>$idSalida]);
        return true;                     
    }
}