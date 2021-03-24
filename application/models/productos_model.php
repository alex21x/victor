<?php
class Productos_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('compras_model');
    }

    public function select($id = '', $activo = '') {
        if ($id != '') {            
            $datos = $this->db->from('productos pr')                            
                              ->join("categoria cat","pr.prod_categoria_id=cat.cat_id")
                              ->join("medida md","pr.prod_medida_id=md.medida_id")
                              ->where('prod_id',$id)
                              ->where('prod_estado',ST_ACTIVO)
                              ->get()
                              ->row();
                    // obtenemos el stock inicial del producto //
                    /*$this->db->where("ejm_producto_id", $id);
                    $this->db->where("ejm_fecha_ingreso", $datos->prod_fecha);
                    $this->db->where("ejm_almacen_id",$this->session->userdata("almacen_id"));
                    $this->db->from("ejemplar");
                    $countProductos = $this->db->count_all_results();*/

                     $stock = $this->getStockProductos($id,$this->session->userdata("almacen_id"));
                     $datos->prod_stock = $stock;
                    
                      
            
        } else {

            $datos = $this->db->from('productos pr')
                                  ->join("categoria cat","pr.prod_categoria_id=cat.cat_id")
                                  ->join("medida md","pr.prod_medida_id=md.medida_id")
                                  ->where('prod_estado',ST_ACTIVO)
                                  ->get()
                                  ->row();
        }
        return $datos;
    }  

    public function guardar() {   


       //GUARDAR IMAGEN
       $carpeta = 'images/productos/';
       opendir($carpeta);
       $destino = $carpeta.$_FILES['prod_imagen']['name'];
       
       copy($_FILES['prod_imagen']['tmp_name'], $destino);


       $result= $this->db->from("productos")
                          ->where('prod_codigo',$_POST['prod_codigo'])
                          ->where("prod_id !=",$_POST['prod_id'])
                          ->get()
                          ->row();
                          //echo $_POST['prod_id'];exit;
                          //var_dump($result);exit;
        if ($result) {
          //echo $_POST['prod_id'];exit;
            return false;
        }

            $prod_codigo = $_POST['prod_codigo'];          
            if($_POST['stock_inicial']==''){$stock_inicial=0;}else{$stock_inicial=floatval($_POST['stock_inicial']);}
            if($_POST['prod_cantidad_minima']==''){$prod_cantidad_minima=0;}else{$prod_cantidad_minima=floatval($_POST['prod_cantidad_minima']);}

        if($_POST['prod_id']!='')
        {
            $dataUpdate = [
                            'prod_codigo_sunat'    => substr($_POST['prod_codigo_sunat'],0,8),
                            'prod_codigo'          => $prod_codigo,
                            'prod_nombre'          => strtoupper($_POST['prod_nombre']),
                            'prod_precio_publico'  => $_POST['prod_precio_publico'],
                            'prod_precio_2'  => $_POST['prod_precio_2'],
                            'prod_precio_3'  => $_POST['prod_precio_3'],
                            'prod_precio_4'  => $_POST['prod_precio_4'],
                            'prod_precio_5'  => $_POST['prod_precio_5'],                            
                            'prod_precio_compra'  => $_POST['prod_precio_compra'],
                            'prod_imagen' => $_FILES['prod_imagen']['name'],
                            'prod_codigo_barra'=> $_POST['prod_codigo_barra'],
                            'prod_comision_vendedor' => $_POST['prod_comision_vendedor'],
                            'prod_garantia' => $_POST['prod_garantia'],
                            'prod_descuento'=> $_POST['prod_descuento'],
                            'prod_caducidad'=> $_POST['prod_caducidad'],
                            'prod_observaciones'=> $_POST['prod_observaciones'],
                            'prod_stock'           => $stock_inicial,
                            'prod_cantidad_minima' => $prod_cantidad_minima,
                            'prod_categoria_id'    => $_POST['prod_categoria'],
                            'prod_medida_id'       => $_POST['prod_medida'],
                            'prod_almacen_id'       => $this->session->userdata("almacen_id"),
                            'prod_tipo'       => $_POST['prod_tipo'],
                            'prod_linea_id'       => $_POST['linea'],
                            'prod_marca_id'       => $_POST['marca'],
                            'prod_ubicacion'       => $_POST['ubicacion']
                           
                          ];
            $this->db->where('prod_id', $_POST['prod_id']);
            $this->db->update('productos', $dataUpdate);
          
            if($_POST['prod_tipo'] == 1){
           
              $prod_stock = $this->getStockProductos($_POST['prod_id'],$this->session->userdata("almacen_id"));
              
                if($stock_inicial > $prod_stock){
                    $result = $stock_inicial - $prod_stock;
                    $kardex = array(
                      'k_fecha' => date('Y-m-d'),
                      'k_almacen' => $this->session->userdata("almacen_id"),
                      'k_tipo' => 0,
                      'k_operacion_id' => 0, 
                      'k_concepto' => 'Modificación Stock', 
                      'k_producto' => $_POST['prod_id'],
                      'k_ecantidad' => $result,
                      'k_excantidad' => $stock_inicial
                                         
                   );
                     $this->db->insert('kardex', $kardex); 
                }else if($stock_inicial < $prod_stock){
                    $result = $prod_stock - $stock_inicial;
                    $kardex = array(
                      'k_fecha' => date('Y-m-d'),
                      'k_almacen' => $this->session->userdata("almacen_id"),
                      'k_tipo' => 0,
                      'k_operacion_id' => 0, 
                      'k_concepto' => 'Modificación Stock', 
                      'k_producto' => $_POST['prod_id'],
                      'k_scantidad' => $result,
                      'k_excantidad' => $stock_inicial
                                         
                   );
                     $this->db->insert('kardex', $kardex); 
                }                       
            }
        } else {
            //*generamos codigo aleatorio*/
           // $codigo = rand(10000,99999);
            
           if($_POST['codigo_auto_num_m']==1){
              $this->db->select_max('prod_id');
              $cod = $this->db->get('productos')->row(); 
              $prod_codigo = str_pad(($cod->prod_id + 1), 5, "0", STR_PAD_LEFT);
            }


            $dataInsert = [
                            'prod_codigo_sunat'    => substr($_POST['prod_codigo_sunat'],0,8),
                            'prod_codigo'          => $prod_codigo,
                            'prod_nombre'          => strtoupper($_POST['prod_nombre']),
                            'prod_precio_publico'  => $_POST['prod_precio_publico'],
                            'prod_precio_2'  => $_POST['prod_precio_2'],
                            'prod_precio_3'  => $_POST['prod_precio_3'],
                            'prod_precio_4'  => $_POST['prod_precio_4'],
                            'prod_precio_5'  => $_POST['prod_precio_5'],                            
                            'prod_precio_compra'  => $_POST['prod_precio_compra'],
                            'prod_imagen' => $_FILES['prod_imagen']['name'],
                            'prod_codigo_barra'=> $_POST['prod_codigo_barra'],
                            'prod_comision_vendedor' => $_POST['prod_comision_vendedor'],
                            'prod_garantia' => $_POST['prod_garantia'],
                            'prod_descuento'=> $_POST['prod_descuento'],
                            'prod_caducidad'=> $_POST['prod_caducidad'],
                            'prod_observaciones'=> $_POST['prod_observaciones'],
                            'prod_stock'           => $stock_inicial,
                            'prod_cantidad_minima' => $prod_cantidad_minima,
                            'prod_categoria_id'    => $_POST['prod_categoria'],
                            'prod_medida_id'       => $_POST['prod_medida'],
                            'prod_estado'          => ST_ACTIVO,
                            'prod_almacen_id'       => $this->session->userdata("almacen_id"),
                            'prod_fecha'       => date('Y-m-d'),
                            'prod_tipo'       => $_POST['prod_tipo'],
                            'prod_linea_id'       => $_POST['linea'],
                            'prod_marca_id'       => $_POST['marca'],
                            'prod_ubicacion'       => $_POST['ubicacion']
                          ];
            $this->db->insert('productos', $dataInsert);
            $idProd = $this->db->insert_id();
            
            if($_POST['prod_tipo']==1){
               //$this->compras_model->ingresarStock($idProd,$stock_inicial, 0);
               $kardex = array(
                  'k_fecha' => date('Y-m-d'),
                  'k_almacen' => $this->session->userdata("almacen_id"),
                  'k_tipo' => 0,
                  'k_operacion_id' => 0, 
                  'k_concepto' => 'Stock Inicial', 
                  'k_producto' => $idProd,
                  'k_ecantidad' => $stock_inicial,
                  'k_excantidad' => $stock_inicial
               );

               $this->db->insert('kardex', $kardex);
            }

            $this->db->set('cod_prod_auto',$_POST['codigo_auto_num_m']);
            $this->db->update('comprobantes_ventas');                        
        }

        return true;
    }

    public function getStockProductos($id_producto,$id_almacen){
       $this->db->where('k_producto',$id_producto);
       $this->db->where('k_almacen',$id_almacen);
       $this->db->order_by('k_id','DESC');
       $result = $this->db->get('kardex')->row();

       return $result->k_excantidad;
    }

    public function deleteStockProductos($id_producto,$id_almacen,$tipo,$id_operacion){
       $this->db->where('k_producto',$id_producto);
       $this->db->where('k_almacen',$id_almacen);
       $this->db->where('k_tipo',$tipo);
       $this->db->where('k_operacion_id',$id_operacion);
       $result = $this->db->get('kardex')->row();

       ///eliminar
       $this->db->where('k_id', $result->k_id);
       $this->db->delete('kardex');
       
    }

    public function eliminar($idProducto)
    {
      $productoUpdate = [
                          "prod_estado" => ST_ELIMINADO
                        ];
      $this->db->where("prod_id", $idProducto);
      $this->db->update("productos", $productoUpdate);
    	return true; 
    } 	

    public function getMainList() {
        //cantidad de registros en total    

        $select = $this->db->from("productos as prod")
                           ->join("almacenes as alm", " alm.alm_id = prod.prod_almacen_id")
                           ->join("categoria as cat", " cat.cat_id = prod.prod_categoria_id")
                           ->join("medida med"," med.medida_id = prod.prod_medida_id")
                           ->join("lineas lin"," lin.lin_id = prod.prod_linea_id", 'left')
                           ->join("marcas mar"," mar.mar_id = prod.prod_marca_id", 'left')
                           //->join("tipo_items tpi","tpi.id=prod.prod_tipo_item_id")
                           ->where("prod.prod_estado", ST_ACTIVO)
                           //->where("prod.prod_almacen_id",$this->session->userdata("almacen_id"))
                           ->order_by("prod.prod_id desc");                                  
                           
        if($_POST['search']!='')
        {
           $query = "(prod.prod_nombre like '%".$_POST['search']."%' or prod.prod_codigo like '%".$_POST['search']."%' or prod_codigo_barra LIKE '%".$_POST['search']."%')";
            $select->where($query);
        }       

        //BUSCADOR DE PRODUCTO
        $buscador = isset($_POST['busProducto']) ? $_POST['busProducto'] : '';
        if($buscador != '')
        {           
            $select->where('prod.prod_almacen_id', $this->session->userdata('almacen_id'));
        }


        //FILTRO KENDO GRID
        $filters = $_POST['filter']['filters'];

        //var_dump($filters);exit;
        foreach ($filters as $key => $value) {

            $field = $value['field'];
            $value = $value['value'];

            //echo $field.'---'.$value;
            switch ($field) {
              case 'prod_nombre':
                    $query = "(prod.prod_nombre like '%".$value."%' or prod.prod_codigo like '%".$value."%' or prod_codigo_barra LIKE '%".$value."%')";
                    $select->where($query);
                break;
              case 'lin_nombre':
                    $query = "(lin.lin_nombre like '%".$value."%')";
                    $select->where($query);
                break;

              case 'mar_nombre':
                    $query = "(mar.mar_nombre like '%".$value."%')";
                    $select->where($query);
              break; 
              case 'cat_nombre':
                    $query = "(cat.cat_nombre like '%".$value."%')";
                    $select->where($query);
              break; 

              case 'prod_ubicacion':
                    $query = "(prod.prod_ubicacion like '%".$value."%')";
                    $select->where($query);
              break;    

              case 'alm_nombre':
                    $query = "(alm.alm_nombre like '%".$value."%')";
                    $select->where($query);
              break;    
              
              default:
                # code...
                break;
            }
        }        

        $selectCount = clone $select;
        $rsCount = $selectCount->get()
                               ->result();
        $rows = count($rsCount);
        
        $rsProductos  = $select->limit($_POST['pageSize'], $_POST['skip'])
                               ->get()
                               ->result();              

        foreach($rsProductos as $producto) {            
            $producto->prod_agregarItem = "<a class='btn btn-default btn-xs btn_agregarItem' data-id='{$producto->prod_id}' data-nombre='{$producto->prod_nombre}' data-precio='{$producto->prod_precio_publico}' data-precio_compra='{$producto->prod_precio_compra}'><i class='glyphicon glyphicon-share-alt'></i></a>";
            $producto->prod_editar = '';
            $producto->prod_eliminar = '';
            if($producto->prod_almacen_id == $this->session->userdata('almacen_id')){
              $producto->prod_editar = "<a class='btn btn-default btn-xs btn_modificar_producto' data-id='{$producto->prod_id}' data-toggle='modal' data-target='#myModal' data-keyboard='false' data-backdrop='static'><i class='glyphicon glyphicon-pencil'></i></a>";
              $producto->prod_eliminar = "<a class='btn btn-danger btn-xs btn_eliminar_producto' data-id='{$producto->prod_id}' data-msg='Desea eliminar producto: {$producto->prod_nombre}?'><i class='glyphicon glyphicon-remove'></i></a>";
            }           
           
            if($producto->prod_id==1 || ($this->session->userdata('accesoEmpleado') != "")){
                $producto->prod_eliminar = "";
            }            
            
            $stock = $this->getStockProductos($producto->prod_id,$producto->prod_almacen_id);
            $producto->prod_stock = ($stock!='')?$stock:'0';
            
            
        }
        $datos = [
                    'data' => $rsProductos,
                    'rows' => $rows
                 ];

        
        return $datos;      
    }

    public function stock_producto_select_almacen($idProducto,$almacen)
    {
        /*obtenemos el stock del producto*/
        $this->db->where("ejm_producto_id", $idProducto);
        $this->db->where("ejm_estado", ST_PRODUCTO_DISPONIBLE);
        $this->db->where("ejm_almacen_id",$almacen);
        $this->db->from("ejemplar");
        $countProductos = $this->db->count_all_results();
        return $countProductos;
    }



    public function stock($idProducto)
    {
        /*obtenemos el stock del producto*/
        $this->db->where("ejm_producto_id", $idProducto);
        $this->db->where("ejm_estado", ST_PRODUCTO_DISPONIBLE);
        $this->db->where("ejm_almacen_id",$this->session->userdata("almacen_id"));
        $this->db->from("ejemplar");
        $countProductos = $this->db->count_all_results();
        return $countProductos;
    }

    public function selectAutocompleteprod($buscar){ 

        $where = '(pr.prod_estado='.ST_ACTIVO.' AND pr.prod_nombre LIKE "%'.$buscar.'%") OR (pr.prod_estado='.ST_ACTIVO.' AND pr.prod_codigo LIKE "%'.$buscar.'%") OR (pr.prod_estado='.ST_ACTIVO.' AND pr.prod_codigo_barra LIKE "%'.$buscar.'%")';
        $result = $this->db->from('productos pr')
                            ->join("categoria cat","pr.prod_categoria_id=cat.cat_id")
                            ->join("medida md","pr.prod_medida_id=md.medida_id")
                            ->where('pr.prod_almacen_id',$this->session->userdata("almacen_id"))
                            ->where($where)                            
                            ->get()
                            ->result();

                             

        $data = array();    
        foreach ($result as $prod){

          $stock = $this->getStockProductos($prod->prod_id,$this->session->userdata("almacen_id"));
          $producto_stock = ($stock!='')?$stock:'0';

            $data[] = array(
                "value" => $prod->prod_codigo." - ".$prod->prod_nombre." ........ Precio: ".$prod->prod_precio_publico." / Stock: ".$producto_stock,
                "codigo" => $prod->prod_codigo,
                "precio" => $prod->prod_precio_publico,                    
                "id" => $prod->prod_id,
                "prod_cat_id" => $prod->prod_categoria_id,
                "prod_stock" => $producto_stock,
                "categoria" => $prod->cat_nombre,
                "prod_medida_id" => $prod->prod_medida_id,
                "medida" => $prod->medida_nombre
              
            );
        } 
       
        return $data;
    }

    public function selectAutocompleteprodSC($buscar){           
        $where = '(pr.prod_estado='.ST_ACTIVO.' AND pr.prod_nombre LIKE "%'.$buscar.'%") OR (pr.prod_estado='.ST_ACTIVO.' AND pr.prod_codigo LIKE "%'.$buscar.'%") OR (pr.prod_estado='.ST_ACTIVO.' AND pr.prod_codigo_barra LIKE "%'.$buscar.'%") ';
        $result = $this->db->from('productos pr')
                            ->join("categoria cat","pr.prod_categoria_id=cat.cat_id")
                            ->join("medida md","pr.prod_medida_id=md.medida_id")
                            ->where('pr.prod_almacen_id',$this->session->userdata("almacen_id"))
                            ->where($where)                            
                            ->get()
                            ->result();                             

        $data = array();    
        foreach ($result as $prod){

          $stock = $this->getStockProductos($prod->prod_id,$this->session->userdata("almacen_id"));
          $producto_stock = ($stock!='')?$stock:'0';

            $data[] = array(
                "value" => $prod->prod_nombre." ........ Precio: ".$prod->prod_precio_publico." / Stock: ".$producto_stock,
                "codigo" => $prod->prod_codigo,
                "precio" => $prod->prod_precio_publico,                    
                "precioCosto" => $prod->prod_precio_compra,
                "id" => $prod->prod_id,
                "prod_cat_id" => $prod->prod_categoria_id,
                "prod_stock" => $producto_stock,
                "categoria" => $prod->cat_nombre,
                "prod_medida_id" => $prod->prod_medida_id,
                "medida" => $prod->medida_nombre
              
            );
        } 
       
        return $data;
    }

   public function selectAutocompleteprodSCa($buscar){ 

        $where = '(pr.prod_estado='.ST_ACTIVO.' AND pr.prod_nombre LIKE "%'.$buscar.'%") OR (pr.prod_estado='.ST_ACTIVO.' AND pr.prod_codigo LIKE "%'.$buscar.'%") OR (pr.prod_estado='.ST_ACTIVO.' AND pr.prod_codigo_barra LIKE "%'.$buscar.'%") ';
        $result = $this->db->from('productos pr')
                            ->join("categoria cat","pr.prod_categoria_id=cat.cat_id")
                            ->join("medida md","pr.prod_medida_id=md.medida_id")
                            ->where('pr.prod_almacen_id',$this->session->userdata("almacen_id"))
                            ->where($where)                            
                            ->get()
                            ->result();

                             

        $data = array();    
        foreach ($result as $prod){

          $stock = $this->getStockProductos($prod->prod_id,$this->session->userdata("almacen_id"));
          $producto_stock = ($stock!='')?$stock:'0';

            $data[] = array(
                "value" => $prod->prod_nombre." ........ Costo: ".$prod->prod_precio_compra." / Stock: ".$producto_stock,
                "codigo" => $prod->prod_codigo,
                "precio" => $prod->prod_precio_compra,                    
                "precioCosto" => $prod->prod_precio_compra,
                "id" => $prod->prod_id,
                "prod_cat_id" => $prod->prod_categoria_id,
                "prod_stock" => $producto_stock,
                "categoria" => $prod->cat_nombre,
                "prod_medida_id" => $prod->prod_medida_id,
                "medida" => $prod->medida_nombre
              
            );
        } 
       
        return $data;
    }



    public function selectAutocompleteprodC($buscar){ 

        $where = '(pr.prod_estado='.ST_ACTIVO.' AND pr.prod_nombre LIKE "%'.$buscar.'%") OR (pr.prod_estado='.ST_ACTIVO.' AND pr.prod_codigo LIKE "%'.$buscar.'%")';
        $result = $this->db->from('productos pr')
                            ->join("categoria cat","pr.prod_categoria_id=cat.cat_id")
                            ->join("medida md","pr.prod_medida_id=md.medida_id")
                            ->where($where)
                            //->where('pr.prod_almacen_id',$this->session->userdata("almacen_id"))
                            ->get()
                            ->result();

                             

        $data = array();    
        foreach ($result as $prod){

          $stock = $this->getStockProductos($prod->prod_id,$this->session->userdata("almacen_id"));
          $producto_stock = ($stock!='')?$stock:'0';

            $data[] = array(
                "value" => $prod->prod_codigo." - ".$prod->prod_nombre." ........ Stock: ".$producto_stock,
                "codigo" => $prod->prod_codigo,
                "precio" => $prod->prod_precio_compra,                    
                "id" => $prod->prod_id,
                "prod_cat_id" => $prod->prod_categoria_id,
                "categoria" => $prod->cat_nombre,
                "prod_medida_id" => $prod->prod_medida_id,
                "medida" => $prod->medida_nombre              
            );
        } 
       
        return $data;
    }
    public function getCodigoProducto($idproducto)  {
        $result= $this->db->from('productos')
                          ->where('prod_id',$idproducto)
                          ->get()
                          ->result();
        return $result;
    }
    /*reducir stock */
    public function RestarStock($idProducto,$cantidad) {
        if ($idProducto!='') {
            $dataUpdate = [                            
                            'prod_cantidad_minima' => $_POST['cantidad_minima'] - $cantidad
                          ];
            $this->db->where('prod_id', $idProducto);
            $this->db->update('productos', $dataUpdate); 
        }
        return true;
        
    }

    public function masVendidos_ct(){


        $fecha_desde = isset($_POST['fecha_desde']) ? $_POST['fecha_desde'] : $_GET['fecha_desde'];
        $fecha_hasta = isset($_POST['fecha_hasta']) ? $_POST['fecha_hasta'] : $_GET['fecha_hasta'];             

        if($fecha_desde != ''){
            $fecha_desde =  (new DateTime($fecha_desde))->format('Y-m-d');
            $this->db->where("DATE(com.fecha_de_emision) >=", $fecha_desde);
        }
        if($fecha_hasta != ''){
            $fecha_hasta =  (new DateTime($fecha_hasta))->format('Y-m-d');
            $this->db->where("DATE(com.fecha_de_emision) <=", $fecha_hasta);
        }        

        $rsProductos = $this->db->select("pro.prod_id prod_id,pro.prod_codigo prod_codigo,pro.prod_nombre prod_nombre,SUM(itm.cantidad) cantidad,SUM(itm.total) total_venta")
                        ->from('comprobantes com')
                        ->join('items itm','itm.comprobante_id = com.id')
                        ->join('productos pro','itm.producto_id = pro.prod_id')
                        ->where('com.anulado',0)
                        ->where('com.eliminado',0)
                        ->group_by('itm.producto_id')
                        ->get()
                        ->result_array();

        return $rsProductos;
    }

    public function masVendidos_np(){
        $fecha_desde = isset($_POST['fecha_desde']) ? $_POST['fecha_desde'] : $_GET['fecha_desde'];
        $fecha_hasta = isset($_POST['fecha_hasta']) ? $_POST['fecha_hasta'] : $_GET['fecha_hasta'];        

        if($fecha_desde != ''){
            $fecha_desde =  (new DateTime($fecha_desde))->format('Y-m-d');
            $this->db->where("DATE(npe.notap_fecha) >=", $fecha_desde);
        }
        if($fecha_hasta != ''){
            $fecha_hasta =  (new DateTime($fecha_hasta))->format('Y-m-d');
            $this->db->where("DATE(npe.notap_fecha) <=", $fecha_hasta);
        }        

        $rsProductos = $this->db->select('pro.prod_id prod_id,pro.prod_codigo prod_codigo,pro.prod_nombre prod_nombre,SUM(nde.notapd_cantidad) cantidad,SUM(nde.notapd_total) total_venta')
                                 ->from('nota_pedido npe')                                 
                                 ->join('nota_pedido_detalle nde','nde.notapd_notap_id = npe.notap_id')
                                 ->join('productos pro','nde.notapd_producto_id = pro.prod_id')
                                 ->where('npe.notap_estado',1)
                                 ->group_by('pro.prod_id')
                                 ->order_by('pro.prod_id')
                                 ->get()
                                 ->result_array();

        return $rsProductos;
    }


    //SELECT AUTOCOMPLETE 06-10-2020
    public function selectAutocomplete($buscar,$almacen = ''){
        $where = '(prod.prod_estado ='.ST_ACTIVO.' AND prod.prod_nombre LIKE "%'.$buscar.'%") AND (prod.prod_almacen_id = '.$almacen.')';
        $result = $this->db->from('productos prod')
                            ->where($where)
                            ->get()
                            ->result();
                        
        $data = array();    
        foreach ($result as $prod){
            $data[] = array(
              "value" => $prod->prod_nombre,             
              "id" => $prod->prod_id 
            );
        }        
        return $data;
    }   


    //INGRESO DE STOCK 03-11-2020
    public function ingresarStock($prod_id ,$cantidad, $concepto = 'INGRESO',$comprobante_id = '',$serie = '',$numero = '',$almacen_id = ''){
      $almacen_id = ($almacen_id == '') ? $this->session->userdata("almacen_id") : $almacen_id;

      //echo $almacen_id;exit();
      $stock = $this->getStockProductos($prod_id,$almacen_id);
            $nueva_cantidad = floatval($stock) + floatval($cantidad);

              $kardex = array(
                   'k_fecha' => date('Y-m-d'),
                   'k_almacen' => $almacen_id,
                   'k_tipo' => 4,
                   'k_operacion_id' => $comprobante_id,
                   'k_serie' => $serie.'-'.$numero,
                   'k_concepto' => $concepto,     
                   'k_producto' => $prod_id,
                   'k_scantidad' => $cantidad,
                   'k_excantidad' => $nueva_cantidad
                 );

      $this->db->insert('kardex', $kardex);
    }

    //SALIDA DE STOCK 03-11-2020
    public function salidaStock($prod_id ,$cantidad, $concepto = 'SALIDA',$comprobante_id = '',$serie = '',$numero = '',$almacen_id = ''){      
      $almacen_id = ($almacen_id == '') ? $this->session->userdata("almacen_id") : $almacen_id;
      $stock = $this->getStockProductos($prod_id,$almacen_id);
            $nueva_cantidad = floatval($stock) - floatval($cantidad);

              $kardex = array(
                   'k_fecha' => date('Y-m-d'),
                   'k_almacen' => $almacen_id,
                   'k_tipo' => 5,
                   'k_operacion_id' => $comprobante_id,
                   'k_serie' => $serie.'-'.$numero,
                   'k_concepto' => $concepto,     
                   'k_producto' => $prod_id,
                   'k_scantidad' => $cantidad,
                   'k_excantidad' => $nueva_cantidad                                      
                 );

      $this->db->insert('kardex', $kardex);
    }    


    //CODIGO DE BARRA 10-11-2020
    public function selectPrecioCodBarra($prod_codigo_barra = '') {          
        if ($prod_codigo_barra != '') {            
            $datos = $this->db->from('productos pr')                            
                              ->join("categoria cat","pr.prod_categoria_id=cat.cat_id")
                              ->join("medida md","pr.prod_medida_id=md.medida_id")
                              ->where('prod_codigo_barra',$prod_codigo_barra)
                              ->where('prod_estado',ST_ACTIVO)
                              ->get()
                              ->row();
            
          }    
      return $datos;
    }     
}