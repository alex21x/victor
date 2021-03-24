<?PHP

use PhpOffice\PhpSpreadsheet\Spreadsheet;    
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;


if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Compras extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();        
        date_default_timezone_set('America/Lima');
        $this->load->model('accesos_model');
        $this->load->model('monedas_model'); 
        $this->load->model('empresas_model');
        $this->load->model('tipo_igv_model');
        $this->load->model('tipo_items_model');
        $this->load->model('compras_model'); 
        $this->load->model('tipo_documentos_model');
        $this->load->model('almacenes_model');
        $this->load->model('productos_model');
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

        $data['almacenes'] = $this->almacenes_model->select();
        $this->load->view('compras/basic_index',$data);
        $this->load->view('templates/footer');      
    }
    public function nuevo()
    {
        $data = array();
        $data['tipo_documentos'] = $this->tipo_documentos_model->select();
        $data['monedas'] = $this->monedas_model->select();
        $data['empresas'] = $this->empresas_model->select();
        $data['tipo_igv'] = $this->tipo_igv_model->select('', '', '', 0);
        $data['tipo_item'] = $this->tipo_items_model->select();
        $data['consecutivo'] = $this->compras_model->maximoConsecutivo();
        $data['almacenes'] = $this->almacenes_model->select();
        $this->accesos_model->menuGeneral();
        $this->load->view('compras/nuevo', $data);
        $this->load->view('templates/footer');
    }
    public function editar($idCompra)
    {
        $data['compra'] = $this->compras_model->select($idCompra);
        $data['tipo_documentos'] = $this->tipo_documentos_model->select();
        $data['monedas'] = $this->monedas_model->select();
        $data['empresas'] = $this->empresas_model->select();
        $data['tipo_igv'] = $this->tipo_igv_model->select('', '', '', 0);
        $data['tipo_item'] = $this->tipo_items_model->select(); 
        $data['almacenes'] = $this->almacenes_model->select();
        $this->accesos_model->menuGeneral();       
        $this->load->view('compras/nuevo', $data);
        $this->load->view('templates/footer');
    }
    public function guardarCompra()
    {

        if($this->session->userdata("almacen_id")==''){
                redirect(base_url());     
        }
        
        $error = array();
        if($_POST['fecha'] == '')
        {
            $error['fecha'] = 'falta ingresar fecha';
        }
        /*if($_POST['proveedor_id'] == '')
        {
            $error['proveedor_id'] = 'falta ingresar proveedor';
        }
        if($_POST['serie'] == '')
        {
            $error['serie'] = 'falta ingresar serie';
        }
        if($_POST['numero'] == '')
        {
            $error['numero'] = 'falta ingresar numero';
        }*/
        if($_POST["moneda_id"] == '')
        {
            $error['moneda_id'] = 'falta ingresar moneda';
        }
        if($_POST["tipo_ingreso"] == 'Movimiento')
        {
            if($_POST["almacen_mov"]==""){
                $error['almacen_mov'] = 'falta seleccionar almacen';
                sendJsonData(['status'=>STATUS_FAIL, 'msg'=>'Falta seleccionar almacen']);
                exit();
            }else{
                $idproduc = $_POST['item_id'];
                $cantidad = $_POST['cantidad'];
                $descripcion = $_POST['descripcion'];
                $i = 0;
                foreach ($idproduc as $item) {
                    
                    $this->db->where('prod_id',$idproduc[$i]);
                    $dato_prod = $this->db->get('productos')->row();

                     
                     $prod_stock = $this->productos_model->getStockProductos($idproduc[$i],$_POST["almacen_mov"]);

                     if($_POST['compraId']==''){
                        if($dato_prod->prod_tipo==1){
                             if($cantidad[$i]==0 OR $cantidad[$i]>$prod_stock){
                               sendJsonData(['status'=>STATUS_FAIL, 'msg'=>'No hay stock disponible de '.$dato_prod->prod_nombre]);

                                exit();  
                              }   
                        }
                    }else{

                        $this->db->where('compd_compra_id',$_POST['compraId']);
                        $this->db->where('compd_producto_id',$idproduc[$i]);
                        $dato_producto = $this->db->get('compras_detalle')->row();

                        if($cantidad[$i]==0 OR $cantidad[$i]>($prod_stock+$dato_producto->compd_cantidad)){
                            sendJsonData(['status'=>STATUS_FAIL, 'msg'=>'No hay stock disponible de '.$descripcion[$i]]);
                           exit();  
                        } 

                    }

                    
                                   

                    $i++;                   
                } 
            }
            
        }
    
        
        if(count($error) > 0)
        {
            $data = ['status'=>STATUS_FAIL,'tipo'=>1, 'errores'=>$error];
            sendJsonData($data);
            exit();
        }    

        if(count($_POST['descripcion'])==0)
        {
            $data = ['status'=>STATUS_FAIL, 'tipo'=>2];
            sendJsonData($data);
            exit();
        }

        //guardamos la compra
        $result = $this->compras_model->guardarCompra();
        
        if($result > 0)
        {
            sendJsonData(['status'=>STATUS_OK]);
            exit();
        }   

    }

    public function eliminar($idCompra)
    {
        $result = $this->compras_model->eliminar($idCompra);
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
        $rsDatos = $this->compras_model->getMainList();
        sendJsonData($rsDatos);
    }
    public function getMainListDetail()
    {
        $rsDatos = $this->compras_model->getMainListDetail();
        sendJsonData($rsDatos);        
    }
    public function decargarPdf($idCompra)
    {
        $rsCompra = $this->db->from("compras as comp")
                             ->join("monedas as mon", "comp.comp_moneda_id=mon.id")
                             ->join("proveedores as prov", "comp.comp_proveedor_id=prov.prov_id","left")
                             ->where("comp_id", $idCompra)
                             ->get()
                             ->row();
        /*formateamos fecha*/
        $rsCompra->comp_doc_fecha = (new DateTime($rsCompra->comp_doc_fecha))->format('d/m/Y');                     

        $rsDetalles =  $this->db->from("compras_detalle")
                                ->where("compd_compra_id", $idCompra)
                                ->get()
                                ->result();

        $rsCompra->detalles = $rsDetalles;  

        $this->db->from("empresas")
                 ->where("id",1);
        $query = $this->db->get();
        $rsEmpresa = $query->row();                                   
                    
                                               
        $data = [
                    "compra"    => $rsCompra,
                    "empresa" => $rsEmpresa
                ];                   
        $html = $this->load->view("templates/compra.php",$data,true);                   
        /*escribimos archivo*/
        $archivo = 'COMPRA-'.$rsCompra->comp_correlativo;
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
    public function buscadorProveedor() {
        $proveedor = $this->input->get('term');
        echo json_encode($this->compras_model->selectAutocomplete($proveedor));
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

        $result = $this->db->select('c.*,p.*,m.*,td.tipo_documento')
                 ->from("compras c")
                 ->join("proveedores p","c.comp_proveedor_id=p.prov_id")
                 ->join("monedas m","c.comp_moneda_id=m.id")
                 ->join("tipo_documentos td","td.id=c.comp_tipo_documento")
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
                ->setCellValue('A1', 'N°')
                ->setCellValue('B1', 'PROVEEDOR')
                ->setCellValue('C1', 'RUC')
                ->setCellValue('D1', 'TIP. DOCUMENTO')
                ->setCellValue('E1', 'SERIE')
                ->setCellValue('F1', 'NUMERO')
                ->setCellValue('G1', 'FECHA')
                ->setCellValue('H1', 'MONEDA')
                ->setCellValue('I1', 'SUBTOTAL')
                ->setCellValue('J1', 'IGV')
                ->setCellValue('K1', 'TOTAL');

        $spreadsheet->getActiveSheet()->setTitle('compras');

        foreach ($result as $value) {
            $fecha = (new DateTime($value->comp_doc_fecha))->format('d/m/Y');
            $spreadsheet->getActiveSheet()
                        ->setCellValue('A'.$i, $value->comp_correlativo)
                        ->setCellValue('B'.$i, $value->prov_razon_social)
                        ->setCellValue('C'.$i, $value->prov_ruc)
                        ->setCellValue('D'.$i, $value->tipo_documento)
                        ->setCellValue('E'.$i, $value->comp_doc_serie)
                        ->setCellValue('F'.$i, $value->comp_doc_numero)
                        ->setCellValue('G'.$i, $fecha)
                        ->setCellValue('H'.$i, $value->moneda)
                        ->setCellValue('I'.$i, $value->comp_doc_subtotal)
                        ->setCellValue('J'.$i, $value->comp_doc_igv)
                        ->setCellValue('K'.$i, $value->comp_doc_total);
            /*$i++; 
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

            $spreadsheet->getActiveSheet()->mergeCells('A'.$i.':H'.$i);*/

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

}