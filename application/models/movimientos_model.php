<?php
class Movimientos_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function select($idMovimiento='')
    {
        if($idMovimiento=='')
        {
            $rsMovimientos = $this->db->from("movimientos")
                                      ->get()
                                      ->result();
            return $rsMovimientos;                     
        }
        $rsMovimiento = $this->db->from("movimientos")
                                 ->where("mov_id", $idMovimiento)
                                 ->get()
                                 ->row();
        return $rsMovimiento; 
    }
    public function getMainList()
    {
        $select = $this->db->from("movimientos")
                           ->where("mov_estado", ST_ACTIVO)
                           ->order_by('mov_id', "desc");

        if($_POST['fecha'] != '')
        {
            $select->where("mov_fecha", (new DateTime($_POST['fecha']))->format('Y-m-d'));
        }
        /*obetemos la cantidad de registros*/
        $selectCount = clone $select;
        $rsCount = $selectCount->get()
                               ->result();
        $rows = count($rsCount);
        /*obtenemo los ingresos*/
        $select->limit($_POST['pageSize'], $_POST['skip']);
        $rsMovimientos = $select->get()
                                ->result();

        foreach($rsMovimientos as $movimiento)
        {
        	//formateamos fecha
        	$movimiento->mov_fecha = (new DateTime($movimiento->mov_fecha))->format('d/m/Y');
        	$movimiento->mov_editar = "<a class='btn btn-default btn-xs btn_modificar_movimiento' data-id='{$movimiento->mov_id}' data-toggle='modal' data-target='#myModal'><i class='glyphicon glyphicon-pencil'></i></a>";
        	$movimiento->mov_eliminar = "<a class='btn btn-danger btn-xs btn_eliminar_movimiento' data-id='{$movimiento->mov_id}' data-msg='Desea eliminar movimiento: {$movimiento->mov_codigo}'><i class='glyphicon glyphicon-remove'></i></a>";
            /*almacen origen*/
            $rsOrigen = $this->db->from("almacenes")
                                 ->where("alm_id", $movimiento->mov_origen_id)
                                 ->get()
                                 ->row();
            /*almacen destino*/
            $rsDestino = $this->db->from("almacenes")
                                  ->where("alm_id", $movimiento->mov_destino_id)
                                  ->get()
                                  ->row(); 
            $movimiento->origen = $rsOrigen->alm_nombre;                                          
            $movimiento->destino = $rsDestino->alm_nombre;                                          
        }

       	$datos = [
       				'data' => $rsMovimientos,
       				'rows' => $rows
       			 ];

        return $datos;                   	
    }	

    public function getMainListDetail()
    {
    	$mov_id = $_POST['mov_id'];

        $select = $this->db->from("movimientos_detalle as md")
                           ->join("productos as prod", "md.movd_producto_id=prod.prod_id")
                           ->where("md.movd_movimiento_id", $mov_id);
        /**cantidad de registros*/
        $selectCount = clone $select;
        $rsCount =  $selectCount->get()
                                ->result();
        $rows = count($rsCount);
        /*detalles del movimiento*/
        $rsMovimientoDetalle = $select->limit($_POST['pageSize'], $_POST['skip'])
                                      ->get()
                                      ->result();                                          
        foreach($rsMovimientoDetalle as $movimiento)
        {
            $movimiento->movd_eliminar = '<button type="button" class="btn btn-danger btn-xs btn_eliminar_detalle" data-id="'.$movimiento->movd_id.'" data-msg="Desea eliminar producto: '.$movimiento->prod_nombre.'"><i class="glyphicon glyphicon-remove"></i></button>';
        }

       	$datos = [
       				'data' => $rsMovimientoDetalle,
       				'rows' => $rows
       			 ];

        return $datos;         
    }

    public function guardarMovimiento()
    {
        if($_POST['mov_id'] != '')
        {
            $dataUpdate = [
                            'mov_fecha'       => $_POST['fecha_movimiento'],
                            'mov_observacion' => $_POST['observacion'],
                          ];
            $this->db->where('mov_id', $_POST['mov_id']);
            $this->db->update('movimientos', $dataUpdate); 

            $movimientoId = $_POST['mov_id'];                        
        }else
        {
            $codigo = rand(100000, 999999);
            $dataInsert = [
                            'mov_codigo'      => $codigo,
                            'mov_origen_id'   => $_POST['origen'],
                            'mov_destino_id'  => $_POST['destino'],
                            'mov_fecha'       => $_POST['fecha_movimiento'],
                            'mov_observacion' => $_POST['observacion'],
                            'mov_estado'      => ST_ACTIVO
                          ];
            $this->db->insert('movimientos', $dataInsert); 
            $movimientoId = $this->db->insert_id();              
        } 
        
        return $movimientoId;       
    }
    public function guardarProductoMovimiento()
    {

        $movimientoId = $_POST['mov_id'];
        /*verificamos que la cantidad de productos esten en ese almacen*/
        $rsEjemplares = $this->db->from("ejemplar")
                                 ->where("ejm_producto_id", $_POST['producto'])
                                 ->where("ejm_almacen_id", $_POST['origen'])
                                 ->where("ejm_estado", ST_PRODUCTO_DISPONIBLE)
                                 ->limit($_POST['cantidad'])
                                 ->get()
                                 ->result();
        //print_r($rsEjemplares);exit();                         

        if(count($rsEjemplares) < $_POST['cantidad'])
        {
            return false;
        } 

    	//guardamos el detalle(producto)
    	$dataInsertDetalle = [
    							'movd_movimiento_id'  => $movimientoId,
    							'movd_producto_id'    => $_POST['producto'],
    							'movd_cantidad'       => $_POST['cantidad'],
    						 ];
    	$this->db->insert('movimientos_detalle', $dataInsertDetalle);


        //cambiamos a los ejemplares de almacen
        foreach($rsEjemplares as $ejemplar)
        {
            $dataUpdate = [
                            "ejm_almacen_id" => $_POST['destino']
                          ];
            $this->db->where("ejm_id", $ejemplar->ejm_id);
            $this->db->update("ejemplar", $dataUpdate);  

        }
              
    	return true;
    }

    public function eliminarDetalleMovimiento($idDetalle)
    {
        /*obtenemos el detalle del movimiento*/
        $rsDetalle = $this->db->from("movimientos_detalle")
                              ->where("movd_id", $idDetalle)
                              ->get()
                              ->row();

        /*obtenemos el movimiento*/
        $rsMovimiento = $this->db->from("movimientos")
                                 ->where('mov_id', $rsDetalle->movd_movimiento_id)
                                 ->get()
                                 ->row();

        /*obtenemos todos los ejemplares que son de ese producto*/
        $rsEjemplares = $this->db->from("ejemplar")
                                 ->where("ejm_producto_id",$rsDetalle->movd_producto_id)
                                 ->where("ejm_almacen_id", $rsMovimiento->mov_destino_id)
                                 ->where("ejm_estado", ST_PRODUCTO_DISPONIBLE)
                                 ->limit($rsDetalle->movd_cantidad)
                                 ->get()
                                 ->result();

        /*devolvemos a su almacen origen los ejemplares*/
        foreach($rsEjemplares as $ejemplar)
        {
            $dataUpdate = [
                            "ejm_almacen_id" => $rsMovimiento->mov_origen_id
                          ];

            $this->db->where("ejm_id", $ejemplar->ejm_id);
            $this->db->update("ejemplar", $dataUpdate);              
        } 

        /*eliminamos el detalle*/
        $this->db->delete("movimientos_detalle",["movd_id"=>$idDetalle]);

        return true;                        

    }

    public function eliminarMovimiento($idMovimiento)
    {
        /*primero eliminamos todos los detalles de ese ingreso*/
        $rsDetalle = $this->db->from("movimientos_detalle")
                              ->where("movd_movimiento_id", $idMovimiento)
                              ->get()
                              ->result();
        if($rsDetalle)
        {
            foreach($rsDetalle as $detalle)
            {
                /*eliminamos cada detalle*/
                $this->eliminarDetalleMovimiento($detalle->movd_id);
            }
        } 

        /*eliminamos el ingreso */
        $this->db->delete("movimientos", ['mov_id'=>$idMovimiento]);
        return true;                     
    }
}