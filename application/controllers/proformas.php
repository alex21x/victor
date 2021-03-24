<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;    
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;


class Proformas extends CI_Controller {

    public function __construct()
    {
        parent::__construct();        
        date_default_timezone_set('America/Lima');
        $this->load->model('accesos_model');
        $this->load->model('empleados_model');
        $this->load->model('almacenes_model');
        $this->load->model('monedas_model'); 
        $this->load->model('empresas_model');
        $this->load->model('tipo_igv_model');
        $this->load->model('tipo_items_model');
        $this->load->model('proformas_model');
        $this->load->model('clientes_model'); 
        $this->load->model('proceso_estados_model');
        $this->load->model('tipo_documentos_model');
        $this->load->model('tipo_pagos_model');
        $this->load->model('transportistas_model');
        $this->load->model('medida_model');
        $this->load->model('igv_model');
        $this->load->library('pdf');
        $this->load->helper('ayuda');

        $empleado_id = $this->session->userdata('empleado_id');
        $almacen_id = $this->session->userdata("almacen_id");
        if (empty($empleado_id) or empty($almacen_id)) {
            $this->session->set_flashdata('mensaje', 'No existe sesion activa');
            redirect(base_url());
        }
    }

    public function index()
    {
        $data['empresa'] = $this->empresas_model->select();
        $data['vendedores'] = $this->empleados_model->select2(3);
        $data['proceso_estados'] = $this->proceso_estados_model->select();

        if($this->uri->segment(3) != '' )
        { 
            $data['proforma_id'] = $this->uri->segment(3);
        }     
        
        $this->accesos_model->menuGeneral();
        $this->load->view('proformas/basic_index', $data);
        $this->load->view('templates/footer');      
    }
    public function nuevo()
    {
        $data = array();
        $data['monedas'] = $this->monedas_model->select();
        $data['empresas'] = $this->empresas_model->select();
        $data['tipo_igv'] = $this->tipo_igv_model->select('', '', '', 0);
        $data['tipo_item'] = $this->tipo_items_model->select();
        $data['consecutivo'] = $this->proformas_model->maximoConsecutivo();
        $data['rowIgvActivo'] = $this->igv_model->selectIgvActivo();
        $data['proceso_estados'] = $this->proceso_estados_model->select();
        $data['medida'] = $this->medida_model->select();        
        $this->accesos_model->menuGeneral();
        $this->load->view('proformas/nuevo', $data);
        $this->load->view('templates/footer');
    }
    public function editar($idProforma) {
        
        $data['proforma'] = $this->proformas_model->select($idProforma);
        $data['monedas'] = $this->monedas_model->select();
        $data['empresas'] = $this->empresas_model->select();
        $data['tipo_igv'] = $this->tipo_igv_model->select('', '', '', 0);
        $data['tipo_item'] = $this->tipo_items_model->select(); 
        $data['rowIgvActivo'] = $this->igv_model->selectIgvActivo();
        $data['proceso_estados'] = $this->proceso_estados_model->select();
        $data['medida'] = $this->medida_model->select();
        $this->accesos_model->menuGeneral();       
        $this->load->view('proformas/nuevo', $data);
        $this->load->view('templates/footer');
    }
    public function guardarProforma() {
        $error = array();
        if($_POST['fecha'] == '')
        {
            $error['fecha'] = 'falta ingresar fecha';
        }
        if($_POST['cliente_id'] == '')
        {
            $error['cliente_id'] = 'falta ingresar Cliente';
        }        
        if($_POST["moneda_id"] == '')
        {
            $error['moneda_id'] = 'falta ingresar moneda';
        }

        if($_POST["direccion"] == '')
        {
            $error['direccion'] = 'falta ingresar dirección';
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

        //guardamos la compra
        $result = $this->proformas_model->guardarProforma();        
        
        if($result > 0)
        {
            sendJsonData(['status'=>STATUS_OK,'proforma_id'=>$result]);
            exit();
        }   
    }

    public function eliminar($idProforma)  {
        $result = $this->proformas_model->eliminar($idProforma);
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
        $rsDatos = $this->proformas_model->getMainList();    
        sendJsonData($rsDatos);
    }
    public function getMainListDetail()
    {
        $rsDatos = $this->proformas_model->getMainListDetail();

        sendJsonData($rsDatos);        
    }
    public function descargarPdf($idProforma) {
        require_once (APPPATH .'libraries/Numletras.php');
        
        $rsproforma = $this->db->from("proformas as pf")

                             ->join("monedas as mon", "pf.prof_moneda_id=mon.id")
                             ->join("clientes as cli", "pf.prof_cliente_id=cli.id")                             
                             ->where("prof_id", $idProforma)
                             ->get()
                             ->row();
        
        /*formateamos fecha*/
        $rsproforma->prof_doc_fecha = (new DateTime($rsproforma->prof_doc_fecha))->format('d/m/Y');                     

        $rsDetalles =  $this->db->from("proforma_detalle")
                                ->where("profd_prof_id", $idProforma)
                                ->get()
                                ->result();

        $rsproforma->detalles = $rsDetalles;                                     
        $rsempresa = $this->db->from('empresas')
                              ->where('id',1)
                              ->get()
                              ->row();

        $rscliente = $this->db->from('clientes')
                              ->where('id',$rsproforma->prof_cliente_id)
                              ->get()
                              ->row();
        
        $rsEmpleado =  $this->db->from("empleados")
                              ->where("id", $rsproforma->prof_empleado_id)
                              ->get()
                              ->row();

        $rsmoneda = $this->db->from('monedas')
                              ->where('id',$rsproforma->prof_moneda_id)
                              ->get()
                              ->row();

        $rsdetalle = $this->db->from('proforma_detalle as f')
                              ->join('productos as p','p.prod_id=f.profd_prod_id') 
                              ->where('f.profd_prof_id',$rsproforma->prof_id)
                              ->get()
                              ->result();

        $num = new Numletras();
        $totalVenta = explode(".",$rsproforma->prof_doc_total);
        $totalLetras = $num->num2letras($totalVenta[0]);
        $totalLetras = $totalLetras.' con '.$totalVenta[1].'/100 '.$rsmoneda->moneda;
        $rsproforma->total_letras = $totalLetras; 


        $data = [
                    "proforma"    => $rsproforma,
                    "empresa"     => $rsempresa,
                    "cliente"     => $rscliente,
                    "empleado"    => $rsEmpleado,
                    "moneda"      => $rsmoneda,
                    "detalles"      => $rsdetalle
                ];
        $html = $this->load->view("templates/proforma.php",$data,true);       


        $this->pdf->loadHtml($html);
        $this->pdf->setPaper('A4', 'portrait');
        $this->pdf->render();
        $this->pdf->stream("N.Venta.NP-$idProforma.pdf",
            array("Attachment"=>0)
        );    
    }

    public function modalEnvioProforma(){
          
        $data['proforma'] = $this->proformas_model->select($this->uri->segment(3));        
        echo $this->load->view('proformas/modal_envio_proforma',$data);
    }

//    public function enviarWatsapModal(){

  //      $data['proforma'] = $this->proformas_model->select($this->uri->segment(3));
    //    echo $this->load->view("proformas/enviarWatsapModal",$data);
    //}

     public function enviarEmailModal(){

        $data['proforma'] = $this->proformas_model->select($this->uri->segment(3));
        echo $this->load->view("proformas/enviarEmailModal",$data);
    }

     public function enviarEmailModal_g(){

       $proforma_id = $_POST['proforma_id'];
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

        //Configure email library
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = $correo->correo_host;
        $config['smtp_port'] = $correo->correo_port;
        $config['smtp_user'] = $correo->correo_user;
        $config['smtp_pass'] = $correo->correo_pass;
        $config['smtp_crypto'] = $correo->correo_cifrado;
        $config['charset']='utf-8'; 
        $config['newline']="\r\n"; 
        $config['crlf'] = "\r\n"; 
        $config['mailtype'] = 'html';

         $this->email->initialize($config);
         $proforma = $this->proformas_model->select($proforma_id);

        if($proforma->email == ''){
            $this->db->where('ruc', $proforma->ruc);
            $this->db->update('clientes',array('email' => $mailcc));
        }

        //CREANDO PDF
        $this->create_pdf($proforma_id);

        $file_pdf = APPPATH . "files_pdf/proformas/" .$empresa->ruc.'-PF'.$proforma->prof_correlativo.".pdf";                            

        $this->email->attach($file_pdf);        

        $sender_email = $correo->correo_user;
        $sender_username = $empresa->empresa; 

        // Sender email address
        $this->email->from($sender_email, $sender_username);  
        $this->email->to($mailcc);
        $this->email->cc('fernandezdelacruza@gmail.com');

        $buscar=array(chr(13).chr(10), "\r\n", "\n", "\r");
        $reemplazar=array("", "", "", "");                                       
        $cliente_razon_social = str_ireplace($buscar,$reemplazar,$this->sanear_string(utf8_decode($proforma->razon_social)));
        $cliente_razon_social = str_replace("&", "Y", trim(utf8_decode($cliente_razon_social)));
  
        $tipoDocumentoFormat = "PROFORMA - ELECTRONICA";
        $this->email->subject('COPIA '.$tipoDocumentoFormat.' '. $proforma->ruc.'-0'.$proforma->prof_correlativo.'|'.$cliente_razon_social);

        $body  = 'Sres '.$proforma->ruc.' '.$cliente_razon_social.'<br><br>';
        $body .= 'Sres '.$empresa->empresa.', '.'envía una '.$tipoDocumentoFormat.'<br><br>';

        $body .= '- TIPO: '.$tipoDocumentoFormat.'<br>';        
        $body .= '- CORRELATIVO: '.$proforma->prof_correlativo.'<br>';
        $body .= '- FECHA DE EMISIÓN: '.$proforma->prof_doc_fecha.'<br>';
        $body .= '- TOTAL: '.$proforma->prof_doc_total.'<br><br><br>';

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

    public function create_pdf($idProforma){
        $rsProforma = $this->db->from("proformas as pf")
                           ->join("monedas as mon", "pf.prof_moneda_id = mon.id")
                           ->join("clientes cli","pf.prof_cliente_id = cli.id")
                           ->join("empleados emp","pf.prof_empleado_id = emp.id")
                           ->where("pf.prof_id", $idProforma)
                           ->get()
                           ->row();

        /*formateamos fecha*/
        $rsProforma->prof_doc_fecha = (new DateTime($rsProforma->prof_doc_fecha))->format("d/m/Y");
        $rsDetalles =  $this->db->from("proforma_detalle pf")
                                ->join('productos  as pr','pf.profd_prod_id= pr.prod_id')
                                ->where("pf.profd_prof_id", $idProforma)
                                ->get()
                                ->result();                           

        $rsProforma->detalles = $rsDetalles;        

        $rsEmpresa = $this->db->from("empresas")
                              ->where("id", 1)
                              ->get()
                              ->row();

        $rsCliente =  $this->db->from("clientes")
                               ->where("id", $rsProforma->prof_cliente_id)
                               ->get()
                               ->row();
        
        $rsEmpleado =  $this->db->from("empleados")
                                ->where("id", $rsProforma->prof_empleado_id)
                                ->get()
                                ->row();

        //ALEXANDER FERNANDEZ 31-10-2020
        $rs_almacen_principal = $this->almacenes_model->select($this->session->userdata('almacen_id'));//datos del almacen principal                      
                                               
         $data = [
                    "proforma"    => $rsProforma,
                    "empresa"     => $rsEmpresa,
                    "cliente"     => $rsCliente,
                    "empleado"    => $rsEmpleado,                    
                    "detalles"    => $rsDetalles,                    
                    "almacen_principal" => $rs_almacen_principal
                ];

        $html = $this->load->view("templates/proforma.php",$data,true);
        ////////////////////////////////////////
        $archivo = $rsEmpresa->ruc.'-PF'.$rsProforma->prof_correlativo.'.pdf';
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper('A4', 'portrait');
        $this->pdf->render();
        $contenido = $this->pdf->output();

        $bytes = file_put_contents(APPPATH.'files_pdf/proformas/'.$archivo, $contenido);
        return true;
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

    public function buscadorCliente() {
        $abogado = $this->input->get('term');
        echo json_encode($this->clientes_model->selectAutocomplete($abogado, 'activo'));
    }

    public function exportarExcel($idproveedor='',$correlatio='',$fecha='',$documento='') {

        if($this->uri->segment(3)!='0') {
            $this->db->where('pf.prof_cliente_id', $this->uri->segment(3));
        }
        if($this->uri->segment(4)!='0') {
            $this->db->where('pf.prof_doc_fecha', $this->uri->segment(4));
        }
        if($this->uri->segment(5)!='0') {
            $this->db->where('pf.prof_doc_numero', $this->uri->segment(5));
        }
        if($this->uri->segment(6)!='0') {
            $this->db->where('pf.prof_empleado_id', $this->uri->segment(6));
        }
        if($this->uri->segment(7)!='0') {
            $this->db->where('pf.prof_procesoestado_id', $this->uri->segment(7));
        }

        $this->db->where('pf.prof_estado',ST_ACTIVO);

        $result = $this->db->from("proformas pf")                 
                 ->join("clientes c","pf.prof_cliente_id = c.id")
                 ->join("monedas m","pf.prof_moneda_id = m.id")
                 ->join("proceso_estados pro","pf.prof_procesoestado_id = pro.id")
                 ->join("empleados e","pf.prof_empleado_id = e.id")
                 ->get()
                 ->result();

        /*EXPORTAR A EXCEL*/
        $spreadsheet = new Spreadsheet();         
        // Set workbook properties
        $spreadsheet->getProperties()->setCreator('Rob Gravelle')
                                     ->setLastModifiedBy('Rob Gravelle')
                                     ->setTitle('A Simple Excel Spreadsheet')
                                     ->setSubject('PhpSpreadsheet')
                                     ->setDescription('A Simple Excel Spreadsheet generated using PhpSpreadsheet.')
                                     ->setKeywords('Microsoft office 2013 php PhpSpreadsheet')
                                     ->setCategory('Test file');
        $i=2;

        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(45);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(15);
       
        $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A1', 'CODIGO')
                ->setCellValue('B1', 'CLIENTE')
                ->setCellValue('C1', 'DOCUMENTO')
                ->setCellValue('D1', 'ESTADO')
                ->setCellValue('E1', 'FECHA')
                ->setCellValue('F1', 'MONEDA')
                ->setCellValue('G1', 'SUBTOTAL')
                ->setCellValue('H1', 'IGV')
                ->setCellValue('I1', 'TOTAL')
                ->setCellValue('J1', "USUARIO")
                ->setCellValue('K1', "OBSERVACION");

        $spreadsheet->getActiveSheet()->setTitle('proformas');

        foreach ($result as $value) {
            $fecha = (new DateTime($value->prof_doc_fecha))->format('d/m/Y');
            $spreadsheet->getActiveSheet()
                        ->setCellValue('A'.$i, $value->prof_id)
                        ->setCellValue('B'.$i, $value->razon_social)
                        ->setCellValue('C'.$i, $value->prof_correlativo)
                        ->setCellValue('D'.$i, $value->proceso_estado)
                        ->setCellValue('E'.$i, $fecha)
                        ->setCellValue('F'.$i, $value->moneda)
                        ->setCellValue('G'.$i, $value->prof_doc_subtotal)
                        ->setCellValue('H'.$i, $value->prof_doc_igv)
                        ->setCellValue('I'.$i, $value->prof_doc_total)
                        ->setCellValue('J'.$i, $value->nombre)
                        ->setCellValue('K'.$i, $value->prof_doc_observacion);
            $i++;
        }
        
        $spreadsheet->setActiveSheetIndex(0);         
        // Redirect output to a client's web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="data_proformas.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');       
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }


    public function ExportarExcel_rd($idproveedor='',$correlatio='',$fecha='',$documento='') {

        if($this->uri->segment(3)!='0') {
            $this->db->where('pf.prof_cliente_id', $this->uri->segment(3));
        }
        if($this->uri->segment(4)!='0') {
            $this->db->where('pf.prof_doc_fecha', $this->uri->segment(4));
        }
        if($this->uri->segment(5)!='0') {
            $this->db->where('pf.prof_doc_numero', $this->uri->segment(5));
        }
        if($this->uri->segment(6)!='0') {
            $this->db->where('pf.prof_empleado_id', $this->uri->segment(6));
        }
        if($this->uri->segment(7)!='0') {
            $this->db->where('pf.prof_procesoestado_id', $this->uri->segment(7));
        }

        $this->db->where('pf.prof_estado',ST_ACTIVO);

        $result = $this->db->from("proformas pf")
                           ->join("proforma_detalle pd","pf.prof_id = pd.profd_prof_id")
                           ->join("productos p","p.prod_id = pd.profd_prod_id","left")
                           ->join("categoria ca","p.prod_categoria_id = ca.cat_id")
                           ->join("medida me","pd.profd_unidad_id = me.medida_id")
                           ->join("clientes c","pf.prof_cliente_id = c.id")
                           ->join("empleados e","pf.prof_empleado_id = e.id")
                           ->join("monedas m","pf.prof_moneda_id = m.id")
                           ->join("proceso_estados pro","pf.prof_procesoestado_id = pro.id")
                           ->get()
                           ->result();
                           
        /*EXPORTAR A EXCEL*/
        $spreadsheet = new Spreadsheet();
        // Set workbook properties
        $spreadsheet->getProperties()->setCreator('Rob Gravelle')
                                     ->setLastModifiedBy('Rob Gravelle')
                                     ->setTitle('A Simple Excel Spreadsheet')
                                     ->setSubject('PhpSpreadsheet')
                                     ->setDescription('A Simple Excel Spreadsheet generated using PhpSpreadsheet.')
                                     ->setKeywords('Microsoft office 2013 php PhpSpreadsheet')
                                     ->setCategory('Test file');    
        $i=2;

        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(45);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(15);
       
        $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A1', 'CODIGO')
                ->setCellValue('B1', 'CLIENTE')
                ->setCellValue('C1', "CODIGO")
                ->setCellValue('D1', "CATEGORIA")
                ->setCellValue('E1', "UNIDAD/MEDIDA")
                ->setCellValue('F1', "DESCRIPCION")
                ->setCellValue('G1', "PRECIO UNITARIO")
                ->setCellValue('H1', "CANTIDAD")
                ->setCellValue('I1', "IMPORTE TOTAL")
                ->setCellValue('J1', "USUARIO")
                ->setCellValue('K1', 'ESTADO')
                ->setCellValue('L1', 'DOCUMENTO')
                ->setCellValue('M1', 'FECHA')
                ->setCellValue('N1', 'MONEDA')
                ->setCellValue('O1', 'SUBTOTAL')
                ->setCellValue('P1', 'IGV')
                ->setCellValue('Q1', 'TOTAL');

        $spreadsheet->getActiveSheet()->setTitle('proformas');

        foreach ($result as $value) {
            $fecha = (new DateTime($value->prof_doc_fecha))->format('d/m/Y');
            $spreadsheet->getActiveSheet()
                        ->setCellValue('A'.$i, $value->prof_id)
                        ->setCellValue('B'.$i, $value->razon_social)
                        ->setCellValue('C'.$i, $value->prod_codigo)
                        ->setCellValue('D'.$i, $value->cat_nombre)
                        ->setCellValue('E'.$i, $value->medida_nombre)
                        ->setCellValue('F'.$i, $value->profd_descripcion)
                        ->setCellValue('G'.$i, $value->profd_subtotal)
                        ->setCellValue('H'.$i, $value->profd_cantidad)
                        ->setCellValue('I'.$i, $value->profd_total)
                        ->setCellValue('J'.$i, $value->nombre)
                        ->setCellValue('K'.$i, $value->proceso_estado)
                        ->setCellValue('L'.$i, $value->prof_doc_numero)
                        ->setCellValue('M'.$i, $fecha)
                        ->setCellValue('N'.$i, $value->moneda)
                        ->setCellValue('O'.$i, $value->prof_doc_subtotal)
                        ->setCellValue('P'.$i, $value->prof_doc_igv)
                        ->setCellValue('Q'.$i, $value->prof_doc_total);
            $i++;
            //$spreadsheet->getActiveSheet()->mergeCells('A'.$i.':H'.$i);            
        }
        
        $spreadsheet->setActiveSheetIndex(0);         
        // Redirect output to a client's web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="data_proformas.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');       
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

    public function descargarPdf_ticket($idProforma){
        $rsProforma = $this->db->from("proformas as pro")
                           ->join("monedas as mon", "pro.prof_moneda_id=mon.id")
                           ->where("prof_id", $idProforma)
                           ->get()
                           ->row();
                           //var_dump($rsNota);exit;

        /*formateamos fecha*/
        $rsProforma->prof_doc_fecha = (new DateTime($rsProforma->prof_doc_fecha))->format("d/m/Y h:i:s");
        $rsDetalles =  $this->db->from("proforma_detalle as f")
                                ->join('productos as p','p.prod_id=f.profd_prod_id') 
                           
                           ->where("f.profd_prof_id", $idProforma)
                           ->get()
                           ->result(); 

        $countItems = count($rsDetalles);
        $ticketHeight =  $countItems*23;
        $ticketHeight = ($ticketHeight > 440) ? $ticketHeight : 440;                                                        

        $rsProforma->detalles = $rsDetalles;                                     

        $rsEmpresa = $this->db->from("empresas")
                              ->where("id", 1)
                              ->get()
                              ->row();

        $rsCliente =  $this->db->from("clientes")
                              ->where("id", $rsProforma->prof_cliente_id)
                              ->get()
                              ->row();                      
                      //var_dump($rsCliente)
 
        $data = [
                    "empresa" => $rsEmpresa,
                    "proforma"    => $rsProforma,
                    "cliente" => $rsCliente,
                ];
        $html = $this->load->view("templates/proforma_ticket.php",$data,true); 
        
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper(array(0,0,85,$ticketHeight), 'portrait');
        $this->pdf->render();
        $this->pdf->stream("Proforma.NP-$idProforma.pdf",
            array("Attachment"=>0)
        );
    }



    public function comprobanteTributario(){
    
    $rsProforma = $this->proformas_model->select($this->uri->segment(3));

    //var_dump($rsNota);
    $cabecera = array();
    
    $tipo_cliente_id = $rsProforma->tipo_cliente_id;
    //P.NATURAL,PJURIDICA
    if($tipo_cliente_id == 1) $data['tipo_documento_id'] = 3;
    if($tipo_cliente_id == 2) $data['tipo_documento_id'] = 1;
    
    $cabecera['prof_id'] = $this->uri->segment(3);
    $cabecera['cliente_id'] = $rsProforma->prof_cliente_id;
    $cabecera['cliente_razon_social'] = $rsProforma->razon_social;
    $cabecera['moneda_id']  = $rsProforma->prof_moneda_id;
    $cabecera['total_a_pagar'] = $rsProforma->prof_doc_total;
    $cabecera['comprobante_anticipo'] = 0;
    $cabecera['observaciones'] = $rsProforma->prof_doc_observaciones;
    //$cabecera['almacen_id'] = 1; 
    //$cabecera['tipo_pago_id'] = 1;
    //var_dump($rsNota);exit;
    $data['comprobante'] = $cabecera;

    $items = array();
    foreach ($rsProforma->detalles as $value) {                
                                        
            $item['descripcion'] = $value->profd_descripcion;
            $item['producto_id'] = $value->profd_prod_id;
            $item['unidad_id'] = $value->profd_unidad_id;
            $item['cantidad'] = $value->profd_cantidad;
            $item['tipo_igv_id'] =  1;
            $item['importe'] = $value->profd_precio_unitario;
            $item['importeCosto'] = $value->profd_importeCosto;
            $item['total'] = $value->profd_total;
            $item['totalCosto'] = $value->profd_totalCosto;
            $item['totalVenta'] = $value->profd_totalVenta;
            $items[] = $item;                    
    }    

    $data['items'] = $items;
    $data['tipo_igv'] = $this->tipo_igv_model->select();
    $data['monedas']  = $this->monedas_model->select();
    $data['tipo_documentos'] = $this->tipo_documentos_model->select();    
    $data['empresa'] = $this->empresas_model->select(1);
    $data['transportistas'] = $this->transportistas_model->select();
    $data['tipo_pagos'] = $this->tipo_pagos_model->select();
    $data['rowIgvActivo'] = $this->igv_model->selectIgvActivo();


    $data['configuracion'] = $this->db->from('comprobantes_ventas')->get()->row();


        $this->load->view('templates/header_administrador');
        $this->load->view('comprobantes/generarComprobante',$data);
        $this->load->view('templates/footer');
    }   

}