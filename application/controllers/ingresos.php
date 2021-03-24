<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;    
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;


if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Ingresos extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();        
        date_default_timezone_set('America/Lima');
        $this->load->model('accesos_model'); 
        $this->load->model('ingresos_model');       
        $this->load->model('productos_model');       
        $this->load->model('almacenes_model');       
        $this->load->model('proveedores_model');       
        $this->load->helper('ayuda');        
        $empleado_id = $this->session->userdata('empleado_id');
        if (empty($empleado_id)) {
            $this->session->set_flashdata('mensaje', 'No existe sesion activa');
            redirect(base_url());
        }
    }	
    public function index()
    {
        $this->accesos_model->menuGeneral();
        $this->load->view('ingresos/basic_index');
        $this->load->view('templates/footer');    	
    }  
    public function crear()
    {

        $data['productos'] = $this->productos_model->select();
        $data['almacenes'] = $this->almacenes_model->select();
     	$data['proveedores'] = $this->proveedores_model->select();
    	echo $this->load->view('ingresos/modal_crear', $data);   	
    }
    public function editar($idIngreso)
    {
        $data['ingreso'] = $this->ingresos_model->select($idIngreso);
        $data['productos'] = $this->productos_model->select();
        $data['almacenes'] = $this->almacenes_model->select();
        $data['proveedores'] = $this->proveedores_model->select();
        echo $this->load->view('ingresos/modal_crear', $data); 
    }
    public function getMainList()//trae la lisrta de los ingresos
    {
    	$rsDatos = $this->ingresos_model->getMainList();
    	sendJsonData($rsDatos);
    }
    public function getMainListDetail()//trae los detalles(productos) de un ingreso
    {
    	$rsDatos = $this->ingresos_model->getMainListDetail();
    	sendJsonData($rsDatos);    	
    }

    public function guardarIngreso()
    {
        $error = array();

        if($_POST['fecha_ingreso'] == '')
        {
            $error['fecha_ingreso'] = '';
        } 
        if($_POST['proveedor'] == '')
        {
            $error['proveedor'] = '';
        } 
        if($_POST['almacen'] == '')
        {
            $error['almacen'] = '';
        }  
        if(count($error) > 0)
        {
            sendJsonData(['status'=>STATUS_FAIL, 'tipo'=>1, 'errores'=>$error]);
            exit();
        } 
        
        $idIngreso = $this->ingresos_model->guardarIngreso();

        sendJsonData(['status'=>STATUS_OK, 'idIngreso'=>$idIngreso]);
        exit();                                     
    }
    public function guardarProductoIngreso()
    {
    	$error = array();
   	
   	
    	if($_POST['producto'] == '')
    	{
    		$error['producto'] = '';
    	}    	
    	if($_POST['cantidad'] == '')
    	{
    		$error['cantidad'] = '';
    	}


    	if(count($error) > 0)
    	{
    		sendJsonData(['status'=>STATUS_FAIL, 'tipo'=>1, 'errores'=>$error]);
    		exit();
    	}

    	$rs = $this->ingresos_model->guardarProductoIngreso();

        if($rs)
        {
            sendJsonData(['status'=>STATUS_OK]);
        }
    	exit();
    } 
 
    public function eliminarDetalleIngreso($idDetalle)
    {
        $rs = $this->ingresos_model->eliminarDetalleIngreso($idDetalle);
        if($rs)
        {
            sendJsonData(['status'=>STATUS_OK]);

        }else
        {
            sendJsonData(['status'=>STATUS_FAIL, 'msg'=>'algunos productos de este ingreso ya han sido vendidos']);
        }
    } 
    public function eliminarIngreso($idIngreso)
    {
        $rs = $this->ingresos_model->eliminarIngreso($idIngreso);
        if($rs)
        {
            sendJsonData(['status'=>STATUS_OK]);

        }else
        {
            sendJsonData(['status'=>STATUS_FAIL]);
        }        
    }

    public function ExportarExcel($fecha='',$proveedor='') {

        if($this->uri->segment(3)!='0') {
            $this->db->where('i.ing_fecha', $this->uri->segment(3));
        }
        if($this->uri->segment(4)!='0') {
            $this->db->where('pr.prov_razon_social', $this->uri->segment(4));
        }
        
        $this->db->from("ingresos i")
                 ->join("proveedores pr","pr.prov_id=i.ing_proveedor_id")
                 ->join("almacenes al","al.alm_id=i.ing_almacen_id");

        $query = $this->db->get();
        $result = $query->result();

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
                ->setCellValue('A1', 'N°')
                ->setCellValue('B1', 'FECHA')
                ->setCellValue('C1', 'ALMACEN')
                ->setCellValue('D1', 'PROVEEDOR')
                ->setCellValue('E1', 'OBSERVACIONES');

        $spreadsheet->getActiveSheet()->setTitle('Ingresos');
        foreach ($result as $value) {
            $empresa = $this->db->from('empresas')
                            ->where('id',$value->empresa_id)
                            ->get()
                            ->row();
            $fecha = (new DateTime($value->ing_fecha))->format('d/m/Y');

            $spreadsheet->getActiveSheet()
                        ->setCellValue('A'.$i, $value->ing_codigo)
                        ->setCellValue('B'.$i, $fecha)
                        ->setCellValue('C'.$i, $value->alm_nombre)
                        ->setCellValue('D'.$i, $value->prov_razon_social)
                        ->setCellValue('E'.$i, $value->ing_observaciones);
            $i++;

            $spreadsheet->getActiveSheet()
                        ->setCellValue('A'.$i, '')
                        ->setCellValue('B'.$i, 'CODIGO')
                        ->setCellValue('C'.$i, 'PRODUCTO')
                        ->setCellValue('D'.$i, 'CANTIDAD'); 

            $dataIngreso = $this->db->from('ingresos_detalle ing')
                                   ->join('productos p','ing.ingd_producto_id = p.prod_id')
                                   ->where('ing.ingd_ingreso_id',$value->ing_id)
                                   ->get()
                                   ->result();
            $i++;
            foreach ($dataIngreso as $val) {              
                $spreadsheet->getActiveSheet()
                        ->setCellValue('A'.$i, '')
                        ->setCellValue('B'.$i, $val->prod_codigo)
                        ->setCellValue('C'.$i, $val->prod_nombre)
                        ->setCellValue('D'.$i, $val->ingd_cantidad);                  
                $i++;
            }

            $spreadsheet->getActiveSheet()->mergeCells('A'.$i.':E'.$i);

            $i++;


        }
        
        $spreadsheet->setActiveSheetIndex(0);         
        // Redirect output to a client's web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="data_Ingreso.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');       
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;

    }

}