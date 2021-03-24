<?php

class Proformas_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function select($idproforma = '', $activo = '') {
        
        if($idproforma == '') {
            $rsProformas = $this->db->from('proformas pf')
		    					  ->join('monedas mon','pf.prof_moneda_id=mon.id')
		    					  ->join('clientes c','pf.prof_cliente_id = c.id')
                                  ->get()
                                  ->row();
             return $rsProformas;                     
        }
        $rsproforma = $this->db->from('proformas pf')
		    				 ->join('monedas mon','pf.prof_moneda_id=mon.id')
		    				 ->join('clientes c','pf.prof_cliente_id = c.id')
                             ->where("prof_id", $idproforma)
                             ->get()
                             ->row(); 
        //detallte de la compra
        $rsDetalle = $this->db->from("proforma_detalle")
                              ->where("profd_prof_id", $idproforma)
                              ->get()
                              ->result();

        $rsproforma->detalles = $rsDetalle;                                                                                
        return $rsproforma;                   
    }
    

    public function guardarProforma() {

        //REGISTRO DE CLIENTE API   
         if($_POST['cliente_id'] == 'jApi'){ //REGISTRA CLIENTE RUC
                $this->db->where('ruc',$_POST['ruc_sunat']);
                $dato_sunat_cliente = $this->db->get('clientes')->row();
                if(empty($dato_sunat_cliente->ruc)){
                    $id = $this->clientes_model->obtener_codigo();
                    $data = array(
                        'id' => $id,
                        'ruc' => $_POST['ruc_sunat'],
                        'razon_social' => strtoupper($_POST['razon_sunat']),
                        'domicilio1' => strtoupper($_POST['direccion']),                        
                        'empresa_id' => 1,
                        'activo' => 'activo',
                        'empleado_id_insert' => $this->session->userdata('empleado_id'),
                        'tipo_cliente_id' => 2,
                        'tipo_cliente' => 'Persona Jurídica'
                    );
                    $this->db->insert('clientes',$data);
                    $_POST['cliente_id'] = $id;
                }else{
                    $_POST['cliente_id'] = $dato_sunat_cliente->id; 
                }        
         } else if($_POST['cliente_id'] == 'nApi'){//REGISTRA CLIENTE DNI
                $this->db->where('ruc',$_POST['ruc_sunat']);
                $dato_sunat_cliente = $this->db->get('clientes')->row();
                if(empty($dato_sunat_cliente->ruc)){
                    $id = $this->clientes_model->obtener_codigo();
                    $data = array(
                        'id' => $id,
                        'ruc' => $_POST['ruc_sunat'],
                        'razon_social' => strtoupper($_POST['razon_sunat']),
                        'domicilio1' => strtoupper($_POST['direccion']),                        
                        'empresa_id' => 1,
                        'activo' => 'activo',
                        'empleado_id_insert' => $this->session->userdata('empleado_id'),
                        'tipo_cliente_id' => 1,
                        'tipo_cliente' => 'Persona Natural'
                    );
                    $this->db->insert('clientes',$data);
                    $_POST['cliente_id'] = $id;
                }else{
                    $_POST['cliente_id'] = $dato_sunat_cliente->id; 
                }
         }

        
        if($_POST['proformaId'] == '') {
            $correlativo = $this->maximoConsecutivo();

            //echo $correlativo;exit;
            $dataInsert['prof_correlativo'] = $correlativo+1;            
            $dataInsert['prof_moneda_id'] = $_POST['moneda_id'];
            $dataInsert['prof_cliente_id'] = $_POST['cliente_id'];
            $dataInsert['prof_doc_fecha'] = (new DateTime($_POST['fecha']))->format('Y-m-d h:i:s');            
            $dataInsert['prof_doc_subtotal'] = $_POST['total_gravada'];
            $dataInsert['prof_doc_igv'] = $_POST['total_igv'];
            $dataInsert['prof_doc_total'] = $_POST['total_a_pagar'];
            $dataInsert['prof_doc_observacion'] = $_POST['observaciones'];
            $dataInsert['prof_estado'] = ST_ACTIVO;
            $dataInsert['prof_direccion'] = $_POST['direccion'];
            $dataInsert['prof_orden_compra'] = $_POST['orden_compra'];
            $dataInsert['prof_nguia_remision'] = $_POST['nguia_remision'];
            $dataInsert['prof_procesoestado_id'] = $_POST['proceso_estado'];
            $dataInsert['prof_empleado_id'] = $this->session->userdata('empleado_id');
            $dataInsert['prof_almacen_id'] = $this->session->userdata('almacen_id');
            $this->db->insert("proformas", $dataInsert);
            $idProforma = $this->db->insert_id();

        } else {
            $dataUpdate['prof_moneda_id'] = $_POST['moneda_id'];
            $dataUpdate['prof_cliente_id'] = $_POST['cliente_id'];
            $dataUpdate['prof_doc_fecha'] = (new DateTime($_POST['fecha']))->format('Y-m-d h:i:s');           
            $dataUpdate['prof_doc_subtotal'] = $_POST['total_gravada'];
            $dataUpdate['prof_doc_igv'] = $_POST['total_igv'];
            $dataUpdate['prof_doc_total'] = $_POST['total_a_pagar'];
            $dataUpdate['prof_doc_observacion'] = $_POST['observaciones'];
            $dataUpdate['prof_direccion'] = $_POST['direccion'];
            $dataUpdate['prof_orden_compra'] = $_POST['orden_compra'];
            $dataUpdate['prof_nguia_remision'] = $_POST['nguia_remision'];
            $dataUpdate['prof_procesoestado_id'] = $_POST['proceso_estado'];

            $this->db->where("prof_id", $_POST['proformaId']);
            $this->db->update("proformas", $dataUpdate);
            $idProforma = $_POST['proformaId'];             
        }    

        //si tiene registrado detalles lo eliminamos y lo volvemos a ingresar
        $rsDetalle = $this->db->from("proforma_detalle")
                              ->where("profd_prof_id", $idProforma)
                              ->get()
                              ->result();
        if(count($rsDetalle)>0)
        {
            //eliminamos los detalle para volver a ingresar
            $this->db->delete("proforma_detalle",["profd_prof_id"=>$idProforma]);
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
                                    "profd_descripcion"     => $result->prod_nombre,
                                    "profd_prod_id"     => $_POST['item_id'][$i],
                                    "profd_cantidad"        => $_POST['cantidad'][$i],
                                    "profd_tipo_igv"        => $_POST['tipo_igv'][$i],
                                    "profd_precio_unitario" => $_POST['importe'][$i],
                                    "profd_precio_unitario" => $_POST['importe'][$i],
                                    "profd_importeCosto"    => $_POST['importeCosto'][$i],
                                    "profd_subtotal"        => $_POST['total'][$i],
                                    "profd_descuento"       => $_POST['descuento'][$i],
                                    "profd_igv"             => $_POST['igv'][$i],
                                    "profd_total"           => $_POST['total'][$i],
                                    "profd_totalVenta"      => $_POST['totalVenta'][$i],
                                    "profd_totalCosto"      => $_POST['totalCosto'][$i],
                                    "profd_prof_id"       => $idProforma,
                                  ];            

            $this->db->insert("proforma_detalle", $dataInsertDetalle);              
        }  
        return $idProforma;                    
    } 

    public function eliminar($idProforma)
    {
    	$this->db->delete('proformas', ['prof_id'=>$idProforma]);
        $this->db->delete('proforma_detalle',['profd_prof_id'=>$idProforma]);
    	return true; 
    } 	
    public function maximoConsecutivo() {
        //obtenemos el maximo consecutivo del las notas
       $select = $this->db->from("proformas")                           
                           ->order_by("prof_id","DESC")                           
                           ->get()
                           ->row();                           

        $rsMayorConsecutivo = $select->prof_correlativo;
        return $rsMayorConsecutivo;
    }



    public function getMainList() {
       
        $select = $this->db->from("proformas as pf")
                           ->join("clientes as cli", "pf.prof_cliente_id=cli.id")
                           ->join("monedas as mon", "pf.prof_moneda_id=mon.id")
                           ->join("proceso_estados pro","pf.prof_procesoestado_id = pro.id")
                           ->where("pf.prof_estado", ST_ACTIVO)
                           ->order_by("pf.prof_id", "desc");

       if($_POST['cliente'] > 0)
        {
            $select->where("pf.prof_cliente_id", $_POST['cliente']);
        }
        if($_POST['fecha'] != '')
        {
            $select->where("pf.prof_doc_fecha", $_POST['fecha']);
        }
        if($_POST['estado'] != '')
        {
            $select->where("pf.prof_procesoestado_id", $_POST['estado']);
        }
            
        /*obtener el total*/
        $selectCount = clone $select;
        $rsCount = $selectCount->get()
                               ->result();
        $rows = count($rsCount);                       

        $rsproformas = $select->limit($_POST['pageSize'], $_POST['skip'])
                            ->get()
                            ->result();  

        foreach($rsproformas as $proforma)
        {
            $proforma->prof_doc_fecha = (new DateTime($proforma->prof_doc_fecha))->format("d/m/Y");
            
            $proforma->btn_ticket = '<a href="'.base_url().'index.php/proformas/descargarPdf_ticket/'.$proforma->prof_id.'" target="_seld" ><img src="'.base_url().'/images/pdf.png" data-id="'.$proforma->prof_id.'" class="descargar-pdf"></a>';
            //boton editar
            $proforma->boton_editar = '<button class="btn btn-primary btn-xs btn-editar" data-id="'.$proforma->prof_id.'"><i class="glyphicon glyphicon-pencil"></i></button>';


            // boton para llamar al popap para enviar
            $proforma->btn_popup = '<a href="'.base_url().'index.php/proformas/index/'.$proforma->prof_id.'"><span class="glyphicon glyphicon-file"></a>';
            
            //boton eliminar
            $proforma->boton_eliminar = '';
            if($this->session->userdata('tipo_empleado_id') == 1){
                $proforma->boton_eliminar = '<button class="btn btn btn-danger btn-sm btn-eliminar"   data-id="'.$proforma->prof_id.'" data-msg="Desea eliminar proforma N° '.$proforma->prof_correlativo.'"><i class="glyphicon glyphicon-remove"></i></button>';    
            }     


            
            $proforma->boton_pdf = '<a href="'.base_url().'index.php/proformas/descargarPdf/'.$proforma->prof_id.'" target="_seld" ><img src="'.base_url().'/images/pdf.png" data-id="'.$proforma->prof_id.'" class="descargar-pdf"></a>';            
        }     


       	$datos = [
       				'data' => $rsproformas,
       				'rows' => $rows
       			 ];

        return $datos;    	
    }
 
    public function getMainListDetail()  {

        $select = $this->db->from("proforma_detalle")
                           ->where("profd_prof_id", $_POST['prof_id']);
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