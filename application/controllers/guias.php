<?PHP

use PhpOffice\PhpSpreadsheet\Spreadsheet;    
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;


if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Guias extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();        
        date_default_timezone_set('America/Lima');
        $this->load->model('accesos_model');
        $this->load->model('empresas_model');
        $this->load->model('clientes_model');
        $this->load->model('tipo_clientes_model');
        $this->load->model('guias_model'); 
        $this->load->model('productos_model'); 
        $this->load->model('ubigeo_inei_model');
        $this->load->helper('ayuda');  
        $this->load->model('medida_model'); 
        //$this->load->library('pdf');     

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
        $this->load->view('guias/basic_index');
        $this->load->view('templates/footer');      
    }
    public function nuevo()
    {
        $data = array();
        $data['empresas'] = $this->empresas_model->select();
        $data['motivos'] = $this->guias_model->getMotivosTraslado();
        $data['modalidades'] = $this->guias_model->getModalidadTraslado();
        $data['ubigeo_inei'] = $this->ubigeo_inei_model->select();
        $data['tipo_clientes'] = $this->tipo_clientes_model->select();
        $data['medida'] = $this->medida_model->select();
        $this->accesos_model->menuGeneral();
        $this->load->view('guias/nuevo', $data);
        $this->load->view('templates/footer');
    }
    public function editar($idGuia)
    {
        $data['empresas'] = $this->empresas_model->select();
        $data['motivos'] = $this->guias_model->getMotivosTraslado();
        $data['modalidades'] = $this->guias_model->getModalidadTraslado();
        $data['ubigeo_inei'] = $this->ubigeo_inei_model->select();
        $data['tipo_clientes'] = $this->tipo_clientes_model->select();
        $data['guia'] = $this->guias_model->select($idGuia);
        $data['medida'] = $this->medida_model->select();
        $this->accesos_model->menuGeneral();       
        $this->load->view('guias/nuevo', $data);
        $this->load->view('templates/footer');
    }
    public function selectUltimoReg() {        
        $rowGuia = $this->guias_model->selecMaximoNumero($this->uri->segment(3), $this->uri->segment(4));        
        $row['numero'] = $rowGuia['numero'];        
        echo json_encode($row);
    }
    public function guardarGuia()
    {
        //print_r($_POST['destinatario_documento']);exit();
        $error = array();

        if($_POST['motivo'] == '')
        {
            $error['motivo'] = 'falta ingresar motivo';
        } 
        if($_POST['fecha'] == '')
        {
            $error['fecha'] = 'falta ingresar fecha';
        }  
        if($_POST['destinatario_documento'] == '')
        {
            $error['destinatario_documento'] = 'falta ingresar tipon cliente destinatario';
        } 
        if($_POST['destinatario_ruc'] == '')
        {
            $error['destinatario_ruc'] = 'falta ingresar ruc destinatario';
        } 
        if($_POST['destinatario_razon_social'] == '')
        {
            $error['destinatario_razon_social'] = 'falta ingresar razon destinatario';
        }
        if($_POST['conductor_documento'] == '')
        {
            $error['conductor_documento'] = 'falta ingresar tipon conductor destinatario';
        } 
        if($_POST['transporte_documento'] == '')
        {
            $error['transporte_documento'] = 'falta ingresar tipon transporte destinatario';
        }
        if($_POST['partida_direccion'] == '')
        {
            $error['partida_direccion'] = 'falta ingresar punto partida';
        } 
        if($_POST['llegada_direccion'] == '')
        {
            $error['llegada_direccion'] = 'falta ingresar punto llegada';
        } 
        if($_POST['serie'] == '')
        {
            $error['serie'] = 'falta ingresar serie';
        } 
        if($_POST['numero'] == '')
        {
            $error['numero'] = 'falta ingresar Numero';
        }                                                
        
        if(count($error) > 0)
        {
            $data = ['status'=>STATUS_FAIL,'tipo'=>1, 'errores'=>$error];
            sendJsonData($data);
            exit();
        }  


        $descripcion = $_POST['descripcion'];
        $medida = $_POST['medida'];        
        //verificamos que haya productos
        $tieneProductos = false;
        $msg = 'no hay productos agregados.';

        $i = 0;
        foreach($_POST['item_id'] as $value)
        {
            if($value != '')
            {
                if($value == 0){//PRODUCTO SIN STOCK
                    if($descripcion[$i] == ''){
                       $tieneProductos = false;
                       $msg = 'Ingrese descripción del producto.';break;
                    } elseif ($medida[$i] == '') {                        
                        $tieneProductos = false;
                        $msg = 'Seleccione una unidad de medida.';
                    } else{
                        $tieneProductos = true;
                    }  
                }else{//PRODUCTO CON STOCK
                    $tieneProductos = true;    
                }            
            }else{
                $tieneProductos = false;
                $msg = 'Ingrese items';
                break;
            }
            $i++;
        }
        if(!$tieneProductos)
        {
            sendJsonData(['status'=>STATUS_FAIL,'tipo'=>2,'msg'=>$msg]);
            exit();            
        }

        /////CONSULTA STOCK PRODUCTO ////////////////// 
        if($_POST['numero_factura']==''){
                $productosId = $_POST['item_id'];
                $cantidades = $_POST['cantidad'];
                
                $i = 0;
                foreach ($productosId as $item) {
                    
                    $this->db->where('prod_id',$productosId[$i]);
                    $dato_prod = $this->db->get('productos')->row();

                    $prod_stock = $this->productos_model->getStockProductos($productosId[$i],$this->session->userdata("almacen_id"));

                    if($dato_prod->prod_tipo==1){
                         if($cantidades[$i]==0 OR $cantidades[$i]>$prod_stock){
                           sendJsonData(['status'=>STATUS_FAIL, 'msg'=>'No hay stock disponible de '.$dato_prod->prod_nombre]);
                            exit();  
                          }   
                    }
                                   

                    $i++;                   
                }   
         }
                               
        ///////////////////////////////////////////////
        //guardamos la guia
        $result = $this->guias_model->guardarGuia();
        
        if($result > 0)
        {
            sendJsonData(['status'=>STATUS_OK]);
            exit();
        }   

    }

    public function eliminar($idGuia)
    {
        $result = $this->guias_model->eliminar($idGuia);
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
    public function buscarComprobanteGuia()
    {
        $rsDatos = $this->guias_model->buscarComprobanteGuia();
        sendJsonData($rsDatos);        
    }
    public function getMainList()
    {
        $rsDatos = $this->guias_model->getMainList();
        sendJsonData($rsDatos);
    }
    public function getMainListDetail()
    {
        $rsDatos = $this->guias_model->getMainListDetail();
        sendJsonData($rsDatos);        
    }
    public function descargarPdf($idGuia)
    {
        $rsGuia = $this->db->select("gmts.descripcion modalidad,gmt.*,guia.*",FALSE)
                            ->from("guias as guia")
                           ->join("guia_motivos_traslado as gmt", "guia.motivo_traslado=gmt.id")
                           ->join("guia_modalidad_traslado as gmts", "guia.modalidad_traslado=gmts.id")
                           ->where("guia.id", $idGuia)
                           ->get()
                           ->row();
        /*formateamos fecha*/
        $rsGuia->fecha_inicio_traslado = (new DateTime($rsGuia->fecha_inicio_traslado))->format('d/m/Y');                     

        $rsDetalle = $this->db->from("guia_detalles as guiad")
                              ->join("productos as prod", "prod.prod_id=guiad.producto_id","left")
                              ->join("medida med", "guiad.medida_id=med.medida_id")
                              ->where("guia_id", $idGuia)
                              ->get()
                              ->result();

        $rsGuia->detalles = $rsDetalle;                                     
                    
                                              
        $rsEmpresa = $this->db->from("empresas")
                              ->where("id", 1)
                              ->get()
                              ->row();

        $data = [
                    "empresa"   => $rsEmpresa,
                    "guia"    => $rsGuia,
                ];  

        $html = $this->load->view("templates/guia.php",$data,true); 
        // Cargamos la librería
        $this->load->library('pdfgenerator');
        // definamos un nombre para el archivo. No es necesario agregar la extension .pdf
        $filename = 'comprobante_pago';
        // generamos el PDF. Pasemos por encima de la configuración general y definamos otro tipo de papel
        $this->pdfgenerator->generate($html, $filename, true,'A4','portrait');                                    
    }

    public function descargarPdf_ticket($idGuia){
     
      //var_dump($this->uri->segment(3));exit;
      $rsGuia = $this->db->select("gmts.descripcion modalidad,gmt.*,guia.*",FALSE)
                            ->from("guias as guia")
                           ->join("guia_motivos_traslado as gmt", "guia.motivo_traslado=gmt.id")
                           ->join("guia_modalidad_traslado as gmts", "guia.modalidad_traslado=gmts.id")
                           ->where("guia.id", $idGuia)
                           ->get()
                           ->row();

        /*formateamos fecha*/
        $rsGuia->fecha_inicio_traslado = (new DateTime($rsGuia->fecha_inicio_traslado))->format('d/m/Y');

         $rsDetalle = $this->db->from("guia_detalles as guiad")
                              ->join("productos as prod", "prod.prod_id=guiad.producto_id","left")
                              ->join("medida med", "guiad.medida_id=med.medida_id")
                              ->where("guia_id", $idGuia)
                              ->get()
                              ->result();

        $countItems = count($rsDetalle);
        $ticketHeight =  $countItems*23;
        $ticketHeight = ($ticketHeight > 440) ? $ticketHeight : 440;                                                        

        $rsGuia->detalles = $rsDetalle;                                     

        $rsEmpresa = $this->db->from("empresas")
                              ->where("id", 1)
                              ->get()
                              ->row();

        $rsCliente =  $this->db->from("clientes")
                              ->where("ruc",$rsGuia->destinatario_ruc)
                              ->get()
                              ->row();                      
                      //var_dump($rsCliente);exit;
 
        $data = [
                    "empresa" => $rsEmpresa,
                    "guia"    => $rsGuia,
                    "cliente" => $rsCliente,
                ];

        $html = $this->load->view("templates/guia_ticket.php",$data,true);

        $this->load->library('pdf'); 
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper(array(0,0,85,$ticketHeight),'portrait');
        $this->pdf->render();
        $this->pdf->stream("Proforma.NP-$idProforma.pdf",
         array("Attachment"=>0)
        );
    }


    public function decargarPdf_matriz($idGuia)
    {
        $rsGuia = $this->db->from("guias as guia")
                           ->join("guia_motivos_traslado as gmt", "guia.motivo_traslado=gmt.id")
                           ->where("guia.id", $idGuia)
                           ->get()
                           ->row();
        /*formateamos fecha*/
        $rsGuia->fecha_inicio_traslado = (new DateTime($rsGuia->fecha_inicio_traslado))->format('d/m/Y');                     

        $rsDetalle = $this->db->from("guia_detalles as guiad")
                              ->join("productos as prod", "prod.prod_id=guiad.producto_id")
                              ->join("medida med", "prod.prod_medida_id=med.medida_id")
                              ->where("guia_id", $idGuia)
                              ->get()
                              ->result();

        $rsGuia->detalles = $rsDetalle;                                     
                    
                                              
        $rsEmpresa = $this->db->from("empresas")
                              ->where("id", 1)
                              ->get()
                              ->row();

        $data = [
                    "empresa"   => $rsEmpresa,
                    "guia"    => $rsGuia,
                ];  

        $html = $this->load->view("templates/guia_matriz.php",$data,true);                   
        /*escribimos archivo*/
        $archivo = 'GUIA-'.$rsGuia->correlativo;
        $rutaArchivoHtml = FCPATH.'files\pdf\\'.$archivo.'.html';
        $rutaArchivoPdf = FCPATH.'files\pdf\\'.$archivo.'.pdf';
        $file = fopen($rutaArchivoHtml,'w');
        fwrite($file, $html);
        fclose($file);
        /*convertimos el html en pdf*/
        exec('"C:\Program Files\wkhtmltopdf\bin\wkhtmltopdf" '.$rutaArchivoHtml.' '.$rutaArchivoPdf);
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="'.$archivo.'.pdf"');
        readfile($rutaArchivoPdf);
        /*aliminamos archivos creados html, pdf*/
        unlink($rutaArchivoHtml);
        unlink($rutaArchivoPdf);                        
        
        
    }

     

    public function buscadorCliente() {
        $cliente = $this->input->get('term');
        echo json_encode($this->clientes_model->selectAutocomplete($cliente));
    }

    public function ExportarExcel($idproveedor='',$correlatio='',$fecha='',$documento='') {

        if($this->uri->segment(3)!='0') {
            $this->db->where('c.comp_proveedor_id', $this->uri->segment(3));
        }
        if($this->uri->segment(4)!='0') {
            $this->db->where('c.comp_doc_serie', $this->uri->segment(4));
        }
        if($this->uri->segment(5)!='0') {
            $this->db->where('c.comp_doc_fecha', $this->uri->segment(5));
        }
        if($this->uri->segment(6)!='0') {
            $this->db->where('c.comp_doc_documento', $this->uri->segment(6));
        }

        $this->db->where('c.comp_estado',ST_ACTIVO);

        $result = $this->db->from("compras c")
                 ->join("proveedores p","c.comp_proveedor_id=p.prov_id")
                 ->join("monedas m","c.comp_moneda_id=m.id")
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
                ->setCellValue('B1', 'PROVEEDOR')
                ->setCellValue('C1', 'DOCUMENTO')
                ->setCellValue('D1', 'FECHA')
                ->setCellValue('E1', 'MONEDA')
                ->setCellValue('F1', 'SUBTOTAL')
                ->setCellValue('G1', 'IGV')
                ->setCellValue('H1', 'TOTAL');

        $spreadsheet->getActiveSheet()->setTitle('compras');

        foreach ($result as $value) {
            $fecha = (new DateTime($value->comp_doc_fecha))->format('d/m/Y');
            $spreadsheet->getActiveSheet()
                        ->setCellValue('A'.$i, $value->comp_id)
                        ->setCellValue('B'.$i, $value->prov_razon_social)
                        ->setCellValue('C'.$i, $value->comp_doc_documento)
                        ->setCellValue('D'.$i, $fecha)
                        ->setCellValue('E'.$i, $value->moneda)
                        ->setCellValue('F'.$i, $value->comp_doc_subtotal)
                        ->setCellValue('G'.$i, $value->comp_doc_igv)
                        ->setCellValue('H'.$i, $value->comp_doc_total);
            $i++; 
            $spreadsheet->getActiveSheet()
                        ->setCellValue('A'.$i, '')                        
                        ->setCellValue('B'.$i, 'PRODUCTO')
                        ->setCellValue('C'.$i, 'PRECIO UNITARIO')
                        ->setCellValue('D'.$i, 'SUB TOTAL')
                        ->setCellValue('E'.$i, 'CANTIDAD'); 

            $dataCompras = $this->db->from('compras_detalle cd')
                                   ->where('cd.compd_compra_id',$value->comp_id)
                                   ->get()
                                   ->result();
         
            $i++;
            foreach ($dataCompras as $val) {              
                $spreadsheet->getActiveSheet()
                        ->setCellValue('A'.$i, '')
                        ->setCellValue('B'.$i, $val->compd_descripcion)
                        ->setCellValue('C'.$i, $val->compd_precio_unitario)
                        ->setCellValue('D'.$i, $val->compd_cantidad)
                        ->setCellValue('E'.$i, $val->compd_subtotal);
                $i++;
            }

            $spreadsheet->getActiveSheet()->mergeCells('A'.$i.':H'.$i);

            $i++;
        }
        
        $spreadsheet->setActiveSheetIndex(0);         
        // Redirect output to a client's web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="data_compras.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');       
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;

    }
    public function buscador_item()
    {
        $item = $this->input->get('term');       
        echo json_encode($this->productos_model->selectAutocompleteprod($item));       
    }

    public function buscador_ruc(){
        $texto = $this->input->get("texto");
        $this->db->like("ruc",$texto);
        $this->db->where("eliminado_cliente",0);
        $res = $this->db->get("clientes")->result();
        echo json_encode($res);
    }

    public function buscador_razon(){
        $texto = $this->input->get("texto");
        $this->db->like("razon_social",$texto);
        $this->db->where("eliminado_cliente",0);
        $res = $this->db->get("clientes")->result();
        echo json_encode($res);
    }

    public function buscador_cliente_ruc(){
        $texto = $this->input->get('texto');
        $this->db->where("ruc",$texto);
        $this->db->where("eliminado_cliente",0);
        $res = $this->db->get("clientes")->row();
        echo json_encode($res);
    }

    public function buscador_cliente_razon(){
        $texto = $this->input->get('texto');
        $this->db->where("razon_social",$texto);
        $this->db->where("eliminado_cliente",0);
        $res = $this->db->get("clientes")->row();
        echo json_encode($res);
    }


    public function updateEstadoCDR(){

        $guia = $_POST['guia'];
        $certificado = json_decode($_POST['firma']);
        foreach ($certificado as $value) {
           $firma = $value;
        }

        $this->db->where('id',$guia);
        $this->db->set('estado_sunat',3);
        $this->db->set('firma_sunat',$firma);
        $this->db->update('guias');
    }



    public function getDatosXML(){    
        $guia_id = $this->input->get('guia_id');
        $empresa = $this->db->from('empresas')
                            ->get()
                            ->row();

        //////DATOS EMPRESA
        $datos['empresa']['ruc'] = $empresa->ruc;
        $datos['empresa']['user'] = $empresa->user;
        $datos['empresa']['pass'] = $empresa->pass;
        $datos['empresa']['razon_social'] = $empresa->empresa;
        $datos['empresa']['nombre_comercial'] = $empresa->nombre_comercial;
        $datos['empresa']['direccion'] = $empresa->domicilio_fiscal;
        $datos['empresa']['departamento'] = $empresa->departamento;
        $datos['empresa']['provincia'] = $empresa->provincia;
        $datos['empresa']['distrito']  = $empresa->distrito;
        $datos['empresa']['ubigeo'] = $empresa->ubigeo;
        $datos['empresa']['urbanizacion'] = $empresa->urb;        

        $guia = $this->db->select('gui.*,tde.codigo destinatario_codigo,tpc.codigo transporte_codigo,tcc.codigo conductor_codigo,gmt.codigo motivo_codigo,gmt.descripcion motivo_descripcion,gmd.codigo modalidad_codigo')
                         ->from('guias gui')
                         ->join("guia_motivos_traslado gmt", "gui.motivo_traslado = gmt.id")
                         ->join("guia_modalidad_traslado gmd", "gui.modalidad_traslado =  gmd.id")
                         ->join('tipo_clientes tde', 'gui.destinatario_documento = tde.id')                      
                         ->join('tipo_clientes tpc', 'gui.transporte_documento = tpc.id')
                         ->join('tipo_clientes tcc', 'gui.conductor_documento = tcc.id')
                         ->where('gui.id',$guia_id)
                         ->get()
                         ->row();                         

        $guiaDetalle = $this->db->from("guia_detalles as guiad")
                                ->join("productos as prod", "prod.prod_id = guiad.producto_id","left")
                                ->join("medida med", "prod.prod_medida_id = med.medida_id")
                                ->where("guia_id", $guia_id)
                                ->get()
                                ->result();

        //DATOS CABECERA
        $datos['cabecera']['tipo_documento'] = '09';
        //Transportista                         
        $datos['cabecera']['transporte_codigo'] = $guia->transporte_codigo;
        $datos['cabecera']['transporte_ruc']    = $guia->transporte_ruc;
        $datos['cabecera']['transporte_razon_social'] = $guia->transporte_razon_social;
        //Conductor
        $datos['cabecera']['conductor_codigo'] = $guia->conductor_codigo;
        $datos['cabecera']['conductor_ruc']    = $guia->conductor_ruc;
        $datos['cabecera']['conductor_razon_social'] = $guia->conductor_razon_social;
        $datos['cabecera']['vehiculo_placa']   = $guia->vehiculo_placa;

        $datos['cabecera']['motivo_codigo']      = $guia->motivo_codigo;
        $datos['cabecera']['motivo_descripcion'] = $guia->motivo_descripcion;
        $datos['cabecera']['modalidad_codigo']   = $guia->modalidad_codigo;

        $datos['cabecera']['peso_total']   = $guia->peso_total;

        $datos['cabecera']['ubigeo_partida']   = $guia->ubigeo_partida;
        $datos['cabecera']['partida_direccion']= $guia->partida_direccion;
        $datos['cabecera']['ubigeo_llegada']   = $guia->ubigeo_llegada;
        $datos['cabecera']['llegada_direccion']= $guia->llegada_direccion;


        $datos['cabecera']['guia_serie'] = $guia->guia_serie;
        $datos['cabecera']['guia_numero']= $guia->guia_numero;

        $datos['cabecera']['destinatario_codigo']= $guia->destinatario_codigo;
        $datos['cabecera']['destinatario_ruc']= $guia->destinatario_ruc;
        $datos['cabecera']['destinatario_razon_social']= $guia->destinatario_razon_social;


        ////DATOS ITEMS 
        $datos['detalle'] = [];
            foreach($guiaDetalle as $i){
                $datos_item['sunat'] = $i->prod_codigo_sunat;
                $datos_item['codigo'] = ($i->prod_codigo=='')?"000000000":$i->prod_codigo;
                $datos_item['unidad'] = $i->medida_codigo_unidad;
                $datos_item['cantidad'] = $i->cantidad;
                $datos_item['descripcion'] = $i->descripcion;

                array_push($datos['detalle'],$datos_item);
            } 
        echo json_encode($datos);
    }    

    public function getDatosAnulacion(){
        $guia_id = $this->input->get('guia_id');
        $empresa = $this->db->from('empresas')
                            ->get()
                            ->row();
        //////DATOS EMPRESA
        $datos['empresa']['ruc'] = $empresa->ruc;
        $datos['empresa']['user'] = $empresa->user;
        $datos['empresa']['pass'] = $empresa->pass;
        $datos['empresa']['razon_social'] = $empresa->empresa;
        $datos['empresa']['nombre_comercial'] = $empresa->nombre_comercial;
        $datos['empresa']['direccion'] = $empresa->domicilio_fiscal;
        $datos['empresa']['departamento'] = $empresa->departamento;
        $datos['empresa']['provincia'] = $empresa->provincia;
        $datos['empresa']['distrito'] = $empresa->distrito;
        $datos['empresa']['ubigeo'] = $empresa->ubigeo;
        $datos['empresa']['urbanizacion'] = $empresa->urb;        

        $guia = $this->db->select('gui.*,tde.codigo destinatario_codigo,tpc.codigo transporte_codigo,tcc.codigo conductor_codigo,gmt.codigo motivo_codigo,gmt.descripcion motivo_descripcion,gmd.codigo modalidad_codigo')
                         ->from('guias gui')
                         ->join("guia_motivos_traslado gmt", "gui.motivo_traslado = gmt.id")
                         ->join("guia_modalidad_traslado gmd", "gui.modalidad_traslado =  gmd.id")
                         ->join('tipo_clientes tde', 'gui.destinatario_documento = tde.id')
                         ->join('tipo_clientes tpc', 'gui.transporte_documento = tpc.id')
                         ->join('tipo_clientes tcc', 'gui.conductor_documento = tcc.id')                         
                         ->where('gui.id',$guia_id)
                         ->get()
                         ->row();                         

        $guiaDetalle = $this->db->from("guia_detalles as guiad")
                                ->join("productos as prod", "prod.prod_id = guiad.producto_id","left")
                                ->join("medida med", "prod.prod_medida_id = med.medida_id")
                                ->where("guia_id", $guia_id)
                                ->get()
                                ->result();

        //DATOS CABECERA
        $datos['cabecera']['tipo_documento'] = '09';
        //Transportista                         
        $datos['cabecera']['transporte_codigo'] = $guia->transporte_codigo;
        $datos['cabecera']['transporte_ruc']    = $guia->transporte_ruc;
        $datos['cabecera']['transporte_razon_social'] = $guia->transporte_razon_social;
        //Conductor
        $datos['cabecera']['conductor_codigo'] = $guia->conductor_codigo;
        $datos['cabecera']['conductor_ruc']    = $guia->conductor_ruc;
        $datos['cabecera']['conductor_razon_social'] = $guia->conductor_razon_social;
        $datos['cabecera']['vehiculo_placa']   = $guia->vehiculo_placa;

        $datos['cabecera']['motivo_codigo']      = $guia->motivo_codigo;
        $datos['cabecera']['motivo_descripcion'] = $guia->motivo_descripcion;
        $datos['cabecera']['modalidad_codigo']   = $guia->modalidad_codigo;

        $datos['cabecera']['peso_total']   = $guia->peso_total;

        $datos['cabecera']['ubigeo_partida']   = $guia->ubigeo_partida;
        $datos['cabecera']['partida_direccion']= $guia->partida_direccion;
        $datos['cabecera']['ubigeo_llegada']   = $guia->ubigeo_llegada;
        $datos['cabecera']['llegada_direccion']= $guia->llegada_direccion;


        $datos['cabecera']['guia_serie'] = $guia->guia_serie;
        $datos['cabecera']['guia_numero']= $guia->guia_numero;

        $datos['cabecera']['destinatario_codigo']= $guia->destinatario_codigo;
        $datos['cabecera']['destinatario_ruc']= $guia->destinatario_ruc;
        $datos['cabecera']['destinatario_razon_social']= $guia->destinatario_razon_social;


        ////DATOS ITEMS 
        $datos['detalle'] = [];
            foreach($guiaDetalle as $i){
                $datos_item['sunat'] = $i->prod_codigo_sunat;
                $datos_item['codigo'] = ($i->prod_codigo=='')?"000000000":$i->prod_codigo;
                $datos_item['unidad'] = $i->medida_codigo_unidad;
                $datos_item['cantidad'] = $i->cantidad;
                $datos_item['descripcion'] = $i->descripcion;

                array_push($datos['detalle'],$datos_item);
            }
        
        echo json_encode($datos);
    }


    public function buscar_ubigeo(){
        $texto = $this->input->get('texto');
        $this->db->like('ubigeo',$texto,'after');
        $this->db->or_like('localidad',$texto);
        $this->db->limit(15);
        $ps = $this->db->get('ubigeo_inei')->result();
        echo json_encode($ps);

    }

    public function seleccionar_ubigeo(){
        $ps_cod = $this->input->get('cod');
        $this->db->where('ubigeo',$ps_cod);
        $ps = $this->db->get('ubigeo_inei')->row();
        echo json_encode($ps);
    }
}