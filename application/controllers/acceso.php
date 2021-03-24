<?PHP

class Acceso extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Empleados_model');
        $this->load->model('accesos_model');
        $this->load->model('almacenes_model');
        $this->load->model('monedas_model');
        $this->load->helper('cookie');
        //$this->load->library("encryption");

        //echo "ab";exit;


    }

   

    function index() {
        //echo 'hoa '.$this->session->userdata('session_url');exit;
       //$data = '';
     
        $data=[];
        /*$cokie_empleado_id = $this->input->cookie('empleadoid', TRUE);
        $cokie_cokie = $this->input->cookie('cookie', TRUE);
        if(!empty($cokie_empleado_id) && !empty($cokie_cokie)){            
            $uno = $this->Empleados_model->verificar_cookie($cokie_empleado_id, $cokie_cokie);
            $dos = $this->Empleados_model->verificar_cookie($cokie_empleado_id, $cokie_cokie);
            if(!empty($uno) && $dos > 0){
                $data['dni'] = $this->Empleados_model->verificar_cookie($cokie_empleado_id, $cokie_cokie);
            }
        }*/
        
        /*$empleado_id = $this->session->userdata('empleado_id');
        $almacen_id = $this->session->userdata("almacen_id");
        if (empty($empleado_id) or empty($almacen_id)) {
            $this->session->set_flashdata('mensaje', 'No existe sesion activa');
            redirect(base_url() . "index.php/acceso/login");
        }*/
        
        //$this->session->sess_destroy();

        $data['almacenes'] = $this->almacenes_model->select();
        $data['empresa'] = $this->empresas_model->select(1);
    
        $this->load->view('templates/header_sin_menu');
        $this->load->view('acceso/login', $data);
       // $this->load->view('templates/footer');


    }

    function inicio_administrador() {  
        //$pass = $this->encryption->encrypt('sistema2112345');

        //print_r("asdad");exit();  
        $data['almacenes'] = $this->almacenes_model->select();
        $data['monedas'] = $this->monedas_model->select();
        $this->accesos_model->menuGeneral();                
        $this->load->view('acceso/inicio',$data);
        $this->load->view('templates/footer');
        
    }

    function inicio_secretaria() {                
        $this->accesos_model->menuGeneral();
        $this->load->view('acceso/inicio');
        $this->load->view('templates/footer');
    }

    function inicio_socio() {        
        $this->accesos_model->menuGeneral();
        $this->load->view('acceso/inicio');
        $this->load->view('templates/footer');
    }

    function inicio_abogado() {        
        $this->accesos_model->menuGeneral();
        $this->load->view('acceso/inicio');
        $this->load->view('templates/footer');
    }

    function inicio_practicante() {        
        $this->accesos_model->menuGeneral();
        $this->load->view('acceso/inicio');
        $this->load->view('templates/footer');
    }
    
    function inicio_contabilidad() {        
        $this->accesos_model->menuGeneral();
        $this->load->view('acceso/inicio');
        $this->load->view('templates/footer');
    }
    
    function inicio_recursos_humanos() {        
        $this->accesos_model->menuGeneral();
        $this->load->view('acceso/inicio');
        $this->load->view('templates/footer');
    }

    function inicio_otros_abogados() {
        $this->load->view('templates/header_otros_abogados');        
        $this->load->view('acceso/inicio');
        $this->load->view('templates/footer');
    }

    function login() {
       
        $rsTipo_acceso = $this->db->from('tipo_accesos')->get()->row();
        $almacen = $this->almacenes_model->select($this->input->post('almacen'));

        $dato_almacen = array(
            'almacen_id' => $almacen->alm_id,
            'almacen_nom' => $almacen->alm_nombre
        );

        $this->session->set_userdata($dato_almacen);
       
        if ($rsTipo_acceso->id==1) {
            /*validar con dni*/
            $codigo = $rsTipo_acceso->id;
            $usuario = $this->input->post('usuario');
            $usario_dni = $this->session->userdata('dni');
            
            if($this->input->post('usuario') == '' && !empty($usario_dni)){
                $usuario = $this->session->userdata('dni');
            }
        } else {
            /*validar con clave mensual*/
            $codigo = $rsTipo_acceso->id;
            $usuario = $this->input->post('usuario');

            $usario_dni = $this->session->userdata('dni');            
            if($this->input->post('usuario') == '' && !empty($usario_dni)){
                $usuario = $this->session->userdata('dni');
            }
        }
        
        if ($this->Empleados_model->login($usuario,$codigo,$almacen->alm_id)) {            
            //para las imagenes.
            $user_foto = $this->session->userdata('foto');
            $filename = './files/foto/' . $user_foto;
            if(file_exists($filename) && !empty($user_foto)){
                $data = array(
                    'ruta_foto' => './files/foto/'.$this->session->userdata('foto'),
                    'title' => $this->session->userdata('usuario') . " " . $this->session->userdata('apellido_paterno')
                );                
            }else{
                $data = array(
                    'ruta_foto' => "./files/foto/sin_foto.jpg",
                    'title'=>"sin foto"
                );                
            }
            
            $this->session->set_userdata($data);
           
            session_start();
            //echo $_SESSION["parametro"];exit;
            if(isset($_SESSION["parametro"]))
                redirect($_SESSION["parametro"]);
            
                redirect(base_url() . "index.php/acceso/inicio_administrador");
                                      
        } else {
            redirect(base_url());
        }
    }

    function login_general() {
        $post_usuario = $this->input->post('usuario');
        if (!empty($post_usuario)) { 
            if ($this->Empleados_model->login($post_usuario)) {
                $data = array('grande'=>1);
                
                $this->session->set_userdata($data);
                $this->load->view('acceso/acceso');
            } else {
                redirect('http://tytl.com.pe/sistemas/index.php?res=Datos_Incorrectos');
            }
        } else {            
            redirect('http://tytl.com.pe/sistemas/');
            
//            $this->load->view('templates/header_sin_menu');
//            $this->load->view('acceso/login_general');
//            $this->load->view('templates/footer');           
        }
    }

    function logout() {        
        if($this->session->userdata('grande')==1){
            $this->session->sess_destroy();
            redirect('http://tytl.com.pe/sistemas/');
        }else{
            $this->session->sess_destroy();
            redirect(base_url());
        }
    }

    function documentos_institucionales() {
        if( $this->session->userdata('empleado_id') == 247){
            redirect(base_url() . "documentacion/ABOGADOS.htm");
        }
        
        switch ($this->session->userdata('tipo_empleado_id')) {
            case 1:
                redirect(base_url() . "documentacion/document.htm");
                break;

            case 2:
                redirect(base_url() . "documentacion/PERSONAL.htm");
                break;

            case 3:
                redirect(base_url() . "documentacion/SOCIOS.htm");
                break;

            case 4:
                redirect(base_url() . "documentacion/ABOGADOS.htm");
                break;

            case 5:
                redirect(base_url() . "documentacion/PRACTICANTES.htm");
                break;

            case 7:
                redirect(base_url() . "documentacion/PERSONAL.htm");
                break;                                    

            default:
                echo "Sin acceso<br>";
                echo "<a href='" . base_url() . 'index.php/acceso/menu_principal' . "'>Atras</a>";
                break;
        }
    }

    function biblioteca() {
        $this->load->view('acceso/biblioteca');
    }

    function acceso_datos() {
        $this->load->view('templates/header_sin_menu');
        $this->load->view('acceso/acceso_datos');
        $this->load->view('templates/footer');
    }

    function acceso_datos_descargar() {
        if ($this->input->post('password') == $this->session->userdata('dni')) {
            $this->load->view('templates/header_sin_menu');
            $this->load->view('acceso/acceso_datos_descargar');
            $this->load->view('templates/footer');
        } else {
            echo "Clave Incorrecta";
            echo "<br>";
            echo "<a href='" . base_url() . "index.php/acceso/menu_principal'>Menu</a>";
        }
    }

    public function menu_principal() {
        $this->load->view('acceso/acceso');
    }

    function dirigir() {
        switch ($this->session->userdata('tipo_empleado_id')) {
            case 1:
                $this->load->view('templates/header_administrador');
                break;

            case 2:
                $this->load->view('templates/header_secretaria');
                break;

            case 3:
                $this->load->view('templates/header_socio');
                break;

            case 4:
                $this->load->view('templates/header_abogado');
                break;

            case 5:
                $this->load->view('templates/header_practicante');
                break;

            case 6:
                $this->load->view('templates/header_otros_abogados');
                break;

            default:
                break;
        }
        $this->load->view('acceso/inicio_sistema');
        $this->load->view('templates/footer');
    }
        
    function redirecciona() {        
        session_start();
        $_SESSION["parametro"] = $_GET['parametro'];        
        redirect($_GET['parametro']);
    }

}
