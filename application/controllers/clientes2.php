<?PHP if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Clientes2 extends CI_Controller {

    public function __construct() {
        parent::__construct();        
        $this->load->model('clientes_model');
        $this->load->model('tipo_contratos_model');
        $this->load->model('activos_model');
        $this->load->model('accesos_model');
        $this->load->model('empleados_model');
        $this->load->model('contactos_model');
        $this->load->model('contratos_model');
        $this->load->model('empresas_model');
        $this->load->model('tipo_clientes_model');
        
        $this->load->library('pagination');

        $empleado_id = $this->session->userdata('empleado_id');
        if(empty($empleado_id)){
            $this->session->set_flashdata('mensaje', 'No existe sessión activa');
            redirect(base_url());
        }
    }   

    public function index($pagina = FALSE){                
        $data['tipo_contratos'] = $this->tipo_contratos_model->select();
        $order_activo = " ORDER BY activo ASC ";
        $data['activos'] = $this->activos_model->select('', $order_activo);
        $data['tipo_clientes']= $this->tipo_clientes_model->select('','','activo');

        $data['cliente_select'] = $this->input->post('cliente');
        $data['cliente_select_id'] = $this->input->post('cliente_id');

        if (($this->input->post('estado_cliente')) != '') {
            $data['tipo_activo_select'] = $this->input->post('estado_cliente');
            if (($this->input->post('estado_cliente')) == 'todos') {
                $data['tipo_activo_select'] = '';
            }
        } else {
            $data['tipo_activo_select'] = 1; //quiere decir siempre activo
        }

        $data['tipo_contratos_select'] = $this->input->post('tipo_contratos');
        
        if (($this->input->post('estado_contrato')) != '') {
            $data['tipo_activo_contrato_select'] = $this->input->post('estado_contrato');
            if (($this->input->post('estado_contrato')) == 'todos') {
                $data['tipo_activo_contrato_select'] = '';
            }
        } else {
            $data['tipo_activo_contrato_select'] = ''; //quiere decir siempre activo
        }
        
        $data['tipo_clientes_select']=  $this->input->post('tipo_cliente');

        $estado_cliente = array();
        $estados_cliente = '';
        if (($this->input->post('estado_cliente') != '') && ($this->input->post('estado_cliente') != 'todos')) {
            $estado_cliente = $this->activos_model->select($this->input->post('estado_cliente'));
            $estados_cliente = $estado_cliente['activo'];
        }

        $tipo_contrato = '';
        if ($this->input->post('tipo_contratos') > 0) {
            $tipo_contrato = $this->input->post('tipo_contratos');
        }

        $estado_contrato = array();
        $estados_contrato = '';
        if (($this->input->post('estado_contrato') != '') && ($this->input->post('estado_contrato') != 'todos')) {
            $estado_contrato = $this->activos_model->select($this->input->post('estado_contrato'));
            $estados_contrato = $estado_contrato['activo'];
        }
        

        $cliente_id = '';
        if (($this->input->post('cliente_id') != '') && ($this->input->post('cliente') != '')) {
            $cliente_id = $this->input->post('cliente_id');
        }
        
        $tipo_cliente = '';
        if(($this->input->post('tipo_cliente')!= '') && ($this->input->post('tipo_cliente')!="todos")){           
            $tipo_cliente= $this->input->post('tipo_cliente');
        }
        
                
        // PAGINACION - PAGINACION
        $inicio = 0;
        $limite = 30;
        if($pagina)
          $inicio = $pagina;  
        
         $data['clientes'] = $this->clientes_model->select('', $estados_cliente, $cliente_id, '',$tipo_cliente, $tipo_contrato, $estados_contrato,$limite,$inicio);
        
        
         $config['base_url']    = base_url().'index.php/clientes/index/';
         $config['total_rows']  = count($this->clientes_model->select('', $estados_cliente, $cliente_id, '',$tipo_cliente, $tipo_contrato, $estados_contrato));
         $config['per_page']    = $limite;
         $config['uri_segment'] = 3;
        
         $choice = $config['total_rows']/$config['per_page'];
         $config['num_links'] = floor($choice);
         $data['page'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        
        // PAGINACION - BOOSTRAP
        $config['full_tag_open']   = '<ul class="pagination">';
        $config['full_tag_close']  = '</ul>';
        $config['first_link']      = false;
        $config['last_link']       = false;
        $config['first_tag_open']  = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['prev_link']       = '&laquo';
        $config['prev_tag_open']   = '<li>';
        $config['prev_tag_close']  = '</li>';
        $config['next_link']       = '&raquo';
        $config['next_tag_open']   = '<li>';
        $config['next_tag_close']  = '</li>';
        $config['last_tag_open']   = '<li>';
        $config['last_tag_close']  = '</li>';
        $config['cur_tag_open']    = '<li class="active"><a href="#">';
        $config['cur_tag_close']   = '</a></li>';
        $config['num_tag_open']    = '<li>';
        $config['num_tag_close']   = '</li>';                                            
        
        $this->pagination->initialize($config);
        $data['pagination']  = $this->pagination->create_links();                
        

        $this->accesos_model->menuGeneral();
        $this->load->view('clientes/index', $data);
        $this->load->view('templates/footer');
    }
    
    public function index_select(){
        $cliente_id = $this->input->post('cliente_id');
        $tipo_contrato = $this->input->post('tipo_contratos');
        $actividad_id = $this->input->post('actividad');
        redirect(base_url()."index.php/clientes/index/".$cliente_id."/".$tipo_contrato."/".$actividad_id);
    }

    public function nuevo() {
        $data['activos'] = $this->activos_model->select();
        $data['empresas'] = $this->empresas_model->select();
        $data['tipo_clientes'] = $this->tipo_clientes_model->select();

        $this->accesos_model->menuGeneral();
        $this->load->view('clientes/nuevo', $data);
        $this->load->view('templates/footer');
    }

    public function grabar() {
        $tipo_cliente = explode("xx-xx-xx", $this->input->post('tipo_cliente'));       
        $data = array(
            'ruc' => $this->input->post('ruc'),
            'razon_social' => $this->input->post('razon_social'),
            'domicilio1' => $this->input->post('domicilio1'),
            'domicilio2' => $this->input->post('domicilio2'),
            'email' => $this->input->post('email'),
            'pagina_web' => $this->input->post('pagina_web'),
            'telefono_fijo_1' => $this->input->post('telefono_fijo_1'),
            'telefono_fijo_2' => $this->input->post('telefono_fijo_2'),
            'telefono_movil_1' => $this->input->post('telefono_movil_1'),
            'telefono_movil_2' => $this->input->post('telefono_movil_2'),
            'empresa_id' => $this->input->post('empresa'),
            'activo' => $this->input->post('activo'),
            'empleado_id_insert' => $this->session->userdata('empleado_id'),
            'tipo_cliente_id' => $tipo_cliente[0],
            'tipo_cliente' => $tipo_cliente[1]
        );
        if ($this->input->post('nombres') != ''){
            $data = array_merge($data,array('nombres' => $this->input->post('nombres')));
        }        
        if($this->input->post('empleado_id_responsable') != '' && $this->input->post('empleado_id_responsable') > 0){
            $data = array_merge($data, array('empleado_id_responsable' => $this->input->post('empleado_id_responsable')));
        }
        $mensaje = 'Cliente: ' . $this->input->post('razon_social') . ' ingresado exitosamente';

        $this->clientes_model->insertar($data, $mensaje);
        redirect(base_url()."index.php/clientes/index");
    }

    public function perfil(){
        $data['cliente'] = $this->clientes_model->select($this->uri->segment(3));        
                
        if($data['cliente']['empresa_id']>0){
            $data['empresa'] = $this->empresas_model->select($data['cliente']['empresa_id']);
        }
        
        if($data['cliente']['empleado_id_responsable']>0){
            $data['abogado'] = $this->empleados_model->select($data['cliente']['empleado_id_responsable']);
        }
        
        $this->load->view('templates/header_sin_menu');
        $this->load->view('clientes/perfil', $data);
        $this->load->view('templates/footer');
    }

    public function selectAutocompleteEmpleados(){
        $value = $this->input->get('term');
        $where_cutomizado = ' tipo_empleado_id IN (3,4,5)';
        echo json_encode($this->empleados_model->selectAutocomplete($value, '', '', '', '',$where_cutomizado));
    }

    public function modificar(){
        $data['cliente'] = $this->clientes_model->select($this->uri->segment(3));
        $data['activos'] = $this->activos_model->select();
        $data['empresas'] = $this->empresas_model->select();
        $data['tipo_clientes'] = $this->tipo_clientes_model->select('','','activo');
        
        if($data['cliente']['empleado_id_responsable']>0){
            $data['abogado'] = $this->empleados_model->select($data['cliente']['empleado_id_responsable']);
        }
        
        $this->accesos_model->menuGeneral();
        $this->load->view('clientes/modificar', $data);
        $this->load->view('templates/footer');
    }

    public function modificar_g(){
        $tipo_cliente = explode("xx-xx-xx", $this->input->post('tipo_cliente'));
        $data = array(
            'ruc' => $this->input->post('ruc'),
            'razon_social' => $this->input->post('razon_social'),
            'nombres'    => $this->input->post('nombres'),
            'domicilio1' => $this->input->post('domicilio1'),
            'domicilio2' => $this->input->post('domicilio2'),
            'email' => $this->input->post('email'),
            'pagina_web' => $this->input->post('pagina_web'),
            'telefono_fijo_1' => $this->input->post('telefono_fijo_1'),
            'telefono_fijo_2' => $this->input->post('telefono_fijo_2'),
            'telefono_movil_1' => $this->input->post('telefono_movil_1'),
            'telefono_movil_2' => $this->input->post('telefono_movil_2'),
            'empresa_id' => $this->input->post('empresa'),
            'activo' => $this->input->post('activo'),
            'fecha_update' => date("Y-m-d H:i:s"),
            'empleado_id_update' => $this->session->userdata('empleado_id'),
            'tipo_cliente_id' => $tipo_cliente[0],
            'tipo_cliente' => $tipo_cliente[1]
        );
        
        if($this->input->post('empleado_id_responsable') != '' && $this->input->post('empleado_id_responsable') > 0 && $this->input->post('empleado_descripcion') != ''){
            $data = array_merge($data, array('empleado_id_responsable' => $this->input->post('empleado_id_responsable')));
        }
        if($this->input->post('empleado_descripcion') == ''){
            $data = array_merge($data, array('empleado_id_responsable' => ''));
        }        
        
        $mensaje = 'Cliente: ' . $this->input->post('razon_social') . ', modificado exitosamente';
        $this->clientes_model->modificar($this->input->post('id'), $data, $mensaje);
        redirect(base_url()."index.php/clientes/index");
    }

    public function eliminar(){
        $array_contacto = $this->contactos_model->select('', $this->uri->segment(3),'','','','','0');
        $array_contrato = $this->contratos_model->select('', '', '', $this->uri->segment(3));
        
        if((count($array_contacto)>0) || (count($array_contrato)>0)){
            $array_cliente = $this->clientes_model->select($this->uri->segment(3));
            $this->session->set_flashdata('mensaje_cliente_index', 'No se pudo eliminar el Cliente: ' . $array_cliente['razon_social'] . ',  porque tiene contactos y/o contratos ingresados.');
        }else{
            $this->clientes_model->eliminar();    
        }
        redirect(base_url()."index.php/clientes/index");
    }
    
    public function rr(){
        //$fichero = 'https://www.tytl.com.pe/facturacion/index.php/comprobantes/cdrSunat/360/824';
        $fichero = 'http://190.107.181.252/webServiceSunat/cdrSunat.php?comprobante=R20101283071-01-F001-218.zip&empresa_id=2';
        $pagina_inicio = file_get_contents($fichero);
        
        $file = fopen("./files/archivoxxx.txt", "w");
        fwrite($file, $pagina_inicio . PHP_EOL);
        fclose($file);
        
        echo $pagina_inicio;
    }

}