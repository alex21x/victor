<?PHP

class Comprobantes_ss extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        date_default_timezone_set('America/Lima');
        $this->load->model('comprobantes_model');
        
        $this->load->model('items_model');
        
        //$this->load->model('igv_model');
        $this->load->model('tipo_igv_model');
        $this->load->model('elemento_adicionales_model');
        $this->load->model('tipo_documentos_model');
        $this->load->model('tipo_pagos_model');
        $this->load->model('tipo_items_model');
        $this->load->model('tipo_ncreditos_model');
        $this->load->model('tipo_ndebitos_model');
        //$this->load->model('activos_model');
        $this->load->model('accesos_model');
        $this->load->model('clientes_model');
        $this->load->model('monedas_model');
        $this->load->model('empleados_model');
        $this->load->model('empresas_model');
        $this->load->model('tipo_cambio_model');
        $this->load->model('ser_nums_model');
        $this->load->model('comprobante_anulados_model');
        $this->load->model('cuentas_model');

//        $empleado_id = $this->session->userdata('empleado_id');
//        if (empty($empleado_id)) {
//            $this->session->set_flashdata('mensaje', 'No existe sesion activa');
//            redirect(base_url());
//        }
    }

    public function index() {
        
        echo "abc";exit;
        //PAGINACION        
        $inicio = 0;
        $limite = 15;
        $empresa_id = ($this->uri->segment(3) != '') ? $this->uri->segment(3) : $_POST['empresa_id'];

        $data['pagina'] = $this->uri->segment(4);
        $data['cantidad_fila'] = $limite;
        $pagina = $this->uri->segment(4);
        if ($pagina) {
            $inicio = ($pagina - 1) * $limite;
        }

        $data['tipo_documentos'] = $this->tipo_documentos_model->select();
        $data['cliente_select'] = $this->input->post('cliente');
        $data['cliente_select_id'] = $this->input->post('cliente_id');
        $data['tipo_documento_id'] = $this->input->post('tipo_documento');
        $data['serie_select'] = $this->input->post('serie');
        $data['numero_select'] = $this->input->post('numero');
        $data['empresa'] = $this->empresas_model->select($empresa_id);

        $cliente_id = '';
        if (($this->input->post('cliente_id') != '') && ($this->input->post('cliente') != '')) {
            $cliente_id = $this->input->post('cliente_id');
        }

        $tipo_documento_id = '';
        if ($this->input->post('tipo_documento') != '')
            $tipo_documento_id = $this->input->post('tipo_documento');

        $serie = '';
        if ($this->input->post('serie') != '')
            $serie = $this->input->post('serie');

        $numero = '';
        if ($this->input->post('numero') != '')
            $numero = $this->input->post('numero');

        $fecha_de_emision = '';
        if (!empty($this->input->post('fecha_de_emision'))) {
            $date = new DateTime($this->input->post('fecha_de_emision'));
            $fecha_de_emision = "'" . $date->format('Y-m-d') . "'";
        }

        $fecha_de_vencimiento = '';
        if (!empty($this->input->post('fecha_de_vencimiento'))) {
            $date = new DateTime($fecha_de_vencimiento);
            $fecha_de_vencimiento = "'" . $date->format('Y-m-d') . "'";
        }

        $data['comprobantes'] = $this->comprobantes_model->select('', $serie, $numero, $fecha_de_emision, $fecha_de_vencimiento, $cliente_id, $tipo_documento_id, '', '', $inicio, $limite, $empresa_id);
        //var_dump($data['comprobantes']);exit;
        //PAGINACION        
        $this->load->library('pagination');
        $config['base_url'] = base_url() . 'index.php/comprobantes/comprobante/' . $empresa_id;
        $config['total_rows'] = count($this->comprobantes_model->select('', $serie, $numero, $fecha_de_emision, $fecha_de_vencimiento, $cliente_id, $tipo_documento_id, '', '', FALSE, FALSE, $empresa_id));
        $config['per_page'] = $limite;
        $config['uri_segment'] = 4;
        $config['first_url'] = base_url() . 'index.php/comprobantes/comprobante/' . $empresa_id . '/1';
        $config['num_links'] = 3;

        //PAGINACION - BOOSTRAP
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = false;
        $config['last_link'] = false;
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = '&laquo';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&raquo';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $this->pagination->initialize($config);
        $this->accesos_model->menuGeneral();
        $this->load->view('comprobantes/index', $data);
        $this->load->view('templates/footer');
    }

    public function indexo($pagina = '') {
        //$this->output->enable_profiler(TRUE);

        $this->load->library('pagination');

        $cliente_id = '';
        if (($this->input->post('cliente_id') != '') && ($this->input->post('cliente') != '')) {
            $cliente_id = $this->input->post('cliente_id');
        }

        $tipo_documento_id = '';
        if ($this->input->post('tipo_documento') != '')
            $tipo_documento_id = $this->input->post('tipo_documento');

        $serie = '';
        if ($this->input->post('serie') != '')
            $serie = $this->input->post('serie');

        $numero = '';
        if ($this->input->post('numero') != '')
            $numero = $this->input->post('numero');

        $fecha_de_emision = '';
        if (!empty($this->input->post('fecha_de_emision'))) {
            $date = new DateTime($this->input->post('fecha_de_emision'));
            $fecha_de_emision = "'" . $date->format('Y-m-d') . "'";
        }

        $fecha_de_vencimiento = '';
        if (!empty($this->input->post('fecha_de_vencimiento'))) {
            $date = new DateTime($fecha_de_vencimiento);
            $fecha_de_vencimiento = "'" . $date->format('Y-m-d') . "'";
        }


        $inicio = 0;
        $limite = 5;

        if ($pagina) {
            $inicio = ($pagina - 1) * $limite;
        }

        $data['comprobantes'] = $this->comprobantes_model->select('', '', '', '', '', '', '', '', '', $inicio, $limite, '');

        $config['base_url'] = base_url() . '/index.php/comprobantes/indexo/';
        $config['total_rows'] = count($this->comprobantes_model->select('', '', '', '', '', '', '', '', '', FALSE, FALSE, ''));
        $config['per_page'] = $limite;
        $this->pagination->initialize($config);

        $data = array();
        $this->accesos_model->menuGeneral();
        $this->load->view('comprobantes/indexo', $data);
        $this->load->view('templates/footer');
    }

    public function comprobante() {
        //PAGINACION        
        $inicio = 0;
        $limite = 15;
        $empresa_id = $this->uri->segment(3);

        $data['pagina'] = $this->uri->segment(4);
        $data['cantidad_fila'] = $limite;
        $pagina = $this->uri->segment(4);
        if ($pagina) {
            $inicio = ($pagina - 1) * $limite;
        }

        $data['tipo_documentos'] = $this->tipo_documentos_model->select();
        $data['cliente_select'] = $this->input->post('cliente');
        $data['cliente_select_id'] = $this->input->post('cliente_id');
        $data['tipo_documento_id'] = $this->input->post('tipo_documento');
        $data['serie_select'] = $this->input->post('serie');
        $data['numero_select'] = $this->input->post('numero');
        $data['empresa'] = $this->empresas_model->select($empresa_id);

        $cliente_id = '';
        if (($this->input->post('cliente_id') != '') && ($this->input->post('cliente') != '')) {
            $cliente_id = $this->input->post('cliente_id');
        }

        $tipo_documento_id = '';
        if ($this->input->post('tipo_documento') != '')
            $tipo_documento_id = $this->input->post('tipo_documento');

        $serie = '';
        if ($this->input->post('serie') != '')
            $serie = $this->input->post('serie');

        $numero = '';
        if ($this->input->post('numero') != '')
            $numero = $this->input->post('numero');

        $fecha_de_emision = '';
        if (!empty($this->input->post('fecha_de_emision'))) {
            $date = new DateTime($this->input->post('fecha_de_emision'));
            $fecha_de_emision = "'" . $date->format('Y-m-d') . "'";
        }

        $fecha_de_vencimiento = '';
        if (!empty($this->input->post('fecha_de_vencimiento'))) {
            $date = new DateTime($fecha_de_vencimiento);
            $fecha_de_vencimiento = "'" . $date->format('Y-m-d') . "'";
        }

        $data['comprobantes'] = $this->comprobantes_model->select('', $serie, $numero, $fecha_de_emision, $fecha_de_vencimiento, $cliente_id, $tipo_documento_id, '', '', $inicio, $limite, $empresa_id);
        //var_dump($data['comprobantes']);exit;
        //PAGINACION        
        $this->load->library('pagination');
        $config['base_url'] = base_url() . 'index.php/comprobantes/comprobante/' . $empresa_id;
        $config['total_rows'] = count($this->comprobantes_model->select('', $serie, $numero, $fecha_de_emision, $fecha_de_vencimiento, $cliente_id, $tipo_documento_id, '', '', FALSE, FALSE, $empresa_id));
        $config['per_page'] = $limite;
        $config['uri_segment'] = 4;
        $config['first_url'] = base_url() . 'index.php/comprobantes/comprobante/' . $empresa_id . '/1';
        $config['num_links'] = 3;

        //PAGINACION - BOOSTRAP
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = false;
        $config['last_link'] = false;
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = '&laquo';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&raquo';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $this->pagination->initialize($config);
        $this->accesos_model->menuGeneral();
        $this->load->view('comprobantes/index', $data);
        $this->load->view('templates/footer');
    }

    public function index_asesor($pagina = FALSE) {
        //PAGINACION        
        $inicio = 0;
        $limite = 15;
        $empresa_id = 2;

        if ($pagina) {
            $inicio = ($pagina - 1) * $limite;
        }

        $data['tipo_documentos'] = $this->tipo_documentos_model->select();
        $data['cliente_select'] = $this->input->post('cliente');
        $data['cliente_select_id'] = $this->input->post('cliente_id');
        $data['tipo_documento_id'] = $this->input->post('tipo_documento');
        $data['serie_select'] = $this->input->post('serie');
        $data['numero_select'] = $this->input->post('numero');
        $data['empresa'] = $this->empresas_model->select($empresa_id);

        $cliente_id = '';
        if (($this->input->post('cliente_id') != '') && ($this->input->post('cliente') != ''))
            $cliente_id = $this->input->post('cliente_id');

        $tipo_documento_id = '';
        if ($this->input->post('tipo_documento') != '')
            $tipo_documento_id = $this->input->post('tipo_documento');

        $serie = '';
        if ($this->input->post('serie') != '')
            $serie = $this->input->post('serie');

        $numero = '';
        if ($this->input->post('numero') != '')
            $numero = $this->input->post('numero');

        $fecha_de_emision = '';
        if (!empty($this->input->post('fecha_de_emision'))) {
            $date = new DateTime($this->input->post('fecha_de_emision'));
            $fecha_de_emision = "'" . $date->format('Y-m-d') . "'";
        }

        $fecha_de_vencimiento = '';
        if (!empty($this->input->post('fecha_de_vencimiento'))) {
            $date = new DateTime($fecha_de_vencimiento);
            $fecha_de_vencimiento = "'" . $date->format('Y-m-d') . "'";
        }

        $data['comprobantes'] = $this->comprobantes_model->select('', $serie, $numero, $fecha_de_emision, $fecha_de_vencimiento, $cliente_id, $tipo_documento_id, '', '', $inicio, $limite, $empresa_id);
        //var_dump($data['comprobantes']);exit;
        //PAGINACION        
        $this->load->library('pagination');
        $config['base_url'] = base_url() . 'index.php/pagina';
        $config['total_rows'] = count($this->comprobantes_model->select('', $serie, $numero, $fecha_de_emision, $fecha_de_vencimiento, $cliente_id, $tipo_documento_id, '', '', FALSE, FALSE, $empresa_id));
        $config['per_page'] = $limite;
        $config['uri_segment'] = 2;
        $config['first_url'] = base_url() . 'index.php/pagina/1';
        $config['num_links'] = 2;

        //PAGINACION - BOOSTRAP
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = false;
        $config['last_link'] = false;
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = '&laquo';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&raquo';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $this->pagination->initialize($config);
        $this->accesos_model->menuGeneral();
        $this->load->view('comprobantes/index', $data);
        $this->load->view('templates/footer');
    }

    public function nuevo($factura_id = '', $valida = 0) {
        $data['valida'] = 0;
        $data['ajaxId'] = 0;
        if ($valida === '1') {
            session_start();
            unset($_SESSION['parametro']);
            $data['valida'] = 1;
            $data['ajaxId'] = $factura_id;
        }

        $data['tipo_documentos'] = $this->tipo_documentos_model->select();
        $data['tipo_pagos'] = $this->tipo_pagos_model->select();
        $data['tipo_item'] = $this->tipo_items_model->select();
        $data['tipo_igv'] = $this->tipo_igv_model->select('', '', '', 0);
        $data['tipo_ncreditos'] = $this->tipo_ncreditos_model->select('', '', '', 0);
        $data['tipo_ndebitos'] = $this->tipo_ndebitos_model->select('', '', '', 0);
        $data['elemento_adicionales'] = $this->elemento_adicionales_model->select('', '', 'activo');
        $data['monedas'] = $this->monedas_model->select();
        $data['empresas'] = $this->empresas_model->select();
        $data['comp_adjuntos'] = $this->comprobantes_model->select('', '', '', '', '', '', '', '0');
        $data['ser_nums'] = $this->ser_nums_model->select();
        $data['empresa'] = $this->empresas_model->select($this->uri->segment(3));

        $this->accesos_model->menuGeneral();
        $this->load->view('comprobantes/nuevo', $data);
        $this->load->view('templates/footer');
    }

    public function modificar() {
        $data['comprobante'] = $this->comprobantes_model->select($this->uri->segment(3));
        $data['items'] = $this->items_model->select('', $this->uri->segment(3));

        $data['tipo_documentos'] = $this->tipo_documentos_model->select();
        $data['tipo_pagos'] = $this->tipo_pagos_model->select();
        $data['tipo_item'] = $this->tipo_items_model->select();
        $data['tipo_igv'] = $this->tipo_igv_model->select('', '', '', 0);
        $data['tipo_ncreditos'] = $this->tipo_ncreditos_model->select('', '', '', 0);
        $data['tipo_ndebitos'] = $this->tipo_ndebitos_model->select('', '', '', 0);
        $data['elemento_adicionales'] = $this->elemento_adicionales_model->select('', '', 'activo');
        $data['monedas'] = $this->monedas_model->select();
        $data['empresas'] = $this->empresas_model->select();
        $data['comp_adjuntos'] = $this->comprobantes_model->select('', '', '', '', '', '', '', '0');

        $this->accesos_model->menuGeneral();
        $this->load->view('comprobantes/modificar', $data);
        $this->load->view('templates/footer');
    }

    public function eliminar($comprobante_id) {
        $this->comprobantes_model->eliminar($comprobante_id);
        redirect(base_url() . "index.php/comprobantes/index");
    }

    public function detalle() {

        $this->load->library('numletras');
        $comprobante_id = $this->uri->segment(3);

        $data['comprobante'] = $this->comprobantes_model->select($comprobante_id);
        $data['items'] = $this->items_model->select('', $comprobante_id);

        $tipo_documento_id = $data['comprobante']['tipo_documento_id'];

        switch ($tipo_documento_id) {
            case 1:
                $tipo_documento = "FACTURA ELECTRONICA";
                break;
            case 3:
                $tipo_documento = "BOLETA ELECTRONICA";
                break;
            case 7:
                $tipo_documento = "NOTA DE CREDITO";
                $data['tipo_nota'] = $this->tipo_ncreditos_model->select($data['comprobante']['tipo_nota_id']);
                $data['comp_adjunto'] = $this->comprobantes_model->select($data['comprobante']['com_adjunto_id']);
                break;
            case 8:
                $tipo_documento = "NOTA DE DEBITO";
                $data['tipo_nota'] = $this->tipo_ndebitos_model->select($data['comprobante']['tipo_nota_id']);
                $data['comp_adjunto'] = $this->comprobantes_model->select($data['comprobante']['com_adjunto_id']);
                break;
        }

        $data['tipo_documento'] = $tipo_documento;
        $this->load->view('templates/header_sin_menu');
        $this->load->view('comprobantes/detalle', $data);
        $this->load->view('templates/footer');
    }

    public function pdfGeneraComprobante($comprobante_id = '', $vista = '') {
        $comprobante = $this->comprobantes_model->select($comprobante_id);
        //var_dump($comprobante);exit;
        $items = $this->items_model->select('', $comprobante_id);
        $tnota = '';
        $cadjunto = '';

        //NOTA DE CREDITO,DEBITO
        if ($comprobante['tipo_documento_id'] == 7) {
            $tnota = $this->tipo_ncreditos_model->select($comprobante['tipo_nota_id']);
            $cadjunto = $this->comprobantes_model->select($comprobante['com_adjunto_id']);
        }
        if ($comprobante['tipo_documento_id'] == 8) {
            $tnota = $this->tipo_ndebitos_model->select($comprobante['tipo_nota_id']);
            $cadjunto = $this->comprobantes_model->select($comprobante['com_adjunto_id']);
        }
        //DECLARANDO VARIABLES
        $rucCliente = $comprobante['cliente_ruc'];
        $numSunat = 0;
        $codSunat = 0;
        $desSunat = 0;
        $serNum = $comprobante['serie'] . ' ' . $comprobante['numero'];
        $envValidacion = 0;
        //LLAMADA A LA WEBSERVICE
        $fichero = 'http://190.107.181.252/webServiceSunat/pdfSunat.php?comprobante=' . $comprobante['empresa_ruc'] . '-' . $comprobante['tipo_documento_codigo'] . '-' . $comprobante['serie'] . '-' . $comprobante['numero'] . '.xml&empresa_id=' . $comprobante['empresa_id'];
        //echo $fichero;exit;
        $obj = json_decode(file_get_contents($fichero), true);
        if (!empty($obj)) {
            $numSunat = $obj['numSunat'];
            $codSunat = $obj['codSunat'];
            $desSunat = $obj['desSunat'];
            $serNum = $obj['serNum'];
            $rucCliente = $obj['rucCliente'];
            $envValidacion = 1;
        } //else {echo "Fichero no encontrado";}            
        $array = array(
            'rucCliente' => $rucCliente,
            'numSunat' => $numSunat,
            'codSunat' => $codSunat,
            'desSunat' => $desSunat,
            'serNum' => $serNum,
            'vista' => $vista,
            'envValidacion' => $envValidacion,
        );
        $comprobante = array_merge($comprobante, $array);

        $this->load->library('Pdf', $comprobante);

        $cuentas_bancarias = $this->cuentas_model->select(3);
        $cuenta_formateadas = $this->cuentas_model->formatCuentas($cuentas_bancarias);
        $this->pdf->GenerarComprobante($items, $tnota, $cadjunto, $cuenta_formateadas);
    }
    
    public function pdfGeneraComprobanteOffLine($comprobante_id = '', $vista = '') {
        $comprobante = $this->comprobantes_model->select($comprobante_id);
        //var_dump($comprobante);exit;
        $items = $this->items_model->select('', $comprobante_id);
        $tnota = '';
        $cadjunto = '';

        //NOTA DE CREDITO,DEBITO
        if ($comprobante['tipo_documento_id'] == 7) {
            $tnota = $this->tipo_ncreditos_model->select($comprobante['tipo_nota_id']);
            $cadjunto = $this->comprobantes_model->select($comprobante['com_adjunto_id']);
        }
        if ($comprobante['tipo_documento_id'] == 8) {
            $tnota = $this->tipo_ndebitos_model->select($comprobante['tipo_nota_id']);
            $cadjunto = $this->comprobantes_model->select($comprobante['com_adjunto_id']);
        }
        //DECLARANDO VARIABLES
        $rucCliente = $comprobante['cliente_ruc'];
        $numSunat = 0;
        $codSunat = 0;
        $desSunat2 = 0;
        $serNum = $comprobante['serie'] . ' ' . $comprobante['numero'];
        $envValidacion = 0;
        //LLAMADA A LA WEBSERVICE
        $fichero = 'http://190.107.181.252/webServiceSunat/pdfSunat.php?comprobante=' . $comprobante['empresa_ruc'] . '-' . $comprobante['tipo_documento_codigo'] . '-' . $comprobante['serie'] . '-' . $comprobante['numero'] . '.xml&empresa_id=' . $comprobante['empresa_id'];
        //echo $fichero;exit;
        $obj = json_decode(file_get_contents($fichero), true);
        //var_dump($obj);exit;
        if (!empty($obj)) {
            //$numSunat = $obj['numSunat'];//no se usa en pdf (hasta 25-10-2017)
            //$codSunat = $obj['codSunat'];//no se usa en pdf (hasta 25-10-2017)
            $desSunat = $obj['desSunat'];
            //$serNum = $obj['serNum'];//SI se usa en pdf (hasta 25-10-2017): ARROJA:  SERIE-NUMERO ..  de la factura
            $rucCliente = $obj['rucCliente'];//SI se usa pero usaremos el q viene del sistema back, pq estamos en offline.
            $envValidacion = 1;
        } //else {echo "Fichero no encontrado";}            
        $array = array(
            'rucCliente' => $rucCliente,
            'numSunat' => $numSunat,
            'codSunat' => $codSunat,
            'desSunat' => $desSunat,
            'serNum' => $serNum,
            'vista' => $vista,
            'envValidacion' => $envValidacion,
        );
        $comprobante = array_merge($comprobante, $array);

        $this->load->library('Pdf', $comprobante);

        $cuentas_bancarias = $this->cuentas_model->select(3);
        $cuenta_formateadas = $this->cuentas_model->formatCuentas($cuentas_bancarias);
        $this->pdf->GenerarComprobante($items, $tnota, $cadjunto, $cuenta_formateadas);
    }

    public function jsonComprobante($factura_id = '') {
        $comprobante = array();
        $comprobante = $this->comprobantes_model->jsonComprobante($factura_id);

        //echo $comprobante['comprobante_id'];
        //var_dump($comprobante);exit;   
        $valor = [];

        foreach ($comprobante as $value) {
            $arrayComprobante[$value['comprobante_id']]['comprobante_id'] = $value['comprobante_id'];
            $arrayComprobante[$value['comprobante_id']]['cliente_id'] = $value['cliente_id'];
            $arrayComprobante[$value['comprobante_id']]['razon_social'] = $value['razon_social'];
            $arrayComprobante[$value['comprobante_id']]['fecha'] = $value['fecha'];
            $arrayComprobante[$value['comprobante_id']]['moneda_id'] = $value['moneda_id'];
            $arrayComprobante[$value['comprobante_id']]['moneda'] = $value['moneda'];


            $arrayComprobante[$value['comprobante_id']]['item']['descripcion'] = $value['descripcion'];
            $arrayComprobante[$value['comprobante_id']]['item']['importe'] = $value['importe'];
        }

        echo json_encode($arrayComprobante);
        //echo json_encode($comprobante);
    }

    public function ValidarComprobante($comprobante_id) {

        //GENERO PDF
        $this->pdfGeneraComprobante($comprobante_id);
        $empleado = $this->session->userdata('email');
        $comprobante = $this->comprobantes_model->select($comprobante_id);

        $config['protocol'] = 'smtp';
        $config['mailtype'] = 'html';
        $config['smtp_host'] = 'mail.grupotytl.pe';
        $config['smtp_port'] = 25;
        $config['smtp_user'] = 'prueba@grupotytl.pe';
        $config['smtp_pass'] = '8vNA!hIW2fZy';

        $this->load->library('email');
        $this->email->initialize($config);
        $this->email->from('prueba@grupotytl.pe', 'Sistema Abogados - Re: ' . $empleado);
        $this->email->to('hdelacruz@tytl.com.pe');

        if ($comprobante['tipo_cliente_id'] == 1) {
            $this->email->subject('Factura Cliente - Validacion/ ' . $comprobante['cli_nombres'] . ' ' . $comprobante['cli_razon_social']);
            $body = '<h2>Validación de Emisión de Comprobante de Pago Exitosa!</h2><br>';
            $body .= 'El Comprobante de pago generado al Cliente: ' . $comprobante['cli_nombres'] . ' ' . $comprobante['cli_razon_social'] . ' ha sido emitido con éxito.<br><br>';
        } else {
            $this->email->subject('Factura Cliente - Validacion/ ' . $comprobante['cli_razon_social']);
            $body = '<h2>Validación de Emisión de Comprobante de Pago Exitosa!</h2><br>';
            $body .= 'El Comprobante de pago generado al Cliente: ' . $comprobante['cli_razon_social'] . ' ha sido emitido con éxito<br><br>';
        }
        $body .= 'Por favor de encontrar algún problema reportelo a hdelacruz@tytl.com.pe.<br>';
        $body .= 'Muchas gracias,';

        $this->email->message($body);
        $this->email->attach(APPPATH . "files_pdf/comprobantes/" . $comprobante['cliente_id'] . $comprobante['comprobante_id'] . ".pdf");
        //$mail->AltBody = 'Factura Cliente: ';
        if (!$this->email->send()) {
            $this->session->set_flashdata('mensaje', 'Error');
        } else {
            $this->session->set_flashdata('mensaje', 'Validacion de Factura exitosa!');
        }
    }

    // $envio_automativo, se disparará los correos solamente cuando se guarda el comprobante (de forma individual).
    public function mailEnviarComprobante_1($comprobante_id = '') {
        if (isset($_POST['comprobante_id']))
            $comprobante_id = $_POST['comprobante_id'];

        $this->pdfGeneraComprobante($comprobante_id);
        $comprobante = $this->comprobantes_model->select($comprobante_id);
        //var_dump($comprobante);exit;
        //ENVIO CORREO
        $config['protocol'] = 'smtp';
        $config['mailtype'] = 'html';
        $config['smtp_host'] = 'mail.tytl.pe';
        //$config['smtp_host'] = 'mail.grupotytl.pe';
        //$config['smtp_host'] = 'mail.tytl.com.pe';
        $config['smtp_port'] = 25;


        $config['smtp_user'] = 'facturacion@tytl.pe';
        $config['smtp_pass'] = '%Facturacion2017%';
//        $config['smtp_user'] = 'facturacion@grupotytl.pe';
//        $config['smtp_pass'] = '%Facturacion2017%';
//        $config['smtp_user'] = 'facturacion@tytl.com.pe';
//        $config['smtp_pass'] = '%Facturar2017%';

        $this->load->library('email');
        $this->email->initialize($config);
        $this->email->from('facturacion@tytl.pe', 'Sistema Abogados');
//        $this->email->from('facturacion@tytl.com.pe', 'Sistema Abogados');

        $correos_adjuntos = array(
            'hdelacruz@tytl.com.pe',
            'cobranzastytl.com.pe',
            'jvelasco@tytl.com.pe',
            'carmen@tytl.com.pe',
            'mdptm@tytl.com.pe'
        );
//        $correos_adjuntos = array(
//            'hdelacruz@tytl.com.pe'            
//            );

        if ($this->uri->segment(4) == "enviar_cliente") {
            $this->email->to($comprobante['cli_email']);
            $this->email->cc($correos_adjuntos);
        }

        if ($this->uri->segment(4) == "enviar_equipo") {
            $this->email->to('hdelacruz@tytl.com.pe');
            $this->email->cc($correos_adjuntos);
        }

        $this->email->subject('Facturación Electronica ' . $comprobante['empresa'] . ' ' . strtoupper($comprobante['descripcion1']) . ' - ' . $comprobante['cli_razon_social']);
        $body = '<h2>Comprobante de Pago Electronico</h2>';
        $body .= 'Estimado Cliente:<br><br>';
        $body .= 'Cliente: ' . $comprobante['cli_razon_social'] . '<br><br>';
        $body .= 'Adjunto a la presente se servirá encontrar nuestro comprobante de pago electrónico.' . '<br><br>';
        $body .= 'Por favor cualquier aclaración enviar un correo electrónico a cobranzas@tytl.com.pe' . '<br><br>';
        $body .= 'Muchas gracias,<br><br>';
        $body .= '<b>' . $comprobante['empresa'] . ' ' . strtoupper($comprobante['descripcion1']) . '</b><br><br>';
        $body .= 'Para confirmar la validez de su comprobante de pago, ingrese a la siguiente Dirección de Sunat: http://e-consulta.sunat.gob.pe/ol-ti-itconsvalicpe/ConsValiCpe.htm<br>';

        $this->email->message($body);
        $this->email->attach(APPPATH . "files_pdf/comprobantes/" . $comprobante['cliente_id'] . $comprobante['comprobante_id'] . ".pdf");
        //$mail->AltBody = 'Comprobante Cliente: ';

        if (!$this->email->send()) {
            //error_reporting(E_ALL);exit;
            $this->session->set_flashdata('mensaje', 'Error');
        } else {
            if ($this->uri->segment(4) == "enviar_cliente") {
                $this->comprobantes_model->modificar(array('enviado_cliente' => 1), $comprobante['comprobante_id']);
            }

            if ($this->uri->segment(4) == "enviar_equipo") {
                $this->comprobantes_model->modificar(array('enviado_equipo' => 1), $comprobante['comprobante_id']);
            }
            $this->session->set_flashdata('mensaje', 'Comprobante enviado correctamente');
        }
        //$this->email->print_debugger();
        //$this->session->set_flashdata('mensaje','Error');
        //exit;
        redirect(base_url() . "index.php/comprobantes/index/" . $comprobante['empresa_id']);
    }

    public function mailEnviarComprobante($comprobante_id = '') {
        if (isset($_POST['comprobante_id']))
            $comprobante_id = $_POST['comprobante_id'];

        $this->pdfGeneraComprobante($comprobante_id);
        $comprobante = $this->comprobantes_model->select($comprobante_id);

        ///////////////////////////////////////////////////////////////////////////////////////////////////        
        require_once(APPPATH . 'libraries/PHPMailerAutoload.php');

        $mail = new PHPMailer;
        $mail->CharSet = "UTF-8";
        $mail->isSMTP();
        $mail->Debugoutput = 'html';
        $mail->SMTPDebug = 2;
        $mail->SMTPAuth = true;
        $mail->Host = 'mail.tytl.pe';
        $mail->Port = 25;
        $mail->Username = "facturacion@tytl.pe";
        $mail->Password = "%Facturacion2017%";
        $mail->setFrom('facturacion@tytl.pe', 'Sistema Facturación Grupo TyTL');

        if ($this->uri->segment(4) == "enviar_cliente") {
            $mail->addAddress($comprobante['cli_email']);
            $mail->AddCC('mdptm@tytl.com.pe');
            $mail->AddCC('hdelacruz@tytl.com.pe');
            $mail->AddCC('cobranzas@tytl.com.pe');
            $mail->AddCC('jvelasco@tytl.com.pe');
            $mail->AddCC('carmen@tytl.com.pe');
        }

        if ($this->uri->segment(4) == "enviar_equipo") {
            $mail->addAddress('hdelacruz@tytl.com.pe');
            $mail->AddCC('mdptm@tytl.com.pe');
            $mail->AddCC('cobranzas@tytl.com.pe');
            $mail->AddCC('jvelasco@tytl.com.pe');
            $mail->AddCC('carmen@tytl.com.pe');
        }

        $mail->Subject = 'Facturación Electronica ' . $comprobante['empresa'] . ' ' . strtoupper($comprobante['descripcion1']) . ' - ' . $comprobante['cli_razon_social'];

        $body = '<h2>Comprobante de Pago Electronico</h2>';
        $body .= 'Estimado Cliente:<br><br>';
        $body .= 'Cliente: ' . $comprobante['cli_razon_social'] . '<br><br>';
        $body .= 'Adjunto a la presente se servirá encontrar nuestro comprobante de pago electrónico.' . '<br><br>';
        $body .= 'Por favor cualquier aclaración enviar un correo electrónico a cobranzas@tytl.com.pe' . '<br><br>';
        $body .= 'Muchas gracias,<br><br>';
        $body .= '<b>' . $comprobante['empresa'] . ' ' . strtoupper($comprobante['descripcion1']) . '</b><br><br>';
        $body .= 'Para confirmar la validez de su comprobante de pago, ingrese a la siguiente Dirección de Sunat:<br> <a href="http://e-consulta.sunat.gob.pe/ol-ti-itconsvalicpe/ConsValiCpe.htm">Consulta SUNAT</a><br>';

        $mail->IsHTML(true);
        $mail->msgHTML($body);

        //$mail->Body = $body;
        //$mail->AltBody = 'Resumen Total';

        $mail->AddAttachment(APPPATH . "files_pdf/comprobantes/" . $comprobante['cliente_id'] . $comprobante['comprobante_id'] . ".pdf");
        $mail->IsSendMail();

        if (!$mail->send()) {
            $this->session->set_flashdata('mensaje', 'Error');
        } else {
            if ($this->uri->segment(4) == "enviar_cliente") {
                $this->comprobantes_model->modificar(array('enviado_cliente' => 1), $comprobante['comprobante_id']);
            }

            if ($this->uri->segment(4) == "enviar_equipo") {
                $this->comprobantes_model->modificar(array('enviado_equipo' => 1), $comprobante['comprobante_id']);
            }
            $this->session->set_flashdata('mensaje', 'Comprobante enviado correctamente');
        }
        redirect(base_url() . "index.php/comprobantes/index/" . $comprobante['empresa_id']);
        ///////////////////////////////////////////////////////////////////////////////////////////////////               
    }

    public function popoverSunat($estado = '', $cliente_id = '') {
        /*         * ************************************************ */
        $comprobante = $this->comprobantes_model->select($_GET['comprobanteId']);
        $items = $this->items_model->select('', $_GET['comprobanteId']);
        $cliente = $this->clientes_model->select($_GET['clienteId']);
        $empresa = $this->empresas_model->select($comprobante['empresa_id']);

        $fichero = 'http://190.107.181.252/webServiceSunat/webServiceSunat.php?comprobante=R' . $comprobante['empresa_ruc'] . '-' . $comprobante['tipo_documento_codigo'] . '-' . $comprobante['serie'] . '-' . $comprobante['numero'] . '.zip&empresa_id=' . $comprobante['empresa_id'];
        //echo $fichero;
        $obj = json_decode(file_get_contents($fichero), true);
        //var_dump($obj);

        if (!empty($obj)) {
            $codSunat = $obj['codSunat'];
            $desSunat = $obj['desSunat'];

            echo $codSunat . '<br>' . $desSunat;
        } else {
            echo 'Fichero no encontrado';
        }
    }

    public function rptaSunat() {
        $query = $this->comprobantes_model->selecRptaSunat(1, 1);
        //var_dump($query);
        $numrow = count($query);
        $nuevoEstado = array();
        if ($numrow > 0) {
            foreach ($query as $comprobante) {
                //var_dump($comprobante);            
                $fichero = 'http://190.107.181.252/webServiceSunat/webServiceSunat.php?comprobante=R' . $comprobante['empresa_ruc'] . '-' . $comprobante['tipo_documento_codigo'] . '-' . $comprobante['serie'] . '-' . $comprobante['numero'] . '.zip&empresa_id=' . $comprobante['empresa_id'];
                $obj = json_decode(file_get_contents($fichero), true);

                //var_dump($obj);
                if (!empty($obj)) {
                    $codSunat = $obj['codSunat'];
                    $desSunat = $obj['desSunat'];

                    if ($codSunat == 0) {
                        $this->comprobantes_model->modificar(array('estado_sunat' => $codSunat), $comprobante['comprobante_id']);
                    }

                    $nuevoEstado[] = array(
                        'comprobante_id' => $comprobante['comprobante_id'],
                        'cliente_id' => $comprobante['cliente_id'],
                        'codSunat' => $codSunat,
                        'desSunat' => $desSunat
                    );
                }
            }
        }
        die(json_encode(array('status' => 'resultados', 'datos' => $nuevoEstado)));
    }

    public function xmlSunat($comprobante_id = '', $cliente_id = '') {
        $comprobante = $this->comprobantes_model->select($comprobante_id);
        //$items       = $this->items_model->select('',$comprobante_id);
        //$cliente     = $this->clientes_model->select($cliente_id);
        //$empresa     = $this->empresas_model->select($comprobante_id);                                               

        $fichero = 'http://190.107.181.252/webServiceSunat/xmlSunat.php?comprobante=' . $comprobante['empresa_ruc'] . '-' . $comprobante['tipo_documento_codigo'] . '-' . $comprobante['serie'] . '-' . $comprobante['numero'] . '.xml&empresa_id=' . $comprobante['empresa_id'];
        //echo $fichero;exit;
        $obj = json_decode(file_get_contents($fichero), true);
        //var_dump($obj);exit;
        //header("Content-type: text/xml; charset=utf-8");
        header('Content-type: text/xml; content="text/html; charset=UTF-8"');
        echo $obj['contenido'];
    }

    public function cdrSunat($comprobante_id = '', $cliente_id = '') {
        $comprobante = $this->comprobantes_model->select($comprobante_id);
        $items = $this->items_model->select('', $comprobante_id);
        $cliente = $this->clientes_model->select($cliente_id);
        $empresa = $this->empresas_model->select($comprobante_id);

        $fichero = 'http://190.107.181.252/webServiceSunat/cdrSunat.php?comprobante=R' . $comprobante['empresa_ruc'] . '-' . $comprobante['tipo_documento_codigo'] . '-' . $comprobante['serie'] . '-' . $comprobante['numero'] . '.zip&empresa_id=' . $comprobante['empresa_id'];
        //echo $fichero;exit;
        $obj = json_decode(file_get_contents($fichero), true);

        header("Content-type: text/xml; charset=utf-8");
        echo $obj['contenido'];
    }

    public function buscador_cliente() {
        $abogado = $this->input->get('term');
        echo json_encode($this->clientes_model->selectAutocomplete($abogado, 'activo'));
    }

    public function guardar_comprobante() {
        // Recibir Datos mediante Ajax
        //$comprobante = json_decode(stripslashes($_POST['comprobante']),true);
        //$items = json_decode(stripslashes($_POST['items']));
        //var_dump($comprobante);        
        //var_dump($comprobante);exit;

        $fecha_de_emision = new DateTime($_POST['fecha_de_emision']);
        $fecha_de_emision = $fecha_de_emision->format('Y-m-d');

        $fecha_de_vencimiento = new DateTime($_POST['fecha_de_vencimiento']);
        $fecha_de_vencimiento = $fecha_de_vencimiento->format('Y-m-d');
        $operacion_gratuita = isset($_POST['operacion_gratuita']) ? 1 : 0;
        $operacion_cancelada = isset($_POST['operacion_cancelada']) ? 1 : 0;

        $comprobante = array(
            'cliente_id' => $_POST['cliente_id'],
            'tipo_documento_id' => $_POST['tipo_documento'],
            'serie' => strtoupper($_POST['serie']),
            'numero' => $_POST['numero'],
            'fecha_de_emision' => $fecha_de_emision,
            'moneda_id' => $_POST['moneda_id'],
            'fecha_de_vencimiento' => $fecha_de_vencimiento,
            'operacion_gratuita' => $operacion_gratuita,
            'operacion_cancelada' => $operacion_cancelada,
            'observaciones' => $_POST['observaciones'],
            'empresa_id' => $_POST['empresa'],
            'tipo_pago_id' => $_POST['tipo_pago'],
            'descuento_global' => $_POST['descuento_global'],
            'total_exonerada' => $_POST['total_exonerada'],
            'total_inafecta' => $_POST['total_inafecta'],
            'total_gravada' => $_POST['total_gravada'],
            'total_igv' => $_POST['total_igv'],
            'total_gratuita' => $_POST['total_gratuita'],
            'total_otros_cargos' => $_POST['total_otros_cargos'],
            'total_descuentos' => $_POST['total_descuentos'],
            'total_a_pagar' => $_POST['total_a_pagar'],
            'empleado_insert' => $this->session->userdata('empleado_id'),
            'fecha_insert' => date("Y-m-d H:i:s")
        );

        if ($_POST['moneda_id'] > 1) {
            $comprobante = array_merge($comprobante, array('tipo_de_cambio' => $_POST['tipo_de_cambio']));
        }

        if ($_POST['tipo_documento'] <= 3) {
            if ($_POST['tipo_documento'] == 1) {// facturas
                $detraccion = isset($_POST['detraccion']) ? 1 : 0;
                $comprobante = array_merge($comprobante, array('detraccion' => $detraccion));
                if ($this->input->post('tipo_de_detraccion') != '')
                    $comprobante = array_merge($comprobante, array('elemento_adicional_id' => $this->input->post('tipo_de_detraccion')));
                if ($this->input->post('porcentaje_de_detraccion') != '')
                    $comprobante = array_merge($comprobante, array('porcentaje_de_detraccion' => $this->input->post('porcentaje_de_detraccion')));
                if ($this->input->post('total_detraccion') != '')
                    $montoTotalDetraccion = $this->input->post('total_detraccion');

                if ($_POST['moneda_id'] > 1) {
                    $tipoCambio = $this->tipo_cambio_model->selectJson($_POST['moneda_id']);
                    $tipoCambio = $tipoCambio['tipo_cambio'];

                    $montoTotalDetraccion = $this->input->post('total_detraccion') * $tipoCambio;
                }
                $comprobante = array_merge($comprobante, array('total_detraccion' => $montoTotalDetraccion));
            }
        } else {
            if ($_POST['tipo_documento'] == 7) {
                if ($this->input->post('tipo_ncredito') != '') {
                    $tipoNota = explode('*', $this->input->post('tipo_ncredito'));
                    $comprobante = array_merge($comprobante, array('tipo_nota_id' => $tipoNota[0]));
                    $comprobante = array_merge($comprobante, array('tipo_nota_codigo' => $tipoNota[1]));
                }
            }
            if ($_POST['tipo_documento'] == 8) {
                if ($this->input->post('tipo_ndebito') != '') {
                    $tipoNota = explode('*', $this->input->post('tipo_ndebito'));
                    $comprobante = array_merge($comprobante, array('tipo_nota_id' => $tipoNota[0]));
                    $comprobante = array_merge($comprobante, array('tipo_nota_codigo' => $tipoNota[1]));
                }
            }
            if ($this->input->post('comp_adjunto') != '')
                $comprobante = array_merge($comprobante, array('com_adjunto_id' => $this->input->post('comp_adjunto')));
        }
        //var_dump($comprobante);exit;
        // Insertar datos del documento
        $comprobante_id = $this->comprobantes_model->insertar($comprobante);
        // GUARDANDO ITEMS

        $tipo_item_id = $_POST['tipo_venta'];
        $descripcion = $_POST['descripcion'];
        $cantidad = $_POST['cantidad'];
        $tipo_igv_id = $_POST['tipo_igv'];
        $importe = $_POST['importe'];
        $subtotal = $_POST['subtotal'];
        $total = $_POST['total'];
        $igv = $_POST['igv'];


        $i = 0;
        foreach ($tipo_item_id as $item) {
            $consulta = "INSERT INTO items (comprobante_id, tipo_item_id, descripcion, cantidad, tipo_igv_id, importe, subtotal, igv, total) VALUES ('" . $comprobante_id . "', '" . $tipo_item_id[$i] . "' , '" . $this->quitarSaltoDeLinea($descripcion[$i]) . "' , '" . $cantidad[$i] . "' , '" . $tipo_igv_id[$i] . "' , '" . $importe[$i] . "' , '" . $subtotal[$i] . "' , '" . $igv[$i] . "' , '" . $total[$i] . "')";
            //echo $consulta;
            $i++;
            //$resultado = mysql_query($consulta, $conexion);
            $resultado = $this->db->query(sprintf($consulta));
        }


        //Esto me parece que es para Notas de crédito.
//        if($_POST['ajaxId'] !== '0')
//        $this->ValidarComprobante($comprobante_id);

        redirect(base_url() . "index.php/comprobantes/index/" . $_POST['empresa']);
        //echo json_encode($comprobante_id);                
    }

    public function updateEstadoComprobante($comprobante_id = '', $operacion_cancelada = '') {
        $array = array();
        if (!empty($operacion_cancelada) || $operacion_cancelada != '') {
            //echo $operacion_cancelada;exit;
            $array = array_merge($array, array('operacion_cancelada' => $operacion_cancelada));
        }
        $this->comprobantes_model->modificar($array, $comprobante_id);
        redirect(base_url() . "index.php/comprobantes/index");
    }

    public function modificar_comprobante() {

        $fecha_de_emision = new DateTime($_POST['fecha_de_emision']);
        $fecha_de_emision = $fecha_de_emision->format('Y-m-d');

        $fecha_de_vencimiento = new DateTime($_POST['fecha_de_vencimiento']);
        $fecha_de_vencimiento = $fecha_de_vencimiento->format('Y-m-d');


        $operacion_gratuita = isset($_POST['operacion_gratuita']) ? 1 : 0;
        $operacion_cancelada = isset($_POST['operacion_cancelada']) ? 1 : 0;

        $comprobante = array(
            'cliente_id' => $_POST['cliente_id'],
            'tipo_documento_id' => $_POST['tipo_documento'],
            'serie' => strtoupper($_POST['serie']),
            'numero' => $_POST['numero'],
            'fecha_de_emision' => $fecha_de_emision,
            'moneda_id' => $_POST['moneda_id'],
            'tipo_de_cambio' => $_POST['tipo_de_cambio'],
            'fecha_de_vencimiento' => $fecha_de_vencimiento,
            'operacion_gratuita' => $operacion_gratuita,
            'operacion_cancelada' => $operacion_cancelada,
            'observaciones' => $_POST['observaciones'],
            'empresa_id' => $_POST['empresa'],
            'tipo_pago_id' => $_POST['tipo_pago'],
            'descuento_global' => $_POST['descuento_global'],
            'total_exonerada' => $_POST['total_exonerada'],
            'total_inafecta' => $_POST['total_inafecta'],
            'total_gravada' => $_POST['total_gravada'],
            'total_igv' => $_POST['total_igv'],
            'total_gratuita' => $_POST['total_gratuita'],
            'total_otros_cargos' => $_POST['total_otros_cargos'],
            'total_descuentos' => $_POST['total_descuentos'],
            'total_a_pagar' => $_POST['total_a_pagar']
        );


        //tipo_documentos:  1 factura; 3 boleta; 7 nota de credito; 8 nota de debito
        if ($_POST['tipo_documento'] <= 3) {
            if ($_POST['tipo_documento'] == 1) {
                $detraccion = isset($_POST['detraccion']) ? 1 : 0;
                $comprobante = array_merge($comprobante, array('detraccion' => $detraccion));
                if ($this->input->post('tipo_de_detraccion') != '')
                    $comprobante = array_merge($comprobante, array('elemento_adicional_id' => $this->input->post('tipo_de_detraccion')));
                if ($this->input->post('porcentaje_de_detraccion') != '')
                    $comprobante = array_merge($comprobante, array('porcentaje_de_detraccion' => $this->input->post('porcentaje_de_detraccion')));
                if ($this->input->post('total_detraccion') != '')
                    $montoTotalDetraccion = $this->input->post('total_detraccion');
                if ($_POST['moneda_id'] > 1) {
                    $tipoCambio = $this->tipo_cambio_model->selectJson($_POST['moneda_id']);
                    $tipoCambio = $tipoCambio['tipo_cambio'];

                    $montoTotalDetraccion = $this->input->post('total_detraccion') * $tipoCambio;
                }
                $comprobante = array_merge($comprobante, array('total_detraccion' => $montoTotalDetraccion));
            }
            if ($_POST['tipo_documento'] == 3) {
                $comprobante = array_merge($comprobante, array('detraccion' => NULL));
                $comprobante = array_merge($comprobante, array('elemento_adicional_id' => NULL));
                $comprobante = array_merge($comprobante, array('porcentaje_de_detraccion' => NULL));
                $comprobante = array_merge($comprobante, array('total_detraccion' => NULL));
            }
            $comprobante = array_merge($comprobante, array('tipo_nota_id' => NULL));
            $comprobante = array_merge($comprobante, array('com_adjunto_id' => NULL));
        } else {
            if ($_POST['tipo_documento'] == 7) {
                if ($this->input->post('tipo_ncredito') != '') {
                    $tipoNota = explode('*', $this->input->post('tipo_ncredito'));
                    $comprobante = array_merge($comprobante, array('tipo_nota_id' => $tipoNota[0]));
                    $comprobante = array_merge($comprobante, array('tipo_nota_codigo' => $tipoNota[1]));
                }
            }
            if ($_POST['tipo_documento'] == 8) {
                if ($this->input->post('tipo_ndebito') != '') {
                    $tipoNota = explode('*', $this->input->post('tipo_ndebito'));
                    $comprobante = array_merge($comprobante, array('tipo_nota_id' => $tipoNota[0]));
                    $comprobante = array_merge($comprobante, array('tipo_nota_codigo' => $tipoNota[1]));
                }
            }
            if ($this->input->post('comp_adjunto') != '')
                $comprobante = array_merge($comprobante, array('com_adjunto_id' => $this->input->post('comp_adjunto')));

            $comprobante = array_merge($comprobante, array('detraccion' => NULL));
            $comprobante = array_merge($comprobante, array('elemento_adicional_id' => NULL));
            $comprobante = array_merge($comprobante, array('porcentaje_de_detraccion' => NULL));
            $comprobante = array_merge($comprobante, array('total_detraccion' => NULL));
        }

        $comprobante_id = $this->uri->segment(3);
        $this->comprobantes_model->modificar($comprobante, $comprobante_id);


        $item_id = $_POST['item_id'];
        $tipo_item_id = $_POST['tipo_venta'];
        $descripcion = $_POST['descripcion'];
        $cantidad = $_POST['cantidad'];
        $tipo_igv_id = $_POST['tipo_igv'];
        $importe = $_POST['importe'];
        $subtotal = $_POST['subtotal'];
        $total = $_POST['total'];
        $igv = $_POST['igv'];

        $i = 0;
        foreach ($tipo_item_id as $item) {
            if (!isset($item_id[$i])) {
                $consulta = "INSERT INTO items (comprobante_id, tipo_item_id, descripcion, cantidad, tipo_igv_id, importe, subtotal, igv, total) VALUES ('" . $comprobante_id . "', '" . $tipo_item_id[$i] . "' , '" . $descripcion[$i] . "' , '" . $cantidad[$i] . "' , '" . $tipo_igv_id[$i] . "' , '" . $importe[$i] . "' , '" . $subtotal[$i] . "' , '" . $igv[$i] . "' , '" . $total[$i] . "')";
                $resultado = $this->db->query($consulta);
            } else {
                $consulta = "UPDATE items SET tipo_item_id ='" . $tipo_item_id[$i] . "',descripcion ='" . $descripcion[$i] . "', cantidad ='" . $cantidad[$i] . "', tipo_igv_id='" . $tipo_igv_id[$i] . "',importe='" . $importe[$i] . "', subtotal='" . $subtotal[$i] . "', igv='" . $igv[$i] . "', total='" . $total[$i] . "' WHERE id ='" . $item_id[$i] . "'";
                $resultado = $this->db->query($consulta);
            }
            $i++;
        }
        redirect(base_url() . "index.php/comprobantes/index/" . $_POST['empresa']);
    }

    public function txt($envio = 0, $comprobante_id = '') {

        $data = $this->clientes_model->select();
        $comprobante = $this->comprobantes_model->select($comprobante_id);
        $items = $this->items_model->select('', $comprobante_id);
        $detraccion = $this->elemento_adicionales_model->select('', '', 'activo');

        //TIPO EMPRESA
        $ruta = '';
        if ($comprobante['empresa_id'] == 1) {
            $ruta = 'sunat_archivos/sfs/DATA/';
        }
        if ($comprobante['empresa_id'] == 2) {
            $ruta = 'neple/sunat_archivos/sfs/DATA/';
        }

        if ($envio == 0) {
            if ($comprobante['tipo_documento_id'] < 4) {
                // FACTURA , BOLETA

                $sql = 'ftp://Pruebas:1475963@190.107.181.254:21/' . $ruta . $comprobante['empresa_ruc'] . '-' . $comprobante['tipo_documento_codigo'] . '-' . $comprobante['serie'] . '-' . $comprobante['numero'] . '.CAB';
                $f = fopen($sql, 'w');
                $linea = "01|" . $comprobante['fecha_sunat'] . '||' . $comprobante['tipo_cliente_codigo'] . "|" . trim($comprobante['cliente_ruc']) . "|" . $comprobante['cli_razon_social'] . "|" . $comprobante['abrstandar'] . "|0.00|0.00|0.00|" . $comprobante['total_gravada'] . "|" . $comprobante['total_inafecta'] . "|" . $comprobante['total_exonerada'] . "|" . $comprobante['total_igv'] . "|0.00|0.00|" . $comprobante['total_a_pagar'] . "|\r\n";
                fwrite($f, $linea);
                fclose($f);

                $f = fopen('ftp://Pruebas:1475963@190.107.181.254:21/' . $ruta . $comprobante['empresa_ruc'] . '-' . $comprobante['tipo_documento_codigo'] . '-' . $comprobante['serie'] . '-' . $comprobante['numero'] . '.DET', 'w');
                foreach ($items as $value) {
                    $descripction = $this->sanear_string(utf8_decode($value['descripcion']));
                    $linea = "NIU" . "|" . $value['cantidad'] . "|||" . str_replace("&", "Y", trim(utf8_decode($descripction))) . "|" . $value['importe'] . "|0.00|" . $value['igv'] . "|" . $value['tipo_igv_codigo'] . "|0.00||" . $value['importe'] . "|" . $value['total'] . "|\r\n";
                    fwrite($f, $linea);
                }
                fclose($f);
            } else {
                //NOTA DE CREDITO , DEBITO
                $nota = $this->comprobantes_model->select($comprobante['com_adjunto_id']);
                $f = fopen('ftp://Pruebas:1475963@190.107.181.254:21/' . $ruta . $comprobante['empresa_ruc'] . '-' . $comprobante['tipo_documento_codigo'] . '-' . $comprobante['serie'] . '-' . $comprobante['numero'] . '.NOT', 'w');
                $linea = $comprobante['fecha_sunat'] . "|" . $comprobante['tipo_nota_codigo'] . "|INTERES|01|" . $nota['serie'] . "-" . $nota['numero'] . "|" . $comprobante['tipo_cliente_codigo'] . "|" . $comprobante['cliente_ruc'] . "|" . $comprobante['cli_razon_social'] . "|" . $comprobante['abrstandar'] . "|0.00|" . $comprobante['total_gravada'] . "|" . $comprobante['total_inafecta'] . "|" . $comprobante['total_exonerada'] . "|" . $comprobante["total_igv"] . "|0.00|0.00|" . $comprobante['total_a_pagar'] . "\r\n";
                fwrite($f, $linea);
                fclose($f);
                $f = fopen('ftp://Pruebas:1475963@190.107.181.254:21/' . $ruta . $comprobante['empresa_ruc'] . '-' . $comprobante['tipo_documento_codigo'] . '-' . $comprobante['serie'] . '-' . $comprobante['numero'] . '.DET', 'w');
                foreach ($items as $value) {
                    $descripction = $this->sanear_string(utf8_decode($value['descripcion']));
                    $linea = "NIU" . "|" . $value['cantidad'] . "|||" . str_replace("&", "Y", trim($descripction)) . "|" . $value['importe'] . "|0.00|" . $value['igv'] . "|" . $value['tipo_igv_codigo'] . "|0.00|01|" . $value['importe'] . "|" . $value['total'] . "\r\n";
                    fwrite($f, $linea);
                }
                fclose($f);
            }
            $this->comprobantes_model->modificar(array('enviado_sunat' => 1), $comprobante_id);
            $this->session->set_flashdata('mensaje', 'Envio exitoso!');
        } else {
            //  COMUNICACION DE BAJA TXT
            $fecha1 = date("Ymd");
            $fecha2 = date("Y-m-d");
            $numero = $this->comprobante_anulados_model->maxNumero($fecha2) + 1;

            $f = fopen('ftp://Pruebas:1475963@190.107.181.254:21/' . $ruta . $comprobante['empresa_ruc'] . '-RA-' . $fecha1 . '-' . $numero . '.CBA', 'w');
            $linea = $comprobante['fecha_sunat'] . "|" . $fecha2 . "|" . $comprobante['tipo_documento_codigo'] . "|" . $comprobante['serie'] . '-' . $comprobante['numero'] . "|ERROR|\r\n";
            fwrite($f, $linea);
            fclose($f);

            $dataAnular = array(
                'fecha' => $fecha2,
                'numero' => $numero,
                'comprobante_id' => $comprobante_id,
                'empleado_insert' => $this->session->userdata('empleado_id'),
                'fecha_insert' => date("Y-m-d H:i:s")
            );
            $this->comprobante_anulados_model->insertar($dataAnular);
            $this->comprobantes_model->modificar(array('fecha_de_baja' => $fecha2, 'anulado' => 1), $comprobante_id);
            $this->session->set_flashdata('mensaje', 'Anulación exitosa!');
        }
        redirect(base_url() . "index.php/comprobantes/index/" . $comprobante['empresa_id']);
    }

    public function comunicacionBaja() {

        $data['comprobante'] = $this->comprobantes_model->select('', '', '', '', '', '', '', '', 1);
        $this->load->view('templates/header_administrador');
        $this->load->view('comprobantes/comunicacionBaja', $data);
        $this->load->view('templates/footer');
    }

    public function estadoBaja($comprobante_id = '', $cliente_id = '') {
        $comprobante = $this->comprobantes_model->select($comprobante_id);
        $items = $this->items_model->select('', $comprobante_id);
        $cliente = $this->clientes_model->select($cliente_id);
        $empresa = $this->empresas_model->select($comprobante_id);

        //20110152711-RA-20161214-011.CBA            
        $fecha_de_baja = $comprobante['fecha_de_baja'];
        $date = new DateTime($fecha_de_baja);
        $fecha_de_baja = $date->format('Ymd');

        $fichero = 'http://190.107.181.252/webServiceSunat/xmlSunat.php?comprobante=' . $comprobante['empresa_ruc'] . '-RA-' . $fecha_de_baja . '-011' . '.xml';
        $obj = json_decode(file_get_contents($fichero), true);

        //header("Content-type: text/xml; charset=utf-8");
        header('Content-type: text/xml; content="text/html; charset=UTF-8"');
        echo $obj['contenido'];
    }

    public function tipoCambio() {
        $moneda_id = $this->input->post('moneda_id');

        $json = $this->tipo_cambio_model->selectJson($moneda_id);
        //var_dump($json);                                    
        echo json_encode($json);
    }

    public function selectUltimoReg() {
        //$row = $this->comprobantes_model->selectUltimoReg($_POST['serieId']);
        //$numero = $this->comprobantes_model->selecMaximoNumero($empresa_id, $tipo_documento_id, $serie) + 1;
        $row = $this->comprobantes_model->selecMaximoNumero2($this->uri->segment(3), $this->uri->segment(4), $this->uri->segment(5));
        echo json_encode($row);
    }

    public function sanear_string($string) {

        $string = trim(utf8_encode($string));
//        $string = str_replace(
//            array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
//            array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
//            $string
//        );
        $string = str_replace(
                array('à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'), array('a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'), $string
        );

//        $string = str_replace(
//            array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
//            array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
//            $string
//        );
        $string = str_replace(
                array('è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'), array('e', 'e', 'e', 'E', 'E', 'E', 'E'), $string
        );

//        $string = str_replace(
//            array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
//            array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
//            $string
//        );
        $string = str_replace(
                array('ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'), array('i', 'i', 'i', 'I', 'I', 'I', 'I'), $string
        );

//        $string = str_replace(
//            array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
//            array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
//            $string
//        );
        $string = str_replace(
                array('ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'), array('o', 'o', 'o', 'O', 'O', 'O', 'O'), $string
        );

//        $string = str_replace(
//            array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
//            array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
//            $string
//        );        
        $string = str_replace(
                array('ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'), array('u', 'u', 'u', 'U', 'U', 'U', 'U'), $string
        );

//        $string = str_replace(
//            array('ñ', 'Ñ', 'ç', 'Ç'),
//            array('n', 'N', 'c', 'C',),
//            $string
//        );
        $string = str_replace(
                array('ç', 'Ç'), array('c', 'C',), $string
        );

        //Esta parte se encarga de eliminar cualquier caracter extraño
//        $string = str_replace(
//            array("\\", "¨", "º", "-", "~",
//                 "#", "@", "|", "!", "\"",
//                 "·", "$", "%", "&", "/",
//                 "(", ")", "?", "'", "¡",
//                 "¿", "[", "^", "`", "]",
//                 "+", "}", "{", "¨", "´",
//                 ">", "< ", ";", ",", ":",
//                 ".", " "),
//            '',
        $string = str_replace(
                array("\\", "¨", "º", "-", "~",
            "|", "!", "\"",
            "·", "&", "/",
            "(", ")", "'", "¡",
            "¿", "[", "^", "`", "]",
            "}", "{", "¨", "´"
                ), '', $string
        );
        $string = str_replace(
                array("\n"
                ), ' ', $string
        );
        return $string;
    }

    function quitarSaltoDeLinea($cadenaDeTexto) {
        $buscar = array(chr(13) . chr(10), "\r\n", "\n", "\r");
        $reemplazar = array(" ", " ", " ", " ");
        $cadena = str_ireplace($buscar, $reemplazar, $cadenaDeTexto);        
        return $cadena;
    }

}

?>