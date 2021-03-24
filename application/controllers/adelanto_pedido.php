<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Adelanto_pedido extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();        
        date_default_timezone_set('America/Lima');
        $this->load->model('accesos_model');
        $this->load->model('monedas_model'); 
        $this->load->model('empresas_model');
        $this->load->model('tipo_igv_model');
        $this->load->model('tipo_items_model');
        $this->load->model('notas_model');
        $this->load->model('productos_model'); 
        $this->load->model('adelanto_pedido_model'); 
        $this->load->helper('ayuda');        
//
        $empleado_id = $this->session->userdata('empleado_id');
        if (empty($empleado_id)) {
            $this->session->set_flashdata('mensaje', 'No existe sesion activa');
            redirect(base_url());
        }
    }

    public function index()
    {
    	$data['empresa'] = $this->empresas_model->select();
        $this->accesos_model->menuGeneral();
        $this->load->view('adelanto_pedido/basic_index', $data);
        $this->load->view('templates/footer');    	
    }
    public function nuevo()
    {
    	$data = array();
    	$data['monedas'] = $this->monedas_model->select();
    	$data['empresas'] = $this->empresas_model->select();
    	$data['tipo_igv'] = $this->tipo_igv_model->select('', '', '', 0);
    	$data['tipo_item'] = $this->tipo_items_model->select();
        $data['consecutivo'] = $this->adelanto_pedido_model->maximoConsecutivo();
    	$this->accesos_model->menuGeneral();
    	$this->load->view('adelanto_pedido/nuevo', $data);
    	$this->load->view('templates/footer');
    }
    public function editar($idNota)
    {
    	$data['nota'] = $this->adelanto_pedido_model->select($idNota);
        //print_r($data['nota']->detalles);exit();
        $data['monedas'] = $this->monedas_model->select();
        $data['empresas'] = $this->empresas_model->select();
        $data['tipo_igv'] = $this->tipo_igv_model->select('', '', '', 0);
        $data['tipo_item'] = $this->tipo_items_model->select(); 
        $this->accesos_model->menuGeneral();       
    	$this->load->view('adelanto_pedido/nuevo', $data);
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

                $idproduc = $_POST['item_id'];
                $cantidad = $_POST['cantidad'];
                $descripcion = $_POST['descripcion'];
                $i = 0;
                foreach ($idproduc as $item) {
                    
                    $this->db->where('prod_id',$idproduc[$i]);
                    $dato_prod = $this->db->get('productos')->row();

                    $prod_stock = $this->productos_model->stock($idproduc[$i]);

                    if($dato_prod->prod_tipo==1){
                         if($_POST['notaId']==''){
                            if($cantidad[$i]==0 OR $cantidad[$i]>$prod_stock){
                             sendJsonData(['status'=>STATUS_FAIL, 'msg'=>'No hay stock disponible de '.$descripcion[$i]]);
                              exit();  
                            } 
                        }else{
                             $this->db->where('notapd_producto_id',$idproduc[$i]);
                             $this->db->where('notapd_notap_id',$_POST['notaId']);
                             $item_producto = $this->db->get('adelanto_pedido_detalle')->row();

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
    	$result = $this->adelanto_pedido_model->guardarNota();
    	
    	if($result > 0)
    	{
            
     		sendJsonData(['status'=>STATUS_OK]);
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
    public function getMainList()
    {
    	$rsDatos = $this->adelanto_pedido_model->getMainList();
    	sendJsonData($rsDatos);
    }
    public function getMainListDetail()
    {
        $rsDatos = $this->adelanto_pedido_model->getMainListDetail();
        sendJsonData($rsDatos);        
    }
    public function decargarPdf($idNota)
    {
        $rsNota = $this->db->from("adelanto_pedido as np")
                           ->join("monedas as mon", "np.notap_moneda_id=mon.id")
                           ->where("notap_id", $idNota)
                           ->get()
                           ->row();
        /*formateamos fecha*/
        $rsNota->notap_fecha = (new DateTime($rsNota->notap_detalle))->format("d/m/Y");                   
        $rsDetalles =  $this->db->from("adelanto_pedido_detalle")
                           ->where("notapd_notap_id", $idNota)
                           ->get()
                           ->result();

        $rsNota->detalles = $rsDetalles;                                     

        $rsEmpresa = $this->db->from("empresas")
                              ->where("id", 1)
                              ->get()
                              ->row();  

        $rsCliente =  $this->db->from("clientes")
                              ->where("id", $rsNota->notap_cliente_id)
                              ->get()
                              ->row();                      
                                               
        $data = [
                    "empresa" => $rsEmpresa,
                    "nota"    => $rsNota,
                    "cliente" => $rsCliente,
                ];                   
        $html = $this->load->view("templates/adelanto_pedido.php",$data,true);                   
        /*escribimos archivo*/
        $archivo = 'NP-'.$rsNota->notap_correlativo;
        $rutaArchivoHtml = FCPATH.'files\pdf\\'.$archivo.'.html';
        $rutaArchivoPdf = FCPATH.'files\pdf\\'.$archivo.'.pdf';
        $file = fopen($rutaArchivoHtml,'w');
        fwrite($file, $html);
        fclose($file);
        /*convertimos el html en pdf*/
        exec('"C:\Program Files\wkhtmltopdf\bin\wkhtmltopdf" '.$rutaArchivoHtml.' '.$rutaArchivoPdf);
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="'.$archivo.'.pdf"');
        readfile($rutaArchivoPdf);
        /*aliminamos archivos creados html, pdf*/
        unlink($rutaArchivoHtml);
        unlink($rutaArchivoPdf);                        
        
        
    }
    public function buscador_item() {
        $item = $this->input->get('term');       
        echo json_encode($this->productos_model->selectAutocompleteprod($item));
    }    

    public function eliminar_nota($id){
        $this->db->set('notap_estado', 2);
        $this->db->where('notap_id',$id);
        $this->db->update('adelanto_pedido');

        $this->db->where('notap_id',$id);
        $nt = $this->db->get('adelanto_pedido')->row();

        $this->db->where('notapd_notap_id',$id);
        $adp = $this->db->get('adelanto_pedido_detalle')->get();
        
        if($nt->notap_descontar==1){
            foreach ($adp as $a) {
                $this->quitarStock($a->notapd_producto_id,$a->notapd_cantidad);
            }
            
        }

        $this->quitarStock($adp->notapd_producto_id);

        $this->session->set_flashdata('mensaje', 'Adelanto de pedido eliminada con exito');
        redirect(base_url() . "index.php/adelanto_pedido/index");
    }

    public function quitarStock($idProducto)
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

        
        if($_GET['cliente']!='undefined')
        {
            $this->db->where('notap_cliente_id',$_GET['cliente']);
        }  
        /*if($this->uri->segment(4)!='null')
        {
            $this->db->where('tipo_documento_id', $this->uri->segment(4));
        }*/        
        if($_GET['fecha']!='null') {

            $fecha = (new DateTime($_GET['fecha']))->format("Y-m-d");          
            $this->db->where('DATE_FORMAT(notap_fecha, "%Y-%m-%d") >= ', $fecha);
        } 

        if($_GET['correlativo']!='null')
        {
        
            $this->db->where('notap_correlativo',$_GET['correlativo']);
        }
        

        //$this->db->where('eliminado', 0);   
        //$this->db->group_by('id');
        $this->db->where('notap_estado',1);
        $this->db->from("adelanto_pedido");

        $query = $this->db->get();
        //print_r($query);exit();

        $resultComprobantes = $query->result();
        //print_r($resultComprobantes);exit();

        $this->load->library('excel');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
                ->setLastModifiedBy("Maarten Balliauw")
                ->setTitle("Office 2007 XLSX Test Document")
                ->setSubject("Office 2007 XLSX Test Document")
                ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                ->setKeywords("office 2007 openxml php")
                ->setCategory("Test result file");

        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', "CORRELATIVO"); 
        $objPHPExcel->getActiveSheet()->setCellValue('B1', "FECHA");
        $objPHPExcel->getActiveSheet()->setCellValue('C1', "TIPO DOC.");
        $objPHPExcel->getActiveSheet()->setCellValue('D1', "NUMERO DOC.");
        $objPHPExcel->getActiveSheet()->setCellValue('E1', "CLIENTE"); 
        $objPHPExcel->getActiveSheet()->setCellValue('F1', "MONEDA");
        $objPHPExcel->getActiveSheet()->setCellValue('G1', "SUBTOTAL");
        $objPHPExcel->getActiveSheet()->setCellValue('H1', "IGV");
        $objPHPExcel->getActiveSheet()->setCellValue('I1', "IMPORTE TOTAL");        

        //MONTO EN LETRAS
        //$num = new Numletras();
        /*cargamos datos al excel*/
        $i=2;

        /*print_r($resultComprobantes);
        die();*/
     
        foreach($resultComprobantes as $item)
        {
            /*datos cliente*/
            $this->db->where('id', $item->notap_cliente_id);
            $this->db->from('clientes');
            $queryCliente = $this->db->get();
            $rsCliente = $queryCliente->row();

           
            /*datos moneda*/ 
            $this->db->where('id', $item->notap_moneda_id);
            $this->db->from('monedas');
            $queryMoneda = $this->db->get();
            $rsMoneda = $queryMoneda->row(); 

            $fecha = new DateTime($item->notap_fecha);
                  
            // tipo documento cliente
            $tipo_dcli = 'DNI';
            if (strlen($rsCliente->ruc)>8) {
                $tipo_dcli = 'RUC';
            }
            $num = $i-1;

            /*$importeAPagar = $item->notap_total;
            $importeAPagar = explode(".",$importeAPagar);*/
            //$importeLetras = $num->num2letras(intval($importeAPagar[0]));//numero entero
            //$importeLetras .= $importeLetras.' con '.$importeAPagar[1].'/100 '.$rsMoneda->moneda;
            $objPHPExcel->getActiveSheet()
                        ->setCellValue('A' . ($i), $item->notap_correlativo)
                        ->setCellValue('B' . ($i), ($fecha)->format('d-m-Y'))
                        ->setCellValue('C' . ($i), $tipo_dcli)
                        ->setCellValue('D' . ($i), $rsCliente->ruc)
                        ->setCellValue('E' . ($i), $rsCliente->razon_social)
                        ->setCellValue('F' . ($i), $rsMoneda->moneda)
                        ->setCellValue('G' . ($i), $item->notap_subtotal)
                        ->setCellValue('H' . ($i), $item->notap_igv)
                        ->setCellValue('I' . ($i), $item->notap_total);                        
               
            $i++;

        }

        $result = $this->db->from("adelanto_pedido_detalle i")
                            ->join("adelanto_pedido com","com.notap_id=i.notapd_notap_id")
                            ->join("productos pro","pro.prod_id=i.notapd_producto_id")
                            ->join("categoria c","pro.prod_categoria_id=c.cat_id")
                            ->join("medida m","pro.prod_medida_id=m.medida_id")
                            ->where("pro.prod_estado", ST_ACTIVO)
                            ->where('com.notap_estado',1)
                            ->get()
                            ->result(); 

        $i=2;                                       

        $myWorkSheet = new PHPExcel_Worksheet($objPHPExcel, 'Detalle');
        $objPHPExcel->addSheet($myWorkSheet, 1);
        $objPHPExcel->setActiveSheetIndex(1);
     
        $objPHPExcel->getActiveSheet()->setCellValue('A1', "CORRELATIVO"); 
        $objPHPExcel->getActiveSheet()->setCellValue('B1', "DOCUMENTO"); 
        $objPHPExcel->getActiveSheet()->setCellValue('C1', "NUM. DOC.");      
        $objPHPExcel->getActiveSheet()->setCellValue('D1', "CLIENTE"); 

        $objPHPExcel->getActiveSheet()->setCellValue('E1', "CODIGO");
        $objPHPExcel->getActiveSheet()->setCellValue('F1', "CATEGORIA");
        $objPHPExcel->getActiveSheet()->setCellValue('G1', "UNIDAD/MEDIDA");
        $objPHPExcel->getActiveSheet()->setCellValue('H1', "DESCRIPCION");
        $objPHPExcel->getActiveSheet()->setCellValue('I1', "PRECIO UNITARIO");
        $objPHPExcel->getActiveSheet()->setCellValue('J1', "CANTIDAD");
        $objPHPExcel->getActiveSheet()->setCellValue('K1', "SUBTOTAL");
        $objPHPExcel->getActiveSheet()->setCellValue('L1', "IGV");        
        $objPHPExcel->getActiveSheet()->setCellValue('M1', "IMPORTE TOTAL");

        foreach ($result as $value) {
           

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

            $objPHPExcel->getActiveSheet()
                        ->setCellValue('A'.$i, $value->notap_correlativo)
                        ->setCellValue('B'.$i, $tipo_dcli)
                        ->setCellValue('C'.$i, $rsCliente->ruc)
                        ->setCellValue('D'.$i, $rsCliente->razon_social)
                        ->setCellValue('E'.$i, $value->prod_codigo)
                        ->setCellValue('F'.$i, $value->cat_nombre)
                        ->setCellValue('G'.$i, $value->medida_nombre)
                        ->setCellValue('H'.$i, $value->prod_nombre)
                        ->setCellValue('I'.$i, $value->notapd_precio_unitario)
                        ->setCellValue('J'.$i, $value->notapd_cantidad)
                        ->setCellValue('K'.$i, $value->notapd_subtotal)
                        ->setCellValue('L'.$i, $value->notapd_igv)
                        ->setCellValue('M'.$i, $value->notapd_total);
            $i++;
        }


        $filename = 'adelanto_pedido---' . date("d-m-Y") . '---' . rand(1000, 9999) . '.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }


}