<?PHP
//use PhpOffice\PhpSpreadsheet\Spreadsheet;
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Medida extends CI_Controller {

    public function __construct() {
        parent::__construct();

        date_default_timezone_set('America/Lima');              
        $this->load->model('accesos_model');
        $this->load->model('medida_model');
        $this->load->helper('ayuda');

        $empleado_id = $this->session->userdata('empleado_id');
        $almacen_id = $this->session->userdata("almacen_id");
        if (empty($empleado_id) or empty($almacen_id)) {
            $this->session->set_flashdata('mensaje', 'No existe sesion activa');
            redirect(base_url());
        }
    }
    
    public function index() {
        //$data['categoria'] = $this->medida_model->Listarcategoria();
        $this->accesos_model->menuGeneral();
        $this->load->view('medida/basic_index', $data);
        $this->load->view('templates/footer');      
    }           
    
    public function crear()
    {
        $data = array();
        echo $this->load->view('medida/modal_crear', $data);
    }
    public function editar($idMedida)
    {
        $data['medida'] = $this->medida_model->select($idMedida);
        $this->load->view('medida/modal_crear', $data);
    }
    public function guardarMedida() {
        $error = array();
        if($_POST['nombre'] == '')
        {
            $error['nombre'] = 'falta ingresar nombre';
        }
        if(count($error) > 0)
        {
            $data = ['status'=>STATUS_FAIL,'tipo'=>1, 'errores'=>$error];
            sendJsonData($data);
            exit();
        }   

        //guardamos la categoria
        $result = $this->medida_model->guardar();
        
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

    public function eliminar($idMedida)
    {
        $result = $this->medida_model->eliminar($idMedida);
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
        $rsDatos = $this->medida_model->getMainList();
        sendJsonData($rsDatos);
    }
    public function cambiarEstado()
    {
        $rs = $this->medida_model->cambiarEstado();
        sendJsonData(['status'=>STATUS_OK]);
    }
}




?>

