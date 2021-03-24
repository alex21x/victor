<?PHP
//use PhpOffice\PhpSpreadsheet\Spreadsheet;
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class resumenes extends CI_Controller {

    public function __construct() {
        parent::__construct();

        date_default_timezone_set('America/Lima');              
        $this->load->model('accesos_model');
        $this->load->model('resumenes_model');
        $this->load->helper('ayuda');
  
        $this->load->model('comprobantes_model');
        $this->load->model('items_model');
        //$this->load->model('igv_model');
        $this->load->model('tipo_igv_model');
        $this->load->model('elemento_adicionales_model');
        $this->load->model('tipo_documentos_model');
        $this->load->model('tipo_pagos_model');
        $this->load->model('tipo_items_model');
        $this->load->model('tipo_ncreditos_model');
        $this->load->model('tipo_ndebitos_model');
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
        $this->load->model('empresas_model');


        $empleado_id = $this->session->userdata('empleado_id');
        $almacen_id = $this->session->userdata("almacen_id");
        if (empty($empleado_id) or empty($almacen_id)) {
            $this->session->set_flashdata('mensaje', 'No existe sesion activa');
            redirect(base_url());
        }
    }
    
    public function index() {
        //$data['categoria'] = $this->Categoria_model->Listarcategoria();        
        $this->accesos_model->menuGeneral();
        $this->load->view('resumenes/basic_index');
        $this->load->view('templates/footer');      
    }           

    public function guardarResumen() {
        $error = array(); 
        if(count($error) > 0)
        {
            $data = ['status'=>STATUS_FAIL,'tipo'=>1, 'errores'=>$error];
            sendJsonData($data);
            exit();
        }

        $result = $this->resumenes_model->guardarResumen();
        if ($result) {
            $this->session->set_flashdata('mensaje', 'Se envio al facturador!');
        } else {
            $this->session->set_flashdata('mensaje', 'ocurrio error al enviar!');
        }
        redirect(base_url() . "index.php/comprobantes/index/" ); 

    }

    public function txtboleta($resumen_id = '') {
        require_once (APPPATH .'libraries/Numletras.php');
        
        $resumen = $this->resumenes_model->getListComprobanteResumen($resumen_id);
        /*print_r($resumen);exit();*/
        $ruta = 'sunat_archivos/sfs/DATA/';        
        $rutaArchivos = DISCO.':\xampp\htdocs'.CARPETA."/".SFS."/sunat_archivos/sfs/DATA/";
        /*nombre archivo*/
        $correlativo= str_pad($resumen[0]->correlativo, 3,'0',STR_PAD_LEFT);        
        $fecha_envio = new DateTime(date('Y-m-d H:m:s'));
        $empresa = $this->db->from('empresas')
                            ->get()
                            ->row();

        $sql = $rutaArchivos . $empresa->ruc. '-RC-'.($fecha_envio->format('Ymd')) .'-'.$correlativo .'.RDI';
        $f = fopen($sql, 'w');

        $sql2 = $rutaArchivos . $empresa->ruc. '-RC-'.($fecha_envio->format('Ymd')) .'-'.$correlativo .'.TRD';
        $f2 = fopen($sql2, 'w');

        $n = 1;
        $linea2 = '';
        foreach ($resumen as $value) {
            $comprobante = $this->comprobantes_model->select($value->comprobante_id);
            $this->db->where("id",$value->comprobante_id);
            $this->db->where("eliminado",0);
            $query = $this->db->get("comprobantes");
            $comprobante1 = $query->row();            
            $items = $this->items_model->select('', $value->comprobante_id);
            $detraccion = $this->elemento_adicionales_model->select('', '', 'activo');        
            $fecha_resumen =  new DateTime($value->resumen_fecha);
            $estado_doc = $value->comprores_estado;

            $fechaHoraEmision = new DateTime($comprobante['fecha_sunat']);
            $fechaVencimiento = new DateTime($comprobante['fecha_de_vencimiento']);
            
            /*cuerpo documento cabecera*/            
        /*1*/$linea .= "{$fechaHoraEmision->format('Y-m-d')}|";//fecha emision
        /*2*/$linea .="{$fecha_resumen->format('Y-m-d')}|"; // fecha generada del resumen
            $linea .= "{$comprobante['tipo_documento_codigo']}|";//codigo tipo documento
            $linea .= "{$comprobante['serie']}"."-"."{$comprobante['numero']}|";//serie y numero de documento                
            $linea .= "{$comprobante['tipo_cliente_codigo']}|";//tipo de documento de identidad
            $linea .= trim("{$comprobante['cliente_ruc']}")."|";//:numero de documento identidad                
            $linea .= "{$comprobante['abrstandar']}|";//:tipo de moneda
            $linea .= "{$comprobante['total_gravada']}|";//:total valor venta - operaciones gravadas
            $linea .= "{$comprobante['total_exonerada']}|";//:total valor venta - operaciones exonerado
    /*10*/  $linea .= "{$comprobante['total_inafecta']}|";//:total valor venta - operaciones inafecta

            if($comprobante['tipo_operacion']=='0200'){
                $linea .= "{$comprobante['total_a_pagar']}|";//:total valor venta - operaciones gratuitas
            }else{
                $linea .= "0.00|";//:total valor venta - operaciones gratuitas
            }

       /*12*/$linea .= "{$comprobante['total_gratuita']}|";//:total valor venta - operaciones gratuitas
       /*13*/$linea .= ($comprobante['total_otros_cargos'] + $comprobante['total_icbper'])."|";//:total valor venta - operaciones otros cargos 
            $linea .= "{$comprobante['total_a_pagar']}|";//total precio venta
      /*15*/ $linea .="|"; //monto triblso 


            
            $linea .="|"; //Tipo de documento que modifica
            $linea .="|"; //Número de serie de la boleta de venta que modifica
            $linea .="|"; //Número correlativo de la boleta de venta que modifica


            $linea .="0|"; //Porcentaje de Percepcion
            $linea .="0|"; //Base imponible percepción 
            $linea .="0|"; //Monto de la percepción 
            $linea .="0|"; //Monto total a cobrar incluida la
            $linea .="{$estado_doc}|\r\n"; //Estado del documento  

              $linea2.=  $n."|";
            //si tributo es igv
                if($comprobante['total_gravada'] > 0)
                {
                    $linea2.= "1000|";//Identificador de tributo
                    $linea2 .= "IGV|";//Nombre de tributo
                    $linea2 .= "VAT|";//Código de tipo de tributo
                    $linea2 .= "{$comprobante['total_gravada']}|";//Base imponible
                    $linea2 .= "{$comprobante['total_igv']}|\r\n";//Monto de Tirbuto por ítem
                                    
                }
                //si tributo es exonerada
                if($comprobante['total_exonerada'] > 0)
                {
                    $linea2.= "9997|";//Identificador de tributo
                    $linea2 .= "EXO|";//Nombre de tributo
                    $linea2 .= "VAT|";//Código de tipo de tributo
                    $linea2 .= "{$comprobante['total_exonerada']}|";//Base imponible
                    $linea2 .= "0|\r\n";//Monto de Tirbuto por ítem
                                  
                } 
                //si tributo es inafecto
                if($comprobante['total_inafecta'] > 0)
                {
                   if($comprobante1->tipo_operacion=='0101'){
                        $linea2 .= "9998|";//Identificador de tributo
                        $linea2 .= "INA|";//Nombre de tributo
                        $linea2 .= "FRE|";//Código de tipo de tributo
                        $linea2 .= "{$comprobante['total_inafecta']}|";//Base imponible
                        $linea2 .= "0|\r\n";//Monto de Tirbuto por ítem
                         
                    }else{
                        $linea2 .= "9995|";//Identificador de tributo
                        $linea2 .= "EXP|";//Nombre de tributo
                        $linea2 .= "FRE|";//Código de tipo de tributo
                        $linea2 .= "{$comprobante['total_gratuita']}|";//Base imponible
                        $linea2 .= "0|\r\n";//Monto de Tirbuto por ítem
                       
                    }                     
                }
                //si tributo es gratuita/exportacion
                if($comprobante['total_gratuita'] > 0)
                {
                    $linea2 .= "9996|";//Identificador de tributo
                    $linea2 .= "GRA|";//Nombre de tributo
                    $linea2 .= "FRE|";//Código de tipo de tributo
                    $linea2 .= "{$comprobante['total_gratuita']}|";//Base imponible
                    $linea2 .= "0|\r\n";//Monto de Tirbuto por ítem
                                 
                }                                                

                $n++;
            if ($estado_doc == 3) {
                
                $fecha2 = date("Y-m-d");
                $num = $this->comprobante_anulados_model->maxNumero($fecha2) + 1;
                $dataAnular = array(
                    'fecha' => $fecha2,
                    'numero' => $num,
                    'comprobante_id' => $value->comprobante_id,
                    'empleado_insert' => $this->session->userdata('empleado_id'),
                    'fecha_insert' => date("Y-m-d H:i:s")
                );
                $this->comprobante_anulados_model->insertar($dataAnular);
                $this->comprobantes_model->modificar(array('enviado_sunat' => 1, 'anulado' =>1), $value->comprobante_id);                

            } else {
                $this->comprobantes_model->Modificar_boleta($value->comprobante_id);
            }

        }
        fwrite($f2, $linea2);    
        fwrite($f, $linea);
        fclose($f);     
         fclose($f2);   
        
      
  
         $this->resumenes_model->Guardar_Resumen($resumen_id);
        $this->session->set_flashdata('mensaje', 'Resumen enviado!');
        redirect(base_url() . "index.php/comprobantes/index/" . $empresa->id);
    }
    

    public function getMainList()
    {
        $rsDatos = $this->resumenes_model->getMainList();
        sendJsonData($rsDatos);
    }
    public function getMainListDetail() {
        $rsDatos = $this->resumenes_model->getMainListDetail();
        sendJsonData($rsDatos);     
    }





}




?>

