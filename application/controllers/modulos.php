<?PHP
//use PhpOffice\PhpSpreadsheet\Spreadsheet;
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Modulos extends CI_Controller {

    public function __construct() {
        parent::__construct();

        date_default_timezone_set('America/Lima');
        $this->load->model('accesos_model');
        $this->load->model('almacenes_model');
        $this->load->model('modulos_model');
        $this->load->helper('ayuda');

        $empleado_id = $this->session->userdata('empleado_id');
        $almacen_id = $this->session->userdata("almacen_id");
        if (empty($empleado_id) or empty($almacen_id)) {
            $this->session->set_flashdata('mensaje', 'No existe sesion activa');
            redirect(base_url());
        }
    }
    
    public function index() {    
        $data['almacenes'] = $this->almacenes_model->select();    
        $this->accesos_model->menuGeneral();
        $this->load->view('modulos/basic_index', $data);
        $this->load->view('templates/footer');      
    }

    public function crear()
    {        

        $data = array();                
        $data['almacenes'] = $this->almacenes_model->select();        
        $data['padres'] = $this->modulos_model->select('',1);

        echo $this->load->view('modulos/modal_crear', $data);
    }
    public function editar($idModulo)  {

        $data['modulo'] =  $this->modulos_model->select($idModulo);
        $data['almacenes'] = $this->almacenes_model->select();        
        $data['padres'] = $this->modulos_model->select('',1);
        $this->load->view('modulos/modal_crear', $data);
    }


    public function guardarModulo()
    {
        $error = array();        
        if($_POST['nombre'] == '')
        {
            $error['nombre'] = 'falta ingresar nombre';
        }
        if($_POST['tipoModulo'] == '')
        {
            $error['tipoModulo'] = 'falta ingresar Tipo Módulo';
        }        
        if($_POST['orden'] == '')
        {
            $error['orden'] = 'falta ingresar Orden';
        }

                
        if(count($error) > 0)
        {
            $data = ['status'=>STATUS_FAIL,'tipo'=>1, 'errores'=>$error];
            sendJsonData($data);
            exit();
        }    

        //guardamos el módulo
        $result = $this->modulos_model->guardar();        
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

    public function getMainList()
    {
        $rsDatos = $this->modulos_model->getMainList();
        sendJsonData($rsDatos);
    }


    public function eliminar($idModulo)
    {
        $result = $this->modulos_model->eliminar($idModulo);
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
 }   