<?PHP if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use Endroid\QrCode\QrCode;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\Printer;
use mikehaertl\wkhtmlto\Pdf;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

use PhpOffice\PhpSpreadsheet\Spreadsheet;    
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Download extends CI_Controller {

    public function __construct() {
        parent::__construct();  
        $this->load->model('comprobantes_model');
        $this->load->model('items_model');
        $this->load->model('igv_model');
        $this->load->model('icbper_model');
        $this->load->model('tipo_igv_model');
        $this->load->model('elemento_adicionales_model');
        $this->load->model('tipo_documentos_model');
        $this->load->model('tipo_pagos_model');
        $this->load->model('tipo_items_model');
        $this->load->model('tipo_ncreditos_model');
        $this->load->model('tipo_ndebitos_model');        
        $this->load->model('accesos_model');
        $this->load->model('clientes_model');
        $this->load->model('monedas_model');
        $this->load->model('empleados_model');
        $this->load->model('empresas_model');
        $this->load->model('tipo_cambio_model');
        $this->load->model('ser_nums_model');
        $this->load->model('comprobante_anulados_model');
        $this->load->model('cuentas_model');
        $this->load->model('variables_diversas_model');
        $this->load->model('ticket_model');
        $this->load->model('productos_model');
        $this->load->model('categoria_model');
        $this->load->model('medida_model');
        $this->load->model('resumenes_model');
        $this->load->model('tipo_clientes_model');
        $this->load->model('cajas_model');
        $this->load->model('almacenes_model');
        $this->load->model('notas_model');
        $this->load->model('transportistas_model');   
        $this->load->model('historias_model');        
        $this->load->model('historia_estados_model');
        $this->load->model('historia_estadoComprobante_model');
        $this->load->model('historia_imagenes_model');
        $this->load->model('especialidades_model');
        $this->load->model('profesionales_model');
        $this->load->model('pacientes_model');
        $this->load->library('pdf');
    }

  
    //A4
    public function downloadPdf($comprobante_id){
        $comprobante = $this->comprobantes_model->select($comprobante_id);       
        $this->create_pdf($comprobante_id);                  
    }

    public function downloadPdf_nv($notap_id){
        $nota = $this->notas_model->select($notap_id);     
        $this->create_pdf_nv($notap_id);                    
    }

    public function downloadPdf_hc($historia_id){
        $historia = $this->historias_model->select($historia_id);     
        $this->create_pdf_hc($historia_id);
    }

    //TICKET
    public function downloadPdfTicket($comprobante_id){
        $comprobante = $this->comprobantes_model->select($comprobante_id);
        $this->pdfTicket($comprobante_id);    
    }

    public function downloadPdfTicket_nv($notap_id){
        $nota = $this->notas_model->select($notap_id);
        $this->pdfTicket_nv($notap_id);
    }

    public function downloadPdfTicket_hc($historia_id){
        $historia = $this->historias_model->select($historia_id);
        $this->pdfTicket_hc($historia_id);
    }

    public function create_pdf($comprobante_id = '')
    {
        require_once (APPPATH .'libraries/Numletras.php');
        /*datos de la empresa*/
        $this->db->from("empresas")
                 ->where("id",1);
        $query = $this->db->get();
        $rsEmpresa = $query->row();
        /*obtenemos todos los datos del documento*/
        $this->db->from("comprobantes as com")
                 ->join("tipo_documentos as tdoc", "com.tipo_documento_id=tdoc.id")
                 ->join("clientes as cli", "com.cliente_id=cli.id")
                 ->join("transportistas as trans", "com.transportista_id=trans.transp_id")
                 ->join("monedas as tmon", "com.moneda_id=tmon.id")
                 ->join("tipo_pagos as tpag", "com.tipo_pago_id=tpag.id")
                 ->where("com.id",$comprobante_id);         
        $query = $this->db->get();
        $rsComprobante = $query->row();
        /*obtenemos el detalle del documento*/
        /*$this->db->from("items")
                 ->where("comprobante_id", $comprobante_id);*/

          $this->db->select('i.*,p.*,m.medida_codigo_unidad')
                 ->from("items as i")
                 ->join("productos as p","p.prod_id=i.producto_id", 'left')
                 ->join("medida as m","m.medida_id=i.unidad_id")
                 ->where("i.comprobante_id", $comprobante_id);
        $query = $this->db->get();
        $rsDetalle = $query->result();

        //PAGO MONTO ALEXANDER FERNANDEZ 14-10-2020
        $rsPagoMonto =  $this->db->from('comprobante_pagos cmp')
                                ->join('tipo_pagos tpg','cmp.tipo_pago_id =  tpg.id')
                                ->where('cmp.comprobante_id', $comprobante_id)
                                ->get()
                                ->result();

        $rsComprobante->fecha_de_emision = (new DateTime($rsComprobante->fecha_de_emision))->format("d/m/Y");
        $rsComprobante->fecha_de_vencimiento = ($rsComprobante->fecha_de_vencimiento!='')?(new DateTime($rsComprobante->fecha_de_vencimiento))->format("d/m/Y"):'';
        /*documento relacionado*/
        $rsRelacionado = $this->db->from("comprobantes")
                                  ->where("id", $rsComprobante->com_adjunto_id)
                                  ->get()
                                  ->row();

        //convetimos el total en texto
        $num = new Numletras();
        $totalVenta = explode(".",$rsComprobante->total_a_pagar);
        $totalLetras = $num->num2letras($totalVenta[0]);
        $totalLetras = $totalLetras.' con '.$totalVenta[1].'/100 '.$rsComprobante->moneda;
        $rsComprobante->total_letras = $totalLetras; 

        /*anticipos del documento*/
        $this->db->from("comprobante_anticipo as coma")
                 ->join("comprobantes as com", "coma.anticipo_id=com.id")
                 ->where("coma.comprobante_id", $comprobante_id);
        $query = $this->db->get();
        $rsAnticipos = $query->result(); 
        $totalAnticipo = 0;
        foreach($rsAnticipos as $item)
        {
           $totalAnticipo += $item->total_a_pagar; 
        }
        $data['comprobante'] = $this->comprobantes_model->select($comprobante_id);
        $rsComprobante->total_anticipos = $totalAnticipo;
        //$certificado = $this->ObtenerCertificado($rsEmpresa->ruc,$rsComprobante->codigo,$rsComprobante->serie,$rsComprobante->numero);    
        $certificado = $rsComprobante->firma_sunat;
        $configuracion = $this->db->from('comprobantes_ventas')->get()->row();

        //ALEXANDER FERNANDEZ 31-10-2020
        $rs_almacen_principal = $this->almacenes_model->select($this->session->userdata('almacen_id'));//datos del almacen principal
        $data = [
                    "comprobante"   => $rsComprobante,
                    "relacionado"   => $rsRelacionado,
                    "empresa"       => $rsEmpresa,
                    "detalles"      => $rsDetalle,
                    "pagoMonto"     =>  $rsPagoMonto,
                    "anticipos"     => $rsAnticipos,
                    "rutaqr"        => $this->GetImgQr($data['comprobante']),
                    "certificado"   => $certificado,
                    "configuracion" => $configuracion,
                    "almacen_principal" => $rs_almacen_principal
                ];
        $html = $this->load->view("templates/invoice.php",$data,true);
        
        $archivo = $rsEmpresa->ruc.'-0'.$rsComprobante->tipo_documento_id.'-'.$rsComprobante->serie.'-'.$rsComprobante->numero.'.pdf';
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper('A4', 'portrait');
        $this->pdf->render();
        $tipo_documento_descargar = str_replace(" ","_",$rsComprobante->tipo_documento);
        $this->pdf->stream("$tipo_documento_descargar-$rsComprobante->serie-$rsComprobante->numero.pdf",
            array("Attachment"=>0)
        );       
    }


    public function create_pdf_nv($idNota){
        
        $rsNota = $this->db->from("nota_pedido as np")
                           ->join("monedas as mon", "np.notap_moneda_id=mon.id")
                           ->join("tipo_pagos tpg","np.notap_tipopago_id = tpg.id")
                           ->join("transportistas tra","np.notap_transportista_id = tra.transp_id")
                           ->where("notap_id", $idNota)
                           ->get()
                           ->row();

                //var_dump($rsNota);exit;
        /*formateamos fecha*/
        $rsNota->notap_fecha = (new DateTime($rsNota->notap_detalle))->format("d/m/Y");                   
        $rsDetalles =  $this->db->from("nota_pedido_detalle as f")
                                ->join('productos as p','p.prod_id=f.notapd_producto_id') 
                           
                           ->where("f.notapd_notap_id", $idNota)
                           ->get()
                           ->result();

                           //var_dump($rsDetalles);exit;

        $rsNota->detalles = $rsDetalles;    

        //PAGO MONTO ALEXANDER FERNANDEZ 14-10-2020
        $rsPagoMonto =  $this->db->from('nota_pagos cmp')
                                ->join('tipo_pagos tpg','cmp.tipo_pago_id =  tpg.id')
                                ->where('cmp.nota_id', $idNota)
                                ->get()
                                ->result();                                 

        $rsEmpresa = $this->db->from("empresas")
                              ->where("id", 1)
                              ->get()
                              ->row();  

        $rsCliente =  $this->db->from("clientes")
                              ->where("id", $rsNota->notap_cliente_id)
                              ->get()
                              ->row();
        
        $rsEmpleado =  $this->db->from("empleados")
                      ->where("id", $rsNota->notap_empleado_insert)
                      ->get()
                      ->row();

        //ALEXANDER FERNANDEZ 31-10-2020
        $rs_almacen_principal = $this->almacenes_model->select($this->session->userdata('almacen_id'));//datos del almacen principal                                            
        $data = [
                    "empresa" => $rsEmpresa,
                    "nota"    => $rsNota,
                    "pagoMonto" =>  $rsPagoMonto,
                    "cliente" => $rsCliente,
                    "empleado" => $rsEmpleado,
                    "almacen_principal" => $rs_almacen_principal

                ];                   
        $html = $this->load->view("templates/nota.php",$data,true); 

        ////////////////////////////////////////
        $archivo = $rsEmpresa->ruc.'-NP'.$rsNota->notap_correlativo.'.pdf';
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper('A4', 'portrait');
        $this->pdf->render();        
        $this->pdf->stream("N.Venta.NP-$idNota.pdf",
            array("Attachment"=>0)
        );        
    }



    public function create_pdf_hc($idHistoria = '')
    {        
        
        $rsHistoria = $this->db->select('his.his_id his_id,his.his_correlativo his_correlativo,DATE_FORMAT(his.his_fecha, "%d-%m-%Y %h:%i:%s") his_fecha,his.his_ini_peso,his.his_ini_talla,his.his_ini_presion_arterial,his.his_ini_temperatura,his.his_ini_otros,his.his_enfermedad_actual,his.his_motivo,his.his_diagnostico,his.his_tratamiento,his.his_recomendacion,his.his_documento_venta,DATE_FORMAT(his.his_fecha_cita, "%d-%m-%Y %h:%i:%s") his_fecha_cita,pac.id paciente_id,pac.razon_social pac_razon_social,prof.prof_nombre prof_nombre,prof.prof_firma prof_firma,esp.esp_descripcion,CONCAT(emp.nombre," ",emp.apellido_paterno) empleado,hie.hie_descripcion estado',FALSE)
                            ->from('historias his')
                            ->join('pacientes pac','pac.id = his.his_paciente_id')
                            ->join('profesionales prof','prof.prof_id =  his_profesional_id')
                            ->join('especialidades esp','esp.esp_id = prof_especialidad_id')
                            ->join('empleados emp','emp.id = his.his_empleado_insert')
                            ->join('historia_estados hie','hie.hie_id = his.his_historia_estado_id')
                            ->where("his.his_id", $idHistoria)
                            ->where("his_estado",ST_ACTIVO)
                            ->get()
                            ->row();
                            //var_dump($rsHistoria);Exit;

        /*formateamos fecha*/
        $rsHistoria->his_fecha = (new DateTime($rsHistoria->his_fecha))->format("d/m/Y h:i:s");                   
        $rsDetalles =  $this->db->from("historia_detalles as f")
                                ->join('productos as p','p.prod_id=f.hid_producto_id','left')                            
                                ->where("f.hid_his_id", $idHistoria)
                                ->get()
                                ->result();

        //HEIGHT TICKET 21-09-2020
        $countItems = count($rsDetalles);
        $ticketHeight =  $countItems*22;
        $ticketHeight = ($ticketHeight > 440) ? $ticketHeight : 440;        


        $rsHistoria->detalles = $rsDetalles;                                     

        $rsEmpresa = $this->db->from("empresas")
                              ->where("id", 1)
                              ->get()
                              ->row();  

        $rsPaciente =  $this->db->from("pacientes")
                                ->where("id", $rsHistoria->paciente_id)
                                ->get()
                                ->row();       


        $rsImagenes =  $this->db->from("historia_imagenes")
                                ->where("hii_his_id",$idHistoria)
                                ->get()
                                ->result();

        //ALEXANDER FERNANDEZ DE LA CRUZ 30-10-2020
        $rs_almacen_principal = $this->almacenes_model->select($this->session->userdata('almacen_id'));//datos del almacen principal
        $data = [
                    "empresa" => $rsEmpresa,
                    "historia" => $rsHistoria,               
                    "paciente" => $rsPaciente,
                    "imagenes" => $rsImagenes,
                    "almacen_principal" => $rs_almacen_principal
                ];
        $html = $this->load->view("templates/historia.php",$data,true); 
        
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper('A4', 'portrait');
        $this->pdf->render();
        $this->pdf->stream("HISTORIA.H-$idHistoria.pdf",
            array("Attachment"=>0)
        );
    }


    public function pdfTicket($comprobante_id){        
        
        require_once (APPPATH .'libraries/Numletras.php');
        /*datos de la empresa*/

        //$comprobante_id =  32;

        $this->db->from("empresas")
                 ->where("id",1);
        $query = $this->db->get();
        $rsEmpresa = $query->row();
        /*obtenemos todos los datos del documento*/
        $this->db->from("comprobantes as com")
                 ->join("tipo_documentos as tdoc", "com.tipo_documento_id=tdoc.id")
                 ->join("clientes as cli", "com.cliente_id=cli.id")
                 ->join("transportistas as trans", "com.transportista_id=trans.transp_id")
                 ->join("monedas as tmon", "com.moneda_id=tmon.id")
                 ->join("tipo_pagos as tpag", "com.tipo_pago_id=tpag.id")
                 ->where("com.id",$comprobante_id);         
        $query = $this->db->get();
        $rsComprobante = $query->row();

        //var_dump($rsComprobante);exit;

        /*obtenemos el detalle del documento*/
        $this->db->select('i.*,p.*,m.medida_codigo_unidad')
                 ->from("items as i")
                 ->join("productos as p","p.prod_id=i.producto_id", 'left')
                 ->join("medida as m","m.medida_id=i.unidad_id")
                 ->where("i.comprobante_id", $comprobante_id)
                 ->order_by("i.id","ASC");
        $query = $this->db->get();
        $rsDetalle = $query->result();

        //PAGO MONTO ALEXANDER FERNANDEZ 14-10-2020
        $rsPagoMonto =  $this->db->from('comprobante_pagos cmp')
                                ->join('tipo_pagos tpg','cmp.tipo_pago_id =  tpg.id')
                                ->where('cmp.comprobante_id', $comprobante_id)
                                ->get()
                                ->result();
   
        $rsComprobante->fecha_de_emision = (new DateTime($rsComprobante->fecha_de_emision))->format("d/m/Y");
        $rsComprobante->fecha_de_vencimiento = ($rsComprobante->fecha_de_vencimiento!='')?(new DateTime($rsComprobante->fecha_de_vencimiento))->format("d/m/Y"):'';
        /*documento relacionado*/
        $rsRelacionado = $this->db->from("comprobantes")
                                  ->where("id", $rsComprobante->com_adjunto_id)
                                  ->get()
                                  ->row();

        //convetimos el total en texto
        $num = new Numletras();
        $totalVenta = explode(".",$rsComprobante->total_a_pagar);
        $totalLetras = $num->num2letras($totalVenta[0]);
        $totalLetras = $totalLetras.' con '.$totalVenta[1].'/100 '.$rsComprobante->moneda;
        $rsComprobante->total_letras = $totalLetras; 

        /*anticipos del documento*/
        $this->db->from("comprobante_anticipo as coma")
                 ->join("comprobantes as com", "coma.anticipo_id=com.id")
                 ->where("coma.comprobante_id", $comprobante_id);
        $query = $this->db->get();
        $rsAnticipos = $query->result(); 
        $totalAnticipo = 0;
        foreach($rsAnticipos as $item)
        {
           $totalAnticipo += $item->total_a_pagar; 
        }
        $data['comprobante'] = $this->comprobantes_model->select($comprobante_id);

        $guia_id = $data['comprobante']['numero_guia_remision'];
        $datos_guia = $this->db->from('guias')
                               ->where('id',$guia_id)
                               ->get()->row();
        $rsComprobante->total_anticipos = $totalAnticipo;

        //ALEXANDER FERNANDEZ DE LA CRUZ 31-10-2020
        $rs_almacen_principal = $this->almacenes_model->select($this->session->userdata('almacen_id'));//datos del almacen principal

        //$certificado =  $url_content;
        //print_r(RUTA_API."/SITIFACSUNAT/index.php/Sunat/getFirmaDigital/".$rsEmpresa->ruc.'/'.$rsComprobante->codigo.'/'.$rsComprobante->serie.'/'.$rsComprobante->numero);exit();
       // $certificado = $this->ObtenerCertificado($rsEmpresa->ruc,$rsComprobante->codigo,$rsComprobante->serie,$rsComprobante->numero);    
        $certificado = $rsComprobante->firma_sunat;
        $configuracion = $this->db->from('comprobantes_ventas')->get()->row();
        $data = [
                    "comprobante"   => $rsComprobante,
                    "relacionado"   => $rsRelacionado,
                    "empresa"       => $rsEmpresa,
                    "detalles"      => $rsDetalle,
                    "pagoMonto"     =>  $rsPagoMonto,
                    "anticipos"     => $rsAnticipos,
                    "rutaqr"        => $this->GetImgQr($data['comprobante']),
                    "certificado"   => $certificado,
                    "configuracion" => $configuracion,
                    "guia"          => $datos_guia,
                    "almacen_principal" => $rs_almacen_principal
                ];
                //var_dump($data);EXIT;
        $html = $this->load->view("templates/ticket_pdf.php",$data,true);
        //var_dump($data);exit;
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper(array(0,0,85,440), 'portrait');
        $this->pdf->render();
        $tipo_documento_descargar = str_replace(" ","_",$rsComprobante->tipo_documento);
        $this->pdf->stream("$tipo_documento_descargar-$rsComprobante->serie-$rsComprobante->numero.pdf",
            array("Attachment"=>0)
        );        
    }


    public function pdfTicket_nv($idNota){
        $rsNota = $this->db->from("nota_pedido as np")
                           ->join("monedas as mon", "np.notap_moneda_id=mon.id")
                           ->join("tipo_pagos as tpg", "np.notap_tipopago_id=tpg.id")
                           ->join('transportistas tra','np.notap_transportista_id = tra.transp_id')
                           ->where("notap_id", $idNota)
                           ->get()
                           ->row();
                           //var_dump($rsNota);exit;

        /*formateamos fecha*/
        $rsNota->notap_fecha = (new DateTime($rsNota->notap_detalle))->format("d/m/Y");                   
        $rsDetalles =  $this->db->from("nota_pedido_detalle as f")
                                ->join('productos as p','p.prod_id=f.notapd_producto_id') 
                           
                           ->where("f.notapd_notap_id", $idNota)
                           ->get()
                           ->result();

        $rsNota->detalles = $rsDetalles;  

        //PAGO MONTO ALEXANDER FERNANDEZ 14-10-2020
        $rsPagoMonto =  $this->db->from('nota_pagos cmp')
                                ->join('tipo_pagos tpg','cmp.tipo_pago_id =  tpg.id')
                                ->where('cmp.nota_id', $idNota)
                                ->get()
                                ->result();                                   

        $rsEmpresa = $this->db->from("empresas")
                              ->where("id", 1)
                              ->get()
                              ->row();  

        $rsCliente =  $this->db->from("clientes")
                              ->where("id", $rsNota->notap_cliente_id)
                              ->get()
                              ->row();                      
                      //var_dump($rsCliente)

        //ALEXANDER FERNANDEZ DE LA CRUZ 31-10-2020
        $rs_almacen_principal = $this->almacenes_model->select($this->session->userdata('almacen_id'));//datos del almacen principal                              
 
        $data = [
                    "empresa" => $rsEmpresa,
                    "nota"    => $rsNota,
                    "pagoMonto" =>  $rsPagoMonto,
                    "cliente" => $rsCliente,
                    "almacen_principal" => $rs_almacen_principal
                ];
        $html = $this->load->view("templates/nota_ticket.php",$data,true); 
        
//        $this->load->library('pdfgenerator');
//        $filename = 'comprobante_pago';
//        $this->pdfgenerator->generate($html, $filename, true,'A4','portrait');
        
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper(array(0,0,85,440), 'portrait');
        $this->pdf->render();
        $this->pdf->stream("N.Venta.NP-$idNota.pdf",
            array("Attachment"=>0)
        );
    }


     public function pdfTicket_hc($idHistoria){
          $rsHistoria = $this->db->select('his.his_id his_id,his.his_correlativo his_correlativo,DATE_FORMAT(his.his_fecha, "%d-%m-%Y %h:%i:%s") his_fecha,his.his_ini_peso,his.his_ini_talla,his.his_ini_presion_arterial,his.his_ini_temperatura,his.his_ini_otros,his.his_enfermedad_actual,his.his_motivo,his.his_diagnostico,his.his_tratamiento,his.his_recomendacion,DATE_FORMAT(his.his_fecha_cita, "%d-%m-%Y %h:%i:%s") his_fecha_cita,pac.id paciente_id,pac.razon_social pac_razon_social,prof.prof_nombre prof_nombre,prof.prof_firma prof_firma,esp.esp_descripcion,CONCAT(emp.nombre," ",emp.apellido_paterno) empleado,hie.hie_descripcion estado',FALSE)
                            ->from('historias his')
                            ->join('pacientes pac','pac.id = his.his_paciente_id')
                            ->join('profesionales prof','prof.prof_id =  his_profesional_id')
                            ->join('especialidades esp','esp.esp_id = prof_especialidad_id')
                            ->join('empleados emp','emp.id = his.his_empleado_insert')
                            ->join('historia_estados hie','hie.hie_id = his.his_historia_estado_id')
                            ->where("his.his_id", $idHistoria)
                            ->where("his_estado",ST_ACTIVO)
                            ->get()
                            ->row();
                            //var_dump($rsHistoria);Exit;

        /*formateamos fecha*/
        $rsHistoria->his_fecha = (new DateTime($rsHistoria->his_fecha))->format("d/m/Y h:i:s");                   
        $rsDetalles =  $this->db->from("historia_detalles as f")
                                ->join('productos as p','p.prod_id=f.hid_producto_id','left')                            
                                ->where("f.hid_his_id", $idHistoria)
                                ->get()
                                ->result();

        //HEIGHT TICKET 21-09-2020
        $countItems = count($rsDetalles);
        $ticketHeight =  $countItems*22;
        $ticketHeight = ($ticketHeight > 440) ? $ticketHeight : 440;        


        $rsHistoria->detalles = $rsDetalles;                                     

        $rsEmpresa = $this->db->from("empresas")
                              ->where("id", 1)
                              ->get()
                              ->row();  

        $rsPaciente =  $this->db->from("pacientes")
                                ->where("id", $rsHistoria->paciente_id)
                                ->get()
                                ->row();                                            

        //ALEXANDER FERNANDEZ DE LA CRUZ 30-10-2020
        $rs_almacen_principal = $this->almacenes_model->select($this->session->userdata('almacen_id'));//datos del almacen principal
        $data = [
                    "empresa" => $rsEmpresa,
                    "historia" => $rsHistoria,                    
                    "paciente" => $rsPaciente,
                    "almacen_principal" => $rs_almacen_principal
                ];
        $html = $this->load->view("templates/historia_ticket.php",$data,true); 
        
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper(array(0,0,85,$ticketHeight), 'portrait');
        $this->pdf->render();
        $this->pdf->stream("N.Historia.H-$idHistoria.pdf",
            array("Attachment"=>0)
        );
    }



    public function GetImgQr($dataComprobante)  {
        $textoQR = '';
        $textoQR .= $dataComprobante['empresa_ruc']."|";//RUC EMPRESA
        $resultComprobante = $this->db->from("tipo_documentos")
                                    ->where("id",$dataComprobante['tipo_documento_id'])
                                    ->get()
                                    ->row();
        
        $textoQR .= "{$resultComprobante->codigo}|";//TIPO DE DOCUMENTO 
        $textoQR .= $dataComprobante['serie']."|";//SERIE
        $textoQR .= $dataComprobante['numero']."|";//NUMERO
        $textoQR .= $dataComprobante['total_igv']."|";//MTO TOTAL IGV
        $textoQR .= $dataComprobante['total_a_pagar']."|";//MTO TOTAL DEL COMPROBANTE
        //$fechaEmision = (new DateTime($rsComprobante->fecha_de_emision))->format('d-m-Y');
        $textoQR .= $dataComprobante['fecha_de_emision']."|";//FECHA DE EMISION 
        //tipo de cliente
        $rsTipoCliente = $this->db->from("tipo_clientes")
                                  ->where("id", $dataComprobante['tipo_cliente_id'])
                                  ->get()
                                  ->row();
        
     
        $textoQR .= "{$rsTipoCliente->codigo}|";//TIPO DE DOCUMENTO ADQUIRENTE 
        $textoQR .= $dataComprobante['cliente_ruc']."|";//NUMERO DE DOCUMENTO ADQUIRENTE 
        $qrCode = new QrCode($textoQR);
        $qrCode->setSize(200);
        $qrCode->setWriterByName('png');
        $nombreQR = $dataComprobante['tipo_documento_id'].'-'.$dataComprobante['serie'].'-'.$dataComprobante['numero'];
        unlink(FCPATH."images/qr/{$nombreQR}.png");
        $qrCode->writeFile(FCPATH."images/qr/{$nombreQR}.png");

        $ruta= FCPATH."images/qr/{$nombreQR}.png";
        return $ruta;
    }
    public function ObtenerCertificado($rucEmpresa,$tipodocCodigo,$comprobanteSerie,$comprobanteSNumero) {
        /*obetenemos el certificado*/
        $archivoXML = "{$rucEmpresa}-{$tipodocCodigo}-{$comprobanteSerie}-{$comprobanteSNumero}.xml";
        $rutaFirma = DISCO.':\xampp\htdocs'.CARPETA."/".SFS."/sunat_archivos/sfs/PARSE/{$archivoXML}";
        $certificado = '';
        //calidamos que exista fichero 
        if(file_exists($rutaFirma))
        {
            $library = new SimpleXMLElement($rutaFirma, null, true);
            $ns = $library->getDocNamespaces();
            $ext1 = $library->children($ns['ext']);
            $ext2 = $ext1->children($ns['ext']);
            $ext3 = $ext2->children($ns['ext']);
            $ds1 = $ext3->children($ns['ds']);
            $ds2 = $ds1->children($ns['ds']);
            $certificado = $ds2->SignedInfo->Reference->DigestValue; 

        }
        return $certificado;
    }
}




