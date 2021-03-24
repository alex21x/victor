<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use Endroid\QrCode\QrCode;
/*require __DIR__ . '/../ticket/autoload.php';*/
//use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\Printer;
use mikehaertl\wkhtmlto\Pdf;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

use PhpOffice\PhpSpreadsheet\Spreadsheet;    
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Comprobantes extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('America/Lima');
        $this->load->model('comprobantes_model');
        $this->load->model('items_model');
        $this->load->model('igv_model');
        $this->load->model('icbper_model');
        $this->load->model('tipo_igv_model');
        $this->load->model('elemento_adicionales_model');
        $this->load->model('tipo_documentos_model');
        $this->load->model('tipo_pagos_model');
        $this->load->model('tipo_items_model');
        $this->load->model('tipo_ncreditos_model');
        $this->load->model('tipo_ndebitos_model');
        //$this->load->model('activos_model');
        $this->load->model('accesos_model');
        $this->load->model('clientes_model');
        $this->load->model('monedas_model');
        $this->load->model('empleados_model');
        $this->load->model('empresas_model');
        $this->load->model('tipo_cambio_model');
        $this->load->model('ser_nums_model');
        $this->load->model('comprobante_anulados_model');
        $this->load->model('cuentas_model');
        $this->load->model('variables_diversas_model');
        $this->load->model('ticket_model');
        $this->load->model('productos_model');
        $this->load->model('categoria_model');
        $this->load->model('medida_model');
        $this->load->model('resumenes_model');
        $this->load->model('tipo_clientes_model');
        $this->load->model('cajas_model');
        $this->load->model('almacenes_model');
        $this->load->model('notas_model');
        $this->load->model('transportistas_model');
        $this->load->model('pse_token_model');
        $this->load->library('pdf');
        

        $this->load->helper('ayuda');
        
        $data_actual = strtotime(date("Y-m-d"));
        if($data_actual >= strtotime('2019-05-05')){
            //echo "actualizar datos";exit;
        }

        $empleado_id = $this->session->userdata('empleado_id');
        $almacen_id = $this->session->userdata("almacen_id");
        if (empty($empleado_id) or empty($almacen_id)) {
            $this->session->set_flashdata('mensaje', 'No existe sesion activa');
            redirect(base_url());
        }
    }
    public function dashboard() {
        $inicio = $_POST['inicio'];
        $final = $_POST['final'];
        $res= $this->comprobantes_model->getdash($inicio,$final);
        echo json_encode($res);  
    }

    public function documentos(){        
        $pagina = $this->uri->segment(11);
        $inicio = 0;
        $limite = 10;
        if ($pagina) {
            $inicio = ($pagina - 1) * $limite;
        }
        $data['empresa_id'] = $this->uri->segment(3);
        $data['cliente_select'] = myUrlDecode($this->uri->segment(4));
        $data['cliente_select_id'] = myUrlDecode($this->uri->segment(5));
        $data['tipo_documento_id'] = myUrlDecode($this->uri->segment(6));
        $data['fecha_de_emision_inicio'] = format_fecha_0000_00_00(myUrlDecode($this->uri->segment(7)));
        $data['fecha_de_emision_final'] = format_fecha_0000_00_00(myUrlDecode($this->uri->segment(8)));
        $data['serie_select'] = myUrlDecode($this->uri->segment(9));
        $data['numero_select'] = myUrlDecode($this->uri->segment(10));        
        $data['fecha_de_emision_inicio_select'] = myUrlDecode($this->uri->segment(7));
        $data['fecha_de_emision_final_select'] = myUrlDecode($this->uri->segment(8));

        $this->load->library('pagination');
        $dataComprobante = $this->comprobantes_model->selectVersion2('', $data['serie_select'], $data['numero_select'], $data['fecha_de_emision_inicio'], $data['fecha_de_emision_final'], $data['cliente_select_id'], $data['tipo_documento_id'], '', '', $inicio, $limite, $data['empresa_id']);
        $data['comprobantes'] = $this->comprobantes_model->formatVoucher($dataComprobante);
        $data['numero_inicio'] = $inicio + 1;
        $data['empresa'] = $this->empresas_model->select($data['empresa_id']);
        $data['tipo_documentos'] = $this->tipo_documentos_model->select();
       
        $url = $data['empresa_id'] . "/" .
                myUrlEncode($data['cliente_select']) . "/" .
                myUrlEncode($data['cliente_select_id']) . "/" .
                myUrlEncode($data['tipo_documento_id']) . "/" .
                myUrlEncode($data['fecha_de_emision_inicio_select']) . "/" .
                myUrlEncode($data['fecha_de_emision_final_select']) . "/" .
                myUrlEncode($data['serie_select']) . "/" .
                myUrlEncode($data['numero_select']) . "/" ;

        $config['base_url'] = base_url() . 'index.php/comprobantes/documentos/' . $url;
        $config['total_rows'] = $this->comprobantes_model->selectCountVersion2('', $data['serie_select'], $data['numero_select'], $data['fecha_de_emision_inicio'], $data['fecha_de_emision_final'], $data['cliente_select_id'], $data['tipo_documento_id'], '', '', '', '', $data['empresa_id']);
        $config['per_page'] = $limite;
        $config['uri_segment'] = 11;
        $config = paginacionBoostrapCodeigniter($config);
        $this->pagination->initialize($config);

        $this->accesos_model->menuGeneral();
        $this->load->view('comprobantes/documentos', $data);
        $this->load->view('templates/footer');
    }
    public function getMainList() {
        $rsDatos = $this->comprobantes_model->getMainList();        
        sendJsonData($rsDatos);
    }
    public function getMainListDetail()
    {
        $rsDatos = $this->comprobantes_model->getMainListDetail();
        sendJsonData($rsDatos);
    }

    public function documentosBuscar() {
        /**
          //ordern de parametros para la URL
         * 3 empresa
         * 4 cliente   (descripcion)
         * 5 cliente_id
         * 6 tipo_documento
         * 7 fecha_emision_desde
         * 8 fecha_emision_hasta
         * 9 serie
         * 10 numero
         * 11 pagina          
         * */
        $pagina = 1;
        $cliente_id = ($this->input->post('cliente') == '') ? '' : $this->input->post('cliente_id');

        $url = "index.php/comprobantes/documentos/" . $this->input->post('empresa_id') . "/" .
                myUrlEncode($this->input->post('cliente')) . "/" .
                myUrlEncode($cliente_id) . "/" .
                myUrlEncode($this->input->post('tipo_documento')) . "/" .
                myUrlEncode($this->input->post('fecha_de_emision_inicio')) . "/" .
                myUrlEncode($this->input->post('fecha_de_emision_final')) . "/" .
                myUrlEncode($this->input->post('serie')) . "/" .
                myUrlEncode($this->input->post('numero')) . "/" .
                $pagina;
        //echo $url;exit;
        redirect(base_url() . $url);
    }



    public function index() {
        //PAGINACION        
        $inicio = 0;
        $limite = 15;    
        $empresa_id = 1;

        if($this->uri->segment(3) != '' )     
        $data['comprobante_id'] = $this->uri->segment(3);        

        $data['tipo_documentos'] = $this->tipo_documentos_model->select();
        $data['tipo_pagos']     = $this->tipo_pagos_model->select();
        $data['cliente_select'] = $this->input->post('cliente');
        $data['cliente_select_id'] = $this->input->post('cliente_id');
        $data['tipo_documento_id'] = $this->input->post('tipo_documento');
        $data['tipo_pago_id'] = $this->input->post('tipo_pago');
        $data['serie_select']  = $this->input->post('serie');
        $data['numero_select'] = $this->input->post('numero');
        $data['empresa'] = $this->empresas_model->select($empresa_id);
        $data['numero_pedido_select'] = $this->input->post('numero_pedido');
        $data['orden_compra_select'] = $this->input->post('orden_compra');
        $data['numero_pedido_select'] = $this->input->post('numero_pedido');
        $data['numero_guia_select'] = $this->input->post('numero_guia');
        $data['empresa'] = $this->empresas_model->select($empresa_id);        

        $cliente_id = '';
        if (($this->input->post('cliente_id') != '') && ($this->input->post('cliente') != '')) {
            $cliente_id = $this->input->post('cliente_id');
        }

        $tipo_documento_id = '';
        if ($this->input->post('tipo_documento') != '')
            $tipo_documento_id = trim($this->input->post('tipo_documento'));

        $serie = '';
        if ($this->input->post('serie') != '')
            $serie = trim($this->input->post('serie'));

        $numero = '';
        if ($this->input->post('numero') != '')
            $numero = trim($this->input->post('numero'));

        $fecha_de_emision = '';
        if (!empty($this->input->post('fecha_desde'))) {
            $date = new DateTime($this->input->post('fecha_desde'));
            $fecha_de_emision = '"'.$date->format('Y-m-d').'"';

        }
        //print_r($fecha_de_emision);exit();
        $fecha_de_vencimiento = '';
        if (!empty($this->input->post('fecha_hasta'))) {
            $date = new DateTime($this->input->post('fecha_hasta'));
            $fecha_de_vencimiento = '"' . $date->format('Y-m-d') . '"';
        }
        $numero_pedido = '';
        if ($this->input->post('numero_pedido') != '')
            $numero_pedido = trim($this->input->post('numero_pedido'));

        $numero_guia = '';
        if ($this->input->post('numero_guia') != '')
            $numero_guia = trim($this->input->post('numero_guia'));   

        $orden_compra = '';
        if ($this->input->post('orden_compra') != '')
            $orden_compra = trim($this->input->post('orden_compra'));        

        $dataComprobante = $this->comprobantes_model->select('', $serie, $numero, $fecha_de_emision, $fecha_de_vencimiento, $cliente_id, $tipo_documento_id, '', '', $inicio, $limite, $empresa_id,$numero_pedido,$numero_guia,$orden_compra);
                
        $data['comprobantes'] = $this->comprobantes_model->formatVoucher($dataComprobante);        
        /* cargar la configuracion */
        $data['configuracion'] = $this->db->from('comprobantes_ventas')->get()->row();

        //$data['vendedores'] = $this->db->where('tipo_empleado_id',20)->get('empleados')->result();
        $data['vendedores'] = $this->empleados_model->select2(3);
        $data['config'] = $this->db->get('comprobantes_ventas')->row();



        $viewContent = 'comprobantes/index';
        //Status Caja
        $rs = $this->cajas_model->ultimoRegCaja();    

        //var_dump($rs);exit;
        if(($rs->tipo_transaccion_id == 2) && ($this->session->userdata('tipo_empleado_id') == ST_CAJA)){
            echo '<script>alert("DEBE APERTURAR CAJA")</script>';                    
            $viewContent = 'cajas/index';
        }

        $this->accesos_model->menuGeneral();
        $this->load->view($viewContent, $data);
        $this->load->view('templates/footer');

    }

    //ALEXANDER FERNANDEZ 14-10-2020
    public function modal_pagoMonto(){
        $data['tipo_pagos'] =  $this->tipo_pagos_model->select();
        echo $this->load->view('comprobantes/modal_pagoMonto',$data);
    }
    
    //ALEXANDER FERNANDEZ 04-08-2020
    public function modalEnvioComprobante(){
          
        $data['comprobante'] = $this->comprobantes_model->select($this->uri->segment(3));
        $tipo_documento_id = $data['comprobante']['tipo_documento_id'];
        switch ($tipo_documento_id) {
            case 1:
                $tipo_documento = "FACTURA ELECTRONICA";
                break;
            case 3:
                $tipo_documento = "BOLETA ELECTRONICA";
                break;
            case 7:
                $tipo_documento = "NOTA DE CREDITO";
                $data['tipo_nota'] = $this->tipo_ncreditos_model->select($data['comprobante']['tipo_nota_id']);
                $data['comp_adjunto'] = $this->comprobantes_model->select($data['comprobante']['com_adjunto_id']);
                break;
            case 8:
                $tipo_documento = "NOTA DE DEBITO";
                $data['tipo_nota'] = $this->tipo_ndebitos_model->select($data['comprobante']['tipo_nota_id']);
                $data['comp_adjunto'] = $this->comprobantes_model->select($data['comprobante']['com_adjunto_id']);
                break;
        }

        $data['tipo_documento'] = $tipo_documento;
        //var_dump($data['comprobante']);
        echo $this->load->view('comprobantes/modal_envio_comprobante',$data);
    }

    //ALEXANDER FERNANDEZ 04-08-2020
    public function enviarWatsapModal(){

        $data['comprobante'] = $this->comprobantes_model->select($this->uri->segment(3));
        echo $this->load->view("comprobantes/enviarWatsapModal",$data);
    }


    public function enviarEmailModal(){

        $data['comprobante'] = $this->comprobantes_model->select($this->uri->segment(3));
        echo $this->load->view("comprobantes/enviarEmailModal",$data);
    }



    public function enviarWatsapModal_g(){

        $comprobante_id = $_POST['comprobante_id'];
        $telefono_movil = $_POST['telefono_movil'];        

        $comprobante = $this->comprobantes_model->select($comprobante_id);

        if ($comprobante['telefono_movil_1'] == '') {            
            $this->db->where('ruc',$comprobante['cliente_ruc']);
            $this->db->update('clientes',array('telefono_movil_1'=> $telefono_movil));
        }
        //https://api.whatsapp.com/send?phone=936554175&text=dsfasdf

        //echo '<script LANGUAGE="JavaScript">';
        //echo 'setTimeout(function() {location.href="https://api.whatsapp.com/send?phone=936554175&text=dsfasdf";}, 2000);';
        //echo '</script>';
        echo json_encode(['status' => STATUS_OK, 'msg' => 'Mensaje enviado correctamente']);
        exit();        
    }

    //ALEXANDER FERNANDEZ 04-08-2020
    public function enviarEmailModal_g(){

        $comprobante_id = $_POST['comprobante_id'];
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

        $comprobante = $this->comprobantes_model->select($comprobante_id);
        if($comprobante['cli_email'] == ''){
            $this->db->where('ruc', $comprobante['cliente_ruc']);
            $this->db->update('clientes',array('email' => $mailcc));
        }                

        //CREANDO PDF
        $this->create_pdf($comprobante_id);
        $this->get_xml($comprobante_id); 

        $file_pdf = APPPATH . "files_pdf/comprobantes/" .$comprobante['empresa_ruc'].'-0'.$comprobante['tipo_documento_id'].'-'. $comprobante['serie'] .'-'. $comprobante['numero'] . ".pdf";        
        $file_xml = APPPATH . "files_xml/comprobantes/" .$comprobante['empresa_ruc'].'-0'.$comprobante['tipo_documento_id'].'-'. $comprobante['serie'] .'-'. $comprobante['numero'] . ".xml";        
        
        
        $this->email->attach($file_pdf);
        $this->email->attach($file_xml);        

        $sender_email = $correo->correo_user;
        $sender_username = $empresa->empresa;  

        // Sender email address
        $this->email->from($sender_email, $sender_username);  
        $this->email->to($mailcc);
        $this->email->cc('fernandezdelacruza@gmail.com');


        $buscar=array(chr(13).chr(10), "\r\n", "\n", "\r");
        $reemplazar=array("", "", "", "");                                       
        $cliente_razon_social = str_ireplace($buscar,$reemplazar,$this->sanear_string(utf8_decode($comprobante['cli_razon_social'])));
        $cliente_razon_social = str_replace("&", "Y", trim(utf8_decode($cliente_razon_social)));

        $tipoDocumentoFormat =  $this->tipoDocumentoFormat($comprobante['tipo_documento_id']);
        $this->email->subject('COPIA '.$tipoDocumentoFormat.' '. $comprobante['serie'].'-'.$comprobante['numero'].'|'.$cliente_razon_social.'|'.$comprobante['cliente_ruc']);

        $body  = 'Sres '.$comprobante['cliente_ruc'].' '.$cliente_razon_social.'<br><br>';
        $body .= 'Sres '.$comprobante['empresa'].', '.'envía una '.$tipoDocumentoFormat.'<br><br>';

        $body .= '- TIPO: '.$tipoDocumentoFormat.'<br>';
        $body .= '- SERIE: '.$comprobante['serie'].'<br>';
        $body .= '- NUMERO: '.$comprobante['numero'].'<br>';
        $body .= '- FECHA DE EMISIÓN: '.$comprobante['fecha_de_emision'].'<br>';
        $body .= '- TOTAL: '.$comprobante['total_a_pagar'].'<br><br><br>';


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


    public function tipoDocumentoFormat($tipo_documento_id){

         switch ($tipo_documento_id) {
            case 1:
                $tipo_documento = "FACTURA ELECTRÓNICA";
                return $tipo_documento;
                break;
            case 3:
                $tipo_documento = "BOLETA ELECTRÓNICA";
                return $tipo_documento;
                break;
            case 7:
                $tipo_documento = "NOTA DE CREDITO ELECTRÓNICA";                
                return $tipo_documento;
                break;
            case 8:
                $tipo_documento = "NOTA DE DEBITO ELECTRÓNICA";
                return $tipo_documento;
                break;
        }
    }

    public function agregarAnticipoUi()
    {
        $anticipos = $this->comprobantes_model->listaAnticiposClientes();
        $data = [
                    "anticipos" => $anticipos
                ];
        echo $this->load->view('comprobantes/modal_anticipo_ui', $data);
    }

    //LISTA DE PRECIOS A SELECCIONAR 03-08-2020 - ALEXANDER FERNANDEZ
    public function SeleccionaListaPrecio(){        
        $producto = $this->productos_model->select($_REQUEST['productoId']);        
        $data = [
                    "producto" => $producto
                ];
        echo $this->load->view('comprobantes/modal_lista_precio', $data);
    }

    public function guardarAnticipo()
    {   $error = false;
        if($_POST['anticipo_id'] == "")
        {
            $error = true;
        }
        if($_POST['cliente'] == "")
        {
            $error = true;
        }
        if($error)
        {
            sendJsonData(['status'=>STATUS_FAIL, 'tipo'=>1]);
            exit();
        }
        //guardamos el producto
        $result = $this->comprobantes_model->guardarAnticipo();       
    }

    public function getListaAnticiposAgregados()
    {
        //obtenemos la lista de anticipos agregados
        $anticipos = $this->session->userdata("comprobantes_anticipos");
        $datos = [];
        foreach ($anticipos as $key => $value) {
            $value->eliminar = '<button type="button" data-anticipo="'.$key.'" class="btn btn-danger btn-xs btn-eliminar_anticipo"><i class="glyphicon glyphicon-remove"></i></button>';
            $datos[] = $value;
        }
        sendJsonData(['status'=>STATUS_OK,'data'=>$datos]);
        exit();
    }
    public function eliminarAnticipo()
    {
        //eliminamos de session el anticipo agregado
        $anticipos = $this->session->userdata("comprobantes_anticipos");
        $importeAnticipoEliminar=$anticipos[$_POST['anticipo']]->anticipo_total;
        unset($anticipos[$_POST['anticipo']]);
        //obtenemos la suma totoal de los anticipos
        $totalAnticipos = 0;
        $total_a_pagar = $_POST['total_a_pagar']-$importeAnticipoEliminar;
        $gravadas = $total_a_pagar/1.18;
        $igv = $total_a_pagar-$gravadas; 
        foreach ($anticipos as $key => $value)
        {
            $totalAnticipos+=$value->anticipo_total;
        }
        $this->session->set_userdata("comprobantes_anticipos",$anticipos);
        $datos = [
                    'status'=>STATUS_OK,
                    'totalAnticipo'=>$totalAnticipos,
                    'gravadas' => round($gravadas,2),
                    'igv'=> round($igv, 2),
                    'totalPagar' => round($total_a_pagar,2)
                 ];
        sendJsonData($datos);
        exit();
    }

    public function indexo($pagina = '') {
        //$this->output->enable_profiler(TRUE);

        $this->load->library('pagination');

        $cliente_id = '';
        if (($this->input->post('cliente_id') != '') && ($this->input->post('cliente') != '')) {
            $cliente_id = $this->input->post('cliente_id');
        }

        $tipo_documento_id = '';
        if ($this->input->post('tipo_documento') != '')
            $tipo_documento_id = $this->input->post('tipo_documento');

        $serie = '';
        if ($this->input->post('serie') != '')
            $serie = $this->input->post('serie');

        $numero = '';
        if ($this->input->post('numero') != '')
            $numero = $this->input->post('numero');

        $fecha_de_emision = '';
        if (!empty($this->input->post('fecha_de_emision'))) {
            $date = new DateTime($this->input->post('fecha_de_emision'));
            $fecha_de_emision = "'" . $date->format('Y-m-d') . "'";
        }

        $fecha_de_vencimiento = '';
        if (!empty($this->input->post('fecha_de_vencimiento'))) {
            $date = new DateTime($fecha_de_vencimiento);
            $fecha_de_vencimiento = "'" . $date->format('Y-m-d') . "'";
        }


        $inicio = 0;
        $limite = 5;

        if ($pagina) {
            $inicio = ($pagina - 1) * $limite;
        }

        $data['comprobantes'] = $this->comprobantes_model->select('', '', '', '', '', '', '', '', '', $inicio, $limite, '');

        $config['base_url'] = base_url() . '/index.php/comprobantes/indexo/';
        $config['total_rows'] = count($this->comprobantes_model->select('', '', '', '', '', '', '', '', '', FALSE, FALSE, ''));
        $config['per_page'] = $limite;
        $this->pagination->initialize($config);

        $data = array();
        $this->accesos_model->menuGeneral();
        $this->load->view('comprobantes/indexo', $data);
        $this->load->view('templates/footer');
    }

    public function comprobante() {
        //PAGINACION        
        $inicio = 0;
        $limite = 15;
        $empresa_id = $this->uri->segment(3);

        $data['pagina'] = $this->uri->segment(4);
        $data['cantidad_fila'] = $limite;
        $pagina = $this->uri->segment(4);
        if ($pagina) {
            $inicio = ($pagina - 1) * $limite;
        }

        $data['tipo_documentos'] = $this->tipo_documentos_model->select();
        $data['cliente_select'] = $this->input->post('cliente');
        $data['cliente_select_id'] = $this->input->post('cliente_id');
        $data['tipo_documento_id'] = $this->input->post('tipo_documento');
        $data['serie_select'] = $this->input->post('serie');
        $data['numero_select'] = $this->input->post('numero');
        $data['empresa'] = $this->empresas_model->select($empresa_id);

        $cliente_id = '';
        if (($this->input->post('cliente_id') != '') && ($this->input->post('cliente') != '')) {
            $cliente_id = $this->input->post('cliente_id');
        }

        $tipo_documento_id = '';
        if ($this->input->post('tipo_documento') != '')
            $tipo_documento_id = $this->input->post('tipo_documento');

        $serie = '';
        if ($this->input->post('serie') != '')
            $serie = $this->input->post('serie');

        $numero = '';
        if ($this->input->post('numero') != '')
            $numero = $this->input->post('numero');

        $fecha_de_emision = '';
        if (!empty($this->input->post('fecha_de_emision'))) {
            $date = new DateTime($this->input->post('fecha_de_emision'));
            $fecha_de_emision = "'" . $date->format('Y-m-d') . "'";
        }

        $fecha_de_vencimiento = '';
        if (!empty($this->input->post('fecha_de_vencimiento'))) {
            $date = new DateTime($fecha_de_vencimiento);
            $fecha_de_vencimiento = "'" . $date->format('Y-m-d') . "'";
        }

        $dataComprobante = $this->comprobantes_model->select('', $serie, $numero, $fecha_de_emision, $fecha_de_vencimiento, $cliente_id, $tipo_documento_id, '', '', $inicio, $limite, $empresa_id);
        $data['comprobantes'] = $this->comprobantes_model->formatVoucher($dataComprobante);

        //var_dump($data['comprobantes']);exit;
        //PAGINACION        
        $this->load->library('pagination');
        $config['base_url'] = base_url() . 'index.php/comprobantes/comprobante/' . $empresa_id;
        $config['total_rows'] = count($this->comprobantes_model->select('', $serie, $numero, $fecha_de_emision, $fecha_de_vencimiento, $cliente_id, $tipo_documento_id, '', '', FALSE, FALSE, $empresa_id));
        $config['per_page'] = $limite;
        $config['uri_segment'] = 4;
        $config['first_url'] = base_url() . 'index.php/comprobantes/comprobante/' . $empresa_id . '/1';
        $config['num_links'] = 3;

        //PAGINACION - BOOSTRAP
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = false;
        $config['last_link'] = false;
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = '&laquo';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&raquo';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $this->pagination->initialize($config);
        $this->accesos_model->menuGeneral();
        $this->load->view('comprobantes/index', $data);
        $this->load->view('templates/footer');
    }

    public function index_asesor($pagina = FALSE) {
        //PAGINACION        
        $inicio = 0;
        $limite = 15;
        $empresa_id = 2;

        if ($pagina) {
            $inicio = ($pagina - 1) * $limite;
        }

        $data['tipo_documentos'] = $this->tipo_documentos_model->select();
        $data['cliente_select'] = $this->input->post('cliente');
        $data['cliente_select_id'] = $this->input->post('cliente_id');
        $data['tipo_documento_id'] = $this->input->post('tipo_documento');
        $data['serie_select'] = $this->input->post('serie');
        $data['numero_select'] = $this->input->post('numero');
        $data['empresa'] = $this->empresas_model->select($empresa_id);

        $cliente_id = '';
        if (($this->input->post('cliente_id') != '') && ($this->input->post('cliente') != ''))
            $cliente_id = $this->input->post('cliente_id');

        $tipo_documento_id = '';
        if ($this->input->post('tipo_documento') != '')
            $tipo_documento_id = $this->input->post('tipo_documento');

        $serie = '';
        if ($this->input->post('serie') != '')
            $serie = $this->input->post('serie');

        $numero = '';
        if ($this->input->post('numero') != '')
            $numero = $this->input->post('numero');

        $fecha_de_emision = '';
        if (!empty($this->input->post('fecha_de_emision'))) {
            $date = new DateTime($this->input->post('fecha_de_emision'));
            $fecha_de_emision = "'" . $date->format('Y-m-d') . "'";
        }

        $fecha_de_vencimiento = '';
        if (!empty($this->input->post('fecha_de_vencimiento'))) {
            $date = new DateTime($fecha_de_vencimiento);
            $fecha_de_vencimiento = "'" . $date->format('Y-m-d') . "'";
        }

        $data['comprobantes'] = $this->comprobantes_model->select('', $serie, $numero, $fecha_de_emision, $fecha_de_vencimiento, $cliente_id, $tipo_documento_id, '', '', $inicio, $limite, $empresa_id);
        //var_dump($data['comprobantes']);exit;
        //PAGINACION        
        $this->load->library('pagination');
        $config['base_url'] = base_url() . 'index.php/pagina';
        $config['total_rows'] = count($this->comprobantes_model->select('', $serie, $numero, $fecha_de_emision, $fecha_de_vencimiento, $cliente_id, $tipo_documento_id, '', '', FALSE, FALSE, $empresa_id));
        $config['per_page'] = $limite;
        $config['uri_segment'] = 2;
        $config['first_url'] = base_url() . 'index.php/pagina/1';
        $config['num_links'] = 2;

        //PAGINACION - BOOSTRAP
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = false;
        $config['last_link'] = false;
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = '&laquo';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&raquo';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $this->pagination->initialize($config);
        $this->accesos_model->menuGeneral();
        $this->load->view('comprobantes/index', $data);
        $this->load->view('templates/footer');
    }
    public function nuevo($factura_id = '', $valida = 0) {
        $data['empresa'] = $this->empresas_model->select($this->uri->segment(3));
        if($data['empresa']['ruc']==''){
             $this->session->set_flashdata('mensaje','Registre datos de su empresa');
             redirect(base_url() . "index.php/comprobantes/index/" . 1);
             exit();
        }

        $data['adjunto_estado'] = 0;
        if($this->uri->segment(4)!=0){
           if($this->uri->segment(4)==1){
               if($this->uri->segment(5)==1){
                   $data['adjunto_tipo_documento'] = 7;
               }else if($this->uri->segment(5)==3){
                   $data['adjunto_tipo_documento'] = 9;
               }
           }else if($this->uri->segment(4)==2){
               if($this->uri->segment(5)==1){
                   $data['adjunto_tipo_documento'] = 8;
               }else if($this->uri->segment(5)==3){
                   $data['adjunto_tipo_documento'] = 10;
               }
           }

           $this->db->select('a.*,c.ruc,c.razon_social,c.tipo_cliente_id');
           $this->db->from('comprobantes as a');
           $this->db->join('clientes as c','c.id=a.cliente_id');
           $this->db->where('a.id',$this->uri->segment(6));
           $adjunto_com = $this->db->get()->row();


           $this->db->where('comprobante_id',$this->uri->segment(6));
           $adjunto_items = $this->db->get('items')->result();

            $data['adjunto_estado'] = 1;
            $data['adjunto_id'] = $this->uri->segment(6);
            $data['adjunto_datos'] = $adjunto_com;
            $data['adjunto_items'] = $adjunto_items;

        }
        if($this->session->userdata("comprobantes_anticipos")!=null)
        {
            $this->session->unset_userdata('comprobantes_anticipos');
        }
        $this->session->set_userdata("comprobantes_anticipos",[]);

        $data['valida'] = 0;
        $data['ajaxId'] = 0;
        if ($valida === '1') {
            session_start();
            unset($_SESSION['parametro']);
            $data['valida'] = 1;
            $data['ajaxId'] = $factura_id;
        }

        $data['tipo_documentos'] = $this->tipo_documentos_model->select('','',12);
        $data['transportistas'] = $this->transportistas_model->select();
        $data['tipo_pagos'] = $this->tipo_pagos_model->select();
        $data['tipo_item'] = $this->tipo_items_model->select();
        $data['tipo_igv'] = $this->tipo_igv_model->select('', '', '', 0);
        $data['tipo_ncreditos'] = $this->tipo_ncreditos_model->select('', '', '', 0);
        $data['tipo_ndebitos'] = $this->tipo_ndebitos_model->select('', '', '', 0);
        $data['elemento_adicionales'] = $this->elemento_adicionales_model->select('', '', 'activo');
        $data['monedas'] = $this->monedas_model->select();
        $data['empresas'] = $this->empresas_model->select();        
        $data['comp_adjuntos'] = $this->comprobantes_model->select('', '', '', '', '', '', '', '0');
        $data['ser_nums'] = $this->ser_nums_model->select();
        
        $data['factura_antigua'] = $this->variables_diversas_model->factura_antigua;
        $data['boleta_antigua'] = $this->variables_diversas_model->boleta_antigua;
        $data['tipo_clientes'] = $this->tipo_clientes_model->select();
        $data['medida'] = $this->medida_model->select();
        /* cargar la configuracion */
        $data['configuracion'] = $this->db->from('comprobantes_ventas')->get()->row();        


        $data['vendedores'] = $this->db->where('tipo_empleado_id',20)->get('empleados')->result();
        $data['igv'] = $this->db->get('comprobantes_ventas')->row();


        $data['rowIgvActivo'] = $this->igv_model->selectIgvActivo();
        $data['rowIcbPerActivo'] = $this->icbper_model->selectIcbPerActivo();

        $this->accesos_model->menuGeneral();
        $this->load->view('comprobantes/nuevo', $data);
        $this->load->view('templates/footer');
    }


    public function estado_igv($valor){
        //$valor = $this->input->get('valor');
        $this->db->set('pu_igv',$valor);
        $this->db->update('comprobantes_ventas');
        redirect(base_url() . "index.php/comprobantes/index/" . 1);
    }

    public function facturador_auto($valor){
        //$valor = $this->input->get('valor');
        $this->db->set('facturador_auto',$valor);
        $this->db->update('comprobantes_ventas');
        redirect(base_url() . "index.php/comprobantes/index/" . 1);
    }

    public function ticket_auto($valor){
        //$valor = $this->input->get('valor');
        $this->db->set('ticket_auto',$valor);
        $this->db->update('comprobantes_ventas');
        redirect(base_url() . "index.php/comprobantes/index/" . 1);
    }

  

    public function modificar() {                
        $detraccion_valor = $this->variables_diversas_model->detraccion_valor;
        $data['comprobante'] = $this->comprobantes_model->select($this->uri->segment(3));  
        

       // print_r($data['comprobante']);exit();

        //var_dump($data['comprobante']);exit;
        //la detraccion que se muestra en la vista siempre será el 10% del monto total independiente de la moneda.
        $data['comprobante']['total_detraccion_calculada'] = ($data['comprobante']['total_a_pagar'] >= $detraccion_valor) ? $data['comprobante']['total_a_pagar'] * 0.1 : 0.00;
        $data['comprobante']['fecha_de_emision_No_format'] = format_fecha_0000_00_00($data['comprobante']['fecha_de_emision']);                        
        
        $data['items'] = $this->items_model->select('', $this->uri->segment(3));

        //print_r($data['items'] );exit();  
        $data['transportistas'] = $this->transportistas_model->select();
        $data['tipo_documentos'] = $this->tipo_documentos_model->select();
        $data['tipo_pagos'] = $this->tipo_pagos_model->select();
        $data['tipo_item'] = $this->tipo_items_model->select();
        $data['tipo_igv'] = $this->tipo_igv_model->select('', '', '', 0);
        $data['tipo_ncreditos'] = $this->tipo_ncreditos_model->select('', '', '', 0);
        $data['tipo_ndebitos'] = $this->tipo_ndebitos_model->select('', '', '', 0);
        $data['elemento_adicionales'] = $this->elemento_adicionales_model->select('', '', 'activo');
        $data['monedas'] = $this->monedas_model->select();
        $data['empresas'] = $this->empresas_model->select();
        $data['comp_adjuntos'] = $this->comprobantes_model->select('', '', '', '', '', '', '', '0');
        $data['empresa'] = $this->empresas_model->select($data['comprobante']['empresa_id']);
        $data['tipo_clientes'] = $this->tipo_clientes_model->select();
        $data['medida'] = $this->medida_model->select();
        $data['vendedores'] = $this->db->where('tipo_empleado_id',20)->get('empleados')->result();
       
       /* cargar la configuracion */
        $data['configuracion'] = $this->db->from('comprobantes_ventas')->get()->row();
        $data['rowIgvActivo'] = $this->igv_model->selectIgvActivo();
        $data['rowIcbPerActivo'] = $this->icbper_model->selectIcbPerActivo();

        /*si tiene anticipo traemos el total*/
        $this->db->where('comprobante_anticipo.comprobante_id', $this->uri->segment(3));
        $this->db->from('comprobante_anticipo');
        $this->db->join('comprobantes','comprobante_anticipo.anticipo_id=comprobantes.id');
        $query = $this->db->get();
        $rsAnticipos = $query->result();
        $totalAnticipos = 0;
        $this->session->unset_userdata("comprobantes_anticipos");
        foreach($rsAnticipos as $anticipo)
        {
           $totalAnticipos += $anticipo->total_a_pagar; 
           /*agregamos a session los anticipos que se hayan agregado*/
            $datos = (object)[
                        'id'              => $anticipo->id,
                        'anticipo_numero' => $anticipo->serie.'-'.$anticipo->numero,
                        'anticipo_total'  => $anticipo->total_a_pagar
                     ];        
            $anticipos = $this->session->userdata("comprobantes_anticipos");
            $anticipos[$rsComprobante->id] = $datos;
            $this->session->set_userdata("comprobantes_anticipos", $anticipos);            
        }
        $data['totalAnticipo'] = $totalAnticipos;
        /* cargar la configuracion */
        $data['configuracion'] = $this->db->from('comprobantes_ventas')->get()->row();
        

        $this->accesos_model->menuGeneral();
        $this->load->view('comprobantes/modificar', $data);
        $this->load->view('templates/footer');
    }

    public function eliminar($comprobante_id) {
        $this->comprobantes_model->eliminar($comprobante_id);
        redirect(base_url() . "index.php/comprobantes/");
    }

    public function detalle() {

        $this->load->library('numletras');
        $comprobante_id = $this->uri->segment(3);

        $data['comprobante'] = $this->comprobantes_model->select($comprobante_id);
        $data['items'] = $this->items_model->select('', $comprobante_id);

        $tipo_documento_id = $data['comprobante']['tipo_documento_id'];

        switch ($tipo_documento_id) {
            case 1:
                $tipo_documento = "FACTURA ELECTRONICA";
                break;
            case 3:
                $tipo_documento = "BOLETA ELECTRONICA";
                break;
            case 7:
                $tipo_documento = "NOTA DE CREDITO";
                $data['tipo_nota'] = $this->tipo_ncreditos_model->select($data['comprobante']['tipo_nota_id']);
                $data['comp_adjunto'] = $this->comprobantes_model->select($data['comprobante']['com_adjunto_id']);
                break;
            case 8:
                $tipo_documento = "NOTA DE DEBITO";
                $data['tipo_nota'] = $this->tipo_ndebitos_model->select($data['comprobante']['tipo_nota_id']);
                $data['comp_adjunto'] = $this->comprobantes_model->select($data['comprobante']['com_adjunto_id']);
                break;
        }

        $data['tipo_documento'] = $tipo_documento;

        $this->load->view('templates/header_sin_menu');        
        $this->load->view('comprobantes/detalle', $data);
        $this->load->view('templates/footer');
    }

    public function dowload_xml($comprobante_id) {
        /*datos de la empresa*/
        $this->db->from("empresas")
                 ->where("id",1);
        $query = $this->db->get();
        $rsEmpresa = $query->row();
        /*obtenemos todos los datos del documento*/
        $this->db->from("comprobantes as com")
                 ->join("tipo_documentos as tdoc", "com.tipo_documento_id=tdoc.id")
                 ->join("clientes as cli", "com.cliente_id=cli.id")
                 ->join("tipo_clientes as tp", "cli.tipo_cliente_id=tp.id")
                 ->join("monedas as tmon", "com.moneda_id=tmon.id")
                 ->where("com.id",$comprobante_id);         
        $query = $this->db->get();
        $rsComprobante = $query->row();
        
        $rsTipoDocumento = $this->db->from('tipo_documentos')
                                    ->where('id',$rsComprobante->tipo_documento_id)
                                    ->get()
                                    ->row();
        /*obtenemos el detalle del documento*/
        $this->db->from("items")
                 ->where("comprobante_id", $comprobante_id);
        $query = $this->db->get();
        $rsDetalle = $query->result();
        
        $archivoXML = "{$rsEmpresa->ruc}-{$rsTipoDocumento->codigo}-{$rsComprobante->serie}-{$rsComprobante->numero}.xml";
        //$rutaFirma = "D:/".SFS."/sunat_archivos/sfs/FIRMA/aaa.xml";
        $rutaFirma = DISCO.':\xampp\htdocs'.CARPETA."/".SFS."/sunat_archivos/sfs/FIRMA/{$archivoXML}";
        if (file_exists($rutaFirma)) {
            //echo "El fichero $nombre_fichero existe :" .$rutaFirma;
            //header('Content-type: application/xml');
            
            header('Content-Description: File Transfer');
            header('Content-Type: application/xml');
            header('Content-Disposition: attachment; filename="'.basename($rutaFirma).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($rutaFirma));
            flush(); // Flush system output buffer
            readfile($rutaFirma);

            //sendJsonData(['status'=>STATUS_OK,'msg'=>$rutaFirma]);
            exit();
        
        } else {            
            echo '
            <script>                            
                window.close();
            </script>
            ';
            //sendJsonData(['status'=>STATUS_FAIL,'msg'=>'el xml no existe']);
        }
    }

    public function dowload_cdr($comprobante_id) {
        /*datos de la empresa*/
        $this->db->from("empresas")
                 ->where("id",1);
        $query = $this->db->get();
        $rsEmpresa = $query->row();
        /*obtenemos todos los datos del documento*/
        $this->db->from("comprobantes as com")
                 ->join("tipo_documentos as tdoc", "com.tipo_documento_id=tdoc.id")
                 ->join("clientes as cli", "com.cliente_id=cli.id")
                 ->join("tipo_clientes as tp", "cli.tipo_cliente_id=tp.id")
                 ->join("monedas as tmon", "com.moneda_id=tmon.id")
                 ->where("com.id",$comprobante_id);         
        $query = $this->db->get();
        $rsComprobante = $query->row();
        
        $rsTipoDocumento = $this->db->from('tipo_documentos')
                                    ->where('id',$rsComprobante->tipo_documento_id)
                                    ->get()
                                    ->row();
        /*obtenemos el detalle del documento*/
        $this->db->from("items")
                 ->where("comprobante_id", $comprobante_id);
        $query = $this->db->get();
        $rsDetalle = $query->result();
        
        $archivoXML = "R{$rsEmpresa->ruc}-{$rsTipoDocumento->codigo}-{$rsComprobante->serie}-{$rsComprobante->numero}.zip";
        //$rutaFirma = "D:/".SFS."/sunat_archivos/sfs/FIRMA/aaa.xml";
        $rutaFirma = DISCO.':\xampp\htdocs'.CARPETA."/".SFS."/sunat_archivos/sfs/RPTA/{$archivoXML}";
        if (file_exists($rutaFirma)) {
            //echo "El fichero $nombre_fichero existe :" .$rutaFirma;
            //header('Content-type: application/xml');
            
            header('Content-Description: File Transfer');
            header('Content-Type: application/xml');
            header('Content-Disposition: attachment; filename="'.basename($rutaFirma).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($rutaFirma));
            flush(); // Flush system output buffer
            readfile($rutaFirma);

            //sendJsonData(['status'=>STATUS_OK,'msg'=>$rutaFirma]);
            exit();
        
        } else {            
            echo '
            <script>                            
                window.close();
            </script>
            ';
            //sendJsonData(['status'=>STATUS_FAIL,'msg'=>'el xml no existe']);
        }
    }

    public function data_impresion_pos_ticket($idComprobante) {
        $this->load->library('numletras');
        //$comprobante_id = $this->uri->segment(3);
         $comprobante_id = $idComprobante;
        $comprobante = $this->db->from("comprobantes")
                                ->where("id", $this->uri->segment(3))
                                ->get()
                                ->row();     
        $data['comprobante'] = $this->comprobantes_model->select($comprobante_id);
        $data['items'] = $this->items_model->select('', $comprobante_id);
        $tipo_documento_id = $data['comprobante']['tipo_documento_id'];

        // total a pagar en letras
        $num = new Numletras();
        $totalVenta = explode(".",$comprobante->total_a_pagar);
        $totalLetras = $num->num2letras($totalVenta[0]);
        $totalLetras = $totalLetras.' con '.$totalVenta[1].'/100 '.$comprobante->moneda;
        $comprobante->total_letras = $totalLetras;    

            /*   echo "<pre>";           
                echo "----------- comprobantes ------";
                print_r($comprobante);
                echo "----------- data['comprobante'] --- ------";
                print_r($data['comprobante']);
                
                echo "--------- item --------". "\n";
                print_r($data['items']);
                echo "*************";
                echo $item[0]['producto_id'];
                echo "--------- producto --------". "\n";
                print_r($data['producto']);
                echo "-----------------". "\n";
                echo "</pre>";
                echo $data['comprobante']['cli_nombres']." ".$data['comprobante']['cli_razon_social']. "\n";
                echo "Tipo Documento: ";
                echo $data['comprobante']['cliente_ruc']."\n";
                echo("TOTAL A PAGAR EN LETRAS "."\n");
                echo $comprobante->total_letras;
                die(); */
       
        $resultComprobante = $this->db->from("tipo_documentos")
                                    ->where("id",$data['comprobante']['tipo_documento_id'])
                                    ->get()
                                    ->row();      
   
        $certificado = $this->ObtenerCertificado($data['comprobante']['empresa_ruc'],$resultComprobante->codigo,$data['comprobante']['serie'],$data['comprobante']['numero']);        

        switch ($tipo_documento_id) {
            case 1:
                $tipo_documento = "FACTURA DE VENTA ELECTRONICA";
                break;
            case 3:
                $tipo_documento = "BOLETA DE VENTA ELECTRONICA";
                break;
            case 7:
                $tipo_documento = "NOTA DE CREDITO";
                $data['tipo_nota'] = $this->tipo_ncreditos_model->select($data['comprobante']['tipo_nota_id']);
                $data['comp_adjunto'] = $this->comprobantes_model->select($data['comprobante']['com_adjunto_id']);
                break;
            case 8:
                $tipo_documento = "NOTA DE DEBITO";
                $data['tipo_nota'] = $this->tipo_ndebitos_model->select($data['comprobante']['tipo_nota_id']);
                $data['comp_adjunto'] = $this->comprobantes_model->select($data['comprobante']['com_adjunto_id']);
                break;
        }

        $data['tipo_documento'] = $tipo_documento;                                

        $documento ='';
        if (strlen($data['comprobante']['cliente_ruc'])==8) {
            $documento ="DNI";
        } else {
            $documento ="RUC";
        }
        

        
        $connector = new WindowsPrintConnector("POSS");
        //$connector = new NetworkPrintConnector("192.168.1.50", 9100);
        $printer = new Printer($connector);       
        $moneda = $data['comprobante']['simbolo'];

        /*imprimeir imagen*/
        try {
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $logo = EscposImage::load("C:/xampp/htdocs/Sitifac/images/".$data['comprobante']['foto'], false);
            $imgModes = array(
                Printer::IMG_DEFAULT,
                /*Printer::IMG_DOUBLE_WIDTH,
                Printer::IMG_DOUBLE_HEIGHT,
                Printer::IMG_DOUBLE_WIDTH | Printer::IMG_DOUBLE_HEIGHT*/
            );
            foreach ($imgModes as $mode) {
                $printer->bitImage($logo, $mode);
            }
        } catch (Exception $e) {/* $printer->text($e->getMessage() . "\n"); */ }

        try {

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setFont(Printer::FONT_B);          
                       

            $printer->text("\n" . $data['comprobante']['empresa'] . "\n");
            $printer->text("\n" . "RUC " . $data['comprobante']['empresa_ruc'] . "\n");
            $printer->text("\n" . $data['comprobante']['domicilio_fiscal'] . "\n");
            $printer->text("****************************************************************" . "\n");
            
            $printer->setFont(Printer::FONT_A); 
            $printer->text($data['tipo_documento']."\n");
            $printer->text($data['comprobante']['serie']."-".$data['comprobante']['numero']."\n");
            $printer->text("\n");
            
            $printer->setFont(Printer::FONT_B); 
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            date_default_timezone_set("America/Lima");
            $printer->text("Fecha Emisión: ");
            $printer->text($data['comprobante']['fecha_de_emision']."        Hora Emisión: ".date("h:i A") . "\n");
            $printer->text("Responsable: ");
            $printer->text($this->session->userdata('usuario'). " ". $this->session->userdata('apellido_paterno')."\n");

            $printer->text("****************************************************************" . "\n");
            $printer->text("Cliente: ");
            $printer->text($data['comprobante']['cli_nombres']." ".$data['comprobante']['cli_razon_social']."\n");
            $printer->text("Tipo Documento: ");
            $printer->text($documento."  ".$data['comprobante']['cliente_ruc']."\n");
            $printer->text("Dirección: ");
            $printer->text($data['comprobante']['cli_domicilio1']."\n");
            $printer->text("****************************************************************" . "\n");

            $printer->setJustification(Printer::JUSTIFY_LEFT);
            //$printer->text("CANT    DESCRIPCION          P/U            SUBTOTAL.\n");
            $printer->text("DESCRIPCION PRODUCTO                                PRECIO TOTAL\n");
            $printer->text("****************************************************************" . "\n");

            /*
                A partir de aca se imprimen los productos
            */
            /*Alinear a la izquierda para la cantidad y el nombre*/

            
            $desc = 0;
            $tipopago ="";
            $fila = 1;
            foreach ($data['items'] as $row) {

                if($fila!=1){
                  $printer->text("----------------------------------------------------------------" . "\n");
                }
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                if($row['producto_id']!=0){
                    $prod_codigo = $row['prod_codigo'];
                }else{
                    $prod_codigo = "00000";
                }
                
                /*$printer->text($prod_codigo);
                for($i = strlen($prod_codigo); $i <=14; $i++ ){
                    $printer->text(" ");
                }*/
            
                $printer->text($prod_codigo.'  '.$row['descripcion'].'  '.$row['unidad']);
                /*for($ii = strlen($row['descripcion']); $ii <= 38; $ii++ ){
                    $printer->text(" ");
                }*/

                $printer->text("\n"); 
                $printer->setJustification(Printer::JUSTIFY_RIGHT);
                $subtotal = $row['total'] ;
               
                $printer->text($row['cantidad'].' x '.number_format($row['importe'],2).'  '.number_format($subtotal,2));
                /*for($i = strlen($row['cantidad'].'x'.number_format($row['importe'],2)); $i <=51; $i++ ){
                    $printer->text(" ");
                }*/
                   
                                
                /*for($i = strlen(number_format($row['importe'],2)); $i <=9; $i++ ){
                    $printer->text(" ");
                }
                $printer->text(number_format($row['importe'],2)); 
                $printer->text(" ");*/


               /*$subtotal = $row['total'] ;
                for($i = strlen(number_format($subtotal,2)); $i <=9; $i++ ){
                    $printer->text(" ");
                }
                //$printer->text("  ");
                //$subtotal = ($row['incluye_igv']==1) ? $row['total'] : $row['subtotal'] ;
              

                $printer->text(number_format($subtotal,2));*/
                //$printer->text( "S/1220.00");
                $desc+= number_format($row['total_descuentos'],2);
                $tipopago = $row['tipo_pago'];
                $printer->text("\n");   

                $fila++;            
            }

            
            $printer->text("****************************************************************" . "\n");
            
            //descuento y subtotal                    
            $printer->setJustification(Printer::JUSTIFY_RIGHT);
            $printer->text("Total Descuentos :");
            for($i = strlen(number_format($row['descuento_global'],2)); $i < 20; $i++ ){
                $printer->text(" ");
            }
            if ($desc>0) {
                $printer->text($moneda." -". number_format($row['descuento_global'],2) . "\n");
            } else {
                $printer->text($moneda." ". number_format($row['descuento_global'],2) . "\n");
            }
            

            
            $printer->text("Subtotal :" );   
            for($i = strlen(number_format($data['comprobante']['total_gravada'],2)); $i < 20; $i++ ){
                $printer->text(" ");
            }
            $printer->text($moneda." ". number_format($data['comprobante']['total_gravada'] - $desc,2). "\n");

           $printer->text("****************************************************************" . "\n");

            
            $printer->text("Op. Gravadas :");
            for($i = strlen(number_format($data['comprobante']['total_gravada'],2)); $i < 20; $i++ ){
                $printer->text(" ");
            }
            $printer->text($moneda." ". ($data['comprobante']['total_gravada']) . "\n");
            
            $printer->text("Op. Inafectas :");
            for($i = strlen(number_format($data['comprobante']['total_inafecta'],2)); $i < 20; $i++ ){
                $printer->text(" ");
            }            
            $printer->text($moneda." ".$data['comprobante']['total_inafecta'] . "\n");
            
            $printer->text("Op. Gratuitas :");
            for($i = strlen(number_format($data['comprobante']['total_gratuita'],2)); $i < 20; $i++ ){
                $printer->text(" ");
            }            
            if ($data['comprobante']['total_gratuita']>0) {
                $printer->text($moneda." ".number_format($data['comprobante']['total_gratuita'],2) . "\n");
            } else {
                $printer->text($moneda." 0.00" . "\n");
            }
            
            $printer->text("IGV (18%) :");
            for($i = strlen(number_format($data['comprobante']['total_igv'],2)); $i < 20; $i++ ){
                $printer->text(" ");
            }                        
            $printer->text($moneda." ". number_format($data['comprobante']['total_igv'],2) . "\n");
            
            $printer->text("IMPORTE TOTAL :");
            for($i = strlen(number_format($data['comprobante']['total_a_pagar'],2)); $i < 20; $i++ ){
                $printer->text(" ");
            }            
            $printer->text($moneda." ". number_format($data['comprobante']['total_a_pagar'],2) . "\n");
            $printer->text("\n");
            
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("SON: ".$comprobante->total_letras. " ".$data['comprobante']['moneda'] ."\n");

            
            $printer->text("Forma de pago: ");           
            $printer->text($tipopago."  ");
            $printer->text($moneda." ". number_format($data['comprobante']['total_a_pagar'],2) . "\n");

            $printer->text(" "."\n");

            $codeQR = $this->GetImgQr($data['comprobante']);
            
            try {
                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $logo = EscposImage::load($codeQR, false);
                $imgModes = array(
                    Printer::IMG_DEFAULT,
                );
                foreach ($imgModes as $mode) {
                    $printer->bitImage($logo, $mode);
                }
            } catch (Exception $e) {}

            
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text($certificado."\n");
            $printer->text("\n");

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("Representación impresa de la ".$data['tipo_documento']."\n");
            $printer->text("       Obligado a ser Emisor Electrónico mediante la Resolución de Superintendecia"."\n");
            $printer->text("N° 155-2017/SUNAT-Anexo IV  \n");
            $printer->text(" " . "\n");
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("GRACIAS POR SU VISITA !  \n");
            $printer->feed(3);
            $printer->cut();
            $printer->pulse();
            $printer->close();
                        
            
        } finally {
            $printer->close();
        }

        sendJsonData(['status'=>STATUS_OK,]);
        exit(); 
    }
    public function GetImgQr($dataComprobante)  {
        $textoQR = '';
        $textoQR .= $dataComprobante['empresa_ruc']."|";//RUC EMPRESA
        $resultComprobante = $this->db->from("tipo_documentos")
                                    ->where("id",$dataComprobante['tipo_documento_id'])
                                    ->get()
                                    ->row();
        
        $textoQR .= "{$resultComprobante->codigo}|";//TIPO DE DOCUMENTO 
        $textoQR .= $dataComprobante['serie']."|";//SERIE
        $textoQR .= $dataComprobante['numero']."|";//NUMERO
        $textoQR .= $dataComprobante['total_igv']."|";//MTO TOTAL IGV
        $textoQR .= $dataComprobante['total_a_pagar']."|";//MTO TOTAL DEL COMPROBANTE
        //$fechaEmision = (new DateTime($rsComprobante->fecha_de_emision))->format('d-m-Y');
        $textoQR .= $dataComprobante['fecha_de_emision']."|";//FECHA DE EMISION 
        //tipo de cliente
        $rsTipoCliente = $this->db->from("tipo_clientes")
                                  ->where("id", $dataComprobante['tipo_cliente_id'])
                                  ->get()
                                  ->row();
        
     
        $textoQR .= "{$rsTipoCliente->codigo}|";//TIPO DE DOCUMENTO ADQUIRENTE 
        $textoQR .= $dataComprobante['cliente_ruc']."|";//NUMERO DE DOCUMENTO ADQUIRENTE 
        $qrCode = new QrCode($textoQR);
        $qrCode->setSize(200);
        $qrCode->setWriterByName('png');
        $nombreQR = $dataComprobante['tipo_documento_id'].'-'.$dataComprobante['serie'].'-'.$dataComprobante['numero'];
        unlink(FCPATH."images/qr/{$nombreQR}.png");
        $qrCode->writeFile(FCPATH."images/qr/{$nombreQR}.png");

        $ruta= FCPATH."images/qr/{$nombreQR}.png";
        return $ruta;
    }
    public function ObtenerCertificado($rucEmpresa,$tipodocCodigo,$comprobanteSerie,$comprobanteSNumero) {
        /*obetenemos el certificado*/
        $archivoXML = "{$rucEmpresa}-{$tipodocCodigo}-{$comprobanteSerie}-{$comprobanteSNumero}.xml";
        $rutaFirma = DISCO.':\xampp\htdocs'.CARPETA."/".SFS."/sunat_archivos/sfs/PARSE/{$archivoXML}";
        $certificado = '';
        //calidamos que exista fichero 
        if(file_exists($rutaFirma))
        {
            $library = new SimpleXMLElement($rutaFirma, null, true);
            $ns = $library->getDocNamespaces();
            $ext1 = $library->children($ns['ext']);
            $ext2 = $ext1->children($ns['ext']);
            $ext3 = $ext2->children($ns['ext']);
            $ds1 = $ext3->children($ns['ds']);
            $ds2 = $ds1->children($ns['ds']);
            $certificado = $ds2->SignedInfo->Reference->DigestValue; 

        }
        return $certificado;
    }


   
    public function show_ticket($comprobante_id='')
    {
        require_once (APPPATH .'libraries/Numletras.php');
        /*datos de la empresa*/
        $this->db->from("empresas")
                 ->where("id",1);
        $query = $this->db->get();
        $rsEmpresa = $query->row();
        /*obtenemos todos los datos del documento*/
        $this->db->from("comprobantes as com")
                 ->join("tipo_documentos as tdoc", "com.tipo_documento_id=tdoc.id")
                 ->join("clientes as cli", "com.cliente_id=cli.id")
                 ->join("monedas as tmon", "com.moneda_id=tmon.id")
                 ->where("com.id",$comprobante_id);         
        $query = $this->db->get();
        $rsComprobante = $query->row();
        /*obtenemos el detalle del documento*/
        $this->db->from("items")
                 ->where("comprobante_id", $comprobante_id);
        $query = $this->db->get();
        $rsDetalle = $query->result();

        $rsComprobante->fecha_de_emision = (new DateTime($rsComprobante->fecha_de_emision))->format("d/m/Y");
        $rsComprobante->fecha_de_vencimiento = ($rsComprobante->fecha_de_vencimiento!='')?(new DateTime($rsComprobante->fecha_de_vencimiento))->format("d/m/Y"):'';
        /*documento relacionado*/
        $rsRelacionado = $this->db->from("comprobantes")
                                  ->where("id", $rsComprobante->com_adjunto_id)
                                  ->get()
                                  ->row();

        //convetimos el total en texto
        $num = new Numletras();
        $totalVenta = explode(".",$rsComprobante->total_a_pagar);
        $totalLetras = $num->num2letras($totalVenta[0]);
        $totalLetras = $totalLetras.' con '.$totalVenta[1].'/100 '.$rsComprobante->moneda;
        $rsComprobante->total_letras = $totalLetras; 

        /*anticipos del documento*/
        $this->db->from("comprobante_anticipo as coma")
                 ->join("comprobantes as com", "coma.anticipo_id=com.id")
                 ->where("coma.comprobante_id", $comprobante_id);
        $query = $this->db->get();
        $rsAnticipos = $query->result(); 
        $totalAnticipo = 0;
        foreach($rsAnticipos as $item)
        {
           $totalAnticipo += $item->total_a_pagar; 
        }
        $data['comprobante'] = $this->comprobantes_model->select($comprobante_id);
        $rsComprobante->total_anticipos = $totalAnticipo;
        $certificado = $this->ObtenerCertificado($rsEmpresa->ruc,$rsComprobante->codigo,$rsComprobante->serie,$rsComprobante->numero);    
        $configuracion = $this->db->from('comprobantes_ventas')->get()->row();
        $data = [
                    "comprobante"   => $rsComprobante,
                    "relacionado"   => $rsRelacionado,
                    "empresa"       => $rsEmpresa,
                    "detalles"      => $rsDetalle,
                    "anticipos"     => $rsAnticipos,
                    "rutaqr"        => $this->GetImgQr($data['comprobante']),
                    "certificado"   => $certificado,
                    "configuracion" => $configuracion

                ];
        $html = $this->load->view("templates/ticket.php",$data,true);
        /*escribimos el archivo*/
        $archivo = $rsComprobante->serie.'-'.$rsComprobante->numero;
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
        exit();        
        $comprobante = $this->comprobantes_model->select($comprobante_id);
        $this->db->where("id", $comprobante_id);
        $query = $this->db->get("comprobantes");
        $comprobante1= $query->row();
        /*verificamos si el comprobante tiene anticipos agregados*/
        $this->db->where("comprobante_anticipo.comprobante_id", $comprobante1->id);
        $this->db->from("comprobante_anticipo");
        $this->db->join("comprobantes", "comprobante_anticipo.anticipo_id=comprobantes.id");
        $query = $this->db->get();
        $comprobante1->anticipos = $query->result();
        //print_r($comprobante1);exit();
        //var_dump($comprobante);exit;
        $items = $this->items_model->select('', $comprobante_id);
        $tnota = '';
        $cadjunto = '';

        //NOTA DE CREDITO,DEBITO
        if ($comprobante['tipo_documento_id'] == 7) {
            $tnota = $this->tipo_ncreditos_model->select($comprobante['tipo_nota_id']);
            $cadjunto = $this->comprobantes_model->select($comprobante['com_adjunto_id']);
        }
        if ($comprobante['tipo_documento_id'] == 8) {
            $tnota = $this->tipo_ndebitos_model->select($comprobante['tipo_nota_id']);
            $cadjunto = $this->comprobantes_model->select($comprobante['com_adjunto_id']);
        }
        //DECLARANDO VARIABLES
        $rucCliente = $comprobante['cliente_ruc'];
        $numSunat = 0;
        $codSunat = 0;
        $desSunat = 0;
        $serNum = $comprobante['serie'] . ' ' . $comprobante['numero'];
        $envValidacion = 0;
        //LLAMADA A LA WEBSERVICE
        $fichero = $this->config->item('base_ip').'/webServiceSunat/pdfSunat.php?comprobante=' . $comprobante['empresa_ruc'] . '-' . $comprobante['tipo_documento_codigo'] . '-' . $comprobante['serie'] . '-' . $comprobante['numero'] . '.xml&empresa_id=' . $comprobante['empresa_id'];                        
        $obj = json_decode(file_get_contents($fichero), true);

        //var_dump($obj);exit;
        if (!empty($obj)) {
            //$numSunat = $obj['numSunat'];//no se usa en pdf (hasta 25-10-2017)
            //$codSunat = $obj['codSunat'];//no se usa en pdf (hasta 25-10-2017)
            $desSunat = $obj['desSunat'];
            //$serNum = $obj['serNum'];//SI se usa en pdf (hasta 25-10-2017): ARROJA:  SERIE-NUMERO ..  de la factura
            $rucCliente = $obj['rucCliente']; //SI se usa pero usaremos el q viene del sistema back, pq estamos en offline.
            $envValidacion = 1;
        } //else {echo "Fichero no encontrado";}            
        $array = array(
            'rucCliente' => $rucCliente,
            'numSunat' => $numSunat,
            'codSunat' => $codSunat,
            'desSunat' => $desSunat,
            'serNum' => $serNum,
            'vista' => $vista,
            'envValidacion' => $envValidacion,
        );
        $comprobante = array_merge($comprobante, $array);
        
        $cuentas_bancarias = $this->cuentas_model->select(3);
        $cuenta_formateadas = $this->cuentas_model->formatCuentas($cuentas_bancarias);

        $this->load->library('Pdf', $comprobante);
        $this->pdf->GenerarComprobante($items, $tnota, $cadjunto, $cuenta_formateadas, $comprobante1);  
    }
    public function pdfGeneraComprobanteOffLine($comprobante_id = '', $vista = '') {
        require_once (APPPATH .'libraries/Numletras.php');
        /*datos de la empresa*/
        $this->db->from("empresas")
                 ->where("id",1);
        $query = $this->db->get();
        $rsEmpresa = $query->row();
        /*obtenemos todos los datos del documento*/
        $this->db->from("comprobantes as com")
                 ->join("tipo_documentos as tdoc", "com.tipo_documento_id=tdoc.id")
                 ->join("clientes as cli", "com.cliente_id=cli.id")
                 ->join("transportistas as trans", "com.transportista_id=trans.transp_id")
                 ->join("monedas as tmon", "com.moneda_id=tmon.id")
                 ->join("tipo_pagos as tpag", "com.tipo_pago_id=tpag.id")
                 ->where("com.id",$comprobante_id);         
        $query = $this->db->get();
        $rsComprobante = $query->row();
        /*obtenemos el detalle del documento*/
        $this->db->select('i.*,p.*,m.medida_codigo_unidad')
                 ->from("items as i")
                 ->join("productos as p","p.prod_id=i.producto_id", 'left')
                 ->join("medida as m","m.medida_id=i.unidad_id")
                 ->where("i.comprobante_id", $comprobante_id)
                 ->order_by("i.id","ASC");
        $query = $this->db->get();
        $rsDetalle = $query->result();

        //PAGO MONTO ALEXANDER FERNANDEZ 14-10-2020
        $rsPagoMonto =  $this->db->from('comprobante_pagos cmp')
                                ->join('tipo_pagos tpg','cmp.tipo_pago_id =  tpg.id')
                                ->where('cmp.comprobante_id', $comprobante_id)
                                ->get()
                                ->result();
   
        $rsComprobante->fecha_de_emision = (new DateTime($rsComprobante->fecha_de_emision))->format("d/m/Y");
        $rsComprobante->fecha_de_vencimiento = ($rsComprobante->fecha_de_vencimiento!='')?(new DateTime($rsComprobante->fecha_de_vencimiento))->format("d/m/Y"):'';
        /*documento relacionado*/
        $rsRelacionado = $this->db->from("comprobantes")
                                  ->where("id", $rsComprobante->com_adjunto_id)
                                  ->get()
                                  ->row();

        //convetimos el total en texto
        $num = new Numletras();
        $totalVenta = explode(".",$rsComprobante->total_a_pagar);
        $totalLetras = $num->num2letras($totalVenta[0]);
        $totalLetras = $totalLetras.' con '.$totalVenta[1].'/100 '.$rsComprobante->moneda;
        $rsComprobante->total_letras = $totalLetras; 

        /*anticipos del documento*/
        $this->db->from("comprobante_anticipo as coma")
                 ->join("comprobantes as com", "coma.anticipo_id=com.id")
                 ->where("coma.comprobante_id", $comprobante_id);
        $query = $this->db->get();
        $rsAnticipos = $query->result(); 
        $totalAnticipo = 0;
        foreach($rsAnticipos as $item)
        {
           $totalAnticipo += $item->total_a_pagar; 
        }
        $data['comprobante'] = $this->comprobantes_model->select($comprobante_id);

        $guia_id = $data['comprobante']['numero_guia_remision'];
        $datos_guia = $this->db->from('guias')
                               ->where('id',$guia_id)
                               ->get()->row();          
        
        $rsComprobante->total_anticipos = $totalAnticipo;
        $certificado = $rsComprobante->firma_sunat;
        $configuracion = $this->db->from('comprobantes_ventas')->get()->row();

        //ALEXANDER FERNANDEZ 31-10-2020
        $rs_almacen_principal = $this->almacenes_model->select($this->session->userdata('almacen_id'));//datos del almacen principal        
        $data = [
                    "comprobante"   => $rsComprobante,
                    "relacionado"   => $rsRelacionado,
                    "empresa"       => $rsEmpresa,
                    "detalles"      => $rsDetalle,
                    "pagoMonto"     => $rsPagoMonto,
                    "anticipos"     => $rsAnticipos,
                    "rutaqr"        => $this->GetImgQr($data['comprobante']),
                    "certificado"   => $certificado,
                    "configuracion" => $configuracion,
                    "guia"          => $datos_guia,
                    "almacen_principal" => $rs_almacen_principal
                ];
           
        $html = $this->load->view("templates/invoice.php",$data,true);


        //$config['source_image'] = '/path/a/imagen/mi_imagen.jpg';
        //$config['wm_text'] = 'Copyright 2018 - Bartolomé Esteban Murillo';
        //$config['wm_type'] = 'text';
        //$config['wm_font_path'] = './system/fonts/texb.ttf';
        //$config['wm_font_size'] = '16';
        //$config['wm_font_color'] = 'ffffff';

        //$config['wm_vrt_alignment'] = 'bottom';
        //$config['wm_hor_alignment'] = 'center';
        //$config['wm_padding'] = '20';
        //$this->image_lib->initialize($config);
        //$this->image_lib->watermark();


        $this->pdf->loadHtml($html);
        $this->pdf->setPaper('A4', 'portrait');
        $this->pdf->render();
        $tipo_documento_descargar = str_replace(" ","_",$rsComprobante->tipo_documento);
        $this->pdf->stream("$tipo_documento_descargar-$rsComprobante->serie-$rsComprobante->numero.pdf",
            array("Attachment"=>0)
        );            
    }
    
    public function pdfGeneraComprobanteOffLineAnulado($comprobante_id = '', $vista = '') {
        $comprobante = $this->comprobantes_model->select($comprobante_id);
        //var_dump($comprobante);exit;
        $items = $this->items_model->select('', $comprobante_id);
        $tnota = '';
        $cadjunto = '';

        //NOTA DE CREDITO,DEBITO
        if ($comprobante['tipo_documento_id'] == 7) {
            $tnota = $this->tipo_ncreditos_model->select($comprobante['tipo_nota_id']);
            $cadjunto = $this->comprobantes_model->select($comprobante['com_adjunto_id']);
        }
        if ($comprobante['tipo_documento_id'] == 8) {
            $tnota = $this->tipo_ndebitos_model->select($comprobante['tipo_nota_id']);
            $cadjunto = $this->comprobantes_model->select($comprobante['com_adjunto_id']);
        }
        //DECLARANDO VARIABLES
        $rucCliente = $comprobante['cliente_ruc'];
        $numSunat = 0;
        $codSunat = 0;
        $desSunat = 0;
        $serNum = $comprobante['serie'] . ' ' . $comprobante['numero'];
        $envValidacion = 0;       
        $array = array(
            'rucCliente' => $rucCliente,
            'numSunat' => $numSunat,
            'codSunat' => $codSunat,
            'desSunat' => $desSunat,
            'serNum' => $serNum,
            'vista' => $vista,
            'envValidacion' => $envValidacion,
        );
        $comprobante = array_merge($comprobante, $array);
        
        $cuentas_bancarias = $this->cuentas_model->select(3);        
        $cuenta_formateadas = $this->cuentas_model->formatCuentas($cuentas_bancarias);        
        $this->load->library('Sello', $comprobante);                

        $this->sello->GenerarComprobante($items, $tnota, $cadjunto, $cuenta_formateadas);
    }

    public function jsonComprobante($factura_id = '') {
        $comprobante = array();
        $comprobante = $this->comprobantes_model->jsonComprobante($factura_id);

        //echo $comprobante['comprobante_id'];
        //var_dump($comprobante);exit;   
        $valor = [];

        foreach ($comprobante as $value) {
            $arrayComprobante[$value['comprobante_id']]['comprobante_id'] = $value['comprobante_id'];
            $arrayComprobante[$value['comprobante_id']]['cliente_id'] = $value['cliente_id'];
            $arrayComprobante[$value['comprobante_id']]['razon_social'] = $value['razon_social'];
            $arrayComprobante[$value['comprobante_id']]['fecha'] = $value['fecha'];
            $arrayComprobante[$value['comprobante_id']]['moneda_id'] = $value['moneda_id'];
            $arrayComprobante[$value['comprobante_id']]['moneda'] = $value['moneda'];


            $arrayComprobante[$value['comprobante_id']]['item']['descripcion'] = $value['descripcion'];
            $arrayComprobante[$value['comprobante_id']]['item']['importe'] = $value['importe'];
        }

        echo json_encode($arrayComprobante);
        //echo json_encode($comprobante);
    }

    public function ValidarComprobante($comprobante_id) {

        //GENERO PDF
        $this->pdfGeneraComprobante($comprobante_id);
        $empleado = $this->session->userdata('email');
        $comprobante = $this->comprobantes_model->select($comprobante_id);

        $config['protocol'] = 'smtp';
        $config['mailtype'] = 'html';
        $config['smtp_host'] = 'mail.grupotytl.pe';
        $config['smtp_port'] = 25;
        $config['smtp_user'] = 'prueba@grupotytl.pe';
        $config['smtp_pass'] = '8vNA!hIW2fZy';

        $this->load->library('email');
        $this->email->initialize($config);
        $this->email->from('prueba@grupotytl.pe', 'Sistema Abogados - Re: ' . $empleado);
        $this->email->to('hdelacruz@tytl.com.pe');

        if ($comprobante['tipo_cliente_id'] == 1) {
            $this->email->subject('Factura Cliente - Validacion/ ' . $comprobante['cli_nombres'] . ' ' . $comprobante['cli_razon_social']);
            $body = '<h2>Validación de Emisión de Comprobante de Pago Exitosa!</h2><br>';
            $body .= 'El Comprobante de pago generado al Cliente: ' . $comprobante['cli_nombres'] . ' ' . $comprobante['cli_razon_social'] . ' ha sido emitido con éxito.<br><br>';
        } else {
            $this->email->subject('Factura Cliente - Validacion/ ' . $comprobante['cli_razon_social']);
            $body = '<h2>Validación de Emisión de Comprobante de Pago Exitosa!</h2><br>';
            $body .= 'El Comprobante de pago generado al Cliente: ' . $comprobante['cli_razon_social'] . ' ha sido emitido con éxito<br><br>';
        }
        $body .= 'Por favor de encontrar algún problema reportelo a hdelacruz@tytl.com.pe.<br>';
        $body .= 'Muchas gracias,';

        $this->email->message($body);
        $this->email->attach(APPPATH . "files_pdf/comprobantes/" . $comprobante['cliente_id'] . $comprobante['comprobante_id'] . ".pdf");
        //$mail->AltBody = 'Factura Cliente: ';
        if (!$this->email->send()) {
            $this->session->set_flashdata('mensaje', 'Error');
        } else {
            $this->session->set_flashdata('mensaje', 'Validacion de Factura exitosa!');
        }
    }
    public function create_pdf($comprobante_id = '')
    {
        require_once (APPPATH .'libraries/Numletras.php');
        /*datos de la empresa*/
        $this->db->from("empresas")
                 ->where("id",1);
        $query = $this->db->get();
        $rsEmpresa = $query->row();
        /*obtenemos todos los datos del documento*/
        $this->db->from("comprobantes as com")
                 ->join("tipo_documentos as tdoc", "com.tipo_documento_id=tdoc.id")
                 ->join("clientes as cli", "com.cliente_id=cli.id")
                 ->join("transportistas as trans", "com.transportista_id=trans.transp_id")
                 ->join("monedas as tmon", "com.moneda_id=tmon.id")
                 ->join("tipo_pagos as tpag", "com.tipo_pago_id=tpag.id")
                 ->where("com.id",$comprobante_id);         
        $query = $this->db->get();
        $rsComprobante = $query->row();
        /*obtenemos el detalle del documento*/
        /*$this->db->from("items")
                 ->where("comprobante_id", $comprobante_id);*/

          $this->db->select('i.*,p.*,m.medida_codigo_unidad')
                 ->from("items as i")
                 ->join("productos as p","p.prod_id=i.producto_id", 'left')
                 ->join("medida as m","m.medida_id=i.unidad_id")
                 ->where("i.comprobante_id", $comprobante_id);
        $query = $this->db->get();
        $rsDetalle = $query->result();

        //PAGO MONTO ALEXANDER FERNANDEZ 14-10-2020
        $rsPagoMonto =  $this->db->from('comprobante_pagos cmp')
                                ->join('tipo_pagos tpg','cmp.tipo_pago_id =  tpg.id')
                                ->where('cmp.comprobante_id', $comprobante_id)
                                ->get()
                                ->result();

        $rsComprobante->fecha_de_emision = (new DateTime($rsComprobante->fecha_de_emision))->format("d/m/Y");
        $rsComprobante->fecha_de_vencimiento = ($rsComprobante->fecha_de_vencimiento!='')?(new DateTime($rsComprobante->fecha_de_vencimiento))->format("d/m/Y"):'';
        /*documento relacionado*/
        $rsRelacionado = $this->db->from("comprobantes")
                                  ->where("id", $rsComprobante->com_adjunto_id)
                                  ->get()
                                  ->row();

        //convetimos el total en texto
        $num = new Numletras();
        $totalVenta = explode(".",$rsComprobante->total_a_pagar);
        $totalLetras = $num->num2letras($totalVenta[0]);
        $totalLetras = $totalLetras.' con '.$totalVenta[1].'/100 '.$rsComprobante->moneda;
        $rsComprobante->total_letras = $totalLetras; 

        /*anticipos del documento*/
        $this->db->from("comprobante_anticipo as coma")
                 ->join("comprobantes as com", "coma.anticipo_id=com.id")
                 ->where("coma.comprobante_id", $comprobante_id);
        $query = $this->db->get();
        $rsAnticipos = $query->result(); 
        $totalAnticipo = 0;
        foreach($rsAnticipos as $item)
        {
           $totalAnticipo += $item->total_a_pagar; 
        }
        $data['comprobante'] = $this->comprobantes_model->select($comprobante_id);
        $rsComprobante->total_anticipos = $totalAnticipo;
        //$certificado = $this->ObtenerCertificado($rsEmpresa->ruc,$rsComprobante->codigo,$rsComprobante->serie,$rsComprobante->numero);    
        $certificado = $rsComprobante->firma_sunat;
        $configuracion = $this->db->from('comprobantes_ventas')->get()->row();

        //ALEXANDER FERNANDEZ 31-10-2020
        $rs_almacen_principal = $this->almacenes_model->select($this->session->userdata('almacen_id'));//datos del almacen principal
        $data = [
                    "comprobante"   => $rsComprobante,
                    "relacionado"   => $rsRelacionado,
                    "empresa"       => $rsEmpresa,
                    "detalles"      => $rsDetalle,
                    "pagoMonto"     =>  $rsPagoMonto,
                    "anticipos"     => $rsAnticipos,
                    "rutaqr"        => $this->GetImgQr($data['comprobante']),
                    "certificado"   => $certificado,
                    "configuracion" => $configuracion,
                    "almacen_principal" => $rs_almacen_principal
                ];
        $html = $this->load->view("templates/invoice.php",$data,true);
        
        $archivo = $rsEmpresa->ruc.'-0'.$rsComprobante->tipo_documento_id.'-'.$rsComprobante->serie.'-'.$rsComprobante->numero.'.pdf';
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper('A4', 'portrait');
        $this->pdf->render();
        $contenido = $this->pdf->output();

        $bytes = file_put_contents(APPPATH.'files_pdf/comprobantes/'.$archivo, $contenido);
        return true;
    }

    /*enviar correo a los clientes */
    public function Send_Mail(){  

        $this->db->from("correo");
        $correo = $this->db->get()->row();

        /*datos de la empresa*/
        $this->db->from("empresas")->where("id",1);
        $empresa = $this->db->get()->row();
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
        $sender_email = $correo->correo_user;
        $sender_username = $empresa->empresa;  

        // Load email library and passing configured values to email library 
               
        //$this->email->set_newline("\r\n");

        // Sender email address
        $this->email->from($sender_email, $sender_username);       

        /*configuracion para enviar el correo*/
         if (isset($_POST['comprobante_id']))
            $comprobante_id = $_POST['comprobante_id'];
            $comprobante_id = $this->uri->segment(3);

        //$this->pdfGeneraComprobante($comprobante_id);
        $comprobante = $this->comprobantes_model->select($comprobante_id);

        if ($comprobante['cli_email']=='') {
            sendJsonData(['status'=>STATUS_FAIL,'msg'=>'El cliente no tiene correo']);
            exit();    
        }
        
        //print($comprobante['cli_email']);exit;
        /*crear pdf*/
        $this->create_pdf($comprobante_id);
        // Receiver email address
        $this->email->to($comprobante['cli_email']);
         
        $mailcc = ''; 
        if($comprobante['cli_email2']!=''){
            $mailcc.= $comprobante['cli_email2'];
        }

        if($comprobante['cli_email3']!=''){
            $mailcc.= ', '.$comprobante['cli_email3'];
        }

        if(strlen($mailcc)>0){
            $this->email->cc($mailcc);
        }

        $this->email->subject('Comprobante Electrónico '. ' - ' . $comprobante['cli_razon_social']);
        

        $body = '<html><head></head><body><h2>Comprobante de Pago Electrónico</h2>';
        $body .= 'Estimado Cliente, '. '<br>';
        $body .= 'Sr(es). '.$comprobante['cli_razon_social']. '<br>';
        $body .= 'RUC '. $comprobante['cliente_ruc']. '<br>';
        $body .= 'Informamos a usted que el documento '.$comprobante['serie'].'-'.$comprobante['numero'].' ya se encuentra disponible.' . '<br><br>';
        $body .= 'Saluda atentamente,<br>';
        $body .= '<b>' . $comprobante['empresa'] .' </b><br><br></body></html>';
       
       
        $file_pdf = APPPATH . "files_pdf/comprobantes/" .$comprobante['empresa_ruc'].'-0'.$comprobante['tipo_documento_id'].'-'. $comprobante['serie'] .'-'. $comprobante['numero'] . ".pdf";        
        $file_xml = $this->get_xml($comprobante['comprobante_id']);

        //$file_cdr = $this->get_cdr($comprobante['comprobante_id']);
        
        
        $this->email->attach($file_pdf);
        $this->email->attach($file_xml);
        //$this->email->attach($file_cdr);
       
        //$this->$mail->IsHTML(true);
       
       $this->email->message($body);
       
        // Message in email
        
         //$email->IsSendMail();

        if (!$this->email->send()) {
            sendJsonData(['status'=>STATUS_FAIL,'msg'=>'Correo Invalido !']);
            exit();
            //$data['message_display'] = 'Email Successfully Send !';
        } else {
            sendJsonData(['status'=>STATUS_OK,'msg'=>'Correo enviado con éxito !']);
            
            exit();
            //$data['message_display'] = '<p class="error_msg">Invalid Gmail Account or Password !</p>';
        }
         
        //redirect(base_url("contacto"));
        //echo $this->email->print_debugger();
    } 

    // $envio_automativo, se disparará los correos solamente cuando se guarda el comprobante (de forma individual).
    public function mailEnviarComprobante_1($comprobante_id = '') {
        if (isset($_POST['comprobante_id']))
            $comprobante_id = $_POST['comprobante_id'];

        //$this->pdfGeneraComprobante($comprobante_id);
        $comprobante = $this->comprobantes_model->select($comprobante_id);
        $this->load->library('email');

        //ENVIO CORREO
        $config['protocol'] = 'smtp';
        $config['mailtype'] = 'html';
        $config['smtp_host'] = 'mail.tytl.pe';
        //$config['smtp_host'] = 'mail.grupotytl.pe';
        //$config['smtp_host'] = 'mail.tytl.com.pe';
        $config['smtp_port'] = 25;


        $config['smtp_user'] = 'facturacion@tytl.pe';
        $config['smtp_pass'] = '%Facturacion2017%';
        /*$config['smtp_user'] = 'facturacion@grupotytl.pe';
        $config['smtp_pass'] = '%Facturacion2017%';
        $config['smtp_user'] = 'facturacion@tytl.com.pe';
        $config['smtp_pass'] = '%Facturar2017%';*/

        
        $this->email->initialize($config);
        $this->email->from('facturacion@tytl.pe', 'Sistema Abogados');
        //$this->email->from('facturacion@tytl.com.pe', 'Sistema Abogados');

        $correos_adjuntos = array(
            'hdelacruz@tytl.com.pe',
            'cobranzastytl.com.pe',
            'jvelasco@tytl.com.pe',
            'carmen@tytl.com.pe',
            'mdptm@tytl.com.pe'
        );       


        if ($this->uri->segment(4) == "enviar_cliente") {
            $this->email->to($comprobante['cli_email']);
            $this->email->cc($correos_adjuntos);
        }

        if ($this->uri->segment(4) == "enviar_equipo") {
            $this->email->to($comprobante['cli_email']);
            $this->email->cc($correos_adjuntos);
        }

        $this->email->subject('Facturación Electronica ' . $comprobante['empresa'] . ' ' . strtoupper($comprobante['descripcion1']) . ' - ' . $comprobante['cli_razon_social']);
        $body = '<h2>Comprobante de Pago Electronico</h2>';
        $body .= 'Estimado Cliente:<br><br>';
        $body .= 'Cliente: ' . $comprobante['cli_razon_social'] . '<br><br>';
        $body .= 'Adjunto a la presente se servirá encontrar nuestro comprobante de pago electrónico.' . '<br><br>';
        $body .= 'Por favor cualquier aclaración enviar un correo electrónico a cobranzas@tytl.com.pe' . '<br><br>';
        $body .= 'Muchas gracias,<br><br>';
        $body .= '<b>' . $comprobante['empresa'] . ' ' . strtoupper($comprobante['descripcion1']) . '</b><br><br>';
        $body .= 'Para confirmar la validez de su comprobante de pago, ingrese a la siguiente Dirección de Sunat: http://e-consulta.sunat.gob.pe/ol-ti-itconsvalicpe/ConsValiCpe.htm<br>';

        $this->email->message($body);
        $this->email->attach(APPPATH . "files_pdf/comprobantes/" . $comprobante['cliente_id'] . $comprobante['comprobante_id'] . ".pdf");
        //$mail->AltBody = 'Comprobante Cliente: ';
        $this->$mail->IsHTML(true);
        $this->$mail->msgHTML($body);

        $mail->IsSendMail();

      /*  if (!$this->email->send()) {
            //error_reporting(E_ALL);exit;
            $this->session->set_flashdata('mensaje', 'Error');
        } else {
            if ($this->uri->segment(4) == "enviar_cliente") {
                $this->comprobantes_model->modificar(array('enviado_cliente' => 1), $comprobante['comprobante_id']);
            }

            if ($this->uri->segment(4) == "enviar_equipo") {
                $this->comprobantes_model->modificar(array('enviado_equipo' => 1), $comprobante['comprobante_id']);
            }
            $this->session->set_flashdata('mensaje', 'Comprobante enviado correctamente');
        }*/
        $this->email->print_debugger();
        //$this->session->set_flashdata('mensaje','Error');
        //exit;
        redirect(base_url() . "index.php/comprobantes/documentos/" . $comprobante['empresa_id']);
    }

    public function mailEnviarComprobante($comprobante_id = '') {
        if (isset($_POST['comprobante_id']))
            $comprobante_id = $_POST['comprobante_id'];

        //$this->pdfGeneraComprobanteOffLine($comprobante_id);
        $comprobante = $this->comprobantes_model->select($comprobante_id);
        require_once(APPPATH . 'libraries/PHPMailerAutoload.php');

        $mail = new PHPMailer;
        $mail->CharSet = "UTF-8";
        $mail->isSMTP();
        $mail->Debugoutput = 'html';
        $mail->SMTPDebug = 2;
        $mail->SMTPAuth = true;
        $mail->Host = 'mail.tytl.pe';
        $mail->Port = 25;
        $mail->Username = "facturacion@tytl.pe";
        $mail->Password = "%Facturacion2017%";
        $mail->setFrom('facturacion@tytl.pe', 'Sistema Facturación Grupo TyTL');

        if ($this->uri->segment(4) == "enviar_cliente") {
            $mail->addAddress($comprobante['cli_email']);
            $mail->AddCC('mdptm@tytl.com.pe');
            $mail->AddCC('hdelacruz@tytl.com.pe');
            $mail->AddCC('cobranzas@tytl.com.pe');
            $mail->AddCC('jvelasco@tytl.com.pe');
            $mail->AddCC('carmen@tytl.com.pe');
            $mail->AddCC('kvelasquez@tytl.com.pe');
        }

        if ($this->uri->segment(4) == "enviar_equipo") {
            $mail->addAddress('hdelacruz@tytl.com.pe');
            $mail->AddCC('mdptm@tytl.com.pe');
            $mail->AddCC('cobranzas@tytl.com.pe');
            $mail->AddCC('jvelasco@tytl.com.pe');
            $mail->AddCC('carmen@tytl.com.pe');
            $mail->AddCC('kvelasquez@tytl.com.pe');
        }

        $mail->Subject = 'Facturación Electronica ' . $comprobante['empresa'] . ' ' . strtoupper($comprobante['descripcion1']) . ' - ' . $comprobante['cli_razon_social'];

        $body = '<h2>Comprobante de Pago Electronico</h2>';
        $body .= 'Estimado Cliente:<br><br>';
        $body .= 'Cliente: ' . $comprobante['cli_razon_social'] . '<br><br>';
        $body .= 'Adjunto a la presente se servirá encontrar nuestro comprobante de pago electrónico.' . '<br><br>';
        $body .= 'Por favor cualquier aclaración enviar un correo electrónico a cobranzas@tytl.com.pe' . '<br><br>';
        $body .= 'Muchas gracias,<br><br>';
        $body .= '<b>' . $comprobante['empresa'] . ' ' . strtoupper($comprobante['descripcion1']) . '</b><br><br>';
        $body .= 'Para confirmar la validez de su comprobante de pago, ingrese a la siguiente Dirección de Sunat:<br> <a href="http://e-consulta.sunat.gob.pe/ol-ti-itconsvalicpe/ConsValiCpe.htm">Consulta SUNAT</a><br><br>';
        //$body .= 'Descargar comprobante en PDF:<br> <a href="' . base_url() . 'index.php/comprobantes_ss/pdfGeneraComprobanteOffLine/' . $comprobante_id . '/0">' . $comprobante['serie'] . ' ' . $comprobante['numero'] . '</a><br>';

        $mail->IsHTML(true);
        $mail->msgHTML($body);

        //$mail->Body = $body;
        //$mail->AltBody = 'Resumen Total';

        $file1 = APPPATH . "files_pdf/comprobantes/" . $comprobante['cliente_id'] . $comprobante['comprobante_id'] . ".pdf";        
        $file2 = $this->selectRutaCDR($comprobante['empresa_id'], $comprobante['empresa_ruc'], $comprobante['tipo_documento_codigo'], $comprobante['serie'], $comprobante['numero']);
        $mail->AddAttachment($file1, $comprobante['cliente_id'] . $comprobante['comprobante_id'] . ".pdf");
        $mail->AddAttachment($file2, $comprobante['empresa_ruc'] . "-" . $comprobante['tipo_documento_codigo'] . "-" . $comprobante['serie'] . "-" . $comprobante['numero'] . ".xml");
        
        /*$mail->IsSendMail();

        if (!$mail->send()) {
            $this->session->set_flashdata('mensaje', 'Error');
        } else {
            if ($this->uri->segment(4) == "enviar_cliente") {
                $this->comprobantes_model->modificar(array('enviado_cliente' => 1), $comprobante['comprobante_id']);
            }

            if ($this->uri->segment(4) == "enviar_equipo") {
                $this->comprobantes_model->modificar(array('enviado_equipo' => 1), $comprobante['comprobante_id']);
            }
            $this->session->set_flashdata('mensaje', 'Comprobante enviado correctamente');
        }*/
        redirect(base_url() . "index.php/comprobantes/documentos/" . $comprobante['empresa_id']);
                   
    }

    public function popoverSunat($estado = '', $cliente_id = '') {
        /* ************************************************* */
        $comprobante = $this->comprobantes_model->select($_GET['comprobanteId']);
        $items = $this->items_model->select('', $_GET['comprobanteId']);
        $cliente = $this->clientes_model->select($_GET['clienteId']);
        $empresa = $this->empresas_model->select($comprobante['empresa_id']);

        $fichero = 'http://190.107.181.252/webServiceSunat/webServiceSunat.php?comprobante=R' . $comprobante['empresa_ruc'] . '-' . $comprobante['tipo_documento_codigo'] . '-' . $comprobante['serie'] . '-' . $comprobante['numero'] . '.zip&empresa_id=' . $comprobante['empresa_id'];
        //echo $fichero;
        $obj = json_decode(file_get_contents($fichero), true);
        //var_dump($obj);

        if (!empty($obj)) {
            $codSunat = $obj['codSunat'];
            $desSunat = $obj['desSunat'];

            echo $codSunat . '<br>' . $desSunat;
        } else {
            echo 'Fichero no encontrado';
        }
    }

    public function rptaSunat() {
        $query = $this->comprobantes_model->selecRptaSunat(1, 1);
        //var_dump($query);
        $numrow = count($query);
        $nuevoEstado = array();
        if ($numrow > 0) {
            foreach ($query as $comprobante) {                
                $fichero = 'http://localhost/webServiceSunat/webServiceSunat.php?comprobante=R' . $comprobante['empresa_ruc'] . '-' . $comprobante['tipo_documento_codigo'] . '-' . $comprobante['serie'] . '-' . $comprobante['numero'] . '.zip&empresa_id=' . $comprobante['empresa_id'];                
                $obj = json_decode(file_get_contents($fichero), true);
                
                if (!empty($obj)) {
                    $codSunat = $obj['codSunat'];
                    $desSunat = $obj['desSunat'];

                    if ($codSunat == 0) {
                        $this->comprobantes_model->modificar(array('estado_sunat' => $codSunat), $comprobante['comprobante_id']);
                    }
                }
            }
        }
        die(json_encode(array('status' => 'resultados', 'datos' => $nuevoEstado)));
    }
    
    //para confirmar las anulaciones
    public function rptaSunatAnulado() {
        //estado anulado: 
        //0 NADA(recien creado), 
        //1 MANDO a anular, 
        //2 CONFIRMO la anulacion
        $estado_anulado = 1; 
        $query = $this->comprobantes_model->selecRptaSunatAnulaciones($estado_anulado);
        $numrow = count($query);
        $nuevoEstado = array();

        if ($numrow > 0) {
            foreach ($query as $comprobante) {
                $numero = $this->comprobante_anulados_model->maxNumero(date("Y-m-d")) + 1;
                $fichero = 'http://localhost/webServiceSunat/webServiceSunat.php?comprobante=R' . $comprobante['empresa_ruc'] . '-RA-' . date("Ymd") . '-' . $numero . '&empresa_id=1';
                echo $fichero;exit;
                $obj = json_decode(file_get_contents($fichero), true);
                
                if (!empty($obj)) {
                    $codSunat = $obj['codSunat'];
                    $desSunat = $obj['desSunat'];
                    //para SUNAT: 0 BUENO, 1 MALO
                    //PARA MIKI: 0 NADA, 1 MANDO, 2 CONFIRMO
                    if ($codSunat == 0) {
                        // SI ES CERO quiere decir que se acepto la baja, por tanto confirmo
                        $this->comprobantes_model->modificar(array('anulado' => 2), $comprobante['comprobante_id']);
                    }
                }
            }
        }
        die(json_encode(array('status' => 'resultados', 'datos' => $nuevoEstado)));
    }

    public function xmlSunat($comprobante_id = '', $cliente_id = '') {
        $comprobante = $this->comprobantes_model->select($comprobante_id);
        //$items       = $this->items_model->select('',$comprobante_id);
        //$cliente     = $this->clientes_model->select($cliente_id);
        //$empresa     = $this->empresas_model->select($comprobante_id);                                               

        $fichero = 'http://190.107.181.252/webServiceSunat/xmlSunat.php?comprobante=' . $comprobante['empresa_ruc'] . '-' . $comprobante['tipo_documento_codigo'] . '-' . $comprobante['serie'] . '-' . $comprobante['numero'] . '.xml&empresa_id=' . $comprobante['empresa_id'];
        //echo $fichero;exit;
        $obj = json_decode(file_get_contents($fichero), true);
        //var_dump($obj);exit;
        //header("Content-type: text/xml; charset=utf-8");
        header('Content-type: text/xml; content="text/html; charset=UTF-8"');
        echo $obj['contenido'];
    }
    
    public function webServiceInvocarTXT($comprobante_id = '') {
        $comprobante_id = 102;
        $comprobante = $this->comprobantes_model->select($comprobante_id);
        //$items       = $this->items_model->select('',$comprobante_id);
        //$cliente     = $this->clientes_model->select($cliente_id);
        //$empresa     = $this->empresas_model->select($comprobante_id);
        //echo $comprobante['tipo_documento_id'];exit;
        //var_dump($comprobante);exit;
        $tipo_documento_identidad = ($comprobante['tipo_documento_id'] == 1) ? 6 : 1 ;
        
        $nombre_file = array(
            'ruc_emisor' => $comprobante['empresa_ruc'],
            'tipo_documento' => $comprobante['tipo_documento_codigo'],
            'serie' => $comprobante['serie'],
            'numero' => $comprobante['numero']
        );                        
        
        //tipo de moneda:  PEN   --  USD
        
        $datos_cabecera = array(
            'tipo_operacion-C' => '',
            'fecha_emision-M' => $comprobante['fecha_de_emision'],
            'codigo_domicilio_fiscal-C' => '',
            'tipo_documento_identidad-M' => $tipo_documento_identidad,
            'numero_documento_identidad-M' => $comprobante['cliente_ruc'],
            'razon_social_nombres-M' => $comprobante['cli_razon_social'],
            'tipo_moneda-M' => $comprobante['abrstandar'],
            'descuentos_globales-C' => '',
            'sumatoria_otros_cargos-C' => '',
            'total_descuentos-C' => '',
            'total_valor_venta_operaciones_grabadas-M' => '',
            'total_valor_venta_operaciones_inafectas-M' => '',
            'total_valor_venta_operaciones_exoneradas-M' => '',
            'Sumatoria_IGV-C' => '',
            'Sumatoria_ISC-C' => '',
            'Sumatoria_otro_tributos-C' => '',
            'Importe_total_venta-M' => ''            
        );
        
        echo json_encode($datos_cabecera);exit;
        

        $fichero = 'http://190.107.181.252/webServiceSunat/documento_cabecera.php.php?comprobante=' . $comprobante['empresa_ruc'] . '-' . $comprobante['tipo_documento_codigo'] . '-' . $comprobante['serie'] . '-' . $comprobante['numero'] . '.xml&empresa_id=' . $comprobante['empresa_id'];
        //echo $fichero;exit;
//        $obj = json_decode(file_get_contents($fichero), true);
//        //var_dump($obj);exit;
//        //header("Content-type: text/xml; charset=utf-8");
//        header('Content-type: text/xml; content="text/html; charset=UTF-8"');
//        echo $obj['contenido'];
    }

    public function cdrSunat($comprobante_id = '', $cliente_id = '') {
        $comprobante = $this->comprobantes_model->select($comprobante_id);
        $items = $this->items_model->select('', $comprobante_id);
        $cliente = $this->clientes_model->select($cliente_id);
        $empresa = $this->empresas_model->select($comprobante_id);

        $fichero = 'http://190.107.181.252/webServiceSunat/cdrSunat.php?comprobante=R' . $comprobante['empresa_ruc'] . '-' . $comprobante['tipo_documento_codigo'] . '-' . $comprobante['serie'] . '-' . $comprobante['numero'] . '.zip&empresa_id=' . $comprobante['empresa_id'];
        //echo $fichero;exit;
        $obj = json_decode(file_get_contents($fichero), true);

        header("Content-type: text/xml; charset=utf-8");
        echo $obj['contenido'];
    }

    public function buscador_cliente() {
        $abogado = $this->input->get('term');
        echo json_encode($this->clientes_model->selectAutocomplete($abogado, 'activo'));
    }

    public function buscador_empleado() {
        $abogado = $this->input->get('term');
        echo json_encode($this->empleados_model->selectAutocomplete($abogado));
    }
  
    public function buscador_item() {
        $item = $this->input->get('term');       
        echo json_encode($this->productos_model->selectAutocompleteprodSC($item));
    }

    public function buscador_itema() {
        $item = $this->input->get('term');       
        echo json_encode($this->productos_model->selectAutocompleteprodSCa($item));
    }

    public function buscador_itemC() {
        $item = $this->input->get('term');       
        echo json_encode($this->productos_model->selectAutocompleteprodC($item));
    }

     public function guardar_comprobante() {

        //DEVUELVO EL STOCK
        if($_POST['notap_id'] != ''){
            $this->notas_model->devolverStock($_POST['notap_id']);           
        }
        //CAMBIO DE ESTADO PROFORMA - 21/02/2021
        if($_POST['prof_id'] != ''){
            $this->db->where('prof_id',$_POST['prof_id']);
            $this->db->update('proformas',array('prof_procesoestado_id' => 2));//ESTADO ACEPTADO
        }

        /*validamos que haya ingresado el cliente*/
        if($_POST['cliente_id']=='')
        {
            sendJsonData(['status'=>STATUS_FAIL, 'msg'=>"Falta ingresar cliente"]);
            exit();
        }else if($_POST['cliente_id']!=0){
            $this->db->where('id',$_POST['cliente_id']);
            $data_cliente = $this->db->get('clientes')->row();
            if($data_cliente->tipo_cliente_id==1){
                if($_POST['tipo_documento']!=3 and $_POST['tipo_documento']!=9 and $_POST['tipo_documento']!=10 and $_POST['tipo_documento']!=13){
                    sendJsonData(['status'=>STATUS_FAIL, 'msg'=>"Inválido Tipo de documento con DNI"]);
                    exit();
                }
            }else{
                 if($_POST['tipo_documento']!=1 and $_POST['tipo_documento']!=7 and $_POST['tipo_documento']!=8){
                    sendJsonData(['status'=>STATUS_FAIL, 'msg'=>"Inválido Tipo de documento con RUC"]);
                    exit();
                 }
            }
        } else if($_POST['cliente_id'] == 'nApi'){
                if($_POST['tipo_documento']!=3 and $_POST['tipo_documento']!=9 and $_POST['tipo_documento']!=10 and $_POST['tipo_documento']!=13){
                    sendJsonData(['status'=>STATUS_FAIL, 'msg'=>"Inválido Tipo de documento con DNI"]);
                    exit();
                }            
        }else if($_POST['cliente_id'] == 'jApi') {                    
            if($_POST['tipo_documento']!=1 and $_POST['tipo_documento']!=7 and $_POST['tipo_documento']!=8){
                    sendJsonData(['status'=>STATUS_FAIL, 'msg'=>"Inválido Tipo de documento con RUC"]);
                    exit();
            }
        }

        $idproduc = $_POST['item_id'];
        $cantidad = $_POST['cantidad'];
        $descripcion = $_POST['descripcion'];
        $medida = $_POST['medida'];
        $importe = $_POST['importe'];
        $tipIGV = $_POST['tipo_igv'];


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
         
        $g = 0; 
        if($_POST['operacion']=='0200'){
            foreach($idproduc as $value){
                  if($tipIGV[$g]!=16){
                     sendJsonData(['status'=>STATUS_FAIL, 'msg'=>'Tipo de IGV del producto debe ser exportación']);
                     exit(); 
                  }
            }
            $g++;
        }        
        if($_POST['direccion']=='')
        {
            sendJsonData(['status'=>STATUS_FAIL, 'msg'=>'Ingrese dirección']);
            exit();            
        }

        //ALEXANDER FERNANDEZ VALIDACION PARA MONTO PAGO 14-10-2020
        $importe_pagoMonto =  $_POST['importe_pagoMonto'];            
        $m = 0;
        foreach ($importe_pagoMonto as $value) {
                if($value == '' || $value == 0){
                    $montoPago = false;
                    $msg = 'monto no registrato bien.';
                    break;
                } else{
                    $montoPago = true;
                }
            $m++;            
        }        
        if(!$montoPago)
        {
            sendJsonData(['status'=>STATUS_FAIL, 'msg'=>$msg]);
            exit();
        }    

        /////CONSULTA STOCK PRODUCTO ///////        
        if($_POST['tipo_documento'] != 7 and $_POST['tipo_documento'] != 9){
                $i = 0;
                foreach ($idproduc as $item) {
                   if($importe[$i] != 0 and $importe[$i] != ''){
                      if($idproduc[$i] != 0){                         
                        $dato_prod = $this->db->from('productos')
                                              ->where('prod_id',$idproduc[$i])
                                              ->get()
                                              ->row();
                                                
                        $prod_stock = $this->productos_model->getStockProductos($idproduc[$i],$dato_prod->prod_almacen_id);                        
                        if($dato_prod->prod_tipo == 1){
                             if($cantidad[$i]== 0 OR $cantidad[$i]>$prod_stock){
                                sendJsonData(['status'=>STATUS_FAIL, 'msg'=>'No hay stock disponible de '.$descripcion[$i]]);
                                exit();  
                              }   
                        }
                       } 
                    }else{
                         sendJsonData(['status'=>STATUS_FAIL, 'msg'=>'Falta precio unitario a un producto']);
                         exit();  
                    }
                    $i++;                   
                }   
         }                         
            
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

        //ACTUALIZACION DE PLACA PARA EL CLIENTE 17/08/2020
        //echo $_POST['cliente_id'];exit;
        $this->db->where('id', $_POST['cliente_id']);
        $this->db->update("clientes", array('placa' => $_POST['placa'] ));
    

        $minutos = new DateTime();
        $fecha_de_emision = new DateTime($_POST['fecha_de_emision']);
        $fecha_de_emision = $fecha_de_emision->format('Y-m-d')." ".$minutos->format('H:i:s');

        $fecha_de_vencimiento = new DateTime($_POST['fecha_de_vencimiento']);
        $fecha_de_vencimiento = $fecha_de_vencimiento->format('Y-m-d');
        $operacion_gratuita = isset($_POST['operacion_gratuita']) ? 1 : 0;
        $operacion_cancelada = isset($_POST['operacion_cancelada']) ? 1 : 0;
        
        $serie = (($_POST['tipo_documento'] == 1) || 
                ($_POST['tipo_documento'] == 3) || 
                ($_POST['tipo_documento'] == 7) || 
                ($_POST['tipo_documento'] == 9) || 
                ($_POST['tipo_documento'] == 10) || 
                ($_POST['tipo_documento'] == 8) ) ? strtoupper($_POST['serie']): $_POST['serie_antiguo'];        
        
        if($_POST['tipo_documento'] == $this->variables_diversas_model->factura_antigua){
            $tipo_documento = 1;
        }else{
            if($_POST['tipo_documento'] == $this->variables_diversas_model->boleta_antigua){
                $tipo_documento = 3;
            }else{
                $tipo_documento = $_POST['tipo_documento'];
            }            
        }
        $numero= $this->comprobantes_model->selecMaximoNumero2(1,$_POST['tipo_documento'],strtoupper($_POST['serie']))['numero'];
        $rsCliente = $this->db->from("clientes")
                              ->where("id", $_POST['cliente_id'])
                              ->get()
                              ->row();

        $configuracion = $this->db->from('comprobantes_ventas')->get()->row();
        $numero_pedido = ($configuracion->numero_pedido) ? $_POST['numero_pedido'] :''  ;
        $orden_compra = ($configuracion->orden_compra) ? $_POST['orden_compra'] : '' ;
        $guia_remision = ($configuracion->numero_guia) ? $_POST['guia_remision'] : ''  ;
        //$notas = ($configuracion->notas) ? $_POST['notas'] : '' ;
        $notas = (isset($_POST['notas']))?$_POST['notas']:'';
        $condicion_venta = ($configuracion->condicion_venta) ? $_POST['condicion_venta'] : ''  ;
        
        $impo = (($_POST['total_descuentos'] + $_POST['total_a_pagar'])/ 1.18 );
        //$total_desc = ($impo - ($_POST['total_gravada']));
        $total_desc = $_POST['descuento_global'];
            
        $comprobante = array(
            'cliente_id' => $_POST['cliente_id'],
            'direccion_cliente' => $_POST['direccion'],
            'transportista_id'=>$_POST['transportista'],
            'tipo_documento_id' => $tipo_documento,
            'serie' => $serie,
            'numero' =>$numero,
            'fecha_de_emision' => $fecha_de_emision,
            'moneda_id' => $_POST['moneda_id'],
            'fecha_de_vencimiento' => $fecha_de_vencimiento,
            'operacion_gratuita' => $operacion_gratuita,
            'operacion_cancelada' => $operacion_cancelada,
            'observaciones' => $_POST['observaciones'],
            'empresa_id' => 1,//VER
            'tipo_pago_id' => $_POST['tipo_pago'],//VER
            'total_otros_cargos' => 0.00,
            'total_detraccion'  => 0.00,
            'numero_tarjeta' =>$_POST['ntarjeta'],
            'descuento_global' => $total_desc,
            'total_exonerada' => $_POST['total_exonerada'],
            'total_inafecta' => $_POST['total_inafecta'],
            'total_gravada' => $_POST['total_gravada'],
            'total_igv' => $_POST['total_igv'],
            'total_icbper' => $_POST['total_icbper'],
            'total_a_pagar' => $_POST['total_a_pagar'],
            'cambio' => $_POST['cambio'],            
            'empleado_insert' => $this->session->userdata('empleado_id'),
            'fecha_insert' => date("Y-m-d H:i:s"),
            'numero_pedido' => $numero_pedido,
            'orden_compra'  => $orden_compra,
            'numero_guia_remision' => $_POST['numero_guia'],
            'notas' => $notas,
            'condicion_venta' => $condicion_venta,
            'empleado_select' => $this->session->userdata('empleado_id'),
            'venta_almacen_id' => $this->session->userdata('almacen_id'),
            'tipo_operacion' => $_POST['operacion']
        );
        if($_POST['fecha_de_vencimiento']!='')
        {
            $comprobante['fecha_de_vencimiento'] = $fecha_de_vencimiento;
        }

        $comprobante['incluye_igv'] = ($_POST['incluye_igv']!='')?1:0;

        if ($_POST['total_descuentos'] == '') {
            $comprobante = array_merge($comprobante, array('total_descuentos' => 0.00));
        }else{
            $comprobante = array_merge($comprobante, array('total_descuentos' => $_POST['total_descuentos']));
        }               
            
        if ($_POST['total_otros_cargos'] == '') {
            $comprobante = array_merge($comprobante, array('total_otros_cargos' => 0.00));
        }else{
            $comprobante = array_merge($comprobante, array('total_otros_cargos' => $_POST['total_otros_cargos']));
        }
        
        if ($_POST['total_gratuita'] == '') {
            $comprobante = array_merge($comprobante, array('total_gratuita' => 0.00));
        }else{
            $comprobante = array_merge($comprobante, array('total_gratuita' => $_POST['total_gratuita']));
        }

        if ($_POST['moneda_id'] > 1) {
            $comprobante = array_merge($comprobante, array('tipo_de_cambio' => $_POST['tipo_de_cambio']));
        }
                    
        /*if ($_POST['descuento_global'] == '') {
            $comprobante = array_merge($comprobante, array('descuento_global' => 0.00));
        }else{
            $comprobante = array_merge($comprobante, array('descuento_global' => $_POST['descuento_global']));
        }*/
            
        if ($_POST['tipo_documento'] <= 3) {
            if ($_POST['tipo_documento'] == 1) {// facturas
                $detraccion = isset($_POST['detraccion']) ? 1 : 0;
                $comprobante = array_merge($comprobante, array('detraccion' => $detraccion));
                if ($this->input->post('tipo_de_detraccion') != '')
                    $comprobante = array_merge($comprobante, array('elemento_adicional_id' => $this->input->post('tipo_de_detraccion')));
                if ($this->input->post('porcentaje_de_detraccion') != '')
                    $comprobante = array_merge($comprobante, array('porcentaje_de_detraccion' => $this->input->post('porcentaje_de_detraccion')));
                if ($this->input->post('total_detraccion') != '')
                    $montoTotalDetraccion = 0.00;

                if ($_POST['moneda_id'] > 1) {                    
                    $tipoCambio = $this->tipoCambioFecha($_POST['moneda_id'], $fecha_de_emision);
                    //$montoTotalDetraccion = $this->input->post('total_detraccion') * $tipoCambio;
                    $montoTotalDetraccion = 0.00;
                }
                $comprobante = array_merge($comprobante, array('total_detraccion' => $montoTotalDetraccion));
            }
        } else {
            if ($_POST['tipo_documento'] == 7 or $_POST['tipo_documento'] == 9) {
                if ($this->input->post('tipo_ncredito') != '') {
                    $tipoNota = explode('*', $this->input->post('tipo_ncredito'));
                    $comprobante = array_merge($comprobante, array('tipo_nota_id' => $tipoNota[0]));
                    $comprobante = array_merge($comprobante, array('tipo_nota_codigo' => $tipoNota[1]));
                }
            }
            if ($_POST['tipo_documento'] == 8 or $_POST['tipo_documento'] == 10) {
                if ($this->input->post('tipo_ndebito') != '') {
                    $tipoNota = explode('*', $this->input->post('tipo_ndebito'));
                    $comprobante = array_merge($comprobante, array('tipo_nota_id' => $tipoNota[0]));
                    $comprobante = array_merge($comprobante, array('tipo_nota_codigo' => $tipoNota[1]));
                }
            }
            if ($this->input->post('comp_adjunto') != '')
                $comprobante = array_merge($comprobante, array('com_adjunto_id' => $this->input->post('comp_adjunto')));
        }

        //nos fijamos si es un documento de anticipo
        if($_POST['anticipo'] == '1')
        {
            $comprobante['comprobante_anticipo'] = '1';
        }
        //print_r($comprobante);exit();
        $comprobante_id = $this->comprobantes_model->insertar($comprobante);

        if($_POST['numero_guia']!=''){
             $this->db->set('numero_factura',$serie.'-'.$numero);
             $this->db->where('CONCAT(guia_serie,"-",guia_numero)',$_POST['numero_guia']);
             $guia = $this->db->update('guias');

        }    

        $descripcion = $_POST['descripcion'];
        $medida      = $_POST['medida'];
        $cantidad    = $_POST['cantidad'];
        $tipo_igv_id  = $_POST['tipo_igv'];
        $importe      = $_POST['importe'];        
        $importeCosto = $_POST['importeCosto'];
        $subtotal = $_POST['subtotal'];
        $total    = $_POST['total'];
        $totalVenta = $_POST['totalVenta'];
        $totalCosto = $_POST['totalCosto'];
        $igv = $_POST['igv'];

        $descuentos = ($configuracion->descuento) ? $_POST['descuento'] : '0.00' ;        

        $idproduc = $_POST['item_id'];

        $i = 0;
        foreach ($idproduc as $item) {
            $result = $this->db->from('productos')
                               ->where('prod_id',$idproduc[$i])
                               ->get()
                               ->row();
                
            $dataDetalleInsert["comprobante_id"] = $comprobante_id;           
            //$dataDetalleInsert["descripcion"] = $this->quitarSaltoDeLinea($descripcion[$i]);  

            if($idproduc[$i]!=0){
                $dataDetalleInsert["descripcion"] = $result->prod_nombre; 
                $dataDetalleInsert["categoria_id"] = $result->prod_categoria_id;
                $dataDetalleInsert["unidad_id"] = $result->prod_medida_id;
            }else{
                $dataDetalleInsert["descripcion"] = strtoupper($descripcion[$i]);
                $dataDetalleInsert["categoria_id"] = 1;
                $dataDetalleInsert["unidad_id"] = $medida[$i];
            }
                                            
            $dataDetalleInsert["importe"] = $importe[$i]; //precio por unidad
            $dataDetalleInsert["importeCosto"] = $importeCosto[$i]; //precioCosto
            if($configuracion->pu_igv==1)
            {
                $dataDetalleInsert["precio_base"] = $dataDetalleInsert["importe"] / 1.18;
            }else{
                $dataDetalleInsert["precio_base"] =  $dataDetalleInsert["importe"];
            }
            $dataDetalleInsert["cantidad"] = $cantidad[$i];
            $dataDetalleInsert["tipo_igv_id"] = $tipo_igv_id[$i];
            
            $dataDetalleInsert["subtotal"] = $subtotal[$i]; //unidad*cantidad sin igv
            $dataDetalleInsert["igv"] = $igv[$i];
            $dataDetalleInsert["total"] = $total[$i];
            $dataDetalleInsert["totalVenta"] = $totalVenta[$i];
            $dataDetalleInsert["totalCosto"] = $totalCosto[$i];
            $dataDetalleInsert["descuento"] = $descuentos[$i];
            $dataDetalleInsert["producto_id"] = $idproduc[$i];                
            

            $this->db->insert("items", $dataDetalleInsert);
            if($result->prod_tipo==1){
                
                
                    if($_POST['tipo_documento'] != 7 and $_POST['tipo_documento'] != 9){

                         $stock = $this->productos_model->getStockProductos($idproduc[$i],$result->prod_almacen_id);
                         $nueva_cantidad = floatval($stock) - floatval($cantidad[$i]);

                         $concepto = 'Venta';
                         if($_POST['tipo_documento'] == 8 or $_POST['tipo_documento'] == 10){
                            $this->db->where('id',$this->input->post('tipo_ndebito'));
                            $concepto = $this->db->get('tipo_ndebitos')->row(); 
                            $concepto = $concepto->tipo_ndebito;
                         } 

                         $kardex = array(
                          'k_fecha' => date('Y-m-d'),
                          'k_almacen' => $result->prod_almacen_id,
                          'k_tipo' => 2,
                          'k_operacion_id' => $comprobante_id,
                          'k_serie' => $serie.'-'.$numero,
                          'k_concepto' => $concepto,     
                          'k_producto' => $idproduc[$i],
                          'k_scantidad' => $cantidad[$i],
                          'k_excantidad' => $nueva_cantidad                                           
                         );

                         $this->db->insert('kardex', $kardex);
                    } else{                        

                         $stock = $this->productos_model->getStockProductos($idproduc[$i],$result->prod_almacen_id);
                         $nueva_cantidad = floatval($stock) + floatval($cantidad[$i]);

                         $this->db->where('id',$this->input->post('tipo_ncredito'));
                         $concepto = $this->db->get('tipo_ncreditos')->row();

                         $kardex = array(
                          'k_fecha' => date('Y-m-d'),
                          'k_almacen' => $result->prod_almacen_id,
                          'k_tipo' => 2,
                          'k_operacion_id' => $comprobante_id,
                          'k_serie' => $serie.'-'.$numero,
                          'k_concepto' => $concepto->tipo_ncredito,     
                          'k_producto' => $idproduc[$i],
                          'k_ecantidad' => $cantidad[$i],
                          'k_excantidad' => $nueva_cantidad                                           
                         );

                         $this->db->insert('kardex', $kardex);  
                    }                                        
            } 
            //$dataDetalleInsert["descuento"] = ($descuento[$i]);     
            $i++;
        }
        
        //verificamos si el comprobante tiene anticipos
        if($comprobante_id != '')
        {
            $anticipos = $this->session->userdata("comprobantes_anticipos");
            if(count($anticipos) > 0)
            {
                //registramos anticipos
                foreach ($anticipos as $key => $value)
                {
                    $dataInsert = [
                                    'comprobante_id' => $comprobante_id,
                                    'anticipo_id'    => $value->id
                                  ];
                    $this->db->insert('comprobante_anticipo', $dataInsert);  
                    
                    //cambiamos a etado usado a cada uno de los anticipos agregados
                    $dataUpdate = [
                                    'comprobante_anticipo_usado' => 1
                                  ];  
                    $this->db->where('id', $value->id);
                    $this->db->update('comprobantes', $dataUpdate);                       
                }

                $this->session->unset_userdata("comprobantes_anticipos");

            }            
        }

        //INSERTAR COMPROBANTES PAGOS 10-10-2020
        $tipo_pagoMonto = $_POST['tipo_pagoMonto'];
        $importe_pagoMonto  = $_POST['importe_pagoMonto'];
        $observacion_pagoMonto = $_POST['observacion_pagoMonto'];   
        $pagoMonto = array();
        $i=0;
        foreach ($tipo_pagoMonto as $value) {
            
            $pagoMonto['comprobante_id'] = $comprobante_id;
            $pagoMonto['tipo_pago_id'] = $tipo_pagoMonto[$i];
            $pagoMonto['monto'] = $importe_pagoMonto[$i];
            $pagoMonto['observaciones'] = $observacion_pagoMonto[$i];

            $this->db->insert('comprobante_pagos',$pagoMonto);  
            $i++;               
        }

        $config = $this->db->get('comprobantes_ventas')->row();
        if($config->facturador_auto==1){
            $this->txt_directo(0,$comprobante_id);
        } 
        if($config->ticket_auto==1){
            $this->data_impresion_pos_ticket($comprobante_id);
        } 
        sendJsonData(['status'=>STATUS_OK,'cpe_id'=>$comprobante_id]);
        exit();         
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
                            'ejm_almacen_id'    => $rsAlmacen->prod_almacen_id,
                            'ejm_estado'        => ST_PRODUCTO_DISPONIBLE
                           
                           ];

        $this->db->insert("ejemplar", $insertEjemplar);   
      }
                                          
    }

    // contar registro en ejemplar
    public function existproduct($idproducto) {               
        $resultado = $this->db->where('ejm_producto_id',$idproducto)
                                ->where('ejm_estado',1)
                                ->from('ejemplar')->count_all_results();
        return $resultado;        
    }
    // actualizar vendidos en ejemplar
    public function UpdateEstadoVendido($idproducto,$cantidad) {
        
        $resultados = $this->db->from('ejemplar')
                               ->where('ejm_producto_id',$idproducto)
                               ->where('ejm_estado',ST_PRODUCTO_DISPONIBLE)
                               ->where('ejm_almacen_id',$this->session->userdata('almacen_id'))
                               ->limit($cantidad)
                               ->get()
                               ->result()
                               ;
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

    public function updateEstadoComprobante($comprobante_id = '', $operacion_cancelada = '') {
        $array = array();
        if (!empty($operacion_cancelada) || $operacion_cancelada != '') {
            //echo $operacion_cancelada;exit;
            $array = array_merge($array, array('operacion_cancelada' => $operacion_cancelada));
        }
        $this->comprobantes_model->modificar($array, $comprobante_id);
        redirect(base_url() . "index.php/comprobantes/documentos");
    }

    public function modificar_comprobante() {  

        $comprobante_id = $this->uri->segment(3);

        /*validamos que haya ingresado el cliente*/
        if($_POST['cliente_id']=='')
        {
            sendJsonData(['status'=>STATUS_FAIL, 'msg'=>"Falta ingresar cliente"]);
            exit();
        }else if($_POST['cliente_id']!=0){
            $this->db->where('id',$_POST['cliente_id']);
            $data_cliente = $this->db->get('clientes')->row();
            if($data_cliente->tipo_cliente_id==1){
                if($_POST['tipo_documento']!=3 and $_POST['tipo_documento']!=9 and $_POST['tipo_documento']!=10){
                    sendJsonData(['status'=>STATUS_FAIL, 'msg'=>"Inválido Tipo de documento con DNI"]);
                    exit();
                }
            }else{
                 if($_POST['tipo_documento']!=1 and $_POST['tipo_documento']!=7 and $_POST['tipo_documento']!=8){
                    sendJsonData(['status'=>STATUS_FAIL, 'msg'=>"Inválido Tipo de documento con RUC"]);
                    exit();
                 }
            }
        }else{
            if($_POST['tipo_documento']!=1 and $_POST['tipo_documento']!=7 and $_POST['tipo_documento']!=8){
                    sendJsonData(['status'=>STATUS_FAIL, 'msg'=>"Inválido Tipo de documento con RUC"]);
                    exit();
            }
        }



        $idproduc = $_POST['item_id'];
        $cantidad = $_POST['cantidad'];
        $descripcion = $_POST['descripcion'];
        $medida = $_POST['medida'];
        $importe = $_POST['importe'];
        $tipIGV = $_POST['tipo_igv'];


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
         
        $g = 0; 
        if($_POST['operacion']=='0200'){
            foreach($idproduc as $value){
                  if($tipIGV[$g]!=16){
                     sendJsonData(['status'=>STATUS_FAIL, 'msg'=>'Tipo de IGV del producto debe ser exportación']);
                     exit(); 
                  }
            }
            $g++;
        }
       
        if($_POST['direccion']=='')
        {
            sendJsonData(['status'=>STATUS_FAIL, 'msg'=>'Ingrese dirección']);
            exit();            
        }

        /////CONSULTA STOCK PRODUCTO PARA MODIFICAR ////////////////// 
         if($_POST['tipo_documento']!=7 and $_POST['tipo_documento']!=9){
            $i = 0;
            foreach ($idproduc as $item) {
                
                $this->db->where('comprobante_id',$comprobante_id);
                $this->db->where('producto_id',$idproduc[$i]);
                $dato_producto = $this->db->get('items')->row();

                $this->db->where('prod_id',$idproduc[$i]);
                $dato_prod = $this->db->get('productos')->row();

                $prod_stock = $this->productos_model->getStockProductos($idproduc[$i],$this->session->userdata("almacen_id"));

              if($idproduc[$i]!=0){ 
                if($dato_prod->prod_tipo==1){
                    if($cantidad[$i]==0 OR $cantidad[$i]>($prod_stock+$dato_producto->cantidad)){
                        sendJsonData(['status'=>STATUS_FAIL, 'msg'=>'No hay stock disponible de '.$descripcion[$i]]);
                       exit();  
                    }                   
                }
              }
                $i++;                   
            }   
         }                  
        ///////////////////////////////////////////////
         /////REGISTRA CLIENTE SUNAT
         if($_POST['cliente_id']==0){
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
         }

        //ACTUALIZACION DE PLACA PARA EL CLIENTE 17/08/2020
        $this->db->where('id', $_POST['cliente_id']);
        $this->db->update("clientes", array('placa' => $_POST['placa'] ));

        $minutos = new DateTime();             
        $fecha_de_emision = new DateTime($_POST['fecha_de_emision']);
        $fecha_de_emision = $fecha_de_emision->format('Y-m-d')." ".$minutos->format('H:i:s');

        $fecha_de_vencimiento = new DateTime($_POST['fecha_de_vencimiento']);
        $fecha_de_vencimiento = $fecha_de_vencimiento->format('Y-m-d');

        $operacion_gratuita = isset($_POST['operacion_gratuita']) ? 1 : 0;
        $operacion_cancelada = isset($_POST['operacion_cancelada']) ? 1 : 0;
        $rsCliente = $this->db->from("clientes")
                              ->where("id", $_POST['cliente_id'])
                              ->get()
                              ->row();

        $configuracion = $this->db->from('comprobantes_ventas')->get()->row();
        
        $numero_pedido = ($configuracion->numero_pedido) ? $_POST['numero_pedido'] :''  ;
        $orden_compra = ($configuracion->orden_compra) ? $_POST['orden_compra'] : '' ;
        $guia_remision = ($configuracion->numero_guia) ? $_POST['guia_remision'] : ''  ;
        $notas = (isset($_POST['notas']))?$_POST['notas']:'';
        $condicion_venta = ($configuracion->condicion_venta) ? $_POST['condicion_venta'] : ''  ;

        $impo = (($_POST['total_descuentos'] + $_POST['total_a_pagar'])/ 1.18 );
        //$total_desc = ($impo - ($_POST['total_gravada']));
        $total_desc = $_POST['descuento_global'];

        $comprobante = array(
            'cliente_id' => $_POST['cliente_id'],
            'direccion_cliente' => $_POST['direccion'],
            'transportista_id'=> $_POST['transportista'],
            'tipo_documento_id' => $_POST['tipo_documento'],
            'serie' => strtoupper($_POST['serie']),
            'numero' => $_POST['numero'],
            'fecha_de_emision' => $fecha_de_emision,
            'moneda_id' => $_POST['moneda_id'],
            'tipo_de_cambio' => $_POST['tipo_de_cambio'],
            'fecha_de_vencimiento' => $fecha_de_vencimiento,
            'operacion_gratuita' => $operacion_gratuita,
            'operacion_cancelada' => $operacion_cancelada,
            'observaciones' => $_POST['observaciones'],
            'empresa_id' => 1,
            'tipo_pago_id' => $_POST['tipo_pago'],//VER
            'total_otros_cargos' =>0.00,
            'total_detraccion'  =>0.00,
            'numero_tarjeta' =>$_POST['ntarjeta'],
            'descuento_global' => $total_desc,
            'total_exonerada' => $_POST['total_exonerada'],
            'total_inafecta' => $_POST['total_inafecta'],
            'total_gravada' => $_POST['total_gravada'],
            'total_igv' => $_POST['total_igv'],
            'total_gratuita' => $_POST['total_gratuita'],
            'total_otros_cargos' => $_POST['total_otros_cargos'],
            'total_descuentos' => $_POST['total_descuentos'],
            'total_a_pagar' => $_POST['total_a_pagar'],
            'cambio' => $_POST['cambio'],
            'empleado_insert' => $this->session->userdata('empleado_id'),
            'fecha_insert' => date("Y-m-d H:i:s"),
            'numero_pedido' => $numero_pedido,
            'orden_compra'  => $orden_compra,
            //'numero_guia_remision' => $guia_remision,
            'notas' => $notas,
            'condicion_venta' => $condicion_venta,
            'numero_guia_remision' => $_POST['numero_guia'],
             'tipo_operacion' => $_POST['operacion']       

        );

        if($_POST['fecha_de_vencimiento']!='')
        {
            $comprobante['fecha_de_vencimiento'] = $fecha_de_vencimiento;
        }
        $comprobante['incluye_igv'] = ($_POST['incluye_igv']!='')?1:0;
        //$tipoCambio = ($_POST['moneda_id'] != 1) ? $this->tipo_cambio_model->selectJson($_POST['moneda_id']) : 1;
        //echo $this->tipoCambioFechaJson($_POST['moneda_id'], $fecha_de_emision)."<br>";
        
        $tipoCambio = ($_POST['moneda_id'] != 1) ? $this->tipoCambioFecha($_POST['moneda_id'], $fecha_de_emision) : 1;
        $total_pagar = ($_POST['moneda_id'] == 1) ? $_POST['total_a_pagar'] : $_POST['total_a_pagar'] * $tipoCambio;        

        //tipo_documentos:  1 factura; 3 boleta; 7 nota de credito; 8 nota de debito
        if ($_POST['tipo_documento'] <= 3) {
            if ($_POST['tipo_documento'] == 1) {
                $detraccion = isset($_POST['detraccion']) ? 1 : 0;
                $comprobante = array_merge($comprobante, array('detraccion' => $detraccion));
                if ($this->input->post('tipo_de_detraccion') != '')
                    $comprobante = array_merge($comprobante, array('elemento_adicional_id' => $this->input->post('tipo_de_detraccion')));
                if ($this->input->post('porcentaje_de_detraccion') != '')
                    $comprobante = array_merge($comprobante, array('porcentaje_de_detraccion' => $this->input->post('porcentaje_de_detraccion')));
                          
                //$this->variables_diversas_model->detraccion_valor == 700 al 17-01-2018
                // el total a pagar ya esta en soles....                
                if($total_pagar >= $this->variables_diversas_model->detraccion_valor){
                    $montoTotalDetraccion = $total_pagar * $this->variables_diversas_model->porcentaje_detraccion;
                }
                $comprobante = array_merge($comprobante, array('total_detraccion' => $montoTotalDetraccion));                
                
            }
            
            if ($_POST['tipo_documento'] == 3) {
                $comprobante = array_merge($comprobante, array('detraccion' => 0.00));
                $comprobante = array_merge($comprobante, array('elemento_adicional_id' => NULL));
                $comprobante = array_merge($comprobante, array('porcentaje_de_detraccion' => 0.00));
                $comprobante = array_merge($comprobante, array('total_detraccion' => 0.00));
            }
            $comprobante = array_merge($comprobante, array('tipo_nota_id' => NULL));
            $comprobante = array_merge($comprobante, array('com_adjunto_id' => NULL));
        } else {
            if ($_POST['tipo_documento'] == 7 or $_POST['tipo_documento'] == 9) {
                if ($this->input->post('tipo_ncredito') != '') {
                    $tipoNota = explode('*', $this->input->post('tipo_ncredito'));
                    $comprobante = array_merge($comprobante, array('tipo_nota_id' => $tipoNota[0]));
                    $comprobante = array_merge($comprobante, array('tipo_nota_codigo' => $tipoNota[1]));
                }
            }
            if ($_POST['tipo_documento'] == 8 or $_POST['tipo_documento'] == 10) {
                if ($this->input->post('tipo_ndebito') != '') {
                    $tipoNota = explode('*', $this->input->post('tipo_ndebito'));
                    $comprobante = array_merge($comprobante, array('tipo_nota_id' => $tipoNota[0]));
                    $comprobante = array_merge($comprobante, array('tipo_nota_codigo' => $tipoNota[1]));
                }
            }
            if ($this->input->post('comp_adjunto') != '')
                $comprobante = array_merge($comprobante, array('com_adjunto_id' => $this->input->post('comp_adjunto')));

            $comprobante = array_merge($comprobante, array('detraccion' => 0.00));
            $comprobante = array_merge($comprobante, array('elemento_adicional_id' => NULL));
            $comprobante = array_merge($comprobante, array('porcentaje_de_detraccion' => 0.00));
            $comprobante = array_merge($comprobante, array('total_detraccion' => 0.00));
        }
        //nos fijamos si es un documento de anticipo
        if($_POST['anticipo'] == '1')
        {
            $comprobante['comprobante_anticipo'] = '1';
        }

        
        $this->comprobantes_model->modificar($comprobante, $comprobante_id);


        
        $descripcion = $_POST['descripcion'];
        $cantidad = $_POST['cantidad'];
        $tipo_igv_id = $_POST['tipo_igv'];
        $importe = $_POST['importe'];
        $importeCosto = $_POST['importeCosto'];
        $subtotal = $_POST['subtotal'];
        $total = $_POST['total'];
        $totalCosto = $_POST['totalCosto'];
        $totalVenta = $_POST['totalVenta'];
        $igv = $_POST['igv'];
        $medida = $_POST['medida'];
        //$descuento = $_POST['descuento'];
        $desc = ($configuracion->descuento) ? $_POST['descuento'] : '0.00' ; 
        $idproduc = $_POST['item_id'];
       

        /*primero liberamos los prodcutos con sus respectivos ejemplares*/        
        $rsDetalles = $this->db->from("items")
                               ->where("comprobante_id", $comprobante_id)
                               ->get()
                               ->result();
        
        $i = 0;       
        foreach($rsDetalles as $item) {
           
         $result = $this->db->from('productos')
                               ->where('prod_id',$item->producto_id)
                               ->get()
                               ->row();      
                if($result->prod_tipo==1){
            
                    if($_POST['tipo_documento']!=7 and $_POST['tipo_documento']!=9){
                        //$this->UpdateEstadoVendido($idproduc[$i],$cantidad[$i]);

                         $stock = $this->productos_model->getStockProductos($item->producto_id,$this->session->userdata("almacen_id"));
                         $nueva_cantidad = floatval($stock)+floatval($item->cantidad);

                         $kardex = array(
                          'k_fecha' => date('Y-m-d'),
                          'k_almacen' => $this->session->userdata("almacen_id"),
                          'k_tipo' => 2,
                          'k_operacion_id' => $comprobante_id,
                          'k_serie' => $_POST['serie'].'-'.$_POST['numero'],
                          'k_concepto' => 'RESTAURAR',     
                          'k_producto' => $item->producto_id,
                          'k_ecantidad' => $item->cantidad,
                          'k_excantidad' => $nueva_cantidad,
                                           
                         );

                         $this->db->insert('kardex', $kardex);
                    } else{
                        //$this->UpdateEstadoDisponible($idproduc[$i], $cantidad[$i]);  

                         $stock = $this->productos_model->getStockProductos($item->producto_id,$this->session->userdata("almacen_id"));
                         $nueva_cantidad = floatval($stock)-floatval($item->cantidad);

                    
                         $kardex = array(
                          'k_fecha' => date('Y-m-d'),
                          'k_almacen' => $this->session->userdata("almacen_id"),
                          'k_tipo' => 2,
                          'k_operacion_id' => $comprobante_id,
                          'k_serie' => $_POST['serie'].'-'.$_POST['numero'],
                          'k_concepto' => 'RESTAURAR',     
                          'k_producto' => $item->producto_id,
                          'k_scantidad' => $item->cantidad,
                          'k_excantidad' => $nueva_cantidad,
                                           
                         );

                         $this->db->insert('kardex', $kardex);  
                    }                                
            }          
                                  
        } 
        /*eliminamos los detalles y volvemos a ingresar*/
        $this->db->where("comprobante_id", $comprobante_id);
        $this->db->delete("items");


        $i = 0;
        foreach ($idproduc as $item) {
            $result = $this->db->from('productos')
                               ->where('prod_id',$idproduc[$i])
                               ->get()
                               ->row();

            $precioBaseItem = $total[$i];
            $precioTotalItem =  ($total[$i] * 1.18);
            $igvItem = ($precioTotalItem - $precioBaseItem);
            $dataDetalleInsert["comprobante_id"] = $comprobante_id;
           // $dataDetalleInsert["tipo_item_id"] = $result->prod_tipo_item_id;
           // $dataDetalleInsert["descripcion"] = $this->quitarSaltoDeLinea($descripcion[$i]);

            if($idproduc[$i]!=0){
                $dataDetalleInsert["descripcion"] = $result->prod_nombre; 
                $dataDetalleInsert["categoria_id"] = $result->prod_categoria_id;
                $dataDetalleInsert["unidad_id"] = $result->prod_medida_id;
            }else{
                $dataDetalleInsert["descripcion"] = strtoupper($descripcion[$i]);
                $dataDetalleInsert["categoria_id"] = 1;
                $dataDetalleInsert["unidad_id"] = $medida[$i];
            }
            
            $dataDetalleInsert["importe"] = $importe[$i]; //precio por unidad
            $dataDetalleInsert["importeCosto"] = $importeCosto[$i]; //precio por unidad
            /*if($configuracion->pu_igv==1)
            {
                $dataDetalleInsert["precio_base"] = $dataDetalleInsert["importe"] / 1.18;
            }else{*/
            $dataDetalleInsert["precio_base"] =  $dataDetalleInsert["importe"];   
            /*}*/
            $dataDetalleInsert["cantidad"] = $cantidad[$i];
            $dataDetalleInsert["tipo_igv_id"] = $tipo_igv_id[$i];
            $dataDetalleInsert["subtotal"] = $total[$i]-$igv[$i]; //unidad*cantidad sin igv
            $dataDetalleInsert["igv"] = $igv[$i];
            $dataDetalleInsert["total"] = $total[$i];
            $dataDetalleInsert["totalVenta"] = $totalVenta[$i];
            $dataDetalleInsert["totalCosto"] = $totalCosto[$i];
            $dataDetalleInsert["descuento"] = $desc[$i];
            $dataDetalleInsert["producto_id"] = $idproduc[$i];
           
            //$this->UpdateEstadoVendido($idproduc[$i],$cantidad[$i]);
            if($result->prod_tipo==1){
                
                
                    if($_POST['tipo_documento']!=7 and $_POST['tipo_documento']!=9){
                        //$this->UpdateEstadoVendido($idproduc[$i],$cantidad[$i]);
                         $stock = $this->productos_model->getStockProductos($idproduc[$i],$this->session->userdata("almacen_id"));
                         $nueva_cantidad = floatval($stock)-floatval($cantidad[$i]);

                
                         $kardex = array(
                          'k_fecha' => date('Y-m-d'),
                          'k_almacen' => $this->session->userdata("almacen_id"),
                          'k_tipo' => 2,
                          'k_operacion_id' => $comprobante_id,
                          'k_serie' => $_POST['serie'].'-'.$_POST['numero'],
                          'k_concepto' => 'MODIFICACIÓN DE DOCUMENTO',     
                          'k_producto' => $idproduc[$i],
                          'k_scantidad' => $cantidad[$i],
                          'k_excantidad' => $nueva_cantidad,
                                           
                         );
                         $this->db->insert('kardex', $kardex);
                    } else{
                        //$this->UpdateEstadoDisponible($idproduc[$i], $cantidad[$i]);  

                         $stock = $this->productos_model->getStockProductos($idproduc[$i],$this->session->userdata("almacen_id"));
                         $nueva_cantidad = floatval($stock)+floatval($cantidad[$i]);
                    
                         $kardex = array(
                          'k_fecha' => date('Y-m-d'),
                          'k_almacen' => $this->session->userdata("almacen_id"),
                          'k_tipo' => 2,
                          'k_operacion_id' => $comprobante_id,
                          'k_serie' => $_POST['serie'].'-'.$_POST['numero'],
                          'k_concepto' => 'MODIFICACION DOCUMENTO',     
                          'k_producto' => $idproduc[$i],
                          'k_ecantidad' => $cantidad[$i],
                          'k_excantidad' => $nueva_cantidad,
                                           
                         );
                         $this->db->insert('kardex', $kardex);  
                    }                                       
            }         
            ///// GUARDAR NUEVO ITEM /////
            $this->db->insert("items", $dataDetalleInsert);
            $i++;
        }
        //verificamos si el comprobante tiene anticipos
        if($comprobante_id != '')
        {
            $this->db->where("comprobante_id",$comprobante_id);
            $this->db->from("comprobante_anticipo");
            $query = $this->db->get();
            $rsAnticipos = $query->result();
            foreach($rsAnticipos as $item)
            {
                /*quitamos el estado de usado*/
                $dataUpdate = [
                                'comprobante_anticipo_usado' => 0
                              ];                    
                $this->db->where("id", $item->anticipo_id);
                $this->db->update("comprobantes",$dataUpdate);
                
            }
            /*eliminamos de la tabla comprobante anticipo*/
            $this->db->where("comprobante_id", $comprobante_id);
            $this->db->delete("comprobante_anticipo");   
                     
            $anticipos = $this->session->userdata("comprobantes_anticipos");
            if(count($anticipos) > 0)
            {
                /*quitamos los anticipos que se han agregado antes*/
                //registramos anticipos
                foreach ($anticipos as $key => $value)
                {
                    $dataInsert = [
                                    'comprobante_id' => $comprobante_id,
                                    'anticipo_id'    => $value->id
                                  ];
                    $this->db->insert('comprobante_anticipo', $dataInsert);  
                    
                    //cambiamos a etado usado a cada uno de los anticipos agregados
                    $dataUpdate = [
                                    'comprobante_anticipo_usado' => 1
                                  ];  
                    $this->db->where('id', $value->id);
                    $this->db->update('comprobantes', $dataUpdate);                       
                }

                $this->session->unset_userdata("comprobantes_anticipos");

            }            
        }    
        sendJsonData(['status'=>STATUS_OK]);
        exit();                   
    }

    public function txt($envio = 0, $comprobante_id = '') {
        
        $data = $this->clientes_model->select();
        $comprobante = $this->comprobantes_model->select($comprobante_id);
        $this->db->where("id",$comprobante_id);
        $this->db->where("eliminado",0);
        $query = $this->db->get("comprobantes");
        $comprobante1 = $query->row();
        //print_r($comprobante1->total_descuentos);exit();
        $items = $this->items_model->select('', $comprobante_id);
        $detraccion = $this->elemento_adicionales_model->select('', '', 'activo');

        $configuracion = $this->db->from('comprobantes_ventas')->get()->row();
            

        /*print_r($comprobante);exit();*/
        //TIPO EMPRESA
        $ruta = '';
        if ($comprobante['empresa_id'] == 1) {
            $ruta = 'sunat_archivos/sfs/DATA/';
        }
        if ($comprobante['empresa_id'] == 2) {
            $ruta = 'neple/sunat_archivos/sfs/DATA/';
        }
        $rutaArchivos = DISCO.':\xampp\htdocs'.CARPETA."/".SFS."/sunat_archivos/sfs/DATA/";

        if ($envio == 0) {
            if ($comprobante['tipo_documento_id'] < 4) {
                // FACTURA , BOLETA                
                  
                /*nombre archivo*/
                $fechaHoraEmision = new DateTime($comprobante['fecha_sunat']);
                $fechaVencimiento = new DateTime($comprobante['fecha_de_vencimiento']);
                $sql = $rutaArchivos . $comprobante['empresa_ruc'] . '-' . $comprobante['tipo_documento_codigo'] . '-' . $comprobante['serie'] . '-' . $comprobante['numero'] . '.CAB';
                $f = fopen($sql, 'w');

                /*cuerpo documento cabecera*/ 
                $linea = $comprobante1->tipo_operacion."|";//tipo operacion
                $linea .= "{$fechaHoraEmision->format('Y-m-d')}|";//fecha emision
                $linea .= "{$fechaHoraEmision->format('H:i:s')}|";//hora emision
                $linea .= "{$fechaVencimiento->format('Y-m-d')}|";//:fecha vencimiento
                $linea .= "0000|";//codigo domicilio fiscal
                $linea .= "{$comprobante['tipo_cliente_codigo']}|";//tipo de documento de identidad
                $linea .= trim("{$comprobante['cliente_ruc']}")."|";//:numero de documento identidad
                $linea .= "{$comprobante['cli_razon_social']}"." {$comprobante['cli_nombres']}|";//apellidos y nombres o razon social
                $linea .= "{$comprobante['abrstandar']}|";//:tipo de moneda
                $linea .= "{$comprobante['total_igv']}|";//:sumatoria tributos
                $linea .= "{$comprobante['total_gravada']}|";//:total valor venta
                $linea .= round($comprobante['total_igv']+($comprobante['total_gravada']),2)."|";//total precio venta
                $linea .= $comprobante1->total_descuentos."|";//total descuento
                $linea .= ($comprobante['total_otros_cargos'] + $comprobante['total_icbper'])."|";//sumatoria otros cargos
                /*buscamos si el comprobante a enviar tiene anticipos*/
                $this->db->from("comprobante_anticipo");
                $this->db->join("comprobantes", "comprobante_anticipo.comprobante_id=comprobantes.id");
                $this->db->where("comprobante_id" , $comprobante_id);
                $query = $this->db->get();
                $anticipos = $query->result(); 
                if(count($anticipos))
                {
                    $totalAnticipos = 0;
                    foreach($anticipos as $anticipo)
                    {
                        $totalAnticipo += $anticipo->total_a_pagar;
                    }
                    $linea .= "{$totalAnticipo}|";
                }else{
                    $linea .= "0|";//total anticipo                  
                }               
                $linea .= "{$comprobante['total_a_pagar']}|";//importe total venta
                $linea .= "2.1|";//version UBL
                $linea .= "2.0|\r\n";//customization

                fwrite($f, $linea);
                fclose($f);
                
                $rut = $rutaArchivos . $comprobante['empresa_ruc'] . '-' . $comprobante['tipo_documento_codigo'] . '-' . $comprobante['serie'] . '-' . $comprobante['numero'] . '.DET';
                $f = fopen($rut, 'w');

                foreach ($items as $value) {
                    $result = $this->db->from('productos prod')
                                       ->join('medida med',"prod.prod_medida_id=med.medida_id")
                                       ->where('prod_id',$value['producto_id'])
                                       ->get()
                                       ->row();

                   // $precioBaseUnidad = ($value['total']-$value['igv'])/$value['cantidad'];//precio unitario sin igv
                    if($comprobante1->comprobante_anticipo == '1')
                    {
                        $precioBaseUnidad = (($value['subtotal']/$value['cantidad'])/1.18);
                    }else{
                        $precioBaseUnidad = ($value['subtotal']/$value['cantidad']);
                    }
                    
                    $precioConIgv = $precioBaseUnidad*1.18;
                    $igvUnitario = $precioConIgv-$precioBaseUnidad ;

                   // $igvPorUnidad = $precioBaseUnidad;                    
                        $buscar=array(chr(13).chr(10), "\r\n", "\n", "\r");
                        $reemplazar=array("", "", "", "");
                                       
                    $descripction = str_ireplace($buscar,$reemplazar,$this->sanear_string(utf8_decode($value['descripcion'])));
                   
                     $linea = "{$value['unidad']}|";//Código de unidad de medida por ítem
                    $linea .= "{$value['cantidad']}|";//Cantidad de unidades por ítem

                    if($value['producto_id']!=0){
                        $linea .= "{$result->prod_codigo}|";//Código de producto
                    }else{
                        $linea .= "00000|";//Código de producto
                    }

                    if($result->prod_codigo_sunat!=''){
                        $linea .= "{$result->prod_codigo_sunat}|";//Código de producto
                    }else{
                        $linea .= "-|";//Codigo producto SUNAT
                    }
                    
                    $linea .= str_replace("&", "Y", trim(utf8_decode($descripction)))."|";//Descripción detallada del servicio prestado, bien vendido o cedido en uso, indicando las características.

                    if(intval($value['tipo_igv_codigo']) >= 20)//gratuitas la base es 0
                    {
                       $linea .= round($value['importe'], 2)."|";//Valor Unitario (cac:InvoiceLine/cac:Price/
                    }else{
                        if ($configuracion->pu_igv==1){
                            $linea .= round($value['importe']/1.18, 2)."|";//Valor Unitario (cac:InvoiceLine/cac:Price/cbc:PriceAmount)
                        }else{
                            $linea .= round($value['importe'], 2)."|";//Valor Unitario (cac:InvoiceLine/cac:Price/cbc:PriceAmount)
                        }
                    }

                    //$linea .= "{$value['igv']}|";//Sumatoria Tributos por item

                    if(intval($value['tipo_igv_codigo']) >= 20)//gratuitas la base es 0
                    {
                        $linea .= "0|";//Tributo: Base Imponible IGV por Item
                    }else{
                        if ($configuracion->pu_igv==1){
                            $linea .= round($value['total']-($value['total']/1.18),2)."|";//Sumatoria Tributos por item
                        }else{
                            $linea .= round($value['total']*0.18,2)."|";//Sumatoria Tributos por item
                        }
                        
                    }
					
                    $linea .= $this->tipoCodigoDeTributo($value['tipo_igv_codigo'])['codigo']."|";//Tributo: Códigos de tipos de tributos IGV(1000 - 1016 - 9995 - 9996 - 9997 - 9998)
                    //$linea .= "{$value['igv']}|";//Tributo: Monto de IGV por ítem

					if(intval($value['tipo_igv_codigo']) >= 20)//gratuitas la base es 0
                    {
                        $linea .= "0|";//Tributo: Base Imponible IGV por Item
                    }else{
                        if ($configuracion->pu_igv==1){
                            $linea .= round($value['total']-($value['total']/1.18),2)."|";//Sumatoria Tributos por item
                        }else{
                            $linea .= round($value['total']*0.18,2)."|";//Sumatoria Tributos por item
                        }
                        
                    }

                  
                    if(intval($value['tipo_igv_codigo']) >= 20)//gratuitas la base es 0
                    {
                       $linea .= round($value['total'],2)."|";//Sumatoria Tributos por item
                    }else{
                        if ($configuracion->pu_igv==1){
                            $linea .= round(($value['total']/1.18),2)."|";//Sumatoria Tributos por item
                        }else{
                            $linea .= round($value['total'],2)."|";//Sumatoria Tributos por item
                        }
                    }
                    
                    $linea .= $this->tipoCodigoDeTributo($value['tipo_igv_codigo'])['nombre']."|";//Tributo: Nombre de tributo por item
                    //$linea .= $this->tipoCodigoDeTributo($value['tipo_igv_codigo'])."|";//Tributo: Código de tipo de tributo por Item
                    $linea .= $this->tipoCodigoDeTributo($value['tipo_igv_codigo'])['codigoInternacional']."|";//Tributo: Código de tipo de tributo por Item
                    $linea .= "{$value['tipo_igv_codigo']}|";//Tributo: Afectación al IGV por ítem
                    $linea .= "18|";//Tributo: Porcentaje de IGV
                    /*Tributo ISC (2000)*/
                    $linea .= "-|";//Tributo ISC: Códigos de tipos de tributos ISC
                    $linea .= "|";//Tributo ISC: Monto de ISC por ítem
                    $linea .= "|";//Tributo ISC: Base Imponible ISC por Item
                    $linea .= "|";//Tributo ISC: Nombre de tributo por item
                    $linea .= "|";//Tributo ISC: Código de tipo de tributo por Item
                    $linea .= "|";//Tributo ISC: Tipo de sistema ISC
                    $linea .= "|";//Tributo ISC: Porcentaje de ISC
                    /*Tributo Otro 9999*/
                    $linea .= "-|";//Tributo Otro: Códigos de tipos de tributos OTRO
                    $linea .= "|";//Tributo Otro: Monto de tributo OTRO por iItem
                    $linea .= "|";//Tributo Otro: Base Imponible de tributo OTRO por Item
                    $linea .= "|";//Tributo Otro:  Nombre de tributo OTRO por item
                    $linea .= "|";//Tributo Otro: Código de tipo de tributo OTRO por Item
                    $linea .= "|";//Tributo Otro: Porcentaje de tributo OTRO por Item

                    /*Tributo ICBPER 7152*/
                    if($result->prod_codigo == 'ICBPER'){
                        $linea .= "7152|";//Tributo Otro: Códigos de tipos de tributos OTRO
                        $linea .= ($value['cantidad']*floatval($configuracion->monto_icbper))."|";//Tributo Otro: Monto de tributo OTRO por iItem
                        $linea .= intval($value['cantidad'])."|";//Tributo Otro: Base Imponible de tributo OTRO por Item
                        $linea .= "ICBPER|";//Tributo Otro:  Nombre de tributo OTRO por item
                        $linea .= "OTH|";//Tributo Otro: Código de tipo de tributo OTRO por Item
                        $linea .= $configuracion->monto_icbper."|";//Tributo Otro: Porcentaje de tributo OTRO por Item
                    }else{
                        $linea .= "-|";//Tributo Otro: Códigos de tipos de tributos OTRO
                        $linea .= "|";//Tributo Otro: Monto de tributo OTRO por iItem
                        $linea .= "|";//Tributo Otro: Base Imponible de tributo OTRO por Item
                        $linea .= "|";//Tributo Otro:  Nombre de tributo OTRO por item
                        $linea .= "|";//Tributo Otro: Código de tipo de tributo OTRO por Item
                        $linea .= "|";//Tributo Otro: Porcentaje de tributo OTRO por Item
                    }
                    


                    //$linea .= ($value['precio_base']*1.18)."|";//Precio de venta unitario(base+igv)

                    if ($configuracion->pu_igv==1){
                        $linea .= round($value['importe'],2)."|";//Precio de venta unitario(base+igv)
                    }else{
                        $linea .= round($value['importe']*1.18,2)."|";//Precio de venta unitario(base+igv)
                    }
					
                    //$linea .= round($value['subtotal']-$value['descuento'], 2)."|";//Valor de venta por Item
                    if(intval($value['tipo_igv_codigo']) >= 20)//gratuitas la base es 0
                    {
                       $linea .= round($value['total']-$value['descuento'], 2)."|";//Valor de venta por Item
                    }else{
                        if ($configuracion->pu_igv==1){
                            $linea .= round(($value['total']/1.18)-$value['descuento'], 2)."|";//Valor de venta por Item
                        }else{
                            $linea .= round($value['total']-$value['descuento'], 2)."|";//Valor de venta por Item
                        }
					}
                    
                    $linea .= "0.00|\r\n";//Valor REFERENCIAL unitario (gratuitos) 
                    fwrite($f, $linea);
                }
                fclose($f);
                /*DOCUMENTO TRIBUTO*/
                $rut_tributo = $rutaArchivos . $comprobante['empresa_ruc'] . '-' . $comprobante['tipo_documento_codigo'] . '-' . $comprobante['serie'] . '-' . $comprobante['numero'] . '.TRI';
                $f = fopen($rut_tributo, 'w');

                //si tributo es igv
                if($comprobante['total_gravada'] > 0)
                {
                    $linea = "1000|";//Identificador de tributo
                    $linea .= "IGV|";//Nombre de tributo
                    $linea .= "VAT|";//Código de tipo de tributo
                    $linea .= "{$comprobante['total_gravada']}|";//Base imponible
                    $linea .= "{$comprobante['total_igv']}|\r\n";//Monto de Tirbuto por ítem
                    fwrite($f, $linea);                    
                }
                //si tributo es exonerada
                if($comprobante['total_exonerada'] > 0)
                {
                    $linea = "9997|";//Identificador de tributo
                    $linea .= "EXO|";//Nombre de tributo
                    $linea .= "VAT|";//Código de tipo de tributo
                    $linea .= "{$comprobante['total_exonerada']}|";//Base imponible
                    $linea .= "0|\r\n";//Monto de Tirbuto por ítem
                    fwrite($f, $linea);                    
                } 
                //si tributo es inafecto
                if($comprobante['total_inafecta'] > 0)
                {
                    
                    if($comprobante1->tipo_operacion=='0101'){
                        $linea = "9998|";//Identificador de tributo
                        $linea .= "INA|";//Nombre de tributo
                        $linea .= "FRE|";//Código de tipo de tributo
                        $linea .= "{$comprobante['total_inafecta']}|";//Base imponible
                        $linea .= "0|\r\n";//Monto de Tirbuto por ítem
                        fwrite($f, $linea);   
                    }else{
                        $linea = "9995|";//Identificador de tributo
                        $linea .= "EXP|";//Nombre de tributo
                        $linea .= "FRE|";//Código de tipo de tributo
                        $linea .= "{$comprobante['total_gratuita']}|";//Base imponible
                        $linea .= "0|\r\n";//Monto de Tirbuto por ítem
                        fwrite($f, $linea); 
                    }                  
                }
                //si tributo es gratuita/exportacion
                if($comprobante['total_gratuita'] > 0)
                {
                    
                        $linea = "9996|";//Identificador de tributo
                        $linea .= "GRA|";//Nombre de tributo
                        $linea .= "FRE|";//Código de tipo de tributo
                        $linea .= "{$comprobante['total_gratuita']}|";//Base imponible
                        $linea .= "0|\r\n";//Monto de Tirbuto por ítem
                        fwrite($f, $linea);  
                   
                                     
                }                                                

                fclose($f);
                /*DOCUMENTO LEYENDA*/
              /*  require_once (APPPATH .'libraries/Numletras.php');

        $num = new Numletras();*/
                $importe_letra = $num->num2letras(intval($comprobante['total_a_pagar']));
                $arrayImporte = explode(".",$comprobante['total_a_pagar']); 
                $montoLetras = $importe_letra.' con ' .$arrayImporte[1].'/100 '.$comprobante['moneda'];
                $rut_leyenda = $rutaArchivos . $comprobante['empresa_ruc'] . '-' . $comprobante['tipo_documento_codigo'] . '-' . $comprobante['serie'] . '-' . $comprobante['numero'] . '.LEY';
                $f = fopen($rut_leyenda, 'w'); 
                $linea = "1000|";//Código de leyenda
                $linea .= "{$montoLetras}|";//Descripción de leyenda    
                fwrite($f, $linea);
                fclose($f);   
                /*DOCUMENTOS RELACIONADO*/
                //verificamos si el comprobante tiene agregado anticipos
                $this->db->from("comprobante_anticipo");
                $this->db->join("comprobantes", "comprobante_anticipo.comprobante_id=comprobantes.id");
                $this->db->join("clientes", "comprobantes.cliente_id=clientes.id");
                $this->db->join("tipo_clientes", "clientes.tipo_cliente_id=tipo_clientes.id");
                $this->db->where("comprobante_id" , $comprobante_id);
                $query = $this->db->get();
                $anticipos = $query->result();
                //print_r($anticipos);exit();
                if(count($anticipos) > 0)
                {
                    $rut_relacionados = $rutaArchivos . $comprobante['empresa_ruc'] . '-' . $comprobante['tipo_documento_codigo'] . '-' . $comprobante['serie'] . '-' . $comprobante['numero'] . '.REL';
                    $f = fopen($rut_relacionados, 'w');
                    foreach ($anticipos as $anticipo)
                    {
                        $linea = "2|";//Indicador de documento relacionado (1: Guía, 2: Anticipo, 3: Orden de compra, 98: Documentos afectados (múltiples) por una Nota de Crédito / Débido,  99: Otros)
                        $linea .= "2|";//Número identificador del anticipo (solo para el Caso: 2 Anticipo).
                        $linea .= "02|";//Tipo de documento relacionado
                        $linea .= "{$anticipo->serie}-{$anticipo->numero}|";//Número de documento relacionado
                        $linea .= "{$anticipo->codigo}|";//Tipo de documento del emisor del documento relacionado
                        $linea .= "{$anticipo->ruc}|";//Número de documento del emisor del documento relacionado
                        $linea .= "{$anticipo->total_a_pagar}|\r\n";//Monto del documento relacionado
                        fwrite($f, $linea);
                    }

                    fclose($f);

                }else if($comprobante['numero_guia_remision']!=''){
                     $guia = $this->db->from('guias')
                              ->where('id',$comprobante['numero_guia_remision'])
                              ->get()->row(); 
                     $rut_relacionados = $rutaArchivos . $comprobante['empresa_ruc'] . '-' . $comprobante['tipo_documento_codigo'] . '-' . $comprobante['serie'] . '-' . $comprobante['numero'] . '.REL';
                    $f = fopen($rut_relacionados, 'w');
                    
                        $linea = "1|";//Indicador de documento relacionado (1: Guía, 2: Anticipo, 3: Orden de compra, 98: Documentos afectados (múltiples) por una Nota de Crédito / Débido,  99: Otros)
                        $linea .= "-|";//Número identificador del anticipo (solo para el Caso: 2 Anticipo).
                        $linea .= "09|";//Tipo de documento relacionado
                        $linea .= $comprobante['numero_guia_remision']."|";//Número de documento relacionado
                        $linea .= "6|";//Tipo de documento del emisor del documento relacionado
                        $linea .= $comprobante['empresa_ruc']."|";//Número de documento del emisor del documento relacionado
                        $linea .= $comprobante['total_a_pagar']."|\r\n";//Monto del documento relacionado
                        fwrite($f, $linea);
                    
                    fclose($f);
                }

            } else {
                //NOTA DE CREDITO , DEBITO
                /*obtenemos el archivo adjunto a esa nota de credito o debito*/
                $rsAdjunto = $this->db->from("comprobantes as comp")
                                      ->join("tipo_documentos as tdoc", "tdoc.id=comp.tipo_documento_id")
                                      ->where("comp.id", $comprobante['com_adjunto_id'])
                                      ->get()
                                      ->row();

                $fechaHoraEmision = new DateTime($comprobante['fecha_sunat']);
                $linea = '';
                $linea .= "0101|";//Tipo de operación 
                $linea .= "{$fechaHoraEmision->format('Y-m-d')}|";//Fecha de emisión 
                $linea .= "{$fechaHoraEmision->format('H:i:s')}|";//Hora de Emisión
                $linea .= "000|";//Código del domicilio fiscal o de local anexo del emisor 
                $linea .= "{$comprobante['tipo_cliente_codigo']}|";//Tipo de documento de identidad del adquirente o usuario 
                $linea .= "{$comprobante['cliente_ruc']}|";//Número de documento de identidad del adquirente o usuario
                $linea .= "{$comprobante['cli_razon_social']}"." {$comprobante['cli_nombres']}|";//Apellidos y nombres, denominación o razón social del adquirente o usuario 
                $linea .= "{$comprobante['abrstandar']}|";//Tipo de moneda en la cual se emite la factura electrónica
                $linea .= "{$comprobante['tipo_nota_codigo']}|";//Código del tipo de Nota de débito electrónica 
                //obetenmos la descripcion de la nota
                $rsNotaCredito = $this->db->from("tipo_ncreditos")
                                         ->where("codigo", $comprobante['tipo_nota_codigo'])
                                         ->get()
                                         ->row();

                $linea .= "{$rsNotaCredito->tipo_ncredito}|";//Descripción de motivo o sustento 
                $linea .= "{$rsAdjunto->codigo}|";//Tipo de documento del documento que modifica 
                $linea .= "{$rsAdjunto->serie}-{$rsAdjunto->numero}|";//Serie y número del documento que modifica
                $linea .= "{$comprobante['total_igv']}|";//Sumatoria Tributos
                $linea .= "{$comprobante['total_gravada']}|";//Total valor de venta 
                $linea .= "{$comprobante['total_a_pagar']}|";//Total Precio de Venta  //15
                $linea .= "{$comprobante['descuento_global']}|";//Total descuentos   //16
                $linea .= ($comprobante['total_otros_cargos'] + $comprobante['total_icbper'])."|";//sumatoria otros cargos
                $linea .= "0.00|";//Total Anticipos   //18
                $importe = $comprobante['total_a_pagar']-$comprobante['descuento_global']+$comprobante['total_otros_cargos']-0.00; //19
                $linea .= "{$importe}|";//Importe total de la venta, cesión en uso o del servicio prestado
                $linea .= "2.1|";//Versión UBL
                $linea .= "2.0|";//Customization Documento
                /*creamos archivo nota*/
                $sql = $rutaArchivos . $comprobante['empresa_ruc'] . '-' . $comprobante['tipo_documento_codigo']. '-' . $comprobante['serie'] . '-' . $comprobante['numero'] . '.NOT';
                $f = fopen($sql, 'w');
                fwrite($f, $linea);
                fclose($f); 

                /*detalle de nota credito*/               
                $rut = $rutaArchivos . $comprobante['empresa_ruc'] . '-' . $comprobante['tipo_documento_codigo'] . '-' . $comprobante['serie'] . '-' . $comprobante['numero'] . '.DET';
                $f = fopen($rut, 'w');
                foreach ($items as $value) {
                    $result = $this->db->from('productos prod')
                                       ->join('medida med',"prod.prod_medida_id=med.medida_id")
                                       ->where('prod_id',$value['producto_id'])
                                       ->get()
                                       ->row();

                   // $precioBaseUnidad = ($value['total']-$value['igv'])/$value['cantidad'];//precio unitario sin igv
                    if($comprobante1->comprobante_anticipo == '1')
                    {
                        $precioBaseUnidad = (($value['subtotal']/$value['cantidad'])/1.18);
                    }else{
                        $precioBaseUnidad = ($value['subtotal']/$value['cantidad']);
                    }
                    
                    $precioConIgv = $precioBaseUnidad*1.18;
                    $igvUnitario = $precioConIgv-$precioBaseUnidad ;

                   // $igvPorUnidad = $precioBaseUnidad;                    
                    $buscar=array(chr(13).chr(10), "\r\n", "\n", "\r");
                        $reemplazar=array("", "", "", "");
                                       
                    $descripction = str_ireplace($buscar,$reemplazar,$this->sanear_string(utf8_decode($value['descripcion'])));
                   
                     $linea = "{$value['unidad_id']}|";//Código de unidad de medida por ítem
                    $linea .= "{$value['cantidad']}|";//Cantidad de unidades por ítem

                    if($value['producto_id']!=0){
                        $linea .= "{$result->prod_codigo}|";//Código de producto
                    }else{
                        $linea .= "00000|";//Código de producto
                    }
                    $linea .= "-|";//Codigo producto SUNAT
                    $linea .= str_replace("&", "Y", trim(utf8_decode($descripction)))."|";//Descripción detallada del servicio prestado, bien vendido o cedido en uso, indicando las características.

                    if(intval($value['tipo_igv_codigo']) >= 20)//gratuitas la base es 0
                    {
                       $linea .= round($value['importe'], 2)."|";//Valor Unitario (cac:InvoiceLine/cac:Price/
                    }else{
                        if ($configuracion->pu_igv==1){
                            $linea .= round($value['importe']/1.18, 2)."|";//Valor Unitario (cac:InvoiceLine/cac:Price/cbc:PriceAmount)
                        }else{
                            $linea .= round($value['importe'], 2)."|";//Valor Unitario (cac:InvoiceLine/cac:Price/cbc:PriceAmount)
                        }
                    }

                    //$linea .= "{$value['igv']}|";//Sumatoria Tributos por item

                    if(intval($value['tipo_igv_codigo']) >= 20)//gratuitas la base es 0
                    {
                        $linea .= "0|";//Tributo: Base Imponible IGV por Item
                    }else{
                        if ($configuracion->pu_igv==1){
                            $linea .= round($value['total']-($value['total']/1.18),2)."|";//Sumatoria Tributos por item
                        }else{
                            $linea .= round($value['total']*0.18,2)."|";//Sumatoria Tributos por item
                        }
                        
                    }
                    
                    $linea .= $this->tipoCodigoDeTributo($value['tipo_igv_codigo'])['codigo']."|";//Tributo: Códigos de tipos de tributos IGV(1000 - 1016 - 9995 - 9996 - 9997 - 9998)
                    //$linea .= "{$value['igv']}|";//Tributo: Monto de IGV por ítem

                    if(intval($value['tipo_igv_codigo']) >= 20)//gratuitas la base es 0
                    {
                        $linea .= "0|";//Tributo: Base Imponible IGV por Item
                    }else{
                        if ($configuracion->pu_igv==1){
                            $linea .= round($value['total']-($value['total']/1.18),2)."|";//Sumatoria Tributos por item
                        }else{
                            $linea .= round($value['total']*0.18,2)."|";//Sumatoria Tributos por item
                        }
                        
                    }

                  
                    if(intval($value['tipo_igv_codigo']) >= 20)//gratuitas la base es 0
                    {
                       $linea .= round($value['total'],2)."|";//Sumatoria Tributos por item
                    }else{
                        if ($configuracion->pu_igv==1){
                            $linea .= round(($value['total']/1.18),2)."|";//Sumatoria Tributos por item
                        }else{
                            $linea .= round($value['total'],2)."|";//Sumatoria Tributos por item
                        }
                    }
                    
                    $linea .= $this->tipoCodigoDeTributo($value['tipo_igv_codigo'])['nombre']."|";//Tributo: Nombre de tributo por item
                    //$linea .= $this->tipoCodigoDeTributo($value['tipo_igv_codigo'])."|";//Tributo: Código de tipo de tributo por Item
                    $linea .= $this->tipoCodigoDeTributo($value['tipo_igv_codigo'])['codigoInternacional']."|";//Tributo: Código de tipo de tributo por Item
                    $linea .= "{$value['tipo_igv_codigo']}|";//Tributo: Afectación al IGV por ítem
                    $linea .= "18|";//Tributo: Porcentaje de IGV
                    /*Tributo ISC (2000)*/
                    $linea .= "-|";//Tributo ISC: Códigos de tipos de tributos ISC
                    $linea .= "|";//Tributo ISC: Monto de ISC por ítem
                    $linea .= "|";//Tributo ISC: Base Imponible ISC por Item
                    $linea .= "|";//Tributo ISC: Nombre de tributo por item
                    $linea .= "|";//Tributo ISC: Código de tipo de tributo por Item
                    $linea .= "|";//Tributo ISC: Tipo de sistema ISC
                    $linea .= "|";//Tributo ISC: Porcentaje de ISC
                    /*Tributo Otro 9999*/
                    $linea .= "-|";//Tributo Otro: Códigos de tipos de tributos OTRO
                    $linea .= "|";//Tributo Otro: Monto de tributo OTRO por iItem
                    $linea .= "|";//Tributo Otro: Base Imponible de tributo OTRO por Item
                    $linea .= "|";//Tributo Otro:  Nombre de tributo OTRO por item
                    $linea .= "|";//Tributo Otro: Código de tipo de tributo OTRO por Item
                    $linea .= "|";//Tributo Otro: Porcentaje de tributo OTRO por Item

                    /*Tributo ICBPER 7152*/
                    if($result->prod_codigo == 'ICBPER'){
                        $linea .= "7152|";//Tributo Otro: Códigos de tipos de tributos OTRO
                        $linea .= ($value['cantidad']*floatval($configuracion->monto_icbper))."|";//Tributo Otro: Monto de tributo OTRO por iItem
                        $linea .= intval($value['cantidad'])."|";//Tributo Otro: Base Imponible de tributo OTRO por Item
                        $linea .= "ICBPER|";//Tributo Otro:  Nombre de tributo OTRO por item
                        $linea .= "OTH|";//Tributo Otro: Código de tipo de tributo OTRO por Item
                        $linea .= $configuracion->monto_icbper."|";//Tributo Otro: Porcentaje de tributo OTRO por Item
                    }else{
                        $linea .= "-|";//Tributo Otro: Códigos de tipos de tributos OTRO
                        $linea .= "|";//Tributo Otro: Monto de tributo OTRO por iItem
                        $linea .= "|";//Tributo Otro: Base Imponible de tributo OTRO por Item
                        $linea .= "|";//Tributo Otro:  Nombre de tributo OTRO por item
                        $linea .= "|";//Tributo Otro: Código de tipo de tributo OTRO por Item
                        $linea .= "|";//Tributo Otro: Porcentaje de tributo OTRO por Item
                    }


                    //$linea .= ($value['precio_base']*1.18)."|";//Precio de venta unitario(base+igv)

                    if ($configuracion->pu_igv==1){
                        $linea .= round($value['importe'],2)."|";//Precio de venta unitario(base+igv)
                    }else{
                        $linea .= round($value['importe']*1.18,2)."|";//Precio de venta unitario(base+igv)
                    }
                    
                    //$linea .= round($value['subtotal']-$value['descuento'], 2)."|";//Valor de venta por Item
                    if(intval($value['tipo_igv_codigo']) >= 20)//gratuitas la base es 0
                    {
                       $linea .= round($value['total']-$value['descuento'], 2)."|";//Valor de venta por Item
                    }else{
                        if ($configuracion->pu_igv==1){
                            $linea .= round(($value['total']/1.18)-$value['descuento'], 2)."|";//Valor de venta por Item
                        }else{
                            $linea .= round($value['total']-$value['descuento'], 2)."|";//Valor de venta por Item
                        }
                    }
                    
                    $linea .= "0.00|\r\n";//Valor REFERENCIAL unitario (gratuitos) 
                    fwrite($f, $linea);
                }

                fclose($f);

                /*TRIBUTO NOTA DE CREDITO*/
                /*DOCUMENTO TRIBUTO*/
                $rut_tributo = $rutaArchivos . $comprobante['empresa_ruc'] . '-' . $comprobante['tipo_documento_codigo'] . '-' . $comprobante['serie'] . '-' . $comprobante['numero'] . '.TRI';
                $f = fopen($rut_tributo, 'w');
                //si tributo es igv
                if($comprobante['total_gravada'] > 0)
                {
                    $linea = "1000|";//Identificador de tributo
                    $linea .= "IGV|";//Nombre de tributo
                    $linea .= "VAT|";//Código de tipo de tributo
                    $linea .= "{$comprobante['total_gravada']}|";//Base imponible
                    $linea .= "{$comprobante['total_igv']}|\r\n";//Monto de Tirbuto por ítem
                    fwrite($f, $linea);                    
                }
                //si tributo es exonerada
                if($comprobante['total_exonerada'] > 0)
                {
                    $linea = "9997|";//Identificador de tributo
                    $linea .= "EXO|";//Nombre de tributo
                    $linea .= "VAT|";//Código de tipo de tributo
                    $linea .= "{$comprobante['total_exonerada']}|";//Base imponible
                    $linea .= "0|\r\n";//Monto de Tirbuto por ítem
                    fwrite($f, $linea);                    
                } 
                //si tributo es inafecto
                if($comprobante['total_inafecta'] > 0)
                {
                   if($comprobante1->tipo_operacion=='0101'){
                        $linea = "9998|";//Identificador de tributo
                        $linea .= "INA|";//Nombre de tributo
                        $linea .= "FRE|";//Código de tipo de tributo
                        $linea .= "{$comprobante['total_inafecta']}|";//Base imponible
                        $linea .= "0|\r\n";//Monto de Tirbuto por ítem
                        fwrite($f, $linea);   
                    }else{
                        $linea = "9995|";//Identificador de tributo
                        $linea .= "EXP|";//Nombre de tributo
                        $linea .= "FRE|";//Código de tipo de tributo
                        $linea .= "{$comprobante['total_gratuita']}|";//Base imponible
                        $linea .= "0|\r\n";//Monto de Tirbuto por ítem
                        fwrite($f, $linea); 
                    }                     
                }
                //si tributo es gratuita/exportacion
                if($comprobante['total_gratuita'] > 0)
                {
                    $linea = "9996|";//Identificador de tributo
                    $linea .= "GRA|";//Nombre de tributo
                    $linea .= "FRE|";//Código de tipo de tributo
                    $linea .= "{$comprobante['total_gratuita']}|";//Base imponible
                    $linea .= "0|\r\n";//Monto de Tirbuto por ítem
                    fwrite($f, $linea);                    
                }                                                

                fclose($f);
                /*DOCUMENTO LEYENDA*/
                $importe_letra = $num->num2letras(intval($comprobante['total_a_pagar']));
                $arrayImporte = explode(".",$comprobante['total_a_pagar']); 
                $montoLetras = $importe_letra.' con ' .$arrayImporte[1].'/100 '.$comprobante['moneda'];
                $rut_leyenda = $rutaArchivos . $comprobante['empresa_ruc'] . '-' . $comprobante['tipo_documento_codigo'] . '-' . $comprobante['serie'] . '-' . $comprobante['numero'] . '.LEY';
                $f = fopen($rut_leyenda, 'w'); 
                $linea = "1000|";//Código de leyenda
                $linea .= "{$montoLetras}|";//Descripción de leyenda    
                fwrite($f, $linea);
                fclose($f);                 

            }
            /*if ($comprobante['tipo_documento_id'] == 3) {
                $this->resumenes_model->guardarResumen($comprobante_id,1);
            }*/

            $this->comprobantes_model->modificar(array('enviado_sunat' => 1), $comprobante_id);
            $this->session->set_flashdata('mensaje', 'Envio exitoso!');
        } else {
            /*comunicar de baja a boletas*/
            if ($comprobante['tipo_documento_id'] == 3) {
                $this->resumenes_model->guardarResumen($comprobante_id,3);

                $fecha1 = date("Ymd");
                $fecha2 = date("Y-m-d");
                $dataAnular = array(
                    'fecha' => $fecha2,
                    'numero' => $numero,
                    'comprobante_id' => $comprobante_id,
                    'empleado_insert' => $this->session->userdata('empleado_id'),
                    'fecha_insert' => date("Y-m-d H:i:s")
                );
                $this->comprobante_anulados_model->insertar($dataAnular);
                $this->comprobantes_model->modificar(array('fecha_de_baja' => $fecha2, 'anulado' => 1), $comprobante_id);
            } else {
                //  COMUNICACION DE BAJA TXT
                $fecha1 = date("Ymd");
                $fecha2 = date("Y-m-d");
                $numero = $this->comprobante_anulados_model->maxNumero($fecha2) + 1;
                
                $f = fopen($rutaArchivos . $comprobante['empresa_ruc'] . '-RA-' . $fecha1 . '-' . $numero . '.CBA', 'w');
                
                $linea = (new DateTime($comprobante1->fecha_de_emision))->format('Y-m-d').'|';
                $linea .= (new DateTime())->format('Y-m-d')."|";
                $linea .= $comprobante['tipo_documento_codigo']."|";
                $linea .= $comprobante['serie'] . '-' .$comprobante['numero'].'|';
                $linea .= "anulacion|";
                fwrite($f, $linea);
                fclose($f);

                $dataAnular = array(
                    'fecha' => $fecha2,
                    'numero' => $numero,
                    'comprobante_id' => $comprobante_id,
                    'empleado_insert' => $this->session->userdata('empleado_id'),
                    'fecha_insert' => date("Y-m-d H:i:s")
                );
                $this->comprobante_anulados_model->insertar($dataAnular);
                $this->comprobantes_model->modificar(array('fecha_de_baja' => $fecha2, 'anulado' => 1), $comprobante_id);

               
                


                $this->session->set_flashdata('mensaje', 'Anulación exitosa!');
            }

            ////// STOCK 
                $this->db->select('i.*,c.serie,c.numero');
                $this->db->from('items as i');
                $this->db->join('comprobantes as c','c.id=i.comprobante_id');
                $this->db->where('i.comprobante_id',$comprobante_id);
                $items = $this->db->get()->result();
                foreach ($items as $i) {
                   

                    if($i->producto_id!=0){
                        if($com->tipo_documento_id!=7 and $com->tipo_documento_id!=9){
                             $stock = $this->productos_model->getStockProductos($i->producto_id,$this->session->userdata("almacen_id"));
                             $nueva_cantidad = floatval($stock)+floatval($i->cantidad);

                             $kardex = array(
                              'k_fecha' => date('Y-m-d'),
                              'k_almacen' => $this->session->userdata("almacen_id"),
                              'k_tipo' => 2,
                              'k_operacion_id' => $comprobante_id,
                              'k_serie' => $i->serie.'-'.$i->numero,
                              'k_concepto' => 'Anulación de documento',    
                              'k_producto' => $i->producto_id,
                              'k_ecantidad' => $i->cantidad,
                              'k_excantidad' => $nueva_cantidad,
                                               
                             );

                             $this->db->insert('kardex', $kardex);
                        } else{
                             $stock = $this->productos_model->getStockProductos($i->producto_id,$this->session->userdata("almacen_id"));
                             $nueva_cantidad = floatval($stock)-floatval($i->cantidad);

                             $kardex = array(
                              'k_fecha' => date('Y-m-d'),
                              'k_almacen' => $this->session->userdata("almacen_id"),
                              'k_tipo' => 2,
                              'k_operacion_id' => $comprobante_id,
                              'k_serie' => $i->serie.'-'.$i->numero,
                              'k_concepto' => 'Anulación de documento',    
                              'k_producto' => $i->producto_id,
                              'k_scantidad' => $i->cantidad,
                              'k_excantidad' => $nueva_cantidad,
                                               
                             );

                             $this->db->insert('kardex', $kardex);  
                        }
                    } 
                }

            

            
        }
        redirect(base_url() . "index.php/comprobantes/index/" . $comprobante['empresa_id']);
    }

    public function txt_directo($envio = 0, $comprobante_id = '') {
        require_once (APPPATH .'libraries/Numletras.php');

        $num = new Numletras();
        $data = $this->clientes_model->select();
        $comprobante = $this->comprobantes_model->select($comprobante_id);
        $this->db->where("id",$comprobante_id);
        $this->db->where("eliminado",0);
        $query = $this->db->get("comprobantes");
        $comprobante1 = $query->row();
        //print_r($comprobante1->total_descuentos);exit();
        $items = $this->items_model->select('', $comprobante_id);
        $detraccion = $this->elemento_adicionales_model->select('', '', 'activo');

         $configuracion = $this->db->from('comprobantes_ventas')->get()->row();
            

        /*print_r($comprobante);exit();*/
        //TIPO EMPRESA
        $ruta = '';
        if ($comprobante['empresa_id'] == 1) {
            $ruta = 'sunat_archivos/sfs/DATA/';
        }
        if ($comprobante['empresa_id'] == 2) {
            $ruta = 'neple/sunat_archivos/sfs/DATA/';
        }
        $rutaArchivos = DISCO.':\xampp\htdocs'.CARPETA."/".SFS."/sunat_archivos/sfs/DATA/";

        if ($envio == 0) {
            if ($comprobante['tipo_documento_id'] < 4) {
                // FACTURA , BOLETA                
                  
                /*nombre archivo*/
                $fechaHoraEmision = new DateTime($comprobante['fecha_sunat']);
                $fechaVencimiento = new DateTime($comprobante['fecha_de_vencimiento']);
                $sql = $rutaArchivos . $comprobante['empresa_ruc'] . '-' . $comprobante['tipo_documento_codigo'] . '-' . $comprobante['serie'] . '-' . $comprobante['numero'] . '.CAB';
                $f = fopen($sql, 'w');

                /*cuerpo documento cabecera*/ 
                $linea = $comprobante1->tipo_operacion."|";//tipo operacion
                $linea .= "{$fechaHoraEmision->format('Y-m-d')}|";//fecha emision
                $linea .= "{$fechaHoraEmision->format('H:i:s')}|";//hora emision
                $linea .= "{$fechaVencimiento->format('Y-m-d')}|";//:fecha vencimiento
                $linea .= "0000|";//codigo domicilio fiscal
                $linea .= "{$comprobante['tipo_cliente_codigo']}|";//tipo de documento de identidad
                $linea .= trim("{$comprobante['cliente_ruc']}")."|";//:numero de documento identidad
                $linea .= "{$comprobante['cli_razon_social']}"." {$comprobante['cli_nombres']}|";//apellidos y nombres o razon social
                $linea .= "{$comprobante['abrstandar']}|";//:tipo de moneda
                $linea .= "{$comprobante['total_igv']}|";//:sumatoria tributos
                $linea .= "{$comprobante['total_gravada']}|";//:total valor venta
                $linea .= "{$comprobante['total_a_pagar']}|";//total precio venta
                $linea .= $comprobante1->total_descuentos."|";//total descuento
                $linea .= "0|";//sumatoria otros cargos
                /*buscamos si el comprobante a enviar tiene anticipos*/
                $this->db->from("comprobante_anticipo");
                $this->db->join("comprobantes", "comprobante_anticipo.comprobante_id=comprobantes.id");
                $this->db->where("comprobante_id" , $comprobante_id);
                $query = $this->db->get();
                $anticipos = $query->result(); 
                if(count($anticipos))
                {
                    $totalAnticipos = 0;
                    foreach($anticipos as $anticipo)
                    {
                        $totalAnticipo += $anticipo->total_a_pagar;
                    }
                    $linea .= "{$totalAnticipo}|";
                }else{
                    $linea .= "0|";//total anticipo                  
                }               
                $linea .= "{$comprobante['total_a_pagar']}|";//importe total venta
                $linea .= "2.1|";//version UBL
                $linea .= "2.0|\r\n";//customization

                fwrite($f, $linea);
                fclose($f);
                
                $rut = $rutaArchivos . $comprobante['empresa_ruc'] . '-' . $comprobante['tipo_documento_codigo'] . '-' . $comprobante['serie'] . '-' . $comprobante['numero'] . '.DET';
                $f = fopen($rut, 'w');

                foreach ($items as $value) {
                    $result = $this->db->from('productos prod')
                                       ->join('medida med',"prod.prod_medida_id=med.medida_id")
                                       ->where('prod_id',$value['producto_id'])
                                       ->get()
                                       ->row();

                   // $precioBaseUnidad = ($value['total']-$value['igv'])/$value['cantidad'];//precio unitario sin igv
                    if($comprobante1->comprobante_anticipo == '1')
                    {
                        $precioBaseUnidad = (($value['subtotal']/$value['cantidad'])/1.18);
                    }else{
                        $precioBaseUnidad = ($value['subtotal']/$value['cantidad']);
                    }
                    
                    $precioConIgv = $precioBaseUnidad*1.18;
                    $igvUnitario = $precioConIgv-$precioBaseUnidad ;

                   // $igvPorUnidad = $precioBaseUnidad;                    
                    $buscar=array(chr(13).chr(10), "\r\n", "\n", "\r");
                        $reemplazar=array("", "", "", "");
                                       
                    $descripction = str_ireplace($buscar,$reemplazar,$this->sanear_string(utf8_decode($value['descripcion'])));
                   
                     $linea = "{$value['unidad_id']}|";//Código de unidad de medida por ítem
                    $linea .= "{$value['cantidad']}|";//Cantidad de unidades por ítem

                    if($value['producto_id']!=0){
                        $linea .= "{$result->prod_codigo}|";//Código de producto
                    }else{
                        $linea .= "NONE|";//Código de producto
                    }

                    if($result->prod_codigo_sunat!=''){
                        $linea .= "{$result->prod_codigo_sunat}|";//Código de producto
                    }else{
                        $linea .= "-|";//Codigo producto SUNAT
                    }
                    
                    $linea .= str_replace("&", "Y", trim(utf8_decode($descripction)))."|";//Descripción detallada del servicio prestado, bien vendido o cedido en uso, indicando las características.

                    if(intval($value['tipo_igv_codigo']) >= 20)//gratuitas la base es 0
                    {
                       $linea .= round($value['importe'], 2)."|";//Valor Unitario (cac:InvoiceLine/cac:Price/
                    }else{
                        if ($configuracion->pu_igv==1){
                            $linea .= round($value['importe']/1.18, 2)."|";//Valor Unitario (cac:InvoiceLine/cac:Price/cbc:PriceAmount)
                        }else{
                            $linea .= round($value['importe'], 2)."|";//Valor Unitario (cac:InvoiceLine/cac:Price/cbc:PriceAmount)
                        }
                    }

                    //$linea .= "{$value['igv']}|";//Sumatoria Tributos por item

                    if(intval($value['tipo_igv_codigo']) >= 20)//gratuitas la base es 0
                    {
                        $linea .= "0|";//Tributo: Base Imponible IGV por Item
                    }else{
                        if ($configuracion->pu_igv==1){
                            $linea .= round($value['total']-($value['total']/1.18),2)."|";//Sumatoria Tributos por item
                        }else{
                            $linea .= round($value['total']*0.18,2)."|";//Sumatoria Tributos por item
                        }
                        
                    }
                    
                    $linea .= $this->tipoCodigoDeTributo($value['tipo_igv_codigo'])['codigo']."|";//Tributo: Códigos de tipos de tributos IGV(1000 - 1016 - 9995 - 9996 - 9997 - 9998)
                    //$linea .= "{$value['igv']}|";//Tributo: Monto de IGV por ítem

                    if(intval($value['tipo_igv_codigo']) >= 20)//gratuitas la base es 0
                    {
                        $linea .= "0|";//Tributo: Base Imponible IGV por Item
                    }else{
                        if ($configuracion->pu_igv==1){
                            $linea .= round($value['total']-($value['total']/1.18),2)."|";//Sumatoria Tributos por item
                        }else{
                            $linea .= round($value['total']*0.18,2)."|";//Sumatoria Tributos por item
                        }
                        
                    }

                  
                    if(intval($value['tipo_igv_codigo']) >= 20)//gratuitas la base es 0
                    {
                       $linea .= round($value['total'],2)."|";//Sumatoria Tributos por item
                    }else{
                        if ($configuracion->pu_igv==1){
                            $linea .= round(($value['total']/1.18),2)."|";//Sumatoria Tributos por item
                        }else{
                            $linea .= round($value['total'],2)."|";//Sumatoria Tributos por item
                        }
                    }
                    
                    $linea .= $this->tipoCodigoDeTributo($value['tipo_igv_codigo'])['nombre']."|";//Tributo: Nombre de tributo por item
                    //$linea .= $this->tipoCodigoDeTributo($value['tipo_igv_codigo'])."|";//Tributo: Código de tipo de tributo por Item
                    $linea .= $this->tipoCodigoDeTributo($value['tipo_igv_codigo'])['codigoInternacional']."|";//Tributo: Código de tipo de tributo por Item
                    $linea .= "{$value['tipo_igv_codigo']}|";//Tributo: Afectación al IGV por ítem
                    $linea .= "18|";//Tributo: Porcentaje de IGV
                    /*Tributo ISC (2000)*/
                    $linea .= "-|";//Tributo ISC: Códigos de tipos de tributos ISC
                    $linea .= "|";//Tributo ISC: Monto de ISC por ítem
                    $linea .= "|";//Tributo ISC: Base Imponible ISC por Item
                    $linea .= "|";//Tributo ISC: Nombre de tributo por item
                    $linea .= "|";//Tributo ISC: Código de tipo de tributo por Item
                    $linea .= "|";//Tributo ISC: Tipo de sistema ISC
                    $linea .= "|";//Tributo ISC: Porcentaje de ISC
                    /*Tributo Otro 9999*/
                    $linea .= "-|";//Tributo Otro: Códigos de tipos de tributos OTRO
                    $linea .= "|";//Tributo Otro: Monto de tributo OTRO por iItem
                    $linea .= "|";//Tributo Otro: Base Imponible de tributo OTRO por Item
                    $linea .= "|";//Tributo Otro:  Nombre de tributo OTRO por item
                    $linea .= "|";//Tributo Otro: Código de tipo de tributo OTRO por Item
                    $linea .= "|";//Tributo Otro: Porcentaje de tributo OTRO por Item


                    //$linea .= ($value['precio_base']*1.18)."|";//Precio de venta unitario(base+igv)

                    if ($configuracion->pu_igv==1){
                        $linea .= round($value['importe'],2)."|";//Precio de venta unitario(base+igv)
                    }else{
                        $linea .= round($value['importe']*1.18,2)."|";//Precio de venta unitario(base+igv)
                    }
                    
                    //$linea .= round($value['subtotal']-$value['descuento'], 2)."|";//Valor de venta por Item
                    if(intval($value['tipo_igv_codigo']) >= 20)//gratuitas la base es 0
                    {
                       $linea .= round($value['total']-$value['descuento'], 2)."|";//Valor de venta por Item
                    }else{
                        if ($configuracion->pu_igv==1){
                            $linea .= round(($value['total']/1.18)-$value['descuento'], 2)."|";//Valor de venta por Item
                        }else{
                            $linea .= round($value['total']-$value['descuento'], 2)."|";//Valor de venta por Item
                        }
                    }
                    
                    $linea .= "0.00|\r\n";//Valor REFERENCIAL unitario (gratuitos) 
                    fwrite($f, $linea);
                }
                fclose($f);
                /*DOCUMENTO TRIBUTO*/
                $rut_tributo = $rutaArchivos . $comprobante['empresa_ruc'] . '-' . $comprobante['tipo_documento_codigo'] . '-' . $comprobante['serie'] . '-' . $comprobante['numero'] . '.TRI';
                $f = fopen($rut_tributo, 'w');

                //si tributo es igv
                if($comprobante['total_gravada'] > 0)
                {
                    $linea = "1000|";//Identificador de tributo
                    $linea .= "IGV|";//Nombre de tributo
                    $linea .= "VAT|";//Código de tipo de tributo
                    $linea .= "{$comprobante['total_gravada']}|";//Base imponible
                    $linea .= "{$comprobante['total_igv']}|\r\n";//Monto de Tirbuto por ítem
                    fwrite($f, $linea);                    
                }
                //si tributo es exonerada
                if($comprobante['total_exonerada'] > 0)
                {
                    $linea = "9997|";//Identificador de tributo
                    $linea .= "EXO|";//Nombre de tributo
                    $linea .= "VAT|";//Código de tipo de tributo
                    $linea .= "{$comprobante['total_exonerada']}|";//Base imponible
                    $linea .= "0|\r\n";//Monto de Tirbuto por ítem
                    fwrite($f, $linea);                    
                } 
                //si tributo es inafecto
                if($comprobante['total_inafecta'] > 0)
                {
                    
                    if($comprobante1->tipo_operacion=='0101'){
                        $linea = "9998|";//Identificador de tributo
                        $linea .= "INA|";//Nombre de tributo
                        $linea .= "FRE|";//Código de tipo de tributo
                        $linea .= "{$comprobante['total_inafecta']}|";//Base imponible
                        $linea .= "0|\r\n";//Monto de Tirbuto por ítem
                        fwrite($f, $linea);   
                    }else{
                        $linea = "9995|";//Identificador de tributo
                        $linea .= "EXP|";//Nombre de tributo
                        $linea .= "FRE|";//Código de tipo de tributo
                        $linea .= "{$comprobante['total_gratuita']}|";//Base imponible
                        $linea .= "0|\r\n";//Monto de Tirbuto por ítem
                        fwrite($f, $linea); 
                    }                  
                }
                //si tributo es gratuita/exportacion
                if($comprobante['total_gratuita'] > 0)
                {
                    
                        $linea = "9996|";//Identificador de tributo
                        $linea .= "GRA|";//Nombre de tributo
                        $linea .= "FRE|";//Código de tipo de tributo
                        $linea .= "{$comprobante['total_gratuita']}|";//Base imponible
                        $linea .= "0|\r\n";//Monto de Tirbuto por ítem
                        fwrite($f, $linea);  
                   
                                     
                }                                                

                fclose($f);
                /*DOCUMENTO LEYENDA*/
                $importe_letra = $num->num2letras(intval($comprobante['total_a_pagar']));
                $arrayImporte = explode(".",$comprobante['total_a_pagar']); 
                $montoLetras = $importe_letra.' con ' .$arrayImporte[1].'/100 '.$comprobante['moneda'];
                $rut_leyenda = $rutaArchivos . $comprobante['empresa_ruc'] . '-' . $comprobante['tipo_documento_codigo'] . '-' . $comprobante['serie'] . '-' . $comprobante['numero'] . '.LEY';
                $f = fopen($rut_leyenda, 'w'); 
                $linea = "1000|";//Código de leyenda
                $linea .= "{$montoLetras}|";//Descripción de leyenda    
                fwrite($f, $linea);
                fclose($f);   
                /*DOCUMENTOS RELACIONADO*/
                //verificamos si el comprobante tiene agregado anticipos
                $this->db->from("comprobante_anticipo");
                $this->db->join("comprobantes", "comprobante_anticipo.comprobante_id=comprobantes.id");
                $this->db->join("clientes", "comprobantes.cliente_id=clientes.id");
                $this->db->join("tipo_clientes", "clientes.tipo_cliente_id=tipo_clientes.id");
                $this->db->where("comprobante_id" , $comprobante_id);
                $query = $this->db->get();
                $anticipos = $query->result();
                //print_r($anticipos);exit();
                if(count($anticipos) > 0)
                {
                    $rut_relacionados = $rutaArchivos . $comprobante['empresa_ruc'] . '-' . $comprobante['tipo_documento_codigo'] . '-' . $comprobante['serie'] . '-' . $comprobante['numero'] . '.REL';
                    $f = fopen($rut_relacionados, 'w');
                    foreach ($anticipos as $anticipo)
                    {
                        $linea = "2|";//Indicador de documento relacionado (1: Guía, 2: Anticipo, 3: Orden de compra, 98: Documentos afectados (múltiples) por una Nota de Crédito / Débido,  99: Otros)
                        $linea .= "2|";//Número identificador del anticipo (solo para el Caso: 2 Anticipo).
                        $linea .= "02|";//Tipo de documento relacionado
                        $linea .= "{$anticipo->serie}-{$anticipo->numero}|";//Número de documento relacionado
                        $linea .= "{$anticipo->codigo}|";//Tipo de documento del emisor del documento relacionado
                        $linea .= "{$anticipo->ruc}|";//Número de documento del emisor del documento relacionado
                        $linea .= "{$anticipo->total_a_pagar}|\r\n";//Monto del documento relacionado
                        fwrite($f, $linea);
                    }

                    fclose($f);

                }else if($comprobante['numero_guia_remision']!=''){
                     $guia = $this->db->from('guias')
                              ->where('id',$comprobante['numero_guia_remision'])
                              ->get()->row(); 
                     $rut_relacionados = $rutaArchivos . $comprobante['empresa_ruc'] . '-' . $comprobante['tipo_documento_codigo'] . '-' . $comprobante['serie'] . '-' . $comprobante['numero'] . '.REL';
                    $f = fopen($rut_relacionados, 'w');
                    
                        $linea = "1|";//Indicador de documento relacionado (1: Guía, 2: Anticipo, 3: Orden de compra, 98: Documentos afectados (múltiples) por una Nota de Crédito / Débido,  99: Otros)
                        $linea .= "-|";//Número identificador del anticipo (solo para el Caso: 2 Anticipo).
                        $linea .= "09|";//Tipo de documento relacionado
                        $linea .= $comprobante['numero_guia_remision']."|";//Número de documento relacionado
                        $linea .= "6|";//Tipo de documento del emisor del documento relacionado
                        $linea .= $comprobante['empresa_ruc']."|";//Número de documento del emisor del documento relacionado
                        $linea .= $comprobante['total_a_pagar']."|\r\n";//Monto del documento relacionado
                        fwrite($f, $linea);
                    
                    fclose($f);
                }

            } else {
                //NOTA DE CREDITO , DEBITO
                /*obtenemos el archivo adjunto a esa nota de credito o debito*/
                $rsAdjunto = $this->db->from("comprobantes as comp")
                                      ->join("tipo_documentos as tdoc", "tdoc.id=comp.tipo_documento_id")
                                      ->where("comp.id", $comprobante['com_adjunto_id'])
                                      ->get()
                                      ->row();

                $fechaHoraEmision = new DateTime($comprobante['fecha_sunat']);
                $linea = '';
                $linea .= "0101|";//Tipo de operación 
                $linea .= "{$fechaHoraEmision->format('Y-m-d')}|";//Fecha de emisión 
                $linea .= "{$fechaHoraEmision->format('H:i:s')}|";//Hora de Emisión
                $linea .= "000|";//Código del domicilio fiscal o de local anexo del emisor 
                $linea .= "{$comprobante['tipo_cliente_codigo']}|";//Tipo de documento de identidad del adquirente o usuario 
                $linea .= "{$comprobante['cliente_ruc']}|";//Número de documento de identidad del adquirente o usuario
                $linea .= "{$comprobante['cli_razon_social']}"." {$comprobante['cli_nombres']}|";//Apellidos y nombres, denominación o razón social del adquirente o usuario 
                $linea .= "{$comprobante['abrstandar']}|";//Tipo de moneda en la cual se emite la factura electrónica
                $linea .= "{$comprobante['tipo_nota_codigo']}|";//Código del tipo de Nota de débito electrónica 
                //obetenmos la descripcion de la nota
                $rsNotaCredito = $this->db->from("tipo_ncreditos")
                                         ->where("codigo", $comprobante['tipo_nota_codigo'])
                                         ->get()
                                         ->row();

                $linea .= "{$rsNotaCredito->tipo_ncredito}|";//Descripción de motivo o sustento 
                $linea .= "{$rsAdjunto->codigo}|";//Tipo de documento del documento que modifica 
                $linea .= "{$rsAdjunto->serie}-{$rsAdjunto->numero}|";//Serie y número del documento que modifica
                $linea .= "{$comprobante['total_igv']}|";//Sumatoria Tributos
                $linea .= "{$comprobante['total_gravada']}|";//Total valor de venta 
                $linea .= "{$comprobante['total_a_pagar']}|";//Total Precio de Venta  //15
                $linea .= "{$comprobante['descuento_global']}|";//Total descuentos   //16
                $linea .= "0.00|";//Sumatoria otros Cargos  //17
                $linea .= "0.00|";//Total Anticipos   //18
                $importe = $comprobante['total_a_pagar']-$comprobante['descuento_global']+$comprobante['total_otros_cargos']-0.00; //19
                $linea .= "{$importe}|";//Importe total de la venta, cesión en uso o del servicio prestado
                $linea .= "2.1|";//Versión UBL
                $linea .= "2.0|";//Customization Documento
                /*creamos archivo nota*/
                $sql = $rutaArchivos . $comprobante['empresa_ruc'] . '-' . $comprobante['tipo_documento_codigo']. '-' . $comprobante['serie'] . '-' . $comprobante['numero'] . '.NOT';
                $f = fopen($sql, 'w');
                fwrite($f, $linea);
                fclose($f); 

                /*detalle de nota credito*/               
                $rut = $rutaArchivos . $comprobante['empresa_ruc'] . '-' . $comprobante['tipo_documento_codigo'] . '-' . $comprobante['serie'] . '-' . $comprobante['numero'] . '.DET';
                $f = fopen($rut, 'w');
                foreach ($items as $value) {
                    $result = $this->db->from('productos prod')
                                       ->join('medida med',"prod.prod_medida_id=med.medida_id")
                                       ->where('prod_id',$value['producto_id'])
                                       ->get()
                                       ->row();

                   // $precioBaseUnidad = ($value['total']-$value['igv'])/$value['cantidad'];//precio unitario sin igv
                    if($comprobante1->comprobante_anticipo == '1')
                    {
                        $precioBaseUnidad = (($value['subtotal']/$value['cantidad'])/1.18);
                    }else{
                        $precioBaseUnidad = ($value['subtotal']/$value['cantidad']);
                    }
                    
                    $precioConIgv = $precioBaseUnidad*1.18;
                    $igvUnitario = $precioConIgv-$precioBaseUnidad ;

                   // $igvPorUnidad = $precioBaseUnidad;                    
                    $buscar=array(chr(13).chr(10), "\r\n", "\n", "\r");
                        $reemplazar=array("", "", "", "");
                                       
                    $descripction = str_ireplace($buscar,$reemplazar,$this->sanear_string(utf8_decode($value['descripcion'])));
                   
                     $linea = "{$value['unidad_id']}|";//Código de unidad de medida por ítem
                    $linea .= "{$value['cantidad']}|";//Cantidad de unidades por ítem

                    if($value['producto_id']!=0){
                        $linea .= "{$result->prod_codigo}|";//Código de producto
                    }else{
                        $linea .= "NONE|";//Código de producto
                    }
                    $linea .= "-|";//Codigo producto SUNAT
                    $linea .= str_replace("&", "Y", trim(utf8_decode($descripction)))."|";//Descripción detallada del servicio prestado, bien vendido o cedido en uso, indicando las características.

                    if(intval($value['tipo_igv_codigo']) >= 20)//gratuitas la base es 0
                    {
                       $linea .= round($value['importe'], 2)."|";//Valor Unitario (cac:InvoiceLine/cac:Price/
                    }else{
                        if ($configuracion->pu_igv==1){
                            $linea .= round($value['importe']/1.18, 2)."|";//Valor Unitario (cac:InvoiceLine/cac:Price/cbc:PriceAmount)
                        }else{
                            $linea .= round($value['importe'], 2)."|";//Valor Unitario (cac:InvoiceLine/cac:Price/cbc:PriceAmount)
                        }
                    }

                    //$linea .= "{$value['igv']}|";//Sumatoria Tributos por item

                    if(intval($value['tipo_igv_codigo']) >= 20)//gratuitas la base es 0
                    {
                        $linea .= "0|";//Tributo: Base Imponible IGV por Item
                    }else{
                        if ($configuracion->pu_igv==1){
                            $linea .= round($value['total']-($value['total']/1.18),2)."|";//Sumatoria Tributos por item
                        }else{
                            $linea .= round($value['total']*0.18,2)."|";//Sumatoria Tributos por item
                        }
                        
                    }
                    
                    $linea .= $this->tipoCodigoDeTributo($value['tipo_igv_codigo'])['codigo']."|";//Tributo: Códigos de tipos de tributos IGV(1000 - 1016 - 9995 - 9996 - 9997 - 9998)
                    //$linea .= "{$value['igv']}|";//Tributo: Monto de IGV por ítem

                    if(intval($value['tipo_igv_codigo']) >= 20)//gratuitas la base es 0
                    {
                        $linea .= "0|";//Tributo: Base Imponible IGV por Item
                    }else{
                        if ($configuracion->pu_igv==1){
                            $linea .= round($value['total']-($value['total']/1.18),2)."|";//Sumatoria Tributos por item
                        }else{
                            $linea .= round($value['total']*0.18,2)."|";//Sumatoria Tributos por item
                        }
                        
                    }

                  
                    if(intval($value['tipo_igv_codigo']) >= 20)//gratuitas la base es 0
                    {
                       $linea .= round($value['total'],2)."|";//Sumatoria Tributos por item
                    }else{
                        if ($configuracion->pu_igv==1){
                            $linea .= round(($value['total']/1.18),2)."|";//Sumatoria Tributos por item
                        }else{
                            $linea .= round($value['total'],2)."|";//Sumatoria Tributos por item
                        }
                    }
                    
                    $linea .= $this->tipoCodigoDeTributo($value['tipo_igv_codigo'])['nombre']."|";//Tributo: Nombre de tributo por item
                    //$linea .= $this->tipoCodigoDeTributo($value['tipo_igv_codigo'])."|";//Tributo: Código de tipo de tributo por Item
                    $linea .= $this->tipoCodigoDeTributo($value['tipo_igv_codigo'])['codigoInternacional']."|";//Tributo: Código de tipo de tributo por Item
                    $linea .= "{$value['tipo_igv_codigo']}|";//Tributo: Afectación al IGV por ítem
                    $linea .= "18|";//Tributo: Porcentaje de IGV
                    /*Tributo ISC (2000)*/
                    $linea .= "-|";//Tributo ISC: Códigos de tipos de tributos ISC
                    $linea .= "|";//Tributo ISC: Monto de ISC por ítem
                    $linea .= "|";//Tributo ISC: Base Imponible ISC por Item
                    $linea .= "|";//Tributo ISC: Nombre de tributo por item
                    $linea .= "|";//Tributo ISC: Código de tipo de tributo por Item
                    $linea .= "|";//Tributo ISC: Tipo de sistema ISC
                    $linea .= "|";//Tributo ISC: Porcentaje de ISC
                    /*Tributo Otro 9999*/
                    $linea .= "-|";//Tributo Otro: Códigos de tipos de tributos OTRO
                    $linea .= "|";//Tributo Otro: Monto de tributo OTRO por iItem
                    $linea .= "|";//Tributo Otro: Base Imponible de tributo OTRO por Item
                    $linea .= "|";//Tributo Otro:  Nombre de tributo OTRO por item
                    $linea .= "|";//Tributo Otro: Código de tipo de tributo OTRO por Item
                    $linea .= "|";//Tributo Otro: Porcentaje de tributo OTRO por Item


                    //$linea .= ($value['precio_base']*1.18)."|";//Precio de venta unitario(base+igv)

                    if ($configuracion->pu_igv==1){
                        $linea .= round($value['importe'],2)."|";//Precio de venta unitario(base+igv)
                    }else{
                        $linea .= round($value['importe']*1.18,2)."|";//Precio de venta unitario(base+igv)
                    }
                    
                    //$linea .= round($value['subtotal']-$value['descuento'], 2)."|";//Valor de venta por Item
                    if(intval($value['tipo_igv_codigo']) >= 20)//gratuitas la base es 0
                    {
                       $linea .= round($value['total']-$value['descuento'], 2)."|";//Valor de venta por Item
                    }else{
                        if ($configuracion->pu_igv==1){
                            $linea .= round(($value['total']/1.18)-$value['descuento'], 2)."|";//Valor de venta por Item
                        }else{
                            $linea .= round($value['total']-$value['descuento'], 2)."|";//Valor de venta por Item
                        }
                    }
                    
                    $linea .= "0.00|\r\n";//Valor REFERENCIAL unitario (gratuitos) 
                    fwrite($f, $linea);
                }

                fclose($f);

                /*TRIBUTO NOTA DE CREDITO*/
                /*DOCUMENTO TRIBUTO*/
                $rut_tributo = $rutaArchivos . $comprobante['empresa_ruc'] . '-' . $comprobante['tipo_documento_codigo'] . '-' . $comprobante['serie'] . '-' . $comprobante['numero'] . '.TRI';
                $f = fopen($rut_tributo, 'w');
                //si tributo es igv
                if($comprobante['total_gravada'] > 0)
                {
                    $linea = "1000|";//Identificador de tributo
                    $linea .= "IGV|";//Nombre de tributo
                    $linea .= "VAT|";//Código de tipo de tributo
                    $linea .= "{$comprobante['total_gravada']}|";//Base imponible
                    $linea .= "{$comprobante['total_igv']}|\r\n";//Monto de Tirbuto por ítem
                    fwrite($f, $linea);                    
                }
                //si tributo es exonerada
                if($comprobante['total_exonerada'] > 0)
                {
                    $linea = "9997|";//Identificador de tributo
                    $linea .= "EXO|";//Nombre de tributo
                    $linea .= "VAT|";//Código de tipo de tributo
                    $linea .= "{$comprobante['total_exonerada']}|";//Base imponible
                    $linea .= "0|\r\n";//Monto de Tirbuto por ítem
                    fwrite($f, $linea);                    
                } 
                //si tributo es inafecto
                if($comprobante['total_inafecta'] > 0)
                {
                   if($comprobante1->tipo_operacion=='0101'){
                        $linea = "9998|";//Identificador de tributo
                        $linea .= "INA|";//Nombre de tributo
                        $linea .= "FRE|";//Código de tipo de tributo
                        $linea .= "{$comprobante['total_inafecta']}|";//Base imponible
                        $linea .= "0|\r\n";//Monto de Tirbuto por ítem
                        fwrite($f, $linea);   
                    }else{
                        $linea = "9995|";//Identificador de tributo
                        $linea .= "EXP|";//Nombre de tributo
                        $linea .= "FRE|";//Código de tipo de tributo
                        $linea .= "{$comprobante['total_gratuita']}|";//Base imponible
                        $linea .= "0|\r\n";//Monto de Tirbuto por ítem
                        fwrite($f, $linea); 
                    }                     
                }
                //si tributo es gratuita/exportacion
                if($comprobante['total_gratuita'] > 0)
                {
                    $linea = "9996|";//Identificador de tributo
                    $linea .= "GRA|";//Nombre de tributo
                    $linea .= "FRE|";//Código de tipo de tributo
                    $linea .= "{$comprobante['total_gratuita']}|";//Base imponible
                    $linea .= "0|\r\n";//Monto de Tirbuto por ítem
                    fwrite($f, $linea);                    
                }                                                

                fclose($f);
                /*DOCUMENTO LEYENDA*/
                $importe_letra = $num->num2letras(intval($comprobante['total_a_pagar']));
                $arrayImporte = explode(".",$comprobante['total_a_pagar']); 
                $montoLetras = $importe_letra.' con ' .$arrayImporte[1].'/100 '.$comprobante['moneda'];
                $rut_leyenda = $rutaArchivos . $comprobante['empresa_ruc'] . '-' . $comprobante['tipo_documento_codigo'] . '-' . $comprobante['serie'] . '-' . $comprobante['numero'] . '.LEY';
                $f = fopen($rut_leyenda, 'w'); 
                $linea = "1000|";//Código de leyenda
                $linea .= "{$montoLetras}|";//Descripción de leyenda    
                fwrite($f, $linea);
                fclose($f);                 

            }
            /*if ($comprobante['tipo_documento_id'] == 3) {
                $this->resumenes_model->guardarResumen($comprobante_id,1);
            }*/

            $this->comprobantes_model->modificar(array('enviado_sunat' => 1), $comprobante_id);
            $this->session->set_flashdata('mensaje', 'Envio exitoso!');
        } else {
            /*comunicar de baja a boletas*/
            if ($comprobante['tipo_documento_id'] == 3) {
                $this->resumenes_model->guardarResumen($comprobante_id,3);

                $fecha1 = date("Ymd");
                $fecha2 = date("Y-m-d");
                $dataAnular = array(
                    'fecha' => $fecha2,
                    'numero' => $numero,
                    'comprobante_id' => $comprobante_id,
                    'empleado_insert' => $this->session->userdata('empleado_id'),
                    'fecha_insert' => date("Y-m-d H:i:s")
                );
                $this->comprobante_anulados_model->insertar($dataAnular);
                $this->comprobantes_model->modificar(array('fecha_de_baja' => $fecha2, 'anulado' => 1), $comprobante_id);
            } else {
                //  COMUNICACION DE BAJA TXT
                $fecha1 = date("Ymd");
                $fecha2 = date("Y-m-d");
                $numero = $this->comprobante_anulados_model->maxNumero($fecha2) + 1;
                
                $f = fopen($rutaArchivos . $comprobante['empresa_ruc'] . '-RA-' . $fecha1 . '-' . $numero . '.CBA', 'w');
                
                $linea = (new DateTime($comprobante1->fecha_de_emision))->format('Y-m-d').'|';
                $linea .= (new DateTime())->format('Y-m-d')."|";
                $linea .= $comprobante['tipo_documento_codigo']."|";
                $linea .= $comprobante['serie'] . '-' .$comprobante['numero'].'|';
                $linea .= "anulacion|";
                fwrite($f, $linea);
                fclose($f);

                $dataAnular = array(
                    'fecha' => $fecha2,
                    'numero' => $numero,
                    'comprobante_id' => $comprobante_id,
                    'empleado_insert' => $this->session->userdata('empleado_id'),
                    'fecha_insert' => date("Y-m-d H:i:s")
                );
                $this->comprobante_anulados_model->insertar($dataAnular);
                $this->comprobantes_model->modificar(array('fecha_de_baja' => $fecha2, 'anulado' => 1), $comprobante_id);

               
                


                $this->session->set_flashdata('mensaje', 'Anulación exitosa!');
            }

            ////// STOCK 
                $this->db->select('i.*,c.serie,c.numero');
                $this->db->from('items as i');
                $this->db->join('comprobantes as c','c.id=i.comprobante_id');
                $this->db->where('i.comprobante_id',$comprobante_id);
                $items = $this->db->get()->result();
                foreach ($items as $i) {
                   // $this->quitarStock($i->producto_id,$i->cantidad);
                     $stock = $this->productos_model->getStockProductos($i->producto_id,$this->session->userdata("almacen_id"));
                     $nueva_cantidad = floatval($stock)+floatval($i->cantidad);

                     $kardex = array(
                      'k_fecha' => date('Y-m-d'),
                      'k_almacen' => $this->session->userdata("almacen_id"),
                      'k_tipo' => 2,
                      'k_operacion_id' => $comprobante_id,
                      'k_serie' => $i->serie.'-'.$i->numero,
                      'k_concepto' => 'Anulación de documento',     
                      'k_producto' => $i->producto_id,
                      'k_ecantidad' => $i->cantidad,
                      'k_excantidad' => $nueva_cantidad,
                                       
                     );

                     $this->db->insert('kardex', $kardex);
                }

            

            
        }
        //redirect(base_url() . "index.php/comprobantes/index/" . $comprobante['empresa_id']);
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


    

    public function tipoCodigoDeTributo($codigoIgv)
    {
        $codigoTributo = '';
        switch ($codigoIgv) {
            case '10':
                $codigoTiposTributo = [
                                        "codigo"              => "1000",
                                        "codigoInternacional" => "VAT",
                                        "nombre"              => "IGV" 
                                     ];
                break;
            case '20':
                $codigoTiposTributo = [
                                        "codigo"              => "9997",
                                        "codigoInternacional" => "VAT",
                                        "nombre"              => "EXO" 
                                     ];
                break;
            case '30':
                $codigoTiposTributo = [
                                        "codigo"              => "9998",
                                        "codigoInternacional" => "FRE",
                                        "nombre"              => "INA" 
                                     ];
                break;   
            case '40':
                $codigoTiposTributo = [
                                        "codigo"              => "9995",
                                        "codigoInternacional" => "FRE",
                                        "nombre"              => "EXP" 
                                     ];
                break;                   
            default:
                $codigoTiposTributo = [
                                        "codigo"              => "9996",
                                        "codigoInternacional" => "FRE",
                                        "nombre"              => "GRA" 
                                     ];
                break;
        }

        return $codigoTiposTributo;
    }
    
    public function mombreCodigoDeTributo($codigoIgv)
    {
        $codigoTributo = '';
        switch ($codigoIgv) {
            case '10':
                $codigoTributo = "1000";
                break;
            case '20':
                $codigoTributo = "9997";
                break;
            case '30':
                $codigoTributo = "9998";
                break;                
            default:
                $codigoTributo = "9996";
                break;
        }

        $nombreCodigoTributo  = '';
        switch ($codigoTributo) {
            case '1000':
                $nombreCodigoTributo = "IGV";
                break;
            case '9997':
                $nombreCodigoTributo = "EXO";
                break;
            case '9998':
                $nombreCodigoTributo = "INA";
                break;  
            case '9996':
                $nombreCodigoTributo = "GRA";
                break;                                
        }   

        return  $nombreCodigoTributo;            
    }
    public function comunicacionBaja() {

        $data['comprobante'] = $this->comprobantes_model->select('', '', '', '', '', '', '', '', 1);
        $this->accesos_model->menuGeneral();
        $this->load->view('comprobantes/comunicacionBaja', $data);
        $this->load->view('templates/footer');
    }

    public function estadoBaja($comprobante_id = '', $cliente_id = '') {
        $comprobante = $this->comprobantes_model->select($comprobante_id);
        $items = $this->items_model->select('', $comprobante_id);
        $cliente = $this->clientes_model->select($cliente_id);
        $empresa = $this->empresas_model->select($comprobante_id);

        //20110152711-RA-20161214-011.CBA            
        $fecha_de_baja = $comprobante['fecha_de_baja'];
        $date = new DateTime($fecha_de_baja);
        $fecha_de_baja = $date->format('Ymd');

        $fichero = 'http://190.107.181.252/webServiceSunat/xmlSunat.php?comprobante=' . $comprobante['empresa_ruc'] . '-RA-' . $fecha_de_baja . '-011' . '.xml';
        $obj = json_decode(file_get_contents($fichero), true);

        //header("Content-type: text/xml; charset=utf-8");
        header('Content-type: text/xml; content="text/html; charset=UTF-8"');
        echo $obj['contenido'];
    }

    public function tipoCambio() {
        $moneda_id = $this->input->post('moneda_id');

        $json = $this->tipo_cambio_model->selectJson($moneda_id);
        //var_dump($json);                                    
        echo json_encode($json);
    }


    public function selectUltimoReg() {        
        $row = $this->comprobantes_model->selecMaximoNumero2($this->uri->segment(3), $this->uri->segment(4), $this->uri->segment(5));            
        echo json_encode($row);
    }
    
    public function selectUltimoRegModificar(){
        if(($this->uri->segment(4) == $this->uri->segment(6)) && ($this->uri->segment(5) == $this->uri->segment(7))){
            $row['numero'] = $this->uri->segment(8);
        }else{
            $row = $this->comprobantes_model->selecMaximoNumero2($this->uri->segment(3), $this->uri->segment(4), $this->uri->segment(5));
            $row['numero'] = ($row['numero'] == null) ? 1 : ($row['numero'] + 1);
        }                        
        echo json_encode($row);
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

    function quitarSaltoDeLinea($cadenaDeTexto) {
        $buscar = array(chr(13) . chr(10), "\r\n", "\n", "\r");
        $reemplazar = array(" ", " ", " ", " ");
        $cadena = str_ireplace($buscar, $reemplazar, $cadenaDeTexto);
        return $cadena;
    }

    public function _exportarExcel() {   
        require_once (APPPATH .'libraries/Numletras.php');

        $campos = array();
        $campos = ( $this->uri->segment(3) == 'null') ? $campos : array_merge($campos, array('cliente_id' => $this->uri->segment(3)));
        $campos = ( $this->uri->segment(4) == 'null') ? $campos : array_merge($campos, array('tipo_documento_id' => $this->uri->segment(4)));
        $campos = ( $this->uri->segment(5) == 'null') ? $campos : array_merge($campos, array('fecha_de_emision' => "BETWEEN '" . format_fecha_0000_00_00($this->uri->segment(5)) . "' AND '" . format_fecha_0000_00_00($this->uri->segment(6)) . "'"));
        $campos = ( $this->uri->segment(7) == 'null') ? $campos : array_merge($campos, array('serie' => $this->uri->segment(7)));
        $campos = ( $this->uri->segment(8) == 'null') ? $campos : array_merge($campos, array('numero' => $this->uri->segment(8)));
        $campos = ( $this->uri->segment(9) == 'null') ? $campos : array_merge($campos, array('com.empresa_id' => $this->uri->segment(9)));        
        var_dump($campos);exit;

        //$comprobantes = $this->comprobantes_model->selectCustomizado(3, $campos);
        $comprobantes_items = $this->comprobantes_model->selectCustomizadoDetalle(3, $campos);
        $comprobantes = $this->comprobantes_model->FormatedSelectCustomizadoDetalle($comprobantes_items);

        $this->load->library('excel');

        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        define('EOL', (PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
        date_default_timezone_set('Europe/London');

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
                ->setLastModifiedBy("Maarten Balliauw")
                ->setTitle("Office 2007 XLSX Test Document")
                ->setSubject("Office 2007 XLSX Test Document")
                ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                ->setKeywords("office 2007 openxml php")
                ->setCategory("Test result file");

        
        
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', "Nr. Factura");
        $objPHPExcel->getActiveSheet()->setCellValue('B1', "Estado");
        $objPHPExcel->getActiveSheet()->setCellValue('C1', "Fecha de Emisión");
        $objPHPExcel->getActiveSheet()->setCellValue('D1', "Fecha de cancelación");
        $objPHPExcel->getActiveSheet()->setCellValue('E1', "RUC");
        $objPHPExcel->getActiveSheet()->setCellValue('F1', "RAZON SOCIAL");
        $objPHPExcel->getActiveSheet()->setCellValue('G1', "TRANSFERENCIA TITULO GRATUITO");
        $objPHPExcel->getActiveSheet()->setCellValue('H1', "Vta. Libros");
        $objPHPExcel->getActiveSheet()->setCellValue('I1', "Base Imponible");
        $objPHPExcel->getActiveSheet()->setCellValue('J1', "IGV");
        $objPHPExcel->getActiveSheet()->setCellValue('K1', "TOTAL");
        $objPHPExcel->getActiveSheet()->setCellValue('L1', "DETRACCION");
        $objPHPExcel->getActiveSheet()->setCellValue('M1', "NUMERO DE DETRACCIÓN");
        $objPHPExcel->getActiveSheet()->setCellValue('N1', "FECHA DE DETRACCIÓN");
        $objPHPExcel->getActiveSheet()->setCellValue('O1', "TIPO DE CAMBIO");
        $objPHPExcel->getActiveSheet()->setCellValue('P1', "CONCEPTO");
        $objPHPExcel->getActiveSheet()->setCellValue('Q1', "MONEDA");
        $objPHPExcel->getActiveSheet()->setCellValue('R1', "Monto en letras");

        //MONTO EN LETRAS
        $num = new Numletras();
        for ($i = 2; $i <= (count($comprobantes) + 1); $i++) {            
            $fecha_de_emision = str_replace("-", "/", $comprobantes[$i - 2]['fecha_de_emision']);
            
            $importe_letra = $num->num2letras(intval($comprobantes[$i - 2]['total_a_pagar']));                        
            $cad = number_format($comprobantes[$i - 2]['total_a_pagar'], 2, ".", ",");
            $lon = strlen($cad);
            for ($ii = $lon; $ii > 0; $ii--) {
                $let = substr($cad, $ii, 1);
                if ($let == ".") {
                    $dec_tot = substr($cad, ($ii + 1), ($lon - $ii - 1));
                    $ii = 0;
                }
            }
            $montoLetras = $importe_letra.' con ' .$dec_tot.'/100 '.$comprobantes[$i - 2]['moneda'];
            
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $comprobantes[$i - 2]['serie'] . ' - ' . $comprobantes[$i - 2]['numero'])
                    ->setCellValue('B' . $i, '')
                    ->setCellValue('C' . $i, $fecha_de_emision)
                    ->setCellValue('D' . $i, '')
                    ->setCellValue('E' . $i, $comprobantes[$i - 2]['cli_ruc'])
                    ->setCellValue('F' . $i, $comprobantes[$i - 2]['cli_razon_social'])
                    ->setCellValue('G' . $i, '')
                    ->setCellValue('H' . $i, '')
                    ->setCellValue('I' . $i, $comprobantes[$i - 2]['total_gravada'])
                    ->setCellValue('J' . $i, $comprobantes[$i - 2]['total_igv'])
                    ->setCellValue('K' . $i, $comprobantes[$i - 2]['total_a_pagar'])
                    ->setCellValue('L' . $i, $comprobantes[$i - 2]['total_detraccion'])
                    ->setCellValue('M' . $i, '')
                    ->setCellValue('N' . $i, '')                                        
                    ->setCellValue('O' . $i, $comprobantes[$i - 2]['tipo_de_cambio'])
                    ->setCellValue('P' . $i, $comprobantes[$i - 2]['descripcion'])
                    ->setCellValue('Q' . $i, strtoupper(substr($comprobantes[$i - 2]['moneda'], 0, 1)))
                    ->setCellValue('R' . $i, $montoLetras)
            ;
        }
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////     

        $filename = 'comprobantes---' . date("d-m-Y") . '---' . rand(1000, 9999) . '.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }
    public function exist_xml($comprobante_id) {
       $rsComprobante = $this->db->select("comp.serie as serie,comp.numero as numero, tdoc.codigo as codigo, emp.ruc as empresa_ruc")
                                  ->from("comprobantes as comp")
                                  ->join("tipo_documentos as tdoc", "comp.tipo_documento_id=tdoc.id")
                                  ->join("empresas as emp", "comp.empresa_id=emp.id")
                                  ->where("comp.id", $comprobante_id)
                                  ->get()
                                  ->row();

        $archivoXML = "{$rsComprobante->empresa_ruc}-{$rsComprobante->codigo}-{$rsComprobante->serie}-{$rsComprobante->numero}.xml";

        $rutaFirma = DISCO.':\xampp\htdocs'.CARPETA."/".SFS."/sunat_archivos/sfs/PARSE/{$archivoXML}";

        if (file_exists($rutaFirma)) {
            return true;
        
        } else {
            return false;
        }
    }
    public function get_xml($comprobante_id)
    {
        $rsComprobante = $this->db->select("comp.serie as serie,comp.numero as numero, tdoc.codigo as codigo, emp.ruc as empresa_ruc")
                                  ->from("comprobantes as comp")
                                  ->join("tipo_documentos as tdoc", "comp.tipo_documento_id=tdoc.id")
                                  ->join("empresas as emp", "comp.empresa_id=emp.id")
                                  ->where("comp.id", $comprobante_id)
                                  ->get()
                                  ->row();


        $archivoXML = "{$rsComprobante->empresa_ruc}-{$rsComprobante->codigo}-{$rsComprobante->serie}-{$rsComprobante->numero}.xml";

        $rutaFirma = RUTA_API."index.php/Sunat/get_xml/{$rsComprobante->empresa_ruc}/{$rsComprobante->codigo}/{$rsComprobante->serie}/{$rsComprobante->numero}";
        

        //echo $rutaFirma;exit;
        //echo file_get_contents($rutaFirma);exit;

        $contenido = file_get_contents($rutaFirma);                
        $bytes = file_put_contents(APPPATH.'files_xml/comprobantes/'.$archivoXML, $contenido);
        return true;
        //return file_get_contents($rutaFirma);        
    }

    public function get_cdr($comprobante_id) {
        /*datos de la empresa*/
        $this->db->from("empresas")
                 ->where("id",1);
        $query = $this->db->get();
        $rsEmpresa = $query->row();
        /*obtenemos todos los datos del documento*/
        $this->db->from("comprobantes as com")
                 ->join("tipo_documentos as tdoc", "com.tipo_documento_id=tdoc.id")
                 ->join("clientes as cli", "com.cliente_id=cli.id")
                 ->join("tipo_clientes as tp", "cli.tipo_cliente_id=tp.id")
                 ->join("monedas as tmon", "com.moneda_id=tmon.id")
                 ->where("com.id",$comprobante_id);         
        $query = $this->db->get();
        $rsComprobante = $query->row();
        
        $rsTipoDocumento = $this->db->from('tipo_documentos')
                                    ->where('id',$rsComprobante->tipo_documento_id)
                                    ->get()
                                    ->row();
        /*obtenemos el detalle del documento*/
        $this->db->from("items")
                 ->where("comprobante_id", $comprobante_id);
        $query = $this->db->get();
        $rsDetalle = $query->result();
        
        $archivoXML = "R{$rsEmpresa->ruc}-{$rsTipoDocumento->codigo}-{$rsComprobante->serie}-{$rsComprobante->numero}.zip";
        //$rutaFirma = "D:/".SFS."/sunat_archivos/sfs/FIRMA/aaa.xml";
        $rutaFirma = DISCO.':\xampp\htdocs'.CARPETA."/".SFS."/sunat_archivos/sfs/RPTA/{$archivoXML}";
        if (file_exists($rutaFirma)) {
            //echo "El fichero $nombre_fichero existe :" .$rutaFirma;
            //header('Content-type: application/xml');
            
            return $rutaFirma;

           
        
        } else {            
            return false;
            //sendJsonData(['status'=>STATUS_FAIL,'msg'=>'el xml no existe']);
        }
    }

    public function exportarExcel()
    {
        require_once (APPPATH .'libraries/Numletras.php');

        $configuracion = $this->db->from('comprobantes_ventas')->get()->row();        
        if($this->uri->segment(3)!='null')
        {
            $this->db->where('com.cliente_id', $this->uri->segment(3));
        }  
        if($this->uri->segment(4)!='null')
        {
            $this->db->where('com.tipo_documento_id', $this->uri->segment(4));
        }        
        if($this->uri->segment(5)!='null') {
            $fechaDesde = (new DateTime($this->uri->segment(5)))->format("Y-m-d");          
            $this->db->where('DATE_FORMAT(com.fecha_de_emision, "%Y-%m-%d") >= ', $fechaDesde);
        } 
        if($this->uri->segment(6)!='null')
        {
            $fechaHasta = (new DateTime($this->uri->segment(6)))->format("Y-m-d");
            $this->db->where('DATE_FORMAT(com.fecha_de_emision, "%Y-%m-%d") <= ', $fechaHasta);
        }    
        if($this->uri->segment(7)!='null')
        {
            $this->db->where('com.serie', $this->uri->segment(7));
        }         
        if($this->uri->segment(8)!='null')
        {
            $this->db->where('com.numero', $this->uri->segment(8));
        } 
        if($this->uri->segment(13)!='null')
        {
            $this->db->where('com.empleado_select', $this->uri->segment(13));
        } 

        $this->db->where('com.venta_almacen_id', $this->session->userdata('almacen_id'));
        $resultComprobantes = $this->db->from("comprobantes com")                                                                           
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

        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->getActiveSheet()->setTitle('COMPROBANTES');

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(20);

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
        $objPHPExcel->getActiveSheet()->getStyle("N1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("O1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("P1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("Q1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("R1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("S1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("T1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("U1")->getFont()->setBold(true);
        

        $objPHPExcel->getActiveSheet()->setCellValue('A1', "TIPO COMPROBANTE"); 
        $objPHPExcel->getActiveSheet()->setCellValue('B1', "SERIE DOC.");
        $objPHPExcel->getActiveSheet()->setCellValue('C1', "CORRELATIVO");      
        $objPHPExcel->getActiveSheet()->setCellValue('D1', "F.EMISIÓN");
        $objPHPExcel->getActiveSheet()->setCellValue('E1', "F.VENCIMIENTO");
        $objPHPExcel->getActiveSheet()->setCellValue('F1', "TIPO DOC.");
        $objPHPExcel->getActiveSheet()->setCellValue('G1', "NUMERO DOC.");
        $objPHPExcel->getActiveSheet()->setCellValue('H1', "CLIENTE"); 
        $objPHPExcel->getActiveSheet()->setCellValue('I1', "MONEDA");        
        $objPHPExcel->getActiveSheet()->setCellValue('J1', "BASE IMPONIBLE");
        $objPHPExcel->getActiveSheet()->setCellValue('K1', "IGV");
        $objPHPExcel->getActiveSheet()->setCellValue('L1', "IMPORTE TOTAL");        
        $objPHPExcel->getActiveSheet()->setCellValue('M1', "ESTADO SUNAT");
        $objPHPExcel->getActiveSheet()->setCellValue('N1', "METODO DE PAGO");
        $objPHPExcel->getActiveSheet()->setCellValue('O1', "USUARIO");
        $objPHPExcel->getActiveSheet()->setCellValue('P1', "UTILIDAD");
        $objPHPExcel->getActiveSheet()->setCellValue('Q1', "TRANSPORTISTA");
        $objPHPExcel->getActiveSheet()->setCellValue('R1', "PLACA");
        $objPHPExcel->getActiveSheet()->setCellValue('S1', "ORDEN DE COMPRA");
        $objPHPExcel->getActiveSheet()->setCellValue('T1', "ALMACEN");
        $objPHPExcel->getActiveSheet()->setCellValue('U1', "OBSERVACION");
        
        /*cargamos datos al excel*/
        $i=2;

        //print_r($resultComprobantes);
        //die();
        $suma_total = 0; 
        foreach($resultComprobantes as $index => $item)
        {
            /*datos cliente*/
            $this->db->where('id', $item->cliente_id);
            $this->db->from('clientes');
            $queryCliente = $this->db->get();
            $rsCliente = $queryCliente->row();

            /*datos tipo de documento*/
            $this->db->where('id', $item->tipo_documento_id);
            $this->db->from('tipo_documentos');
            $queryDocumento = $this->db->get();
            $rsTipoDocumento = $queryDocumento->row();
            /*datos empresa*/ 
            $this->db->where('id', $item->empresa_id);
            $this->db->from('empresas');
            $queryEmpresa = $this->db->get();
            $rsEmpresa = $queryEmpresa->row(); 
            /*datos moneda*/ 
            $this->db->where('id', $item->moneda_id);
            $this->db->from('monedas');
            $queryMoneda = $this->db->get();
            $rsMoneda = $queryMoneda->row(); 

            /*datos empleado*/ 
            $this->db->where('id', $item->empleado_select);
            $this->db->from('empleados');
            $queryEmpleado = $this->db->get();
            $rsEmpleado = $queryEmpleado->row();

            //ALEXANDER FERNANDEZ 01-11-2020
            $rsTransportista = $this->db->from('transportistas')
                                        ->where('transp_id', $item->transportista_id)
                                        ->get()
                                        ->row();            
            
            //ALEXANDER FERNANDEZ 01-11-2020
            $rsPagos = $this->db->select('tpg.tipo_pago')
                               ->from('comprobante_pagos cmp')
                               ->join('tipo_pagos tpg','tpg.id = cmp.tipo_pago_id')
                               ->where('cmp.comprobante_id', $item->id)
                               ->get()
                               ->result();
                               //var_dump($rsPago);exit;
            $rsPago = '';
            foreach ($rsPagos as  $value) {
                $rsPago = $rsPago.','.$value->tipo_pago;
            } 

            //ALEXANDER FERNANDEZ 01-11-2020
            $rsUtilidad =  $this->db->select('SUM(totalVenta) totalVenta ,SUM(totalCosto) totalCosto')
                                    ->from('items')
                                    ->where('comprobante_id',$item->id)
                                    ->group_by('comprobante_id')
                                    ->get()
                                    ->row();

            $fechaEmision = new DateTime($item->fecha_de_emision);
            $fechaVencimiento = new DateTime($item->fecha_de_vencimiento);    

            if ($item->anulado == 1) {
                $estado='ANULADO/RECHAZADO';
                $item->total_gravada = 0.00;
                $item->total_igv = 0.00;
                $item->total_a_pagar = 0.00;
                $rsUtilidad->totalVenta = 0.00;
                $rsUtilidad->totalCosto = 0.00;
            } else if($item->estado_sunat == 1){
                $estado = 'NO ENVIADO';
            } else if($item->estado_sunat == 3){
                $estado = 'ENVIADO SUNAT';
            } 

            // tipo documento cliente
            $tipo_dcli = 'DNI';
            if (strlen($rsCliente->ruc)>8) {
                $tipo_dcli = 'RUC';
            }
            $num = $i-1;

            $objPHPExcel->getActiveSheet()
                        ->setCellValue('A' . ($i), $rsTipoDocumento->tipo_documento)
                        ->setCellValue('B' . ($i), $item->serie)
                        ->setCellValue('C' . ($i), $item->numero)                     
                        ->setCellValue('D' . ($i), $fechaEmision->format('d-m-Y'))
                        ->setCellValue('E' . ($i), $fechaVencimiento->format('d-m-Y'))
                        ->setCellValue('F' . ($i), $tipo_dcli)
                        ->setCellValue('G' . ($i), $rsCliente->ruc)
                        ->setCellValue('H' . ($i), $rsCliente->razon_social)
                        ->setCellValue('I' . ($i), $rsMoneda->moneda)
                        ->setCellValue('J' . ($i), $item->total_gravada)
                        ->setCellValue('K' . ($i), $item->total_igv)
                        ->setCellValue('L' . ($i), $item->total_a_pagar)                        
                        ->setCellValue('M' . ($i), $estado)
                        ->setCellValue('N' . ($i), $rsPago)
                        ->setCellValue('O' . ($i), $rsEmpleado->nombre.' '.$rsEmpleado->apellido_paterno)
                        ->setCellValue('P' . ($i), $rsUtilidad->totalVenta - $rsUtilidad->totalCosto)
                        ->setCellValue('Q' . ($i), $rsTransportista->transp_nombre)
                        ->setCellValue('R' . ($i), $rsCliente->placa)
                        ->setCellValue('S' . ($i), $item->orden_compra)
                        ->setCellValue('T' . ($i), $this->session->userdata('almacen_nom'))
                        ->setCellValue('U' . ($i), $item->notas);               

              
            if($item->tipo_documento_id==7 or $item->tipo_documento_id==9){
                $suma_total = $suma_total - $item->total_a_pagar;
            }else{
                $suma_total = $suma_total + $item->total_a_pagar;
            }  
            
            $i++;

        }
        $objPHPExcel->getActiveSheet()->getStyle('K' . ($i +1 ))->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->setCellValue('K' . ($i +1 ), 'MONTO TOTAL');
        $objPHPExcel->getActiveSheet()->setCellValue('L' . ($i +1 ), $suma_total);


        $filename = 'Reporte_Comprobantes_' . date("d-m-Y") . '---' . rand(1000, 9999) . '.xls'; //save our workbook as this file name
        ob_end_clean();
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    } 


    public function exportarExcel_rd()
    {
        require_once (APPPATH .'libraries/Numletras.php');
        $configuracion = $this->db->from('comprobantes_ventas')->get()->row();        
        if($this->uri->segment(3)!='null')
        {
            $this->db->where('com.cliente_id', $this->uri->segment(3));
        }  
        if($this->uri->segment(4)!='null')
        {
          $this->db->where('com.tipo_documento_id', $this->uri->segment(4));
        }        
        if($this->uri->segment(5)!='null') {
            $fechaDesde = (new DateTime($this->uri->segment(5)))->format("Y-m-d");
            $this->db->where('DATE_FORMAT(com.fecha_de_emision, "%Y-%m-%d") >= ', $fechaDesde);
        } 
        if($this->uri->segment(6)!='null')
        {
            $fechaHasta = (new DateTime($this->uri->segment(6)))->format("Y-m-d");
            $this->db->where('DATE_FORMAT(com.fecha_de_emision, "%Y-%m-%d") <= ', $fechaHasta);
        }    
        if($this->uri->segment(7)!='null')
        {
            $this->db->where('com.serie', $this->uri->segment(7));
        }         
        if($this->uri->segment(8)!='null')
        {
            $this->db->where('com.numero', $this->uri->segment(8));
        }
        if($this->uri->segment(13)!='null')
        {
            $this->db->where('com.empleado_select', $this->uri->segment(13));
        }

        $this->db->where('com.venta_almacen_id', $this->session->userdata('almacen_id'));
        $resultComprobantes = $this->db->from("items i")
                                       ->join("comprobantes com","com.id=i.comprobante_id")
                                       ->join("productos pro","pro.prod_id=i.producto_id","left")
                                       ->join("categoria c","i.categoria_id=c.cat_id")
                                       ->join("medida m","i.unidad_id=m.medida_id")
                                       //->where("pro.prod_estado", ST_ACTIVO)
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

        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->getActiveSheet()->setTitle('COMPROBANTES');

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(20);

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
        $objPHPExcel->getActiveSheet()->getStyle("N1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("O1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("P1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("Q1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("R1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("S1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("T1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("U1")->getFont()->setBold(true);
        

        $objPHPExcel->getActiveSheet()->setCellValue('A1', "CORRELATIVO");      
        $objPHPExcel->getActiveSheet()->setCellValue('B1', "F.EMISIÓN");
        $objPHPExcel->getActiveSheet()->setCellValue('C1', "F.VENCIMIENTO");
        $objPHPExcel->getActiveSheet()->setCellValue('D1', "TIPO DOC.");
        $objPHPExcel->getActiveSheet()->setCellValue('E1', "NUMERO DOC.");
        $objPHPExcel->getActiveSheet()->setCellValue('F1', "CLIENTE");             
        $objPHPExcel->getActiveSheet()->setCellValue('G1', "CODIGO");
        $objPHPExcel->getActiveSheet()->setCellValue('H1', "CATEGORIA");
        $objPHPExcel->getActiveSheet()->setCellValue('I1', "UNIDAD/MEDIDA");
        $objPHPExcel->getActiveSheet()->setCellValue('J1', "DESCRIPCION");
        $objPHPExcel->getActiveSheet()->setCellValue('K1', "PRECIO UNITARIO");
        $objPHPExcel->getActiveSheet()->setCellValue('L1', "CANTIDAD");        
        $objPHPExcel->getActiveSheet()->setCellValue('M1', "IMPORTE TOTAL");        
        $objPHPExcel->getActiveSheet()->setCellValue('N1', "METODO DE PAGO");
        $objPHPExcel->getActiveSheet()->setCellValue('O1', "USUARIO");
        $objPHPExcel->getActiveSheet()->setCellValue('P1', "UTILIDAD");
        $objPHPExcel->getActiveSheet()->setCellValue('Q1', "TRANSPORTISTA");
        $objPHPExcel->getActiveSheet()->setCellValue('R1', "PLACA");
        $objPHPExcel->getActiveSheet()->setCellValue('S1', "ORDEN DE COMPRA");
        $objPHPExcel->getActiveSheet()->setCellValue('T1', "ALMACEN");
        $objPHPExcel->getActiveSheet()->setCellValue('U1', "OBSERVACION");
        
        /*cargamos datos al excel*/
        $i=2;

        //print_r($resultComprobantes);
        //die();
        $suma_total = 0; 
        foreach($resultComprobantes as $index => $item)
        {
            /*datos cliente*/
            $this->db->where('id', $item->cliente_id);
            $this->db->from('clientes');
            $queryCliente = $this->db->get();
            $rsCliente = $queryCliente->row();

            /*datos tipo de documento*/
            $this->db->where('id', $item->tipo_documento_id);
            $this->db->from('tipo_documentos');
            $queryDocumento = $this->db->get();
            $rsTipoDocumento = $queryDocumento->row();
            /*datos empresa*/ 
            $this->db->where('id', $item->empresa_id);
            $this->db->from('empresas');
            $queryEmpresa = $this->db->get();
            $rsEmpresa = $queryEmpresa->row(); 
            /*datos moneda*/ 
            $this->db->where('id', $item->moneda_id);
            $this->db->from('monedas');
            $queryMoneda = $this->db->get();
            $rsMoneda = $queryMoneda->row(); 

            /*datos empleado*/ 
            $this->db->where('id', $item->empleado_select);
            $this->db->from('empleados');
            $queryEmpleado = $this->db->get();
            $rsEmpleado = $queryEmpleado->row();

            //ALEXANDER FERNANDEZ 01-11-2020
            $rsTransportista = $this->db->from('transportistas')
                                        ->where('transp_id', $item->transportista_id)
                                        ->get()
                                        ->row();            
            
            //ALEXANDER FERNANDEZ 01-11-2020
            $rsPagos = $this->db->select('tpg.tipo_pago')
                               ->from('comprobante_pagos cmp')
                               ->join('tipo_pagos tpg','tpg.id = cmp.tipo_pago_id')
                               ->where('cmp.comprobante_id', $item->comprobante_id)
                               ->get()
                               ->result();
                               //var_dump($rsPago);exit;
            $rsPago = '';
            foreach ($rsPagos as  $value) {
                $rsPago = $rsPago.','.$value->tipo_pago;
            } 

            //ALEXANDER FERNANDEZ 01-11-2020
            $rsUtilidad =  $this->db->select('SUM(totalVenta) totalVenta ,SUM(totalCosto) totalCosto')
                                    ->from('items')
                                    ->where('comprobante_id',$item->comprobante_id)
                                    ->group_by('comprobante_id')
                                    ->get()
                                    ->row();

            $fechaEmision = new DateTime($item->fecha_de_emision);
            $fechaVencimiento = new DateTime($item->fecha_de_vencimiento);    

            if ($item->anulado == 1) {
                $estado='ANULADO/RECHAZADO';
                $item->total = 0.00;
                //$item->total_igv = 0.00;
                //$item->total_a_pagar = 0.00;
                $rsUtilidad->totalVenta = 0.00;
                $rsUtilidad->totalCosto = 0.00;
            } else if($item->estado_sunat == 1){
                $estado = 'NO ENVIADO';
            } else if($item->estado_sunat == 3){
                $estado = 'ENVIADO SUNAT';
            } 

            // tipo documento cliente
            $tipo_dcli = 'DNI';
            if (strlen($rsCliente->ruc)>8) {
                $tipo_dcli = 'RUC';
            }
            $num = $i-1;

            $objPHPExcel->getActiveSheet()                        
                        ->setCellValue('A' . ($i), $item->serie.'-'.$item->numero)
                        ->setCellValue('B' . ($i), $fechaEmision->format('d-m-Y'))
                        ->setCellValue('C' . ($i), $fechaVencimiento->format('d-m-Y'))
                        ->setCellValue('D' . ($i), $tipo_dcli)
                        ->setCellValue('E' . ($i), $rsCliente->ruc)
                        ->setCellValue('F' . ($i), $rsCliente->razon_social)                        
                        ->setCellValue('G' . ($i), $item->prod_codigo)
                        ->setCellValue('H' . ($i), $item->cat_nombre)
                        ->setCellValue('I' . ($i), $item->medida_nombre)
                        ->setCellValue('J' . ($i), $item->descripcion)
                        ->setCellValue('K' . ($i), $item->importe)
                        ->setCellValue('L' . ($i), $item->cantidad)                                                                                    
                        ->setCellValue('M' . ($i), $item->total)                                                
                        ->setCellValue('N' . ($i), $rsPago)
                        ->setCellValue('O' . ($i), $rsEmpleado->nombre.' '.$rsEmpleado->apellido_paterno)
                        ->setCellValue('P' . ($i), $rsUtilidad->totalVenta - $rsUtilidad->totalCosto)
                        ->setCellValue('Q' . ($i), $rsTransportista->transp_nombre)
                        ->setCellValue('R' . ($i), $rsCliente->placa)
                        ->setCellValue('S' . ($i), $item->orden_compra)
                        ->setCellValue('T' . ($i), $this->session->userdata('almacen_nom'))
                        ->setCellValue('U' . ($i), $item->notas);                          

              
            if($item->tipo_documento_id==7 or $item->tipo_documento_id==9){
                $suma_total = $suma_total - $item->total;
            }else{
                $suma_total = $suma_total + $item->total;
            }              
            $i++;
        }
         $objPHPExcel->getActiveSheet()->getStyle('L' . ($i +1 ))->getFont()->setBold(true);
         $objPHPExcel->getActiveSheet()->setCellValue('L' . ($i +1 ), 'MONTO TOTAL');
         $objPHPExcel->getActiveSheet()->setCellValue('M' . ($i +1 ), $suma_total);
        

        $filename = 'Reporte_Comprobantes_' . date("d-m-Y") . '---' . rand(1000, 9999) . '.xls'; //save our workbook as this file name
        ob_end_clean();
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    } 



    public function exportarExcel_mes() {

        $this->db->where('tipo_empleado_id',20);
        $this->db->where('estado',2);
        $vendedores = $this->db->get("empleados")->result();

                 
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
        $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A1', 'VENDEDORES')
                ->setCellValue('B1', 'ENE')
                ->setCellValue('C1', 'FEB')
                ->setCellValue('D1', 'MAR')
                ->setCellValue('E1', 'ABR')
                ->setCellValue('F1', 'MAY')
                ->setCellValue('G1', 'JUN')
                ->setCellValue('H1', 'JUL')
                ->setCellValue('I1', 'AGO')
                ->setCellValue('J1', 'SEP')
                ->setCellValue('K1', 'OCT')
                ->setCellValue('L1', 'NOV')
                ->setCellValue('M1', 'DIC');
                
                
        $spreadsheet->getActiveSheet()->setTitle('Ventas x Mes');

        $mes['1'] = "B";
        $mes['2'] = "C";
        $mes['3'] = "D";
        $mes['4'] = "E";
        $mes['5'] = "F";
        $mes['6'] = "G";
        $mes['7'] = "H";
        $mes['8'] = "I";
        $mes['9'] = "J";
        $mes['10'] = "K";
        $mes['11'] = "L";
        $mes['12'] = "M";

     
           
           
            
        foreach ($vendedores as $ven) {
            
            $spreadsheet->getActiveSheet()->setCellValue('A'.$i, $ven->nombre." ".$ven->apellido_paterno);

            for($b=1;$b<=12;$b++){
                $this->db->from("comprobantes");
                $this->db->where("anulado", 0);
                $this->db->where("tipo_documento_id !=",7);
                $this->db->where('MONTH(fecha_de_emision)',$b);
                $this->db->where('empleado_select',$ven->id);
                $this->db->select_sum('total_a_pagar');
                $res = $this->db->get()->row();

                $this->db->from("comprobantes");
                $this->db->where("tipo_documento_id",7);
                $this->db->where('MONTH(fecha_de_emision)',$b);
                $this->db->where('empleado_select',$ven->id);
                $this->db->select_sum('total_a_pagar');
                $res_nc = $this->db->get()->row();

                $res_total = $res->total_a_pagar - $res_nc->total_a_pagar;

                $spreadsheet->getActiveSheet()->setCellValue($mes[$b].$i,$res_total);
              
            }

       }


        $nombre = "vendedores_x_mes_".date('Y');
        
        $spreadsheet->setActiveSheetIndex(0);         
        // Redirect output to a client's web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$nombre.'.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');       
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
   }  

    public function cargarComprobantesCliente() {
        //$com_adjuntos  quiere decir q mandara boletas y facturas. 1 manda, '' no manda
        $com_adjuntos = 1;
        $anulado = 0; //comprobantes anulados por SUNAT; 0 no estan anulados.
        $empresa_id = $this->uri->segment(3);
        $cliente_id = $this->uri->segment(4);
        $comprobantes = $this->comprobantes_model->select('', '', '', '', '', $cliente_id, '', $com_adjuntos, $anulado = '', FALSE, FALSE, $empresa_id);
        echo '<option value="">Seleccionar Comprobantes</option>';
        foreach ($comprobantes as $value) {
            $selected = ($value['comprobante_id'] == $this->uri->segment(4)) ? 'SELECTED' : '';
            echo '<option ' . $selected . ' value="' . $value['comprobante_id'] . '">' . $value['serie'] . '-' . $value['numero'] . '</option>';
        }
    }
    
    //Carga comprobantes de clientes para Notas de credito.
    public function comprobantesNotasCredito() {
        //$com_adjuntos  quiere decir q mandara boletas y facturas. 1 manda, '' no manda
        $anulado = 0; //comprobantes anulados por SUNAT; 0 no estan anulados.
        $empresa_id = $this->uri->segment(3);
        $cliente_id = $this->uri->segment(4);        
        $tipo_documento_id = (substr($this->uri->segment(5), 0, 1) == "F") ? 1 : 3;
        $doc_adjunto = $this->uri->segment(6);     
        $comprobantes = $this->comprobantes_model->select('', '', '', '', '', $cliente_id, $tipo_documento_id, '', $anulado = '', FALSE, FALSE, $empresa_id);
        //$comprobantes = $this->comprobantes_model->select_nc('', '', '', '', '', $cliente_id, $tipo_documento_id, '', $anulado = '', FALSE, FALSE, $empresa_id);
        //print_r($comprobantes);exit;
    
        echo '<option value="">Seleccionar Comprobantes</option>';
        foreach ($comprobantes as $value) {
            //$selected = ($value['comprobante_id'] == $this->uri->segment(4)) ? 'SELECTED' : '';
            $selected = ($value['comprobante_id'] == $this->uri->segment(6)) ? 'SELECTED' : '';
            echo '<option ' . $selected . ' value="' . $value['comprobante_id'] . '"  >' . $value['serie'] . '-' . $value['numero'] . '</option>';
        }
    }

    public function selectRutaCDR($empresa_id, $ruc_emisor, $tipo_documento, $serie, $numero) {
        $nombre_fichero = "files/facturacion_electronica/CDR/" . $ruc_emisor . "-" . $tipo_documento . "-" . $serie . "-" . $numero . ".xml";
        if (file_exists($nombre_fichero)) {
            return $nombre_fichero;
        } else {
            $this->crearDocumentoCDR($empresa_id, $ruc_emisor, $tipo_documento, $serie, $numero);
            return $nombre_fichero;
        }
    }
    
    public function selectCDR($empresa_id, $ruc_emisor, $tipo_documento, $serie, $numero) {
        $nombre_fichero = "files/facturacion_electronica/CDR/" . $ruc_emisor . "-" . $tipo_documento . "-" . $serie . "-" . $numero . ".xml";

        if (file_exists($nombre_fichero)) {
            redirect(base_url() . $nombre_fichero);
        } else {
            $this->crearDocumentoCDR($empresa_id, $ruc_emisor, $tipo_documento, $serie, $numero);
            redirect(base_url() . $nombre_fichero);
        }
    }

    /* ///tipo documento segun SUNAT:
      01      Factura          F
      03      Boleta de Venta  B
      07      Nota de Credito  NC
      08      Nota de Debito   ND
     */

    public function crearDocumentoCDR($empresa_id, $ruc_emisor, $tipo_documento, $serie, $numero) {
        $fichero = "http://" . $this->config->item('servidor_sunat_windows') . "/webServiceSunat/response/response_cdrSunat.php?empresa_id=" . $empresa_id . "&comprobante=" . $ruc_emisor . "-" . $tipo_documento . "-" . $serie . "-" . $numero . ".xml";
        $obj = json_decode(file_get_contents($fichero), true);

        $datos = $obj['datos'];
        $nombre_comprobante = "./files/facturacion_electronica/CDR/" . $obj['nombre_comprobante'];
        crearFileBinary($nombre_comprobante, $datos);
    }
    
    //fecha con este formato: 0000-00-00
    public function tipoCambioFechaJson($tipo_moneda, $fecha){
        $data_cambio = $this->tipo_cambio_model->selectFechaJson($tipo_moneda, $fecha);
        echo json_encode($data_cambio['tipo_cambio']);
    }
    
    //fecha con este formato: 0000-00-00
    public function tipoCambioFecha($tipo_moneda, $fecha){
        $data_cambio = $this->tipo_cambio_model->selectFechaJson($tipo_moneda, $fecha);
        return $data_cambio['tipo_cambio'];
    }

    public function buscar_guia(){
        $guia = $this->input->get('guia');

        $this->db->where('CONCAT(guia_serie,"-",guia_numero)',$guia);
        $this->db->where('estado',2);
        $res = $this->db->get('guias')->row();

        if($res->numero_factura!=''){
            $this->db->where('notap_correlativo',$res->numero_factura);
            $res = $this->db->get('adelanto_pedido')->row();

            $this->db->where('id',$res->notap_cliente_id);
            $cliente = $this->db->get('clientes')->row();

            $this->db->where('notapd_notap_id',$res->notap_id);
            $detalle = $this->db->get('adelanto_pedido_detalle')->result();

            $adelanto = 1;
        }else{

            $res->notap_cliente_direccion = $res->llegada_direccion;
            $this->db->where('ruc',$res->destinatario_ruc);
            $cliente = $this->db->get('clientes')->row();

            $this->db->from('guia_detalles as g');
            $this->db->join('productos as p','p.prod_id=g.producto_id');
            $this->db->join('medida as m','m.medida_id=p.prod_medida_id');
            $this->db->where('g.guia_id',$res->id);
            $detalles = $this->db->get()->result();
             
            $detalle = []; 
            foreach ($detalles as $d) {

               $this->db->from('productos as p');
               $this->db->join('medida as m','m.medida_id=p.prod_medida_id');
               $this->db->where('p.prod_id',$d->producto_id);
               $prod = $this->db->get()->row();

               $objeto = new stdClass();
               $objeto->notapd_descripcion = $d->descripcion;
               $objeto->notapd_producto_id = $d->producto_id;
               $objeto->notapd_cantidad = $d->cantidad;
               $objeto->notapd_precio_unitario = $d->precio;
               $objeto->notapd_subtotal = $d->cantidad*$d->precio;
               $objeto->notapd_medida = $d->medida_nombre;

               array_push($detalle,$objeto);
            }

            $adelanto = 0;
                
        }
                    
        $data['doc'] = $res;
        $data['cli'] = $cliente;
        $data['det'] = $detalle;
        $data['ade'] = $adelanto;

        echo json_encode($data);
    }

    public function buscar_cliente(){
        $texto = $this->input->get('texto');
        $query = "((ruc like '%".$texto."%' AND eliminado_cliente=0) or (razon_social like '%".$texto."%' AND eliminado_cliente=0))";
        $this->db->where($query);
        $clientes = $this->db->get('clientes')->result();

        echo json_encode($clientes);

    }

    public function seleccionar_cliente(){
        $cliente_id = $this->input->get('cliente_id');
        $this->db->where('id',$cliente_id);
        $cliente = $this->db->get('clientes')->row();


        echo json_encode($cliente);

    }

    public function obtener_documento_relacionado(){
        $id = $this->input->get('id');
        $this->db->from('items as i');
        $this->db->join('medida as m','m.medida_id=i.unidad_id');
        $this->db->where('i.comprobante_id',$id);
        $items = $this->db->get()->result();
        echo json_encode($items);

    }

    public function init_dashboard(){
        $almacen = $this->input->get('almacen');
        $moneda = $this->input->get('moneda');
         $response = [];

        ////VENTA DE HOY
        $doc = array(7,9);
        $this->db->select_sum('total_a_pagar');
        $this->db->where('venta_almacen_id',$almacen);
        $this->db->where('moneda_id',$moneda);
        $this->db->where('DATE(fecha_de_emision)',date('Y-m-d'));        
        $this->db->where_not_in('tipo_documento_id',$doc);
        $this->db->where('anulado',0); 
        $com1 = $this->db->get('comprobantes')->row(); 

        $this->db->select_sum('total_a_pagar');
        $this->db->where('venta_almacen_id',$almacen);
        $this->db->where('moneda_id',$moneda);   
        $this->db->where('DATE(fecha_de_emision)',date('Y-m-d'));      
        $this->db->where_in('tipo_documento_id',$doc);
        $this->db->where('anulado',0); 
        $com2 = $this->db->get('comprobantes')->row();

        $this->db->select_sum('notap_total');
        $this->db->where('notap_almacen',$almacen);
        $this->db->where('notap_moneda_id',$moneda);   
        $this->db->where('DATE(notap_fecha)',date('Y-m-d'));      
        $this->db->where('notap_estado',1); 
        $com3 = $this->db->get('nota_pedido')->row();
        $response['a'] = number_format($com1->total_a_pagar+$com3->notap_total-$com2->total_a_pagar,2);

        /////VENTA MES
        $this->db->select_sum('total_a_pagar');
        $this->db->where('venta_almacen_id',$almacen);
        $this->db->where('moneda_id',$moneda);
        $this->db->where('MONTH(fecha_de_emision)',date('m'));        
        $this->db->where_not_in('tipo_documento_id',$doc);
        $this->db->where('anulado',0); 
        $com1 = $this->db->get('comprobantes')->row(); 
    
        $this->db->select_sum('total_a_pagar');
        $this->db->where('venta_almacen_id',$almacen);
        $this->db->where('moneda_id',$moneda);   
        $this->db->where('MONTH(fecha_de_emision)',date('m'));       
        $this->db->where_in('tipo_documento_id',$doc);
        $this->db->where('anulado',0); 
        $com2 = $this->db->get('comprobantes')->row();
 
        $this->db->select_sum('notap_total');
        $this->db->where('notap_almacen',$almacen);
        $this->db->where('notap_moneda_id',$moneda);   
        $this->db->where('MONTH(notap_fecha)',date('m'));      
        $this->db->where('notap_estado',1); 
        $com3 = $this->db->get('nota_pedido')->row(); 
        $response['b'] = number_format($com1->total_a_pagar+$com3->notap_total-$com2->total_a_pagar,2);

        /////COMPRA MES
        $this->db->select_sum('total_a_pagar');
        $this->db->where('venta_almacen_id',$almacen);
        $this->db->where('moneda_id',$moneda);
        $this->db->where('MONTH(fecha_de_emision)',date('m'));        
        $this->db->where_not_in('tipo_documento_id',$doc);
        $this->db->where('anulado',0); 
        $com1 = $this->db->get('comprobantes_compras')->row(); 

        $this->db->select_sum('total_a_pagar');
        $this->db->where('venta_almacen_id',$almacen);
        $this->db->where('moneda_id',$moneda);   
        $this->db->where('MONTH(fecha_de_emision)',date('m'));       
        $this->db->where_in('tipo_documento_id',$doc);
        $this->db->where('anulado',0); 
        $com2 = $this->db->get('comprobantes_compras')->row();
        $response['c'] = number_format($com1->total_a_pagar-$com2->total_a_pagar,2);

        ////TOTAL CLIENTES
        $rsClientes =  $this->db->from('clientes')
                                ->where('eliminado_cliente',0)
                                ->get()
                                ->result();        
        $cli = count($rsClientes); 
        $response['d'] = $cli;

        ////SIMBOLO MONEDA
        $this->db->where('id',$moneda);
        $mon = $this->db->get('monedas')->row();
        $response['moneda'] = $mon->simbolo;

        ////RESUMEN DE COMPRAS Y VENTAS
        $mes_nombre['1'] = "ENE";
        $mes_nombre['2'] = "FEB";
        $mes_nombre['3'] = "MAR";
        $mes_nombre['4'] = "ABR";
        $mes_nombre['5'] = "MAY";
        $mes_nombre['6'] = "JUN";
        $mes_nombre['7'] = "JUL";
        $mes_nombre['8'] = "AGO";
        $mes_nombre['9'] = "SEP";
        $mes_nombre['10'] = "OCT";
        $mes_nombre['11'] = "NOV";
        $mes_nombre['12'] = "DIC";
        $mes = intval(date('m')-1);
        $mes_final = $mes - 2;
        $ventas = [];
        $compras = [];
        $productos = [];
        $meses = [];

        array_push($ventas,$response['b']);
        array_push($compras,$response['c']);
        array_push($meses,$mes_nombre[$mes+1]);

        while($mes>=$mes_final){    
            /////VENTAS
            $this->db->select_sum('total_a_pagar');
            $this->db->where('venta_almacen_id',$almacen);
            $this->db->where('moneda_id',$moneda);
            $this->db->where('MONTH(fecha_de_emision)',$mes);        
            $this->db->where_not_in('tipo_documento_id',$doc);
            $this->db->where('anulado',0); 
            $com1 = $this->db->get('comprobantes')->row(); 

            $this->db->select_sum('total_a_pagar');
            $this->db->where('venta_almacen_id',$almacen);
            $this->db->where('moneda_id',$moneda);   
            $this->db->where('MONTH(fecha_de_emision)',$mes);      
            $this->db->where_in('tipo_documento_id',$doc);
            $this->db->where('anulado',0); 
            $com2 = $this->db->get('comprobantes')->row();

            $this->db->select_sum('notap_total');
            $this->db->where('notap_almacen',$almacen);
            $this->db->where('notap_moneda_id',$moneda);   
            $this->db->where('MONTH(notap_fecha)',$mes);      
            $this->db->where('notap_estado',1); 
            $com3 = $this->db->get('nota_pedido')->row();

            $resv = $com1->total_a_pagar+$com3->notap_total-$com2->total_a_pagar;   
            array_push($ventas,$resv);

            /////COMPRAS
            $this->db->select_sum('total_a_pagar');
            $this->db->where('venta_almacen_id',$almacen);
            $this->db->where('moneda_id',$moneda);
            $this->db->where('MONTH(fecha_de_emision)',$mes);        
            $this->db->where_not_in('tipo_documento_id',$doc);
            $this->db->where('anulado',0); 
            $com1 = $this->db->get('comprobantes_compras')->row(); 

            $this->db->select_sum('total_a_pagar');
            $this->db->where('venta_almacen_id',$almacen);
            $this->db->where('moneda_id',$moneda);   
            $this->db->where('MONTH(fecha_de_emision)',$mes);       
            $this->db->where_in('tipo_documento_id',$doc);
            $this->db->where('anulado',0); 
            $com2 = $this->db->get('comprobantes_compras')->row();

            $resc = $com1->total_a_pagar-$com2->total_a_pagar;
            array_push($compras,$resc);

            ////PRODUCTOS + VENDIDOS
            /*$this->db->from('items as i');
            $this->db->join('comprobantes as c','c.id=i.comprobante_id');
            $this->db->where('DATE(c.fecha_de_emision)',$mes);
            $this->db->select('c.fecha_de_emision,i.producto_id,i.cantidad');
            $this->db->group_by('i.producto_id');
            $pro = count($this->db->get()->result()); */
 
            /*$query =   "select i.producto_id,sum(i.cantidad) from items as i 
                        inner join comprobantes as c ON c.id=i.comprobante_id 
                        WHERE MONTH(c.fecha_de_emision)=".$mes."
                        GROUP BY i.producto_id ORDER BY sum(i.cantidad) DESC";


            array_push($productos,$pro);*/

            array_push($meses,$mes_nombre[$mes]);
            $mes--;
            
        }

        $response['grafico_ventas'] = $ventas;
        $response['grafico_compras'] = $compras;
        $response['grafico_meses'] = $meses;
        $response['grafico_productos'] = $productos;

        
        
        echo json_encode($response);

    }

    public function getDatosXML()
    {        
        //TRAER IGV, ICBPER ACTIVO
        $igv =  $this->igv_model->selectIgvActivo()->valor;
        $icbPer =  $this->icbper_model->selectIcbPerActivo()->icbPer_valor;        

        $id = $this->input->get('comprobante_id'); 
        $configuracion = $this->db->from('comprobantes_ventas')->get()->row(); 
        $empresa = $this->db->get('empresas')->row();        

        //////DATOS EMPRESA
        $datos['empresa']['ruc'] = $empresa->ruc;
        $datos['empresa']['user'] = $empresa->user;
        $datos['empresa']['pass'] = $empresa->pass;
        $datos['empresa']['razon_social'] = $empresa->empresa;
        $datos['empresa']['nombre_comercial'] = $empresa->nombre_comercial;
        $datos['empresa']['direccion'] = $empresa->domicilio_fiscal;
        $datos['empresa']['departamento'] = $empresa->departamento;
        $datos['empresa']['provincia'] = $empresa->provincia;
        $datos['empresa']['distrito'] = $empresa->distrito;
        $datos['empresa']['ubigeo'] = $empresa->ubigeo;
        $datos['empresa']['urbanizacion'] = $empresa->urb;

        $this->db->select('c.*,cli.ruc,cli.razon_social,d.codigo,m.abrstandar,m.moneda,clit.codigo as tip_doc_cli');
        $this->db->from('comprobantes as c');
        $this->db->join('clientes as cli','cli.id=c.cliente_id');
        $this->db->join('tipo_clientes as clit','clit.id=cli.tipo_cliente_id');
        $this->db->join('tipo_documentos as d','d.id=c.tipo_documento_id');
        $this->db->join('monedas as m','m.id=c.moneda_id');
        $this->db->where('c.id',$id);
        $comprobante = $this->db->get()->row();

        /////DATOS CLIENTE/CABECERA
        $datos['cliente']['ruc'] = $comprobante->ruc;
        $datos['cliente']['razon_social'] = $comprobante->razon_social;
        $datos['cliente']['tipo_documento'] = $comprobante->tip_doc_cli;

        $datos['cabecera']['tipo_operacion'] = $comprobante->tipo_operacion;
        $datos['cabecera']['tipo_documento'] = $comprobante->codigo;
        $datos['cabecera']['serie'] = $comprobante->serie;
        $datos['cabecera']['numero'] = $comprobante->numero;
        $datos['cabecera']['fecha_emision'] = $comprobante->fecha_de_emision;
        $datos['cabecera']['tipo_moneda'] = $comprobante->abrstandar;
        $datos['cabecera']['exoneradas'] = $comprobante->total_exonerada;
        $datos['cabecera']['gravadas'] = $comprobante->total_gravada;
        $datos['cabecera']['inafectas'] = $comprobante->total_inafecta;
        $datos['cabecera']['igv'] = $comprobante->total_igv;
        $datos['cabecera']['icbper'] = $comprobante->total_icbper;
        $datos['cabecera']['impuestos'] = $comprobante->total_igv+$comprobante->total_icbper;
        //$datos['cabecera']['valor_venta'] = $comprobante->total_a_pagar - $comprobante->total_igv;
        $datos['cabecera']['importe_venta'] = $comprobante->total_a_pagar;

        /////DATOS NOTA CREDITO/DEBITO  
        if($comprobante->codigo == '07' or $comprobante->codigo == '08'){
            $this->db->select('c.*,d.codigo');
            $this->db->from('comprobantes as c');
            $this->db->join('tipo_documentos as d','d.id=c.tipo_documento_id');
            $this->db->where('c.id',$comprobante->com_adjunto_id);
            $adjunto = $this->db->get()->row();

            if($comprobante->codigo == '07'){
                $this->db->where('codigo',$comprobante->tipo_nota_codigo);
                $des_motivo = ($this->db->get('tipo_ncreditos')->row())->tipo_ncredito;
            }else{
                $this->db->where('codigo',$comprobante->tipo_nota_codigo);
                $des_motivo = ($this->db->get('tipo_ndebitos')->row())->tipo_ndebito;
            }

            $datos['cabecera']['cod_motivo'] = $comprobante->tipo_nota_codigo;
            $datos['cabecera']['des_motivo'] = $des_motivo;
            $datos['adjunto']['tipo_documento'] = $adjunto->codigo;
            $datos['adjunto']['serie'] = $adjunto->serie;
            $datos['adjunto']['numero'] = $adjunto->numero;
        
        }

        $this->db->select('i.*,p.prod_codigo,m.medida_codigo_unidad,g.codigo,p.prod_codigo_sunat');
        $this->db->from('items as i');
        $this->db->join('productos as p','p.prod_id=i.producto_id', 'left');
        $this->db->join('medida as m','m.medida_id=i.unidad_id');
        $this->db->join('tipo_igv as g','g.id=i.tipo_igv_id');
        $this->db->where('i.comprobante_id',$id);
        $items = $this->db->get()->result();
        
        ////DATOS ITEMS 
            $datos['detalle'] = [];
            foreach($items as $i){
                $datos_item['sunat'] = $i->prod_codigo_sunat;
                $datos_item['codigo'] = ($i->prod_codigo=='')?"000000000":$i->prod_codigo;
                $datos_item['unidad'] = $i->medida_codigo_unidad;
                $datos_item['cantidad'] = $i->cantidad;
                $datos_item['descripcion'] = $i->descripcion;
                $datos_item['tipo_igv'] = $i->codigo;
                $datos_item['igv'] = (intval($i->codigo)>=20)?0:round($i->total-($i->total/(1+$igv)),2);
                $datos_item['base'] = (intval($i->codigo)>=20)?$i->total:round(($i->total/(1+$igv)),2);
                $datos_item['porcentaje_igv'] = $igv*100;
                $datos_item['impuestos'] = (intval($i->codigo)>=20)?0:$datos_item['igv'];
                $datos_item['valor_venta'] = (intval($i->codigo)>=20)?$i->total:$datos_item['base'];
                $datos_item['valor_unitario'] = (intval($i->codigo)>=20)?round(($i->total/$i->cantidad),2):round(($datos_item['base']/$i->cantidad),2);
                $datos_item['precio_unitario'] = round(($i->total/$i->cantidad),2);

                ////ICBPER
                $datos_item['icbper'] = ($i->producto_id == 1)?"on":"off";
                $datos_item['valor_icbper'] = $icbPer;
                $datos_item['igv_icbper'] = $i->cantidad*$icbPer;

                array_push($datos['detalle'],$datos_item);
            } 

        ////LEYENDA 
        require_once (APPPATH .'libraries/Numletras.php');
        $num = new Numletras();
        $importe_letra = $num->num2letras(intval($comprobante->total_a_pagar));
        $arrayImporte = explode(".",$comprobante->total_a_pagar); 
        $montoLetras = $importe_letra.' con ' .$arrayImporte[1].'/100 '.$comprobante->moneda;
        $datos['letra'] = $montoLetras;        
       
        echo json_encode($datos);
    }

    public function getDatosAnulacion()
    {
        $opc = $this->input->get('opc');  
        $id = $this->input->get('comprobante_id'); 
        $configuracion = $this->db->from('comprobantes_ventas')->get()->row(); 
        $empresa = $this->db->get('empresas')->row();


        //DEVOLVER STOCK 03-11-2020        
        $rsProductos = $this->db->from('items ite')
                                   ->join('comprobantes com','com.id = ite.comprobante_id')
                                   ->where('ite.comprobante_id',$id)
                                   ->get()
                                   ->result();

        foreach ($rsProductos as $rsProducto) {
            $this->productos_model->ingresarStock($rsProducto->producto_id ,$rsProducto->cantidad, $concepto = 'RESTAURAR STOCK',$comprobante,$rsProducto->serie,$rsProducto->numero);
        }

        //////DATOS EMPRESA
        $datos['empresa']['ruc'] = $empresa->ruc;
        $datos['empresa']['user'] = $empresa->user;
        $datos['empresa']['pass'] = $empresa->pass;
        $datos['empresa']['razon_social'] = $empresa->empresa;
        $datos['empresa']['nombre_comercial'] = $empresa->nombre_comercial;
        $datos['empresa']['direccion'] = $empresa->domicilio_fiscal;
        $datos['empresa']['departamento'] = $empresa->departamento;
        $datos['empresa']['provincia'] = $empresa->provincia;
        $datos['empresa']['distrito'] = $empresa->distrito;
        $datos['empresa']['ubigeo'] = $empresa->ubigeo;
        $datos['empresa']['urbanizacion'] = $empresa->urb;

        if($opc == 2){
            $resumen = $this->resumenes_model->getListComprobanteResumen($id);
            $datos['resumenes'] = [];
            $datos['correlativo_resumen'] = str_pad($resumen[0]->correlativo, 3,'0',STR_PAD_LEFT); 
            foreach ($resumen as $value) {
                $this->db->select('c.*,cli.ruc,cli.razon_social,d.codigo,m.abrstandar,m.moneda,clit.codigo as tip_doc_cli');
                $this->db->from('comprobantes as c');
                $this->db->join('clientes as cli','cli.id=c.cliente_id');
                $this->db->join('tipo_clientes as clit','clit.id=cli.tipo_cliente_id');
                $this->db->join('tipo_documentos as d','d.id=c.tipo_documento_id');
                $this->db->join('monedas as m','m.id=c.moneda_id');
                $this->db->where('c.id',$value->comprobante_id);
                $comprobante = $this->db->get()->row();

                $datos_resumen['ruc_cliente'] = $comprobante->ruc;
                $datos_resumen['razon_social_cliente'] = $comprobante->razon_social;
                $datos_resumen['tipo_documento_cliente'] = $comprobante->tip_doc_cli;

                $datos_resumen['tipo_operacion'] = $comprobante->tipo_operacion;
                $datos_resumen['tipo_documento'] = $comprobante->codigo;
                $datos_resumen['serie'] = $comprobante->serie;
                $datos_resumen['numero'] = $comprobante->numero;
                $datos_resumen['fecha_emision'] = $comprobante->fecha_de_emision;
                $datos_resumen['tipo_moneda'] = $comprobante->abrstandar;
                $datos_resumen['gravadas'] = $comprobante->total_gravada;
                $datos_resumen['impuestos'] = $comprobante->total_igv;
                $datos_resumen['valor_venta'] = $comprobante->total_a_pagar - $comprobante->total_igv;
                $datos_resumen['importe_venta'] = $comprobante->total_a_pagar;

                array_push($datos['resumenes'],$datos_resumen);
            }

            $datos['resumen'] = 1; 
            echo json_encode($datos); 
            exit();

        }

        $this->db->select('c.*,cli.ruc,cli.razon_social,d.codigo,m.abrstandar,m.moneda,clit.codigo as tip_doc_cli');
        $this->db->from('comprobantes as c');
        $this->db->join('clientes as cli','cli.id=c.cliente_id');
        $this->db->join('tipo_clientes as clit','clit.id=cli.tipo_cliente_id');
        $this->db->join('tipo_documentos as d','d.id=c.tipo_documento_id');
        $this->db->join('monedas as m','m.id=c.moneda_id');
        $this->db->where('c.id',$id);
        $comprobante = $this->db->get()->row();

        /////DATOS CLIENTE/CABECERA
        $datos['cliente']['ruc'] = $comprobante->ruc;
        $datos['cliente']['razon_social'] = $comprobante->razon_social;
        $datos['cliente']['tipo_documento'] = $comprobante->tip_doc_cli;

        $datos['cabecera']['tipo_operacion'] = $comprobante->tipo_operacion;
        $datos['cabecera']['tipo_documento'] = $comprobante->codigo;
        $datos['cabecera']['serie'] = $comprobante->serie;
        $datos['cabecera']['numero'] = $comprobante->numero;
        $datos['cabecera']['fecha_emision'] = $comprobante->fecha_de_emision;
        $datos['cabecera']['tipo_moneda'] = $comprobante->abrstandar;
        $datos['cabecera']['gravadas'] = $comprobante->total_gravada;
        $datos['cabecera']['impuestos'] = $comprobante->total_igv;
        $datos['cabecera']['valor_venta'] = $comprobante->total_a_pagar - $comprobante->total_igv;
        $datos['cabecera']['importe_venta'] = $comprobante->total_a_pagar;

        if($comprobante->codigo == '07' or $comprobante->codigo == '08'){
            $this->db->select('c.*,d.codigo');
            $this->db->from('comprobantes as c');
            $this->db->join('tipo_documentos as d','d.id=c.tipo_documento_id');
            $this->db->where('c.id',$comprobante->com_adjunto_id);
            $adjunto = $this->db->get()->row();

            if($comprobante->codigo == '07'){
                $this->db->where('codigo',$comprobante->tipo_nota_codigo);
                $des_motivo = ($this->db->get('tipo_ncreditos')->row())->tipo_ncredito;
            }else{
                $this->db->where('codigo',$comprobante->tipo_nota_codigo);
                $des_motivo = ($this->db->get('tipo_ndebitos')->row())->tipo_ndebito;
            }

            $datos['cabecera']['cod_motivo'] = $comprobante->tipo_nota_codigo;
            $datos['cabecera']['des_motivo'] = $des_motivo;
            $datos['adjunto']['tipo_documento'] = $adjunto->codigo;
            $datos['adjunto']['serie'] = $adjunto->serie;
            $datos['adjunto']['numero'] = $adjunto->numero;
        
        }

        if($comprobante->codigo == '03' /*OR ($comprobante->codigo == '08' AND $adjunto->codigo == '03') OR ($comprobante->codigo == '07' AND $adjunto->codigo == '03')*/){
          $this->resumenes_model->guardarResumen($id,3);
          $datos['go'] = 0;
          echo json_encode($datos); 
          exit();
        }else{
          $fecha = date("Y-m-d");
          $numero = $this->comprobante_anulados_model->maxNumero($fecha) + 1;
          $datos['anulado']['numero'] = $numero;
          $datos['go'] = 1;
          $datos['resumen'] = 0;
        }
        echo json_encode($datos); 
    }    

    public function getDatosAnulacionPASSWORD()
    {
        $opc = $this->input->get('opc');  
        $id = $this->input->get('comprobante_id');
        $password = $this->input->get('password');

        $rsPassword = $this->db->from('comprobantes_ventas')
                               ->where('textPasswordDelete',$password)
                               ->get()
                               ->row();


        if(empty($rsPassword)){//PASSWORD INVALIDO 15-02-2021
            $datos['password'] = -1; 
            echo json_encode($datos); 
            exit();
        } else{

        $configuracion = $this->db->from('comprobantes_ventas')->get()->row(); 
        $empresa = $this->db->get('empresas')->row();

        //DEVOLVER STOCK 03-11-2020        
        $rsProductos = $this->db->from('items ite')
                                   ->join('comprobantes com','com.id = ite.comprobante_id')
                                   ->where('ite.comprobante_id',$id)
                                   ->get()
                                   ->result();

        foreach ($rsProductos as $rsProducto) {
            $this->productos_model->ingresarStock($rsProducto->producto_id ,$rsProducto->cantidad, $concepto = 'RESTAURAR STOCK',$comprobante,$rsProducto->serie,$rsProducto->numero);
        }

        //////DATOS EMPRESA
        $datos['empresa']['ruc'] = $empresa->ruc;
        $datos['empresa']['user'] = $empresa->user;
        $datos['empresa']['pass'] = $empresa->pass;
        $datos['empresa']['razon_social'] = $empresa->empresa;
        $datos['empresa']['nombre_comercial'] = $empresa->nombre_comercial;
        $datos['empresa']['direccion'] = $empresa->domicilio_fiscal;
        $datos['empresa']['departamento'] = $empresa->departamento;
        $datos['empresa']['provincia'] = $empresa->provincia;
        $datos['empresa']['distrito'] = $empresa->distrito;
        $datos['empresa']['ubigeo'] = $empresa->ubigeo;
        $datos['empresa']['urbanizacion'] = $empresa->urb;

        if($opc == 2){
            $resumen = $this->resumenes_model->getListComprobanteResumen($id);
            $datos['resumenes'] = [];
            $datos['correlativo_resumen'] = str_pad($resumen[0]->correlativo, 3,'0',STR_PAD_LEFT); 
            foreach ($resumen as $value) {
                $this->db->select('c.*,cli.ruc,cli.razon_social,d.codigo,m.abrstandar,m.moneda,clit.codigo as tip_doc_cli');
                $this->db->from('comprobantes as c');
                $this->db->join('clientes as cli','cli.id=c.cliente_id');
                $this->db->join('tipo_clientes as clit','clit.id=cli.tipo_cliente_id');
                $this->db->join('tipo_documentos as d','d.id=c.tipo_documento_id');
                $this->db->join('monedas as m','m.id=c.moneda_id');
                $this->db->where('c.id',$value->comprobante_id);
                $comprobante = $this->db->get()->row();

                $datos_resumen['ruc_cliente'] = $comprobante->ruc;
                $datos_resumen['razon_social_cliente'] = $comprobante->razon_social;
                $datos_resumen['tipo_documento_cliente'] = $comprobante->tip_doc_cli;

                $datos_resumen['tipo_operacion'] = $comprobante->tipo_operacion;
                $datos_resumen['tipo_documento'] = $comprobante->codigo;
                $datos_resumen['serie'] = $comprobante->serie;
                $datos_resumen['numero'] = $comprobante->numero;
                $datos_resumen['fecha_emision'] = $comprobante->fecha_de_emision;
                $datos_resumen['tipo_moneda'] = $comprobante->abrstandar;
                $datos_resumen['gravadas'] = $comprobante->total_gravada;
                $datos_resumen['impuestos'] = $comprobante->total_igv;
                $datos_resumen['valor_venta'] = $comprobante->total_a_pagar - $comprobante->total_igv;
                $datos_resumen['importe_venta'] = $comprobante->total_a_pagar;

                array_push($datos['resumenes'],$datos_resumen);
            }

            $datos['resumen'] = 1; 
            echo json_encode($datos); 
            exit();

        }

        $this->db->select('c.*,cli.ruc,cli.razon_social,d.codigo,m.abrstandar,m.moneda,clit.codigo as tip_doc_cli');
        $this->db->from('comprobantes as c');
        $this->db->join('clientes as cli','cli.id=c.cliente_id');
        $this->db->join('tipo_clientes as clit','clit.id=cli.tipo_cliente_id');
        $this->db->join('tipo_documentos as d','d.id=c.tipo_documento_id');
        $this->db->join('monedas as m','m.id=c.moneda_id');
        $this->db->where('c.id',$id);
        $comprobante = $this->db->get()->row();

        /////DATOS CLIENTE/CABECERA
        $datos['cliente']['ruc'] = $comprobante->ruc;
        $datos['cliente']['razon_social'] = $comprobante->razon_social;
        $datos['cliente']['tipo_documento'] = $comprobante->tip_doc_cli;

        $datos['cabecera']['tipo_operacion'] = $comprobante->tipo_operacion;
        $datos['cabecera']['tipo_documento'] = $comprobante->codigo;
        $datos['cabecera']['serie'] = $comprobante->serie;
        $datos['cabecera']['numero'] = $comprobante->numero;
        $datos['cabecera']['fecha_emision'] = $comprobante->fecha_de_emision;
        $datos['cabecera']['tipo_moneda'] = $comprobante->abrstandar;
        $datos['cabecera']['gravadas'] = $comprobante->total_gravada;
        $datos['cabecera']['impuestos'] = $comprobante->total_igv;
        $datos['cabecera']['valor_venta'] = $comprobante->total_a_pagar - $comprobante->total_igv;
        $datos['cabecera']['importe_venta'] = $comprobante->total_a_pagar;

        if($comprobante->codigo == '07' or $comprobante->codigo == '08'){
            $this->db->select('c.*,d.codigo');
            $this->db->from('comprobantes as c');
            $this->db->join('tipo_documentos as d','d.id=c.tipo_documento_id');
            $this->db->where('c.id',$comprobante->com_adjunto_id);
            $adjunto = $this->db->get()->row();

            if($comprobante->codigo == '07'){
                $this->db->where('codigo',$comprobante->tipo_nota_codigo);
                $des_motivo = ($this->db->get('tipo_ncreditos')->row())->tipo_ncredito;
            }else{
                $this->db->where('codigo',$comprobante->tipo_nota_codigo);
                $des_motivo = ($this->db->get('tipo_ndebitos')->row())->tipo_ndebito;
            }

            $datos['cabecera']['cod_motivo'] = $comprobante->tipo_nota_codigo;
            $datos['cabecera']['des_motivo'] = $des_motivo;
            $datos['adjunto']['tipo_documento'] = $adjunto->codigo;
            $datos['adjunto']['serie'] = $adjunto->serie;
            $datos['adjunto']['numero'] = $adjunto->numero;
        
        }

        if($comprobante->codigo == '03' /*OR ($comprobante->codigo == '08' AND $adjunto->codigo == '03') OR ($comprobante->codigo == '07' AND $adjunto->codigo == '03')*/){
          $this->resumenes_model->guardarResumen($id,3);
          $datos['go'] = 0;
          echo json_encode($datos); 
          exit();
        }else{
          $fecha = date("Y-m-d");
          $numero = $this->comprobante_anulados_model->maxNumero($fecha) + 1;
          $datos['anulado']['numero'] = $numero;
          $datos['go'] = 1;
          $datos['resumen'] = 0;
        }

            echo json_encode($datos); 
        }        
    }

    public function getDatosSunat()
    {       
        //TRAER IGV ACTIVO
        $igv =  $this->igv_model->selectIgvActivo()->valor;

        $id = $this->input->get('comprobante_id'); 
        $opc = $this->input->get('opc'); 
        $configuracion = $this->db->from('comprobantes_ventas')->get()->row(); 
        $empresa = $this->db->get('empresas')->row();
        
        $datos['config']['opc'] = $opc;
        $datos['config']['id'] = $id;
        $datos['config']['go'] = 1;

        $datos['empresa']['ruc'] = $empresa->ruc;
        $datos['empresa']['user'] = $empresa->user;
        $datos['empresa']['pass'] = $empresa->pass;
        $datos['empresa']['razon_social'] = $empresa->empresa;
        $datos['empresa']['nombre_comercial'] = $empresa->nombre_comercial;
        $datos['empresa']['direccion'] = $empresa->domicilio_fiscal;
        $datos['empresa']['departamento'] = $empresa->departamento;
        $datos['empresa']['provincia'] = $empresa->provincia;
        $datos['empresa']['distrito'] = $empresa->distrito;
        $datos['empresa']['ubigeo'] = $empresa->ubigeo;
        $datos['empresa']['urbanizacion'] = $empresa->urb;

        if($opc == 3){
            $resumen = $this->resumenes_model->getListComprobanteResumen($id);
            $datos['resumenes'] = [];
            $datos['correlativo_resumen'] = str_pad($resumen[0]->correlativo, 3,'0',STR_PAD_LEFT); 
            foreach ($resumen as $value) {
                $this->db->select('c.*,cli.ruc,cli.razon_social,d.codigo,m.abrstandar,m.moneda,clit.codigo as tip_doc_cli');
                $this->db->from('comprobantes as c');
                $this->db->join('clientes as cli','cli.id=c.cliente_id');
                $this->db->join('tipo_clientes as clit','clit.id=cli.tipo_cliente_id');
                $this->db->join('tipo_documentos as d','d.id=c.tipo_documento_id');
                $this->db->join('monedas as m','m.id=c.moneda_id');
                $this->db->where('c.id',$value->comprobante_id);
                $comprobante = $this->db->get()->row();

                $datos_resumen['ruc_cliente'] = $comprobante->ruc;
                $datos_resumen['razon_social_cliente'] = $comprobante->razon_social;
                $datos_resumen['tipo_documento_cliente'] = $comprobante->tip_doc_cli;

                $datos_resumen['tipo_operacion'] = $comprobante->tipo_operacion;
                $datos_resumen['tipo_documento'] = $comprobante->codigo;
                $datos_resumen['serie'] = $comprobante->serie;
                $datos_resumen['numero'] = $comprobante->numero;
                $datos_resumen['fecha_emision'] = $comprobante->fecha_de_emision;
                $datos_resumen['tipo_moneda'] = $comprobante->abrstandar;
                $datos_resumen['gravadas'] = $comprobante->total_gravada;
                $datos_resumen['impuestos'] = $comprobante->total_igv;
                $datos_resumen['valor_venta'] = $comprobante->total_a_pagar - $comprobante->total_igv;
                $datos_resumen['importe_venta'] = $comprobante->total_a_pagar;

                array_push($datos['resumenes'],$datos_resumen);
            }


            echo json_encode($datos); 
            exit();

        }
        
        $this->db->select('c.*,cli.ruc,cli.razon_social,d.codigo,m.abrstandar,m.moneda,clit.codigo as tip_doc_cli');
        $this->db->from('comprobantes as c');
        $this->db->join('clientes as cli','cli.id=c.cliente_id');
        $this->db->join('tipo_clientes as clit','clit.id=cli.tipo_cliente_id');
        $this->db->join('tipo_documentos as d','d.id=c.tipo_documento_id');
        $this->db->join('monedas as m','m.id=c.moneda_id');
        $this->db->where('c.id',$id);
        $comprobante = $this->db->get()->row();

        $datos['cliente']['ruc'] = $comprobante->ruc;
        $datos['cliente']['razon_social'] = $comprobante->razon_social;
        $datos['cliente']['tipo_documento'] = $comprobante->tip_doc_cli;

        $datos['cabecera']['tipo_operacion'] = $comprobante->tipo_operacion;
        $datos['cabecera']['tipo_documento'] = $comprobante->codigo;
        $datos['cabecera']['serie'] = $comprobante->serie;
        $datos['cabecera']['numero'] = $comprobante->numero;
        $datos['cabecera']['fecha_emision'] = $comprobante->fecha_de_emision;
        $datos['cabecera']['tipo_moneda'] = $comprobante->abrstandar;
        $datos['cabecera']['gravadas'] = $comprobante->total_gravada;
        $datos['cabecera']['impuestos'] = $comprobante->total_igv;
        $datos['cabecera']['valor_venta'] = $comprobante->total_a_pagar - $comprobante->total_igv;
        $datos['cabecera']['importe_venta'] = $comprobante->total_a_pagar;

        if($comprobante->codigo == '07' or $comprobante->codigo == '08'){
            $this->db->select('c.*,d.codigo');
            $this->db->from('comprobantes as c');
            $this->db->join('tipo_documentos as d','d.id=c.tipo_documento_id');
            $this->db->where('c.id',$comprobante->com_adjunto_id);
            $adjunto = $this->db->get()->row();

            if($comprobante->codigo == '07'){
                $this->db->where('codigo',$comprobante->tipo_nota_codigo);
                $des_motivo = ($this->db->get('tipo_ncreditos')->row())->tipo_ncredito;
            }else{
                $this->db->where('codigo',$comprobante->tipo_nota_codigo);
                $des_motivo = ($this->db->get('tipo_ndebitos')->row())->tipo_ndebito;
            }

            $datos['cabecera']['cod_motivo'] = $comprobante->tipo_nota_codigo;
            $datos['cabecera']['des_motivo'] = $des_motivo;
            $datos['adjunto']['tipo_documento'] = $adjunto->codigo;
            $datos['adjunto']['serie'] = $adjunto->serie;
            $datos['adjunto']['numero'] = $adjunto->numero;
        
        }
        
        if($opc == 0){
            if($comprobante->codigo == '03'){
                $this->resumenes_model->guardarResumen($id,3);
                $datos['config']['go'] = 0;
                echo json_encode($datos); 
                exit();
            }else{
                $fecha = date("Y-m-d");
                $numero = $this->comprobante_anulados_model->maxNumero($fecha) + 1;
                $datos['anulado']['numero'] = $numero;
            }
        }else{

            $this->db->select('i.*,p.prod_codigo,m.medida_codigo_unidad,g.codigo');
            $this->db->from('items as i');
            $this->db->join('productos as p','p.prod_id=i.producto_id', 'left');
            $this->db->join('medida as m','m.medida_id=i.unidad_id');
            $this->db->join('tipo_igv as g','g.id=i.tipo_igv_id');
            $this->db->where('i.comprobante_id',$id);
            $items = $this->db->get()->result();

            $datos['detalle'] = [];
            foreach($items as $i){
                $datos_item['codigo'] = ($i->prod_codigo=='')?"000000000":$i->prod_codigo;
                $datos_item['unidad'] = $i->medida_codigo_unidad;
                $datos_item['cantidad'] = $i->cantidad;
                $datos_item['descripcion'] = $i->descripcion;
                $datos_item['igv'] = ($configuracion->pu_igv==1)?round($i->total-($i->total/(1+$igv)),2):round($i->total*$igv,2);
                $datos_item['base'] = $i->total - $datos_item['igv'];
                $datos_item['porcentaje_igv'] = $igv*100;
                
                $datos_item['tipo_igv'] = $i->codigo;
                $datos_item['impuestos'] = $datos_item['igv'];
                $datos_item['valor_venta'] = $i->total;
                $datos_item['valor_unitario'] = ($configuracion->pu_igv==1)?round($i->importe/(1+$igv),2):round($i->importe,2);
                $datos_item['precio_unitario'] = ($configuracion->pu_igv==1)?round($i->importe,2):round($i->importe*(1+$igv),2);

                array_push($datos['detalle'],$datos_item);
            }  

            require_once (APPPATH .'libraries/Numletras.php');
            $num = new Numletras();
            $importe_letra = $num->num2letras(intval($comprobante->total_a_pagar));
            $arrayImporte = explode(".",$comprobante->total_a_pagar); 
            $montoLetras = $importe_letra.' con ' .$arrayImporte[1].'/100 '.$comprobante->moneda;
            $datos['letra'] = $montoLetras;  
        }
        
        
        $datos['config']['documento'] = $comprobante->codigo;
        
         
        echo json_encode($datos); 
          
    }

    function updateEstadoXML(){
        $comprobante = $_POST['comprobante'];
        $certificado = json_decode($_POST['firma']);
        foreach ($certificado as $value) {
           $firma = $value;
        }

        $this->db->where('id',$comprobante);
        $this->db->set('estado_sunat',2);
        $this->db->set('firma_sunat',$firma);
        $this->db->update('comprobantes');
    }

    function updateEstadoCDR(){
        $comprobante = $_POST['comprobante'];
        $certificado = json_decode($_POST['firma']);
        foreach ($certificado as $value) {
           $firma = $value;
        }

        $this->db->where('id',$comprobante);
        $this->db->set('estado_sunat',3);
        $this->db->set('firma_sunat',$firma);
        $this->db->update('comprobantes');
    }

     function updateEstadoCDR_PSE(){
        $comprobante = $_POST['comprobante'];
        $firma = $_POST['firma'];
        $enlace_del_xml = $_POST['enlace_del_xml'];
        $enlace_del_cdr = $_POST['enlace_del_cdr'];

        $this->db->where('id',$comprobante);
        $this->db->set('estado_sunat',3);
        $this->db->set('firma_sunat',$firma);
        $this->db->set('enlace_del_xml',$enlace_del_xml);
        $this->db->set('enlace_del_cdr',$enlace_del_cdr);
        $this->db->update('comprobantes');
    }

    function updateEstadoANULACION(){
        $estado = $_POST['estado'];
        $comprobante = $_POST['comprobante'];
        $ticket = $_POST['ticket'];
        $numero = $_POST['numero'];
        
        $this->db->where('id',$comprobante);
        $this->db->set('estado_sunat',$estado);
        $this->db->set('ticket_sunat',$ticket);
        $this->db->update('comprobantes');

        if($numero != 'none'){
            $fecha = date('Y-m-d');
            $dataAnular = array(
                            'fecha' => $fecha,
                            'numero' => $numero,
                            'comprobante_id' => $comprobante,
                            'empleado_insert' => $this->session->userdata('empleado_id'),
                            'fecha_insert' => date("Y-m-d H:i:s")
                          );
            $this->comprobante_anulados_model->insertar($dataAnular);
            $this->comprobantes_model->modificar(array('fecha_de_baja' =>$fecha, 'anulado' => 1), $comprobante);            
        }
    }

    function updateEstadoResumenCPE(){
        $estado = $_POST['estado'];
        $resumen = $_POST['comprobante'];
        $ticket = $_POST['ticket'];
        $numero = $_POST['numero'];

        $this->db->where('id',$resumen);
        $this->db->set('estado',$estado);
        $que = $this->db->update('resumenes');


        if($numero != 'none'){
            $fecha = date('Y-m-d');
            $datos = $this->resumenes_model->getListComprobanteResumen($resumen);
            foreach($datos as $value){
                $this->db->where('id',$value->comprobante_id);
                $this->db->set('estado_sunat',$estado);
                $this->db->set('ticket_sunat',$ticket);
                $this->db->update('comprobantes');

                if($numero != 'none'){
                   
                    $dataAnular = array(
                                    'fecha' => $fecha,
                                    'numero' => $numero,
                                    'comprobante_id' => $value->comprobante_id,
                                    'empleado_insert' => $this->session->userdata('empleado_id'),
                                    'fecha_insert' => date("Y-m-d H:i:s")
                                  );
                    $this->comprobante_anulados_model->insertar($dataAnular);
                    $this->comprobantes_model->modificar(array('fecha_de_baja' =>$fecha, 'anulado' => 1), $value->comprobante_id);
                }
            }          
        }                   
    }

    public function pdf($comprobante_id){        
        
        require_once (APPPATH .'libraries/Numletras.php');
        /*datos de la empresa*/

        //$comprobante_id =  32;

        $this->db->from("empresas")
                 ->where("id",1);
        $query = $this->db->get();
        $rsEmpresa = $query->row();
        /*obtenemos todos los datos del documento*/
        $this->db->from("comprobantes as com")
                 ->join("tipo_documentos as tdoc", "com.tipo_documento_id=tdoc.id")
                 ->join("clientes as cli", "com.cliente_id=cli.id")
                 ->join("transportistas as trans", "com.transportista_id=trans.transp_id")
                 ->join("monedas as tmon", "com.moneda_id=tmon.id")
                 ->join("tipo_pagos as tpag", "com.tipo_pago_id=tpag.id")
                 ->where("com.id",$comprobante_id);         
        $query = $this->db->get();
        $rsComprobante = $query->row();

        //var_dump($rsComprobante);exit;

        /*obtenemos el detalle del documento*/
        $this->db->select('i.*,p.*,m.medida_codigo_unidad')
                 ->from("items as i")
                 ->join("productos as p","p.prod_id=i.producto_id", 'left')
                 ->join("medida as m","m.medida_id=i.unidad_id")
                 ->where("i.comprobante_id", $comprobante_id)
                 ->order_by("i.id","ASC");
        $query = $this->db->get();
        $rsDetalle = $query->result();

        //PAGO MONTO ALEXANDER FERNANDEZ 14-10-2020
        $rsPagoMonto =  $this->db->from('comprobante_pagos cmp')
                                ->join('tipo_pagos tpg','cmp.tipo_pago_id =  tpg.id')
                                ->where('cmp.comprobante_id', $comprobante_id)
                                ->get()
                                ->result();                                

        $countItems = count($rsDetalle);
        $ticketHeight =  $countItems*35;
        $ticketHeight = ($ticketHeight > 440) ? $ticketHeight : 440;
        //print_r($rsDetalle);exit();
        $rsComprobante->fecha_de_emision = (new DateTime($rsComprobante->fecha_de_emision))->format("d/m/Y h:i:s");
        $rsComprobante->fecha_de_vencimiento = ($rsComprobante->fecha_de_vencimiento!='')?(new DateTime($rsComprobante->fecha_de_vencimiento))->format("d/m/Y"):'';
        /*documento relacionado*/
        $rsRelacionado = $this->db->from("comprobantes")
                                  ->where("id", $rsComprobante->com_adjunto_id)
                                  ->get()
                                  ->row();

        //convetimos el total en texto
        $num = new Numletras();
        $totalVenta = explode(".",$rsComprobante->total_a_pagar);
        $totalLetras = $num->num2letras($totalVenta[0]);
        $totalLetras = $totalLetras.' con '.$totalVenta[1].'/100 '.$rsComprobante->moneda;
        $rsComprobante->total_letras = $totalLetras; 

        /*anticipos del documento*/
        $this->db->from("comprobante_anticipo as coma")
                 ->join("comprobantes as com", "coma.anticipo_id=com.id")
                 ->where("coma.comprobante_id", $comprobante_id);
        $query = $this->db->get();
        $rsAnticipos = $query->result(); 
        $totalAnticipo = 0;
        foreach($rsAnticipos as $item)
        {
           $totalAnticipo += $item->total_a_pagar; 
        }
        $data['comprobante'] = $this->comprobantes_model->select($comprobante_id);

        $guia_id = $data['comprobante']['numero_guia_remision'];
        $datos_guia = $this->db->from('guias')
                               ->where('id',$guia_id)
                               ->get()->row();
        $rsComprobante->total_anticipos = $totalAnticipo;
        
        $rs_almacen_principal = $this->almacenes_model->select($this->session->userdata('almacen_id'));//datos del almacen principal

        //var_dump($rs_almacen_principal);exit();
        //$certificado =  $url_content;
        //print_r(RUTA_API."/SITIFACSUNAT/index.php/Sunat/getFirmaDigital/".$rsEmpresa->ruc.'/'.$rsComprobante->codigo.'/'.$rsComprobante->serie.'/'.$rsComprobante->numero);exit();
       // $certificado = $this->ObtenerCertificado($rsEmpresa->ruc,$rsComprobante->codigo,$rsComprobante->serie,$rsComprobante->numero);    
        $certificado = $rsComprobante->firma_sunat;
        $configuracion = $this->db->from('comprobantes_ventas')->get()->row();
        $data = [
                    "comprobante"   => $rsComprobante,
                    "relacionado"   => $rsRelacionado,
                    "empresa"       => $rsEmpresa,
                    "detalles"      => $rsDetalle,
                    "pagoMonto"     =>  $rsPagoMonto,
                    "anticipos"     => $rsAnticipos,
                    "rutaqr"        => $this->GetImgQr($data['comprobante']),
                    "certificado"   => $certificado,
                    "configuracion" => $configuracion,
                    "guia"          => $datos_guia,
                    "almacen_principal" => $rs_almacen_principal
                ];
                //var_dump($data);EXIT;
        $html = $this->load->view("templates/ticket_pdf.php",$data,true);
        //var_dump($data);exit;
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper(array(0,0,85,$ticketHeight), 'portrait');
        $this->pdf->render();
        $tipo_documento_descargar = str_replace(" ","_",$rsComprobante->tipo_documento);
        $this->pdf->stream("$tipo_documento_descargar-$rsComprobante->serie-$rsComprobante->numero.pdf",
            array("Attachment"=>0)
        );
    }


    public function consultaComprobantes(){

        $this->load->view('templates/header_administrador');
        $this->load->view('comprobantes/consultaComprobantes');
        $this->load->view('templates/footer');
    }    

  public function consulta_sunat(){


  $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api-seguridad.sunat.gob.pe/v1/clientesextranet/e8bcca68-0760-44e6-a6b7-7bbad7d0800d/oauth2/token/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "grant_type=client_credentials&scope=https%3A//api.sunat.gob.pe/v1/contribuyente/contribuyentes&client_id=e8bcca68-0760-44e6-a6b7-7bbad7d0800d&client_secret=eKiZA6rxoNuJnIjABPjx7Q%3D%3D",
          CURLOPT_HTTPHEADER => array(
            "Content-Type: application/x-www-form-urlencoded",
            //"Cookie: TS019e7fc2=014dc399cbf45599ea47f9abf1b0a1eac3fe77bae317fecff5571a78407590bc560657c64eea952a2ef323b8795106219fb5cc3db2"
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        //echo $response;exit;
        $response =  explode('"', $response);
        //echo $response[3];exit;

        $fecha_de_emision = (new DateTime($_POST['fecha_de_emision']))->format('d/m/yy');        

        $curl_1= curl_init();           
        //$numRuc = '10455923951';
        $codComp = '03';
        //$numeroSerie = 'F001';
        //$numero =  "2";
        //$fechaEmision = '27-03-2020';
        //$monto = '1574.28';


        $data = array("numRuc" => $_POST['numRuc'],
                      "codComp" => $codComp,
                      "numeroSerie" => $_POST['serie'],
                      "numero" => $_POST['numero'],
                      "fechaEmision" => $fecha_de_emision,
                      "monto" => $_POST['monto']
                );
        //var_dump($data);

        $data_string = json_encode($data);

        curl_setopt_array($curl_1, array(
          CURLOPT_URL => "https://api.sunat.gob.pe/v1/contribuyente/contribuyentes/".$_POST['numRuc']."/validarcomprobante",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => $data_string,
          CURLOPT_HTTPHEADER => array(
            "Content-Type:application/json",
            "Authorization: Bearer ".$response[3]           
          ),
        ));


        $response = curl_exec($curl_1);

        curl_close($curl_1);
        //var_dump($response);
        echo $response;exit;

  }

  //BUSCAR DOCUMENTO
  public function buscar_documento(){

    $this->load->view('templates/header_administrador');
    $this->load->view('comprobantes/buscar_documento');
    $this->load->view('templates/footer');

  }

  public function buscar_documento_s(){

    
    switch ($_POST['tipo_documento_id']) {
        case ST_NOTA_PEDIDO:
            
            $documento = $this->db->select('np.notap_id documento_id, np.notap_correlativo nDocumento, np.notap_fecha fecha_de_emision,cli.razon_social razon_social_cliente,CONCAT(emp.nombre," ",emp.apellido_paterno) as vendedor,alm.alm_nombre',FALSE)
                                          ->from('nota_pedido np')
                                          ->join('clientes cli','np.notap_cliente_id = cli.id')
                                          ->join('empleados emp','np.notap_vendedor = emp.id')
                                          ->join('almacenes alm','np.notap_almacen = alm.alm_id')
                                          ->where('notap_correlativo',$_POST['nDocumento'])
                                          ->get()
                                          ->row();

            $documento->a4 = '<a href="'.base_url().'index.php/notas/decargarPdf/'.$documento->documento_id.'" target="_seld"><img src="'.base_url().'images/pdf.png"</a>';
            $documento->url = '<a href="'.base_url().'index.php/notas/comprobanteTributario/'.$documento->documento_id.'/1" target="_seld"><span class="glyphicon glyphicon-export"></span></a>';

            break;

        case ST_PROFORMA:

            $documento = $this->db->select('pf.prof_id documento_id, pf.prof_correlativo nDocumento, pf.prof_doc_fecha fecha_de_emision,cli.razon_social razon_social_cliente,CONCAT(emp.nombre," ",emp.apellido_paterno) as vendedor,alm.alm_nombre',FALSE)
                                          ->from('proformas pf')
                                          ->join('clientes cli','pf.prof_cliente_id = cli.id')
                                          ->join('empleados emp','pf.prof_empleado_id = emp.id')
                                          ->join('almacenes alm','pf.prof_almacen_id = alm.alm_id')
                                          ->where('prof_correlativo',$_POST['nDocumento'])
                                          ->get()
                                          ->row();

            $documento->a4 = '<a href="'.base_url().'index.php/proformas/descargarPdf/'.$documento->documento_id.'" target="_seld"><img src="'.base_url().'images/pdf.png"</a>';
            $documento->url = '<a href="'.base_url().'index.php/proformas/comprobanteTributario/'.$documento->documento_id.'" target="_seld"><span class="glyphicon glyphicon-export"></span></a>';
                                          
            break;

        case ST_COMPROBANTE:
            
            $documento = $this->db->select('com.id documento_id, concat_ws("-", com.serie, com.numero) nDocumento, com.fecha_de_emision fecha_de_emision,cli.razon_social razon_social_cliente,CONCAT(emp.nombre," ",emp.apellido_paterno) as vendedor,alm.alm_nombre',FALSE)
                                          ->from('comprobantes com')
                                          ->join('clientes cli','com.cliente_id = cli.id')
                                          ->join('empleados emp','com.empleado_select = emp.id')
                                          ->join('almacenes alm','com.venta_almacen_id = alm.alm_id')
                                          ->where_in("com.tipo_documento_id", [1,3])//solo factura/boleta
                                          ->where("concat_ws('-', com.serie, com.numero) =", trim($_POST['nDocumento']))
                                          ->get()
                                          ->row();

            $documento->a4 = '<a href="'.base_url().'index.php/comprobantes/pdfGeneraComprobanteOffLine/'.$documento->documento_id.'" target="_seld"><img src="'.base_url().'images/pdf.png"</a>';  

             $documento->url = '<a href="'.base_url().'index.php/comprobantes/comprobanteTributario/'.$documento->documento_id.'" target="_seld"><span class="glyphicon glyphicon-export"></span></a>';


            break;    
        default:
            # code...
            break;
    }

    $tabla = '<table class="table table-bordered">        
                <tr>
                <td>ID</td>
                <td>CORRELATIVO</td>
                <td>FECHA EMISION</td>
                <td>CLIENTE</td>
                <td>USUARIO</td>
                <td>ALMACEN</td>
                <td>A4</td>
                <td>IMPORTAR DOCUMENTO</td>
                </tr>';
    
    $tabla .=  '<tr>
                <td>'.$documento->documento_id.'</td>
                <td>'.$documento->nDocumento.'</td>
                <td>'.$documento->fecha_de_emision.'</td>
                <td>'.$documento->razon_social_cliente.'</td>
                <td>'.$documento->vendedor.'</td>
                <td>'.$documento->alm_nombre.'</td>
                <td>'.$documento->a4.'</td>
                <td>'.$documento->url.'</td>
                </tr>
                </table>';

        echo $tabla;

    }  

    public function comprobanteTributario(){
    
    $rsComprobante = $this->comprobantes_model->select_comprobanteTributario($this->uri->segment(3));    

    //var_dump($rsNota);
    $cabecera = array();
    
    $tipo_cliente_id = $rsComprobante->tipo_cliente_id;
    //P.NATURAL,PJURIDICA
    if($tipo_cliente_id == 1) $data['tipo_documento_id'] = 3;
    if($tipo_cliente_id == 2) $data['tipo_documento_id'] = 1;    

    
    $cabecera['cliente_id'] = $rsComprobante->cliente_id;
    $cabecera['cliente_razon_social'] = $rsComprobante->razon_social;
    $cabecera['moneda_id']  = $rsComprobante->moneda_id;
    $cabecera['total_a_pagar'] = $rsComprobante->total_a_pagar;
    $cabecera['observaciones'] = $rsComprobante->observaciones;
    $cabecera['almacen_id'] = $rsComprobante->almacen_id; 
    $cabecera['tipo_pago_id'] = $rsComprobante->tipo_pago_id;


    //var_dump($rsNota);exit;
    $data['comprobante'] = $cabecera;

    $items = array();
    foreach ($rsComprobante->detalles as $value) {                
                                        
            $item['descripcion'] = $value->descripcion;
            $item['producto_id'] = $value->producto_id;
            $item['cantidad'] = $value->cantidad;
            $item['tipo_igv_id'] =  $value->tipo_igv_id;
            $item['importe'] = $value->importe;
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




    //PSE_TOKEN 10-01-2021
    public function getDatosXML_PSE()
    {        
        //TRAER IGV, ICBPER ACTIVO
        $igv =  $this->igv_model->selectIgvActivo()->valor;
        $icbPer =  $this->icbper_model->selectIcbPerActivo()->icbPer_valor;
        $idComprobante = $this->input->get('comprobante_id');
        $configuracion = $this->db->from('comprobantes_ventas')->get()->row();

        $comprobante = $this->db->select('com.*,cli.ruc,cli.razon_social,tdc.codigo,tdc.codigoPSE,mon.id moneda_id,mon.abrstandar,mon.moneda,tpc.codigo as tip_doc_cli')
                                ->from('comprobantes as com')
                                ->join('clientes as cli','cli.id=com.cliente_id')
                                ->join('tipo_clientes as tpc','tpc.id=cli.tipo_cliente_id')
                                ->join('tipo_documentos as tdc','tdc.id=com.tipo_documento_id')
                                ->join('monedas as mon','mon.id=com.moneda_id')
                                ->where('com.id',$idComprobante)
                                ->get()
                                ->row();

        //DATOS CABECERA                                
        //$datos['operacion'] = $comprobante->tipo_operacion;
        $datos['operacion'] = "generar_comprobante";
        $datos['tipo_de_comprobante'] = $comprobante->codigoPSE;
        $datos['serie']  = $comprobante->serie;
        $datos['numero'] = $comprobante->numero;
        $datos['sunat_transaction'] = 1;

        $datos['cliente_tipo_de_documento']   = $comprobante->tip_doc_cli;
        $datos['cliente_numero_de_documento'] = $comprobante->ruc;
        $datos['cliente_denominacion']        = $comprobante->razon_social;
        $datos['cliente_direccion']           = $comprobante->domicilio1;
        $datos['cliente_email']               = 'fernandezdelacruza@gmail.com';
        $datos['fecha_de_emision'] = $comprobante->fecha_de_emision;
        $datos['fecha_de_vencimiento'] = $comprobante->fecha_de_vencimiento;

        $datos['moneda'] = $comprobante->moneda_id;
        $datos['tipo_de_cambio'] = '';


        $datos['total_gravada'] = $comprobante->total_gravada;
        $datos['total_inafecta'] = $comprobante->total_inafecta;
        $datos['total_exonerada']= $comprobante->total_exonerada;
        $datos['total_igv'] = $comprobante->total_igv;        
        $datos['total_impuestos_bolsas'] = $comprobante->total_icbper;
        $datos['total_gratuita'] = $comprobante->total_gratuita;
        $datos['total'] = $comprobante->total_a_pagar;
        $datos['enviar_automaticamente_a_la_sunat'] = true;        

        
        /////DATOS NOTA CREDITO/DEBITO  
        if($comprobante->codigo == '07' or $comprobante->codigo == '08'){
            $adjunto = $this->db->select('com.*,tdc.codigo')
                                ->from('comprobantes as com')
                                ->join('tipo_documentos as tdc','tdc.id=com.tipo_documento_id')
                                ->where('com.id',$comprobante->com_adjunto_id)
                                ->get()
                                ->row();             

            if($comprobante->codigo == '07'){
                    $rsNota =  $this->db->from('tipo_ncreditos')
                                        ->where('codigo',$comprobante->tipo_nota_codigo)
                                        ->get()
                                        ->row();
                    $datos['tipo_de_nota_de_credito'] = $rsNota->id;
            }else{
                    $rsNota =  $this->db->from('tipo_ndebitos')
                                        ->where('codigo',$comprobante->tipo_nota_codigo)
                                        ->get()
                                        ->row();
                    $datos['tipo_de_nota_de_debito'] = $rsNota->id;
            }

            $datos['documento_que_se_modifica_tipo'] = $adjunto->codigo;
            $datos['documento_que_se_modifica_serie'] = $adjunto->serie;
            $datos['documento_que_se_modifica_numero'] = $adjunto->numero;
        
        }

        $items = $this->db->select('ite.*,pro.prod_codigo,med.medida_codigo_unidad,tig.codigo,pro.prod_codigo_sunat')
                          ->from('items as ite')
                          ->join('productos as pro','pro.prod_id = ite.producto_id', 'left')
                          ->join('medida as med','med.medida_id=ite.unidad_id')
                          ->join('tipo_igv as tig','tig.id=ite.tipo_igv_id')
                          ->where('ite.comprobante_id',$idComprobante)
                          ->get()
                          ->result();

        ////DATOS ITEMS 
            $datos['items'] = [];
            foreach($items as $i){

                $datos_item['unidad_de_medida'] = $i->medida_codigo_unidad;            
                $datos_item['codigo'] = ($i->prod_codigo=='')?"000000000":$i->prod_codigo;
                $datos_item['descripcion'] = $i->descripcion;                
                $datos_item['cantidad'] = $i->cantidad;
                $datos_item['base'] = (intval($i->codigo)>=20)?$i->total:round(($i->total/(1+$igv)),2);
                $datos_item['valor_unitario'] = (intval($i->codigo)>=20)?round(($i->total/$i->cantidad),2):round(($datos_item['base']/$i->cantidad),2);
                $datos_item['precio_unitario'] = round(($i->total/$i->cantidad),2);                
                //$datos_item['tipo_de_igv'] = $i->codigo;                
                $datos_item['tipo_de_igv'] = 1;
                $datos_item['igv']   = (intval($i->codigo)>=20)?0:round($i->total-($i->total/(1+$igv)),2);
                $datos_item['subtotal'] = (intval($i->codigo)>=20)?$i->total:round(($i->total/(1+$igv)),2);
                $datos_item['total'] = (intval($i->codigo)==10)?$i->total:$datos_item['subtotal'];                
                //$datos_item['porcentaje_igv'] = $igv*100;
                //$datos_item['impuestos'] = (intval($i->codigo)>=20)?0:$datos_item['igv'];                 
                array_push($datos['items'],$datos_item);
            } 

        ////LEYENDA 
        require_once (APPPATH .'libraries/Numletras.php');
        $num = new Numletras();
        $importe_letra = $num->num2letras(intval($comprobante->total_a_pagar));
        $arrayImporte = explode(".",$comprobante->total_a_pagar); 
        $montoLetras = $importe_letra.' con ' .$arrayImporte[1].'/100 '.$comprobante->moneda;
        $datos['letra'] = $montoLetras;        
       
        echo json_encode($datos);
    }



    //ANULACION PSE 10-01-2001
     public function getDatosAnulacion_PSE()
    {
        $opc = $this->input->get('opc');
        $id  = $this->input->get('comprobante_id');
        $configuracion = $this->db->from('comprobantes_ventas')
                                  ->get()
                                  ->row(); 

        $empresa = $this->db->from('empresas')
                            ->get()
                            ->row();

        //DEVOLVER STOCK 03-11-2020        
        $rsProductos = $this->db->from('items ite')
                                ->join('comprobantes com','com.id = ite.comprobante_id')
                                ->where('ite.comprobante_id',$id)
                                ->get()
                                ->result();

        foreach ($rsProductos as $rsProducto) {
            $this->productos_model->ingresarStock($rsProducto->producto_id ,$rsProducto->cantidad, $concepto = 'RESTAURAR STOCK',$comprobante,$rsProducto->serie,$rsProducto->numero);
        }        
        
        $comprobante = $this->db->select('com.*,cli.ruc,cli.razon_social,tdc.codigo,tdc.codigoPSE,mon.abrstandar,mon.moneda,tpc.codigo as tip_doc_cli')
                                ->from('comprobantes as com')
                                ->join('clientes as cli','cli.id = com.cliente_id')
                                ->join('tipo_clientes as tpc','tpc.id = cli.tipo_cliente_id')
                                ->join('tipo_documentos as tdc','tdc.id = com.tipo_documento_id')
                                ->join('monedas as mon','mon.id = com.moneda_id')
                                ->where('com.id',$id)
                                ->get()
                                ->row();

        /////DATOS CLIENTE/CABECERA
        $datos['operacion'] = 'generar_anulacion';        
        $datos['tipo_de_comprobante'] = $comprobante->codigoPSE;
        $datos['serie'] = $comprobante->serie;
        $datos['numero'] = $comprobante->numero;
        $datos['motivo'] = "ERROR DEL SISTEMA";

        if($comprobante->codigo == '03'){
          $this->resumenes_model->guardarResumen($id,3);
          $datos['go'] = 1;
          echo json_encode($datos);
          exit();
        }else {
          $fecha = date("Y-m-d");
          $numero = $this->comprobante_anulados_model->maxNumero($fecha) + 1;
          $datos['anulado']['numero'] = $numero;
          $datos['go'] = 1;
          $datos['resumen'] = 0;
        }
        echo json_encode($datos);
    }


    //ENVIAR ANULACIÓN PSE
    public function send_xmlPSE(){
        $pseToken =  $this->pse_token_model->pseToken($this->session->userdata('almacen_id'));
        //var_dump($pseToken);exit;
        $comprobanteJSON = json_decode($_POST['json']);
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $pseToken->ruta,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($comprobanteJSON),
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.$pseToken->token,
            'Content-Type: application/json'
          ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        echo $response;
    }
}
?>