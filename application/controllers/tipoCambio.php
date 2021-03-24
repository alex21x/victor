<?PHP

    if(!defined('BASEPATH')) exit('No direct script access allowed');
    
    
    class TipoCambio extends CI_Controller{                
        public function __construct() {
            parent::__construct();
            $this->load->model('monedas_model');
            $this->load->model('tipo_cambio_model');
            $this->load->model('accesos_model');
        }
        
        public function index() {
            
            $data['monedas'] = $this->monedas_model->select();                        
            $moneda_id = '';
            if($this->input->post('moneda_id') != '' && $this->input->post('moneda_id')> 0){
                $moneda_id = $this->input->post('moneda_id');
                $data['moneda_selec'] = $this->input->post('moneda_id');
            }            
            $fecha = '';
            if($this->input->post('fecha') != ''){
                $fecha = new DateTime($this->input->post('fecha'));
                $fecha = $fecha->format('Y-m-d');
            }
                
            $data['tipo_cambio']= $this->tipo_cambio_model->select('',$moneda_id,$fecha);
            //var_dump($tipo_cambio);                        
            $this->accesos_model->menuGeneral();
            $this->load->view('tipoCambio/index',$data);
            $this->load->view('templates/footer');
        }
        
        
        public function nuevo(){
            $data['tipo_cambio']= $this->tipo_cambio_model->select();
            $data['monedas'] = $this->monedas_model->select();
            
            $this->accesos_model->menuGeneral();
            $this->load->view('tipoCambio/nuevo',$data);
            $this->load->view('templates/footer');                                                            
        }
        
        public function guardar(){            
            $fecha = new DateTime($this->input->post('fecha'));
            $fecha = $fecha->format('Y-m-d');
            
            $array = array(
                'fecha' => $fecha,
                'moneda_id' => $this->input->post('moneda_id'),
                'tipo_cambio' => $this->input->post('tipo_cambio')
            );
            //var_dump($array);exit;
            $this->tipo_cambio_model->insertar($array);
            redirect(base_url().'index.php/tipoCambio/index');
        }
        
        public function modificar() {
            
            $data['tCambioSelect'] = $this->tipo_cambio_model->select($this->uri->segment(3));
            $data['monedas'] = $this->monedas_model->select();
                                                                                                                
            $this->accesos_model->menuGeneral();
            $this->load->view('tipoCambio/modificar',$data);
            $this->load->view('templates/footer');            
        }                
        
        
        public function modificar_g() {                  
            $fecha = new DateTime($this->input->post('fecha'));
            $fecha = $fecha->format('Y-m-d');
            
            $array =  array(
                'fecha' => $fecha,
                'moneda_id' => $this->input->post('moneda_id'),
                'tipo_cambio' => $this->input->post('tipo_cambio')
            );
            
            //Tipo Cambio activo
            if($this->uri->segment(4) != ''){
                if($this->uri->segment(4) == 'activo')
                    $array = array('activo' => 'inactivo');
                else
                    $array = array('activo' => 'activo');
            }            
            
            $tCambioSelect_id = $this->uri->segment(3);            
            $this->tipo_cambio_model->modificar($tCambioSelect_id , $array);
            redirect(base_url().'index.php/tipoCambio/index');
        }
        
        public function eliminar() {            
            $this->tipo_cambio_model->eliminar($this->uri->segment(3));
            redirect(base_url().'index.php/tipoCambio/index');
        }        
    }
?>