<?PHP
//use PhpOffice\PhpSpreadsheet\Spreadsheet;
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class SerNums extends CI_Controller {

    public function __construct() {
        parent::__construct();

        date_default_timezone_set('America/Lima');              
        $this->load->model('accesos_model');
        $this->load->model('empresas_model');
        $this->load->model('almacenes_model');
        $this->load->model('tipo_documentos_model');
        $this->load->model('serNums_model');
        $this->load->helper('ayuda');

        $empleado_id = $this->session->userdata('empleado_id');
        $almacen_id = $this->session->userdata("almacen_id");
        if (empty($empleado_id) or empty($almacen_id)) {
            $this->session->set_flashdata('mensaje', 'No existe sesion activa');
            redirect(base_url());
        }
    }
    
    public function index() {        
        $this->accesos_model->menuGeneral();
        $this->load->view('serNums/basic_index');
        $this->load->view('templates/footer');      
    }           
    
    public function crear()
    {
        $data = array();
        $data['empresa'] = $this->empresas_model->select(1);
        $data['tipo_documentos'] = $this->tipo_documentos_model->select();
        $data['almacenes'] = $this->almacenes_model->select();
        echo $this->load->view('serNums/modal_crear', $data);
    }
    public function editar($idSerNum)
    {
        $data['serNum'] = $this->serNums_model->select($idSerNum);
        $data['empresa'] = $this->empresas_model->select(1);
        $data['tipo_documentos'] = $this->tipo_documentos_model->select();
        $data['almacenes'] = $this->almacenes_model->select();
        $this->load->view('serNums/modal_crear', $data);
    }
    public function guardarSerNum() {
        $error = array();
        if($_POST['tipo_documento'] == '')
        {
            $error['tipo_documento'] = 'falta ingresar tipo documento';
        }
        if($_POST['almacen'] == '')
        {
            $error['almacen'] = 'falta ingresar almacen';
        }        
        if($_POST['serie'] == '')
        {
            $error['serie'] = 'falta ingresar serie';
        }
        if(count($error) > 0)
        {
            $data = ['status'=>STATUS_FAIL,'tipo'=>1, 'errores'=>$error];
            sendJsonData($data);
            exit();
        }   

        //guardamos la sernums
        $result = $this->serNums_model->guardar();
        
        if($result)
        {
            sendJsonData(['status'=>STATUS_OK]);
            exit();
        }else
        {
            sendJsonData(['status'=>STATUS_FAIL, 'tipo'=>2]);
            exit();
        }   

    }

    public function eliminar($idSerNum)
    {
        $result = $this->serNums_model->eliminar($idSerNum);
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
        $rsDatos = $this->serNums_model->getMainList();
        sendJsonData($rsDatos);
    }


    public function selectSerie() {        
            $series = $this->serNums_model->select('',$_POST['tipo_documento_id'], $this->uri->segment(3)); 
            //var_dump($series);exit;
            foreach ($series as $value) {
                echo '<option value="' . $value->serie . '">' . $value->serie . '</option>';
            }
    }
}
?>

