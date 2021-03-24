<?PHP
//use PhpOffice\PhpSpreadsheet\Spreadsheet;
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Marcas extends CI_Controller {

    public function __construct() {
        parent::__construct();

        date_default_timezone_set('America/Lima');              
        $this->load->model('accesos_model');
        $this->load->model('marcas_model');
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
        $this->load->view('marcas/basic_index', $data);
        $this->load->view('templates/footer');      
    }           
    
    public function crear()
    {
        $data = array();
        echo $this->load->view('marcas/modal_crear', $data);
    }
    public function editar($idMarca)
    {
        $data['marca'] = $this->marcas_model->select($idMarca);
        $this->load->view('marcas/modal_crear', $data);
    }
    public function guardarMarca() {
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

        //guardamos la marca
        $result = $this->marcas_model->guardar();        
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

    public function eliminar($idMarca)
    {
        $result = $this->marcas_model->eliminar($idMarca);
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
        $rsDatos = $this->marcas_model->getMainList();
        sendJsonData($rsDatos);
    }

    //SELECT AUTOCOMPLETE 06-10-2020
    public function selectAutocomplete(){
        $marca = $this->input->get('term');
        echo json_encode($this->marcas_model->selectAutocomplete($marca));
    }      
}
?>

