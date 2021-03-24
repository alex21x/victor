<?PHP

use PhpOffice\PhpSpreadsheet\Spreadsheet;    
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Historias extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();
        $this->load->model('empresas_model');
		$this->load->model('historias_model');		
		//$this->load->model('historia_detalles_model');            
		$this->load->model('historia_estados_model');
        $this->load->model('historia_estadoComprobante_model');
        $this->load->model('historia_imagenes_model');
        $this->load->model('especialidades_model');
        $this->load->model('profesionales_model');
        $this->load->model('pacientes_model');
        $this->load->model('almacenes_model');
        $this->load->library('pdf');

        $empleado_id = $this->session->userdata('empleado_id');
        $almacen_id = $this->session->userdata("almacen_id");
        if (empty($empleado_id) or empty($almacen_id)) {
            $this->session->set_flashdata('mensaje', 'No existe sesion activa');
            redirect(base_url());
        }
	}


	public function index(){

        if($this->uri->segment(3) != '' )     
        $data['historia_id'] = $this->uri->segment(3);
        $data['especialidades'] =  $this->especialidades_model->select();
        $data['historia_estados'] =  $this->historia_estados_model->select();
		$this->load->view('templates/header_administrador');
		$this->load->view('historias/base_index',$data);
		$this->load->view('templates/footer');
	}

	public function crear(){
        $data['empresa'] =  $this->empresas_model->select(1);       
        $data['profesionales'] =  $this->profesionales_model->select();
        $data['especialidades'] =  $this->especialidades_model->select();
		$data['historia_estados'] =  $this->historia_estados_model->select();
        $data['historia_estadoComprobante'] =  $this->historia_estadoComprobante_model->select();
		$this->load->view('historias/modal_crear',$data);
	}

    public function editar(){
        $data['profesionales'] =  $this->profesionales_model->select();
        $data['especialidades'] =  $this->especialidades_model->select();
        $data['historia'] =  $this->historias_model->select($this->uri->segment(3));
        $data['historia_estados'] =  $this->historia_estados_model->select();        
        $data['historia_estadoComprobante'] =  $this->historia_estadoComprobante_model->select();
        $data['historia_imagenes'] =  $this->historia_imagenes_model->select('',$this->uri->segment(3));
        $this->load->view('historias/modal_crear',$data);   
    }


	public function guardarHistoria(){    

		$error = array();      
		if($_POST['paciente_id'] == '')
        {
           $error['paciente'] = 'falta ingresar paciente';
        }  
		if($_POST['fecha_nacimiento'] == '')
        {
            $error['fecha_nacimiento'] = 'falta ingresar fecha_nacimiento';
        }  
		if($_POST['especialidad'] == '')
        {
            $error['especialidad'] = 'falta ingresar especialidad';
        }  
        if($_POST['motivo'] == '')
        {
            $error['motivo'] = 'falta ingresar motivo';
        }
        if($_POST['documento_venta'] == '')
        {
            $error['documento_venta'] = 'falta ingresar documento venta';
        }        

        if($_POST['estado'] == '')
        {
            $error['estado'] = 'falta ingresar estado';
        }  
        if($_POST['estado_documentoVenta'] == '')
        {
            $error['estado_documentoVenta'] = 'falta ingresar estado';
        }  


        if(count($error) > 0)
        {
            $data = ['status'=>STATUS_FAIL,'tipo'=>1, 'errores'=>$error];
            echo json_encode($data);
            exit();
        }   


        if($_POST['estado'] == 2){//VALIDACIÓN PARA ESTADO CERRADO
        //VALIDACIÓN DE PRODUCTOS
        $idProducto = $_POST['item_id'];
        $cantidad   = $_POST['cantidad'];
        $descripcion = $_POST['descripcion'];
        $medida = $_POST['medida'];                      

        $tieneProductos = false;
        $msg = 'no hay productos agregados.';
        $b = 0;
        foreach($idProducto as $value)
        {
            if($value!='')
            {                
                if($value == 0){
                  if($descripcion[$b]==''){
                     $tieneProductos = false;
                    $msg = 'Ingrese descripción del producto.';
                    break;
                  }else if($medida[$b]==''){                    
                     $tieneProductos = false;
                    $msg = 'Seleccione una unidad de medida.';
                    break;
                  }else{
                    $tieneProductos = true;
                  }
                }else{
                    $tieneProductos = true;
                }  
            }else{
                $tieneProductos = false;
                $msg = 'hay un producto que no se ha registrado bien.';
                break;
            }
            $b++;
        }
        if(!$tieneProductos)
        {
            sendJsonData(['status'=>STATUS_FAIL,'tipo'=>1, 'msg'=>$msg]);
            exit();            
        }

        $f = 0; 
        foreach($idProducto as $value){
          if($cantidad[$f] <= 0){
            sendJsonData(['status'=>STATUS_FAIL,'tipo'=>1,'msg'=>'La cantidad del producto debe ser mayor a cero']);
            exit(); 
          }
          $f++;
        }
        }

        //guardamos la historia
        $result = $this->historias_model->guardarHistoria();

        if($result)
        {
            echo json_encode(['status'=>STATUS_OK,'his_id' => $result]);
            exit();
        }else
        {
            echo json_encode(['status'=>STATUS_FAIL, 'tipo'=>2]);
            exit();
        }
	}

	public function getMainList(){

		$rsHistorias =  $this->historias_model->getMainList();
		echo json_encode($rsHistorias);
	}	

	public function buscador_item() {
        $item = $this->input->get('term');       
        echo json_encode($this->historias_model->selectAutocompleteprod($item));
    }

    public function decargarPdf($idHistoria){

        $rsHistoria = $this->db->select('his.his_id his_id,his.his_correlativo his_correlativo,DATE_FORMAT(his.his_fecha, "%d-%m-%Y %h:%i:%s") his_fecha,his.his_ini_peso,his.his_ini_talla,his.his_ini_presion_arterial,his.his_ini_temperatura,his.his_ini_otros,his.his_codigo_cie,his.his_enfermedad_actual,his.his_motivo,his.his_diagnostico,his.his_tratamiento,his.his_recomendacion,his.his_documento_venta,DATE_FORMAT(his.his_fecha_cita, "%d-%m-%Y %h:%i:%s") his_fecha_cita,pac.id paciente_id,pac.razon_social pac_razon_social,prof.prof_nombre prof_nombre,prof.prof_firma prof_firma,esp.esp_descripcion,CONCAT(emp.nombre," ",emp.apellido_paterno) empleado,hie.hie_descripcion estado,tpe.descripcion his_codigoCEI_descripcion',FALSE)
                            ->from('historias his')
                            ->join('pacientes pac','pac.id = his.his_paciente_id')
                            ->join('profesionales prof','prof.prof_id =  his_profesional_id')
                            ->join('especialidades esp','esp.esp_id = prof_especialidad_id')
                            ->join('empleados emp','emp.id = his.his_empleado_insert')
                            ->join('historia_estados hie','hie.hie_id = his.his_historia_estado_id')
                            ->join('tipo_enfermedades tpe','tpe.codigo = his.his_codigo_cie','left')
                            ->where("his.his_id", $idHistoria)
                            ->where("his_estado",ST_ACTIVO)
                            ->get()
                            ->row();
                            //var_dump($rsHistoria);Exit;

        /*formateamos fecha*/
        $rsHistoria->his_fecha = (new DateTime($rsHistoria->his_fecha))->format("d/m/Y h:i:s");                   
        $rsDetalles =  $this->db->from("historia_detalles as f")
                                ->join('productos as p','p.prod_id=f.hid_producto_id','left')
                                ->where("f.hid_his_id", $idHistoria)
                                ->get()
                                ->result();

        //HEIGHT TICKET 21-09-2020
        $countItems = count($rsDetalles);
        $ticketHeight =  $countItems*22;
        $ticketHeight = ($ticketHeight > 440) ? $ticketHeight : 440;        


        $rsHistoria->detalles = $rsDetalles;                                     

        $rsEmpresa = $this->db->from("empresas")
                              ->where("id", 1)
                              ->get()
                              ->row();  

        $rsPaciente =  $this->db->from("pacientes")
                                ->where("id", $rsHistoria->paciente_id)
                                ->get()
                                ->row();       


        $rsImagenes =  $this->db->from("historia_imagenes")
                                ->where("hii_his_id",$idHistoria)
                                ->get()
                                ->result();

        //ALEXANDER FERNANDEZ DE LA CRUZ 30-10-2020
        $rs_almacen_principal = $this->almacenes_model->select($this->session->userdata('almacen_id'));//datos del almacen principal
        $data = [
                    "empresa" => $rsEmpresa,
                    "historia" => $rsHistoria,               
                    "paciente" => $rsPaciente,
                    "imagenes" => $rsImagenes,
                    "almacen_principal" => $rs_almacen_principal
                ];
        $html = $this->load->view("templates/historia.php",$data,true); 
        
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper('A4', 'portrait');
        $this->pdf->render();
        $this->pdf->stream("HISTORIA.H-$idHistoria.pdf",
            array("Attachment"=>0)
        );
    }


    public function decargarPdf_ticket($idHistoria){
        $rsHistoria = $this->db->select('his.his_id his_id,his.his_correlativo his_correlativo,DATE_FORMAT(his.his_fecha, "%d-%m-%Y %h:%i:%s") his_fecha,his.his_ini_peso,his.his_ini_talla,his.his_ini_presion_arterial,his.his_ini_temperatura,his.his_ini_otros,his.his_codigo_cie,his.his_enfermedad_actual,his.his_motivo,his.his_diagnostico,his.his_tratamiento,his.his_recomendacion,DATE_FORMAT(his.his_fecha_cita, "%d-%m-%Y %h:%i:%s") his_fecha_cita,pac.id paciente_id,pac.razon_social pac_razon_social,prof.prof_nombre prof_nombre,prof.prof_firma prof_firma,esp.esp_descripcion,CONCAT(emp.nombre," ",emp.apellido_paterno) empleado,hie.hie_descripcion estado,tpe.descripcion his_codigoCEI_descripcion',FALSE)
                            ->from('historias his')
                            ->join('pacientes pac','pac.id = his.his_paciente_id')
                            ->join('profesionales prof','prof.prof_id =  his_profesional_id')
                            ->join('especialidades esp','esp.esp_id = prof_especialidad_id')
                            ->join('empleados emp','emp.id = his.his_empleado_insert')
                            ->join('historia_estados hie','hie.hie_id = his.his_historia_estado_id')
                            ->join('tipo_enfermedades tpe','tpe.codigo = his.his_codigo_cie','left')
                            ->where("his.his_id", $idHistoria)
                            ->where("his_estado",ST_ACTIVO)
                            ->get()
                            ->row();
                            //var_dump($rsHistoria);Exit;

        /*formateamos fecha*/
        $rsHistoria->his_fecha = (new DateTime($rsHistoria->his_fecha))->format("d/m/Y h:i:s");                   
        $rsDetalles =  $this->db->from("historia_detalles as f")
                                ->join('productos as p','p.prod_id=f.hid_producto_id','left')                            
                                ->where("f.hid_his_id", $idHistoria)
                                ->get()
                                ->result();

        //HEIGHT TICKET 21-09-2020
        $countItems = count($rsDetalles);
        $ticketHeight =  $countItems*22;
        $ticketHeight = ($ticketHeight > 440) ? $ticketHeight : 440;        


        $rsHistoria->detalles = $rsDetalles;                                     

        $rsEmpresa = $this->db->from("empresas")
                              ->where("id", 1)
                              ->get()
                              ->row();  

        $rsPaciente =  $this->db->from("pacientes")
                                ->where("id", $rsHistoria->paciente_id)
                                ->get()
                                ->row();                                            

        //ALEXANDER FERNANDEZ DE LA CRUZ 30-10-2020
        $rs_almacen_principal = $this->almacenes_model->select($this->session->userdata('almacen_id'));//datos del almacen principal
        $data = [
                    "empresa" => $rsEmpresa,
                    "historia" => $rsHistoria,                    
                    "paciente" => $rsPaciente,
                    "almacen_principal" => $rs_almacen_principal
                ];
        $html = $this->load->view("templates/historia_ticket.php",$data,true); 
        
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper(array(0,0,85,$ticketHeight), 'portrait');
        $this->pdf->render();
        $this->pdf->stream("N.Historia.H-$idHistoria.pdf",
            array("Attachment"=>0)
        );
    }



    public function reporteHistoria_pdf(){
            
        $array = array();        

        if ($_GET['paciente_check'] == 'on') {
            $fecha_desde = '';
            $fecha_hasta = '';
        } else {
            $fecha_desde = $_GET['fecha_desde'];
            $fecha_hasta = $_GET['fecha_hasta'];    
        }

        $paciente_id = $_GET['paciente_s_id'];
        $rsHistoria = $this->historias_model->select('',$paciente_id,$fecha_desde,$fecha_hasta);
        $data['paciente']  = $this->pacientes_model->select($paciente_id);
        $data['empresa']   = $this->db->from("empresas")
                                      ->where("id", 1)
                                      ->get()
                                      ->row();  

        foreach ($rsHistoria as $value) {
            $data['historias'][] =  $this->historias_model->select($value->his_id);            
        }

        //$paciente_id =  $_REQUEST['paciente_id'];
        //echo $paciente_id;exit;
        $html = $this->load->view("templates/reporteHistoria_pdf.php",$data,true);
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper('A4', 'portrait');
        $this->pdf->render();
        //$tipo_documento_descargar = str_replace(" ","_",$rsComprobante->tipo_documento);
        $tipo_documento_descargar = str_replace(" ","_","as");
        $this->pdf->stream("$tipo_documento_descargar-$rsComprobante->serie-$rsComprobante->numero.pdf",
            array("Attachment"=>0)
        );              
    }



    //ALEXANDER FERNANDEZ 28-11-2020
    public function modalEnvioHistoria(){
          
        $data['historia'] = $this->historias_model->select($this->uri->segment(3));        
        $data['tipo_documento'] = 'HISTORIA CLINICA';
        //var_dump($data['comprobante']);
        echo $this->load->view('historias/modal_envio_historia',$data);
    }


    //ALEXANDER FERNANDEZ 28-11-2020
    public function enviarWatsapModal(){

        $data['historia'] = $this->historias_model->select($this->uri->segment(3));
        echo $this->load->view("historias/enviarWatsapModal",$data);
    }


    public function enviarEmailModal(){

        $data['historia'] = $this->historias_model->select($this->uri->segment(3));
        echo $this->load->view("historias/enviarEmailModal",$data);
    }

    public function enviarWatsapModal_g(){

        $historia_id = $_POST['historia_id'];
        $telefono_movil = $_POST['telefono_movil'];        

        $historia = $this->historias_model->select($historia_id);

        if ($historia->pac_telefono == '') {         
            $this->db->where('id',$historia->his_paciente_id);
            $this->db->update('pacientes',array('telefono'=> $telefono_movil));
        }

        echo json_encode(['status' => STATUS_OK, 'msg' => 'Mensaje enviado correctamente']);
        exit();        
    }

    //ALEXANDER FERNANDEZ 28-11-2020
    public function enviarEmailModal_g(){

        $historia_id = $_POST['historia_id'];            
        $mailcc = $_POST['correo'];

        //echo $mailcc;exit;
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

        $historia = $this->historias_model->select($historia_id);
        if($historia->pac_correo == ''){
            $this->db->where('id', $historia->his_paciente_id);
            $this->db->update('pacientes',array('correo' => $mailcc));
        }                

        //CREANDO PDF
        $this->create_pdf($historia_id);        
        $file_pdf = APPPATH . "files_pdf/historias/".$empresa->ruc.'-H'.$historia->his_correlativo. ".pdf";        
        
        $this->email->attach($file_pdf);        

        $sender_email = $correo->correo_user;
        $sender_username = $empresa->empresa;  

        // Sender email address
        $this->email->from($sender_email, $sender_username);
        $this->email->to($mailcc);
        $this->email->cc('fernandezdelacruza@gmail.com');


        $buscar=array(chr(13).chr(10), "\r\n", "\n", "\r");
        $reemplazar=array("", "", "", "");                                       
        $cliente_razon_social = str_ireplace($buscar,$reemplazar,$this->sanear_string(utf8_decode($historia->pac_razon_social)));
        $cliente_razon_social = str_replace("&", "Y", trim(utf8_decode($cliente_razon_social)));

        $tipoDocumentoFormat =  'HISTORIA CLINICA';
        $this->email->subject('COPIA '.$tipoDocumentoFormat.' '. $historia->his_correlativo.'|'.$cliente_razon_social.'|'.$historia->pac_ruc);

        $body  = 'Sres '.$historia->pac_ruc.' '.$cliente_razon_social.'<br><br>';
        $body .= 'Sres '.$empresa->empresa.', '.'envía una '.$tipoDocumentoFormat.'<br><br>';

        $body .= '- TIPO: '.$tipoDocumentoFormat.'<br>';
        $body .= '- CORRELATIVO: '.$historia->his_correlativo.'<br>';
        $body .= '- FECHA DE EMISIÓN: '.$historia->his_fecha.'<br>';        

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



    public function create_pdf($historia_id = '')
    {        
        
        $rsHistoria = $this->db->select('his.his_id his_id,his.his_correlativo his_correlativo,DATE_FORMAT(his.his_fecha, "%d-%m-%Y %h:%i:%s") his_fecha,his.his_ini_peso,his.his_ini_talla,his.his_ini_presion_arterial,his.his_ini_temperatura,his.his_ini_otros,his.his_enfermedad_actual,his.his_motivo,his.his_diagnostico,his.his_tratamiento,his.his_recomendacion,his.his_documento_venta,DATE_FORMAT(his.his_fecha_cita, "%d-%m-%Y %h:%i:%s") his_fecha_cita,pac.id paciente_id,pac.razon_social pac_razon_social,prof.prof_nombre prof_nombre,prof.prof_firma prof_firma,esp.esp_descripcion,CONCAT(emp.nombre," ",emp.apellido_paterno) empleado,hie.hie_descripcion estado',FALSE)
                            ->from('historias his')
                            ->join('pacientes pac','pac.id = his.his_paciente_id')
                            ->join('profesionales prof','prof.prof_id =  his_profesional_id')
                            ->join('especialidades esp','esp.esp_id = prof_especialidad_id')
                            ->join('empleados emp','emp.id = his.his_empleado_insert')
                            ->join('historia_estados hie','hie.hie_id = his.his_historia_estado_id')
                            ->where("his.his_id", $historia_id)
                            ->where("his_estado",ST_ACTIVO)
                            ->get()
                            ->row();                            

        /*formateamos fecha*/
        $rsHistoria->his_fecha = (new DateTime($rsHistoria->his_fecha))->format("d/m/Y h:i:s");                   
        $rsDetalles =  $this->db->from("historia_detalles as f")
                                ->join('productos as p','p.prod_id=f.hid_producto_id','left')                            
                                ->where("f.hid_his_id", $historia_id)
                                ->get()
                                ->result();

        //HEIGHT TICKET 21-09-2020
        $countItems = count($rsDetalles);
        $ticketHeight =  $countItems*22;
        $ticketHeight = ($ticketHeight > 440) ? $ticketHeight : 440;        


        $rsHistoria->detalles = $rsDetalles;                                     

        $rsEmpresa = $this->db->from("empresas")
                              ->where("id", 1)
                              ->get()
                              ->row();  

        $rsPaciente =  $this->db->from("pacientes")
                                ->where("id", $rsHistoria->paciente_id)
                                ->get()
                                ->row();       


        $rsImagenes =  $this->db->from("historia_imagenes")
                                ->where("hii_his_id",$historia_id)
                                ->get()
                                ->result();

        //ALEXANDER FERNANDEZ DE LA CRUZ 30-10-2020
        $rs_almacen_principal = $this->almacenes_model->select($this->session->userdata('almacen_id'));//datos del almacen principal
        $data = [
                    "empresa" => $rsEmpresa,
                    "historia" => $rsHistoria,               
                    "paciente" => $rsPaciente,
                    "imagenes" => $rsImagenes,
                    "almacen_principal" => $rs_almacen_principal
                ];

        $html = $this->load->view("templates/historia.php",$data,true);
        
        $archivo = $rsEmpresa->ruc.'-H'.$rsHistoria->his_correlativo.'.pdf';
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper('A4', 'portrait');
        $this->pdf->render();
        $contenido = $this->pdf->output();

        $bytes = file_put_contents(APPPATH.'files_pdf/historias/'.$archivo, $contenido);
        return true;
    }



    public function eliminarHistoriaImagen(){
        $result = $this->historia_imagenes_model->eliminar($this->uri->segment(3));

        $historia_imagenes = $this->historia_imagenes_model->select('',$this->uri->segment(4));
        $rsHI =  '<div id="images_gallery">';                      
            foreach($historia_imagenes as $image)
                {              
                    $rsHI .= '<div class="col-xs-2 col-md-2 col-lg-2" align="center" ><a class="example-image-link" href="'.base_url().'images/historias/'. $image->hii_foto.'" data-lightbox="example-1"><img class="example-image" src="'.base_url().'images/historias/'. $image->hii_foto .'" width="120px" height="100px" style="border:1px solid #ccc;margin-top:10px;" /></a>
                                <span '.$this->session->userdata('accesoEmpleado').' class="glyphicon glyphicon-remove eliminarImagen" data-id="'.$image->hii_id.'"></span></div>';                              
                }
                  
        $rsHI .= '</div>';


        echo $rsHI;
    }


    //REPORTE DE HISTORIA EXCEL
    public function exportarReporteHistoria(){        

        $rsReporteHistoria = $this->historias_model->exportarReporteHistoria();


        //var_dump($rsReporteHistoria);exit;

        $fecha_desde = $_GET['fecha_desde'];
        $fecha_hasta = $_GET['fecha_hasta'];

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
        
        $i=6;

        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(20);

       
        $spreadsheet->getActiveSheet()->getStyle("A1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("B1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("C1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("D1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("E1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("F1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("G1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("H1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("I1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("J1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("K1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("L1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("M1")->getFont()->setBold(true);


        $spreadsheet->getActiveSheet()->getStyle('B')
        ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);



        $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('B1', 'REPORTE DE HISTORIAS CLINICAS');

        $spreadsheet->getActiveSheet()
                ->setCellValue('A2', 'FECHA DESDE')
                ->setCellValue('B2', $fecha_desde)
                ->setCellValue('A3', 'FECHA HASTA')
                ->setCellValue('B3', $fecha_hasta);


        $spreadsheet->getActiveSheet()
                ->setCellValue('A5', 'FECHA ATENCION')
                ->setCellValue('B5', 'FECHA CITA')
                ->setCellValue('C5', 'ESTADO')
                ->setCellValue('D5', 'PACIENTE')
                ->setCellValue('E5', 'TELEFONO')
                ->setCellValue('F5', 'MEDICO')
                ->setCellValue('G5', 'ESPECIALIDAD')
                ->setCellValue('H5', 'USUARIO')
                ->setCellValue('I5', 'HONORARIO')
                ->setCellValue('J5', 'PROXIMA CITA');

        foreach($rsReporteHistoria  as $value) {
         $spreadsheet->getActiveSheet()
                        ->setCellValue('A'.$i, $value->his_fecha)
                        ->setCellValue('B'.$i, $value->his_fecha_cita)
                        ->setCellValue('C'.$i, $value->estado)
                        ->setCellValue('D'.$i, $value->pac_razon_social)
                        ->setCellValue('E'.$i, $value->pac_telefono)
                        ->setCellValue('F'.$i, $value->prof_nombre)
                        ->setCellValue('G'.$i, $value->esp_descripcion)
                        ->setCellValue('H'.$i, $value->empleado)
                        ->setCellValue('I'.$i, $value->estadoComprobante)
                        ->setCellValue('J'.$i, $value->his_proxima_cita);   

           $i++;                                  
         }                                   

        $spreadsheet->setActiveSheetIndex(0);         
        // Redirect output to a client's web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="data_cliente.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }


    public function sanear_string($string) {

        $string = trim(utf8_encode($string));
        $string = str_replace(
                array('à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'), array('a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'), $string
        );

        $string = str_replace(
                array('è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'), array('e', 'e', 'e', 'E', 'E', 'E', 'E'), $string
        );

        $string = str_replace(
                array('ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'), array('i', 'i', 'i', 'I', 'I', 'I', 'I'), $string
        );

        $string = str_replace(
                array('ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'), array('o', 'o', 'o', 'O', 'O', 'O', 'O'), $string
        );

        $string = str_replace(
                array('ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'), array('u', 'u', 'u', 'U', 'U', 'U', 'U'), $string
        );

        $string = str_replace(
                array('ç', 'Ç'), array('c', 'C',), $string
        );

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

     public function buscar_codigoCIE(){

        $texto = $this->input->get('texto');
        $rs    = $this->db->from('tipo_enfermedades')
                          ->like('codigo',$texto,'after')
                          ->or_like('descripcion',$texto)
                          ->limit(15)
                          ->get()
                          ->result();

        echo json_encode($rs);
    }


    public function seleccionar_codigoCIE(){

        $codigo = $this->input->get('cod');

        $rs = $this->db->from('tipo_enfermedades')
                       ->where('codigo',$codigo) 
                       ->get()
                       ->row();

        echo json_encode($rs);

    }

    public function imprimirReceta(){

        $descripcionOtros = $_GET['descripcionOtros'];      


        //$posicion_coincidencia = strrpos($descripcionOtros, "/");
        //echo $posicion_coincidencia;exit;


        $cantidadOtros    = $_GET['cantidadOtros'];
        $observacionOtros = $_GET['observacionOtros'];
        $paciente_id = $_GET['paciente_id'];

        $profesional = $_GET['profesional'];
        $especialidad = $_GET['especialidad'];


        $rsHistoria = array('his_ini_peso' => $_GET["peso_ini"],
                            'his_ini_talla' => $_GET['talla_ini'],
                            'his_ini_presion_arterial' => $_GET['presion_arterial_ini'],
                            'his_ini_temperatura'  => $_GET['temperatura_ini'],
                            'his_ini_otros' => $_GET['otros_ini'],                            
                         );     



        $rsEspecialidad = $this->db->from("especialidades")
                                  ->where("esp_id",$especialidad)
                                  ->get()
                                  ->row();

        $rsProfesional =  $this->db->from("profesionales")
                                  ->where("prof_id",$profesional)
                                  ->get()
                                  ->row();

        
        $rsEmpresa = $this->db->from("empresas")
                              ->where("id",1)
                              ->get()
                              ->row();

        $rsPaciente = $this->db->from("pacientes")
                               ->where("id",$paciente_id)
                               ->get()
                               ->row();


        $rsDetalle = $_GET['descripcionOtros'];


        $configuracion = $this->db->from('comprobantes_ventas')->get()->row();
        $data = [
                                        
                    "empresa"  => $rsEmpresa,
                    "historia" => $rsHistoria,
                    "descripcionOtros" => $descripcionOtros,
                    "cantidadOtros"  => $cantidadOtros,
                    "observacionOtros" => $observacionOtros,
                    "paciente" => $rsPaciente,
                    "especialidad" => $rsEspecialidad,
                    "profesional" => $rsProfesional,
                    "configuracion" => $configuracion,                    
                    "almacen_principal" => $rs_almacen_principal
                ];
                //var_dump($data);EXIT;
        $html = $this->load->view("templates/historia_recetaTicket.php",$data,true);
        //var_dump($data);exit;
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper(array(0,0,85,440), 'portrait');
        $this->pdf->render();
        $tipo_documento_descargar = str_replace(" ","_",$rsComprobante->tipo_documento);
        $this->pdf->stream("$tipo_documento_descargar-$rsComprobante->serie-$rsComprobante->numero.pdf",
            array("Attachment"=>0)
        );

    }
}