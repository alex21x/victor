<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use Endroid\QrCode\QrCode;
/*require __DIR__ . '/../ticket/autoload.php';*/
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\Printer;
//use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class Libros_electronicos extends CI_Controller {

    public function __construct() {
        parent::__construct();

        date_default_timezone_set('America/Lima');
        $this->load->model('comprobantes_model');
        $this->load->model('comprobantes_compras_model');
        $this->load->model('compras_model');
        $this->load->model('items_model');
        //$this->load->model('igv_model');
        $this->load->model('tipo_igv_model');
        $this->load->model('elemento_adicionales_model');
        $this->load->model('tipo_documentos_model');
        $this->load->model('tipo_pagos_model');
        $this->load->model('tipo_items_model');
        $this->load->model('tipo_ncreditos_model');
        $this->load->model('tipo_ndebitos_model');
        //$this->load->model('activos_model');
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

        $this->load->model('libros_electronicos_model');



        $this->load->helper('ayuda');

        $data_actual = strtotime(date("Y-m-d"));
        if($data_actual >= strtotime('2019-05-05')){
            //echo "actualizar datos";exit;
        }

        $empleado_id = $this->session->userdata('empleado_id');
        $almacen_id = $this->session->userdata("almacen_id");
        if (empty($empleado_id) or empty($almacen_id)) {
            $this->session->set_flashdata('mensaje', 'No existe sesion activa');
            redirect(base_url());
        }
    }

    public function ventas(){
      $this->accesos_model->menuGeneral();
      $this->load->view('Libros_electronicos/ventas');
      $this->load->view('templates/footer');
    }

    public function compras(){
      $this->accesos_model->menuGeneral();
      $this->load->view('Libros_electronicos/compras');
      $this->load->view('templates/footer');
    }

    public function borrar_le(){

      $tipo = $this->input->post("tipo");
      $mes = $this->input->post("mes");
      $año = $this->input->post("anio");
      $indicador_operaciones = $this->input->post("operaciones");

      //// DESCIFRANDO CARPETA ///
      $mes_nombre = array('01'=>'ENERO','02'=>'FEBRERO','03'=>'MARZO','04'=>'ABRIL','05'=>'MAYO','06'=>'JUNIO','07'=>'JULIO','08'=>'AGOSTO','09'=>'SETIEMBRE','10'=>'OCTUBRE','11'=>'NOVIEMBRE','12'=>'DICIEMBRE');
      $carpeta_principal = DISCO.':/'.CARPETA_LE.'/LE PERIODO '.$mes_nombre[$mes].' '.$año;
      $carpeta = $carpeta_principal.(($tipo==1)?'\NORMAL':'\SIMPLIFICADO');

            $files = glob($carpeta.'/*'); //obtenemos todos los nombres de los ficheros
            foreach($files as $file){
                if(is_file($file))
                unlink($file); //elimino el fichero
            }

            echo 1;
    }



    public function generar_leventas(){
      $tipo = $this->input->post("tipo");
      $mes = $this->input->post("mes");
      $año = $this->input->post("anio");
      $indicador_operaciones = $this->input->post("operaciones");

      $where = array(
           'mes' => $mes,
           'anio' => $año,
           'libro_id' => ($tipo==1)?'140100':'140200'
      );
      $json = $this->libros_electronicos_model->select_le($where);

      //if(count($json)<1){

            $array_where = array(
               'MONTH(com.fecha_de_emision)' => $mes,
               'YEAR(com.fecha_de_emision)' => $año
            );

            $jsonComprobantes = $this->comprobantes_model->getComprobantes($array_where);
            $jsonEmpresa = $this->empresas_model->getEmpresa();

            $n = 0;
            $venta = '';
            $monto = 0;
            //print_r($jsonComprobantes);exit();
            foreach($jsonComprobantes as $value){
               $n++;

              /*1*/ $periodo = date('Y',strtotime($value->fecha_de_emision)).date('m',strtotime($value->fecha_de_emision)).'00';
              /*2*/ $cuo = str_pad($n, 4, "0", STR_PAD_LEFT);  //// CUO CORRELATIVO
              /*3*/ $cuo_diario = 'M'.$cuo; //// A:ASIENTO DE APERTURA,M:SIENTO DE MOVIMIENTO,C:ASIENTO DE CIERRE
              /*4*/ $fecha_emision = date('d/m/Y',strtotime($value->fecha_de_emision));
              /*5*/ $fecha_vencimiento = date('d/m/Y',strtotime($value->fecha_de_vencimiento));
              /*6*/ $tip_documento = $value->tipo_documento_codigo;
              /*7*/ $serie = strtoupper($value->serie);

              /*8*/ $num_comp = $value->numero;
              /*9*/ $num_comp_final = ''; //OPERACIONES DIARIAS QUE NO OTORGUEN DERECHO A CREDITO FISCAL, NO OBLIGATORIO
             /*10*/ $doc_prov = $value->tipo_cliente_codigo; ///RUC
             /*11*/ $num_prov = $value->ruc;
             /*12*/ $razon_social = strtoupper($value->razon_social);
 
              /*13*/ $valor_exportacion = '';
             if($value->tipo_cliente_codigo==0 or $value->tipo_cliente_codigo==4 or $value->tipo_cliente_codigo==7){
                /*13*/ $valor_exportacion = $value->total_a_pagar;
             }

            /*14*/ $base_imponible_gravada = $value->total_gravada;
            /*15*/ $dscto_base_imponible = '';
            /*16*/ $igv = $value->total_igv;
            /*17*/ $dscto_igv = ''; //// DESCTO IGV O IMPUESTO DE PROMOCIÓN MUNICIPAL
            /*18*/ $total_exonerada = $value->total_exonerada;
            /*19*/ $total_inafecta = $value->total_inafecta;

            /*20*/ $isc = ''; //// IMPUESTO SELECTIVO AL CONSUMO
            /*21*/ $base_imponible_gravada_sp = ''; /// VENTAS DE ARROZ PILADO
            /*22*/ $ivsp = ''; //// IMPUESTO A LA VENTA DE ARROZ PILADO
            /*23*/ $total_otros = $value->total_otros_cargos;
            /*24*/ $importe_total = $value->total_a_pagar;

            /*25*/ $moneda = $value->abrstandar;
            /*26*/ $tip_cambio = ($moneda!='PEN')?($this->tipo_cambio_model->selectJson($value->moneda_id))['tipo_cambio']:'1.000';


            /*27*/ $modifica_1 = '';
            /*28*/ $modifica_2 = '';
            /*29*/ $modifica_3 = '';
            /*30*/ $modifica_4 = '';
            if($value->tipo_documento_codigo=='07' or $value->tipo_documento_codigo=='08'){
               $this->db->where('id',$value->com_adjunto_id);
               $doc_adjunto = $this->db->get('comprobantes')->row();

                /*13*/ $valor_exportacion = '';
                     if($value->tipo_cliente_codigo==0 or $value->tipo_cliente_codigo==4 or $value->tipo_cliente_codigo==7){
                        /*13*/ $valor_exportacion = -$value->total_a_pagar;
                     }

                      /*14*/ $base_imponible_gravada = -$value->total_gravada;
                      /*15*/ $dscto_base_imponible = '';
                      /*16*/ $igv = -$value->total_igv;
                      /*17*/ $dscto_igv = ''; //// DESCTO IGV O IMPUESTO DE PROMOCIÓN MUNICIPAL
                      /*18*/ $total_exonerada = -$value->total_exonerada;
                      /*19*/ $total_inafecta = -$value->total_inafecta;

                      /*20*/ $isc = ''; //// IMPUESTO SELECTIVO AL CONSUMO
                      /*21*/ $base_imponible_gravada_sp = ''; /// VENTAS DE ARROZ PILADO
                      /*22*/ $ivsp = ''; //// IMPUESTO A LA VENTA DE ARROZ PILADO
                      /*23*/ $total_otros = -$value->total_otros_cargos;
                      /*24*/ $importe_total = floatval(-$value->total_a_pagar);

               /*27*/ $modifica_1 = date('d',strtotime($doc_adjunto->fecha_de_emision)).'/'.date('m',strtotime($doc_adjunto->fecha_de_emision)).'/'.date('Y',strtotime($doc_adjunto->fecha_de_emision));
               /*28*/ $modifica_2 = '0'.$doc_adjunto->tipo_documento_id;
               /*29*/ $modifica_3 = $doc_adjunto->serie;
               /*30*/ $modifica_4 = $doc_adjunto->numero;

            }

            if($value->anulado==1){
                 /*13*/ $valor_exportacion = '';
                     if($value->tipo_cliente_codigo==0 or $value->tipo_cliente_codigo==4 or $value->tipo_cliente_codigo==7){
                        /*13*/ $valor_exportacion = 0;
                     }

                      /*14*/ $base_imponible_gravada =0;
                      /*15*/ $dscto_base_imponible = '';
                      /*16*/ $igv = 0;
                      /*17*/ $dscto_igv = ''; //// DESCTO IGV O IMPUESTO DE PROMOCIÓN MUNICIPAL
                      /*18*/ $total_exonerada = 0;
                      /*19*/ $total_inafecta = 0;

                      /*20*/ $isc = ''; //// IMPUESTO SELECTIVO AL CONSUMO
                      /*21*/ $base_imponible_gravada_sp = ''; /// VENTAS DE ARROZ PILADO
                      /*22*/ $ivsp = ''; //// IMPUESTO A LA VENTA DE ARROZ PILADO
                      /*23*/ $total_otros = 0;
                      /*24*/ $importe_total = 0;
            }

            /*31*/ $joint_ventures = '';
            /*32*/ $error_tipo_1 = '';
            /*33*/ $medio_pago = '1'; /// 1:CANCELADO CON ALGUN MEDIO DE PAGO DE LA TABLA 1
            /*34*/ $estado = '1';

              if($tipo==1){ //// NORMAL
                $venta.= $periodo.'|'.$cuo.'|'.$cuo_diario.'|'.$fecha_emision.'|'.$fecha_vencimiento.'|'.$tip_documento.'|'.$serie.'|';
                $venta.= $num_comp.'|'.$num_comp_final.'|'.$doc_prov.'|'.$num_prov.'|'.$razon_social.'|'.$valor_exportacion.'|';
                $venta.= $base_imponible_gravada.'|'.$dscto_base_imponible.'|'.$igv.'|'.$dscto_igv.'|'.$total_exonerada.'|'.$total_inafecta.'|';
                $venta.= $isc .'|'.$base_imponible_gravada_sp.'|'.$ivsp.'|'.$total_otros.'|'.$importe_total.'|'.$moneda.'|'.$tip_cambio.'|'.$modifica_1.'|'.$modifica_2.'|';
                $venta.= $modifica_3.'|'.$modifica_4.'|'.$joint_ventures.'|'.$error_tipo_1.'|'.$medio_pago.'|'.$estado.'|'."\n";
              }else{ ///SIMPLIFICADO
                $venta.= $periodo.'|'.$cuo.'|'.$cuo_diario.'|'.$fecha_emision.'|'.$fecha_vencimiento.'|'.$tip_documento.'|'.$serie.'|';
                $venta.= $num_comp.'|'.$num_comp_final.'|'.$doc_prov.'|'.$num_prov.'|'.$razon_social.'|';
                $venta.= $base_imponible_gravada.'|'.$igv.'|';
                $venta.= $total_otros.'|'.$importe_total.'|'.$moneda.'|'.$tip_cambio.'|'.$modifica_1.'|'.$modifica_2.'|';
                $venta.= $modifica_3.'|'.$modifica_4.'|'.$error_tipo_1.'|'.$medio_pago.'|'.$estado.'|'."\n";
              }

              $monto = $monto + floatval($importe_total);
            }

            $id_fijo = 'LE';
            $ruc = $jsonEmpresa[0]->ruc;
            $dia = '00'; /// SOLO APLICA AL LIBRO DE INVENTARIOS Y BALANCE
            $id_libro = ($tipo==1)?'140100':'140200'; /// 1:REGISTRO DE VENTAS,2:REGISTRO DE VENTAS SIMPLIFICADO
            $cod_oportunidad = '00'; /// SOLO APLICA AL LIBRO DE INVENTARIOS Y BALANCE
            $indicador_contenido = ($n!=0)?'1':'0'; /// 1:CON INFORMACION,0:SIN INFORMACION
            $moneda = ($jsonComprobantes[0]->moneda_id!='')?$jsonComprobantes[0]->moneda_id:1; /// SOLES
            $id_le = '1'; /// GENERADO POR PLE

            $mes_nombre = array('01'=>'ENERO','02'=>'FEBRERO','03'=>'MARZO','04'=>'ABRIL','05'=>'MAYO','06'=>'JUNIO','07'=>'JULIO','08'=>'AGOSTO','09'=>'SETIEMBRE','10'=>'OCTUBRE','11'=>'NOVIEMBRE','12'=>'DICIEMBRE');
            //// CREANDO CARPETA ///
            $carpeta_principal = DISCO.':/'.CARPETA_LE.'/LE PERIODO '.$mes_nombre[$mes].' '.$año;
            $carpeta_lv = $carpeta_principal.(($tipo==1)?'/NORMAL':'/SIMPLIFICADO');
            if(!file_exists($carpeta_principal)){mkdir($carpeta_principal, 0777, true);}
            if(!file_exists($carpeta_lv)){mkdir($carpeta_lv, 0777, true);}

            //// ARCHIVOS DUPLICADOS ////
            /*$files = glob($carpeta_lv.'/*'); //obtenemos todos los nombres de los ficheros
            foreach($files as $file){
                if(is_file($file))
                unlink($file); //elimino el fichero
            }*/

            ///// CREANDO ARCHIVO ////
            $nombre = $id_fijo.$ruc.$año.$mes.$dia.$id_libro.$cod_oportunidad.$indicador_operaciones.$indicador_contenido.$moneda.$id_le;
            $archivo = fopen($carpeta_lv.'/'.$nombre.'.txt','a+');
            fwrite($archivo,$venta);


            /////////// GUARDAR TABLA  ///////////////
            $data = array(
                       'fecha_insert' => date('Y-m-d H:i:s'),
                       'mes' => $mes,
                       'anio' => $año,
                       'libro_id' => $id_libro,
                       'libro_nombre' => $nombre,
                       'total' => $monto,
                       'empleado_id' => $this->session->userdata('empleado_id')
                   );

            $this->libros_electronicos_model->insert_le($data);

             $zip['le_mes'] = $mes_nombre[$mes];
             $zip['le_anio'] = $año;
             $zip['le_tipo'] = ($tipo==1)?'NORMAL':'SIMPLIFICADO';
             $zip['le_libro'] = $nombre;
             $zip['le_libro2'] = "-";

             echo json_encode($zip);
           


  

      /*}else{
        echo 'lleno';
      }*/

    }

    public function generar_lecompras(){
      $tipo = $this->input->post("tipo");
      $mes = $this->input->post("mes");
      $año = $this->input->post("anio");
      $indicador_operaciones = $this->input->post("operaciones");

      $where = array(
           'mes' => $mes,
           'anio' => $año,
           'libro_id' => ($tipo==1)?'080100':'080300'
      );
      $json = $this->libros_electronicos_model->select_le($where);

      //if(count($json)<1){

              $array_where = array(
                 'com.tipo_operacion' => '0101',
                 'MONTH(com.fecha_de_emision)' => $mes,
                 'YEAR(com.fecha_de_emision)' => $año
              );

              $jsonCompras = $this->comprobantes_compras_model->getCompras($array_where);
              $jsonEmpresa = $this->empresas_model->getEmpresa();

              $n = 0;
              $compra = '';
              $monto = 0;
              //print_r($jsonCompras);exit();
              foreach($jsonCompras as $value){
                $n++;
                /*1*/ $periodo = date('Y',strtotime($value->fecha_de_emision)).date('m',strtotime($value->fecha_de_emision)).'00';
                /*2*/ $cuo = str_pad($n, 4, "0", STR_PAD_LEFT);
                /*3*/ $cuo_diario = 'M'.$cuo; /// M:ASIENTOS DE MOVIMIENTO
                /*4*/ $fecha_emision = date('d/m/Y',strtotime($value->fecha_de_emision));
                /*5*/ $fecha_vencimiento = date('d/m/Y',strtotime($value->fecha_de_vencimiento));
                /*6*/ $tip_documento = $value->tipo_documento_codigo; ////01:FACTURA, 03:BOLETA
                /*7*/ $serie = strtoupper($value->serie);

                /*8*/ $año_dua = ''; //// ADUANAS, NO OBLIGATORIO
                /*9*/ $num_comp = $value->numero;
              /*10*/ $num_comp_final = ''; //// OPERACIONES DIARIAS QUE NO OTORGUEN CRÉDITO FISCAL, NO OBLIGATORIO
              /*11*/ $doc_prov = '6'; //// RUC
              /*12*/ $num_prov = $value->prov_ruc;
              /*13*/ $razon_social = strtoupper($value->prov_razon_social);
              /*14*/ $base_imponible_1 =$value->total_gravada;

              /*15*/ $igv_1 = $value->total_igv;
              /*16*/ $base_imponible_2 = '';
              /*17*/ $igv_2 = '';
              /*18*/ $base_imponible_3 = '';
              /*19*/ $igv_3 = '';
              /*20*/ $no_gravadas = '';

              /*21*/ $isc = '';  //// MONTO IMPUESTO SELECTIVO AL CONSUMO
              /*22*/ $otros = '';

              /*23*/ $importe_total = $value->total_a_pagar;
              /*24*/ $moneda = $value->abrstandar;
              /*25*/ $tip_cambio = ($moneda!='PEN')?($this->tipo_cambio_model->selectJson($value->moneda_id))['tipo_cambio']:'1.000';

              /*26*/ $modifica_1 = '';
              /*27*/ $modifica_2 = '';
              /*28*/ $modifica_3 = '';
              /*29*/ $modifica_4 = '';
              /*30*/ $modifica_5 = '';
              if($tip_documento=='07' or $tip_documento=='08'){
                 /*26*/ $modifica_1 = date('d/m/Y',strtotime($value->adjunto_fecha));
                /*27*/ $modifica_2 = $value->tipo_documento_codigo;
                /*28*/ $modifica_3 = $value->adjunto_serie;
                /*29*/ $modifica_4 = '';
                /*30*/ $modifica_5 = $value->adjunto_numero;

                if($tip_documento=='07'){
                   /*23*/ $importe_total = floatval(-$value->total_a_pagar);
                }
              }

             

              /*31*/ $detracciones_1 = '';
              /*32*/ $detracciones_2 = '';
              /*33*/ $marca_retencion = '';
              /*34*/ $bienes = '';
              /*35*/ $joint_ventures = '';
              /*36*/ $error_tipo_1 = '';
              /*37*/ $error_tipo_2 = '';
              /*38*/ $error_tipo_3 = '';
              /*39*/ $error_tipo_4 = '';
              /*40*/ $medio_pago = '1'; ///// 1: medio de pago
              /*41*/ $estado = ($value->tipo_documento_id==1)?'1':'0';

                if($tipo==1){ //// NORMAL
                  $compra.= $periodo.'|'.$cuo.'|'.$cuo_diario.'|'.$fecha_emision.'|'.$fecha_vencimiento.'|'.$tip_documento.'|'.$serie.'|';
                  $compra.= $año_dua.'|'.$num_comp.'|'.$num_comp_final.'|'.$doc_prov.'|'.$num_prov.'|'.$razon_social.'|'.$base_imponible_1.'|';
                  $compra.= $igv_1.'|'.$base_imponible_2.'|'.$igv_2.'|'.$base_imponible_3.'|'.$igv_3.'|'.$no_gravadas.'|'.$isc.'|'.$otros.'|';
                  $compra.= $importe_total.'|'.$moneda.'|'.$tip_cambio.'|'.$modifica_1.'|'.$modifica_2.'|'.$modifica_3.'|'.$modifica_4.'|';
                  $compra.= $modifica_5.'|'.$detracciones_1.'|'.$detracciones_2.'|'.$marca_retencion.'|'.$bienes.'|'.$joint_ventures.'|';
                  $compra.= $error_tipo_1.'|'.$error_tipo_2.'|'.$error_tipo_3.'|'.$error_tipo_4.'|'.$medio_pago.'|'.$estado.'|'."\n";
                }else{ //// SIMPLIFICADO
                  $compra.= $periodo.'|'.$cuo.'|'.$cuo_diario.'|'.$fecha_emision.'|'.$fecha_vencimiento.'|'.$tip_documento.'|'.$serie.'|';
                  $compra.= $num_comp.'|'.$num_comp_final.'|'.$doc_prov.'|'.$num_prov.'|'.$razon_social.'|'.$base_imponible_1.'|';
                  $compra.= $igv_1.'|'.$otros.'|';
                  $compra.= $importe_total.'|'.$moneda.'|'.$tip_cambio.'|'.$modifica_1.'|'.$modifica_2.'|'.$modifica_3.'|'.$modifica_4.'|';
                  $compra.= $detracciones_1.'|'.$detracciones_2.'|'.$marca_retencion.'|'.$bienes.'|';
                  $compra.= $error_tipo_1.'|'.$error_tipo_2.'|'.$error_tipo_3.'|'.$medio_pago.'|'.$estado.'|'."\n";
                }

                $monto = $monto + floatval($importe_total); /////TOTAL

              }

              $id_fijo = 'LE';
              $ruc = $jsonEmpresa[0]->ruc;
              $dia = '00'; /// SOLO APLICA AL LIBRO DE INVENTARIOS Y BALANCE
              $id_libro = ($tipo==1)?'080100':'080300'; /// 1:REGISTRO DE VENTAS,2:REGISTRO DE VENTAS SIMPLIFICADO
              $cod_oportunidad = '00'; /// SOLO APLICA AL LIBRO DE INVENTARIOS Y BALANCE
              $indicador_contenido = ($n!=0)?'1':'0'; /// 1:CON INFORMACION,0:SIN INFORMACION
              $moneda = '1'; /// SOLES
              $id_le = '1'; /// GENERADO POR PLE

              $mes_nombre = array('01'=>'ENERO','02'=>'FEBRERO','03'=>'MARZO','04'=>'ABRIL','05'=>'MAYO','06'=>'JUNIO','07'=>'JULIO','08'=>'AGOSTO','09'=>'SETIEMBRE','10'=>'OCTUBRE','11'=>'NOVIEMBRE','12'=>'DICIEMBRE');
              //// CREANDO CARPETA ///
              $carpeta_principal = DISCO.':/'.CARPETA_LE.'/LE PERIODO '.$mes_nombre[$mes].' '.$año;
              $carpeta_lc = $carpeta_principal.(($tipo==1)?'/NORMAL':'/SIMPLIFICADO');
              if(!file_exists($carpeta_principal)){mkdir($carpeta_principal, 0777, true);}
              if(!file_exists($carpeta_lc)){mkdir($carpeta_lc, 0777, true);}

              //// ARCHIVOS DUPLICADOS ///
              /*$files = glob($carpeta_lc.'/*'); //obtenemos todos los nombres de los ficheros
              foreach($files as $file){
                  if(is_file($file))
                  unlink($file); //elimino el fichero
              }*/

              //// CREANDO ARCHIVO ///
              $nombre = $id_fijo.$ruc.$año.$mes.$dia.$id_libro.$cod_oportunidad.$indicador_operaciones.$indicador_contenido.$moneda.$id_le;
              $archivo = fopen($carpeta_lc.'/'.$nombre.'.txt','a+');
              fwrite($archivo,$compra);

              /////////// GUARDAR TABLA  ///////////////
              $data = array(
                         'fecha_insert' => date('Y-m-d H:i:s'),
                         'mes' => $mes,
                         'anio' => $año,
                         'libro_id' => $id_libro,
                         'libro_nombre' => $nombre,
                         'total' => $monto,
                         'empleado_id' => $this->session->userdata('empleado_id')
                      );
              $this->libros_electronicos_model->insert_le($data);

             $zip['le_mes'] = $mes_nombre[$mes];
             $zip['le_anio'] = $año;
             $zip['le_tipo'] = ($tipo==1)?'NORMAL':'SIMPLIFICADO';
             $zip['le_libro'] = $nombre;
             $zip['le_libro2'] = "-";

              if($tipo==1){

                $array_where = array(
                 'com.tipo_operacion' => '0200',
                 'MONTH(com.fecha_de_emision)' => $mes,
                 'YEAR(com.fecha_de_emision)' => $año
                );

                $jsonCompras = $this->comprobantes_compras_model->getCompras($array_where);
                $jsonEmpresa = $this->empresas_model->getEmpresa();

                $n = 0;
                $compra = '';
                $monto = 0;
                //print_r($jsonCompras);exit();
                foreach($jsonCompras as $value){
                  $n++;
                  /*1*/ $periodo = date('Y',strtotime($value->fecha_de_emision)).date('m',strtotime($value->fecha_de_emision)).'00';
                  /*2*/ $cuo = str_pad($n, 4, "0", STR_PAD_LEFT);
                  /*3*/ $cuo_diario = 'M'.$cuo; /// M:ASIENTOS DE MOVIMIENTO
                  /*4*/ $fecha_emision = date('d/m/Y',strtotime($value->fecha_de_emision));

                  /*6*/ $tip_documento = '91';//$value->tipo_documento_codigo; ////01:FACTURA, 03:BOLETA
                  /*7*/ $serie = strtoupper($value->serie);
                 /*8*/       $numero = $value->numero;

                  /*9*/ $valor = $value->total_gravada;
                        $otros = '';
                        $importe_total = $value->total_a_pagar;

                        $tipo_comp = '';
                        $seriedua = '';
                        $añodua = '';
                        $numerodua = '';

                        $retencion = "";
                        $moneda = $value->abrstandar;
                        $tip_cambio = $value->tipo_de_cambio;
              /*18*/    $pais = '9249';
                        $nombre = strtoupper($value->prov_razon_social);
                        $dir = strtoupper($value->direccion_cliente);
              /*21*/    $numdoc = $value->prov_ruc;
                        $numbene = '';
                        $bene = '';
                        $paisbene = '';
                        $vinculo = '';

                        $rentabruta = '';
                        $deduccion = '';
                        $rentaneta = '';
                        $tasaretencion = '';
                        $impuestoretenido = '';
                        $convenio = '00';
                        $exoneracion = '';
                        $tiporenta = '00';
                        $modalidad = '';
                        $ley = '';
                        $estado = '0';


            
                    $compra.= $periodo.'|'.$cuo.'|'.$cuo_diario.'|'.$fecha_emision.'|'.$tip_documento.'|'.$serie.'|'.$numero.'|';
                    $compra.= $valor.'|'.$otros.'|'.$importe_total.'|'.$tipo_comp.'|'.$seriedua.'|'.$añodua.'|'.$numerodua.'|';
                    $compra.= $retencion.'|'.$moneda.'|'.$tip_cambio.'|'.$pais.'|'.$nombre.'|'.$dir.'|'.$numdoc.'|';
                    $compra.= $numbene.'|'.$bene.'|'.$paisbene.'|'.$vinculo.'|'.$rentabruta.'|'.$deduccion.'|'.$rentaneta.'|'.$tasaretencion.'|'.$impuestoretenido.'|';
                    $compra.= $convenio.'|'.$exoneracion.'|'.$tiporenta.'|'.$modalidad.'|'.$ley.'|'.$estado.'|'."\n";
               
                  $monto = $monto + floatval($importe_total); /////TOTAL

                }
                /////// REGISTRO DE COMPRAS SUJETOS NO DOMICILIADOS
                $id_libro = '080200'; /// REGISTRO DE COMPRAS NO DOMICILIADOS
                $indicador_contenido = ($n!=0)?'1':'0'; /// 1:CON INFORMACION,0:SIN INFORMACION
                $moneda = '2'; /// SOLES
                $id_le = '1'; /// GENERADO POR PLE

                $nombre = $id_fijo.$ruc.$año.$mes.$dia.$id_libro.$cod_oportunidad.$indicador_operaciones.$indicador_contenido.$moneda.$id_le;
                $archivo = fopen($carpeta_lc.'/'.$nombre.'.txt','a+');
                fwrite($archivo,$compra);

                 $data =  array(
                           'fecha_insert' => date('Y-m-d H:i:s'),
                           'mes' => $mes,
                           'anio' => $año,
                           'libro_id' => '080200',
                           'libro_nombre' => $nombre,
                           'total' => $monto ,
                           'empleado_id' => $this->session->userdata('empleado_id')
                        );

                 $this->libros_electronicos_model->insert_le($data);

                  $zip['le_libro2'] = $nombre;
            }

            

            

             echo json_encode($zip);
           

         

    }

    public function listar_lecompras(){
      $fecha_insert_1 = $this->input->get('fecha_insert_1');
      $fecha_insert_2 = $this->input->get('fecha_insert_2');
      $select_mes = $this->input->get('select_mes');
      $select_anio = $this->input->get('select_anio');
      $json = $this->libros_electronicos_model->listar_lecompras($fecha_insert_1,$fecha_insert_2,$select_mes,$select_anio);
      echo json_encode($json);
    }

    public function listar_leventas(){
       $fecha_insert_1 = $this->input->get('fecha_insert_1');
       $fecha_insert_2 = $this->input->get('fecha_insert_2');
       $select_mes = $this->input->get('select_mes');
       $select_anio = $this->input->get('select_anio');
       $json = $this->libros_electronicos_model->listar_leventas($fecha_insert_1,$fecha_insert_2,$select_mes,$select_anio);
       echo json_encode($json);
    }

    public function listar_le(){
       $fecha_insert_1 = $this->input->get('fecha_insert_1');
       $fecha_insert_2 = $this->input->get('fecha_insert_2');
       $select_mes = $this->input->get('select_mes');
       $select_anio = $this->input->get('select_anio');
       $json = $this->libros_electronicos_model->listar_le($fecha_insert_1,$fecha_insert_2,$select_mes,$select_anio);
       echo json_encode($json);
    }

    public function verificar_comprobante_le(){
      $nombre = $this->input->post('nombre');
      $mes = $this->input->post('mes');
      $año = $this->input->post('anio');
      $mes_nombre = array('01'=>'ENERO','02'=>'FEBRERO','03'=>'MARZO','04'=>'ABRIL','05'=>'MAYO','06'=>'JUNIO','07'=>'JULIO','08'=>'AGOSTO','09'=>'SETIEMBRE','10'=>'OCTUBRE','11'=>'NOVIEMBRE','12'=>'DICIEMBRE');
      $pdf = DISCO.':/'.CARPETA_LE.'/LE PERIODO '.$mes_nombre[$mes].' '.$año.'/'.$nombre.'01.pdf';
      if(file_exists($pdf)){
         echo 1;
      }else{
        echo 0;
      }
    }

    public function descargar_le($le,$mes,$año,$tipo,$libro,$libro2){

            // Creamos un instancia de la clase ZipArchive
             $zip = new ZipArchive();
            // Creamos y abrimos un archivo zip temporal
             $nombre_zip = "LE ".$le." PERIODO ".$mes." ".$año.".zip";
             $zip->open($nombre_zip,ZipArchive::CREATE);
             // Añadimos un directorio
             $dir = $tipo;
             $zip->addEmptyDir($dir);
             // Añadimos un archivo en la raid del zip.
             //$zip->addFile("imagen1.jpg","mi_imagen1.jpg");
             //Añadimos un archivo dentro del directorio que hemos creado
             $archivo = DISCO.":/".CARPETA_LE."/LE PERIODO ".$mes." ".$año."/".$tipo."/".$libro.".txt";
             $zip->addFile($archivo,$dir."/".$libro.".txt");

             if($le=="COMPRAS" and $tipo=="NORMAL"){
                $archivo2 = DISCO.":/".CARPETA_LE."/LE PERIODO ".$mes." ".$año."/".$tipo."/".$libro2.".txt";
                $zip->addFile($archivo2,$dir."/".$libro2.".txt");
             }
             // Una vez añadido los archivos deseados cerramos el zip.
             $zip->close();
             // Creamos las cabezeras que forzaran la descarga del archivo como archivo zip.
             header("Content-type: application/octet-stream");
             header("Content-disposition: attachment; filename=".$nombre_zip);
             // leemos el archivo creado
             readfile($nombre_zip);
             // Por último eliminamos el archivo temporal creado
             unlink($nombre_zip);//Destruye el archivo temporal

    }



  }
