<?PHP if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reportes_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('America/Lima');
        $this->load->library("encryption");
        $this->load->model("productos_model");
    }



    public function reporteUtilidades_ct(){

        $fecha_desde = isset($_POST['fecha_desde']) ? $_POST['fecha_desde'] : $_GET['fecha_desde'];
        $fecha_hasta = isset($_POST['fecha_hasta']) ? $_POST['fecha_hasta'] : $_GET['fecha_hasta']; 
        $vendedor    = isset($_POST['vendedor']) ? $_POST['vendedor'] : $_GET['vendedor'];
        $transportista = isset($_POST['transportista']) ? $_POST['transportista'] : $_GET['transportista'];

        $producto_id  = isset($_POST['producto_id']) ? $_POST['producto_id'] : $_GET['producto_id'];
        $categoria_id = isset($_POST['categoria_id']) ? $_POST['categoria_id'] : $_GET['categoria_id'];
        $linea_id    = isset($_POST['linea_id']) ? $_POST['linea_id'] : $_GET['linea_id'];
        $marca_id    = isset($_POST['marca_id']) ? $_POST['marca_id'] : $_GET['marca_id'];
        


        $countTransportista = strlen(implode("",$transportista));
                
        if($fecha_desde != ''){
            $fecha_desde =  (new DateTime($fecha_desde))->format('Y-m-d');
            $this->db->where("DATE(com.fecha_de_emision) >=", $fecha_desde);
        }
        if($fecha_hasta != ''){
            $fecha_hasta =  (new DateTime($fecha_hasta))->format('Y-m-d');
            $this->db->where("DATE(com.fecha_de_emision) <=", $fecha_hasta);
        }
        if($vendedor != ''){            
            $this->db->where('com.empleado_select',$vendedor);
        }        
        if($countTransportista>0){                        
            $this->db->where_in('com.transportista_id',$transportista);
        }

        if($categoria_id != ''){            
            $this->db->where('cat.cat_id',$categoria_id);
        }        
        if($linea_id != ''){            
            $this->db->where('lin.lin_id',$linea_id);
        }
        if($marca_id != ''){            
            $this->db->where('mar.mar_id',$marca_id);
        }                      
        if($producto_id != ''){            
            $this->db->where("pro.prod_id", $producto_id);
        }         
                
        $rsReporteComprobantes = $this->db->select("pro.prod_id prod_id,pro.prod_codigo prod_codigo,pro.prod_nombre prod_nombre,SUM(itm.cantidad) cantidad,SUM(itm.subtotal) total_venta,cat.cat_nombre,lin.lin_nombre,mar.mar_nombre,med.medida_nombre")
                                          ->from('comprobantes com')
                                          ->join('items itm','itm.comprobante_id = com.id')
                                          ->join('productos pro','itm.producto_id = pro.prod_id')
                                          ->join('medida med','med.medida_id = pro.prod_medida_id')
                                          ->join('categoria cat','cat.cat_id = pro.prod_categoria_id')
                                          ->join('lineas lin','lin.lin_id = pro.prod_linea_id','left')
                                          ->join('marcas mar','mar.mar_id = pro.prod_marca_id','left')
                                          ->where('com.anulado',0)
                                          ->where('com.eliminado',0)
                                          ->group_by('itm.producto_id')
                                          ->order_by('med.medida_id','DESC')                                          
                                          ->get()
                                          ->result_array();
                                                                                          
        return $rsReporteComprobantes;                  			
    }

    public function reporteUtilidades_nv(){

        $fecha_desde = isset($_POST['fecha_desde']) ? $_POST['fecha_desde'] : $_GET['fecha_desde'];
        $fecha_hasta = isset($_POST['fecha_hasta']) ? $_POST['fecha_hasta'] : $_GET['fecha_hasta'];
        $vendedor    = isset($_POST['vendedor']) ? $_POST['vendedor'] : $_GET['vendedor'];
        $transportista = isset($_POST['transportista']) ? $_POST['transportista'] : $_GET['transportista'];

        $producto_id  = isset($_POST['producto_id']) ? $_POST['producto_id'] : $_GET['producto_id'];
        $categoria_id = isset($_POST['categoria_id']) ? $_POST['categoria_id'] : $_GET['categoria_id'];
        $linea_id    = isset($_POST['linea_id']) ? $_POST['linea_id'] : $_GET['linea_id'];
        $marca_id    = isset($_POST['marca_id']) ? $_POST['marca_id'] : $_GET['marca_id'];

        $countTransportista = strlen(implode("",$transportista));

        if($fecha_desde != ''){
            $fecha_desde =  (new DateTime($fecha_desde))->format('Y-m-d');
            $this->db->where("DATE(npe.notap_fecha) >=", $fecha_desde);
        }
        if($fecha_hasta != ''){
            $fecha_hasta =  (new DateTime($fecha_hasta))->format('Y-m-d');
            $this->db->where("DATE(npe.notap_fecha) <=", $fecha_hasta);
        }
        if($vendedor != ''){            
            $this->db->where('npe.notap_vendedor',$vendedor);
        }
        if($countTransportista>0){             
            $this->db->where_in('npe.notap_transportista_id',$transportista);
        }

        if($categoria_id != ''){            
            $this->db->where('cat.cat_id',$categoria_id);
        }        
        if($linea_id != ''){            
            $this->db->where('lin.lin_id',$linea_id);
        }
        if($marca_id != ''){            
            $this->db->where('mar.mar_id',$marca_id);
        }  
        if($producto_id != ''){            
            $this->db->where("pro.prod_id", $producto_id);
        }
       

        $rsReporteNotaVenta = $this->db->select("pro.prod_id prod_id,pro.prod_codigo prod_codigo,pro.prod_nombre prod_nombre,SUM(nde.notapd_cantidad) cantidad,SUM(nde.notapd_subtotal) total_venta,cat.cat_nombre,lin.lin_nombre,mar.mar_nombre,med.medida_nombre")
                                          ->from('nota_pedido npe')
                                          ->join('nota_pedido_detalle nde','nde.notapd_notap_id = npe.notap_id')
                                          ->join('productos pro','nde.notapd_producto_id = pro.prod_id')
                                          ->join('medida med','med.medida_id = pro.prod_medida_id')
                                          ->join('categoria cat','cat.cat_id = pro.prod_categoria_id')
                                          ->join('lineas lin','lin.lin_id = pro.prod_linea_id','left')
                                          ->join('marcas mar','mar.mar_id = pro.prod_marca_id','left')
                                          ->where('npe.notap_estado',1)                                       
                                          ->group_by('nde.notapd_producto_id')
                                          ->order_by('med.medida_id','DESC')                                          
                                          ->get()
                                          ->result_array();           

        
        return $rsReporteNotaVenta;
    }


    public function reporte_liquidacionReparto_ct(){

      $fecha_desde = isset($_POST['fecha_desde']) ? $_POST['fecha_desde'] : $_GET['fecha_desde'];
        $fecha_hasta = isset($_POST['fecha_hasta']) ? $_POST['fecha_hasta'] : $_GET['fecha_hasta']; 
        $vendedor    = isset($_POST['vendedor']) ? $_POST['vendedor'] : $_GET['vendedor'];
        $transportista    = isset($_POST['transportista']) ? $_POST['transportista'] : $_GET['transportista'];

        $countTransportista = strlen(implode("",$transportista));

        if($fecha_desde != ''){
            $fecha_desde =  (new DateTime($fecha_desde))->format('Y-m-d');
            $this->db->where("DATE(com.fecha_de_emision) >=", $fecha_desde);
        }
        if($fecha_hasta != ''){
            $fecha_hasta =  (new DateTime($fecha_hasta))->format('Y-m-d');
            $this->db->where("DATE(com.fecha_de_emision) <=", $fecha_hasta);
        }
        if($vendedor != ''){            
            $this->db->where('com.empleado_select',$vendedor);
        }
        if($countTransportista>0){                        
            $this->db->where_in('com.transportista_id',$transportista);
        }
        
        $rsReporteComprobantes = $this->db->select("com.id comprobante_id,com.serie serie,concat_ws('-', com.serie, com.numero) numser,com.total_igv total_igv,com.total_a_pagar total_a_pagar,(SELECT SUM(totalCosto) FROM items ite WHERE ite.comprobante_id = com.id ) total_costo,
            DATE_FORMAT(com.fecha_de_emision, '%d-%m-%Y') fecha_de_emision,cli.razon_social cliente_razon_social,CONCAT(epl.nombre,' ', epl.apellido_paterno) vendedor,tdc.tipo_documento tipo_documento,tpg.tipo_pago tipo_pago",FALSE)
                                          ->from('comprobantes com')
                                          ->join('clientes cli','com.cliente_id = cli.id')
                                          ->join('empleados epl','com.empleado_select = epl.id')
                                          ->join('tipo_documentos tdc','com.tipo_documento_id = tdc.id')
                                          ->join('tipo_pagos tpg','com.tipo_pago_id = tpg.id')
                                          ->where('com.anulado',0)
                                          ->where('com.eliminado',0)                                          
                                          ->order_by('tipo_pago_id asc,com.id')
                                          ->get()
                                          ->result_array();           

        //var_dump($rsReporteComprobantes);exit;        
        return $rsReporteComprobantes;
    }    

    public function reporte_liquidacionReparto_nv(){

        $fecha_desde = isset($_POST['fecha_desde']) ? $_POST['fecha_desde'] : $_GET['fecha_desde'];
        $fecha_hasta = isset($_POST['fecha_hasta']) ? $_POST['fecha_hasta'] : $_GET['fecha_hasta'];
        $vendedor    = isset($_POST['vendedor']) ? $_POST['vendedor'] : $_GET['vendedor'];
        $transportista    = isset($_POST['transportista']) ? $_POST['transportista'] : $_GET['transportista'];

        $countTransportista = strlen(implode("",$transportista));

        if($fecha_desde != ''){
            $fecha_desde =  (new DateTime($fecha_desde))->format('Y-m-d');
            $this->db->where("DATE(npe.notap_fecha) >=", $fecha_desde);
        }
        if($fecha_hasta != ''){
            $fecha_hasta =  (new DateTime($fecha_hasta))->format('Y-m-d');
            $this->db->where("DATE(npe.notap_fecha) <=", $fecha_hasta);
        }
        if($vendedor != ''){            
            $this->db->where('npe.notap_vendedor',$vendedor);
        }
        if($countTransportista>0){             
            $this->db->where_in('npe.notap_transportista_id',$transportista);
        }

        $rsReporteNotaVenta = $this->db->select("npe.notap_id comprobante_id,'NP01' serie,CONCAT('NP-',npe.notap_correlativo) numser,0 total_igv,npe.notap_total total_a_pagar,(SELECT SUM(notapd_totalCosto) FROM nota_pedido_detalle npd WHERE npd.notapd_notap_id = npe.notap_id ) total_costo,DATE_FORMAT(npe.notap_fecha, '%d-%m-%Y') fecha_de_emision,cli.razon_social cliente_razon_social,CONCAT(epl.nombre,' ', epl.apellido_paterno) vendedor,'NOTA VENTA' tipo_documento,tpg.tipo_pago tipo_pago",FALSE)
                                          ->from('nota_pedido npe')                                       
                                          ->join('clientes cli','npe.notap_cliente_id = cli.id')
                                          ->join('empleados epl','npe.notap_vendedor = epl.id')   
                                          ->join('tipo_pagos tpg','npe.notap_tipopago_id = tpg.id')                                   
                                          ->where('npe.notap_estado',1) 
                                          ->order_by('notap_tipopago_id asc,npe.notap_id')
                                          ->get()
                                          ->result_array();           

        //var_dump($rsReporteNotaVenta);exit();
        return $rsReporteNotaVenta;
    }

    public function reporte_stockMinimo(){     

        $producto_id = isset($_POST['producto_id']) ? $_POST['producto_id'] : $_GET['producto_id'];
        $categoria = isset($_POST['categoria']) ? $_POST['categoria'] : $_GET['categoria'];

        if($producto_id != ''){            
            $this->db->where("pro.prod_id", $producto_id);
        }       
        if($categoria != ''){            
            $this->db->where("cat.cat_id", $categoria);
        }
        

        $rsProducto = $this->db->select('pro.prod_id prod_id,pro.prod_codigo prod_codigo,prod_nombre,prod_cantidad_minima,(SELECT k_excantidad FROM kardex WHERE k_producto = prod_id ORDER BY k_id DESC LIMIT 1) prod_stock,cat.cat_nombre cat_nombre')
                               ->from('productos pro')
                               ->join('categoria cat','pro.prod_categoria_id = cat.cat_id')   
                               ->where('(SELECT k_excantidad FROM kardex WHERE k_producto = prod_id ORDER BY k_id DESC LIMIT 1) <prod_cantidad_minima')                                            
                               ->get()
                               ->result_array();         
      return $rsProducto;
    }

    public function reporte_stockValorizado(){

          $producto_id = isset($_POST['producto_id']) ? $_POST['producto_id'] : $_GET['producto_id'];
          $almacen_id  = isset($_POST['almacen']) ? $_POST['almacen'] : $_GET['almacen'];
          $categoria   = isset($_POST['categoria']) ? $_POST['categoria'] : $_GET['categoria'];
          $linea_id    = isset($_POST['linea_id']) ? $_POST['linea_id'] : $_GET['linea_id'];
          $marca_id    = isset($_POST['marca_id']) ? $_POST['marca_id'] : $_GET['marca_id'];
                      
        if($producto_id != ''){            
              $this->db->where("pro.prod_id", $producto_id);
        }       
        if($almacen_id != ''){            
              $this->db->where("alm.alm_id", $almacen_id);
        }       
        if($categoria != ''){            
              $this->db->where("cat.cat_nombre", $categoria);
        }         
        if($linea_id != ''){            
            $this->db->where('lin.lin_id',$linea_id);
        }
        if($marca_id != ''){
            $this->db->where('mar.mar_id',$marca_id);
        }


          $rsProductos = $this->db->select('pro.prod_id prod_id,pro.prod_almacen_id prod_almacen_id,alm.alm_nombre,pro.prod_codigo prod_codigo,prod_nombre,pro.prod_precio_publico,pro.prod_precio_compra,prod_cantidad_minima,cat.cat_nombre cat_nombre,lin.lin_nombre lin_nombre,mar.mar_nombre mar_nombre')
                                 ->from('productos pro')
                                 ->join('almacenes alm','alm.alm_id = pro.prod_almacen_id')
                                 ->join('categoria cat','cat.cat_id = pro.prod_categoria_id')
                                 ->join('lineas lin','lin.lin_id = pro.prod_linea_id','left')
                                 ->join('marcas mar','mar.mar_id = pro.prod_marca_id','left')                                                                  
                                 ->get()
                                 ->result();

            foreach ($rsProductos as $rsProducto) {                            
                $rsProducto->prod_stock = $this->productos_model->getStockProductos($rsProducto->prod_id,$rsProducto->prod_almacen_id);
            }            

        return $rsProductos;
    }



    public function reporteVendedor($vendedor_id, $fecha_inicio = '', $fecha_fin = '', $cliente_id = ''){
         
        $where = '';
        $where .= ' AND npe.notap_empleado_insert = ' . $vendedor_id;
        $where .= ' AND npe.notap_estado = 1';
        if($cliente_id != '') $where .= ' AND npe.notap_cliente_id';
        if(($fecha_inicio != '') && ($fecha_fin != '')) $where .= " AND npe.`notap_fecha` BETWEEN '$fecha_inicio' AND '$fecha_fin' ";                
        
        $sql = "SELECT emp.`apellido_paterno`, emp.`apellido_materno`, emp.`nombre`, SUM(nde.`notapd_cantidad`) cantidad, pro.`prod_id`, pro.`prod_nombre`, med.`medida_nombre` FROM `nota_pedido` npe
        JOIN empleados emp ON emp.`id` = npe.`notap_empleado_insert`
        JOIN `nota_pedido_detalle` nde ON nde.`notapd_notap_id` = npe.`notap_id`
        JOIN `productos` pro ON pro.`prod_id` = nde.`notapd_producto_id`
        JOIN medida med ON med.`medida_id` = pro.`prod_medida_id`
        WHERE 1 = 1 $where
        GROUP BY nde.`notapd_producto_id`";

        $query = $this->db->query($sql);
        
        $rows = array();
        foreach ($query->result_array() as $row) {
            $rows[] = $row;
        }
        return $rows;
        
    }

    //REPORTE POR LOTES
    public function reporte_impresionLotes(){


      $fecha_desde = isset($_POST['fecha_desde']) ? $_POST['fecha_desde'] : $_GET['fecha_desde'];
      $fecha_hasta = isset($_POST['fecha_hasta']) ? $_POST['fecha_hasta'] : $_GET['fecha_hasta'];
      $tipo_comprobante = isset($_POST['tipo_comprobante']) ? $_POST['tipo_comprobante'] : $_GET['tipo_comprobante'];
      $numero_inicial  = isset($_POST['numero_inicial']) ? $_POST['numero_inicial'] : $_GET['numero_inicial'];
      $numero_final    = isset($_POST['numero_final']) ? $_POST['numero_final'] : $_GET['numero_final'];

      //echo ST_NOTA_PEDIDO;exit;
      //echo $fecha_desde.'//'.$fecha_hasta.'//'.$numero_inicial.'//'.$numero_final.'//'.$tipo_comprobante;exit;
      switch ($tipo_comprobante) {
        case ST_NOTA_PEDIDO:

                
        if($fecha_desde != ''){
            $fecha_desde =  (new DateTime($fecha_desde))->format('Y-m-d');
            $this->db->where("DATE(np.notap_fecha) >=", $fecha_desde);
        }
        if($fecha_hasta != ''){
            $fecha_hasta =  (new DateTime($fecha_hasta))->format('Y-m-d');
            $this->db->where("DATE(np.notap_fecha) <=", $fecha_hasta);
        }
        if($numero_inicial != ''){            
            $this->db->where('np.notap_correlativo >=',$numero_inicial);
        }
        if($numero_final != ''){            
            $this->db->where('np.notap_correlativo <=',$numero_final);
        }      
            
           $documento = $this->db->select('np.notap_id documento_id, np.notap_correlativo nDocumento, np.notap_fecha fecha_de_emision,cli.razon_social razon_social_cliente,CONCAT(emp.nombre," ",emp.apellido_paterno) as vendedor,alm.alm_nombre',FALSE)
                                          ->from('nota_pedido np')
                                          ->join('clientes cli','np.notap_cliente_id = cli.id')
                                          ->join('empleados emp','np.notap_vendedor = emp.id')
                                          ->join('almacenes alm','np.notap_almacen = alm.alm_id');
                                          //->where('notap_correlativo',$_POST['nDocumento'])
                                          //->get()
                                          //->result();

            $documento->a4 = '<a href="'.base_url().'index.php/notas/decargarPdf/'.$documento->documento_id.'" target="_seld"><img src="'.base_url().'images/pdf.png"</a>';
            $documento->url = '<a href="'.base_url().'index.php/notas/comprobanteTributario/'.$documento->documento_id.'/1" target="_seld"><span class="glyphicon glyphicon-export"></span></a>';            

            break;

        case ST_COMPROBANTE:

          if($fecha_desde != ''){
            $fecha_desde =  (new DateTime($fecha_desde))->format('Y-m-d');
            $this->db->where("DATE(com.fecha_de_emision) >=", $fecha_desde);
          }
          if($fecha_hasta != ''){
                $fecha_hasta =  (new DateTime($fecha_hasta))->format('Y-m-d');
                $this->db->where("DATE(com.fecha_de_emision) <=", $fecha_hasta);
          }
          if($numero_inicial != ''){            
            $this->db->where('com.numero >=',$numero_inicial);
          }
          if($numero_final != ''){            
              $this->db->where('com.numero <=',$numero_final);
          }      

            $documento = $this->db->select('com.id documento_id, concat_ws("-", com.serie, com.numero) nDocumento, com.fecha_de_emision fecha_de_emision,cli.razon_social razon_social_cliente,CONCAT(emp.nombre," ",emp.apellido_paterno) as vendedor,alm.alm_nombre',FALSE)
                                          ->from('comprobantes com')
                                          ->join('clientes cli','com.cliente_id = cli.id')
                                          ->join('empleados emp','com.empleado_select = emp.id')
                                          ->join('almacenes alm','com.venta_almacen_id = alm.alm_id')
                                          ->where_in("com.tipo_documento_id", [1,3]);//solo factura/boleta
                                          //->where("concat_ws('-', com.serie, com.numero) =", trim($_POST['nDocumento']))
                                          //->get()
                                          //->result();

            $documento->a4 = '<a href="'.base_url().'index.php/comprobantes/pdfGeneraComprobanteOffLine/'.$documento->documento_id.'" target="_seld"><img src="'.base_url().'images/pdf.png"</a>';  

             $documento->url = '<a href="'.base_url().'index.php/comprobantes/comprobanteTributario/'.$documento->documento_id.'" target="_seld"><span class="glyphicon glyphicon-export"></span></a>';


            break;    
        default:
            # code...
            break;
      }      
      return $documento;
    }
  }  