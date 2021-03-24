<?php
class Guias_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function select($idGuia = '')
    {
        if($idGuia == '')
        {
            $rsGuias = $this->db->from("guias")
                                ->get()
                                ->row();
             return $rsGuias;                     
        }
        $rsGuia = $this->db->select('guia.*')
                           ->from("guias as guia")
                          //->join("comprobante_guias as compg", "guia.id=compg.guia_id")
                           ->where("guia.id", $idGuia)
                           ->get()
                           ->row(); 
        //detallte de la compra
        $rsDetalle = $this->db->from("guia_detalles as guiad")
                              ->join("productos as prod", "prod.prod_id=guiad.producto_id","left")
                              ->join("medida med", "guiad.medida_id=med.medida_id")
                              ->where("guia_id", $idGuia)
                              ->get()
                              ->result();
                              //print_r($rsDetalle);exit();
        $rsGuia->detalles = $rsDetalle;                                                                                
        return $rsGuia;                   
    }  

    public function guardarGuia()
    {
      //GUARDAMOS SI ES CLIENTE NUBE 01-03-2021
      if(!is_int($_POST['cliente_id']))
         $_POST['cliente_id'] = $this->clientes_model->guardarClienteNube();

        if($_POST['guiaId'] == '')
        {
            $correlativo = $this->maximoConsecutivo();
            
            $dataInsert['correlativo'] = $correlativo++;            
            $dataInsert['motivo_traslado'] = $_POST['motivo'];
            $dataInsert['modalidad_traslado'] = $_POST['modalidad'];
            $dataInsert['comprobante_id'] = $_POST['facturaId']; 
            $dataInsert['numero_factura'] = $_POST['numero_factura'];                       
            $dataInsert['guia_serie'] = strtoupper($_POST['serie']);
            $dataInsert['guia_numero'] = $_POST['numero'];
            $dataInsert['fecha_inicio_traslado'] = (new DateTime($_POST['fecha']))->format('Y-m-d');
            $dataInsert['transporte_documento'] = $_POST['transporte_documento'];
            $dataInsert['transporte_ruc'] = $_POST['transporte_ruc'];
            $dataInsert['transporte_razon_social'] = $_POST['transporte_razon_social'];
            $dataInsert['conductor_documento'] = $_POST['conductor_documento'];
            $dataInsert['conductor_ruc'] = $_POST['conductor_ruc'];
            $dataInsert['conductor_razon_social'] = $_POST['conductor_razon_social'];
            $dataInsert['destinatario_documento'] = $_POST['destinatario_documento'];
            $dataInsert['destinatario_ruc'] = $_POST['destinatario_ruc'];
            $dataInsert['destinatario_razon_social'] = $_POST['destinatario_razon_social'];
            $dataInsert['ubigeo_partida'] = substr($_POST['ubigeo_partida'],0,6);
            $dataInsert['ubigeo_llegada'] = substr($_POST['ubigeo_llegada'],0,6);
            $dataInsert['partida_direccion'] = $_POST['partida_direccion'];
            $dataInsert['partida_localidad'] = $_POST['partida_localidad'];
            $dataInsert['llegada_direccion'] = $_POST['llegada_direccion'];
            $dataInsert['llegada_localidad'] = $_POST['llegada_localidad'];            
            $dataInsert['vehiculo_placa'] = $_POST['vehiculo_placa'];
            $dataInsert['vehiculo_marca'] = $_POST['vehiculo_marca'];
            $dataInsert['vehiculo_licencia'] = $_POST['vehiculo_licencia'];
            $dataInsert['vehiculo_constancia'] = $_POST['vehiculo_constancia'];
            $dataInsert['peso_total'] = $_POST['peso_total'];
            $dataInsert['numero_bultos'] = $_POST['numero_bultos'];
            $dataInsert['estado'] = ST_ACTIVO;
            $this->db->insert("guias", $dataInsert);
            $idGuia = $this->db->insert_id();

            unset($dataInsert);

            /*$dataInsert["comprobante_id"] = $_POST['facturaId'];
            $dataInsert["guia_id"] = $idGuia;
            $this->db->insert("comprobante_guias", $dataInsert);*/
            
            if($_POST['facturaId']!=''){
              $serie_guia = strtoupper($_POST['serie']).'-'.$_POST['numero'];
              $this->insertar_facturaxguia($_POST['facturaId'],$serie_guia);  
            }
            

        }else{
            //$dataUpdate['correlativo'] = $correlativo++;            
            $dataUpdate['comprobante_id'] = $_POST['facturaId']; 
            $dataUpdate['numero_factura'] = $_POST['numero_factura'];
            $dataUpdate['guia_serie'] = strtoupper($_POST['serie']);
            $dataUpdate['guia_numero'] = $_POST['numero'];
            $dataUpdate['motivo_traslado'] = $_POST['motivo'];
            $dataUpdate['modalidad_traslado'] = $_POST['modalidad'];
            $dataUpdate['fecha_inicio_traslado'] = (new DateTime($_POST['fecha']))->format('Y-m-d');
            $dataUpdate['transporte_documento'] = $_POST['transporte_documento'];
            $dataUpdate['transporte_ruc'] = $_POST['transporte_ruc'];
            $dataUpdate['transporte_razon_social'] = $_POST['transporte_razon_social'];
            $dataUpdate['conductor_documento'] = $_POST['conductor_documento'];
            $dataUpdate['conductor_ruc'] = $_POST['conductor_ruc'];
            $dataUpdate['conductor_razon_social'] = $_POST['conductor_razon_social'];
            $dataUpdate['destinatario_documento'] = $_POST['destinatario_documento'];
            $dataUpdate['destinatario_ruc'] = $_POST['destinatario_ruc'];
            $dataUpdate['destinatario_razon_social'] = $_POST['destinatario_razon_social'];
            $dataUpdate['ubigeo_partida'] = substr($_POST['ubigeo_partida'],0,6);
            $dataUpdate['ubigeo_llegada'] = substr($_POST['ubigeo_llegada'],0,6);
            $dataUpdate['partida_direccion'] = $_POST['partida_direccion'];
            $dataUpdate['partida_localidad'] = $_POST['partida_localidad'];
            $dataUpdate['llegada_direccion'] = $_POST['llegada_direccion'];
            $dataUpdate['llegada_localidad'] = $_POST['llegada_localidad'];            
            $dataUpdate['vehiculo_placa'] = $_POST['vehiculo_placa'];
            $dataUpdate['vehiculo_marca'] = $_POST['vehiculo_marca'];
            $dataUpdate['vehiculo_licencia'] = $_POST['vehiculo_licencia'];
            $dataUpdate['vehiculo_constancia'] = $_POST['vehiculo_constancia'];
            $dataUpdate['peso_total'] = $_POST['peso_total'];
            $dataUpdate['numero_bultos'] = $_POST['numero_bultos'];
            $dataUpdate['estado'] = ST_ACTIVO;
            $this->db->where("id", $_POST['guiaId']);
            $this->db->update("guias", $dataUpdate);
            $idGuia = $_POST['guiaId']; 
            /*eliminamos los registros de la tabla comprobante_guias*/
            $this->db->where("comprobante_id", $_POST['facturaId']);
            $this->db->where("guia_id", $idGuia);
            $this->db->delete("comprobante_guias");
            /*ingresamos los registros de la tabla comprobante_guias*/
            $dataInsert["comprobante_id"] = $_POST['facturaId'];
            $dataInsert["guia_id"] = $idGuia;
            $this->db->insert("comprobante_guias", $dataInsert);

            $this->actualizar_facturaxguia($_POST['facturaId'],$_POST['guiaId']);  


             if($_POST['descontar_stock']==on){                    
                $rsDetalles = $this->db->from("guia_detalles")
                                       ->where("guia_id", $idGuia)
                                       ->get()
                                       ->result();
                $i = 0;
                foreach($rsDetalles as $item) {         
                  $result = $this->db->from('productos')
                               ->where('prod_id',$item->producto_id)
                               ->get()
                               ->row();        

                                $concepto = 'MODIFICAR GUIA - RESTAURAR';
                                $this->productos_model->salidaStock($item->producto_id,$item->cantidad,$concepto,$idGuia,strtoupper($_POST['serie']),$_POST['numero']);                                                                                           
                } 
            }           
        }    

        //si tiene registrado detalles lo eliminamos y lo volvemos a ingresar
        $rsDetalle = $this->db->from("guia_detalles")
                              ->where("guia_id", $idGuia)
                              ->get()
                              ->result();
        if(count($rsDetalle)>0)
        {
            //eliminamos los detalle para volver a ingresar
            $this->db->delete("guia_detalles",["guia_id"=>$idGuia]);
        }
        //ingresamos los detalle

        $productosId = $_POST['item_id'];
        $cantidades  = $_POST['cantidad'];
        $descripcion = $_POST['descripcion'];
        $precios = $_POST['precio'];
        $codigo = $_POST['codigo'];
        $medida = $_POST['medida'];

        $i = 0;
        foreach($productosId as $index => $item)
        {
           $rsProducto = $this->db->from("productos")
                                 ->where("prod_id", $item)
                                 ->get()
                                 ->row();

            if($precios[$index]==''){
              $precios[$index] = 0;
               }

          if($productosId[$i]!=0){
           
            $dataInsertDetallee ['producto_id'] =$item;
            $dataInsertDetallee['descripcion'] =$rsProducto->prod_nombre;
            $dataInsertDetallee ['codigo'] =$rsProducto->prod_codigo;
            $dataInsertDetallee ['medida_id']= $rsProducto->prod_medida_id;
            } 
          else{       
            $dataInsertDetallee ['producto_id'] =0;
            $dataInsertDetallee['descripcion'] =strtoupper($descripcion[$i]);
            $dataInsertDetallee ['codigo'] =0;
            $dataInsertDetallee ['medida_id'] =$medida[$i];
            }   

            $dataInsertDetallee ['cantidad'] =$cantidades[$index];
            $dataInsertDetallee ['precio'] =$precios[$index];
            $dataInsertDetallee ['guia_id'] =$idGuia;
           
   
       //   $dataInsertDetalle = [
         //                           "producto_id"  => $item,
          //                          "descripcion"  => $rsProducto->prod_nombre,
           //                         "codigo"       => $rsProducto->prod_codigo,
            //                        "cantidad"     => $cantidades[$index],
             //                         "precio"     => $precios[$index],
              //                      "guia_id"      => $idGuia,
          //                        ];   
          $this->db->insert("guia_detalles", $dataInsertDetallee);   

          //DESCONTAR STOCK
          if($_POST['descontar_stock']==on){
              $concepto = 'MOVIMIENTO GUIA SALIDA';
              $this->productos_model->salidaStock($item, $cantidades[$index], $concepto, $idGuia,strtoupper($_POST['serie']),$_POST['numero']);
          }
          $i++;    
        }
        return $idGuia;                    
    }

    public function insertar_facturaxguia($factura_id,$guia){

      $this->db->set("numero_guia_remision",$guia)
               ->where("id",$factura_id)
               ->update("comprobantes");
    } 

    public function actualizar_facturaxguia($factura_id='',$guia_id){

      $this->db->set("numero_guia_remision","")
               ->where("numero_guia_remision",$guia_id)
               ->update("comprobantes");

      if($factura_id!=''){
        $this->db->set("numero_guia_remision",$guia_id)
               ->where("id",$factura_id)
               ->update("comprobantes");
      }         

    } 

    public function getMotivosTraslado()
    {
        $rsMotivos = $this->db->from("guia_motivos_traslado")
                              ->get()
                              ->result();
        return $rsMotivos;                      
    }
    public function getModalidadTraslado()
    {
        $rsModalidad = $this->db->from("guia_modalidad_traslado")
                                ->get()
                                ->result();
        return $rsModalidad;
    }
    public function eliminar($idGuia)
    {
    	$dataUpdate = [
    					'estado' => ST_ELIMINADO
    				  ];
    	$this->db->where("id", $idGuia);			  
    	$this->db->update("guias", $dataUpdate);			  
      return true; 
    }   
    public function maximoConsecutivo()
    {
        //obtenemos el maximo consecutivo del las notas
        $select = $this->db->from("guias")
                           ->where("estado", ST_ACTIVO)
                           ->select_max("correlativo")
                           ->get()
                           ->row();

        $rsMayorConsecutivo = $select->correlativo;
        $rsMayorConsecutivo++;
        return $rsMayorConsecutivo;

    }

    //para coprobante/nuevo de forma individual
    public function selecMaximoNumero($empresa_id, $serie){
        
        $sql = "SELECT MAX(CAST(guia_numero AS UNSIGNED)) as numero FROM guias WHERE guia_serie = '".$serie."'";
        $query = $this->db->query($sql);
        $rowNumero = $query->row_array();        

        if($rowNumero['numero'] == null){
            //BUSQUEDA DE NUMERO 12-01-2021
            $rsNumero = $this->db->from('ser_nums')
                                 ->where('empresa_id',$empresa_id)                                 
                                 ->where('serie',$serie)
                                 ->where('estado',ST_ACTIVO)
                                 ->get()
                                 ->row();
            $rowNumero['numero'] = ($rsNumero->numero == null) ? 1 : $rsNumero->numero;
        }else{
            $rowNumero['numero']++;
        }
        return $rowNumero;        
    }    

    public function buscarComprobanteGuia()
    {
        $documento = $_POST['documento'];
      
         ////BUSCAR FACTURA/BOLETA
        $rsDocumento = $this->db->select("comp.id as comp_id, comp.direccion_cliente as direccion_cliente,cli.ruc as cli_ruc, cli.razon_social as cli_razon")
                              ->from("comprobantes as comp")
                              ->join("clientes as cli", "comp.cliente_id=cli.id")
                              ->where_in("tipo_documento_id", [1,3])//solo factura/boleta
                              ->where("concat_ws('-', comp.serie, comp.numero) =", trim($documento))
                              ->get()
                              ->row();                     

        $rsDetalle = $this->db->select("itm.producto_id as prod_id, itm.descripcion, itm.cantidad,prod.prod_codigo,itm.importe")
                              ->from("items as itm")
                              ->join("productos as prod", "itm.producto_id=prod.prod_id","left")
                              ->where("itm.comprobante_id", $rsDocumento->comp_id)
                              ->get()
                              ->result(); 

        ////BUSCAR ADELANTO DE PEDIDO
        /*$rsDocumento = $this->db->select("comp.notap_id as comp_id, comp.notap_cliente_direccion as direccion_cliente,cli.ruc as cli_ruc, cli.razon_social as cli_razon")
                              ->from("adelanto_pedido as comp")
                              ->join("clientes as cli", "comp.notap_cliente_id=cli.id")
                              //->where_in("tipo_documento_id", [1,3])//solo factura/boleta
                              ->where("comp.notap_correlativo",$documento)
                              ->get()
                              ->row();                     

        $rsDetalle = $this->db->select("prod.prod_id, itm.notapd_descripcion as descripcion, itm.notapd_cantidad as cantidad,prod.prod_codigo")
                              ->from("adelanto_pedido_detalle as itm")
                              ->join("productos as prod", "itm.notapd_producto_id=prod.prod_id")
                              ->where("itm.notapd_notap_id", $rsDocumento->comp_id)
                              ->get()
                              ->result();    */                                            

        $rsEmpresa = $this->db->from("empresas")
                              ->where("id", 1)
                              ->get()
                              ->row();                      
        if(!$rsDocumento)
        {
          
          $datos = ['status'=>STATUS_FAIL];
        }else{

          foreach ($rsDetalle as $item) {
            if($item->prod_id==0){
                $datos = ['status'=>11];
                return $datos; 
                exit();
            }
          }
            $datos = [
                        'status' => STATUS_OK,
                        'datos'  => [
                                        'factura_id'                => $rsDocumento->comp_id,
                                        'partida_direccion'         => $rsEmpresa->domicilio_fiscal,
                                        'destinatario_ruc'          => $rsDocumento->cli_ruc,
                                        'destinatario_razon_social' => $rsDocumento->cli_razon,
                                        'llegada_direccion'         => $rsDocumento->direccion_cliente,
                                        'productos'                 => $rsDetalle
                                    ]
                     ];
        } 
        return $datos;                     
    }
    public function getMainList()
    {
        
        $select = $this->db->select("guia.id as guia_id,guia.guia_serie,guia.guia_numero,guia.destinatario_razon_social as destinatario_razon_social, guia.fecha_inicio_traslado as fecha_inicio_traslado, CONCAT(guia.guia_serie,'-',guia.guia_numero) as numero_factura,guia.correlativo as correlativo,motivo.descripcion as descripcion_traslado, guia.llegada_direccion as llegada_direccion,guia.numero_factura as factura,guia.estado_sunat estado_sunat,emp.ruc empresa_ruc,'09' as codigo",false)
        				   ->from("guias as guia")
        				   ->join("guia_motivos_traslado as motivo", "guia.motivo_traslado = motivo.id")
                   ->join("comprobantes com", "guia.comprobante_id = com.id","LEFT")
                   ->join("empresas emp", "com.empresa_id = emp.id","LEFT")
                           ->where("guia.estado", ST_ACTIVO)
                           ->order_by("guia.id", "desc");

       if($_POST['cliente'] != '')
        {
            $select->where("guia.destinatario_razon_social", $_POST['cliente']);
        }
        if($_POST['fecha'] != '')
        {
            $select->where("guia.fecha_inicio_traslado", $_POST['fecha']);
        }
        if($_POST['factura'] != '')
        {
            $select->where("guia.numero_factura", $_POST['factura']);
        }       
        /*obtener el total*/
        $selectCount = clone $select;
        $rsCount = $selectCount->get()
                               ->result();
        $rows = count($rsCount);                       

        $rsGuias = $select->limit($_POST['pageSize'], $_POST['skip'])
                          ->get()
                          ->result();  

        foreach($rsGuias as $guia)
        {
            $guia->fecha = (new DateTime($guia->fecha))->format("d/m/Y");
            if ($guia->estado_sunat == 1){
                $guia->btn_action =  '<div class="btn-group">
                            <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">No Enviado
                                <span class="caret"></span>
                            </button>
                              <ul class="dropdown-menu dropdown-menu-right">
                                <li><a href="#" class="btn-editar" data-id="'.$guia->guia_id.'">Modificar</a></li>';

                $guia->btn_action .= '<li><a class="envGuiaRemision" onclick="send_guiaRemision('.$guia->guia_id.')">Enviar a Sunat</a></li>';
                $guia->btn_action .= '</ul></div>';
            } else if($guia->estado_sunat == 3){

                $guia->btn_action ='<div class="btn-group">
                      <button class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown"> Enviado a Sunat 
                      <span class="caret"></span>
                      </button>';

                $guia->btn_action.='<ul class="dropdown-menu dropdown-menu-right">';
                $guia->btn_action.='<li><a class="_dow_xml" target="_blank"  href="'.RUTA_API.'index.php/Sunat/dowload_xml/'.$guia->empresa_ruc.'/'.$guia->codigo.'/'.$guia->guia_serie.'/'.$guia->guia_numero.'"><span class="glyphicon glyphicon-file esunat"></span> XML</a></li>';
                $guia->btn_action.='<li><a class="_dow_cdr" target="_blank" href="'.RUTA_API.'index.php/Sunat/dowload_cdr/'.$guia->empresa_ruc.'/'.$guia->codigo.'/'.$guia->guia_serie.'/'.$guia->guia_numero.'"><i class="glyphicon glyphicon-list-alt"></i> CDR</a></li>';   


                //$guia->btn_action.='<li><a onclick="send_guiaAnulacion('.$guia->guia_id.')" >X Anular </a></li>';

                $guia->btn_action.='</ul></div>';
            }
            //if($guia->factura==''){
              if(1==1){
                //boton editar
            //$guia->boton_editar = '<button class="btn btn-primary btn-xs btn-editar" data-id="'.$guia->guia_id.'"><i class="glyphicon glyphicon-pencil"></i></button>';
            //boton eliminar
            $guia->boton_eliminar = '<button class="btn btn-danger btn-xs btn-eliminar" data-id="'.$guia->guia_id.'" data-msg="Desea eliminar guia N° '.$guia->correlativo.'"><i class="glyphicon glyphicon-remove"></i></button>';
          }else{
              //boton editar
            $guia->boton_editar = '';
            //boton eliminar
            $guia->boton_eliminar = '';
          }                  
           $guia->boton_pdf = '<a href="'.base_url().'index.php/guias/descargarPdf/'.$guia->guia_id.'" target="_seld" ><img src="'.base_url().'/images/pdf.png" data-id="'.$guia->guia_id.'" class="descargar-pdf"></a>';
            //$guia->boton_pdf = '<a href="'.base_url().'index.php/guias/decargarPdf_matriz/'.$guia->guia_id.'" target="_seld" ><img src="'.base_url().'/images/pdf.png" data-id="'.$guia->guia_id.'" class="descargar-pdf"></a>';
            $guia->btn_ticket = '<a href="'.base_url().'index.php/guias/descargarPdf_ticket/'.$guia->guia_id.'" target="_seld" ><img src="'.base_url().'/images/pdf.png" data-id="'.$guia->guia_id.'" class="descargar-pdf"></a>';
        }      

        $datos = [
              'data' => $rsGuias,
              'rows' => $rows
             ];
        return $datos;      
    }

    public function getMainListDetail()
    {

        $select = $this->db->from("guia_detalles")
                           ->where("guia_id", $_POST['guia_id'])
                           ;
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
    public function selectAutocomplete($cliente)
    {
        $rsClientes = $this->db->from("clientes")
                                  ->like("razon_social", $cliente)
                                  ->or_like("ruc", $cliente)
                                  ->get()
                                  ->result();
        $arrayClientes = [];                          
        foreach($rsClientes as $item)
        {
            $cliente = new stdClass();
            $cliente->label = $item->razon_social;
            $cliente->value = $item->id;
            $arrayClientes[] = $cliente;
        }                          
        return $arrayClientes;                         
    }


    
}