<?PHP
use PhpOffice\PhpSpreadsheet\Spreadsheet;    
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Clientes extends CI_Controller {

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
        $almacen_id = $this->session->userdata("almacen_id");
        if (empty($empleado_id) or empty($almacen_id)) {
            $this->session->set_flashdata('mensaje', 'No existe sesion activa');
            redirect(base_url());
        }
    }  

    public function subirclientesUi()
    {


        $this->load->view('clientes/subir_clientes_ui');
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
            $estados_cliente = $estado_cliente->activo;
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


        $empleado_id = '';
        if (($this->input->post('empleado_id') != '') && ($this->input->post('empleado') != '')) {
            $empleado_id = $this->input->post('empleado_id');
        }
        
        $tipo_cliente = '';
        if(($this->input->post('tipo_cliente')!= '') && ($this->input->post('tipo_cliente')!="todos")){           
            $tipo_cliente= $this->input->post('tipo_cliente');
        }
        
                
        //PAGINACION - PAGINACION
        $inicio = 0;
        $limite = 20;
        $tipo_contrato = '';
        if($pagina){
            //$inicio = $pagina;
            $inicio = ($pagina-1)*$limite;
        } 
        
         $data['clientes'] = $this->clientes_model->select('', $estados_cliente, $cliente_id, '',$tipo_cliente, $empleado_id, $estados_contrato,$limite,$inicio);
        
        
         $config['base_url']    = base_url().'index.php/clientes/index/';
         $config['total_rows']  = count($this->clientes_model->select('', $estados_cliente, $cliente_id, '',$tipo_cliente, $tipo_contrato, $estados_contrato));
         $config['per_page']    = $limite;
         $config['uri_segment'] = 3;        
         //$choice = $config['total_rows']/$config['per_page'];
         $config['num_links'] = 2;
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


        //echo 123;exit;
        $this->accesos_model->menuGeneral();
        $this->load->view('clientes/index', $data);
        $this->load->view('templates/footer');
    }


    public function guardarSubidaClientes() {
        
        $archivo = $_FILES['files'];
        
        //establecemos la ruta desde donde leeremos
        $rutaArchivo = $archivo['tmp_name'];
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
        $spreadsheet = $reader->load($rutaArchivo);
        $sheet = $spreadsheet->getActiveSheet();
        $arrayClientes = array();

        $highestRow = $spreadsheet->getActiveSheet()->getHighestRow();

        foreach($sheet->getRowIterator(2) as $row)
        {
            $cliente = array();
            $ruc = $sheet->getCellByColumnAndRow('2', $row->getRowIndex())->getValue();

            $cliente['ruc'] = $ruc;
            $rsRuc = $this->db->from('clientes')
                              ->where('ruc',$ruc)
                              ->get()
                              ->row();                              

            if ($rsRuc){
                continue;                     
            }                              

            //echo $ruc;exit;            
            $tipoCliente = $sheet->getCellByColumnAndRow('3', $row->getRowIndex())->getValue();


            $rstipoCliente = $this->db->from('tipo_clientes')
                                    ->where('tipo_cliente',$tipoCliente)
                                    ->get()
                                    ->row();


            $cliente['tipo_cliente'] = $tipoCliente;
            $cliente['tipo_cliente_id'] = $rstipoCliente->id;

            

            $cliente['razon_social'] =  $sheet->getCellByColumnAndRow('4', $row->getRowIndex())->getValue();            

            if ($cliente['razon_social'] == '') {                
                continue;
            }
            
            /*verificamos la razon social  ha sido registrada*/
            $cliente['razon_social'] = $cliente['razon_social'];
            $cliente['empresa_id']  = 1;
            $cliente['domicilio1'] = $sheet->getCellByColumnAndRow('5', $row->getRowIndex())->getValue();
            $cliente['email'] = $sheet->getCellByColumnAndRow('6', $row->getRowIndex())->getValue();
            $cliente['telefono_fijo_1'] = $sheet->getCellByColumnAndRow('7', $row->getRowIndex())->getValue();
            $cliente['telefono_movil_1'] = $sheet->getCellByColumnAndRow('8', $row->getRowIndex())->getValue();
            $cliente['descuento'] = $sheet->getCellByColumnAndRow('9', $row->getRowIndex())->getValue();
            $cliente['linea_de_credito'] = $sheet->getCellByColumnAndRow('10', $row->getRowIndex())->getValue();
            $cliente['zona'] = $sheet->getCellByColumnAndRow('11', $row->getRowIndex())->getValue();
            $cliente['puntos'] = $sheet->getCellByColumnAndRow('12', $row->getRowIndex())->getValue();
            $cliente['bonus'] = $sheet->getCellByColumnAndRow('13', $row->getRowIndex())->getValue();            


            $this->db->insert('clientes',$cliente);                    
    }}

    public function ExportarExcel($id='',$estado='',$tipo='') {

        if($this->uri->segment(3)!='0') {
            $this->db->where('id', $this->uri->segment(3));
        }
        if($this->uri->segment(4)!='0') {
            $this->db->where('activo', 'activo');
        }
        if($this->uri->segment(5)!='0') {
            $this->db->where('tipo_cliente_id', $this->uri->segment(5));
        }

        $this->db->from("clientes");                              
        $query = $this->db->get();
        $result = $query->result();
        //print_r($result);exit();
        
        /*EXPORTAR A EXCEL*/
        $spreadsheet = new Spreadsheet();         
        // Set workbook properties
        $spreadsheet->getProperties()->setCreator('Rob Gravelle')
                ->setLastModifiedBy('Rob Gravelle')
                ->setTitle('A Simple Excel Spreadsheet')
                ->setSubject('PhpSpreadsheet')
                ->setDescription('A Simple Excel Spreadsheet generated using PhpSpreadsheet.')
                ->setKeywords('Microsoft office 2013 php PhpSpreadsheet')
                ->setCategory('Test file');         
        
        $i=2;

        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(20);

       
    

        $spreadsheet->getActiveSheet()->getStyle("A1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("B1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("C1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("D1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("E1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("F1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("G1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("H1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("I1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("J1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("K1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("L1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("M1")->getFont()->setBold(true);


        $spreadsheet->getActiveSheet()->getStyle('B')
        ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

    

        $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A1', 'N°')
                ->setCellValue('B1', 'RUC/DNI')
                ->setCellValue('C1', 'CLIENTE')
                ->setCellValue('D1', 'RAZON SOCIAL/NOMBRES')
                ->setCellValue('E1', 'DOMICILIO1')
                ->setCellValue('F1', 'EMAIL')
                ->setCellValue('G1', 'TELEFONO_FIJO_1')
                ->setCellValue('H1', 'TELEFONO_MOVIL_1')
                ->setCellValue('I1', 'DESCUENTO')
                ->setCellValue('J1', 'LINEA_DE_CREDITO')
                ->setCellValue('K1', 'ZONA')
                ->setCellValue('L1', 'PUNTOS')
                ->setCellValue('M1', 'BONUS');
                
            
               // ->setCellValue('E1', 'EMPRESA');

        $spreadsheet->getActiveSheet()->setTitle('clientes');
        foreach ($result as $value) {
            $empresa = $this->db->from('empresas')
                            ->where('id',$value->empresa_id)
                            ->get()
                            ->row();

            $spreadsheet->getActiveSheet()
                        ->setCellValue('A'.$i, $value->id)
                        ->setCellValue('B'.$i, $value->ruc)
                        ->setCellValue('C'.$i, $value->tipo_cliente)
                        ->setCellValue('D'.$i, $value->nombres.' '.$value->razon_social)
                        ->setCellValue('E'.$i, $value->domicilio1)
                        ->setCellValue('F'.$i, $value->email)
                        ->setCellValue('G'.$i, $value->telefono_fijo_1)
                        ->setCellValue('H'.$i, $value->telefono_movil_1)
                        ->setCellValue('I'.$i, $value->descuento)
                        ->setCellValue('J'.$i, $value->linea_de_credito)
                        ->setCellValue('K'.$i, $value->zona)
                        ->setCellValue('L'.$i, $value->puntos)
                        ->setCellValue('M'.$i, $value->bonus);
                        //->setCellValue('E'.$i, $empresa->empresa);
            $i++;
        }
        
        $spreadsheet->setActiveSheetIndex(0);         
        // Redirect output to a client's web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="data_cliente.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');       
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;

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

        $carpeta = "images/";
        opendir($carpeta);
        $destino = $carpeta.$_FILES['foto']['name'];
        copy($_FILES['foto']['tmp_name'], $destino);

        //$this->empresas_model->modificar(1, $data);

        $tipo_cliente = explode("xx-xx-xx", $this->input->post('tipo_cliente'));       
        $data = array(
            'ruc' => trim($this->input->post('ruc')),
            'razon_social' => trim($this->input->post('razon_social')),
            'domicilio1' => trim($this->input->post('domicilio1')),
            'email' => trim($this->input->post('email')),
            'email2' => trim($this->input->post('email2')),
            'email3' => trim($this->input->post('email3')),
            'pagina_web' => $this->input->post('pagina_web'),
            'foto' => $_FILES['foto']['name'],
            'telefono_fijo_1' => $this->input->post('telefono_fijo_1'),
            'telefono_movil_1' => $this->input->post('telefono_movil_1'),
            'descuento' => $this->input->post('descuento'),
            'linea_de_credito' =>$this->input->post('linea_de_credito'),
            'zona' => $this->input->post('zona'),
            'puntos'=> $this->input->post('puntos'),
            'bonus'=> $this->input->post('bonus'),
            'foto'=> $_FILES['foto']['name'],
            'empresa_id' => 1,
            'activo' => 'activo',
            'empleado_id_insert' => $this->session->userdata('empleado_id'),
            'tipo_cliente_id' => $tipo_cliente[0],
            'tipo_cliente' => $tipo_cliente[1]
        );
        if ($this->input->post('nombres') != ''){
            $data = array_merge($data,array('nombres' => $this->input->post('nombres')));
        }
        if($tipo_cliente[0] == "2"){ //solo en caso de ser cliente juridico se actualiza el campo: razon_social_sunat
            $data = array_merge($data,array('razon_social_sunat' => $this->input->post('razon_social')));
        }
        
        //BUSCAMOS CLIENTE
        $rsCliente =  $this->clientes_model->clientePorRuc(trim($this->input->post('ruc')));

        //var_dump($rsCliente);exit;
        if(empty($rsCliente)){
            $mensaje = 'Cliente: ' . $this->input->post('razon_social') . ' ingresado exitosamente';
            $this->clientes_model->insertar($data, $mensaje);
        }else {
            $mensaje = 'Cliente: ' . $this->input->post('razon_social') . ' ya se encuentra registrado';
            $this->session->set_flashdata('mensaje_cliente_index', $mensaje);
        }

        redirect(base_url()."index.php/clientes/index");
    }

    public function grabar_para_comprobante() {
     

        $tipo_cliente = explode("xx-xx-xx", $this->input->post('tipo_cliente'));
        $id = $this->clientes_model->obtener_codigo();

        if($this->input->post('ruc')==''){
            $cliente['success'] = 1;
            echo json_encode($cliente);
            exit();
        }

        if($this->input->post('razon_social')==''){
            $cliente['success'] = 2;
            echo json_encode($cliente);
            exit();
        }

        if($this->input->post('domicilio1')==''){
            $cliente['success'] = 3;
            echo json_encode($cliente);
            exit();
        }
     
        
        $data = array(
             'id' => $id,
            'ruc' => trim($this->input->post('ruc')),
            'razon_social' => strtoupper(trim($this->input->post('razon_social'))),
            'domicilio1' => strtoupper(trim($this->input->post('domicilio1'))),
            'email' => trim($this->input->post('email')),
            //'pagina_web' => $this->input->post('pagina_web'),
            //'telefono_fijo_1' => $this->input->post('telefono_fijo_1'),
            'telefono_movil_1' => $this->input->post('telefono_movil_1'),
            'empresa_id' => 1,
            'activo' => 'activo',
            'empleado_id_insert' => $this->session->userdata('empleado_id'),
            'tipo_cliente_id' => $tipo_cliente[0],
            'tipo_cliente' => $tipo_cliente[1]
     
        );
        if ($this->input->post('nombres') != ''){
            $data = array_merge($data,array('nombres' => $this->input->post('nombres')));
            
        }
        if($tipo_cliente[0] == "2"){ //solo en caso de ser cliente juridico se actualiza el campo: razon_social_sunat
            $data = array_merge($data,array('razon_social_sunat' => $this->input->post('razon_social')));
        }
        $mensaje = 'Cliente: ' . $this->input->post('razon_social') . ' ingresado exitosamente';

        $this->clientes_model->insertar($data, $mensaje);

        if($tipo_cliente[0]==1){
            $tpc = "DNI";
        }else if($tipo_cliente[0]==2){
            $tpc = "RUC";
        }else{
            $tpc = "SIN DOC.";
        }
        
        $cliente['nombre'] = $tpc.' '.$this->input->post('ruc').' '.strtoupper($this->input->post('razon_social'));
        $cliente['direccion'] = strtoupper($this->input->post('domicilio1'));
        $cliente['id'] = $id;
        $cliente['success'] = 4;
        echo json_encode($cliente);
    }

    public function perfil(){
        $data['cliente'] = $this->clientes_model->select($this->uri->segment(3));        
                
        if($data['cliente']['empresa_id']>0){
            $data['empresa'] = $this->empresas_model->select($data['cliente']['empresa_id']);
        }                
        
        $this->load->view('templates/header_sin_menu_white');
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

        $carpeta = "images/";
        opendir($carpeta);
        $destino = $carpeta.$_FILES['foto']['name'];
        copy($_FILES['foto']['tmp_name'], $destino);

        $tipo_cliente = explode("xx-xx-xx", $this->input->post('tipo_cliente'));
        $data = array(
            'ruc' => $this->input->post('ruc'),
            'razon_social' => $this->input->post('razon_social'),
            'razon_social_sunat' => $this->input->post('razon_social_sunat'),            
            'domicilio1' => $this->input->post('domicilio1'),
            'domicilio2' => $this->input->post('domicilio2'),
            'email' => $this->input->post('email'),
            'email2' => $this->input->post('email2'),
            'email3' => $this->input->post('email3'),
            'pagina_web' => $this->input->post('pagina_web'),
            'telefono_fijo_1' => $this->input->post('telefono_fijo_1'),
            'telefono_fijo_2' => $this->input->post('telefono_fijo_2'),
            'telefono_movil_1' => $this->input->post('telefono_movil_1'),
            'descuento' => $this->input->post('descuento'),
            'linea_de_credito' =>$this->input->post('linea_de_credito'),
            'zona' => $this->input->post('zona'),
            'puntos'=> $this->input->post('puntos'),
            'bonus'=> $this->input->post('bonus'),
            'foto'=> $_FILES['foto']['name'],
            'telefono_movil_2' => $this->input->post('telefono_movil_2'),
            'empresa_id' => $this->input->post('empresa'),
            'activo' => $this->input->post('activo'),
            'fecha_update' => date("Y-m-d H:i:s"),
            'empleado_id_update' => $this->session->userdata('empleado_id'),
            'tipo_cliente_id' => $tipo_cliente[0],
            'tipo_cliente' => $tipo_cliente[1]
        );
        
        if($tipo_cliente[0] == "2"){ //solo en caso de ser cliente juridico se actualiza el campo: razon_social_sunat
            $data = array_merge($data, array('razon_social_sunat' => $this->input->post('razon_social_sunat')));
        }
        
        
        $mensaje = 'Cliente: ' . $this->input->post('razon_social') . ', modificado exitosamente';
        $this->clientes_model->modificar($this->input->post('id'), $data, $mensaje);
        redirect(base_url()."index.php/clientes/index");
    }

    public function eliminar(){      
        $id = $this->uri->segment(3);
        $data = array('eliminado_cliente' => 1);
        $this->clientes_model->modificar($id, $data);    
        redirect(base_url()."index.php/clientes/index");
    }
    
    //SeachCliente    
    public function searchCustomer(){        
        $ruc =  $_POST['ruc'];
        $typeCustomer = $_POST['tipoCliente'];        
        //BUSCAMOS EN EL CLIENTE EN LA BASE DE DATOS        
        $cliente = $this->clientes_model->clientePorRuc($ruc);                
        if($cliente['id'] == 0){        
        //OBTENEMOS EL VALOR            
        switch ($typeCustomer) {
        case 1:
        $consultaApi = file_get_contents('http://mundosoftperu.com/reniec/consulta_reniec.php?dni='.$ruc);
        $consulta = json_decode($consultaApi);        
            if($consultaApi != ''){
                //$partes = explode('|', $consultaApi);  
                sendJsonData(['status'=>STATUS_OK,'typeCustomer' => $typeCustomer,'paterno' => $consulta[2],'materno' => $consulta[3],'nombres' => $consulta[1]]);
            }
            else{
                sendJsonData(['status'=>STATUS_FAIL, 'msg'=> 'DNI NO ENCONTRADO']);
                exit();
            }        
            break;
        //$consultaSunat = fille_get_contents('https://api.sunat.cloud/ruc/'.$ruc);               
        case 2:
        $consultaApi = file_get_contents('https://mundosoftperu.com/sunat/sunat/consulta.php?nruc='.$ruc);        
        $consulta = json_decode($consultaApi);        
            if($consulta->success != FALSE){            
            $razonSocial = $consulta->result->RazonSocial;
            $direccionFiscal = $consulta->result->Direccion;                                 
            sendJsonData(['status'=>STATUS_OK,'typeCustomer'=> $typeCustomer,'razonSocial' => $razonSocial, 'direccionFiscal' => $direccionFiscal]);
            } else{
              sendJsonData(['status'=>STATUS_FAIL, 'msg'=> 'RUC NO ENCONTRADO']);
              exit();  
            }        
            break;
        }}
        else{
            sendJsonData(['status'=>STATUS_FAIL, 'msg'=> 'Cliente ya se encuentra registrado']);
            exit();}
        }


    public function buscador_cliente() {
        $cliente = $this->input->get('term');
        echo json_encode($this->clientes_model->selectAutocomplete($cliente, ST_ACTIVO));
    }    

    public function modal_nuevoCliente(){
        $data['tipo_clientes'] = $this->tipo_clientes_model->select();
        echo $this->load->view('clientes/modal_nuevoCliente',$data);
    }    

    //DNI AUTOMATICO 19/09/2020
    public function dni_auto(){
        $rsCliente = $this->db->from('clientes')
                              ->where('SUBSTRING(ruc,1,1)',9)   
                              ->where('tipo_cliente_id',1)                           
                              ->order_by('id','DESC')
                              ->limit(1)
                              ->get()
                              ->row();                              
                
            if(count($rsCliente) > 0){
                $dni_auto = $rsCliente->ruc+1;
            }else{
                $dni_auto = 90000000;
            }
                echo json_encode(['status'=>STATUS_OK,'dni_auto'=> $dni_auto]);    
                exit();     
    }
}