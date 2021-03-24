<?PHP

use PhpOffice\PhpSpreadsheet\Spreadsheet;    
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Movimiento_caja_controlador extends CI_Controller {

    public function __construct() {
        parent::__construct();
          $this->load->model("movimiento_caja_model");
          $this->load->model('tipo_cmovimiento_model');
          $this->load->model('empleados_model');
          $this->load->model('cajas_model');

          $empleado_id = $this->session->userdata('empleado_id');
          $almacen_id = $this->session->userdata("almacen_id");
          if (empty($empleado_id) or empty($almacen_id)) {
            $this->session->set_flashdata('mensaje', 'No existe sesion activa');
            redirect(base_url());
          }                    
        }

       public function index(){

        $data['vendedores'] = $this->empleados_model->select2(3);
        $viewContent = 'caja_movimientos/index';
        //Status Caja
        $rs = $this->cajas_model->ultimoRegCaja();        
        if(($rs->tipo_transaccion_id == 2) && ($this->session->userdata('tipo_empleado_id') == ST_CAJA)){        
            echo '<script>alert("DEBE APERTURAR CAJA")</script>';                    
            $viewContent = 'cajas/index';
        }
                
        $this->load->view('templates/header_administrador');
        $this->load->view($viewContent,$data);        
        $this->load->view('templates/footer');
       }



      public function selectRowsCajamoviento(){
        $rsCajMov = $this->movimiento_caja_model->select();              

        $rsCaj   = "<table class= 'table table-streap'><tr>";
        $rsCaj  .= "<th>ID</th>";
        $rsCaj  .= "<th>TIPO MOVIMIENTO ID</th>";
        $rsCaj  .= "<th>MONTO</th>";
        $rsCaj  .= "<th>OBSERVACIONES</th>";
        $rsCaj  .= "<th>USUARIO</th>";
        $rsCaj  .= "<th>fecha</th>";
        $rsCaj  .= "<th>&nbsp;</th>";
        $rsCaj  .= "<th>&nbsp;</th></tr>";


        foreach ($rsCajMov as $value) {

            $rsCaj  .= '<tr><td>'.$value->id.'</td>';
            $rsCaj  .= '<td>'.$value->tipo_cMovimiento.'</td>';          
            $rsCaj  .= '<td>'.$value->monto.'</td>';          
            $rsCaj  .= '<td>'.$value->observaciones.'</td>';
            $rsCaj  .= '<td>'.$value->empleado.'</td>';
            $rsCaj  .= '<td>'.$value->fecha.'</td>';
            $rsCaj  .= '<td '.$this->session->userdata('accesoEmpleado').'><a class="btn btn-primary btn-sm btn_modificar" data-id= '.$value->id.' data-toggle="modal" data-target="#modalCajaMov">MODIFICAR</a></td>'; 

            $rsCaj  .= '<td '.$this->session->userdata('accesoEmpleado').'><a class="btn btn-danger btn-sm btn_eliminar" id="eliminar" data-id='.$value->id.'>ELIMINAR</a></td></tr>';
        }
        
        $rsCaj .="</table>";

        echo $rsCaj;
      }


      public function nuevo(){
           

          $data['tipo_cMovimiento'] = $this->tipo_cmovimiento_model->select();
          //var_dump($data['tipo_cMovimiento']);exit;
          $this->load->view('caja_movimientos/modal_crear',$data);
          
      }


      public function modificar(){

        $movCajMov_id = $this->uri->segment(3);
        $data['caja_movimientos'] = $this->movimiento_caja_model->select($movCajMov_id);
        $data['tipo_cMovimiento'] = $this->tipo_cmovimiento_model->select();
        $this->load->view('caja_movimientos/modal_crear',$data);
      }


      public function guardar(){

        $rs = $this->movimiento_caja_model->guardar();
        if($rs){
          echo json_encode(['status' => STATUS_OK]);
        }else {
          echo json_encode(['status' => STATUS_FAIL]);
        }
        if ($rs) {
          $mensaje="GUARDADO";
          $clase="success";
          # code...
        }else{
           
            $mensaje="ERROR";
            $clase="danger";


        }

        $this->session->set_flashdata(array(
                       "mensaje"=>$mensaje,
                       "clase"=>$clase,       
                     ));

      }

    public function eliminar(){
        $caja_movimientos_id=$this->uri->segment(3);
       
        $this->movimiento_caja_model->eliminar($caja_movimientos_id);
        $rs=$this->movimiento_caja_model->eliminar($caja_movimientos_id);
          if($rs){
            echo json_encode(['status' => STATUS_OK]);
          }else {
            echo json_encode(['status' => STATUS_FAIL]);
          }
      }


    public function exportarMovCaj(){
       
      $rsCajMov = $this->movimiento_caja_model->select();      

      $fecha_desde = $_GET['fecha_desde'];    
      $fecha_hasta = $_GET['fecha_hasta'];
      $vendedorText = $_GET['vendedorText'];

      $spreadsheet = new Spreadsheet();         
        // Set workbook properties
        $spreadsheet->getProperties()->setCreator('Rob Gravelle')
                ->setLastModifiedBy('Rob Gravelle')
                ->setTitle('A Simple Excel Spreadsheet')
                ->setSubject('PhpSpreadsheet')
                ->setDescription('A Simple Excel Spreadsheet generated using PhpSpreadsheet.')
                ->setKeywords('Microsoft office 2013 php PhpSpreadsheet')
                ->setCategory('Test file');         
        
        $i = 7;

        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(40);
         
        $spreadsheet->getActiveSheet()->getStyle("A1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("B1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("C1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("D1")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("E1")->getFont()->setBold(true);
       


        $spreadsheet->getActiveSheet()->getStyle('B')
        ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

    

        $spreadsheet->setActiveSheetIndex(0)                
                ->setCellValue('B1', 'MOVIMIENTOS DE CAJA');

        $spreadsheet->getActiveSheet()
                ->setCellValue('A2', 'FECHA DESDE')
                ->setCellValue('B2', $fecha_desde)
                ->setCellValue('A3', 'FECHA HASTA')
                ->setCellValue('B3', $fecha_hasta)
                ->setCellValue('A4', 'VENDEDOR')
                ->setCellValue('B4', $vendedorText); 

        $spreadsheet->getActiveSheet()
                ->setCellValue('A6', 'ID')
                ->setCellValue('B6', 'TIPO MOVIMIENTO')
                ->setCellValue('C6', 'MONTO')
                ->setCellValue('D6', 'OBSERVACIONES')
                ->setCellValue('E6', 'FECHA');                                                        
               // ->setCellValue('E1', 'EMPRESA');

    $rsTotal_ingreso = 0;       
    $rsTotal_salida  = 0;        

    $array = array();
    foreach($rsCajMov  as $value) {   
     $spreadsheet->getActiveSheet()
                        ->setCellValue('A'.$i, $value->id)
                        ->setCellValue('B'.$i, $value->tipo_cMovimiento)
                        ->setCellValue('C'.$i, $value->monto)
                        ->setCellValue('D'.$i, $value->observaciones)
                        ->setCellValue('E'.$i, $value->fecha);                        

         if(!isset($array[$value->tipo_cMovimiento]))                                                
              $results[$value->tipo_cMovimiento] = 0;

        $array[$value->tipo_cMovimiento] += $value->monto;        
      $i++;
     }

     $spreadsheet->getActiveSheet()           
                        ->setCellValue('B'.$i, 'TOTAL INGRESO')
                        ->setCellValue('C'.$i, $array['Ingreso'])
                        ->setCellValue('D'.$i, 'TOTAL SALIDA')
                        ->setCellValue('E'.$i, $array['Salida']);                    

      
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
}
