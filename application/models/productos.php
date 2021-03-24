<?PHP
use PhpOffice\PhpSpreadsheet\Spreadsheet;    
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Productos extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();        
        date_default_timezone_set('America/Lima');
        $this->load->model('accesos_model'); 
        $this->load->model('productos_model');
        $this->load->model('categoria_model');
        $this->load->model('medida_model');
        $this->load->model('tipo_items_model');
        $this->load->model('almacenes_model');
        $this->load->model('lineas_model');
        $this->load->model('marcas_model');
        $this->load->model('tipo_igv_model');

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
        //$this->db->where('ps_id',27);
        //$ps = $this->db->get('productos_sunat')->result();
        //print_r(utf8_encode("C?LON"));exit();
        /*foreach($ps as $p){
          if($p->ps_cod=='') { 
            $cod = substr($p->ps_nom,0,8);
            $nom = utf8_encode(substr($p->ps_nom,9));
            $this->db->set('ps_cod',$cod);
            $this->db->set('ps_nom',$nom);
            $this->db->where('ps_id',$p->ps_id);
            $this->db->update('productos_sunat');
        }
        }*/

        $data['config'] = $this->db->from('comprobantes_ventas')->get()->row();
    	$data['productos'] = $this->productos_model->select();
        $data['almacenes'] = $this->almacenes_model->select();
        $this->accesos_model->menuGeneral();
        $this->load->view('productos/basic_index', $data);
        $this->load->view('templates/footer');    	
    }
    public function crear()
    {
    	$data = array();
        $data['config'] = $this->db->from('comprobantes_ventas')->get()->row();
        $data['categoria'] = $this->categoria_model->select();
        $data['medida'] = $this->medida_model->select();
        $data['tipoitem'] =$this->tipo_items_model->select();
        $data['almacenes'] = $this->almacenes_model->select();
        $data['lineas'] = $this->lineas_model->select();
        $data['marcas'] = $this->marcas_model->select();

    	echo $this->load->view('productos/modal_crear', $data);
    }
    public function editar($idProducto)  {

        $data['config'] = $this->db->from('comprobantes_ventas')->get()->row();
        $data['categoria'] = $this->categoria_model->select();
        $data['medida'] = $this->medida_model->select();
    	$data['producto'] = $this->productos_model->select($idProducto);
        $data['tipoitem'] =$this->tipo_items_model->select();
        $data['almacenes'] = $this->almacenes_model->select();
        $data['lineas'] = $this->lineas_model->select();
        $data['marcas'] = $this->marcas_model->select();
    	$this->load->view('productos/modal_crear', $data);
    }
    public function guardarProducto()
    {
    	$error = array();
        if($_POST['codigo_auto_num_m'] == 0)
        {
            if($_POST['prod_codigo'] == '')
            {
                $error['prod_codigo'] = 'falta ingresar codigo';
            }
        }
    	
    	if($_POST['prod_nombre'] == '')
    	{
    		$error['prod_nombre'] = 'falta ingresar nombre';
    	}
    	if($_POST['prod_precio_publico'] == '')
    	{
    		$error['prod_precio_publico'] = 'falta ingresar precio de venta';
    	}
        if($_POST['prod_precio_compra'] == '')
        {
            $error['prod_precio_compra'] = 'falta ingresar precio de compra';
        }
    	if($_POST['prod_categoria'] == '')
    	{
    		$error['prod_categoria'] = 'falta categoria';
    	}
        if($_POST['prod_medida'] == '')
        {
            $error['prod_medida'] = 'falta medida';
        }
        /*if($_POST['almacen'] == '')
        {
            $error['almacen'] = 'falta almacén';
        }*/

    	if(count($error) > 0)
    	{
    		$data = ['status'=>STATUS_FAIL,'tipo'=>1, 'errores'=>$error];
    		sendJsonData($data);
    		exit();
    	}    

    	//guardamos el producto
    	$result = $this->productos_model->guardar();
    	
    	if($result)
    	{            
     		sendJsonData(['status'=>STATUS_OK]);
     		exit();
    	}else
    	{
    		sendJsonData(['status'=>STATUS_FAIL, 'tipo'=>2]);
    		exit();
    	}	

    }

    public function eliminar($idProducto)
    {
    	$result = $this->productos_model->eliminar($idProducto);
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
    	$rsDatos = $this->productos_model->getMainList();
    	sendJsonData($rsDatos);
    }
    public function subirProductosUi()
    {
        $this->load->view('productos/subir_productos_ui');
    }
    public function generarCodPro() {        
        do {
        $existe=0;
            $codigo = rand(10000,99999);
            $result = $this->validarCodProd($codigo);

            if ($result) {
                $existe=1;
            }
        } while ($existe > 0);
        return $codigo;
    }
    public function validarCodProd($codigo='') {
        $result = $this->db->from("productos")
                               ->where('prod_codigo',$codigo)
                               ->get()
                               ->row();

        if ($result) {
            return true;
        }
        return false;        
    }
    public function guardarSubidaProductos() {     

        //echo 12312;exit();

        $archivo = $_FILES['files'];
        
        //establecemos la ruta desde donde leeremos
        $rutaArchivo = $archivo['tmp_name'];
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
        $spreadsheet = $reader->load($rutaArchivo);
        $sheet = $spreadsheet->getActiveSheet();
        $arrayProductos = array();

        $highestRow = $spreadsheet->getActiveSheet()->getHighestRow();

        foreach($sheet->getRowIterator(2) as $row)
        {            
            $producto = array();
            $codigo = $sheet->getCellByColumnAndRow('1', $row->getRowIndex())->getValue();
            $producto['prod_nombre'] = $sheet->getCellByColumnAndRow('2', $row->getRowIndex())->getValue();

            if($producto['prod_nombre']=='')
            {
                continue;
            }            

            /*si el producto ya existe en la bd no se va a registrar ni actualizar*/
            $rsProducto = $this->db->from("productos")
                                   ->where("prod_codigo", $codigo)
                                   ->where("prod_estado", ST_ACTIVO)
                                   ->get()
                                   ->row();
            if($rsProducto)
            {
                continue;
            }


            if ($codigo=='') {
                $codigo = $this->generarCodPro();
            }

            $producto['prod_codigo'] = $codigo;
            
            /*verificamos si la categoria ha sido registrada*/
            $categoria = $sheet->getCellByColumnAndRow('3', $row->getRowIndex())->getValue();
            $rsCategoria = $this->db->from("categoria")
                                    ->where("cat_nombre", $categoria)
                                    ->get()
                                    ->row();
            if($rsCategoria)
            {
                $idCategoria = $rsCategoria->cat_id;
            }else{
                //regitramos la nueva categoria
                $insertCategoria = [
                                    "cat_nombre" => $categoria,
                                    "cat_estado" => ST_ACTIVO
                                   ];

                $this->db->insert("categoria", $insertCategoria);
                $idCategoria = $this->db->insert_id();                   
            } 
            $producto['prod_categoria_id'] = $idCategoria;

            /*verificamos si unidad de medida ha sido registrada*/

            $linea = $sheet->getCellByColumnAndRow('19', $row->getRowIndex())->getValue();
            $rslinea = $this->db->from("lineas")
                                    ->where("lin_nombre", $linea)
                                    ->get()
                                    ->row();
            if($rslinea)
            {
                $idlinea = $rslinea->lin_id;
            }else{
                //regitramos la nueva categoria
                $insertlinea = [
                                    "lin_nombre" => $linea,
                                    "lin_estado" => ST_ACTIVO
                                   ];

                $this->db->insert("lineas", $insertlinea);
                $idlinea = $this->db->insert_id();                   
            } 
            $producto['prod_linea_id'] = $idlinea;

             $marca = $sheet->getCellByColumnAndRow('20', $row->getRowIndex())->getValue();
            $rsmarca = $this->db->from("marcas")
                                    ->where("mar_nombre", $marca)
                                    ->get()
                                    ->row();
            if($rsmarca)
            {
                $idmarca = $rsmarca->mar_id;
            }else{
                //regitramos la nueva categoria
                $insertmarca = [
                                    "mar_nombre" => $marca,
                                    "mar_estado" => ST_ACTIVO
                                   ];

                $this->db->insert("marcas", $insertmarca);
                $idmarca = $this->db->insert_id();                   
            } 
            $producto['prod_marca_id'] = $idmarca;











            $unidadMedida = $sheet->getCellByColumnAndRow('4', $row->getRowIndex())->getValue();

            $rsUnidadMedida = $this->db->from("medida")
                                       ->where("medida_nombre", $unidadMedida)
                                       ->get()
                                       ->row();

            $producto['prod_medida_id'] = $rsUnidadMedida->medida_id;                           
            $producto['prod_precio_publico'] = $sheet->getCellByColumnAndRow('5', $row->getRowIndex())->getValue();
            $producto['prod_precio_compra'] = $sheet->getCellByColumnAndRow('6', $row->getRowIndex())->getValue();
            $producto['prod_cantidad_minima'] = $sheet->getCellByColumnAndRow('7', $row->getRowIndex())->getValue();
            $producto['prod_codigo_sunat'] = $sheet->getCellByColumnAndRow('9', $row->getRowIndex())->getValue();

            $producto['prod_precio_2'] = $sheet->getCellByColumnAndRow('10', $row->getRowIndex())->getValue();
            $producto['prod_precio_3'] = $sheet->getCellByColumnAndRow('11', $row->getRowIndex())->getValue();
            $producto['prod_precio_4'] = $sheet->getCellByColumnAndRow('12', $row->getRowIndex())->getValue();
            $producto['prod_precio_5'] = $sheet->getCellByColumnAndRow('13', $row->getRowIndex())->getValue();
            $producto['prod_codigo_barra'] = $sheet->getCellByColumnAndRow('14', $row->getRowIndex())->getValue();
            $producto['prod_comision_vendedor'] = $sheet->getCellByColumnAndRow('15', $row->getRowIndex())->getValue();
            $producto['prod_garantia'] = $sheet->getCellByColumnAndRow('16', $row->getRowIndex())->getValue();
            $producto['prod_descuento'] = $sheet->getCellByColumnAndRow('17', $row->getRowIndex())->getValue();            
            //$producto['prod_caducidad'] = new DateTime($sheet->getCellByColumnAndRow('18',$row->getRowIndex())->getValue()))->format('Y-m-d');


            //$data_value = $sheet->getCellByColumnAndRow('18',$row->getRowIndex())->getValue();
            //$data = date($format = "Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($data_value));
            //$phpDateTimeObject = PHPExcel_Shared_Date::ExcelToPHP($data_value);
            //echo  $data;

            //$producto['prod_linea_id'] = $sheet->getCellByColumnAndRow('19', $row->getRowIndex())->getValue();
            //$producto['prod_marca_id'] = $sheet->getCellByColumnAndRow('20', $row->getRowIndex())->getValue();
            $producto['prod_ubicacion'] = $sheet->getCellByColumnAndRow('21', $row->getRowIndex())->getValue();

            $producto['prod_observaciones'] = $sheet->getCellByColumnAndRow('22', $row->getRowIndex())->getValue();

            $producto['prod_estado'] = ST_ACTIVO;
            $producto['prod_almacen_id'] = $this->session->userdata('almacen_id');

            $producto['prod_tipo'] = 1;
            if($producto['prod_medida_id']==59){
                $producto['prod_tipo'] = 2;
            }
            
            //var_dump($producto);exit;
            
            $this->db->insert("productos", $producto);
            $idProducto = $this->db->insert_id();   
            //todos los ingresos se hacen al almacen principal
            /*$rsAlmacen = $this->db->from("almacenes")
                                  ->where("alm_principal", 1)
                                  ->get()

                                  ->row();*/ 
            //verificamos si hay stock inicial
            $stock = (int)($sheet->getCellByColumnAndRow('8', $row->getRowIndex())->getValue());            
            if($stock>0)
            {
                //ingresamos stock
                /*for($i=0; $i<$stock; $i++)
                {
                    $ejemplarInsert = [
                                        "ejm_producto_id"   => $idProducto,
                                        "ejm_fecha_ingreso" => (new DateTime())->format("Y-m-d"),
                                        "ejm_almacen_id"    => $this->session->userdata('almacen_id'),
                                        "ejm_estado"        => ST_PRODUCTO_DISPONIBLE
                                      ];
                    $this->db->insert("ejemplar", $ejemplarInsert);                  
                }*/
               $kardex = array(
                  'k_fecha' => date('Y-m-d'),
                  'k_almacen' => $this->session->userdata("almacen_id"),
                  'k_tipo' => 0,
                  'k_operacion_id' => 0, 
                  'k_concepto' => 'Stock Inicial', 
                  'k_producto' => $idProducto,
                  'k_ecantidad' => $stock,
                  'k_excantidad' => $stock
                                     
               );

               $this->db->insert('kardex', $kardex);

            }                                                                          
        }
        
    }

      public function ExportarExcel($nombre='') {
       if($this->uri->segment(3)!='0') {
            $this->db->like('prod_nombre', $this->uri->segment(3));
        }
        $result = $this->db->from("productos pr")
                            ->join("almacenes alm","alm.alm_id=pr.prod_almacen_id")
                            ->join("categoria c","pr.prod_categoria_id=c.cat_id")
                            ->join("lineas l","pr.prod_linea_id=l.lin_id")
                            ->join("marcas ma","pr.prod_marca_id=ma.mar_id")
                            ->join("medida m","pr.prod_medida_id=m.medida_id")
                            ->where("pr.prod_estado", ST_ACTIVO)
                            ->get()
                            ->result();

                            //var_dump($result);exit;
        
        /*EXPORTAR A EXCEL*/
        $this->load->library('excel');
        $spreadsheet = new PHPExcel();
        $spreadsheet->getProperties()->setCreator('Rob Gravelle')
                ->setLastModifiedBy('Rob Gravelle')
                ->setTitle('A Simple Excel Spreadsheet')
                ->setSubject('PhpSpreadsheet')
                ->setDescription('A Simple Excel Spreadsheet generated using PhpSpreadsheet.')
                ->setKeywords('Microsoft office 2013 php PhpSpreadsheet')
                ->setCategory('Test file');         
        
        $i=2;
    

        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(20);

        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('O')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('P')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('Q')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('R')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('S')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('T')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('U')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('V')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('W')->setWidth(20);


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
         $spreadsheet->getActiveSheet()->getStyle("N1")->getFont()->setBold(true);
         $spreadsheet->getActiveSheet()->getStyle("O1")->getFont()->setBold(true);
         $spreadsheet->getActiveSheet()->getStyle("P1")->getFont()->setBold(true);
         $spreadsheet->getActiveSheet()->getStyle("Q1")->getFont()->setBold(true);
         $spreadsheet->getActiveSheet()->getStyle("R1")->getFont()->setBold(true);
         $spreadsheet->getActiveSheet()->getStyle("S1")->getFont()->setBold(true);
         $spreadsheet->getActiveSheet()->getStyle("T1")->getFont()->setBold(true);
         $spreadsheet->getActiveSheet()->getStyle("U1")->getFont()->setBold(true);
         $spreadsheet->getActiveSheet()->getStyle("V1")->getFont()->setBold(true);
         $spreadsheet->getActiveSheet()->getStyle("W1")->getFont()->setBold(true);


        
        $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A1', 'CÓDIGO')
                ->setCellValue('B1', 'DESCRIPCIÓN')
                ->setCellValue('C1', 'CATEGORIA')
                ->setCellValue('D1', 'UNIDAD/MEDIDA')
                ->setCellValue('E1', 'PRECIO VENTA')
                ->setCellValue('F1', 'PRECIO COMPRA')
                ->setCellValue('G1', 'STOCK')
                ->setCellValue('H1', 'CODIGO SUNAT')
                ->setCellValue('I1', 'ALMACÉN')

                ->setCellValue('J1', 'prod_precio_2')
                ->setCellValue('K1', 'prod_precio_3')
                ->setCellValue('L1', 'prod_precio_4')
                ->setCellValue('M1', 'prod_precio_5')
                ->setCellValue('N1', 'prod_codigo_barra')
                ->setCellValue('O1', 'prod_comision_vendedor')
                ->setCellValue('P1', 'prod_garantia')
                ->setCellValue('Q1', 'prod_descuento')
                ->setCellValue('R1', 'prod_caducidad')
                ->setCellValue('S1', 'prod_cantidad_minima')
                ->setCellValue('T1', 'linea')
                ->setCellValue('U1', 'marca')
                ->setCellValue('V1', 'ubicaciones')

                

                ->setCellValue('W1', 'prod_observaciones');
                
        $spreadsheet->getActiveSheet()->setTitle('ARTICULOS');
        foreach ($result as $value) {
            //$stock = $this->productos_model->stock($value->prod_id);

            $stock = $this->productos_model->getStockProductos($value->prod_id,$this->session->userdata('almacen_id'));

            $spreadsheet->getActiveSheet()
                        ->setCellValue('A'.$i, $value->prod_codigo)
                        ->setCellValue('B'.$i, $value->prod_nombre)
                        ->setCellValue('C'.$i, $value->cat_nombre)
                        ->setCellValue('D'.$i, $value->medida_nombre)
                        ->setCellValue('E'.$i, $value->prod_precio_publico)
                        ->setCellValue('F'.$i, $value->prod_precio_compra)
                        ->setCellValue('G'.$i, $stock)
                        ->setCellValue('H'.$i, $value->prod_codigo_sunat)
                       

                         ->setCellValue('J'.$i, $value->prod_precio_2)
                         ->setCellValue('K'.$i, $value->prod_precio_3)
                         ->setCellValue('L'.$i, $value->prod_precio_4)
                         ->setCellValue('M'.$i, $value->prod_precio_5)
                         ->setCellValue('N'.$i, $value->prod_codigo_barra)
                         ->setCellValue('O'.$i, $value->prod_comision_vendedor)
                         ->setCellValue('P'.$i, $value->prod_garantia)
                         ->setCellValue('Q'.$i, $value->prod_descuento)
                         ->setCellValue('R'.$i, $value->prod_caducidad)
                         ->setCellValue('S'.$i, $value->prod_cantidad_minima)
                         ->setCellValue('T'.$i, $value->lin_nombre)
                         ->setCellValue('U'.$i, $value->mar_nombre)
                         ->setCellValue('V'.$i, $value->prod_ubicacion)
                         ->setCellValue('W'.$i, $value->prod_observaciones)
                          ->setCellValue('I'.$i, $this->session->userdata('almacen_nom'));
            $i++;
        }
        
        $spreadsheet->setActiveSheetIndex(0);         
        // Redirect output to a client's web browser (Xlsx)

        $filename = 'Reporte_Stock_' . date("d-m-Y") . '---' . rand(1000, 9999) . '.xls'; //save our workbook as this file name
        ob_end_clean();
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        $objWriter = PHPExcel_IOFactory::createWriter($spreadsheet, 'Excel5');
        $objWriter->save('php://output');
        exit;
   }

   public function ExportarExcel_vendido($nombre='') {
       if($this->uri->segment(3)!='0') {
            $this->db->like('prod_nombre', $this->uri->segment(3));
        }
        $result = $this->db->from("productos pr")
                            ->join("almacenes alm","alm.alm_id=pr.prod_almacen_id")
                            ->join("categoria c","pr.prod_categoria_id=c.cat_id")
                            ->join("medida m","pr.prod_medida_id=m.medida_id")
                            ->where("pr.prod_estado", ST_ACTIVO)
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
                ->setCellValue('A1', 'CÓDIGO')
                ->setCellValue('B1', 'NOMBRE')
                ->setCellValue('C1', 'ENE')
                ->setCellValue('D1', 'FEB')
                ->setCellValue('E1', 'MAR')
                ->setCellValue('F1', 'ABR')
                ->setCellValue('G1', 'MAY')
                ->setCellValue('H1', 'JUN')
                ->setCellValue('I1', 'JUL')
                ->setCellValue('J1', 'AGO')
                ->setCellValue('K1', 'SEP')
                ->setCellValue('L1', 'OCT')
                ->setCellValue('M1', 'NOV')
                ->setCellValue('N1', 'DIC');
                
                
        $spreadsheet->getActiveSheet()->setTitle('articulos');

        $mes['1'] = "C";
        $mes['2'] = "D";
        $mes['3'] = "E";
        $mes['4'] = "F";
        $mes['5'] = "G";
        $mes['6'] = "H";
        $mes['7'] = "I";
        $mes['8'] = "J";
        $mes['9'] = "K";
        $mes['10'] = "L";
        $mes['11'] = "M";
        $mes['12'] = "N";

        foreach ($result as $value) {
           
            $stock = $this->productos_model->stock($value->prod_id);

            $spreadsheet->getActiveSheet()->setCellValue('A'.$i, $value->prod_codigo);
            $spreadsheet->getActiveSheet()->setCellValue('B'.$i, $value->prod_nombre);

            for($b=1;$b<=12;$b++){
                $this->db->from('items as i');
                $this->db->join('comprobantes as c','c.id=i.comprobante_id');
                $this->db->where('i.producto_id',$value->prod_id);
                $this->db->where('MONTH(c.fecha_de_emision)',$b);
                $this->db->where('c.tipo_documento_id !=',7);
                $this->db->where('c.anulado !=',1);
                $this->db->select_sum('i.cantidad');
                $res1 = $this->db->get()->row();

                $this->db->from('nota_pedido_detalle as npd');
                $this->db->join('nota_pedido as np','np.notap_id=npd.notapd_notap_id');
                $this->db->where('npd.notapd_producto_id',$value->prod_id);
                $this->db->where('MONTH(np.notap_fecha)',$b);
                $this->db->where('np.notap_estado',1);
                $this->db->select_sum('npd.notapd_cantidad');
                $res2 = $this->db->get()->row();

                $res = $res1->cantidad + $res2->notapd_cantidad;

                $spreadsheet->getActiveSheet()->setCellValue($mes[$b].$i, $res);
               
           }

          
            $i++;
        }

        $nombre = "Articulos_vendidos_".date('Y');
        
        $spreadsheet->setActiveSheetIndex(0);         
        // Redirect output to a client's web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$nombre.'.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');       
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
   }

   public function kardex_fisico(){
    

        $data['almacenes'] = $this->almacenes_model->select();
        $this->accesos_model->menuGeneral();
        $this->load->view('kardexf/basic_index', $data);
        $this->load->view('templates/footer');      
   }

   public function buscar_producto(){
        $texto = $this->input->get('texto');
        $where = '(prod_estado='.ST_ACTIVO.' AND prod_nombre LIKE "%'.$texto.'%") OR (prod_estado='.ST_ACTIVO.' AND prod_codigo LIKE "%'.$texto.'%")';
        $this->db->where($where);
        $clientes = $this->db->get('productos')->result();

        echo json_encode($clientes);

    }

    public function kardex_fisico_producto(){
        $producto = $this->input->get('producto_id');
        $almacen = $this->input->get('almacen');
        $fechai = $this->input->get('fechai');
        $fechaf = $this->input->get('fechaf');
        
        $this->db->from('kardex as k');
        $this->db->join('productos as p','p.prod_id=k.k_producto');
        $this->db->join('almacenes as a','a.alm_id=k.k_almacen');
        $this->db->where('k.k_almacen',$almacen);
        $this->db->where('k.k_producto',$producto);

        if($fechai!=''){
            $this->db->where('DATE(k.k_fecha) >=',(new DateTime($fechai))->format("Y-m-d"));
            $this->db->where('DATE(k.k_fecha) <=',(new DateTime($fechaf))->format("Y-m-d"));
        }
        $this->db->order_by('k.k_id','ASC');
        $kardex = $this->db->get()->result();

        echo json_encode($kardex);
    }

    public function buscar_producto_sunat(){
        $texto = $this->input->get('texto');
        $this->db->like('ps_cod',$texto,'after');
        $this->db->or_like('ps_nom',$texto,'after');
        $this->db->limit(15);
        $ps = $this->db->get('productos_sunat')->result();
        echo json_encode($ps);

    }

    public function seleccionar_producto_sunat(){
        $ps_cod = $this->input->get('cod');
        $this->db->where('ps_cod',$ps_cod);
        $ps = $this->db->get('productos_sunat')->row();


        echo json_encode($ps);

    }


    public function masVendidos(){

        $this->load->view('templates/header_administrador');
        $this->load->view('productos/masVendidos');
        $this->load->view('templates/footer');
    }
    
    public function masVendidos_g(){

        $rsmasVendidos_ct = $this->productos_model->masVendidos_ct();
        $rsmasVendidos_np = $this->productos_model->masVendidos_np();

        $arrayTotal = $this->masVendidos_format($rsmasVendidos_np,$rsmasVendidos_ct);        
        //var_dump($results);exit;

        $contenido ='<table class="table table-streap">
                         <tr>
                            <th>N°</th>
                            <th>CODIGO</th>
                            <th>DESCRIPCION</th>
                            <th>CANTIDAD</th>                            
                        </tr>';

        $rsTotal_venta = 0;             
        $rsTotal_utilidad = 0;

        foreach($arrayTotal  as $value) {         
            $contenido .=   '<tr>
                                <td>'.$value['prod_codigo'].'</td>
                                <td>'.$value['prod_codigo'].'</td>
                                <td>'.$value['prod_nombre'].'</td>                                                                     
                                <td>'.$value['cantidad'].'</td>
                            </tr>';

                //$rsTotal_venta += $value['total_venta'];
                
                //$rsTotal_utilidad += $value['utilidad'];
         }

         //$contenido .= '<tr>
                            //<td>&nbsp;</td>
                            //<td>&nbsp;</td>
                            //<td>TOTAL</td>
                            //<td>'.$rsTotal_venta.'</td>
                            //<td>&nbsp;</td>
                            //<td>'.$rsTotal_utilidad.'</td>
                        //</tr>';


         $contenido .= '</table>';
        echo $contenido;        
    }       

    public function masVendidos_format($rsmasVendidos_np,$rsmasVendidos_ct){
        $arrayTotal = array_merge($rsmasVendidos_np,$rsmasVendidos_ct);        
            //$a = array();
            $results = array();
            foreach ($arrayTotal as $value)
            {
                $rsProducto = $this->productos_model->select($value['prod_id']);

                 if(!isset($results[$value['prod_id']]) )
                 {
                    $results[$value['prod_id']]['prod_nombre'] = '';
                    $results[$value['prod_id']]['prod_codigo'] = '';
                    $results[$value['prod_id']]['cantidad'] = 0;                    
                 }

                 $results[$value['prod_id']]['prod_nombre']  = $value['prod_nombre'];
                 $results[$value['prod_id']]['prod_codigo']  = $value['prod_codigo'];
                 $results[$value['prod_id']]['cantidad']    += $value['cantidad'];                                        
            }

            return $results;
    }



    public function exportarProductosmasVendidos(){

        $rsmasVendidos_ct = $this->productos_model->masVendidos_ct();
        $rsmasVendidos_np = $this->productos_model->masVendidos_np();        

        $reporteTotal = $this->masVendidos_format($rsmasVendidos_np,$rsmasVendidos_ct);

        $fecha_desde = $_GET['fecha_desde'];        
        $fecha_hasta = $_GET['fecha_hasta'];

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
        
        $i=6;

        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(40);
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
                ->setCellValue('B1', 'PRODUCTOS MAS VENDIDOS');

        $spreadsheet->getActiveSheet()
                ->setCellValue('A2', 'FECHA DESDE')
                ->setCellValue('B2', $fecha_desde)
                ->setCellValue('A3', 'FECHA HASTA')
                ->setCellValue('B3', $fecha_hasta);             


        $spreadsheet->getActiveSheet()
                ->setCellValue('A5', 'N°')
                ->setCellValue('B5', 'CODIGO')
                ->setCellValue('C5', 'NOMBRE')
                ->setCellValue('D5', 'CANTIDAD');
                
        $rsTotal_venta = 0;
        $rsTotal_compra = 0;
        $rsTotal_utilidad = 0;
        $rsTotal_comision = 0;
        $rsTotal_utilidadNeta = 0;  
        foreach($reporteTotal  as $value) {     
         $spreadsheet->getActiveSheet()
                        ->setCellValue('A'.$i, $value['prod_codigo'])
                        ->setCellValue('B'.$i, $value['prod_codigo'])
                        ->setCellValue('C'.$i, $value['prod_nombre'])
                        ->setCellValue('D'.$i, $value['cantidad']);
            

                //$rsTotal_venta += $value['total_venta'];
                //$rsTotal_compra += $value['total_compra'];
                //$rsTotal_utilidad += $value['utilidad'];
                //$rsTotal_comision += $value['comision'];
                //$rsTotal_utilidadNeta += $value['utilidadNeta'];
                $i++;
         }      

         //$spreadsheet->getActiveSheet()                     
                        //->setCellValue('C'.$i, 'TOTAL')
                        //->setCellValue('D'.$i, $rsTotal_venta)
                        //->setCellValue('E'.$i, $rsTotal_compra)
                        //->setCellValue('F'.$i, $rsTotal_utilidad)
                        //->setCellValue('G'.$i, $rsTotal_comision)
                        //->setCellValue('H'.$i, $rsTotal_utilidadNeta);           

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

    //CAMBIO DE IMAGEN 19/09/2020
    public function galeria_g() {                            

       //GUARDAR IMAGEN
       if($_POST['prod_id'] != ''){

       $carpeta = 'images/productos/';
       opendir($carpeta);
       $destino = $carpeta.$_FILES['prod_imagen']['name'];
       
       copy($_FILES['prod_imagen']['tmp_name'], $destino);       

       $data = array(
                        'prod_id' => $_POST['prod_id'],
                        'prod_imagen' => $_FILES['prod_imagen']['name']
                    );        

       $this->db->where('prod_id', $_POST['prod_id']);
       $this->db->update("productos", $data);        

       $image = $this->db->from('productos')
                            ->where('prod_id',$_POST['prod_id'])
                            ->get()
                            ->row();

        $output = '';            
               $output .= '<div class="col-md-12" align="center" ><img class="example-image" src="'.base_url().'images/'.$image->prod_imagen.'" width="180px" height="180px" style="border:1px solid #ccc;margin-top:10px;" /></div>';        
          echo $output;  
        }  
    }

    public function modal_buscarProducto(){
        //$data['tipo_clientes'] = $this->tipo_clientes_model->select();     
        $data['tipo_igv'] = $this->tipo_igv_model->select('', '', '', 0);
        $data['configuracion'] = $this->db->from('comprobantes_ventas')->get()->row();   
        $this->load->view('productos/modal_buscarProducto',$data);            
    }           
}