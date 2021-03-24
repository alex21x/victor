<?php
class Ingresos_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function select($idIngreso='')
    {
        if($idIngreso=='')
        {
            $rsIngresos = $this->db->from("ingresos")
                               ->get()
                               ->result();
            return $rsIngresos;                     
        }
        $rsIngreso = $this->db->from("ingresos")
                              ->where("ing_id", $idIngreso)
                              ->get()
                              ->row();
        return $rsIngreso; 
    }
    public function getMainList()
    {
        $select = $this->db->from("ingresos as ing")
                           ->join("proveedores as prov", "ing.ing_proveedor_id=prov.prov_id", "left")
                           ->join("almacenes as alm", "ing.ing_almacen_id=alm.alm_id", "left")
                           ->where("ing.ing_estado", ST_ACTIVO)
                           ->order_by('ing.ing_id', "desc");

        if($_POST['proveedor'] != '')
        {
            $select->like("prov.prov_razon_social", $_POST['proveedor']);
        }
        if($_POST['fecha'] != '')
        {
            $select->where("ing_fecha", (new DateTime($_POST['fecha']))->format('Y-m-d'));
        }
        /*obetemos la cantidad de registros*/
        $selectCount = clone $select;
        $rsCount = $selectCount->get()
                               ->result();
        $rows = count($rsCount);
        /*obtenemo los ingresos*/
        $select->limit($_POST['pageSize'], $_POST['skip']);
        $rsIngresos = $select->get()
                             ->result();

        foreach($rsIngresos as $ingreso)
        {
        	//formateamos fecha
        	$ingreso->ing_fecha = (new DateTime($ingreso->ing_fecha))->format('d/m/Y');
        	$ingreso->ing_editar = "<a class='btn btn-default btn-xs btn_modificar_ingreso' data-id='{$ingreso->ing_id}' data-toggle='modal' data-target='#myModal'><i class='glyphicon glyphicon-pencil'></i></a>";
        	$ingreso->ing_eliminar = "<a class='btn btn-danger btn-xs btn_eliminar_ingreso' data-id='{$ingreso->ing_id}' data-msg='Desea eliminar ingreso: {$ingreso->ing_codigo}?'><i class='glyphicon glyphicon-remove'></i></a>";
        }

       	$datos = [
       				'data' => $rsIngresos,
       				'rows' => $rows
       			 ];

        return $datos;                   	
    }	

    public function getMainListDetail()
    {

    	$ing_id = $_POST['ing_id'];
    	$where = "WHERE ingd_ingreso_id = {$ing_id}";

    	/*total de registros*/
     	$sqlRows = "SELECT * FROM ingresos_detalle ".$where." ORDER BY ingd_id DESC";
        $queryRows = $this->db->query($sqlRows);
        $rows = count($queryRows->result());   	
        /*lista de ingresos_detalle*/
        $sql = "SELECT * FROM ingresos_detalle AS id INNER JOIN productos AS p ON id.ingd_producto_id=p.prod_id ".$where." ORDER BY ingd_id DESC LIMIT {$_POST['skip']},{$_POST['pageSize']}";//2:estado_Activo
        $query = $this->db->query($sql);
        $rsIngresoDetalle = $query->result(); 

        //$select = $this->db->
        foreach($rsIngresoDetalle as $item)
        {
           // $item->ingd_eliminar = '<button type="button" class="btn btn-danger btn-xs btn_eliminar_detalle" data-id="'.$item->ingd_id.'" data-msg="Desea eliminar producto: '.$item->prod_nombre.'"><i class="glyphicon glyphicon-remove"></i></button>';
        }

       	$datos = [
       				'data' => $rsIngresoDetalle,
       				'rows' => $rows
       			 ];

        return $datos;         
    }

    public function guardarIngreso()
    {
        if($_POST['ing_id'] != '')
        {
            $dataUpdate = [
                            'ing_proveedor_id'  => $_POST['proveedor'],
                            'ing_fecha'         => $_POST['fecha_ingreso'],
                            'ing_observaciones' => $_POST['observacion'],
                            'ing_almacen_id'    => $_POST['almacen']
                          ];
            $this->db->where('ing_id', $_POST['ing_id']);
            $this->db->update('ingresos', $dataUpdate); 
            /*si ha cambiado de almacen tbn se cambiará de almacen a los ejemplares resgitrados y esten activos*/
            $dataUpdate = [
                            "ejm_almacen_id" => $_POST['almacen']
                          ];
            $this->db->where("ejm_ingreso_id", $_POST['ing_id']);
            $this->db->where("ejm_estado", ST_PRODUCTO_DISPONIBLE);
            $this->db->update("ejemplar", $dataUpdate);

            $ingresoId = $_POST['ing_id'];                        
        } else {
          
            $codigo = rand(100000, 999999);
            $dataInsert = [
                            'ing_proveedor_id'  => $_POST['proveedor'],
                            'ing_fecha'         => $_POST['fecha_ingreso'],
                            'ing_observaciones' => $_POST['observacion'],
                            'ing_almacen_id'    => $_POST['almacen'],
                            'ing_codigo'        => $codigo,
                            'ing_estado'        => ST_ACTIVO
                          ];
            $this->db->insert('ingresos', $dataInsert); 
            $ingresoId = $this->db->insert_id();              
        } 
        
        return $ingresoId;       
    }
    public function guardarProductoIngreso()
    {

        $ingresoId = $_POST['ing_id'];
    	//guardamos el detalle(producto)
    	$dataInsertDetalle = [
    							'ingd_ingreso_id'  => $ingresoId,
    							'ingd_producto_id' => $_POST['producto'],
    							'ingd_cantidad'    => $_POST['cantidad'],
    						 ];
    	$this->db->insert('ingresos_detalle', $dataInsertDetalle);

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

        //ingresamos los ejmplares
        for($i=0;$i<$_POST['cantidad'];$i++)
        {
            $dataInsert = [
                            'ejm_producto_id'   => $_POST['producto'],
                            'ejm_ingreso_id'    => $ingresoId,
                            'ejm_fecha_ingreso' => $_POST['fecha_ingreso'],
                            'ejm_estado'        => ST_PRODUCTO_DISPONIBLE,
                            'ejm_almacen_id'    => $_POST['almacen']
                          ];

            $this->db->insert('ejemplar', $dataInsert);            
        }
              
    	return true;
    }

    public function eliminarDetalleIngreso($idDetalle)
    {
        /*obtenemos el ingreso*/
        $rsDetalle = $this->db->from("ingresos_detalle")
                              ->where("ingd_id", $idDetalle)
                              ->get()
                              ->row();
        /*obtenemos todos los ejemplares que son de ese producto*/
        $rsEjemplares = $this->db->from("ejemplar")
                                 ->where("ejm_producto_id",$rsDetalle->ingd_producto_id)
                                 ->where("ejm_ingreso_id",$rsDetalle->ingd_ingreso_id)
                                 ->where("ejm_estado", ST_PRODUCTO_DISPONIBLE)
                                 ->limit($rsDetalle->ingd_cantidad)
                                 ->get()
                                 ->result();

        /*eliminamos de la bd los ejemplares*/
        foreach($rsEjemplares as $ejemplar)
        {
            $this->db->delete("ejemplar", ["ejm_id"=>$ejemplar->ejm_id]);
        } 

        /*eliminamos el detalle*/
        $this->db->delete("ingresos_detalle",["ingd_id"=>$idDetalle]);

        return true;                        

    }

    public function eliminarIngreso($idIngreso)
    {
        /*primero eliminamos todos los detalles de ese ingreso*/
        $rsDetalle = $this->db->from("ingresos_detalle")
                              ->where("ingd_ingreso_id", $idIngreso)
                              ->get()
                              ->result();
        if($rsDetalle)
        {
            foreach($rsDetalle as $detalle)
            {
                /*eliminamos cada detalle*/
                $this->eliminarDetalleIngreso($detalle->ingd_id);
            }
        } 

        /*eliminamos el ingreso */
        $this->db->delete("ingresos", ['ing_id'=>$idIngreso]);
        return true;                     
    }
}