<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;    
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
class Pagos extends CI_Controller
{

	public function __construct()
	{
	parent::__construct();
	   $this->load->model('comprobantes_compras_model');
       $this->load->model("pagos_model");
       $this->load->model("empleados_model");
       $this->load->model("empresas_model");
       $this->load->model("tipo_pagos_model");
       $this->load->model("proveedores_model");
       $this->load->library('pdf');

	}

    public function index(){         	           
        $data['vendedores'] = $this->empleados_model->select2(3);
        $this->load->view('templates/header_administrador');
    	$this->load->view('pagos/index',$data);
        $this->load->view('templates/footer');
    }

    public function listaComprobantes(){

    $rsComprobantes_ct = $this->pagos_model->selectComprobantesCredito_ct();
    $rsComprobantes_nv = array();
    //$rsComprobantes_nv = $this->pagos_model->selectComprobantesCredito_nv();

    $rsComprobantes =  $this->listaComprobantes_format($rsComprobantes_ct,$rsComprobantes_nv);
    //var_dump($rsComprobantes);exit();

    $rs =  '<table class="table table-streap">
               <tr>
                 <th>N°</th>
                 <th>Historial de Pagos</th>
                 <th>Proveedor</th>
                 <th>Serie</th>
                 <th>Numero</th>
                 <th>Fecha de emisión</th>
                 <th>Fecha de vencimiento</th>
                 <th>Moneda</th>
                 <th>Total Comprobante </th>
                 <th>Total Crédito </th>
                 <th>Saldo </th>
                 <th>Usuario</th>
                 <th>&nbsp;</th>
               </tr>';
    $i=1;
    $y=0;
    
    $sumTotal_total_a_pagar = 0;
    $sumTotal_total_credito = 0;
    $sumTotal_saldo = 0;
    
    //var_dump($rsComprobantes);exit;
   foreach($rsComprobantes  as $key => $value) {       
        $total_a_pagar = 0;         
        $total_credito = 0;
        $saldo = 0; 
        $rs .=  '<tr><td colspan="12">'.$key.'</td></tr>';
        foreach ($value as $key2 => $value2) {        
        //var_dump($value2);exit;
        //var_dump(array_keys($value));exit;
        $rowKey = implode(array_keys($value2));             
             $rs .=  '<tr><td colspan="12">'.$key2.'</td></tr>';
             //echo $key2.'-'.$rowKey;exit;      
            foreach ($value[$key2] as $value_1) {
            $rowColor = ($value_1['saldo'] <= 0) ? 'class="success"' : 'class="danger"';
            if($_POST['estado'] == '0'){//CANCELADO
                    $rowColor = ($value_1['saldo'] <= 0) ? 'class="success"' : '';
                    if($rowColor == '') continue;
            }  else if($_POST['estado'] == 1) {//POR CANCELAR
                    $rowColor = ($value_1['saldo'] <= 0) ? '' : 'class="danger"';
                    if($rowColor == '') continue;
            }
                  
            $rs .= '<tr '.$rowColor.'>
                    <td>'.$i.'</td>
                    <td class="col-sm-1"><a onclick="javascript:window.open(\''.base_url().'index.php/pagos/perfilPago/'. $value_1['comprobante_id'].'/'.$value_1['tipoComprobante'] .'\', \'\', \'width=750,height=600,scrollbars=yes,resizable=yes\')" ><span class="glyphicon glyphicon-search"></a></td>
                    <td class="col-sm-2">'.$value_1['cli_razon_social'].'</td>
                    <td class="col-sm-1">'.$value_1['serie'].'</td>
                    <td class="col-sm-1">'.$value_1['numero'].'</td>
                    <td class="col-sm-1">'.$value_1['fecha_de_emision'].'</td>
                    <td class="col-sm-1">'.$value_1['fecha_de_vencimiento'].'</td>
                    <td class="col-sm-1">'.$value_1['moneda'].'</td>
                    <td class="col-sm-1"> '.$value_1['simbolo'].' '.$value_1['total_a_pagar'].'</td>
                    <td class="col-sm-1"> '.$value_1['simbolo'].' '.$value_1['total_credito'].'</td>
                    <td class="col-sm-1"> '.$value_1['simbolo'].' '.$value_1['saldo'].'</td>
                    <td class="col-sm-1">'.$value_1['empleado'].'</td>
                    <td class="col-sm-1"><a type="button" data-id ="'.$value_1['comprobante_id'].'" data-saldo = "'.$value_1['saldo'].'" data-tipoComprobante = "'.$value_1['tipoComprobante'].'" data-cliente = "'.$value_1['cliente_id'].'" data-vendedor = "'.$value_1['empleado_id'].'" data-serNum = "'.$value_1['numero'].'" data-totalCredito = "'.$value_1['total_credito'].'" data-moneda = "'.$value_1['moneda'].'" class="glyphicon glyphicon-export btn_regPago" data-toggle="modal" data-target="#myModal"></a></td>';                

                    $rs .= '</tr>';                
                    $total_a_pagar += $value_1['total_a_pagar'];
                    $total_credito   += $value_1['total_credito'];
                    $saldo   += $value_1['saldo'];                        
              $y++;
           }}

           $rs .=  '<tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>                                      
                        <td><b>TOTAL: '.strtoupper($key).' SERIE '.$rowKey.'</b></td>                    
                        <td><b>'.$total_a_pagar.'</b></td>
                        <td><b>'.$total_credito.'</b></td>
                        <td><b>'.$saldo.'</b></td>
                        <td>&nbsp;</td>                    
                        <td>&nbsp;</td>                    
                    </tr>';
             $rs .= '</table><br><table class="table table-streap">';
                    $sumTotal_total_a_pagar += $total_a_pagar;
                    $sumTotal_total_credito += $total_credito;
                    $sumTotal_saldo   += $saldo;
        }
            $rs .=  '<tr>
                        <td>&nbsp;</td>
                        <td class="col-sm-1">&nbsp;</td>
                        <td class="col-sm-2">&nbsp;</td>
                        <td class="col-sm-1">&nbsp;</td>                                       
                        <td class="col-sm-1">&nbsp;</td>
                        <td class="col-sm-1">&nbsp;</td>
                        <td class="col-sm-1"><b>TOTALES VENTAS:</b></td>        
                        <td class="col-sm-1"><b>'.$sumTotal_total_a_pagar.'</b></td>
                        <td class="col-sm-1"><b>'.$sumTotal_total_credito.'</b></td>
                        <td class="col-sm-1"><b>'.$sumTotal_saldo.'</b></td>
                        <td class="col-sm-1">&nbsp;</td>                    
                        <td class="col-sm-1">&nbsp;</td>
                    </tr>';        
            $rs .= '</table>';        
        echo $rs;
    }

