<?PHP


use Mike42\Escpos\EscposImage;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class Cajas extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model("cajas_model");
        $this->load->model("movimiento_caja_model");
        $this->load->model("comprobantes_model");
        $this->load->model("empresas_model");
        $this->load->model("empleados_model");
        $this->load->library('pdf');

        $empleado_id = $this->session->userdata('empleado_id');
        $almacen_id = $this->session->userdata("almacen_id");
        if (empty($empleado_id) or empty($almacen_id)) {
            $this->session->set_flashdata('mensaje', 'No existe sesion activa');
            redirect(base_url());
        }
        }
    

    public function index(){
                  
        $data['vendedores'] = $this->empleados_model->select2(3);          
        $data['caja'] = $this->cajas_model->ultimoRegCaja();

    	  $this->load->view('templates/header_administrador');
        $this->load->view('cajas/index',$data);
        $this->load->view('templates/footer');

      }

       public function selectRowsCajamoviento(){
        $rsCajMov = $this->cajas_model->select();
         // $this->load->view('templates/footer2');
        
        //var_dump($rsCajMov);exit;
        $rsCaj   = "<table id='tablaCaja' class='display'><thead><tr>";
        $rsCaj  .= "<th>ID</th>";
        $rsCaj  .= "<th>Saldo-inicial</th>";
        $rsCaj  .= "<th>F.Apertura</th>";
        $rsCaj  .= "<th>Saldo-final</th>";
        $rsCaj  .= "<th>F.Cierre</th>";

        $rsCaj  .= "<th>T.Contado</th>";
        $rsCaj  .= "<th>T.Deposito</th>";
        $rsCaj  .= "<th>T.Cheque</th>";
        $rsCaj  .= "<th>T.tarjeta</th>";
        $rsCaj  .= "<th>T.Cupon</th>";
        $rsCaj  .= "<th>T.Credito</th>";
        $rsCaj  .= "<th>T.Ventas</th>";
        $rsCaj  .= "<th>Ingreso Efectivo</th>";
        $rsCaj  .= "<th>Salida Efectivo</th>";


        $rsCaj  .= "<th>Tipo_transacion</th>";
        $rsCaj  .= "<th>Usuario</th>";
        $rsCaj  .= "<th>PDF</th>";
        $rsCaj  .= "<th>TICKET</th></tr></thead><tbody>";        
        //$rsCaj  .= "<th>Estado</th></tr>";

        $i = 1;
        foreach ($rsCajMov as $value) {

            $rsCaj  .= '<tr><td>'.$i.'</td>';
            $rsCaj  .= '<td>'.$value->saldo_inicial.'</td>';          
            $rsCaj  .= '<td>'.$value->fecha.'</td>';
            $rsCaj  .= '<td>'.$value->saldo_final.'</td>';          
            $rsCaj  .= '<td>'.$value->fechaCierre.'</td>';

            $rsCaj  .= '<td>'.$value->totalContado.'</td>';
            $rsCaj  .= '<td>'.$value->totalDeposito.'</td>';
            $rsCaj  .= '<td>'.$value->totalCheque.'</td>';

            $rsCaj  .= '<td>'.$value->totalTarjeta.'</td>';
            $rsCaj  .= '<td>'.$value->totalCupon.'</td>';
            $rsCaj  .= '<td>'.$value->totalCredito.'</td>';
            $rsCaj  .= '<td>'.$value->totalVenta.'</td>';

            $rsCaj  .= '<td>'.$value->movCajIngreso.'</td>';
            $rsCaj  .= '<td>'.$value->movCajSalida.'</td>';


            $rsCaj  .= '<td>'.$value->tipo_cTransaccion.'</td>';
            $rsCaj  .= '<td>'.$value->empleado.'</td>';
            $rsCaj  .= '<td><a id="pdfCaja" data-id="'.$value->id.'" href="#"><img src="'.base_url().'/images/pdf.png"</a></td>';
            $rsCaj  .= '<td><a id="ticketCaja" data-id="'.$value->id.'" href="#"><span class="glyphicon glyphicon-print"></a></td></tr>';
            

            $i++;            
            //$rsCaj  .= '<td>'.$value->estado.'</td>';

            //$rsCaj  .= '<td><a class="btn btn-primary btn-sm btn_modificar" data-id= '.$value->id.' data-toggle="modal" data-target="#modalCajaMov">MODIFICAR</a></td>'; 

            //$rsCaj  .= '<td><a class="btn btn-danger btn-sm btn_eliminar" id="eliminar" data-id='.$value->id.'>ELIMINAR</a></td></tr>';
        }
        
        $rsCaj .="</tbody></table>";

        echo $rsCaj;
         
      

      }

      public function apertura(){

            $this->load->view('cajas/modal_apertura');

      }

      public function cierre(){
             
           $caja_id = $this->uri->segment(3);
           $data['caja'] = $this->cajas_model->select($caja_id);
           
           $data['cajaMov'] = $this->movimiento_caja_model->selectMovCaj($data['caja']->fecha);    

           $data['selecReporteCaja'] = $this->cajas_model->selecReporteCaja_ct($data['caja']->fecha);
           $data['selecReporteCaja_np'] = $this->cajas_model->selecReporteCaja_np($data['caja']->fecha);
           $arrayTotal = array_merge($data['selecReporteCaja'],$data['selecReporteCaja_np']);
           $data['selecReporteCaja'] = $this->reporteFormat($arrayTotal);


           $data['selecReporteCobro_cc'] = $this->cajas_model->selectReporteCobros($data['caja']->fecha);           
           $data['selecReporteCobro'] = $this->reporteFormat($data['selecReporteCobro_cc']);  


           //CAMBIO - ALEXANDER FERNANDEZ 10-02-2021
           $data['selectCambio_ct'] = $this->cajas_model->selectCambio_ct($data['caja']->fecha);
           $data['selectCambio_np'] = $this->cajas_model->selectCambio_np($data['caja']->fecha);

           //var_dump($data['selecReporteCobro']);exit;

           $this->load->view('cajas/modal_cierre',$data);
      }


      public function reporteFormat($arrayTotal){

          $results = array();
           foreach ($arrayTotal as $value) {
              if(!isset($results[$value['tipo_pago']]) ){           
                $results[$value['tipo_pago']] = 0;                
              }
             $results[$value['tipo_pago']]    += $value['montoTotal'];
           } 

           return $results;      
      }

      public function guardar(){
        //echo "holaaaa";exit();
          $resultado = $this->cajas_model->guardarApertura();
          //echo $resultado;exit;
          if ($resultado) {
            echo json_encode(['status' => STATUS_OK, 'caja_id' => $resultado]);
          }else{

            echo json_encode(['status' => STATUS_FAIL]);
          }

            //return true;
      }



      public function guardarCierre(){

        $rsCierre = $this->cajas_model->guardarCierre();
        if($rsCierre){
            echo json_encode(['status' => STATUS_OK ]);
        } else {
            echo json_encode(['status' => STATUS_FAIL ]);
        }
      }

      public function pdfCaja(){          
        $data['empresa'] = $this->empresas_model->select(1);
        $data['caja'] = $this->cajas_model->select($this->uri->segment(3));      
        $html = $this->load->view('templates/caja.php',$data,TRUE);       

        $this->pdf->loadHtml($html);
        $this->pdf->setPaper('A4', 'portrait');
        $this->pdf->render();
        $this->pdf->stream("N.caja.cv-$idcaja.pdf",
            array("Attachment"=>0)
        );
      }


      public function ticketCajapdf($idCaja){
          $rsEmpresa = $this->empresas_model->select(1);
          $rsCaja    = $this->cajas_model->select($this->uri->segment(3));

        $data = [
                    "caja" => $rsCaja,
                    "empresa"  => $rsEmpresa
                ];

        $html = $this->load->view("templates/caja_ticket.php",$data,TRUE); 
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper(array(0,0,85,440), 'portrait');
        $this->pdf->render();
        $this->pdf->stream("R.caja.RC-002.pdf",
           array("Attachment"=>0)
          );          
      }    
}
