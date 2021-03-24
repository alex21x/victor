<?PHP

    if(!defined('BASEPATH')) exit('No direct script access allowed');
    
    
    class Correo extends CI_Controller{                
        
        public function __construct() {
            parent::__construct();
            $this->load->model('monedas_model');
            $this->load->model('tipo_cambio_model');
            $this->load->model('tipo_documentos_model');
            $this->load->model('ser_nums_model');
            $this->load->model('empresas_model');
            $this->load->model('accesos_model');

            $empleado_id = $this->session->userdata('empleado_id');
            $almacen_id = $this->session->userdata("almacen_id");
            if (empty($empleado_id) or empty($almacen_id)) {
                $this->session->set_flashdata('mensaje', 'No existe sesion activa');
                redirect(base_url());
            }
        }
        
        public function index() {

            $this->db->from('correo');
            $res = $this->db->get()->row();

            $data['correo'] = $res;     
            $data['empresa'] = $this->empresas_model->select($this->uri->segment(3));            

            $this->accesos_model->menuGeneral();
            $this->load->view('correo/index',$data);
            $this->load->view('templates/footer');
        }
        
        public function selectSerie() {
            $series = $this->ser_nums_model->select('',$_POST['tipo_documento_id'], $this->uri->segment(3));
            print_r($series);
            foreach ($series as $value) {
                echo '<option value="' . $value['serie'] . '">' . $value['serie'] . '</option>';
            }
        }

        public function guardar(){
            
            $datos = array(
                'correo_host' => $_POST['host'],
                'correo_port' => $_POST['port'],
                'correo_user' => $_POST['user'],
                'correo_pass' => $_POST['pass'],
                'correo_cifrado' => $_POST['cifrado'],
            );

            $this->db->update("correo",$datos);
            $this->session->set_flashdata('mensaje','Parámetros guardados correctamente');
            redirect(base_url().'index.php/correo/index');
        }

        public function eliminar() {
            $this->ser_nums_model->eliminar($this->uri->segment(3));
            redirect(base_url().'index.php/serNums/index');
        }
    }
?>