    public function listaComprobantes_format($rsComprobantes_ct,$rsComprobantes_nv){


            $arrayTotal =  array_merge($rsComprobantes_ct,$rsComprobantes_nv);            
            $array = array();


            $i = 0;
            foreach ($arrayTotal as $value) {
                $array[$value->tipo_documento][$value->serie][$i]['comprobante_id']  = $value->comprobante_id;
                $array[$value->tipo_documento][$value->serie][$i]['cliente_id'] = $value->cliente_id;
                $array[$value->tipo_documento][$value->serie][$i]['tipo_pago']  = $value->tipo_pago;
                $array[$value->tipo_documento][$value->serie][$i]['tipoComprobante']  = $value->tipoComprobante;
                $array[$value->tipo_documento][$value->serie][$i]['cli_razon_social'] = $value->cli_razon_social;
                $array[$value->tipo_documento][$value->serie][$i]['serie']  = $value->serie;
                $array[$value->tipo_documento][$value->serie][$i]['numero'] = $value->numser;
                $array[$value->tipo_documento][$value->serie][$i]['fecha_de_emision']  = $value->fecha_de_emision;
                $array[$value->tipo_documento][$value->serie][$i]['fecha_de_vencimiento'] = $value->fecha_de_vencimiento;
                $array[$value->tipo_documento][$value->serie][$i]['moneda']  = $value->moneda;
                $array[$value->tipo_documento][$value->serie][$i]['simbolo']  = $value->simbolo;
                $array[$value->tipo_documento][$value->serie][$i]['total_a_pagar']  = $value->total_a_pagar;
                $array[$value->tipo_documento][$value->serie][$i]['total_credito']  = $value->total_credito;
                $array[$value->tipo_documento][$value->serie][$i]['saldo']  = $value->saldo;
                $array[$value->tipo_documento][$value->serie][$i]['empleado_id']  = $value->empleado_id;
                $array[$value->tipo_documento][$value->serie][$i]['empleado']  = $value->empleado;
                $i++;
            }
            return $array;
    }

