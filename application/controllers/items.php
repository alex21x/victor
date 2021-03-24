<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Items extends CI_Controller {

    public function __construct() {
        parent::__construct();

        date_default_timezone_set('America/Lima');                
        $this->load->model('comprobantes_model');
        $this->load->model('items_model');
        //$this->load->model('igv_model');
        $this->load->model('tipo_igv_model');
        $this->load->model('elemento_adicionales_model');
        //$this->load->model('tipo_contratos_model');
        $this->load->model('tipo_documentos_model');
        $this->load->model('tipo_pagos_model');
        $this->load->model('tipo_items_model');
        //$this->load->model('activos_model');
        $this->load->model('accesos_model');
        $this->load->model('clientes_model');
        $this->load->model('monedas_model');
        $this->load->model('empleados_model');        
        $this->load->model('empresas_model');        


        $empleado_id = $this->session->userdata('empleado_id');
        $almacen_id = $this->session->userdata("almacen_id");
        if (empty($empleado_id) or empty($almacen_id)) {
            $this->session->set_flashdata('mensaje', 'No existe sesion activa');
            redirect(base_url());
        }
    }                                       
    
    public function index() {
        
    }
    
    public function modificar() {
        
    }
    
    public function eliminar() {                                        
        if(isset($_GET['delete'])) {
        $this->items_model->eliminar($_GET['delete']);
        //redirect(base_url() . "index.php/comprobantes/modificar/".$this->uri->segment(4));
        }                           
    }
}
?>