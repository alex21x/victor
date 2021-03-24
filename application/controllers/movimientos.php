<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;    
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;



if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Movimientos extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();        
        date_default_timezone_set('America/Lima');
        $this->load->model('accesos_model'); 
        $this->load->model('movimientos_model');       
        $this->load->model('productos_model');       
        $this->load->model('almacenes_model');       
        $this->load->helper('ayuda');        
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
        $this->load->view('movimientos/basic_index');
        $this->load->view('templates/footer');    	
    }  
    public function crear()
    {

        $data['productos'] = $this->productos_model->select();
     	$data['almacenes'] = $this->almacenes_model->select();
    	echo $this->load->view('movimientos/modal_crear', $data);   	
    }
    public function editar($idMovimiento)
    {
       $data['movimiento'] = $this->movimientos_model->select($idMovimiento);
       $data['productos'] = $this->productos_model->select();
       $data['almacenes'] = $this->almacenes_model->select();

        echo $this->load->view('movimientos/modal_crear', $data); 
    }
    public function getMainList()//trae la lisrta de los ingresos
    {
    	$rsDatos = $this->movimientos_model->getMainList();
    	sendJsonData($rsDatos);
    } 
    public function getMainListDetail()//trae los detalles(productos) de un ingreso
    {
    	$rsDatos = $this->movimientos_model->getMainListDetail();
    	sendJsonData($rsDatos);    	
    } 
    public function guardarMovimiento()
    {
        $error = array();

        if($_POST['fecha_movimiento'] == '')
        {
            $error['fecha_movimiento'] = '';
        } 
        if($_POST['origen'] == '')
        {
            $error['origen'] = '';
        } 
        if($_POST['destino'] == '')
        {
            $error['destino'] = '';
        }  
        if(count($error) > 0)
        {
            sendJsonData(['status'=>STATUS_FAIL, 'tipo'=>1, 'errores'=>$error]);
            exit();
        } 
        
        $idMovimiento = $this->movimientos_model->guardarMovimiento();

        sendJsonData(['status'=>STATUS_OK, 'idMovimiento'=>$idMovimiento]);
        exit();                                     
    }
    public function guardarProductoMovimiento()
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

    	$rs = $this->movimientos_model->guardarProductoMovimiento();

        if($rs)
        {
            sendJsonData(['status'=>STATUS_OK]);
        }else
        {
            sendJsonData(['status'=>STATUS_FAIL, 'tipo'=>2]);
        }
    	exit();
    } 
 
    public function eliminarDetalleMovimiento($idDetalle)
    {
        $rs = $this->movimientos_model->eliminarDetalleMovimiento($idDetalle);
        if($rs)
        {
            sendJsonData(['status'=>STATUS_OK]);

        }else
        {
            sendJsonData(['status'=>STATUS_FAIL, 'msg'=>'algunos productos de este ingreso ya han sido vendidos']);
        }
    } 
    public function eliminarMovimiento($idMovimiento)
    {
        $rs = $this->movimientos_model->eliminarMovimiento($idMovimiento);
        if($rs)
        {
            sendJsonData(['status'=>STATUS_OK]);

        }else
        {
            sendJsonData(['status'=>STATUS_FAIL]);
        }        
    }   

    public function buscador_item() {
        $item = $this->input->get('term');       
        echo json_encode($this->productos_model->selectAutocompleteprod($item));
    }
    public function getAlmacen($id) {
        
        $res = $this->db->from('almacenes')
                           ->where('alm_id',$id)
                           ->get()
                           ->row();
        
        if ($res) {
            return $res->alm_nombre;
        }
        return " ";
    }

    public function ExportarExcel($fecha='') {

        if($this->uri->segment(3)!='0') {
            $this->db->where('mov.mov_fecha', $this->uri->segment(3));
        }       
        $this->db->where('mov.mov_estado',ST_ACTIVO);

        $result = $this->db->from("movimientos mov")
                 ->join("almacenes al","al.alm_id=mov.mov_origen_id")
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
                ->setCellValue('A1', 'CODIGO')
                ->setCellValue('B1', 'FECHA')
                ->setCellValue('C1', 'ORIGEN')
                ->setCellValue('D1', 'DESTINO')
                ->setCellValue('E1', 'OBSERVACIONES');                

        $spreadsheet->getActiveSheet()->setTitle('Movimientos');

        foreach ($result as $value) {  
            

            $fecha = (new DateTime($value->mov_fecha))->format('d/m/Y');
            $origen = $this->getAlmacen($value->mov_origen_id);
            $destino = $this->getAlmacen($value->mov_destino_id);

            $spreadsheet->getActiveSheet()
                        ->setCellValue('A'.$i, $value->mov_codigo)
                        ->setCellValue('B'.$i, $fecha)
                        ->setCellValue('C'.$i, $origen)
                        ->setCellValue('D'.$i, $destino)
                        ->setCellValue('E'.$i, $value->mov_observacion);
            $i++; 
            $spreadsheet->getActiveSheet()
                        ->setCellValue('A'.$i, '')
                        ->setCellValue('B'.$i, 'CODIGO')
                        ->setCellValue('C'.$i, 'PRODUCTO')
                        ->setCellValue('D'.$i, 'CANTIDAD');            
            
            $dataMovimiento = $this->db->from('movimientos_detalle mov')
                                       ->join('productos p','mov.movd_producto_id = p.prod_id')
                                       ->where('mov.movd_movimiento_id',$value->mov_id)
                                       ->get()
                                       ->result();

            $i++;
            foreach ($dataMovimiento as $val) {              
                $spreadsheet->getActiveSheet()
                        ->setCellValue('A'.$i, '')
                        ->setCellValue('B'.$i, $val->prod_codigo)
                        ->setCellValue('C'.$i, $val->prod_nombre)
                        ->setCellValue('D'.$i, $val->movd_cantidad);                  
                $i++;
            }

            $spreadsheet->getActiveSheet()->mergeCells('A'.$i.':E'.$i);                        

            $i++;
        }
         
        $spreadsheet->setActiveSheetIndex(0);         
        // Redirect output to a client's web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="data_Movimientos.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');       
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;

    }
 

}