<?php
class Compras_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();

    }

    public function select($idCompra = '', $activo = '')
    {
        if($idCompra == '')
        {
            $rsCompras = $this->db->from("compras as comp")
                                  ->join("monedas as mon", "comp.comp_moneda_id=mon.id")
                                  ->join("proveedores as prov", "comp.comp_proveedor_id=prov.id","left")
                                  ->get()
                                  ->row();
             return $rsCompras;                     
        }
        $rsCompra = $this->db->from("compras as comp")
                             ->join("monedas as mon", "comp.comp_moneda_id=mon.id")
                             ->join("proveedores as prov", "comp.comp_proveedor_id=prov.prov_id","left")
                             ->where("comp_id", $idCompra)
                             ->get()
                             ->row(); 
        //detallte de la compra
        $rsDetalle = $this->db->from("compras_detalle")
                              ->where("compd_compra_id", $idCompra)
                              ->get()
                              ->result();

        $rsCompra->detalles = $rsDetalle;                                                                                
        return $rsCompra;                   
    }  

    public function guardarCompra()
    {
         $this->db->where('alm_id',$_POST['almacen_mov']);
         $alm = $this->db->get('almacenes')->row();

        if($_POST['compraId'] == '')
        {
            $correlativo = $this->maximoConsecutivo();

            $dataInsert['comp_correlativo'] = $correlativo++;
            $dataInsert['comp_moneda_id'] = $_POST['moneda_id'];
            $dataInsert['comp_proveedor_id'] = $_POST['proveedor_id'];
            $dataInsert['comp_doc_fecha'] = (new DateTime($_POST['fecha']))->format('Y-m-d');
             $dataInsert['comp_doc_fechav'] = (new DateTime($_POST['fecha']))->format('Y-m-d');
            $dataInsert['comp_doc_serie'] = strtoupper($_POST['serie']);
            $dataInsert['comp_doc_numero'] = $_POST['numero'];
            $dataInsert['comp_tipo_documento'] = $_POST['tipo_documento'];
            $dataInsert['comp_doc_subtotal'] = $_POST['total_gravada'];
            $dataInsert['comp_doc_igv'] = $_POST['total_igv'];
            $dataInsert['comp_doc_total'] = $_POST['total_a_pagar'];
            $dataInsert['comp_doc_observacion'] = $_POST['observaciones'];
            $dataInsert['comp_estado'] = ST_ACTIVO;
            $dataInsert['comp_almacen_id'] = $this->session->userdata("almacen_id");

            if($_POST["tipo_ingreso"]=="Movimiento"){
              $dataInsert['comp_tipo_ingreso'] = $_POST['tipo_ingreso'];
              $dataInsert['comp_mov'] = $alm->alm_nombre." - ".$this->session->userdata("almacen_nom");
              $dataInsert['comp_almacen_mov'] = $_POST['almacen_mov'];
              $dataInsert['comp_proveedor'] = $alm->alm_nombre;
            }else{
              $dataInsert['comp_tipo_ingreso'] = $_POST['tipo_ingreso'];
              $dataInsert['comp_proveedor'] = $_POST['proveedor'];
            }
            
            $this->db->insert("compras", $dataInsert);
            $idCompra = $this->db->insert_id();

        } else {
            $dataUpdate['comp_moneda_id'] = $_POST['moneda_id'];
            $dataUpdate['comp_proveedor_id'] = $_POST['proveedor_id'];
            $dataUpdate['comp_doc_fecha'] = (new DateTime($_POST['fecha']))->format('Y-m-d');
            $dataUpdate['comp_doc_fechav'] = (new DateTime($_POST['fecha']))->format('Y-m-d');
            $dataUpdate['comp_doc_serie'] = strtoupper($_POST['serie']);
            $dataUpdate['comp_doc_numero'] = $_POST['numero'];
            $dataUpdate['comp_tipo_documento'] = $_POST['tipo_documento'];
            $dataUpdate['comp_doc_subtotal'] = $_POST['total_gravada'];
            $dataUpdate['comp_doc_igv'] = $_POST['total_igv'];
            $dataUpdate['comp_doc_total'] = $_POST['total_a_pagar'];
            $dataUpdate['comp_doc_observacion'] = $_POST['observaciones'];
            $dataUpdate['comp_almacen_id'] = $this->session->userdata("almacen_id");

            if($_POST["tipo_ingreso"]=="Movimiento"){
              $dataUpdate['comp_tipo_ingreso'] = $_POST['tipo_ingreso'];
              $dataInsert['comp_mov'] = $alm->alm_nombre." - ".$this->session->userdata("almacen_nom");
              $dataUpdate['comp_almacen_mov'] = $_POST['almacen_mov'];
              $dataUpdate['comp_proveedor'] = $alm->alm_nombre;
            }else{
              $dataUpdate['comp_tipo_ingreso'] = $_POST['tipo_ingreso'];
              $dataUpdate['comp_proveedor'] = $_POST['proveedor'];
            }
            
            
            $idCompra = $_POST['compraId'];

            //si tiene registrado detalles lo eliminamos y lo volvemos a ingresar
             $rsDetalle = $this->db->from("compras_detalle")
                              ->where("compd_compra_id", $idCompra)
                              ->get()
                              ->result();

             
            /*eliminamos del stock los productos para volver a ingresar los nuevos*/
            /*if($_POST["tipo_ingreso"]!="Movimiento"){
               /////SOLO SI FUE UN MOVIMIENTO
               $this->db->where("comp_id", $_POST['compraId']);
               $comp = $this->db->get('compras')->row();
               if($comp->comp_tipo_ingreso=="Movimiento"){
                  foreach($rsDetalle as $item) {   
                   $this->UpdateEstadoDisponible($item->compd_producto_id,$item->compd_cantidad,$comp->comp_almacen_mov);  
                  } 
               }else{
                  $this->quitarStock($idCompra);
               }
               
            }else{
              foreach($rsDetalle as $item) {   
                 $this->UpdateEstadoDisponible($item->compd_producto_id,$item->compd_cantidad,$_POST['almacen_mov']);  
               } 
            }*/

            $this->db->where("comp_id", $_POST['compraId']);
            $this->db->update("compras", $dataUpdate);
                        
        }    

        

        

        if(count($rsDetalle)>0)
        {
            //eliminamos los detalle para volver a ingresar
            $this->db->delete("compras_detalle",["compd_compra_id"=>$idCompra]);
        }
        //ingresamos los detalle
        $cantidadIngresos = count($_POST['descripcion']);
        for($i=0;$i<$cantidadIngresos;$i++)
        {

          $this->db->where('prod_id',$_POST['item_id'][$i]);
          $dato_prod = $this->db->get('productos')->row();

            $dataInsertDetalle = [
                                    "compd_descripcion"     => $dato_prod->prod_nombre,
                                    "compd_producto_id"     => $_POST['item_id'][$i],
                                    "compd_cantidad"        => $_POST['cantidad'][$i],
                                    "compd_tipo_igv"        => $_POST['tipo_igv'][$i],
                                    "compd_precio_unitario" => $_POST['importe'][$i],
                                    "compd_subtotal"        => $_POST['total'][$i],
                                    "compd_descuento"       => $_POST['descuento'][$i],
                                    "compd_igv"             => $_POST['igv'][$i],
                                    "compd_total"           => $_POST['total'][$i]+$_POST['igv'][$i],
                                    "compd_compra_id"       => $idCompra,
                                  ];

            $this->db->insert("compras_detalle", $dataInsertDetalle); 

            //ingresamos a stock los productos ingresados
            

            /*$this->db->where('ejm_producto_id',$_POST['item_id'][$i]);
            $this->db->where('ejm_almacen_id',$_POST['almacen_mov']);
            $ejemplar_producto = $this->db->get('ejemplar')->result();*/

            if($dato_prod->prod_tipo==1){
              if($_POST["tipo_ingreso"]=="Movimiento"){
                 

                 $stock = $this->getStockProductos($_POST['item_id'][$i],$this->session->userdata("almacen_id"));
                 $nueva_cantidad = floatval($stock)+floatval($_POST['cantidad'][$i]);
              
                 $kardex = array(
                  'k_fecha' => date('Y-m-d'),
                  'k_almacen' => $this->session->userdata("almacen_id"),
                  'k_tipo' => 1,
                  'k_operacion_id' => $idCompra,
                  'k_serie' => strtoupper($_POST['serie']).'-'.$_POST['numero'],
                  'k_concepto' => $_POST["tipo_ingreso"].' Ingreso',     
                  'k_producto' => $_POST['item_id'][$i],
                  'k_ecantidad' => $_POST['cantidad'][$i],
                  'k_excantidad' => $nueva_cantidad
                                    
                 );

                 $this->db->insert('kardex', $kardex);

                 ///// INSERTAR SALIDA DEL ALMACEN DE ORIGEN
                 $stock = $this->getStockProductos($_POST['item_id'][$i],$_POST['almacen_mov']);
                 $nueva_cantidad = floatval($stock)-floatval($_POST['cantidad'][$i]);

                 $kardex = array(
                  'k_fecha' => date('Y-m-d'),
                  'k_almacen' => $_POST['almacen_mov'],
                  'k_tipo' => 1,
                  'k_operacion_id' => $idCompra,
                  'k_serie' => strtoupper($_POST['serie']).'-'.$_POST['numero'],
                  'k_concepto' => $_POST["tipo_ingreso"].' Salida',     
                  'k_producto' => $_POST['item_id'][$i],
                  'k_scantidad' => $_POST['cantidad'][$i],
                  'k_excantidad' => $nueva_cantidad
                                    
                 );

                 $this->db->insert('kardex', $kardex);

              }else{
                 
                 $stock = $this->getStockProductos($_POST['item_id'][$i],$this->session->userdata("almacen_id"));
                 $nueva_cantidad = floatval($stock)+floatval($_POST['cantidad'][$i]);

                 $kardex = array(
                  'k_fecha' => date('Y-m-d'),
                  'k_almacen' => $this->session->userdata("almacen_id"),
                  'k_tipo' => 1,
                  'k_operacion_id' => $idCompra,
                  'k_serie' => strtoupper($_POST['serie']).'-'.$_POST['numero'],
                  'k_concepto' => $_POST["tipo_ingreso"],     
                  'k_producto' => $_POST['item_id'][$i],
                  'k_ecantidad' => $_POST['cantidad'][$i],
                  'k_excantidad' => $nueva_cantidad,
                                   
                 );

                 $this->db->insert('kardex', $kardex);

              }
                
            }          
        }  
        return $idCompra;                    
    } 

    public function getStockProductos($id_producto,$id_almacen){
       $this->db->where('k_producto',$id_producto);
       $this->db->where('k_almacen',$id_almacen);
       $this->db->order_by('k_id','DESC');
       $result = $this->db->get('kardex')->row();

       return $result->k_excantidad;
    }
 
    ////// SOLO PARA MOVIMIENTO
    public function UpdateEstadoDisponible($idproducto,$cantidad,$almacen) {        
        $resultados = $this->db->from('ejemplar')
                               ->where('ejm_producto_id',$idproducto)
                               ->where('ejm_estado',ST_PRODUCTO_DISPONIBLE)
                               ->where('ejm_almacen_id',$this->session->userdata("almacen_id"))
                               ->limit($cantidad)
                               ->get()
                               ->result();

        foreach ($resultados as $key => $value) {
            $dataUpdateProducto = [
                        'ejm_almacen_id' => $almacen
                      ];
            $this->db->where('ejm_id',$value->ejm_id)
                    ->update('ejemplar',$dataUpdateProducto);
        }
        
    }

    public function eliminar($idCompra)
    {   
        $this->db->where('comp_id',$idCompra);
        $comp = $this->db->get('compras')->row();

        $this->db->where('compd_compra_id',$idCompra);
        $detalles = $this->db->get('compras_detalle')->result();

        if($comp->comp_tipo_ingreso!='Movimiento'){
                         //$this->quitarStock($idCompra); 

                   foreach($detalles as $det){
                         $stock = $this->getStockProductos($det->compd_producto_id,$this->session->userdata("almacen_id"));
                         $nueva_cantidad = floatval($stock)-floatval($det->compd_cantidad);

                         $concepto = 'Eliminación Comprobante';
                         $kardex = array(
                          'k_fecha' => date('Y-m-d'),
                          'k_almacen' => $this->session->userdata("almacen_id"),
                          'k_tipo' => 1,
                          'k_operacion_id' => $idCompra,
                          'k_serie' => $comp->comp_doc_serie.'-'.$comp->comp_doc_numero,
                          'k_concepto' => $concepto,     
                          'k_producto' => $det->compd_producto_id,
                          'k_scantidad' => $det->compd_cantidad,
                          'k_excantidad' => $nueva_cantidad,
                                           
                         );

                         $this->db->insert('kardex', $kardex);
                   }      
                         
        }else{
             

           
              /*foreach($rsDetalle as $item) {   
                 $this->UpdateEstadoDisponible($item->compd_producto_id,$item->compd_cantidad,$comp->comp_almacen_mov);  
               } */

               foreach($detalles as $det){
                         $stock = $this->getStockProductos($det->compd_producto_id,$this->session->userdata("almacen_id"));
                         $nueva_cantidad = floatval($stock)-floatval($det->compd_cantidad);

                         $concepto = 'Movimiento de Almacen';
                         $kardex = array(
                          'k_fecha' => date('Y-m-d'),
                          'k_almacen' => $this->session->userdata("almacen_id"),
                          'k_tipo' => 1,
                          'k_operacion_id' => $idCompra,
                          'k_serie' => $comp->comp_doc_serie.'-'.$comp->comp_doc_numero,
                          'k_concepto' => $concepto,     
                          'k_producto' => $det->compd_producto_id,
                          'k_scantidad' => $det->compd_cantidad,
                          'k_excantidad' => $nueva_cantidad,
                                           
                         );

                         $this->db->insert('kardex', $kardex);
                }   

                foreach($detalles as $det){
                         $stock = $this->getStockProductos($det->compd_producto_id,$comp->comp_almacen_mov);
                         $nueva_cantidad = floatval($stock)+floatval($det->compd_cantidad);

                         $concepto = 'Movimiento de Almacen';
                         $kardex = array(
                          'k_fecha' => date('Y-m-d'),
                          'k_almacen' => $comp->comp_almacen_mov,
                          'k_tipo' => 1,
                          'k_operacion_id' => $idCompra,
                          'k_serie' => $comp->comp_doc_serie.'-'.$comp->comp_doc_numero,
                          'k_concepto' => $concepto,     
                          'k_producto' => $det->compd_producto_id,
                          'k_scantidad' => $det->compd_cantidad,
                          'k_excantidad' => $nueva_cantidad,
                                           
                         );

                         $this->db->insert('kardex', $kardex);
                }    
           
        }

        $this->db->delete('compras', ['comp_id'=>$idCompra]);
        $this->db->delete('compras_detalle',['compd_compra_id'=>$idCompra]);
        
      return true; 
    }   

   
    public function maximoConsecutivo()
    {
        //obtenemos el maximo consecutivo del las notas
        $select = $this->db->from("compras")
                           ->select_max("comp_correlativo")
                           ->get()
                           ->row();

        $rsMayorConsecutivo = $select->comp_correlativo;
        $rsMayorConsecutivo++;
        return $rsMayorConsecutivo;

    }
    public function getMainList()
    {
        
        $select = $this->db->from("compras as comp")
                           ->join("proveedores as prov", "comp.comp_proveedor_id=prov.prov_id","left")
                           ->join("monedas as mon", "comp.comp_moneda_id=mon.id")        
                           ->where("comp.comp_estado", ST_ACTIVO)
                           ->where("comp.comp_almacen_id", $_POST['almacen'])
                           ->order_by("comp.comp_id", "desc");

       if($_POST['proveedor'] > 0)
        {
            $select->where("comp.comp_proveedor_id", $_POST['proveedor']);
        }
        if($_POST['fecha_search'] != '')
        {
            $select->where("comp.comp_fecha", $_POST['fecha_search']);
        }
        if($_POST['serie_numero'] != '')
        {
            $select->where("CONCAT_WS('-',comp.comp_doc_serie,comp.comp_doc_numero)", $_POST['serie_numero']);
        }

        /*obtener el total*/
        $selectCount = clone $select;
        $rsCount = $selectCount->get()
                               ->result();
        $rows = count($rsCount);                       

        $rsCompras = $select->limit($_POST['pageSize'], $_POST['skip'])
                            ->get()
                            ->result();  

        foreach($rsCompras as $compra)
        {
            $compra->comp_fecha = (new DateTime($compra->comp_fecha))->format("d/m/Y");

            if($this->session->userdata("almacen_id")==$_POST['almacen']){
              //boton editar
              $compra->boton_editar = '<button class="btn btn-primary btn-xs btn-editar" data-id="'.$compra->comp_id.'"><i class="glyphicon glyphicon-pencil"></i></button>';
              //boton eliminar
              $compra->boton_eliminar = '<button class="btn btn-danger btn-xs btn-eliminar" data-id="'.$compra->comp_id.'" data-msg="Desea eliminar compra N° '.$compra->comp_correlativo.'"><i class="glyphicon glyphicon-remove"></i></button>';
            }else{
              //boton editar
              $compra->boton_editar = '';
              //boton eliminar
              $compra->boton_eliminar = '';
            }
            


            $compra->boton_pdf = '<a href="'.base_url().'index.php/compras/decargarPdf/'.$compra->comp_id.'" target="_seld" ><img src="'.base_url().'/images/pdf.png" data-id="'.$compra->comp_id.'" class="descargar-pdf"></a>';
            $compra->comp_serie_numero = $compra->comp_doc_serie.'-'.$compra->comp_doc_numero;
        }      


        $datos = [
              'data' => $rsCompras,
              'rows' => $rows
             ];

        return $datos;      
    }

    public function getMainListDetail()
    {

        $select = $this->db->from("compras_detalle")
                           ->where("compd_compra_id", $_POST['comp_id']);
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
    public function selectAutocomplete($proveedor)
    {
        $rsProveedores = $this->db->from("proveedores")
                                  ->like("prov_razon_social", $proveedor)
                                  ->or_like("prov_ruc", $proveedor)
                                  ->get()
                                  ->result();
        foreach($rsProveedores as $proveedor)
        {

            $proveedor->label = $proveedor->prov_razon_social;
            $proveedor->value = $proveedor->$proveedor->prov_id;
        }                          
        return $rsProveedores;                         
    }

    public function ingresarStock($idProducto, $cantidad, $idCompra)
    {
      /*$rsAlmacen = $this->db->from("almacenes")
                            ->where('alm_principal', 1)
                            ->get()
                            ->row();*/
      $rsAlmacen = $this->db->from("productos")
                            ->where('prod_id',$idProducto)
                            ->get()
                            ->row();                      

      $id_comp = ($idCompra=='') ? 0 : $idCompra ;
       
      for($i=0; $i<$cantidad;$i++){ 
        $insertEjemplar = [
                            'ejm_producto_id'   => $idProducto,
                            'ejm_compra_id'     => $id_comp,
                            'ejm_fecha_ingreso' => (new DateTime())->format('Y-m-d'),
                            'ejm_almacen_id'    => $this->session->userdata("almacen_id"),
                            'ejm_estado'        => ST_PRODUCTO_DISPONIBLE
                           
                           ];

        $this->db->insert("ejemplar", $insertEjemplar);   
      }
                                          
    }
    public function quitarStock($idCompra)
    {
      //solo quitaremos de stock a los producto que pertenezacan a esa compra
      $this->db->where("ejm_compra_id", $idCompra);
      $this->db->where("ejm_estado", ST_PRODUCTO_DISPONIBLE);
      $this->db->where('ejm_almacen_id',$this->session->userdata('almacen_id'));
      $this->db->delete("ejemplar");                         
    }

     //////// LE //////////
    public function getCompras($where){
       $this->db->select('c.*,p.prov_ruc,p.prov_razon_social,mo.abrstandar,td.codigo as codigo_doc');
       $this->db->from('compras c');
       $this->db->join('proveedores p','p.prov_id = c.comp_proveedor_id');
       $this->db->join('tipo_documentos td','td.id = c.comp_tipo_documento');
       $this->db->join('monedas mo','mo.id = c.comp_moneda_id');
       $this->db->where($where);
       $result = $this->db->get();
       $json =  $result->result();
       return $json;
    }


}