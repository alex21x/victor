<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Notas extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();        
        date_default_timezone_set('America/Lima');
        $this->load->model('accesos_model');
        $this->load->model('monedas_model'); 
        $this->load->model('empresas_model');
        $this->load->model('empleados_model');
        $this->load->model('tipo_igv_model');
        $this->load->model('tipo_items_model');
        $this->load->model('notas_model');
        $this->load->model('productos_model');
        $this->load->model('transportistas_model');
        $this->load->model('tipo_pagos_model');
        $this->load->model('tipo_documentos_model');
        $this->load->model('medida_model');
        $this->load->model('igv_model');
        $this->load->library('pdf');
        $this->load->helper('ayuda');        
//
        $empleado_id = $this->session->userdata('empleado_id');
        $almacen_id = $this->session->userdata("almacen_id");
        if (empty($empleado_id) or empty($almacen_id)) {
            $this->session->set_flashdata('mensaje', 'No existe sesion activa');
            redirect(base_url());
        }
    }
    public function index()    
    {
        //NOTAP_ID
        if($this->uri->segment(3) != '' )     
        $data['notap_id'] = $this->uri->segment(3);

    	$data['empresa'] = $this->empresas_model->select();
    	$data['empleados'] = $this->empleados_model->select2(3);
        $this->accesos_model->menuGeneral();
        $this->load->view('notas/basic_index', $data);
        $this->load->view('templates/footer');    	
    }

    public  function SeleccionaListaPrecio(){
    $producto = $this->productos_model->select($_REQUEST['productoId']);
    $data=[

         "producto"=>$producto

         ];    
        echo $this->load->view('notas/modal_lista_precio',$data);    
    }


    //ALEXANDER FERNANDEZ 16-08-2020
    public function modal_envio_notaPedido(){          
        $data['nota'] = $this->notas_model->select($this->uri->segment(3));
        $data['tipo_documento'] = 'NOTA DE VENTA';        
        echo $this->load->view('notas/modal_envio_notaPedido',$data);
    }

    //ALEXANDER FERNANDEZ 16-08-2020
    public function modal_envio_whatsap(){

        $data['nota'] = $this->notas_model->select($this->uri->segment(3));
        echo $this->load->view("notas/modal_envio_whatsap",$data);
    }


    public function modal_envio_email(){

        $data['nota'] = $this->notas_model->select($this->uri->segment(3));
        echo $this->load->view("notas/modal_envio_email",$data);
    }


    public function modal_envio_whatsap_g(){

        $notap_id = $_POST['notap_id'];
        $telefono_movil = $_POST['telefono_movil'];        

        $nota = $this->notas_model->select($notap_id);

        if ($nota->telefono_movil_1 == '') {
            $this->db->where('ruc',$nota->ruc);
            $this->db->update('clientes',array('telefono_movil_1'=> $telefono_movil));
        }

        echo json_encode(['status' => STATUS_OK, 'msg' => 'Mensaje enviado correctamente']);
        exit();        
    }

    //ALEXANDER FERNANDEZ 04-08-2020
    public function modal_envio_email_g(){

        $notap_id = $_POST['notap_id'];
        $mailcc = $_POST['correo'];        
        //Correo de Empresa
        $correo = $this->db->from("correo")
                           ->get()
                           ->row();
        //Datos de la Empresa /
        $empresa = $this->db->from("empresas")
                            ->where("id",1)
                            ->get()
                            ->row();

        $this->load->library('email'); 
        // Configure email library
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = $correo->correo_host;
        $config['smtp_port'] = $correo->correo_port;
        $config['smtp_user'] = $correo->correo_user;
        $config['smtp_pass'] = $correo->correo_pass;
        $config['smtp_crypto'] = $correo->correo_cifrado;
        $config['charset']='utf-8'; // Default should be utf-8 (this should be a text field) 
        $config['newline']="\r\n"; //"\r\n" or "\n" or "\r". DEFAULT should be "\r\n" 
        $config['crlf'] = "\r\n"; //"\r\n" or "\n" or "\r" DEFAULT should be "\r\n" 
        $config['mailtype'] = 'html';

        $this->email->initialize($config);

        $nota = $this->notas_model->select($notap_id);
        if($nota->email == ''){
            $this->db->where('ruc', $nota->ruc);
            $this->db->update('clientes',array('email' => $mailcc));
        }                

        //echo '123';exit();
        //CREANDO PDF
        $this->create_pdf($notap_id);
        $file_pdf = APPPATH . "files_pdf/nota_venta/" .$empresa->ruc.'-NP'.$nota->notap_correlativo . ".pdf";
        //echo $file_pdf;exit;
        
        $this->email->attach($file_pdf);
        $sender_email = $correo->correo_user;
        $sender_username = $empresa->empresa;  

        // Sender email address
        $this->email->from($sender_email, $sender_username);  
        $this->email->to($mailcc);
        $this->email->cc('fernandezdelacruza@gmail.com');


        $buscar=array(chr(13).chr(10), "\r\n", "\n", "\r");
        $reemplazar=array("", "", "", "");                                       
        $cliente_razon_social = str_ireplace($buscar,$reemplazar,$this->sanear_string(utf8_decode($nota->razon_social)));
        $cliente_razon_social = str_replace("&", "Y", trim(utf8_decode($cliente_razon_social)));

        $tipoDocumentoFormat = 'NOTA VENTA';
        $this->email->subject('COPIA '.$tipoDocumentoFormat.' '. $nota->notap_correlativo.'|'.$cliente_razon_social.'|'.$nota->ruc);

        $body  = 'Sres '.$nota->ruc.' '.$cliente_razon_social.'<br><br>';
        $body .= 'Sres '.$empresa->empresa.', '.'envía una '.$tipoDocumentoFormat.'<br><br>';

        $body .= '- TIPO: '.$tipoDocumentoFormat.'<br>';
        $body .= '- NUMERO: '.$nota->notap_correlativo.'<br>';        
        $body .= '- FECHA DE EMISIÓN: '.$nota->notap_fecha.'<br>';
        $body .= '- TOTAL: '.$nota->notap_total.'<br><br><br>';


        $body .= 'También se adjunta el archivo PDF en este email<br>';       
        $this->email->message($body);
       
        //Message in email         
        if (!$this->email->send()) {
            echo json_encode(['status'=>STATUS_FAIL,'msg'=>'Correo Invalido !']);
            exit();            
        } else {
            echo json_encode(['status'=>STATUS_OK,'msg'=>'Correo enviado con éxito !']);
            exit();            
        }
    }



    public function nuevo()
    {
    	$data = array();
    	$data['monedas'] = $this->monedas_model->select();
    	$data['empresas'] = $this->empresas_model->select();
    	$data['tipo_igv'] = $this->tipo_igv_model->select('', '', '', 0);
    	$data['tipo_item'] = $this->tipo_items_model->select();
        $data['consecutivo'] = $this->notas_model->maximoConsecutivo();
        $data['transportistas'] = $this->transportistas_model->select();
        $data['tipo_pagos']  = $this->tipo_pagos_model->select();
        $data['medida'] = $this->medida_model->select();
        $data['rowIgvActivo'] = $this->igv_model->selectIgvActivo();
        $data['vendedores'] = $this->db->where('tipo_empleado_id',20)->get('empleados')->result();
        
    	$this->accesos_model->menuGeneral();
    	$this->load->view('notas/nuevo', $data);
    	$this->load->view('templates/footer');
    }
    public function editar($idNota)
    {
    	$data['nota'] = $this->notas_model->select($idNota);
        //print_r($data['nota']->detalles);exit();
        $data['monedas'] = $this->monedas_model->select();
        $data['empresas'] = $this->empresas_model->select();
        $data['tipo_igv'] = $this->tipo_igv_model->select('', '', '', 0);
        $data['tipo_item'] = $this->tipo_items_model->select();
        $data['transportistas'] = $this->transportistas_model->select();
        $data['tipo_pagos']  = $this->tipo_pagos_model->select();
        $data['rowIgvActivo'] = $this->igv_model->selectIgvActivo(); 
        $data['vendedores'] = $this->db->where('tipo_empleado_id',20)->get('empleados')->result();
        $this->accesos_model->menuGeneral();       
    	$this->load->view('notas/nuevo', $data);
        $this->load->view('templates/footer');
    }
    public function guardarNota()
    {
    	$error = array();
    	if($_POST['cliente_id'] == '')
    	{
    		$error['cliente'] = 'falta ingresar cliente';
    	}
    	if($_POST['fecha'] == '')
    	{
    		$error['fecha'] = 'falta ingresar fecha';
    	}
    	if($_POST['moneda_id'] == '')
    	{
    		$error['moneda_id'] = 'falta ingresar moneda';
    	}
    	if($_POST['direccion'] == '')
    	{
    		$error['direccion'] = 'falta ingresar direccion';
    	}


        //VALIDACION DE INGRESO DE PRODUCTOS 23-09-2020
        $idproduc = $_POST['item_id'];
        $cantidad = $_POST['cantidad'];
        $descripcion = $_POST['descripcion'];
        

        $tieneProductos = false;
        $msg = 'no hay productos agregados.';
        $b = 0;
        foreach($idproduc as $value)
        {
            if($value!='')
            {                
               if($value==0){
                 if($descripcion[$b]==''){
                    $tieneProductos = false;
                    $msg = 'Ingrese descripción del producto.';
                    break;
                  }else if($medida[$b]==''){                    
                    $tieneProductos = false;
                    $msg = 'Seleccione una unidad de medida.';
                    break;
                  } else {
                    $tieneProductos = true;
                  }
                }else{
                    $tieneProductos = true;
                }  
            } else {
                $tieneProductos = false;
                $msg = 'hay un producto que no se ha registrado bien.';
                break;
            }
            $b++;
        }

        if(!$tieneProductos)
        {
            sendJsonData(['status'=>STATUS_FAIL, 'msg'=>$msg]);
            exit();            
        }
        $f = 0; 
        foreach($idproduc as $value){
          if($cantidad[$f]<=0){
            sendJsonData(['status'=>STATUS_FAIL, 'msg'=>'La cantidad del producto debe ser mayor a cero']);
            exit(); 
          }
          $f++;
        }
       
        $i = 0;
        foreach ($idproduc as $item) {
                    
                $this->db->where('prod_id',$idproduc[$i]);
                $dato_prod = $this->db->get('productos')->row();
                   
                    //$prod_stock = $this->productos_model->stock($idproduc[$i]);
                $prod_stock = $this->productos_model->getStockProductos($idproduc[$i],$this->session->userdata("almacen_id"));
                
                 if($dato_prod->prod_tipo==1){
                    if($_POST['notaId']==''){
                        if($cantidad[$i]==0 OR $cantidad[$i]>$prod_stock){
                           sendJsonData(['status'=>STATUS_FAIL, 'msg'=>'No hay stock disponible de '.$descripcion[$i]]);
                             exit();  
                            } 
                        }else{
                             $this->db->where('notapd_producto_id',$idproduc[$i]);
                             $this->db->where('notapd_notap_id',$_POST['notaId']);
                             $item_producto = $this->db->get('nota_pedido_detalle')->row();

                             if($cantidad[$i]==0 OR $cantidad[$i]>($prod_stock+$item_producto->notapd_cantidad)){
                                 sendJsonData(['status'=>STATUS_FAIL, 'msg'=>'No hay stock disponible de '.$descripcion[$i]]);
                                exit();  
                             }  
                        }                        
                    }                                   
                    $i++;                   
                }   
        
    	if(count($error) > 0)
    	{
    		$data = ['status'=>STATUS_FAIL,'tipo'=>1, 'errores'=>$error];
    		sendJsonData($data);
    		exit();
    	}    

        if(count($_POST['descripcion'])==0)
        {
            $data = ['status'=>STATUS_FAIL, 'tipo'=>2];
            sendJsonData($data);
            exit();
        }

    	//guardamos el producto
    	$notap_id = $this->notas_model->guardarNota();    	
    	if($notap_id > 0)
    	{            
     		echo json_encode(['status'=>STATUS_OK,'notap_id'=> $notap_id]);
     		exit();
    	}	
    }

    public function eliminar($idProducto)
    {
    	$result = $this->productos_model->eliminar($idProducto);
    	if($result)
    	{
     		sendJsonData(['status'=>STATUS_OK]);
     		exit();
    	}else
    	{
    		sendJsonData(['status'=>STATUS_FAIL]);
    		exit();
    	}    	
    }
    public function getMainList(){
              
        $rsDatos = $this->notas_model->getMainList();
        sendJsonData($rsDatos);          
    }
    public function getMainListDetail()
    {
        $rsDatos = $this->notas_model->getMainListDetail();
        sendJsonData($rsDatos);        
    }

    public function create_pdf($idNota){
        
        $rsNota = $this->db->from("nota_pedido as np")
                           ->join("monedas as mon", "np.notap_moneda_id=mon.id")
                           ->join("tipo_pagos tpg","np.notap_tipopago_id = tpg.id")
                           ->join("transportistas tra","np.notap_transportista_id = tra.transp_id")
                           ->where("notap_id", $idNota)
                           ->get()
                           ->row();

                //var_dump($rsNota);exit;
        /*formateamos fecha*/
        $rsNota->notap_fecha = (new DateTime($rsNota->notap_detalle))->format("d/m/Y");                   
        $rsDetalles =  $this->db->from("nota_pedido_detalle as f")
                                ->join('productos as p','p.prod_id=f.notapd_producto_id') 
                           
                           ->where("f.notapd_notap_id", $idNota)
                           ->get()
                           ->result();

                           //var_dump($rsDetalles);exit;

        $rsNota->detalles = $rsDetalles;                                     

        $rsEmpresa = $this->db->from("empresas")
                              ->where("id", 1)
                              ->get()
                              ->row();  

        $rsCliente =  $this->db->from("clientes")
                              ->where("id", $rsNota->notap_cliente_id)
                              ->get()
                              ->row();
        
        $rsEmpleado =  $this->db->from("empleados")
                      ->where("id", $rsNota->notap_empleado_insert)
                      ->get()
                      ->row();
                                               
        $data = [
                    "empresa" => $rsEmpresa,
                    "nota"    => $rsNota,
                    "cliente" => $rsCliente,
                    "empleado" => $rsEmpleado,
                ];                   
        $html = $this->load->view("templates/nota.php",$data,true); 

        ////////////////////////////////////////
        $archivo = $rsEmpresa->ruc.'-NP'.$rsNota->notap_correlativo.'.pdf';
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper('A4', 'portrait');
        $this->pdf->render();        
        $contenido = $this->pdf->output();

        $bytes = file_put_contents(APPPATH.'files_pdf/nota_venta/'.$archivo, $contenido);
        return true;
    }

    public function decargarPdf($idNota)
    {
        $rsNota = $this->db->from("nota_pedido as np")
                           ->join("monedas as mon", "np.notap_moneda_id=mon.id")
                           ->join("tipo_pagos tpg","np.notap_tipopago_id = tpg.id")
                           ->join("transportistas tra","np.notap_transportista_id = tra.transp_id")
                           ->where("notap_id", $idNota)
                           ->get()
                           ->row();

                //var_dump($rsNota);exit;
        /*formateamos fecha*/
        $rsNota->notap_fecha = (new DateTime($rsNota->notap_fecha))->format("d/m/Y");                   
        $rsDetalles =  $this->db->from("nota_pedido_detalle as f")
                                ->join('productos as p','p.prod_id=f.notapd_producto_id','left') 
                           
                           ->where("f.notapd_notap_id", $idNota)
                           ->get()
                           ->result();

                           //var_dump($rsDetalles);exit;

        $rsNota->detalles = $rsDetalles;                                     

        $rsEmpresa = $this->db->from("empresas")
                              ->where("id", 1)
                              ->get()
                              ->row();  

        $rsCliente =  $this->db->from("clientes")
                              ->where("id", $rsNota->notap_cliente_id)
                              ->get()
                              ->row();
        
        $rsEmpleado =  $this->db->from("empleados")
                      ->where("id", $rsNota->notap_empleado_insert)
                      ->get()
                      ->row();
                                               
        $data = [
                    "empresa" => $rsEmpresa,
                    "nota"    => $rsNota,
                    "cliente" => $rsCliente,
                    "empleado" => $rsEmpleado,
                ];                   
        $html = $this->load->view("templates/nota.php",$data,true); 
//        // Cargamos la librería
//        $this->load->library('pdfgenerator');
//        // definamos un nombre para el archivo. No es necesario agregar la extension .pdf
//        $filename = 'comprobante_pago';
//        // generamos el PDF. Pasemos por encima de la configuración general y definamos otro tipo de papel
//        $this->pdfgenerator->generate($html, $filename, true,'A4','portrait'); 
        
        ////////////////////////////////////////
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper('A4', 'portrait');
        $this->pdf->render();
        $this->pdf->stream("N.Venta.NP-$idNota.pdf",
            array("Attachment"=>0)
        );
    }
    public function buscador_item() {
        $item = $this->input->get('term');       
        echo json_encode($this->productos_model->selectAutocompleteprodSC($item));
    }    

    public function eliminar_nota($id){
        $this->db->set('notap_estado', 2);
        $this->db->where('notap_id',$id);
        $this->db->update('nota_pedido');

        $this->db->where('notap_id',$id);
        $nt = $this->db->get('nota_pedido')->row();

        $this->db->where('notapd_notap_id',$id);
        $adp = $this->db->get('nota_pedido_detalle')->result();
        
        if($nt->notap_descontar==1){
            foreach ($adp as $a) {
                $result = $this->db->from('productos')
                               ->where('prod_id',$a->notapd_producto_id)
                               ->get()
                               ->row();
                  if($result->prod_tipo==1){
                      
                              //$this->UpdateEstadoDisponible($idproduc[$i], $cantidad[$i]);  
                               $stock = $this->productos_model->getStockProductos($a->notapd_producto_id,$this->session->userdata("almacen_id"));
                               $nueva_cantidad = floatval($stock)+floatval($a->notapd_cantidad);


                               $kardex = array(
                                'k_fecha' => date('Y-m-d'),
                                'k_almacen' => $this->session->userdata("almacen_id"),
                                'k_tipo' => 3,
                                'k_operacion_id' => $idNota,
                                'k_serie' => 'NP',
                                'k_concepto' => 'RESTAURAR',     
                                'k_producto' => $a->notapd_producto_id,
                                'k_ecantidad' => $a->notapd_cantidad,
                                'k_excantidad' => $nueva_cantidad,
                                                 
                               );

                               $this->db->insert('kardex', $kardex);  
                      
                    } 
            }
            
        }
        

        $this->session->set_flashdata('mensaje', 'Nota de pedido eliminada con exito');
        redirect(base_url() . "index.php/notas/index");
    }

    public function quitarStock($idProducto,$cantidad)
    {
      //solo quitaremos de stock a los producto que pertenezacan a esa compra
      $this->db->where("ejm_producto_id",$idProducto);
      $this->db->where('ejm_almacen_id',$this->session->userdata('almacen_id'));
      $this->db->where('ejm_estado',ST_PRODUCTO_VENDIDO);
      $ejm = $this->db->get('ejemplar')->result();

      for($x=0;$x<$cantidad;$x++) {
           $this->db->where('ejm_id',$ejm[$x]->ejm_id);
           $this->db->set("ejm_estado", ST_PRODUCTO_DISPONIBLE);
           $this->db->update("ejemplar");  
      }
        
                            
    }

    public function exportarExcel()
    {
        require_once (APPPATH .'libraries/Numletras.php');

        
        if($_GET['cliente']!='undefined'){
            $this->db->where('notap_cliente_id',$_GET['cliente']);
        }  
       
        if($_GET['fecha_inicio'] != 'null'){
            $fecha_inicio =  (new DateTime($_GET['fecha_inicio']))->format('Y-m-d');
            $this->db->where("DATE(com.notap_fecha) >=", $fecha_inicio);
        }

        if($_GET['fecha_fin'] != 'null'){
            $fecha_fin =  (new DateTime($_GET['fecha_fin']))->format('Y-m-d');
            $this->db->where("DATE(com.notap_fecha) <=", $fecha_fin);
        }

        if($_GET['correlativo']!='null'){        
            $this->db->where('notap_correlativo',$_GET['correlativo']);
        }

        if($_GET['vendedor_id'] != '')
            $this->db->where('emp.id',$_GET['vendedor_id']);        

        if($this->session->userdata('accesoEmpleado') != '')
            $this->db->where('emp.id',$this->session->userdata('empleado_id'));                                
                
        $resultComprobantes = $this->db->from("nota_pedido com")
                                       ->join("nota_pedido_detalle i","com.notap_id=i.notapd_notap_id")
                                       ->join("productos pro","pro.prod_id=i.notapd_producto_id","left")
                                       ->join("categoria c","pro.prod_categoria_id=c.cat_id","left")
                                       ->join("medida m","pro.prod_medida_id=m.medida_id","left")
                                       ->join("empleados emp","emp.id=com.notap_empleado_insert")
                                       //->where("pro.prod_estado", ST_ACTIVO)
                                       ->where('com.notap_estado',1) 
                                       ->order_by('com.notap_id DESC, i.notapd_id')
                                       ->get()
                                       ->result();        

        $this->load->library('excel');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
                ->setLastModifiedBy("Maarten Balliauw")
                ->setTitle("Office 2007 XLSX Test Document")
                ->setSubject("Office 2007 XLSX Test Document")
                ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                ->setKeywords("office 2007 openxml php")
                ->setCategory("Test result file");

        $i=2;                                    

        $myWorkSheet = new PHPExcel_Worksheet($objPHPExcel, 'Detalle');
        $objPHPExcel->addSheet($myWorkSheet, 1);
        $objPHPExcel->setActiveSheetIndex(1);


        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
         

        $objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("B1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("C1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("D1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("E1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("F1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("G1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("H1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("I1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("J1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("K1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("L1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("M1")->getFont()->setBold(true);
     
        $objPHPExcel->getActiveSheet()->setCellValue('A1', "CORRELATIVO"); 
        $objPHPExcel->getActiveSheet()->setCellValue('B1', "FECHA"); 
        $objPHPExcel->getActiveSheet()->setCellValue('C1', "VENDEDOR"); 
        $objPHPExcel->getActiveSheet()->setCellValue('D1', "DOCUMENTO"); 
        $objPHPExcel->getActiveSheet()->setCellValue('E1', "NUM. DOC.");      
        $objPHPExcel->getActiveSheet()->setCellValue('F1', "CLIENTE"); 
        $objPHPExcel->getActiveSheet()->setCellValue('G1', "CODIGO");
        $objPHPExcel->getActiveSheet()->setCellValue('H1', "CATEGORIA");
        $objPHPExcel->getActiveSheet()->setCellValue('I1', "UNIDAD/MEDIDA");
        $objPHPExcel->getActiveSheet()->setCellValue('J1', "DESCRIPCION");
        $objPHPExcel->getActiveSheet()->setCellValue('K1', "PRECIO UNITARIO");
        $objPHPExcel->getActiveSheet()->setCellValue('L1', "CANTIDAD");
        $objPHPExcel->getActiveSheet()->setCellValue('M1', "IMPORTE TOTAL");

        $suma_total = 0;
        foreach ($resultComprobantes as $value) {           

            /*datos cliente*/
            $this->db->where('id', $value->notap_cliente_id);
            $this->db->from('clientes');
            $queryCliente = $this->db->get();
            $rsCliente = $queryCliente->row();

            // tipo documento cliente
            $tipo_dcli = 'DNI';
            if (strlen($rsCliente->ruc)>8) {
                $tipo_dcli = 'RUC';
            }

            if($value->notap_estado != 1){
                $value->notapd_total = '0.00';
            }


            $objPHPExcel->getActiveSheet()
                        ->setCellValue('A'.$i, $value->notap_correlativo)
                        ->setCellValue('B'.$i, $value->notap_fecha)
                        ->setCellValue('C'.$i, $value->apellido_paterno." ".$value->apellido_materno.", ".$value->nombre)
                        ->setCellValue('D'.$i, $tipo_dcli)
                        ->setCellValue('E'.$i, $rsCliente->ruc)
                        ->setCellValue('F'.$i, $rsCliente->razon_social)
                        ->setCellValue('G'.$i, $value->prod_codigo)
                        ->setCellValue('H'.$i, $value->cat_nombre)
                        ->setCellValue('I'.$i, $value->medida_nombre)
                        ->setCellValue('J'.$i, $value->notapd_descripcion)
                        ->setCellValue('K'.$i, $value->notapd_precio_unitario)
                        ->setCellValue('L'.$i, $value->notapd_cantidad)                        
                        ->setCellValue('M'.$i, $value->notapd_total);

            $suma_total = $suma_total + $value->notapd_total;
            $i++;
        }

         $objPHPExcel->getActiveSheet()->getStyle('L' . ($i +1 ))->getFont()->setBold(true);
         $objPHPExcel->getActiveSheet()->setCellValue('L' . ($i +1 ), 'MONTO TOTAL');
         $objPHPExcel->getActiveSheet()->setCellValue('M' . ($i +1 ), $suma_total);


        $filename = 'Nota_pedido---' . date("d-m-Y") . '---' . rand(1000, 9999) . '.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }
    
    public function exportarExcelFormatoVendedor(){
        
        if($_GET['cliente']!='undefined'){
            $this->db->where('notap_cliente_id',$_GET['cliente']);
        }                  
       
        $fecha_inicio = '';
        $fecha_fin = '';
        $label_inicio = '';
        $label_fin = '';
        if(($_GET['fecha_inicio']!='null') && ($_GET['fecha_fin']!='null')) {
            $fecha_inicio = (new DateTime($_GET['fecha_inicio']))->format("Y-m-d");          
            $fecha_fin = (new DateTime($_GET['fecha_fin']))->format("Y-m-d");          
            $this->db->where('DATE_FORMAT(notap_fecha, "%Y-%m-%d") >= ', $fecha_inicio);
            $this->db->where('DATE_FORMAT(notap_fecha, "%Y-%m-%d") <= ', $fecha_fin);
            
            $label_inicio = 'Fecha Desde:';
            $label_fin = 'Fecha Hasta:';
        } 

        if($_GET['correlativo']!='null'){        
            $this->db->where('notap_correlativo',$_GET['correlativo']);
        }
        
        //CONDICION SI USUARIO ES VENDEDOR        
        $vendedor_id = ($this->session->userdata('tipo_empleado_id') == 20) ? $this->session->userdata('empleado_id') : $_GET['vendedor_id'];           
        
        $datos = $this->empleados_model->reporteVendedor($vendedor_id, $fecha_inicio, $fecha_fin, $cliente_id);        
         
        $this->load->library('excel');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
                ->setLastModifiedBy("Maarten Balliauw")
                ->setTitle("Office 2007 XLSX Test Document")
                ->setSubject("Office 2007 XLSX Test Document")
                ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                ->setKeywords("office 2007 openxml php")
                ->setCategory("Test result file");                                            

        $myWorkSheet = new PHPExcel_Worksheet($objPHPExcel, 'Detalle');
        $objPHPExcel->addSheet($myWorkSheet, 1);
        $objPHPExcel->setActiveSheetIndex(1);
     
        $objPHPExcel->getActiveSheet()->setCellValue('A1', $label_inicio);
        $objPHPExcel->getActiveSheet()->setCellValue('B1', $fecha_inicio);
        $objPHPExcel->getActiveSheet()->setCellValue('A2', $label_fin);
        $objPHPExcel->getActiveSheet()->setCellValue('B2', $fecha_fin);
        
        $objPHPExcel->getActiveSheet()->setCellValue('A4', "N."); 
        $objPHPExcel->getActiveSheet()->setCellValue('B4', "VENDEDOR"); 
        $objPHPExcel->getActiveSheet()->setCellValue('C4', "CANTIDAD"); 
        $objPHPExcel->getActiveSheet()->setCellValue('D4', "UNIDAD"); 
        $objPHPExcel->getActiveSheet()->setCellValue('E4', "PRODUCTO"); 
        
        $i=5;
        foreach ($datos as $value) {           

            $objPHPExcel->getActiveSheet()
                        ->setCellValue('A'.$i, $i - 1)
                        ->setCellValue('B'.$i, $value['apellido_paterno']." ".$value['apellido_materno'].", ".$value['nombre'])
                        ->setCellValue('C'.$i, $value['cantidad'])
                        ->setCellValue('D'.$i, $value['medida_nombre'])
                        ->setCellValue('E'.$i, $value['prod_nombre']);
            $i++;
        }

        $filename = 'Reporte Vendedor---' . date("d-m-Y") . '---' . rand(1000, 9999) . '.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }
    
    public function decargarPdf_ticket($idNota){
        $rsNota = $this->db->from("nota_pedido as np")
                           ->join("monedas as mon", "np.notap_moneda_id=mon.id")
                           ->join("tipo_pagos as tpg", "np.notap_tipopago_id=tpg.id")
                           ->join('transportistas tra','np.notap_transportista_id = tra.transp_id')
                           ->where("notap_id", $idNota)
                           ->get()
                           ->row();
                           //var_dump($rsNota);exit;

        /*formateamos fecha*/
        $rsNota->notap_fecha = (new DateTime($rsNota->notap_fecha))->format("d/m/Y h:i:s");                   
        $rsDetalles =  $this->db->from("nota_pedido_detalle as f")
                                ->join('productos as p','p.prod_id=f.notapd_producto_id','left') 
                           
                           ->where("f.notapd_notap_id", $idNota)
                           ->get()
                           ->result();

        //HEIGHT TICKET 21-09-2020
        $countItems = count($rsDetalles);
        $ticketHeight =  $countItems*22;
        $ticketHeight = ($ticketHeight > 440) ? $ticketHeight : 440;                           

        $rsNota->detalles = $rsDetalles;                                     

        $rsEmpresa = $this->db->from("empresas")
                              ->where("id", 1)
                              ->get()
                              ->row();  

        $rsCliente =  $this->db->from("clientes")
                              ->where("id", $rsNota->notap_cliente_id)
                              ->get()
                              ->row();                      
                      //var_dump($rsCliente)
 
        $data = [
                    "empresa" => $rsEmpresa,
                    "nota"    => $rsNota,
                    "cliente" => $rsCliente,
                ];
        $html = $this->load->view("templates/nota_ticket.php",$data,true); 
        
//        $this->load->library('pdfgenerator');
//        $filename = 'comprobante_pago';
//        $this->pdfgenerator->generate($html, $filename, true,'A4','portrait');
        
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper(array(0,0,85,$ticketHeight), 'portrait');
        $this->pdf->render();
        $this->pdf->stream("N.Venta.NP-$idNota.pdf",
            array("Attachment"=>0)
        );
    }


    public function comprobanteTributario(){
    
    $rsNota = $this->notas_model->select($this->uri->segment(3));    

    //var_dump($rsNota);
    $cabecera = array();
    //VALIDANDO QUE NO VIENE UNA BUSQUEDA 0 DEVELVE STOCK, 1 NO DEVUELE
    $cabecera['notap_id'] = ($this->uri->segment(4) == 1) ? '' : $rsNota->notap_id;        


    
    $tipo_cliente_id = $rsNota->tipo_cliente_id;
    //P.NATURAL,PJURIDICA
    if($tipo_cliente_id == 1) $data['tipo_documento_id'] = 3;
    if($tipo_cliente_id == 2) $data['tipo_documento_id'] = 1;    


    
    $cabecera['cliente_id'] = $rsNota->notap_cliente_id;
    $cabecera['cliente_razon_social'] = $rsNota->razon_social;
    $cabecera['cliente_domicilio'] = $rsNota->domicilio1;
    $cabecera['moneda_id']  = $rsNota->notap_moneda_id;
    $cabecera['total_a_pagar'] = $rsNota->notap_total;
    $cabecera['comprobante_anticipo'] = 0;
    $cabecera['observaciones'] = $rsNota->notap_observaciones;
    $cabecera['almacen_id'] = $rsNota->notap_almacen; 
    $cabecera['transportista_id'] = $rsNota->notap_transportista_id;    
    $cabecera['tipo_pago_id'] = $rsNota->notap_tipopago_id;
    $cabecera['placa'] = $rsNota->placa;


    //var_dump($rsNota);exit;
    $data['comprobante'] = $cabecera;

    $items = array();
    foreach ($rsNota->detalles as $value) {                
                                        
            $item['descripcion'] = $value->notapd_descripcion;
            $item['producto_id'] = $value->notapd_producto_id;
            $item['cantidad'] = $value->notapd_cantidad;
            $item['tipo_igv_id'] =  1;
            $item['importe'] = $value->notapd_precio_unitario;
            $item['importeCosto'] = $value->notapd_importeCosto;
            $item['total'] = $value->notapd_total;
            $item['totalCosto'] = $value->notapd_totalCosto;
            $item['totalVenta'] = $value->notapd_totalVenta;
            $items[] = $item;                    
    }

    $data['items'] = $items;
    $data['tipo_igv'] = $this->tipo_igv_model->select();
    $data['monedas']  = $this->monedas_model->select();
    $data['tipo_documentos'] = $this->tipo_documentos_model->select();    
    $data['empresa'] = $this->empresas_model->select(1);
    $data['transportistas'] = $this->transportistas_model->select();
    $data['tipo_pagos'] = $this->tipo_pagos_model->select();
    $data['medida'] = $this->medida_model->select();
    $data['rowIgvActivo'] = $this->igv_model->selectIgvActivo();


    $data['configuracion'] = $this->db->from('comprobantes_ventas')->get()->row();


        $this->load->view('templates/header_administrador');
        $this->load->view('comprobantes/generarComprobante',$data);
        $this->load->view('templates/footer');
    }

    public function sanear_string($string) {

        $string = trim(utf8_encode($string));
//        $string = str_replace(
//            array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
//            array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
//            $string
//        );
        $string = str_replace(
                array('à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'), array('a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'), $string
        );

//        $string = str_replace(
//            array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
//            array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
//            $string
//        );
        $string = str_replace(
                array('è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'), array('e', 'e', 'e', 'E', 'E', 'E', 'E'), $string
        );

//        $string = str_replace(
//            array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
//            array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
//            $string
//        );
        $string = str_replace(
                array('ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'), array('i', 'i', 'i', 'I', 'I', 'I', 'I'), $string
        );

//        $string = str_replace(
//            array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
//            array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
//            $string
//        );
        $string = str_replace(
                array('ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'), array('o', 'o', 'o', 'O', 'O', 'O', 'O'), $string
        );

//        $string = str_replace(
//            array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
//            array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
//            $string
//        );        
        $string = str_replace(
                array('ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'), array('u', 'u', 'u', 'U', 'U', 'U', 'U'), $string
        );

//        $string = str_replace(
//            array('ñ', 'Ñ', 'ç', 'Ç'),
//            array('n', 'N', 'c', 'C',),
//            $string
//        );
        $string = str_replace(
                array('ç', 'Ç'), array('c', 'C',), $string
        );

        //Esta parte se encarga de eliminar cualquier caracter extraño
//        $string = str_replace(
//            array("\\", "¨", "º", "-", "~",
//                 "#", "@", "|", "!", "\"",
//                 "·", "$", "%", "&", "/",
//                 "(", ")", "?", "'", "¡",
//                 "¿", "[", "^", "`", "]",
//                 "+", "}", "{", "¨", "´",
//                 ">", "< ", ";", ",", ":",
//                 ".", " "),
//            '',
        $string = str_replace(
                array("\\", "¨", "º", "-", "~",
            "|", "!", "\"",
            "·", "&", "/",
            "(", ")", "'", "¡",
            "¿", "[", "^", "`", "]",
            "}", "{", "¨", "´"
                ), '', $string
        );
        $string = str_replace(
                array("\n"
                ), ' ', $string
        );
        return $string;
    }   
}