     public function  modal_crear(){      
      $data['pagos'] =  $this->pagos_model->select('',$_REQUEST['comprobante_id']);      
      $data['comprobante_id'] = $_REQUEST['comprobante_id'];
      $data['cliente_id'] = $_REQUEST['cliente_id'];
      $data['vendedor_id'] = $_REQUEST['vendedor_id'];
      $data['moneda'] = $_REQUEST['moneda'];
      $data['saldo'] = $_REQUEST['saldo'];
      $data['tipoComprobante'] = $_REQUEST['tipoComprobante'];
      $data['serNum'] = $_REQUEST['serNum'];
      $data['totalCredito'] = $_REQUEST['totalCredito'];
      $data['tipo_pagos'] =  $this->tipo_pagos_model->select_cc();

      $this->load->view("pagos/modal_crear",$data);
    }   


    public function rowPago(){
       $moneda = $_POST['moneda'];
       $pagos =  $this->pagos_model->select('',$_POST['comprobante_id'],$_POST['tipoComprobante']);
       
       $rs = '<table class="table table-streap">
                <tr>
                    <td>N°</td>
                    <td>MONTO</td>
                    <td>MONEDA</td>
                    <td>TIPO PAGO</td>
                    <td>FECHA</td>                    
                    <td>USUARIO</td>                    
                    <td>&nbsp;</td>
                </tr>';     
        $i=1;                    
        foreach($pagos as $value){            
            $rs .= '<tr>
                        <td>'.$i.'</td>
                        <td>'.$value->monto.'</td>
                        <td>'.$moneda.'</td>
                        <td>'.$value->tipo_pago.'</td>
                        <td>'.$value->fecha.'</td>    
                        <td>'.$value->empleado.'</td>                             
                        <td><a data-id ='.$value->id.' data-monto='.$value->monto.' class="glyphicon glyphicon-remove-sign removePago"></a></td>
                   </tr>';
            $i++;    
        } 
            $rs .= '</table>';
            echo $rs;           
    }    

    public function guardar(){    

        $error = array();
        if($_POST['monto'] == ''){
            $error['monto'] = 'Falta ingresar monto';
        }
        if($_POST['fecha'] == ''){
            $error['fecha'] = 'Falta ingresar fecha';
        }


        if(count($error)){
            echo json_encode(['status'=> STATUS_FAIL,'tipo'=>1, 'error'=>$error]);
            exit();
        }

        
    $data = $this->pagos_model->guardar();
        if ($data) {
            echo json_encode(['status' => STATUS_OK]);
        }
        else{
            echo json_encode(['status' => STATUS_FAIL,'tipo'=>2]);
        }
    }

