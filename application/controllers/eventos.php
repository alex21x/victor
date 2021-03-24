<?php 

use PhpOffice\PhpSpreadsheet\Spreadsheet;    
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Eventos extends CI_Controller {

  public function __construct()
  {
    parent :: __construct();    
    $this->load->model('accesos_model');
    $this->load->model('eventos_model');
    $this->load->model('tipo_eventos_model');
    $this->load->model('evento_imagenes_model');
    $this->load->model('turnos_model');
    $this->load->model('empleados_model');
    $this->load->model('clientes_model');
    $this->load->library('pdf');
    date_default_timezone_set('America/Lima');
  }

  public function index()
  {
       $data['vendedores'] = $this->empleados_model->select2(3);
	     $this->accesos_model->menuGeneral();
       $this->load->view('eventos/basic_index',$data);
       $this->load->view('templates/footer');  
  }
  
  public function crear()
  {
      $data['tipo_eventos'] = $this->tipo_eventos_model->select();
      $data['turnos'] = $this->turnos_model->select();
  	  $this->load->view('eventos/modal_crear',$data);
  }

  public function editar(){
        $data['evento'] = $this->eventos_model->select($this->uri->segment(3));        
        $data['tipo_eventos'] = $this->tipo_eventos_model->select();
        $data['turnos'] = $this->turnos_model->select();
        $data['evento_imagenes'] =  $this->evento_imagenes_model->select('',$this->uri->segment(3));
        $this->load->view('eventos/modal_crear',$data);
  }

  public function guardarEvento(){    

    $error = array();      
    if($_POST['tipo_evento'] == '')
        {
           $error['tipo_evento'] = 'falta ingresar tipo_evento';
        }      
    if($_POST['turno'] == '')
        {
           $error['turno'] = 'falta ingresar turno';
        }     
    if($_POST['fecha_evento'] == '')
        {
            $error['fecha_evento'] = 'falta ingresar fecha_evento';
        }  
    if($_POST['hora_ingreso'] == '')
        {
            $error['hora_ingreso'] = 'falta ingresar hora_ingreso';
        }  


        if(count($error) > 0)
        {
            $data = ['status'=>STATUS_FAIL,'tipo'=>1, 'errores'=>$error];
            echo json_encode($data);
            exit();
        }   
            

        //guardamos la evento
        $result = $this->eventos_model->guardarEvento();
        if($result)
        {
            echo json_encode(['status'=>STATUS_OK,'eve_id' => $result]);
            exit();
        }else
        {
            echo json_encode(['status'=>STATUS_FAIL, 'tipo'=>2]);
            exit();
        }
  }

  public function getMainList(){
    $rsEventos =  $this->eventos_model->getMainList();
    echo json_encode($rsEventos);
  } 


  public function eliminarEventoImagen(){
        $result = $this->evento_imagenes_model->eliminar($this->uri->segment(3));
        $evento_imagenes = $this->evento_imagenes_model->select('',$this->uri->segment(4));

        $rsHI =  '<div id="images_gallery">';                      
            foreach($evento_imagenes as $image)
                {              
                    $rsHI .= '<div class="col-xs-2 col-md-2 col-lg-2" align="center" ><a class="example-image-link" href="'.base_url().'images/eventos/'. $image->evento_imagen.'" data-lightbox="example-1"><img class="example-image" src="'.base_url().'images/eventos/'. $image->evento_imagen .'" width="120px" height="100px" style="border:1px solid #ccc;margin-top:10px;" /></a>
                                <span '.$this->session->userdata('accesoEmpleado').' class="glyphicon glyphicon-remove eliminarImagen" data-id="'.$image->id.'"></span></div>';                              
                }                  
        $rsHI .= '</div>';
        echo $rsHI;
    }


     public function descargarPdf_ticket($idEvento)
   {
        $rsEvento = $this->db->select('tie.*,tur.*,eve.*,cli.id cliente_id,eve.placa placa,eve.id evento_id, TIMEDIFF(eve.salida, eve.ingreso) totalHoras,CONCAT(epl.nombre," ",epl.apellido_paterno) empleado',FALSE)
                              ->from("eventos as eve")
                              ->join("tipo_eventos tie", "eve.tipo_evento_id = tie.id")
                              ->join("turnos tur", "eve.turno_id = tur.id")
                              ->join("clientes cli", "eve.cliente_id = cli.id","LEFT")
                              ->join("empleados epl","eve.empleado_insert = epl.id")
                              ->where("eve.id", $idEvento)
                              ->get()
                              ->row();
                           //var_dump($rsEvento);exit;

        /*formateamos fecha*/
        $rsEventos->fecha = (new DateTime($rsEventos->fecha))->format("d/m/Y h:i:s");      
        $ticketHeight = ($ticketHeight > 440) ? $ticketHeight : 440;          

        $rsEmpresa = $this->db->from("empresas")
                              ->where("id", 1)
                              ->get()
                              ->row();

        $rsCliente =  $this->db->from("clientes")
                              ->where("id", $rsEvento->cliente_id)
                              ->get()
                              ->row();                      
                      //var_dump($rsCliente);exit;
 
        $data = [
                    "empresa" => $rsEmpresa,
                    "evento"  => $rsEvento,
                    "cliente" => $rsCliente,
                ];
               // var_dump($rsEmpresa);exit;
        $html = $this->load->view("templates/evento_ticket.php",$data,true); 
                 
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper(array(0,0,85,$ticketHeight), 'portrait');
        $this->pdf->render();
        $this->pdf->stream("Proforma.NP-$idProforma.pdf",
            array("Attachment"=>0)
        );
    }

      public function descargarPdf($idEvento)
      {
         //var_dump($idEvento);exit;
           require_once (APPPATH .'libraries/Numletras.php');
        
        $rsEvento = $this->db->select('tie.*,tur.*,eve.*,cli.id cliente_id,eve.fecha fecha_evento,eve.placa placa,eve.observacion observacion ,eve.id evento_id,TIMEDIFF(eve.salida, eve.ingreso) totalHoras,cli.razon_social cli_razon_social,CONCAT(epl.nombre," ",epl.apellido_paterno) empleado',FALSE)
                              ->from("eventos as eve")
                              ->join("tipo_eventos tie", "eve.tipo_evento_id = tie.id")
                              ->join("turnos tur", "eve.turno_id = tur.id")
                              ->join("clientes cli", "eve.cliente_id = cli.id","LEFT")
                              ->join("empleados epl","eve.empleado_insert = epl.id")
                              ->where("eve.id", $idEvento)
                              ->get()
                              ->row();
       // var_dump($rsEvento);exit;
        /*formateamos fecha*/
        $rsEvento->fecha_evento = (new DateTime($rsEvento->fecha_evento))->format('d/m/Y');                     
       // var_dump($idEvento);exit;
        $this->db->where('estado',ST_ACTIVO);
        $rsDetalles =  $this->db->from("evento_imagenes")
                                ->where("evento_id", $idEvento)
                                ->get()
                                ->result();
            
        $rsEvento->detalles = $rsDetalles;                                     
        $rsempresa = $this->db->from('empresas')
                              ->where('id',1)
                              ->get()
                              ->row();
                //var_dump($rsempresa);exit;
        $rscliente = $this->db->from('clientes')
                              ->where('id',$rsEvento->cliente_id)
                              ->get()
                              ->row();
                           // var_dump($rsCliente);exit;
        
        $rsEmpleado =  $this->db->from("empleados")
                              ->where("id", $rsEvento->prof_empleado_id)
                              ->get()
                              ->row();

      

        $num = new Numletras();
        $totalVenta = explode(".",$rsproforma->prof_doc_total);
        $totalLetras = $num->num2letras($totalVenta[0]);
        $totalLetras = $totalLetras.' con '.$totalVenta[1].'/100 '.$rsmoneda->moneda;
        $rsproforma->total_letras = $totalLetras; 


        $data = [
                    "evento"    => $rsEvento,
                    "empresa"     => $rsempresa,
                    "cliente"     => $rscliente,
                    "empleado"    => $rsEmpleado,
                    "moneda"      => $rsmoneda
                ];
        $html = $this->load->view("templates/evento.php",$data,true);       


        $this->pdf->loadHtml($html);
        $this->pdf->setPaper('A4', 'landscape');
        $this->pdf->render();
        $this->pdf->stream("N.Venta.NP-$idProforma.pdf",
            array("Attachment"=>0)
        ); 
      }


    //ALEXANDER FERNANDEZ 05-03-2021
    public function exportarReporteEvento_pdf(){

        $fecha_desde = $_GET['fecha_desde'];
        $fecha_hasta = $_GET['fecha_hasta'];

        $rsEventos = $this->eventos_model->reporteEventos();
        $rsempresa = $this->db->from('empresas')
                              ->where('id',1)
                              ->get()
                              ->row();
        $data = [
                    "eventos"    => $rsEventos,
                    "empresa"     => $rsempresa,
                    "fecha_desde" => $fecha_desde,
                    "fecha_hasta" => $fecha_hasta
                ];                                      

        $html = $this->load->view('templates/evento_pdf.php',$data,true);

        $this->pdf->loadHtml($html);
        $this->pdf->setPaper('A4', 'landscape');
        $this->pdf->render();
        $this->pdf->stream("ReporteEvento_pdf",array("Attachment"=>0));
    }

    public function exportarReporteEvento() {

      $rsEvento = $this->eventos_model->reporteEventos();
       
        

    $fecha_desde = $_GET['fecha_desde'];    
    $fecha_hasta = $_GET['fecha_hasta'];
    //var_dump($rsEvento);exit;
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
        //var_dump($rsEvento);exit;
        $i=6;

        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(60);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(20);
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
                ->setCellValue('B1', 'REPORTE EVENTOS');

        $spreadsheet->getActiveSheet()
                ->setCellValue('A2', 'FECHA DESDE')
                ->setCellValue('B2', $fecha_desde)
                ->setCellValue('A3', 'FECHA HASTA')
                ->setCellValue('B3', $fecha_hasta);             


        $spreadsheet->getActiveSheet()
                ->setCellValue('A5', 'CODIGO')
                ->setCellValue('B5', 'TIPO_EVENTO')
                ->setCellValue('C5', 'FECHA_EVENTO')
                ->setCellValue('D5', 'CLIENTE')
                ->setCellValue('E5', 'HORA_INGRESO')
                ->setCellValue('F5', 'HORA_SALIDA')
                ->setCellValue('G5', 'TOTAL HORAS')
                ->setCellValue('H5', 'TURNO')
                ->setCellValue('I5', 'RESPONSABLE')
                ->setCellValue('J5', 'PLACA')                
                ->setCellValue('K5', 'N DOCUMENTO')                
                ->setCellValue('L5', 'N GUIA')
                ->setCellValue('M5', 'OTROS')
                ->setCellValue('N5', 'USUARIO');
      
    foreach($rsEvento  as $value) {   
     $spreadsheet->getActiveSheet()
                        ->setCellValue('A'.$i, $value['evento_id'])
                        ->setCellValue('B'.$i, $value['tipo_evento'])
                        ->setCellValue('C'.$i, $value['fecha_evento'])
                        ->setCellValue('D'.$i, $value['cli_razon_social'])
                        ->setCellValue('E'.$i, $value['ingreso'])
                        ->setCellValue('F'.$i, $value['salida'])
                        ->setCellValue('G'.$i, $value['totalHoras'])
                        ->setCellValue('H'.$i, $value['turno'])
                        ->setCellValue('I'.$i, $value['responsable'])
                        ->setCellValue('J'.$i, $value['placa'])                                          
                        ->setCellValue('K'.$i, $value['num_documento'])
                        ->setCellValue('L'.$i, $value['num_guia'])
                        ->setCellValue('M'.$i, $value['otros'])
                        ->setCellValue('N'.$i, $value['empleado']);
                                              
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

   public function eliminar($idevento)
   {
      $result = $this->eventos_model->eliminar($idevento);
      if ($result) {
          echo json_encode(['status' => STATUS_OK]);
          exit();
        } else {
          echo json_encode(['status' => STATUS_FAIL]);
          exit();
        }
          
   }
}
