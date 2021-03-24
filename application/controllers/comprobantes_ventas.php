<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Comprobantes_ventas extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('accesos_model');
        $this->load->model('comprobantes_ventas_model');        

        $empleado_id = $this->session->userdata('empleado_id');
        $almacen_id  = $this->session->userdata("almacen_id");
        if (empty($empleado_id) or empty($almacen_id)) {
            $this->session->set_flashdata('mensaje', 'No existe sesion activa');
            redirect(base_url());
        }
    }
    
    public function index() {      

        $this->accesos_model->menuGeneral();

        $data['comprobanteVenta'] =  $this->comprobantes_ventas_model->select();
        $this->load->view('comprobantes_ventas/basic_index', $data);
        $this->load->view('templates/footer');
    }           
    
    public function guardarComprobanteVenta() {
        $error = array();
        /*if($_POST['password'] == '')
        {
            $error['password'] = 'falta ingresar password';
        }
        if(count($error) > 0)
        {
            $data = ['status'=> STATUS_FAIL,'tipo' => 1, 'errores' => $error];
            sendJsonData($data);
            exit();
        }*/  

        //guardamos Comprobante Venta
        $result = $this->comprobantes_ventas_model->guardar();
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
}?>