<?php
class Adelanto_pedido_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function select($id = '', $activo = '')
    {
        $rsNota = $this->db->from("adelanto_pedido as np")
                           ->join("clientes as cli", "np.notap_cliente_id=cli.id")
                           ->where("np.notap_id", $id)
                           ->get()
                           ->row();
        $rsNota->notap_fecha = (new DateTime($rsNota->notap_fecha))->format('d-m-Y'); 
        //obtenemos los detalles
        $rsDetalles = $this->db->from("adelanto_pedido_detalle")
                               ->where("notapd_notap_id", $id)
                               ->get()
                               ->result();
        $rsNota->detalles = $rsDetalles;                                         
        return $rsNota;                   
    }  

    public function guardarNota()
    {
        if($_POST['notaId'] == '')
        {
            $correlativo = $this->maximoConsecutivo();

            $dataInsert['notap_correlativo'] = $correlativo++;
            $dataInsert['notap_fecha'] = (new DateTime($_POST['fecha']))->format('Y-m-d');
            $dataInsert['notap_cliente_id'] = $_POST['cliente_id'];
            $dataInsert['notap_tipo_cambio'] = $_POST['tipo_de_cambio'];
            $dataInsert['notap_cliente_direccion'] = $_POST['direccion'];
            $dataInsert['notap_moneda_id'] = $_POST['moneda_id'];
            $dataInsert['notap_subtotal'] = $_POST['total_gravada'];
            $dataInsert['notap_igv'] = $_POST['total_igv'];
            $dataInsert['notap_total'] = $_POST['total_a_pagar'];
            $dataInsert['notap_observaciones'] = $_POST['observaciones'];
            $dataInsert['notap_estado'] = ST_NOTA_ACTIVA;
            $dataInsert['notap_empleado_insert'] =$this->session->userdata('empleado_id');
            $dataInsert['notap_descontar'] = $_POST['descontar_stock'];
            $this->db->insert("adelanto_pedido", $dataInsert);
            $idNota = $this->db->insert_id();
        }else{
            $dataUpdate['notap_fecha'] = (new DateTime($_POST['fecha']))->format('Y-m-d'); 
            $dataUpdate['notap_cliente_id'] = $_POST['cliente_id'];
            $dataUpdate['notap_tipo_cambio'] = $_POST['tipo_de_cambio']; 
            $dataUpdate['notap_cliente_direccion'] = $_POST['direccion'];  
            $dataUpdate['notap_moneda_id'] = $_POST['moneda_id'];  
            $dataUpdate['notap_subtotal'] = $_POST['total_gravada'];  
            $dataUpdate['notap_igv'] = $_POST['total_igv']; 
            $dataUpdate['notap_total'] = $_POST['total_a_pagar']; 
            $dataUpdate['notap_observaciones'] = $_POST['observaciones'];
            $dataUpdate['notap_descontar'] = $_POST['descontar_stock']; 
            $this->db->where("notap_id", $_POST['notaId']);
            $this->db->update("adelanto_pedido", $dataUpdate);
            $idNota = $_POST['notaId'];   

               /*primero liberamos los prodcutos con sus respectivos ejemplares*/        
                $rsDetalles = $this->db->from("adelanto_pedido_detalle")
                                       ->where("notapd_notap_id", $idNota)
                                       ->get()
                                       ->result();
                
                $i = 0;
          
                foreach($rsDetalles as $item) {
           
                 $this->UpdateEstadoDisponible($item->notapd_producto_id,$item->notapd_cantidad);                  
                                          
                }   

                //exit();        
        }    

        //si tiene registrado detalles lo eliminamos y lo volvemos a ingresar
        $rsDetalle = $this->db->from("adelanto_pedido_detalle")
                              ->where("notapd_notap_id", $idNota)
                              ->get()
                              ->result();
        if(count($rsDetalle)>0)
        {
            //eliminamos los detalle para volver a ingresar
            $this->db->delete("adelanto_pedido_detalle",["notapd_notap_id"=>$idNota]);
        }

        

        //ingresamos los detalle
        $cantidadIngresos = count($_POST['descripcion']);
        for($i=0;$i<$cantidadIngresos;$i++)

        {

            $result = $this->db->from('productos')
                               ->where('prod_id',$_POST['item_id'][$i])
                               ->get()
                               ->row();

            $dataInsertDetalle = [
                                    "notapd_descripcion"     => $result->prod_nombre,
                                    "notapd_producto_id"     => $_POST['item_id'][$i],
                                    "notapd_cantidad"        => $_POST['cantidad'][$i],
                                    "notapd_tipo_igv"        => $_POST['tipo_igv'][$i],
                                    "notapd_precio_unitario" => $_POST['importe'][$i],
                                    "notapd_subtotal"        => $_POST['total'][$i],
                                    "notapd_descuento"       => $_POST['descuento'][$i],
                                    "notapd_igv"             => $_POST['igv'][$i],
                                    "notapd_total"           => $_POST['total'][$i]+$_POST['igv'][$i],
                                    "notapd_notap_id"        => $idNota,
                                  ];

            $this->db->insert("adelanto_pedido_detalle", $dataInsertDetalle);

            /*if($_POST['descontar_stock']==1){
                $this->UpdateEstadoVendido($_POST['item_id'][$i],$_POST['cantidad'][$i]);
            }*/

            $this->UpdateEstadoVendido($_POST['item_id'][$i],$_POST['cantidad'][$i]);
            

        }  
        return $idNota;                    
    }

    public function UpdateEstadoVendido($idproducto,$cantidad) {
        
        $resultados = $this->db->from('ejemplar')
                               ->where('ejm_producto_id',$idproducto)
                               ->where('ejm_estado',ST_PRODUCTO_DISPONIBLE)
                               ->where('ejm_almacen_id',$this->session->userdata('almacen_id'))
                               ->limit($cantidad)
                               ->get()
                               ->result()
                               ;
                              // print_r($idproducto);exit();
        foreach ($resultados as $key => $value) {
            $dataUpdateProducto = [
                        'ejm_estado' => ST_PRODUCTO_VENDIDO
                      ];
            $this->db->where('ejm_id',$value->ejm_id)
                    ->update('ejemplar',$dataUpdateProducto);
        }
        
    } 

     public function UpdateEstadoDisponible($idproducto,$cantidad) {        
        $resultados = $this->db->from('ejemplar')
                               ->where('ejm_producto_id',$idproducto)
                               ->where('ejm_estado',ST_PRODUCTO_VENDIDO)
                               ->where('ejm_almacen_id',$this->session->userdata('almacen_id'))
                               ->limit($cantidad)
                               ->get()
                               ->result();

        foreach ($resultados as $key => $value) {
            $dataUpdateProducto = [
                        'ejm_estado' => ST_PRODUCTO_DISPONIBLE
                      ];
            $this->db->where('ejm_id',$value->ejm_id)
                    ->update('ejemplar',$dataUpdateProducto);
        }
        
    }

    public function eliminar($idProducto)
    {
    	$this->db->delete('productos', ['prod_id'=>$idProducto]);
    	return true; 
    } 	
    public function maximoConsecutivo()
    {
        //obtenemos el maximo consecutivo del las notas
        $select = $this->db->from("adelanto_pedido")
                           ->select_max("notap_correlativo")
                           ->get()
                           ->row();

        $rsMayorConsecutivo = $select->notap_correlativo;
        $rsMayorConsecutivo++;
        return $rsMayorConsecutivo;


    }
    public function getMainList()
    {
        
        $select = $this->db->select('nota.*,CONCAT(em.nombre," ",em.apellido_paterno) as empleado,cli.*,mon.*',FALSE) 
                           ->from("adelanto_pedido as nota")
                           ->join("empleados as em", "em.id=nota.notap_empleado_insert")
                           ->join("clientes as cli", "nota.notap_cliente_id=cli.id")
                           ->join("monedas as mon", "nota.notap_moneda_id=mon.id")        
                           ->where("nota.notap_estado", ST_NOTA_ACTIVA)
                           ->order_by("nota.notap_id", "desc");

       if($_POST['cliente_search'] > 0)
        {
            $select->where("nota.notap_cliente_id", $_POST['cliente_search']);
        }
        if($_POST['correlativo_search'] != '')
        {
            $select->where("nota.notap_correlativo", $_POST['correlativo_search']);
        }
        if($_POST['fecha_search'] != '')
        {
            $select->where("nota.notap_fecha", $_POST['fecha_search']);
        }
        /*obtener el total*/
        $count = clone $select;
        $queryCount = $count->get();
        $rsCount = count($queryCount->result());

        $select->limit($_POST['pageSize'], $_POST['skip']);
        $query = $select->get();
        $rsNotas = $query->result();  

        foreach($rsNotas as $nota)
        {
            $nota->notap_fecha = (new DateTime($nota->notap_fecha))->format("d/m/Y");
            //boton editar
            $nota->boton_editar = '<button class="btn btn-primary btn-sm btn-editar" data-id="'.$nota->notap_id.'"><i class="glyphicon glyphicon-pencil"></i></button>';
            $nota->boton_eliminar = '<button class="btn btn btn-danger btn-sm btn-eliminar"   data-id="'.$nota->notap_id.'"><i class="glyphicon glyphicon-remove"></i></button>';
            $nota->boton_pdf = '<a href="'.base_url().'index.php/adelanto_pedido/decargarPdf/'.$nota->notap_id.'" target="_seld" ><img src="'.base_url().'/images/pdf.png" data-id="'.$nota->notap_id.'" class="descargar-pdf"></a>';
        }      


       	$datos = [
       				'data' => $rsNotas,
       				'rows' => $rsCount
       			 ];

        return $datos;    	
    }

    public function getMainListDetail()
    {

        $select = $this->db->from("adelanto_pedido_detalle")
                           ->where("notapd_notap_id", $_POST['notap_id']);
        //cantidad de registros
        $selectCount = clone $select;                               
        $rsCount = $selectCount->get()
                               ->row();
        $rsCount = count($rsCount);
        
        $rsDetalle = $select->limit($_POST['pageSize'], $_POST['skip'])
                            ->get()
                            ->result();                       
        $datos = [
                'data' => $rsDetalle,
                'rows' => $rsCount
             ];

        return $datos;       
    }
}