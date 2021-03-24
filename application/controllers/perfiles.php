<?PHP
//use PhpOffice\PhpSpreadsheet\Spreadsheet;
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Perfiles extends CI_Controller {

    public function __construct() {
        parent::__construct();

        date_default_timezone_set('America/Lima');              
        $this->load->model('accesos_model');
        $this->load->model('perfiles_model');
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
        $this->load->view('perfiles/basic_index', $data);
        $this->load->view('templates/footer');      
    }           
    
    public function crear()
    {
        $data = array();
        //modulos
        $data['modulos'] = $this->obtenerModulos(); 

        echo $this->load->view('perfiles/modal_crear', $data);
    }
    public function editar($idPerfil)
    {
        $data['perfil'] = $this->perfiles_model->select($idPerfil);
        $data['modulos'] = $this->obtenerModulos($idPerfil);
        //print_r($data['modulos']);exit();
        $this->load->view('perfiles/modal_crear', $data);
    }
    public function guardarPerfil() {

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

        //guardamos el perfil
        $result = $this->perfiles_model->guardarPerfil();
        
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

    public function eliminar($idPerfil)
    {
        $result = $this->perfiles_model->eliminar($idPerfil);
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
        $rsDatos = $this->perfiles_model->getMainList();
        sendJsonData($rsDatos);
    }

    public function obtenerModulos($idPerfil='')
    {
        $rsModulos = $this->perfiles_model->obtenerModulos($idPerfil);
        return $rsModulos;
    }
}




?>

