<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Empleados extends CI_Controller {

    public function __construct() {
        parent::__construct();        

        date_default_timezone_set('America/Lima');        
        $this->load->model('almacenes_model');
        $this->load->model('empleados_model');
        $this->load->model('accesos_model');
        $this->load->model('activos_model');
        $this->load->model('perfiles_model');
        $this->load->helper('ayuda');        
//
        $empleado_id = $this->session->userdata('empleado_id');
        $almacen_id = $this->session->userdata("almacen_id");
        if (empty($empleado_id) or empty($almacen_id)) {
            $this->session->set_flashdata('mensaje', 'No existe sesion activa');
            redirect(base_url());
        }
    }

    public function index() {
        $data['empleados'] = $this->empleados_model->select_new();        
        $this->accesos_model->menuGeneral();
        $this->load->view('empleados/basic_index', $data);
        $this->load->view('templates/footer');
    }
    
    public function index2() {
        $data['empresas'] = $this->empresas_model->select();
        $data['activo'] = $this->activos_model->select();
        
        $this->accesos_model->menuGeneral();
        $this->load->view('empresas/index2', $data);
        $this->load->view('templates/footer');
    }
    
    public function perfil_detalle(){
        $data['empresa'] = $this->empresas_model->select($this->uri->segment(3));
        
        $this->load->view('templates/header_sin_menu');
        $this->load->view('empresas/perfil_detalle', $data);
        $this->load->view('templates/footer');
    }
    
    public function imagen_detalle(){        
       
        $nombre_file = sanear_string(date("Y-m-d--H-i-s")."---".$_FILES["foto"]["name"]);
        $config['upload_path'] = './images/empresa';
        $config['allowed_types'] = 'gif|jpg|png|doc|docx|pdf|txt|xls|xlsx|ppt|pptx';
        $config['file_name'] = $nombre_file;
        $config['overwrite'] =  'TRUE';
        $this->load->library('upload', $config);
        if ($this->upload->do_upload('foto')) {
            $data_upload = $this->upload->data();
            $this->empresas_model->modificar($this->input->post('empresa_id'), array('imagen_detalle' => $nombre_file));
        }
        redirect(base_url()."index.php/empresas/perfil_detalle/".$this->input->post('empresa_id'));
    }
    
    public function perfil_pdf(){
        $data['empresa'] = $this->empresas_model->select($this->uri->segment(3));
        
        $this->load->view('templates/header_sin_menu');
        $this->load->view('empresas/perfil_pdf', $data);
        $this->load->view('templates/footer');
    }
    
    public function imagen_pdf(){        
       
        $nombre_file = sanear_string(date("Y-m-d--H-i-s")."---".$_FILES["foto"]["name"]);
        $config['upload_path'] = './images/empresa';
        $config['allowed_types'] = 'gif|jpg|png|doc|docx|pdf|txt|xls|xlsx|ppt|pptx';
        $config['file_name'] = $nombre_file;
        $config['overwrite'] =  'TRUE';
        $this->load->library('upload', $config);
        if ($this->upload->do_upload('foto')) {
            $data_upload = $this->upload->data();
            $this->empresas_model->modificar($this->input->post('empresa_id'), array('imagen_pdf' => $nombre_file));
        }
        redirect(base_url()."index.php/empresas/perfil_pdf/".$this->input->post('empresa_id'));
    }
    
    public function guardar(){
        
        $data = array(
            'empresa'          => $_POST['empresa'],
            'nombre_comercial' => $_POST['nombre_comercial'],
            'descripcion1'     => $_POST['descripcion1'],
            'ruc'              => $_POST['ruc'],
            'domicilio_fiscal' => $_POST['domicilio_fiscal'],
            'telefono_fijo'    => $_POST['telefono_fijo'],
            'telefono_fijo2'   => $_POST['telefono_fijo2'],
            'telefono_movil'   => $_POST['telefono_movil'],
            'telefono_movil2'  => $_POST['telefono_movil2'],
            'activo'           => $_POST['activo'] 
                       
        );
//                
        $this->empresas_model->save($data);
//        $this->select();
        
        echo "Hola recon:".$_POST['empresa'];
    }
    public function nuevo()
    {
        $data['perfiles'] = $this->perfiles_model->select();
        $data['almacenes'] = $this->almacenes_model->select();
        $this->accesos_model->menuGeneral();
        $this->load->view('empleados/nuevo', $data);
        $this->load->view('templates/footer');
    }
    public function modificar(){
        $data['empresa'] = $this->empresas_model->select(1);
        $data['almacenes'] = $this->almacenes_model->select();
        
        $this->accesos_model->menuGeneral();
        $this->load->view('empresas/basic_update', $data);
        $this->load->view('templates/footer');
    }

    public function modificar_g(){        
        $data = array(
            'empresa'          => $_POST['empresa'],            
            'nombre_comercial' => $_POST['empresa'],
            'descripcion1'     => $_POST['empresa'],
            'almacen_id'       => $_POST['almacen'],
            'ruc'              => $_POST['ruc'],
            'domicilio_fiscal' => $_POST['domicilio_fiscal'],
            'correo'           => $_POST['correo'],            
            'activo'           => 'activo'                         
        );
                
        $this->empresas_model->modificar(1, $data);
        redirect(base_url() . "index.php/empresas/index");                
    }
    
    public function basic_modificar($idEmpleado){
        $data['almacenes'] = $this->almacenes_model->select();
        $data['empleados'] = $this->empleados_model->select_new($idEmpleado);
        $data['perfiles'] = $this->perfiles_model->select();

        $this->accesos_model->menuGeneral();
        $this->load->view('empleados/basic_update', $data);
        $this->load->view('templates/footer');
    }

    /*registrar empleado*/
    public function basic_guardar_g()
    {
        $data = array(
            'almacen_id' => $_POST['almacen'],
            'nombre' => strtoupper($_POST['nombre']),
            'apellido_paterno' => strtoupper($_POST['apellido_paterno']),
            'apellido_materno' => strtoupper($_POST['apellido_materno']),            
            'dni' => $_POST['dni'],
            'email' => strtoupper($_POST['email']),
            'activo' => 'activo',
            'acceso' => 'con acceso',
            'tipo_empleado_id' => $_POST['perfil'] ,
            'empleado_fac'     => 1,
            'estado'           => ST_ACTIVO            
        );
        $this->empleados_model->guardar_g($data);
        redirect(base_url() . "index.php/empleados/index");          
    }
    /*actualziar empleado*/
    public function basic_modificar_g($idEmpleado)
    {
        
        $data = array(
            'almacen_id' => $_POST['almacen'],
            'nombre' => strtoupper($_POST['nombre']),
            'apellido_paterno' => strtoupper($_POST['apellido_paterno']),
            'apellido_materno' => strtoupper($_POST['apellido_materno']),            
            'dni' => $_POST['dni'],
            'email' => strtoupper($_POST['email']),
            'tipo_empleado_id' => $_POST['perfil'] ,
            'empleado_fac'     => 1,
            'estado'           => ST_ACTIVO              
        );
                
        $this->empleados_model->modificar_g($data, $idEmpleado);
        redirect(base_url() . "index.php/empleados/index");                
    }

    //ALEXANDER FERNANDEZ 30-10-2020
    public function basic_eliminar($idEmpleado){        
        $this->db->where('id',$idEmpleado);
        $this->db->update('empleados',array('estado' => ST_ELIMINADO));        

        redirect(base_url() . "index.php/empleados/index");      
    }    
}