   public function eliminar($idPago){


      $result = $this->pagos_model->eliminar($idPago);
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


   public function perfilPago($comprobante_id,$tipoComprobante){

        $data['pagos'] = $this->pagos_model->select('',$comprobante_id,$tipoComprobante);
        $this->load->view('templates/header_sin_menu_white');
        $this->load->view('pagos/perfilPago', $data);
        $this->load->view('templates/footer');
   }



   public function exportarExcel(){

         $rsComprobantes_ct = $this->pagos_model->selectComprobantesCredito_ct();
         //$rsComprobantes_nv = $this->cobros_model->selectComprobantesCredito_nv();
         $rsComprobantes_nv = array();

         $rsComprobantes =  $this->listaComprobantes_format($rsComprobantes_ct,$rsComprobantes_nv);
            
         $vendedorText = $_GET['vendedorText'];        

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
        
        $i=8;

        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(45);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(30);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(30);
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
                ->setCellValue('B1', 'REPORTE DE COMPROBANTES PAGOS PROVEEDORES');

        $spreadsheet->getActiveSheet()
                ->setCellValue('A2', 'FECHA DESDE')
                ->setCellValue('B2', $fecha_desde)
                ->setCellValue('A3', 'FECHA HASTA')
                ->setCellValue('B3', $fecha_hasta)
                ->setCellValue('A4', 'VENDEDOR')
                ->setCellValue('B4', $vendedorText)
                ->setCellValue('A5', '')
                ->setCellValue('B5', $transportistaText);


        $spreadsheet->getActiveSheet()
                ->setCellValue('A7', 'N°')
                ->setCellValue('B7', 'HISTORIAL COBROS')
                ->setCellValue('C7', 'CLIENTE')
                ->setCellValue('D7', 'SERIE')
                ->setCellValue('E7', 'NUMERO')
                ->setCellValue('F7', 'FECHA DE EMISION')
                ->setCellValue('G7', 'FECHA VENCIMIENTO')
                ->setCellValue('H7', 'TOTAL COMPROBANTE')
                ->setCellValue('I7', 'TOTAL CREDITO')
                ->setCellValue('J7', 'SALDO')
                ->setCellValue('K7', 'USUARIO')
                ->setCellValue('L7', '');                
        

        $sumTotal_total_a_pagar = 0;
        $sumTotal_total_credito = 0;
        $sumTotal_saldo = 0;

        $y= 0;
        $x= 0;
        foreach($rsComprobantes  as $key => $value) {     
            $total_a_pagar = 0;         
            $total_credito = 0;
            $saldo = 0; 

            $rs .=  '<tr><td colspan="12">'.$key.'</td></tr>';
            foreach ($value as $key2 => $value2) {        
            //var_dump($value2);exit;
            //var_dump(array_keys($value));exit;
            $rowKey = implode(array_keys($value2));             
             $rs .=  '<tr><td colspan="12">'.$key2.'</td></tr>';
             //echo $key2.'-'.$rowKey;exit;      
            foreach ($value[$key2] as $value_1) {                                            
                $rowColor = ($value_1['saldo'] <= 0) ? 'class="success"' : 'class="danger"';
                if($_GET['estado'] == '0'){//CANCELADO          
                        $rowColor = ($value_1['saldo'] <= 0) ? 'class="success"' : '';
                        if($rowColor == '') continue; 
                }  else if($_GET['estado'] == 1) {//POR CANCELAR
                        $rowColor = ($value_1['saldo'] <= 0) ? '' : 'class="danger"';
                        if($rowColor == '') continue; 
                }
                $spreadsheet->getActiveSheet()
                        ->setCellValue('A'.$i, $i)
                        ->setCellValue('B'.$i, $i)
                        ->setCellValue('C'.$i, $value_1['cli_razon_social'])
                        ->setCellValue('D'.$i, $value_1['serie'])
                        ->setCellValue('E'.$i, $value_1['numero'])
                        ->setCellValue('F'.$i, $value_1['fecha_de_emision'])
                        ->setCellValue('G'.$i, $value_1['fecha_de_vencimiento'])
                        ->setCellValue('H'.$i, $value_1['simbolo'].' '.$value_1['total_a_pagar'])
                        ->setCellValue('I'.$i, $value_1['simbolo'].' '.$value_1['total_credito'])
                        ->setCellValue('J'.$i, $value_1['simbolo'].' '.$value_1['saldo'])
                        ->setCellValue('K'.$i, $value_1['empleado']);                        
 


                $total_a_pagar += $value_1['total_a_pagar'];
                $total_credito   += $value_1['total_credito'];
                $saldo   += $value_1['saldo']; 

                $i++;
            }}

            $spreadsheet->getActiveSheet()                        
                        ->setCellValue('G'.$i, 'TOTAL: '.strtoupper($key).' SERIE '.$rowKey)
                        ->setCellValue('H'.$i, $total_a_pagar)
                        ->setCellValue('I'.$i, $total_credito)
                        ->setCellValue('J'.$i, $saldo);
        
            //FILA DE SUBTOTALES                    
            $sumTotal_total_a_pagar += $total_a_pagar;
            $sumTotal_total_credito += $total_credito;
            $sumTotal_saldo   += $saldo;
                        
            $i++;
         }
         //FILA TOTALES
         $spreadsheet->getActiveSheet()                        
                        ->setCellValue('G'.$i, 'TOTAL VENTAS')
                        ->setCellValue('H'.$i, $sumTotal_total_a_pagar)
                        ->setCellValue('I'.$i, $sumTotal_total_credito)
                        ->setCellValue('J'.$i, $sumTotal_saldo);

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


   public function pdfPago(){                  
        $data['empresa'] = $this->empresas_model->select(1);
        $data['proveedor'] = $this->proveedores_model->select($this->uri->segment(5));
        $data['vendedor']  = $this->empleados_model->select($this->uri->segment(6));
        $data['pago'] = $this->pagos_model->select('',$this->uri->segment(4),$this->uri->segment(3));
        $data['serNum'] = $this->uri->segment(7);
        $data['totalCredito'] = $this->uri->segment(8);


        //var_dump($data['proveedor']);exit;
        
        $html = $this->load->view('templates/pago.php',$data,TRUE);
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper('A4', 'portrait');
        $this->pdf->render();
        $this->pdf->stream("N.caja.cv-$idpago.pdf",
            array("Attachment"=>0)
        );
      }


      public function ticketPagopdf($idPago){
          $rsEmpresa = $this->empresas_model->select(1);
          $rsProveedor = $this->proveedores_model->select($this->uri->segment(5));
          $rsEmpleado  = $this->empleados_model->select($this->uri->segment(6));
          $rsPago      = $this->pagos_model->select('',$this->uri->segment(4),$this->uri->segment(3));
          $serNum      = $this->uri->segment(7);
          $totalCredito = $this->uri->segment(8);          

        $data = [
                    "pago" => $rsPago,
                    "proveedor"=> $rsProveedor,
                    "serNum"   => $serNum,
                    "totalCredito" => $totalCredito,
                    "vendedor" => $rsEmpleado,
                    "empresa"  => $rsEmpresa
                ];

        $html = $this->load->view("templates/pago_ticket.php",$data,TRUE); 
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper(array(0,0,85,440), 'portrait');
        $this->pdf->render();
        $this->pdf->stream("R.pago.PC-002.pdf",
           array("Attachment"=>0)
          );          
      } 


    public function estadoCuenta(){

        $this->load->view('templates/header_administrador');
        $this->load->view('proveedores/estadoCuenta',$data);
        $this->load->view('templates/footer');
    }

    //ESTADO DE CUENTA PAGO PROVEEDOR 24-10-2020
    public function estadoCuenta_g(){
        $rsComprobantes_ct = $this->pagos_model->selectComprobantesCredito_ct();
        $rsComprobantes_nv = array();
        //$rsComprobantes_nv = $this->cobros_model->selectComprobantesCredito_nv();
        $rsComprobantes =  $this->listaComprobantes_format($rsComprobantes_ct,$rsComprobantes_nv);
        //var_dump($rsComprobantes);exit;

        $rs =  '<table class="table table-bordered">
               <tr>
                 <th>N°</th>
                 <th>Usuario</th>                 
                 <th>Proveedor</th>                 
                 <th>Numero</th>
                 <th>Fecha de emisión</th> 
                 <th>Fecha de vencimiento</th>
                 <th>Moneda</th>
                 <th>Total Venta</th>
                 <th>Condición</th>                                 
                 <th>Total Crédito </th>                 
                 <th>Saldo </th>                                                             
               </tr>';
    $i=1;
    $y=0;
    
    $sumTotal_total_a_pagar = 0;
    $sumTotal_total_credito = 0;
    $sumTotal_saldo = 0;

   foreach($rsComprobantes  as $key => $value) {   
        $total_a_pagar = 0;         
        $total_credito = 0;
        $saldo = 0; 

        $rs .=  '<tr><td colspan="12">'.$key.'</td></tr>';
        foreach ($value as $key2 => $value2) {        
        //var_dump(array_keys($value));exit;
        $rowKey = implode(array_keys($value2));             
             $rs .=  '<tr><td colspan="12">'.$key2.'</td></tr>';
        
        foreach ($value[$key2] as $value_1) {

             $rsPago  = ($value_1['tipo_pago'] == 'Crédito') ? $this->pagos_model->select('',$value_1['comprobante_id'],$value_1['tipoComprobante']) : '';
             $rowColor = ($value_1['tipo_pago'] == 'Crédito') ? 'class="success"' : 'class=""'; 
             $saldo  =   ($value_1['tipo_pago'] == 'Crédito') ? $value_1['saldo'] : '';   
                      
        $rs .= '<tr '.$rowColor.'>
                <td class="col-sm-1">'.$i.'</td>
                <td class="col-sm-1">'.$value_1['empleado'].'</td>                
                <td class="col-sm-2">'.$value_1['cli_razon_social'].'</td>                
                <td class="col-sm-1">'.$value_1['numero'].'</td>
                <td class="col-sm-1">'.$value_1['fecha_de_emision'].'</td>   
                <td class="col-sm-1">'.$value_1['fecha_de_vencimiento'].'</td>
                <td class="col-sm-1">'.$value_1['moneda'].'</td>
                <td class="col-sm-1">'.$value_1['simbolo'].' '.$value_1['total_a_pagar'].'</td>
                <td class="col-sm-1">'.$value_1['tipo_pago'].'</td>                             
                <td class="col-sm-1">'.$value_1['simbolo'].' '.$value_1['total_credito'].'</td>                
                <td class="col-sm-1">'.$value_1['simbolo'].' '.$saldo.'</td>';


                //var_dump($rsCobro);exit;
        if(!empty($rsPago)){
            $rs .= '</tr>';
                    $rowColor = 'class="info"';  
                    $saldo = $value_1['total_credito'];
                    foreach($rsPago as $value_3){                        
                        $saldo -= $value_3->monto;
                        $rs .= '<tr '.$rowColor.'>
                                    <td>&nbsp;</td>
                                    <td>'.$value_3->empleado.'</td>
                                    <td>&nbsp;</td>                                                     
                                    <td>&nbsp;</td>
                                    <td>'.$value_3->fecha.'</td>
                                    <td>&nbsp;</td>                                   
                                    <td>&nbsp;</td> 
                                    <td>'.$value_3->tipo_pago.'</td>
                                    <td>'.$value_1['simbolo'].' '.$value_3->monto.'</td>
                                    <td>'.$value_1['simbolo'].' '.$saldo.'</td>                                                                                        
                                    </tr>';                
                 }   
        }else{
            $rs .= '</tr>';
        }
                                    
            $total_a_pagar += $value_1['total_a_pagar'];
            $total_credito += $value_1['total_credito'];
            $saldoTotalCredito += $saldo;
          $y++;
          $i++;
       }}

       $rs .=  '<tr>
                    <td>&nbsp;</td>                    
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>                    
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td><b>TOTAL: '.strtoupper($key).' SERIE '.$rowKey.'</b></td>                                        
                    <td><b>'.$total_credito.'</b></td>                    
                    <td><b>'.$saldoTotalCredito.'</b></td>
                </tr>';
         $rs .= '</table><br><table class="table table-bordered">';
                $sumTotal_total_a_pagar += $total_a_pagar;
                $sumTotal_total_credito += $total_credito;
                $sumTotal_saldo   += $saldoTotalCredito;
    }
        $rs .=  '<tr>                    
                    <td class="col-sm-1">&nbsp;</td>
                    <td class="col-sm-1">&nbsp;</td>
                    <td class="col-sm-1">&nbsp;</td>      
                    <td class="col-sm-1">&nbsp;</td>
                    <td class="col-sm-1">&nbsp;</td>
                    <td class="col-sm-1">&nbsp;</td>
                    <td class="col-sm-1">&nbsp;</td>
                    <td class="col-sm-1"><b>TOTALES VENTAS:</b></td>                            
                    <td class="col-sm-1"><b>'.$sumTotal_total_credito.'</b></td>                    
                    <td class="col-sm-1"><b>'.$sumTotal_saldo.'</b></td>
                </tr>';        
        $rs .= '</table>';
        echo $rs;
    }




    public function exportarExcel_estadoCuenta(){

        $rsComprobantes_ct = $this->pagos_model->selectComprobantesCredito_ct();
        $rsComprobantes_nv = array();
        //$rsComprobantes_nv = $this->cobros_model->selectComprobantesCredito_nv();

        $rsComprobantes =  $this->listaComprobantes_format($rsComprobantes_ct,$rsComprobantes_nv);
            
        $vendedorText = $_GET['vendedorText'];        
        $fecha_inicial= $_GET['fecha_inicial'];
        $fecha_final  = $_GET['fecha_final'];

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
        
        $i=8;

        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(45);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(30);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(40);
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
                ->setCellValue('B1', 'REPORTE DE ESTADO DE CUENTA');

        $spreadsheet->getActiveSheet()
                ->setCellValue('A2', 'FECHA INICIAL')
                ->setCellValue('B2', $fecha_inicial)
                ->setCellValue('A3', 'FECHA FINAL')
                ->setCellValue('B3', $fecha_final);                


        $spreadsheet->getActiveSheet()
                ->setCellValue('A7', 'N°')
                ->setCellValue('B7', 'USUARIO')
                ->setCellValue('C7', 'CLIENTE')                
                ->setCellValue('D7', 'NUMERO')
                ->setCellValue('E7', 'FECHA DE EMISION')
                ->setCellValue('F7', 'FECHA DE VENCIMIENTO')                
                ->setCellValue('G7', 'TOTAL VENTA')
                ->setCellValue('H7', 'CONDICION')
                ->setCellValue('I7', 'TOTAL CREDITO')
                ->setCellValue('J7', 'SALDO');                
        

        $sumTotal_total_a_pagar = 0;
        $sumTotal_total_credito = 0;
        $sumTotal_saldo = 0;

        $y= 0;
        $x= 0;

        foreach($rsComprobantes  as $key => $value) {     
            $total_a_pagar = 0;         
            $total_credito = 0;
            $saldo = 0; 

            $rs .=  '<tr><td colspan="12">'.$key.'</td></tr>';
            foreach ($value as $key2 => $value2) {        
            //var_dump(array_keys($value));exit;
            $rowKey = implode(array_keys($value2));             
                 $rs .=  '<tr><td colspan="12">'.$key2.'</td></tr>';
            
            foreach ($value[$key2] as $value_1) {                              
                $rsPago  = ($value_1['tipo_pago'] == 'Crédito') ? $this->pagos_model->select('',$value_1['comprobante_id'],$value_1['tipoComprobante']) : '';
                $rowColor = ($value_1['tipo_pago'] == 'Crédito') ? 'class="success"' : 'class=""'; 
                $saldo  =   ($value_1['tipo_pago'] == 'Crédito') ? $value_1['saldo'] : '';   

                $spreadsheet->getActiveSheet()
                        ->setCellValue('A'.$i, $i)
                        ->setCellValue('B'.$i, $value_1['empleado'])
                        ->setCellValue('C'.$i, $value_1['cli_razon_social'])
                        ->setCellValue('D'.$i, $value_1['numero'])
                        ->setCellValue('E'.$i, $value_1['fecha_de_emision'])
                        ->setCellValue('F'.$i, $value_1['fecha_de_vencimiento'])                        
                        ->setCellValue('G'.$i, $value_1['simbolo'].' '.$value_1['total_a_pagar'])
                        ->setCellValue('H'.$i, $value_1['tipo_pago'])
                        ->setCellValue('I'.$i, $value_1['simbolo'].' '.$value_1['total_credito'])
                        ->setCellValue('J'.$i, $saldo);


                if(!empty($rsPago)){                    
                    $i++;
                    $saldo = $value_1['total_credito'];
                    foreach($rsPago as $value_3){                        
                        $saldo -= $value_3->monto;
                        $spreadsheet->getActiveSheet()
                                ->setCellValue('A'.$i, '')
                                ->setCellValue('B'.$i, $value_3->empleado)
                                ->setCellValue('C'.$i, '')
                                ->setCellValue('D'.$i, '')
                                ->setCellValue('E'.$i, $value_3->fecha)
                                ->setCellValue('F'.$i, '')
                                ->setCellValue('G'.$i, '')
                                ->setCellValue('H'.$i, $value_3->tipo_pago)
                                ->setCellValue('I'.$i, $value_3->monto)
                                ->setCellValue('J'.$i, $saldo);
                                $i++;
                    }                                 
            }                    
            $total_a_pagar += $value_1['total_a_pagar'];
            $total_credito += $value_1['total_credito'];
            $saldoTotalCredito += $saldo;
            $y++;
            $i++;    
        }}
        

            $spreadsheet->getActiveSheet()                        
                        ->setCellValue('H'.$i, 'TOTAL: '.strtoupper($key).' SERIE '.$rowKey)
                        ->setCellValue('I'.$i, $total_credito)
                        ->setCellValue('J'.$i, $saldoTotalCredito);                   
        
            //FILA DE SUBTOTALES                    
            $sumTotal_total_a_pagar += $total_a_pagar;
            $sumTotal_total_credito += $total_credito;
            $sumTotal_saldo   += $saldoTotalCredito;
                        
            $i++;
         }
         //FILA TOTALES
         $spreadsheet->getActiveSheet()                        
                        ->setCellValue('H'.$i, 'TOTAL VENTAS')                        
                        ->setCellValue('I'.$i, $sumTotal_total_credito)
                        ->setCellValue('J'.$i, $sumTotal_saldo);

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