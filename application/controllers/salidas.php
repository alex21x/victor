<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;    
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;


if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Salidas extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();        
        date_default_timezone_set('America/Lima');
        $this->load->model('accesos_model'); 
        $this->load->model('salidas_model');       
        $this->load->model('productos_model');       
        $this->load->model('almacenes_model');            
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
        $this->load->view('salidas/basic_index');
        $this->load->view('templates/footer');      
    }  
    public function crear()
    {

        $data['productos'] = $this->productos_model->select();
        $data['almacenes'] = $this->almacenes_model->select();
        echo $this->load->view('salidas/modal_crear', $data);      
    }
    public function editar($idSalida)
    {
        $data['salida'] = $this->salidas_model->select($idSalida);
        $data['productos'] = $this->productos_model->select();
        $data['almacenes'] = $this->almacenes_model->select();
        echo $this->load->view('salidas/modal_crear', $data); 
    }
    public function getMainList()//trae la lisrta de las salidas
    {
        $rsDatos = $this->salidas_model->getMainList();
        sendJsonData($rsDatos);
    } 
    public function getMainListDetail()//trae los detalles(productos) de un ingreso
    {
        $rsDatos = $this->salidas_model->getMainListDetail();
        sendJsonData($rsDatos);     
    } 
    
    public function guardarSalida()
    {
        $error = array();

        if($_POST['fecha_salida'] == '')
        {
            $error['fecha_salida'] = '';
        } 
        if($_POST['almacen'] == '')
        {
            $error['almacen'] = '';
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
        
        $idSalida = $this->salidas_model->guardarSalida();

        sendJsonData(['status'=>STATUS_OK, 'idSalida'=>$idSalida]);
        exit();                                     
    }
    public function guardarProductoSalida()
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

        $rs = $this->salidas_model->guardarProductoSalida();

        if($rs)
        {
            sendJsonData(['status'=>STATUS_OK]);
        }
        exit();
    } 
 
    public function eliminarDetalleSalida($idDetalle)
    {
        $rs = $this->salidas_model->eliminarDetalleSalida($idDetalle);
        if($rs)
        {
            sendJsonData(['status'=>STATUS_OK]);

        }else
        {
            sendJsonData(['status'=>STATUS_FAIL, 'msg'=>'algunos productos de este ingreso ya han sido vendidos']);
        }
    } 
    public function eliminarSalida($idSalida)
    {
        $rs = $this->salidas_model->eliminarSalida($idSalida);
        if($rs)
        {
            sendJsonData(['status'=>STATUS_OK]);

        }else
        {
            sendJsonData(['status'=>STATUS_FAIL]);
        }        
    } 

    public function buscador_item()
    {
        $item = $this->input->get('term');       
        echo json_encode($this->productos_model->selectAutocompleteprod($item));
    }

    public function ExportarExcel($fecha='') {

        if($this->uri->segment(3)!='0') {
            $this->db->where('s.sal_fecha', $this->uri->segment(3));
        }       
        $this->db->where('s.sal_estado',ST_ACTIVO);

        $result = $this->db->from("salidas s")
                 ->join("almacenes al","al.alm_id=s.sal_almacen_id")
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
                ->setCellValue('C1', 'ALMACEN')
                ->setCellValue('D1', 'OBSERVACIONES');                

        $spreadsheet->getActiveSheet()->setTitle('salidas');

        foreach ($result as $value) {
            $fecha = (new DateTime($value->sal_fecha))->format('d/m/Y');
            $spreadsheet->getActiveSheet()
                        ->setCellValue('A'.$i, $value->sal_codigo)
                        ->setCellValue('B'.$i, $fecha)
                        ->setCellValue('C'.$i, $value->alm_nombre)
                        ->setCellValue('D'.$i, $value->sal_observacion);
            $i++; 
            $spreadsheet->getActiveSheet()
                        ->setCellValue('A'.$i, '')
                        ->setCellValue('B'.$i, 'CODIGO')
                        ->setCellValue('C'.$i, 'PRODUCTO')
                        ->setCellValue('D'.$i, 'CANTIDAD'); 

            $dataSalida = $this->db->from('salidas_detalle sd')
                                   ->join('productos p','sd.sald_producto_id = p.prod_id')
                                   ->where('sd.sald_salida_id',$value->sal_id)
                                   ->get()
                                   ->result();
            $i++;
            foreach ($dataSalida as $val) {              
                $spreadsheet->getActiveSheet()
                        ->setCellValue('A'.$i, '')
                        ->setCellValue('B'.$i, $val->prod_codigo)
                        ->setCellValue('C'.$i, $val->prod_nombre)
                        ->setCellValue('D'.$i, $val->sald_cantidad);                  
                $i++;
            }
            $spreadsheet->getActiveSheet()->mergeCells('A'.$i.':D'.$i);            

            $i++;
        }
        
        $spreadsheet->setActiveSheetIndex(0);         
        // Redirect output to a client's web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="data_salidas.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');       
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;

    }



}