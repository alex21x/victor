<?PHP
//use PhpOffice\PhpSpreadsheet\Spreadsheet;
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Categoria extends CI_Controller {

    public function __construct() {
        parent::__construct();

        date_default_timezone_set('America/Lima');              
        $this->load->model('accesos_model');
        $this->load->model('categoria_model');
        $this->load->helper('ayuda');

        $empleado_id = $this->session->userdata('empleado_id');
        $almacen_id = $this->session->userdata("almacen_id");
        if (empty($empleado_id) or empty($almacen_id)) {
            $this->session->set_flashdata('mensaje', 'No existe sesion activa');
            redirect(base_url());
        }
    }
    
    public function index() {
        //$data['categoria'] = $this->Categoria_model->Listarcategoria();
        $this->accesos_model->menuGeneral();
        $this->load->view('categoria/basic_index', $data);
        $this->load->view('templates/footer');      
    }           
    
    public function crear()
    {
        $data = array();
        echo $this->load->view('categoria/modal_crear', $data);
    }
    public function editar($idCategoria)
    {
        $data['categoria'] = $this->categoria_model->select($idCategoria);
        $this->load->view('categoria/modal_crear', $data);
    }
    public function guardarCategoria() {
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
        $result = $this->categoria_model->guardar();
        
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

    public function eliminar($idCategoria)
    {
        $result = $this->categoria_model->eliminar($idCategoria);
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
        $rsDatos = $this->categoria_model->getMainList();
        sendJsonData($rsDatos);
    }

    //SELECT AUTOCOMPLETE 06-10-2020
    public function selectAutocomplete(){
        $categoria = $this->input->get('term');
        echo json_encode($this->categoria_model->selectAutocomplete($categoria));
    }      
}
?>

