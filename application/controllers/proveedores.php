<?PHP
use PhpOffice\PhpSpreadsheet\Spreadsheet;    
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Proveedores extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();        
        date_default_timezone_set('America/Lima');
        $this->load->model('accesos_model'); 
        $this->load->model('proveedores_model');       
        $this->load->helper('ayuda');        
//
       $empleado_id = $this->session->userdata('empleado_id');
        $almacen_id = $this->session->userdata("almacen_id");
        if (empty($empleado_id) or empty($almacen_id)) {
            $this->session->set_flashdata('mensaje', 'No existe sesion activa');
            redirect(base_url());
        }
    }

    public function index()
    {
        $this->accesos_model->menuGeneral();
        $this->load->view('proveedores/basic_index');
        $this->load->view('templates/footer');    	
    }
    public function crear()
    {
    	$data = array();
    	echo $this->load->view('proveedores/modal_crear', $data);
    }
    public function editar($idProveedor)
    {
    	$data['proveedor'] = $this->proveedores_model->select($idProveedor);
    	$this->load->view('proveedores/modal_crear', $data);
    }
    public function guardarProveedor()
    {
    	$error = array();
    	if($_POST['ruc'] == '')
    	{
    		$error['ruc'] = 'falta ingresar ruc';
    	}
    	if($_POST['razon_social'] == '')
    	{
    		$error['razon_social'] = 'falta ingresar razon social';
    	}
    	if(count($error) > 0)
    	{
    		$data = ['status'=>STATUS_FAIL,'tipo'=>1, 'errores'=>$error];
    		sendJsonData($data);
    		exit();
    	}    

    	//guardamos el producto
    	$result = $this->proveedores_model->guardar();
    	
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

    public function grabar_para_comprobante() {
     


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

       
       $select = $this->db->from("proveedores")
                           ->select_max("prov_id")
                           ->get()
                           ->row();

        $id =   $select->prov_id + 1;                 
     
        
        $dataInsert = [
                            'prov_id'          => $id,
                            'prov_ruc'          => $this->input->post('ruc'),
                            'prov_razon_social' => strtoupper($this->input->post('razon_social')),
                            'prov_celular'      => $this->input->post('telefono'),
                            'prov_direccion'    => strtoupper($this->input->post('direccion')),
                            'prov_estado'       => ST_ACTIVO
                          ];
            $this->db->insert('proveedores', $dataInsert);  
        
        $cliente['nombre'] = 'RUC '.$this->input->post('ruc').' '.$this->input->post('razon_social');
        $cliente['id'] = $id;
        $cliente['success'] = 4;
        echo json_encode($cliente);
    }

    public function eliminar($idProveedor)
    {
    	$result = $this->proveedores_model->eliminar($idProveedor);
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
    public function getMainList()
    {
    	$rsDatos = $this->proveedores_model->getMainList();
    	sendJsonData($rsDatos);
    }
    public function ExportarExcel($nombre='') {
       if($this->uri->segment(3)!='0') {
            $this->db->like('prov_ruc', $this->uri->segment(3))
                     ->or_like('prov_razon_social',$this->uri->segment(3));
        }
        $result = $this->db->from("proveedores")                            
                            ->where("prov_estado", ST_ACTIVO)
                            ->get()
                            ->result();
        
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

        $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A1', 'RUC')
                ->setCellValue('B1', 'RAZÓN_SOCIAL')
                ->setCellValue('C1', 'DIRECCIÓN')
                ->setCellValue('D1', 'TELEFONO');
                
        $spreadsheet->getActiveSheet()->setTitle('proveedores');
        foreach ($result as $value) {         
            $spreadsheet->getActiveSheet()
                        ->setCellValue('A'.$i, $value->prov_ruc)
                        ->setCellValue('B'.$i, $value->prov_razon_social)
                        ->setCellValue('C'.$i, $value->prov_direccion)
                        ->setCellValue('D'.$i, $value->prov_celular);
            $i++;
        }

        $spreadsheet->setActiveSheetIndex(0);         
        // Redirect output to a client's web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="data_proveedor.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');       
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
   }
      
   //BUSCADOR PROVEEDOR 06-12-2020 //ALEXANDER FERNANDEZ
   public function buscador_proveedor() {
        $proveedor = $this->input->get('term');
        echo json_encode($this->proveedores_model->selectAutocomplete($proveedor, 'activo'));
    }
}