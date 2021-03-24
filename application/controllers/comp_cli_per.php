<?PHP

    if(!defined('BASEPATH')) exit('No direct script access allowed');
    
    
    class Comp_cli_per extends CI_Controller{
        
        public function __construct() {
            parent::__construct();
            
            date_default_timezone_set('America/Lima');
            $this->load->model('monedas_model');
            $this->load->model('tipo_cambio_model');
            $this->load->model('tipo_documentos_model');
            $this->load->model('elemento_adicionales_model');
            $this->load->model('comp_cli_per_model');
            $this->load->model('ser_nums_model');
            $this->load->model('clientes_model');
            $this->load->model('empresas_model');
            $this->load->model('comprobantes_model');
            $this->load->model('items_model');
            $this->load->model('comprobantes_comp_cli_per_model');
            $this->load->model('comp_cli_per_model');
            $this->load->model('accesos_model');
            $this->load->model('variables_diversas_model');
            
            $empleado_id = $this->session->userdata('empleado_id');
            if (empty($empleado_id)) {
                $this->session->set_flashdata('mensaje', 'No existe sesion activa');
                redirect(base_url());
            }                        
        }
        
        public function index() {

            $data['cliente_selec'] = $this->input->post('cliente');
            $data['cliente_selec_id'] = $this->input->post('cliente_id');
            
            $data['empresa_selec'] = $this->input->post('empresa');
            $data['tipo_documento_selec'] = $this->input->post('tipo_documento');
            
            $data['desTipo_selec'] = $this->input->post('desTipo');
            
            $cliente_id = '';
            if($this->input->post('cliente_id')!= '')
                $cliente_id = $this->input->post('cliente_id');                        

            $tipo_documento_id = '';
            $desTipo_id = '';
            if($this->input->post('tipo_documento') > 0 && $this->input->post('tipo_documento')){
                $tipo_documento_id = $this->input->post('tipo_documento');            
                
                if($this->input->post('tipo_documento') == 1)//Solo si es Factura
                    $desTipo_id = $this->input->post('desTipo');
            }
            
            $empresa_id = '';            
            if($this->input->post('empresa') > 0 && $this->input->post('empresa'))
                $empresa_id = $this->input->post('empresa');
            
                                    
            //echo $cliente_id;exit;
            //echo $desTipo_id;exit;
            $data['ser_nums']  = $this->ser_nums_model->select('',1);
            $data['empresas']  = $this->empresas_model->select();
            $data['tipo_documentos'] = $this->tipo_documentos_model->select('','',5);//Documentos donde ID < 5
            $data['clientes']  = $this->comp_cli_per_model->select('',$cliente_id,$tipo_documento_id,$desTipo_id,$empresa_id);
                                            
            $this->accesos_model->menuGeneral();
            $this->load->view('comp_cli_per/index',$data);
            $this->load->view('templates/footer');
        }
                
        public function nuevo(){           

            $data[1] = 1;            
            $this->accesos_model->menuGeneral();
            $this->load->view('comp_cli_per/nuevo',$data);
            $this->load->view('templates/footer');                                                            
        }
        
        public function guardar(){            
            
            $array =  array(
                'cliente_id' => $this->input->post('cliente_id'),
                'tipo_documento_id' => $this->input->post('tipo_documento'),
                'descripcion' => $this->input->post('descripcion'),
                'tipo' => $this->input->post('desTipo'),
                'fecha_insert' => date('Y-m-d h:i:s')
            );
                                                                                
            $this->comp_cli_per_model->insertar($array);
            redirect(base_url().'index.php/comp_cli_per/index');
        }
        
        public function modificar() {
            
            $data['comp_cli_per'] = $this->comp_cli_per_model->select($this->uri->segment(3));
            $this->accesos_model->menuGeneral();
            $this->load->view('comp_cli_per/modificar',$data);
            $this->load->view('templates/footer');            
        }                
                
        public function modificar_g() {            
            $compCliPer_id = $_POST['compCliPer_id'];            
            $array =  array(
                'descripcion' => $this->input->post('descripcion'),
                'tipo' => $this->input->post('desTipo')
            );
                        
            $this->comp_cli_per_model->modificar($compCliPer_id ,$array);
            redirect(base_url().'index.php/comp_cli_per/index');
        }
        
        public function eliminar() {            
            $this->comp_cli_per_model->eliminar($this->uri->segment(3));
            redirect(base_url().'index.php/comp_cli_per/index');
        }                        
        
        public function facMensualPermanentes() {            
            
            $serie = $_POST['series'];                        
            $regUltimo = $this->comprobantes_model->selectUltimoReg($serie);
            $numero = $regUltimo['numero'];                                                
               
            
            //OBTENIENDO DATOS DE FORMULARIO
            $cliente_id = $_POST['cliente_id'];
            $empresa_id = $_POST['empresa_id'];            
            $descripcion = $_POST['descripcion'];
            $tipo_documento_id = $_POST['tipo_documento_id'];
            $moneda_id = $_POST['moneda_id'];            
            $monto = $_POST['monto'];            
                                                
            
            $i = 0;
            
            set_time_limit(150);
            foreach ($cliente_id as $value) {
                $numero++;
                                                
                //CALCULANDO MONTOS
                    $descuento_global = 0;
                    $total_exonerada  = 0;
                    $total_inafecta   = 0;
                    $total_gravada    = 0;
                    $total_igv = 0;
                    $total_gratuita = 0;
                    $total_otros_cargos = 0;
                    $total_descuentos = 0;
                    $total_a_pagar = 0;
                    
                    $valorIgv = 0.18; //Obtener desde la Base de Datos 
                    
                    $importe =  $monto[$i];
                    $igv     =  $importe * $valorIgv;
                    $total   =  $importe + $igv;
                    
                    $total_gravada = $importe;
                    $total_a_pagar = $total;
                    $total_igv     = $igv;
                
                //OBTENIENDO EL TIPO CAMBIO y MONTO TOTAL PARA COMPROBAR Y SI EXISTE DETRACCION
                $montoTotalDetraccion = $total_a_pagar;    
                $tipoCambio = NULL;
                if($moneda_id[$i] > 1){
                    $tipoCambio = $this->tipo_cambio_model->selectJson($moneda_id[$i]);
                    $tipoCambio = $tipoCambio['tipo_cambio'];
                    
                    $montoTotalDetraccion =  $total_a_pagar * $tipoCambio;
                }                                                            
                    
                //CALCULANDO DETRACCION SI HUBIESE
                    $detraccion = NULL;
                    $elemento_adicional_id = NULL;
                    $porcentaje_de_detraccion = NULL;
                    $total_detraccion = NULL;
                    
                if($tipo_documento_id[$i] == 1) {//SIEMPRE SEA FACTURA                     
                     if( $montoTotalDetraccion > 700)
                         
                         $detraccion = 1;
                         $elemento_adicional_id = 11;
                         $porcentaje_de_detraccion = 10;
                         $total_detraccion = $montoTotalDetraccion/10;                                                                   
                }
                
                $array = array(
                'cliente_id'       => $cliente_id[$i],
                'tipo_documento_id'=> $tipo_documento_id[$i],
                'serie'            => $serie,
                'numero'           => $numero,
                'fecha_de_emision' => date('Y-m-d'),
                'moneda_id'        => $moneda_id[$i],
                'tipo_de_cambio'   => $tipoCambio,
                'detraccion'       => $detraccion,
                'elemento_adicional_id'    => $elemento_adicional_id,
                'porcentaje_de_detraccion' => $porcentaje_de_detraccion,
                'total_detraccion' => $total_detraccion,
                'descuento_global' => $descuento_global,
                'total_exonerada' => $total_exonerada,
                'total_inafecta'   => $total_inafecta, 
                'total_gravada'  => $total_gravada,
                'total_igv'      => $total_igv,
                'total_gratuita' => $total_gratuita,
                'total_otros_cargos'=> $total_otros_cargos,
                'total_descuentos'  => $total_descuentos,
                'total_a_pagar'     => $total_a_pagar,
                'empresa_id'        => $empresa_id[$i],
                'tipo_pago_id'      => 1
            );
                
            //var_dump($array);exit;                                                
                $comprobante_id = $this->comprobantes_model->insertar($array);
                
                //var_dump($comprobante_id);exit;
                $items =  array(
                    'comprobante_id' => $comprobante_id,
                    'tipo_item_id' => 1,
                    'descripcion'  => $descripcion[$i],
                    'cantidad'     => 1,
                    'tipo_igv_id'  => 1,
                    'importe'      => $importe,
                    'subtotal'     => $importe,
                    'igv'          => $total_igv,
                    'total'        => $total_a_pagar
                );                
                $this->items_model->insertar($items);//$i++;                
                $this->txt(0,$comprobante_id);$i++;
                if($i%10 == 0)sleep(10);                
            }            
            redirect(base_url().'index.php/comp_cli_per/index');
        }
                
        public function buscador_cliente() {
            $abogado = $this->input->get('term');
            echo json_encode($this->clientes_model->clientesPorContratoJson($abogado,1,'activo'));
        }                
        
        //ENVIANDO AUTOMATIMATICAMENTE CORREO                
        public function txt($envio = 0,$comprobante_id = ''){
                    
            $comprobante = $this->comprobantes_model->select($comprobante_id);
            $items       = $this->items_model->select('',$comprobante_id);
            
            //TIPO EMPRESA
            $ruta = '';
            if ($comprobante['empresa_id'] == 1) { $ruta = 'sunat_archivos/sfs/DATA/'; }
            if ($comprobante['empresa_id'] == 2) { $ruta = 'neple/sunat_archivos/sfs/DATA/'; }
            
            //$filename = fopen('ftp://Pruebas:1475963@54.145.133.104/'.$comprobante['ruc'].'-'.$comprobante['tipo_documento_codigo'].'-'.$comprobante['serie'].'-'.$comprobante['numero'].'.CAB','w');
            //$log_file= file_get_contents("ftp://username:password@68.232.164.100/czero/68.232.164.100:27015/czero/gsconsole.log"); 
            if($envio == 0){                        
                if($comprobante['tipo_documento_id'] < 4){
                    // FACTURA , BOLETA
                    //$f = fopen('D:/data0/facturador/DATA/'.$comprobante['ruc'].'-'.$comprobante['tipo_documento_codigo'].'-'.$comprobante['serie'].'-'.$comprobante['numero'].'.CAB','w');
                    $sql = 'ftp://Pruebas:1475963@190.107.181.254:21/'.$ruta.$comprobante['empresa_ruc'].'-'.$comprobante['tipo_documento_codigo'].'-'.$comprobante['serie'].'-'.$comprobante['numero'].'.CAB';                    
                    $f = fopen($sql,'w');                    
                    $linea = "01|".$comprobante['fecha_sunat'].'||'.$comprobante['tipo_cliente_codigo']."|".trim($comprobante['cliente_ruc'])."|".$comprobante['cli_razon_social']."|".$comprobante['abrstandar']."|0.00|0.00|0.00|".$comprobante['total_gravada']."|".$comprobante['total_inafecta']."|".$comprobante['total_exonerada']."|".$comprobante['total_igv']."|0.00|0.00|".$comprobante['total_a_pagar']."|\r\n";
                    fwrite($f, $linea);
                    fclose($f);

                    //$f = fopen('D:/data0/facturador/DATA/'.$comprobante['ruc'].'-'.$comprobante['tipo_documento_codigo'].'-'.$comprobante['serie'].'-'.$comprobante['numero'].'.DET','w');
                    $f = fopen('ftp://Pruebas:1475963@190.107.181.254:21/'.$ruta.$comprobante['empresa_ruc'].'-'.$comprobante['tipo_documento_codigo'].'-'.$comprobante['serie'].'-'.$comprobante['numero'].'.DET','w');
                    foreach ($items as $value) {
                        $descripction = $this->sanear_string(utf8_decode($value['descripcion']));
                        $linea = "NIU"."|".$value['cantidad']."|||". str_replace("&","Y",trim(utf8_decode($descripction)))."|".$value['importe']."|0.00|".$value['igv']."|".$value['tipo_igv_codigo']."|0.00||".$value['importe']."|".$value['total']."|\r\n";
                        fwrite($f, $linea);
                    }
                    fclose($f);
                } else {
                    //NOTA DE CREDITO , DEBITO
                    $nota = $this->comprobantes_model->select($comprobante['com_adjunto_id']);
                    $f = fopen('ftp://Pruebas:1475963@190.107.181.254:21/'.$ruta.$comprobante['empresa_ruc'].'-'.$comprobante['tipo_documento_codigo'].'-'.$comprobante['serie'].'-'.$comprobante['numero'].'.NOT','w');
                    $linea = $comprobante['fecha_sunat']."|".$comprobante['tipo_nota_codigo']."|INTERES|01|".$nota['serie']."-".$nota['numero']."|".$comprobante['tipo_cliente_codigo']."|".$comprobante['cliente_ruc']."|".$comprobante['cli_razon_social']."|".$comprobante['abrstandar']."|0.00|".$comprobante['total_gravada']."|".$comprobante['total_inafecta']."|".$comprobante['total_exonerada']."|".$comprobante["total_igv"]."|0.00|0.00|".$comprobante['total_a_pagar']."\r\n";
                    fwrite($f, $linea);
                    fclose($f);
                    $f = fopen('ftp://Pruebas:1475963@190.107.181.254:21/'.$ruta.$comprobante['empresa_ruc'].'-'.$comprobante['tipo_documento_codigo'].'-'.$comprobante['serie'].'-'.$comprobante['numero'].'.DET','w');
                    foreach ($items as $value) {
                        $descripction = $this->sanear_string(utf8_decode($value['descripcion']));                    
                        $linea = "NIU"."|".$value['cantidad']."|||".  str_replace("&","Y",trim($descripction))."|".$value['importe']."|0.00|".$value['igv']."|".$value['tipo_igv_codigo']."|0.00|01|".$value['importe']."|".$value['total']."\r\n";
                        fwrite($f, $linea);
                    }
                    fclose($f);;
                }
                $this->comprobantes_model->modificar(array('enviado_sunat' => 1),$comprobante_id);
                $this->session->set_flashdata('mensaje', 'Envio exitoso!');
            } else {
                //  COMUNICACION DE BAJA TXT
                $fecha1 = date("Ymd"); 
                $fecha2 = date("Y-m-d");
                $numero = $this->comprobante_anulados_model->maxNumero($fecha2) + 1;

                $f = fopen('ftp://Pruebas:1475963@190.107.181.254:21/'.$ruta.$comprobante['empresa_ruc'].'-RA-'.$fecha1.'-'.$numero.'.CBA','w');
                $linea = $comprobante['fecha_sunat']."|".$fecha2."|".$comprobante['tipo_documento_codigo']."|".$comprobante['serie'].'-'.$comprobante['numero']."|ERROR|\r\n";
                fwrite($f, $linea);
                fclose($f);

                $dataAnular = array(
                    'fecha' => $fecha2,
                    'numero' => $numero,
                    'comprobante_id' => $comprobante_id,
                    'empleado_insert' => $this->session->userdata('empleado_id'),
                    'fecha_insert' => date("Y-m-d H:i:s")
                );
                $this->comprobante_anulados_model->insertar($dataAnular);
                $this->comprobantes_model->modificar(array('fecha_de_baja'=> $fecha2,'anulado' => 1),$comprobante_id);
                $this->session->set_flashdata('mensaje', 'Anulación exitosa!');
            }
        }
                
        public function seleccionar(){
            $where_customer = " AND c_c.fecha_eliminado IS NULL";
            $data['datos'] = $this->comp_cli_per_model->selectCustomer($_POST['anio'],$_POST['mes'], $where_customer);
            $data['envio_anio'] = $_POST['anio'];
            $data['envio_mes'] = $_POST['mes'];
            //var_dump($data['datos']);exit;
                        
            $this->accesos_model->menuGeneral();
            $this->load->view('comp_cli_per/carga',$data);
            $this->load->view('templates/footer');
        }
        
        public function carga(){
                        
            $data['datos'] = array();
            if(!empty($this->uri->segment(3)) && !empty($this->uri->segment(4))){                
                $where_customer = " AND c_c.fecha_eliminado IS NULL";
                $data['datos'] = $this->comp_cli_per_model->selectCustomer($this->uri->segment(3),$this->uri->segment(4), $where_customer);
            }
            
            $data['envio_anio'] = $this->uri->segment(3);
            $data['envio_mes'] = $this->uri->segment(4);

            $this->accesos_model->menuGeneral();
            $this->load->view('comp_cli_per/carga',$data);
            $this->load->view('templates/footer'); 
        }
        
        public function carga_g(){
                        
            //subimos excel
            $dir_subida = "./files/xlsx/";
            $fichero_subido = $dir_subida . basename($_FILES['fileToUpload']['name']);
            
            if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $fichero_subido)) {
                $mensaje = "Excel leido correctamente";                
            } else {
                $mensaje = "¡Posible ataque de subida de ficheros!\n";
            }
            $this->session->set_flashdata('mensaje_cliente_index', $mensaje);
            
            //echo "abc";exit;
            //leemos excel            
            require_once(APPPATH . 'libraries/PHPExcel/PHPExcel.php');            
            require_once(APPPATH . 'libraries/PHPExcel/PHPExcel/Reader/Excel2007.php');
            
            $objReader = new PHPExcel_Reader_Excel2007();            
            $objPHPExcel = $objReader->load($dir_subida.$_FILES['fileToUpload']['name']);
            //echo "abcde3";exit;
            
            $objPHPExcel->setActiveSheetIndex();
            $filas = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
            
            $estado = 0; $cadena = '';
            $array_ruc = array();
            for($i = 10; $i <= $filas; $i ++){
                $ruc = $this->clientes_model->clientePorRuc($objPHPExcel->getActiveSheet()->getCell('C'.$i));
                if($ruc['id'] == '0'){
                    $estado = 1;    
                    $cadena .= $objPHPExcel->getActiveSheet()->getCell('C'.$i)."<br>";
                }else{
                    $array_ruc["'".$objPHPExcel->getActiveSheet()->getCell('C'.$i)."'"] = $ruc['id'];
                }
            }
            
            if($estado == 1){
                $cadena = substr($cadena, 0, -1);
                $cadena = "RUC no existentes en la base de datos: " . $cadena;
                echo $cadena;
                return;
            }
            
            $anio = $objPHPExcel->getActiveSheet()->getCell('A7');
            $mes = $objPHPExcel->getActiveSheet()->getCell('B7');

            for($i = 10; $i <= $filas; $i ++){                                
                $empresa_id = $objPHPExcel->getActiveSheet()->getCell('A'.$i);
                $tipo_documento_id = $objPHPExcel->getActiveSheet()->getCell('B'.$i);
                $cliente_id = $array_ruc["'" . $objPHPExcel->getActiveSheet()->getCell('C'.$i) . "'"];
                $moneda_id = $objPHPExcel->getActiveSheet()->getCell('D'.$i);
                $monto = $objPHPExcel->getActiveSheet()->getCell('E'.$i);
                $descripcion = $objPHPExcel->getActiveSheet()->getCell('F'.$i)->getCalculatedValue();                                
                $observaciones = $objPHPExcel->getActiveSheet()->getCell('G'.$i);
                
                $this->comp_cli_per_model->insertar($empresa_id, $tipo_documento_id, $cliente_id, $moneda_id, $monto, $descripcion, $observaciones, $anio, $mes, $this->session->userdata('empleado_id'), date("Y-m-d H:i:s"));
            }
            redirect(base_url().'index.php/comp_cli_per/carga/' . $anio.'/' . $mes);
        }
        
        public function insertarComprobante(){

            $empresa_id = $this->uri->segment(3);
            $tipo_documento_id = $this->uri->segment(4);
            $comp_cli_per_id = $this->uri->segment(5);
            
            $serie = $this->ser_nums_model->seleccion(1, array('serie'), array('empresa_id' => $empresa_id, 'tipo_documento_id' => $tipo_documento_id));
            $numero = $this->comprobantes_model->selecMaximoNumero($empresa_id, $tipo_documento_id, $serie) + 1;
            $datos_comp_cli_per = $this->comp_cli_per_model->select(2, '', array('id' => $this->uri->segment(5)));
            
            $cliente_id = $datos_comp_cli_per['cliente_id'];
            $descripcion = $datos_comp_cli_per['descripcion'];
            $tipo_documento_id = $datos_comp_cli_per['tipo_documento_id'];
            $moneda_id = $datos_comp_cli_per['moneda_id'];
            $monto = $datos_comp_cli_per['monto'];

            $descuento_global = 0;
            $total_exonerada  = 0;
            $total_inafecta   = 0;
            $total_gravada    = 0;
            $total_igv = 0;
            $total_gratuita = 0;
            $total_otros_cargos = 0;
            $total_descuentos = 0;
            $total_a_pagar = 0;
            $valorIgv = 0.18; //Obtener desde la Base de Datos 
            $importe =  $monto; //ojo analizar la diferencia entre importe y monto y verificar esta igualdar */*/*/*
            $igv     =  $importe * $valorIgv;
            $total   =  $importe + $igv;
            
            $total_gravada = $importe;
            $total_a_pagar = $total;
            $total_igv     = $igv;

            //OBTENIENDO EL TIPO CAMBIO y MONTO TOTAL PARA COMPROBAR Y SI EXISTE DETRACCION
            $montoTotalDetraccion = $total_a_pagar;
            $tipoCambio = NULL;
            if($moneda_id > 1){
                $tipoCambio = $this->tipo_cambio_model->selectJson($moneda_id);
                $tipoCambio = $tipoCambio['tipo_cambio'];

                $montoTotalDetraccion =  $total_a_pagar * $tipoCambio;
            }

            //CALCULANDO DETRACCION SI HUBIESE
            $detraccion = NULL;
            $elemento_adicional_id = NULL;
            $porcentaje_de_detraccion = NULL;
            $total_detraccion = NULL;

            if($tipo_documento_id == 1) {//SIEMPRE SEA FACTURA
                if( $montoTotalDetraccion > 700){
                    $detraccion_valor = $this->variables_diversas_model->porcentaje_detraccion_entero;
                    $detraccion = 1;
                    $elemento_adicional_id = 11;
                    $porcentaje_de_detraccion = $detraccion_valor;
                    $total_detraccion = $montoTotalDetraccion/$detraccion_valor;
                }
            }

            $array = array(
                'cliente_id'       => $cliente_id,
                'tipo_documento_id'=> $tipo_documento_id,
                'serie'            => $serie,
                'numero'           => $numero,
                'fecha_de_emision' => date('Y-m-d'),
                'moneda_id'        => $moneda_id,
                'tipo_de_cambio'   => $tipoCambio,
                'detraccion'       => $detraccion,
                'elemento_adicional_id'    => $elemento_adicional_id,
                'porcentaje_de_detraccion' => $porcentaje_de_detraccion,
                'total_detraccion' => $total_detraccion,
                'descuento_global' => $descuento_global,
                'total_exonerada' => $total_exonerada,
                'total_inafecta'   => $total_inafecta, 
                'total_gravada'  => $total_gravada,
                'total_igv'      => $total_igv,
                'total_gratuita' => $total_gratuita,
                'total_otros_cargos'=> $total_otros_cargos,
                'total_descuentos'  => $total_descuentos,
                'total_a_pagar'     => $total_a_pagar,
                'empresa_id'        => $empresa_id,
                'tipo_pago_id'      => 1,
                'empleado_insert'   => $this->session->userdata('empleado_id'),
                'fecha_insert'      => date("Y-m-d H:i:s")
            );

            $comprobante_id = $this->comprobantes_model->insertar($array);

            //var_dump($comprobante_id);exit;
            $items =  array(
                'comprobante_id' => $comprobante_id,
                'tipo_item_id' => 1,
                'descripcion'  => $descripcion,
                'cantidad'     => 1,
                'tipo_igv_id'  => 1,
                'importe'      => $importe,
                'subtotal'     => $importe,
                'igv'          => $total_igv,
                'total'        => $total_a_pagar
            );                
            $this->items_model->insertar($items);
                                                
            //inserto en tabla comprobantes_comp_cli_per
            $data_comprobantes_comp_cli_per = array(
                'comprobante_id' => $comprobante_id,
                'comp_cli_per_id' => $comp_cli_per_id,
                'emp_insert' => $this->session->userdata('empleado_id'),
                'date_insert' => date('Y-m-d h:i:s')
            );
            $this->comprobantes_comp_cli_per_model->insertar($data_comprobantes_comp_cli_per);
            
            //$this->txt(0,$comprobante_id);
            redirect(base_url().'index.php/comp_cli_per/carga/'. $this->uri->segment(6) . '/' . $this->uri->segment(7));
        }
        
        public function modificarComprobante(){            
            $data['datos'] = $this->comp_cli_per_model->selectConMoneda($this->uri->segment(3));            
            $data['comp_cli_per_id'] = $this->uri->segment(3);
            $data['monedas'] = $this->monedas_model->select();
            $data['anio'] = $this->uri->segment(4);
            $data['mes'] = $this->uri->segment(5);
                        
            $this->accesos_model->menuGeneral();
            $this->load->view('comp_cli_per/modificarComprobante',$data);
            $this->load->view('templates/footer');             
        }
        
        public function modificarComprobante_g(){
            
            $data = array(
                'descripcion' => $_POST['descripcion'],
                'moneda_id' => $_POST['moneda'],
                'monto' => str_replace ( ',' , '' , $_POST['monto'])
            );
            $this->comp_cli_per_model->modificar($_POST['comp_cli_per_id'], $data);
            redirect(base_url().'index.php/comp_cli_per/carga/'. $_POST['anio'] . '/' . $_POST['mes']);
        }
        
        public function eliminar_comp_cli_per($comp_cli_per_id, $anio, $mes){            
            $this->comp_cli_per_model->modificar($comp_cli_per_id, array('fecha_eliminado' => date("Y-m-d H-i-s")));
            redirect(base_url().'index.php/comp_cli_per/carga/'. $anio . '/' . $mes);
        }
    }
?>