<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comprobantes_compras_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        
    //        $sesion_empleado_id = $this->session->userdata('empleado_id');
    //        if(empty($sesion_empleado_id)){
    //            $this->session->set_flashdata('mensaje', 'No existe sessión activa');
    //            redirect(base_url());
    //        }
    }
    
    public function selecRptaSunat($enviado_sunat = '',$estado_sunat = '') {
        
        $where = '';        
        if($enviado_sunat != ''){$where.= ' AND enviado_sunat = '.$enviado_sunat;}
        if($estado_sunat != ''){$where.= ' AND estado_sunat = '.$estado_sunat;}
        
        $sql = "SELECT com.fecha_de_emision fecha_sunat,"
                . " DATE_FORMAT(fecha_de_emision, '%d-%m-%Y') AS fecha_de_emision,"
                . " DATE_FORMAT(fecha_de_vencimiento, '%d-%m-%Y') AS fecha_de_vencimiento,"
                . " epr.ruc empresa_ruc,"
                . " com.id comprobante_id,"
                . " com.serie serie,"
                . " com.numero numero,"
                . " com.empresa_id empresa_id,"
                . " com.cliente_id cliente_id,"
                . " tdc.codigo tipo_documento_codigo"
                . " FROM comprobantes com"
                . " INNER JOIN empresas epr"
                . " ON com.empresa_id = epr.id"
                . " INNER JOIN tipo_documentos tdc"
                . " ON com.tipo_documento_id = tdc.id"
                . " WHERE 1=1 ".$where." ORDER BY comprobante_id DESC";
            
            
            //echo $sql;
            $query = $this->db->query($sql);
            return $query->result_array();         
    }
    
    public function selecRptaSunatAnulaciones($estado_anulado = '') {
        
        $where = '';        
        if($estado_anulado != ''){$where.= ' AND com.anulado = '.$estado_anulado;}
        
        $sql = "SELECT com.fecha_de_emision fecha_sunat,"
                . " DATE_FORMAT(fecha_de_emision, '%d-%m-%Y') AS fecha_de_emision,"
                . " DATE_FORMAT(fecha_de_vencimiento, '%d-%m-%Y') AS fecha_de_vencimiento,"
                . " epr.ruc empresa_ruc,"
                . " com.id comprobante_id,"
                . " com.serie serie,"
                . " com.numero numero,"
                . " com.empresa_id empresa_id,"
                . " com.cliente_id cliente_id,"
                . " tdc.codigo tipo_documento_codigo"
                . " FROM comprobantes com"
                . " INNER JOIN empresas epr"
                . " ON com.empresa_id = epr.id"
                . " INNER JOIN tipo_documentos tdc"
                . " ON com.tipo_documento_id = tdc.id"
                . " WHERE 1=1 ".$where." ORDER BY comprobante_id DESC";

            //echo $sql;exit;
            $query = $this->db->query($sql);
            return $query->result_array();         
    }
    
    //select comprobante Hector Mostro Face3
    public function selectCustomizado($modo, $condicion = array(), $order = '') {
                        
        if ($condicion == '')
            $condicion = array();

        $where = '';
        foreach ($condicion as $key => $value) {
            if (($value == 'IS NULL') || (substr($value, 0, 7) == 'BETWEEN')) {
                $where .= " AND $key " . $value;
            } else {
                $where .= " AND $key = '" . $value . "' ";
            }
        }
        
        $sql = "SELECT 
        com.`id` comprobante_id,
        cli.`razon_social` cli_razon_social,
        cli.ruc cli_ruc,
        tip.`tipo_documento` tipo_documento,
        serie,
        numero,
        DATE_FORMAT(com.fecha_de_emision, '%d-%m-%Y') AS fecha_de_emision,
        DATE_FORMAT(com.fecha_de_vencimiento, '%d-%m-%Y') AS fecha_de_vencimiento,
        com.total_gravada,
        com.total_igv,
        com.total_a_pagar,
        com.enviado_sunat,
        IF(estado_sunat = '1', 'Por validar','Validado') estado_sunat,
        com.`enviado_cliente`,
        com.`enviado_equipo`,
        tip.`tipo_documento` tipo_documento,
        emp.`empresa` empresa,
        mon.`abreviado`,
        mon.`moneda`,
        mon.`simbolo`
        FROM `comprobantes` com
        JOIN `clientes` cli ON com.`cliente_id` = cli.`id`
        JOIN `tipo_documentos` tip ON com.`tipo_documento_id` = tip.`id` 
        JOIN `empresas` emp ON com.`empresa_id` = emp.`id`
        JOIN `monedas` mon ON com.`moneda_id` = mon.`id`
        WHERE eliminado=0 AND com.fecha_delete IS NULL " . $where . " " . $order;
        
        $query = $this->db->query($sql);

        switch ($modo) {
            case '1':
                $resultado = '';
                if ($query->num_rows() > 0) {
                    $row = $query->row_array();
                    $resultado = $row[$campos];
                }
                return $resultado;

            case '2':
                $row = array();
                if ($query->num_rows() > 0) {
                    $row = $query->row_array();
                }
                return $row;

            case '3':
                $rows = array();
                foreach ($query->result_array() as $row) {
                    $rows[] = $row;
                }
                return $rows;
        }
    }
    
    //Data Factura con Detalles
    public function selectCustomizadoDetalle($modo, $condicion = array(), $order = '') {
                        
        if ($condicion == '')
            $condicion = array();

        $where = '';
        foreach ($condicion as $key => $value) {
            if (($value == 'IS NULL') || (substr($value, 0, 7) == 'BETWEEN')) {
                $where .= " AND $key " . $value;
            } else {
                $where .= " AND $key = '" . $value . "' ";
            }
        }
        
        $sql = "SELECT 
        com.`id` comprobante_id,
        cli.`razon_social` cli_razon_social,
        cli.ruc cli_ruc,
        tip.`tipo_documento` tipo_documento,
        serie,
        numero,
        DATE_FORMAT(com.fecha_de_emision, '%d-%m-%Y') AS fecha_de_emision,
        DATE_FORMAT(com.fecha_de_vencimiento, '%d-%m-%Y') AS fecha_de_vencimiento,
        com.total_gravada,
        com.total_igv,
        com.total_a_pagar,
        com.enviado_sunat,
        com.total_detraccion,
        com.tipo_de_cambio,
        IF(estado_sunat = '1', 'Por validar','Validado') estado_sunat,
        com.`enviado_cliente`,
        com.`enviado_equipo`,
        ite.descripcion descripcion, 
        tip.`tipo_documento` tipo_documento,
        emp.`empresa` empresa,
        mon.`abreviado`,
        mon.`moneda`,
        mon.`simbolo`
        FROM `comprobantes` com
        JOIN items ite ON com.`id` = ite.`comprobante_id`
        JOIN `clientes` cli ON com.`cliente_id` = cli.`id`
        JOIN `tipo_documentos` tip ON com.`tipo_documento_id` = tip.`id` 
        JOIN `empresas` emp ON com.`empresa_id` = emp.`id`
        JOIN `monedas` mon ON com.`moneda_id` = mon.`id`
        WHERE com.eliminado=0 AND com.fecha_delete IS NULL " . $where . " " . $order;
        //echo $sql;exit;
        $query = $this->db->query($sql);

        switch ($modo) {
            case '1':
                $resultado = '';
                if ($query->num_rows() > 0) {
                    $row = $query->row_array();
                    $resultado = $row[$campos];
                }
                return $resultado;

            case '2':
                $row = array();
                if ($query->num_rows() > 0) {
                    $row = $query->row_array();
                }
                return $row;

            case '3':
                $rows = array();
                foreach ($query->result_array() as $row) {
                    $rows[] = $row;
                }
                return $rows;
        }
    }
    
    //Formated customized Boucher Witdh Details
    public function FormatedSelectCustomizadoDetalle($array){

        $comprobante_id2 = '';
        $description = '';
        $i = 0;
        $contador = 1;
        foreach ($array as $value){
            if(($value["comprobante_id"] != $comprobante_id2) && ($comprobante_id2 != '')){
                //le quito la ulima coma
                $result[$i]['descripcion'] = (isset($result[$i]['descripcion'])) ? substr($result[$i]['descripcion'], 0, -1) : '';
                
                $i++;
                $description = '';
            }            
            
            $description .= $value['descripcion'].",";
            $result[$i]['comprobante_id'] = $value['comprobante_id'];
            $result[$i]['cli_razon_social'] = $value['cli_razon_social'];
            $result[$i]['cli_ruc'] = $value['cli_ruc'];
            $result[$i]['tipo_documento'] = $value['tipo_documento'];
            $result[$i]['descripcion'] = $description;
            $result[$i]['serie'] = $value['serie'];
            $result[$i]['numero'] = $value['numero'];
            $result[$i]['fecha_de_emision'] = $value['fecha_de_emision'];
            $result[$i]['fecha_de_vencimiento'] = $value['fecha_de_vencimiento'];
            $result[$i]['total_gravada'] = $value['total_gravada'];
            $result[$i]['total_igv'] = $value['total_igv'];
            $result[$i]['total_a_pagar'] = $value['total_a_pagar'];
            $result[$i]['total_detraccion'] = $value['total_detraccion'];
            $result[$i]['tipo_de_cambio'] = $value['tipo_de_cambio'];
            $result[$i]['enviado_sunat'] = $value['enviado_sunat'];
            $result[$i]['estado_sunat'] = $value['estado_sunat'];
            $result[$i]['enviado_cliente'] = $value['enviado_cliente'];
            $result[$i]['enviado_equipo'] = $value['enviado_equipo'];
            $result[$i]['tipo_documento'] = $value['tipo_documento'];
            $result[$i]['empresa'] = $value['empresa'];
            $result[$i]['abreviado'] = $value['abreviado'];
            $result[$i]['moneda'] = $value['moneda'];
            $result[$i]['simbolo'] = $value['simbolo'];            
            
            $comprobante_id2 = $value["comprobante_id"];
            
            //solamente para quitar la ultima coma.
            if(count($array) == $contador){
                $result[$i]['descripcion'] = (isset($result[$i]['descripcion'])) ? substr($result[$i]['descripcion'], 0, -1) : '';
            }
            $contador ++;
        }
        return $result;
    }
    
    public function select($id = '',$serie = '',$numero = '',$fecha_de_emision = '',$fecha_de_vencimiento = '',$cliente_id = '',$tipo_documento_id = '',$com_adjuntos = '',$anulado = '',$inicio = FALSE,$limite = FALSE, $empresa_id = '',$numero_pedido='',$numero_guia='',$orden_compra=''){
        if($id != ''){
            $sql = "SELECT com.incluye_igv,com.fecha_de_emision fecha_sunat,com.numero_tarjeta,com.tipo_pago_id as pago_id, "
                    . " DATE_FORMAT(fecha_de_emision, '%d-%m-%Y') AS fecha_de_emision, "
                    . " DATE_FORMAT(fecha_de_vencimiento, '%d-%m-%Y') AS fecha_de_vencimiento, "
                    . " DATE_FORMAT(fecha_de_baja, '%d-%m-%Y') AS fecha_de_baja, "
                    . " epr.ruc empresa_ruc, "
                    . " epr.empresa empresa, "
                    . " epr.domicilio_fiscal, "
                    . " epr.telefono_movil, "
                    . " epr.correo, "
                    . " epr.foto, "
                    . " epr.descripcion1 descripcion1, "
                    . " com.id comprobante_id, "
                    . " com.empresa_id empresa_id, "
                    . " com.tipo_documento_id tipo_documento_id, "
                    . " com.serie serie, "
                    . " com.numero numero, "
                    . " com.total_a_pagar total_a_pagar, "
                    . " com.total_descuentos total_descuentos, "
                    . " com.numero_pedido numero_pedido, "
                    . " com.orden_compra orden_compra, "
                    . " com.numero_guia_remision numero_guia_remision, "
                    . " com.notas notas, "
                    . " com.condicion_venta condicion_venta, "
                    . " com.comprobante_anticipo as comprobante_anticipo, "
                    . " com.total_gravada total_gravada, "
                    . " com.total_exonerada total_exonerada, "
                    . " com.total_inafecta total_inafecta, "
                    . " com.total_igv total_igv, "
                    . " com.total_gratuita total_gratuita, "
                    . " com.descuento_global descuento_global, "
                    . " com.total_otros_cargos total_otros_cargos, "
                    . " com.detraccion detraccion, "
                    . " com.operacion_cancelada operacion_cancelada, "
                    . " com.operacion_gratuita operacion_gratuita, "
                    . " com.porcentaje_de_detraccion porcentaje_de_detraccion, "
                    . " com.total_detraccion total_detraccion, "
                    . " com.cliente_id cliente_id, "
                    . " com.tipo_nota_codigo tipo_nota_codigo, "
                    . " com.tipo_nota_id tipo_nota_id, "
                    . " com.com_adjunto_id com_adjunto_id, "
                    . " com.moneda_id moneda_id, "
                    . " com.tipo_de_cambio tipo_de_cambio, "
                    . " com.observaciones observaciones, "                    
                    . " com.enviado_cliente enviado_cliente, "
                    . " com.enviado_equipo enviado_equipo, "
                    . " com.com_adjunto_id com_adjunto_id, "  
                    . " com.direccion_cliente direccion_cliente, " 
                    . " com.empleado_select empleado_select, "  
                    . " com.tipo_operacion tipo_operacion, "  
                    . " com.adjunto_serie adjunto_serie, "  
                    . " com.adjunto_numero adjunto_numero, "  
                    . " com.adjunto_fecha adjunto_fecha, "  
                    . " cli.prov_ruc cliente_ruc, "
                   // . " cli.tipo_cliente_id tipo_cliente_id, "
                    //. " cli.nombres cli_nombres, "
                    . " cli.prov_razon_social cli_razon_social, "
                    //. " cli.razon_social_sunat cli_razon_social_sunat, "
                    //. " cli.domicilio1 cli_domicilio1, "
                    //. " cli.email cli_email, "
                    //. " cli.email2 cli_email2, " 
                    //. " cli.email3 cli_email3, "                     
                   // . " tpc.codigo tipo_cliente_codigo, "                    
                   // . " tdc.codigo tipo_documento_codigo, "
                   // . " tdc.tipo_documento tipo_documento, "
                   // . " tdc.abr abr, "
                    . " eaa.id eee_id, "
                    . " eaa.codigo codigo, "
                    . " eaa.descripcion elemento_adicional_descripcion, "
                    . " mon.moneda moneda, "
                    . " mon.abrstandar abrstandar, "
                    . " mon.simbolo simbolo"
                    . " FROM comprobantes_compras com "
                    . " JOIN proveedores cli ON com.cliente_id = cli.prov_id "
                   // . " JOIN tipo_clientes tpc ON tpc.id = cli.tipo_cliente_id "
                    . " JOIN monedas mon ON com.moneda_id = mon.id "
                    . " JOIN tipo_documentos tdc ON com.tipo_documento_id = tdc.id "
                    . " JOIN tipo_pagos tpp ON com.tipo_pago_id = tpp.id "
                    . " JOIN empresas epr ON com.empresa_id = epr.id "
                    . " LEFT JOIN elemento_adicionales eaa ON com.elemento_adicional_id = eaa.id "
                    . " WHERE eliminado=0 AND com.fecha_delete IS NULL and com.id = ".$id."";
                        
            $query = $this->db->query($sql);  
            //var_dump($query->row_array());exit;
            return $query->row_array();
        }
        
        $where = '';$limit = '';
        if($serie != ''){$where.= ' AND serie = "'.trim($serie).'"';}
        if($numero != ''){$where.= ' AND numero = "'.trim($numero).'"';}
        if($fecha_de_emision != ''){$where.= ' AND DATE_FORMAT(fecha_de_emision, "%Y-%m-%d") >= '.$fecha_de_emision;}
        if($fecha_de_vencimiento != ''){$where.= ' AND DATE_FORMAT(fecha_de_vencimiento, "%Y-%m-%d") <= '.$fecha_de_vencimiento;}
        if($cliente_id != ''){$where.= ' AND cliente_id = '.$cliente_id;}
        if($tipo_documento_id != ''){$where.= ' AND tipo_documento_id = '.$tipo_documento_id;}
        if($com_adjuntos != ''){$where.= ' AND tipo_documento_id IN(1,3)';}
        if($anulado != ''){$where.= ' AND anulado ='.$anulado;}
        if($empresa_id != ''){$where.= ' AND com.empresa_id ='.$empresa_id;}
        if($numero_pedido != ''){$where.= ' AND com.numero_pedido ='.trim($numero_pedido);}
        if($numero_guia != ''){$where.= ' AND com.numero_guia_remision ='.trim($numero_guia);}
        if($orden_compra != ''){$where.= ' AND com.orden_compra ='.trim($orden_compra);}
        if($inicio !== FALSE && $limite !== FALSE){$limit.= ' LIMIT '.$inicio.','.$limite;}
        
        
        $sql = "SELECT com.fecha_de_emision fecha_sunat, "
                    . " DATE_FORMAT(fecha_de_emision, '%d-%m-%Y') AS fecha_de_emision, "
                    . " DATE_FORMAT(fecha_de_vencimiento, '%d-%m-%Y') AS fecha_de_vencimiento, "
                    . " DATE_FORMAT(fecha_de_baja, '%d-%m-%Y') AS fecha_de_baja, "
                    . " epr.ruc empresa_ruc, "
                    . " epr.empresa empresa, "
                    . " epr.descripcion1 descripcion1, "
                    . " com.id comprobante_id, "
                    . " com.empresa_id empresa_id, "
                    . " com.tipo_documento_id tipo_documento_id, "
                    . " com.serie serie, "
                    . " com.numero numero, "
                    . " com.total_a_pagar total_a_pagar, "
                    . " com.total_gravada total_gravada, "
                    . " com.total_exonerada total_exonerada, "
                    . " com.total_inafecta total_inafecta, "
                    . " com.total_igv total_igv, "
                    . " com.total_gratuita total_gratuita, "
                    . " com.total_otros_cargos total_otros_cargos, "
                    . " com.detraccion detraccion, "
                    . " com.operacion_cancelada operacion_cancelada, "
                    . " com.operacion_gratuita operacion_gratuita, "
                    . " com.porcentaje_de_detraccion porcentaje_de_detraccion, "
                    . " com.total_detraccion total_detraccion, "
                    . " com.cliente_id cliente_id, "
                    . " com.tipo_nota_codigo tipo_nota_codigo, "
                    . " com.tipo_nota_id tipo_nota_id, "
                    . " com.com_adjunto_id com_adjunto_id, "
                    . " com.moneda_id moneda_id, "
                    . " com.tipo_de_cambio tipo_de_cambio, "
                    . " com.observaciones observaciones, "
                    . " com.anulado anulado, "
                    . " com.enviado_sunat enviado_sunat, "
                    . " com.estado_sunat estado_sunat, "
                    . " com.enviado_cliente enviado_cliente, "
                    . " com.enviado_equipo enviado_equipo, " 
                    . " com.com_adjunto_id com_adjunto_id, " 
                    . " com.direccion_cliente direccion_cliente, " 
                    . " com.empleado_select empleado_select, "  
                    . " com.tipo_operacion tipo_operacion, " 
                    . " com.adjunto_serie adjunto_serie, "  
                    . " com.adjunto_numero adjunto_numero, "  
                    . " com.adjunto_fecha adjunto_fecha, "   
                    . " cli.prov_ruc cliente_ruc, "
               
                    //. " cli.nombres cli_nombres, "
                    . " cli.prov_razon_social cli_razon_social, "
                    //. " cli.razon_social_sunat cli_razon_social_sunat, "
                    //. " cli.domicilio1 cli_domicilio1, "
                    //. " cli.email cli_email, "
                    //. " cli.email2 cli_email2, " 
                    //. " cli.email3 cli_email3, "                      
                   // . " tpc.codigo tipo_cliente_codigo, "                    
                   // . " tdc.codigo tipo_documento_codigo, "
                   // . " tdc.tipo_documento tipo_documento, "
                   // . " tdc.abr abr, "
                    . " eaa.id eee_id, "
                    . " eaa.codigo codigo, "
                    . " eaa.descripcion elemento_adicional_descripcion, "
                    . " mon.moneda moneda, "
                    . " mon.abrstandar abrstandar, "
                    . " mon.simbolo simbolo"
                    . " FROM comprobantes_compras com "
                    . " JOIN proveedores cli ON com.cliente_id = cli.prov_id "
                    //. " JOIN tipo_clientes tpc ON tpc.id = cli.tipo_cliente_id "
                    . " JOIN monedas mon ON com.moneda_id = mon.id "
                    . " JOIN tipo_documentos tdc ON com.tipo_documento_id = tdc.id "
                    . " JOIN tipo_pagos tpp ON com.tipo_pago_id = tpp.id "
                    . " JOIN empresas epr ON com.empresa_id = epr.id "
                    . " LEFT JOIN elemento_adicionales eaa ON com.elemento_adicional_id = eaa.id "                  
                    . " WHERE 1=1 AND eliminado=0 AND com.fecha_delete IS NULL ".$where." ORDER BY comprobante_id DESC".$limit;
            //echo $sql;exit;
            $query = $this->db->query($sql);
            //var_dump($query->result_array());exit;
            return $query->result_array();        
    }

    public function select_nc($id = '',$serie = '',$numero = '',$fecha_de_emision = '',$fecha_de_vencimiento = '',$cliente_id = '',$tipo_documento_id = '',$com_adjuntos = '',$anulado = '',$inicio = FALSE,$limite = FALSE, $empresa_id = '',$numero_pedido='',$numero_guia='',$orden_compra=''){
        if($id != ''){
            $sql = "SELECT com.incluye_igv,com.fecha_de_emision fecha_sunat,com.numero_tarjeta,com.tipo_pago_id as pago_id, "
                    . " DATE_FORMAT(fecha_de_emision, '%d-%m-%Y') AS fecha_de_emision, "
                    . " DATE_FORMAT(fecha_de_vencimiento, '%d-%m-%Y') AS fecha_de_vencimiento, "
                    . " DATE_FORMAT(fecha_de_baja, '%d-%m-%Y') AS fecha_de_baja, "
                    . " epr.ruc empresa_ruc, "
                    . " epr.empresa empresa, "
                    . " epr.domicilio_fiscal, "
                    . " epr.telefono_movil, "
                    . " epr.correo, "
                    . " epr.foto, "
                    . " epr.descripcion1 descripcion1, "
                    . " com.id comprobante_id, "
                    . " com.empresa_id empresa_id, "
                    . " com.tipo_documento_id tipo_documento_id, "
                    . " com.serie serie, "
                    . " com.numero numero, "
                    . " com.total_a_pagar total_a_pagar, "
                    . " com.total_descuentos total_descuentos, "
                    . " com.numero_pedido numero_pedido, "
                    . " com.orden_compra orden_compra, "
                    . " com.numero_guia_remision numero_guia_remision, "
                    . " com.notas notas, "
                    . " com.condicion_venta condicion_venta, "
                    . " com.comprobante_anticipo as comprobante_anticipo, "
                    . " com.total_gravada total_gravada, "
                    . " com.total_exonerada total_exonerada, "
                    . " com.total_inafecta total_inafecta, "
                    . " com.total_igv total_igv, "
                    . " com.total_gratuita total_gratuita, "
                    . " com.descuento_global descuento_global, "
                    . " com.total_otros_cargos total_otros_cargos, "
                    . " com.detraccion detraccion, "
                    . " com.operacion_cancelada operacion_cancelada, "
                    . " com.operacion_gratuita operacion_gratuita, "
                    . " com.porcentaje_de_detraccion porcentaje_de_detraccion, "
                    . " com.total_detraccion total_detraccion, "
                    . " com.cliente_id cliente_id, "
                    . " com.tipo_nota_codigo tipo_nota_codigo, "
                    . " com.tipo_nota_id tipo_nota_id, "
                    . " com.com_adjunto_id com_adjunto_id, "
                    . " com.moneda_id moneda_id, "
                    . " com.tipo_de_cambio tipo_de_cambio, "
                    . " com.observaciones observaciones, "                    
                    . " com.enviado_cliente enviado_cliente, "
                    . " com.enviado_equipo enviado_equipo, "
                    . " com.com_adjunto_id com_adjunto_id, "  
                    . " com.direccion_cliente direccion_cliente, " 
                    . " com.empleado_select empleado_select, "  
                    . " cli.ruc cliente_ruc, "
                    . " cli.tipo_cliente_id tipo_cliente_id, "
                    . " cli.nombres cli_nombres, "
                    . " cli.razon_social cli_razon_social, "
                    . " cli.razon_social_sunat cli_razon_social_sunat, "
                    . " cli.domicilio1 cli_domicilio1, "
                    . " cli.email cli_email, "                    
                    . " tpc.codigo tipo_cliente_codigo, "                    
                    . " tdc.codigo tipo_documento_codigo, "
                    . " tdc.tipo_documento tipo_documento, "
                    . " tdc.abr abr, "
                    . " eaa.id eee_id, "
                    . " eaa.codigo codigo, "
                    . " eaa.descripcion elemento_adicional_descripcion, "
                    . " mon.moneda moneda, "
                    . " mon.abrstandar abrstandar, "
                    . " mon.simbolo simbolo"
                    . " FROM comprobantes com "
                    . " JOIN clientes cli ON com.cliente_id = cli.id "
                    . " JOIN tipo_clientes tpc ON tpc.id = cli.tipo_cliente_id "
                    . " JOIN monedas mon ON com.moneda_id = mon.id "
                    . " JOIN tipo_documentos tdc ON com.tipo_documento_id = tdc.id "
                    . " JOIN tipo_pagos tpp ON com.tipo_pago_id = tpp.id "
                    . " JOIN empresas epr ON com.empresa_id = epr.id "
                    . " LEFT JOIN elemento_adicionales eaa ON com.elemento_adicional_id = eaa.id "
                    . " WHERE eliminado=0 AND com.fecha_delete IS NULL and com.id = ".$id."";
                        
            $query = $this->db->query($sql);  
            //var_dump($query->row_array());exit;
            return $query->row_array();
        }
        
        $where = '';$limit = '';
        if($serie != ''){$where.= ' AND serie = "'.trim($serie).'"';}
        if($numero != ''){$where.= ' AND numero = "'.trim($numero).'"';}
        if($fecha_de_emision != ''){$where.= ' AND DATE_FORMAT(fecha_de_emision, "%Y-%m-%d") >= '.$fecha_de_emision;}
        if($fecha_de_vencimiento != ''){$where.= ' AND DATE_FORMAT(fecha_de_vencimiento, "%Y-%m-%d") <= '.$fecha_de_vencimiento;}
        if($cliente_id != ''){$where.= ' AND cliente_id = '.$cliente_id;}
        //if($tipo_documento_id != ''){$where.= ' AND tipo_documento_id = '.$tipo_documento_id;}
        if($tipo_documento_id != ''){$where.= ' AND tipo_documento_id in(1,3)';}
        if($com_adjuntos != ''){$where.= ' AND tipo_documento_id IN(1,3)';}
        if($anulado != ''){$where.= ' AND anulado ='.$anulado;}
        if($empresa_id != ''){$where.= ' AND com.empresa_id ='.$empresa_id;}
        if($numero_pedido != ''){$where.= ' AND com.numero_pedido ='.trim($numero_pedido);}
        if($numero_guia != ''){$where.= ' AND com.numero_guia_remision ='.trim($numero_guia);}
        if($orden_compra != ''){$where.= ' AND com.orden_compra ='.trim($orden_compra);}
        if($inicio !== FALSE && $limite !== FALSE){$limit.= ' LIMIT '.$inicio.','.$limite;}
        
        
        $sql = "SELECT com.fecha_de_emision fecha_sunat, "
                    . " DATE_FORMAT(fecha_de_emision, '%d-%m-%Y') AS fecha_de_emision, "
                    . " DATE_FORMAT(fecha_de_vencimiento, '%d-%m-%Y') AS fecha_de_vencimiento, "
                    . " DATE_FORMAT(fecha_de_baja, '%d-%m-%Y') AS fecha_de_baja, "
                    . " epr.ruc empresa_ruc, "
                    . " epr.empresa empresa, "
                    . " epr.descripcion1 descripcion1, "
                    . " com.id comprobante_id, "
                    . " com.empresa_id empresa_id, "
                    . " com.tipo_documento_id tipo_documento_id, "
                    . " com.serie serie, "
                    . " com.numero numero, "
                    . " com.total_a_pagar total_a_pagar, "
                    . " com.total_gravada total_gravada, "
                    . " com.total_exonerada total_exonerada, "
                    . " com.total_inafecta total_inafecta, "
                    . " com.total_igv total_igv, "
                    . " com.total_gratuita total_gratuita, "
                    . " com.total_otros_cargos total_otros_cargos, "
                    . " com.detraccion detraccion, "
                    . " com.operacion_cancelada operacion_cancelada, "
                    . " com.operacion_gratuita operacion_gratuita, "
                    . " com.porcentaje_de_detraccion porcentaje_de_detraccion, "
                    . " com.total_detraccion total_detraccion, "
                    . " com.cliente_id cliente_id, "
                    . " com.tipo_nota_codigo tipo_nota_codigo, "
                    . " com.tipo_nota_id tipo_nota_id, "
                    . " com.com_adjunto_id com_adjunto_id, "
                    . " com.moneda_id moneda_id, "
                    . " com.tipo_de_cambio tipo_de_cambio, "
                    . " com.observaciones observaciones, "
                    . " com.anulado anulado, "
                    . " com.enviado_sunat enviado_sunat, "
                    . " com.estado_sunat estado_sunat, "
                    . " com.enviado_cliente enviado_cliente, "
                    . " com.enviado_equipo enviado_equipo, " 
                    . " com.com_adjunto_id com_adjunto_id, " 
                    . " com.direccion_cliente direccion_cliente, " 
                    . " com.empleado_select empleado_select, "  
                    . " cli.ruc cliente_ruc, "
                    . " cli.tipo_cliente_id tipo_cliente_id, "
                    . " cli.nombres cli_nombres, "
                    . " cli.razon_social cli_razon_social, "
                    . " cli.razon_social razon_social, "
                    . " cli.razon_social_sunat cli_razon_social_sunat, "
                    . " cli.domicilio1 cli_domicilio1, "
                    . " cli.email cli_email, "                    
                    . " tpc.codigo tipo_cliente_codigo, "                    
                    . " tdc.codigo tipo_documento_codigo, "
                    . " tdc.tipo_documento tipo_documento, "
                    . " tdc.abr abr, "
                    . " eaa.id eee_id, "
                    . " eaa.codigo codigo, "
                    . " eaa.descripcion elemento_adicional_descripcion, "
                    . " mon.moneda moneda, "
                    . " mon.abrstandar abrstandar, "
                    . " mon.simbolo simbolo"
                    . " FROM comprobantes com "
                    . " JOIN clientes cli ON com.cliente_id = cli.id "
                    . " JOIN tipo_clientes tpc ON tpc.id = cli.tipo_cliente_id "
                    . " JOIN monedas mon ON com.moneda_id = mon.id "
                    . " JOIN tipo_documentos tdc ON com.tipo_documento_id = tdc.id "
                    . " JOIN tipo_pagos tpp ON com.tipo_pago_id = tpp.id "
                    . " JOIN empresas epr ON com.empresa_id = epr.id "
                    . " LEFT JOIN elemento_adicionales eaa ON com.elemento_adicional_id = eaa.id "                  
                    . " WHERE 1=1 AND eliminado=0 AND com.fecha_delete IS NULL ".$where." ORDER BY comprobante_id DESC".$limit;
            //echo $sql;exit;
            $query = $this->db->query($sql);
            //var_dump($query->result_array());exit;
            return $query->result_array();        
    }
    
    public function selectCount($id = '',$serie = '',$numero = '',$fecha_de_emision = '',$fecha_de_vencimiento = '',$cliente_id = '',$tipo_documento_id = '',$com_adjuntos = '',$anulado = '',$inicio = FALSE,$limite = FALSE, $empresa_id = ''){
                        
        $where = '';$limit = '';
        if($serie != ''){$where.= ' AND serie = "'.$serie.'"';}
        if($numero != ''){$where.= ' AND numero = "'.$numero.'"';}
        if($fecha_de_emision != ''){$where.= ' AND fecha_de_emision = '.$fecha_de_emision;}
        if($fecha_de_vencimiento != ''){$where.= ' AND fecha_de_vencimiento = '.$fecha_de_vencimiento;}
        if($cliente_id != ''){$where.= ' AND cliente_id = '.$cliente_id;}
        if($tipo_documento_id != ''){$where.= ' AND tipo_documento_id = '.$tipo_documento_id;}
        if($tipo_documento_id != ''){$where.= ' AND tipo_documento_id = '.$tipo_documento_id;}
        if($com_adjuntos != ''){$where.= ' AND tipo_documento_id IN(1,3)';}
        if($anulado != ''){$where.= ' AND anulado ='.$anulado;}
        if($empresa_id != ''){$where.= ' AND com.empresa_id ='.$empresa_id;}
        if($inicio !== FALSE && $limite !== FALSE){$limit.= ' LIMIT '.$inicio.','.$limite;}
        
        
$sql = "SELECT COUNT(com.id) as comprobante_id "
                    . " FROM comprobantes com "
                    . " JOIN clientes cli ON com.cliente_id = cli.id "
                    . " JOIN tipo_clientes tpc ON tpc.id = cli.tipo_cliente_id "
                    . " JOIN monedas mon ON com.moneda_id = mon.id "
                    . " JOIN tipo_documentos tdc ON com.tipo_documento_id = tdc.id "
                    . " JOIN tipo_pagos tpp ON com.tipo_pago_id = tpp.id "
                    . " JOIN empresas epr ON com.empresa_id = epr.id "
                    . " LEFT JOIN elemento_adicionales eaa ON com.elemento_adicional_id = eaa.id "                  
                    . " WHERE 1=1 AND eliminado=0 AND com.fecha_delete IS NULL ".$where." ORDER BY comprobante_id DESC";
            //echo $sql;exit;
            $query = $this->db->query($sql);
            //var_dump($query->result_array());exit;
            $resultado = '';
            if ($query->num_rows() > 0) {
                    $row = $query->row_array();
                    $resultado = $row['comprobante_id'];
            }
            return $resultado;                        
    }
    
    /*
     * $fecha_de_emision desde - hasta con between
     */
    public function selectVersion2($id = '',$serie = '',$numero = '',$fecha_de_emision_desde = '',$fecha_de_emision_hasta = '',$cliente_id = '',$tipo_documento_id = '',$com_adjuntos = '',$anulado = '',$inicio = FALSE,$limite = FALSE, $empresa_id = ''){
        
        if($id != ''){
            $sql = "SELECT com.fecha_de_emision fecha_sunat, "
                    . " DATE_FORMAT(fecha_de_emision, '%d-%m-%Y') AS fecha_de_emision, "
                    . " DATE_FORMAT(fecha_de_vencimiento, '%d-%m-%Y') AS fecha_de_vencimiento, "
                    . " DATE_FORMAT(fecha_de_baja, '%d-%m-%Y') AS fecha_de_baja, "
                    . " epr.ruc empresa_ruc, "
                    . " epr.empresa empresa, "
                    . " epr.descripcion1 descripcion1, "
                    . " com.id comprobante_id, "
                    . " com.empresa_id empresa_id, "
                    . " com.tipo_documento_id tipo_documento_id, "
                    . " com.serie serie, "
                    . " com.numero numero, "
                    . " com.total_a_pagar total_a_pagar, "
                    . " com.total_gravada total_gravada, "
                    . " com.total_exonerada total_exonerada, "
                    . " com.total_inafecta total_inafecta, "
                    . " com.total_igv total_igv, "
                    . " com.total_gratuita total_gratuita, "
                    . " com.total_otros_cargos total_otros_cargos, "
                    . " com.detraccion detraccion, "
                    . " com.operacion_cancelada operacion_cancelada, "
                    . " com.operacion_gratuita operacion_gratuita, "
                    . " com.porcentaje_de_detraccion porcentaje_de_detraccion, "
                    . " com.total_detraccion total_detraccion, "
                    . " com.cliente_id cliente_id, "
                    . " com.tipo_nota_codigo tipo_nota_codigo, "
                    . " com.moneda_id moneda_id, "
                    . " com.tipo_de_cambio tipo_de_cambio, "
                    . " com.observaciones observaciones, "                    
                    . " com.enviado_cliente enviado_cliente, "
                    . " com.enviado_equipo enviado_equipo, "                   
                    . " cli.ruc cliente_ruc, "
                    . " cli.tipo_cliente_id tipo_cliente_id, "
                    . " cli.nombres cli_nombres, "
                    . " cli.razon_social cli_razon_social, "
                    . " cli.razon_social_sunat cli_razon_social_sunat, "
                    . " cli.domicilio1 cli_domicilio1, "
                    . " cli.email cli_email, "                    
                    . " tpc.codigo tipo_cliente_codigo, "                    
                    . " tdc.codigo tipo_documento_codigo, "
                    . " tdc.tipo_documento tipo_documento, "
                    . " eaa.id eee_id, "
                    . " eaa.codigo codigo, "
                    . " eaa.descripcion elemento_adicional_descripcion, "
                    . " mon.moneda moneda, "
                    . " mon.abrstandar abrstandar, "
                    . " mon.simbolo simbolo"
                    . " FROM comprobantes com "
                    . " JOIN clientes cli ON com.cliente_id = cli.id "
                    . " JOIN tipo_clientes tpc ON tpc.id = cli.tipo_cliente_id "
                    . " JOIN monedas mon ON com.moneda_id = mon.id "
                    . " JOIN tipo_documentos tdc ON com.tipo_documento_id = tdc.id "
                    . " JOIN tipo_pagos tpp ON com.tipo_pago_id = tpp.id "
                    . " JOIN empresas epr ON com.empresa_id = epr.id "
                    . " LEFT JOIN elemento_adicionales eaa ON com.elemento_adicional_id = eaa.id "
                    . " WHERE com.id = ".$id." AND eliminado=0 AND com.fecha_delete IS NULL";
            
            //echo $sql;exit;
            $query = $this->db->query($sql);  
            //var_dump($query->row_array());exit;
            return $query->row_array();
        }
        
        $where = '';$limit = '';
        if($serie != ''){$where.= ' AND serie = "'.$serie.'"';}
        if($numero != ''){$where.= ' AND numero = "'.$numero.'"';}
        if(($fecha_de_emision_desde != '') && ($fecha_de_emision_hasta != '')){$where.= " AND fecha_de_emision BETWEEN '" . $fecha_de_emision_desde . "' AND '" . $fecha_de_emision_hasta ."'";}
        if($cliente_id != ''){$where.= ' AND cliente_id = '.$cliente_id;}
        if($tipo_documento_id != ''){$where.= ' AND tipo_documento_id = '.$tipo_documento_id;}
        if($tipo_documento_id != ''){$where.= ' AND tipo_documento_id = '.$tipo_documento_id;}
        if($com_adjuntos != ''){$where.= ' AND tipo_documento_id IN(1,3)';}
        if($anulado != ''){$where.= ' AND anulado ='.$anulado;}
        if($empresa_id != ''){$where.= ' AND com.empresa_id ='.$empresa_id;}
        if($inicio !== FALSE && $limite !== FALSE){$limit.= ' LIMIT '.$inicio.','.$limite;}        
        
        $sql = "SELECT com.fecha_de_emision fecha_sunat, "
                    . " DATE_FORMAT(fecha_de_emision, '%d-%m-%Y') AS fecha_de_emision, "
                    . " DATE_FORMAT(fecha_de_vencimiento, '%d-%m-%Y') AS fecha_de_vencimiento, "
                    . " DATE_FORMAT(fecha_de_baja, '%d-%m-%Y') AS fecha_de_baja, "
                    . " epr.ruc empresa_ruc, "
                    . " epr.empresa empresa, "
                    . " epr.descripcion1 descripcion1, "
                    . " com.id comprobante_id, "
                    . " com.empresa_id empresa_id, "
                    . " com.tipo_documento_id tipo_documento_id, "
                    . " com.serie serie, "
                    . " com.numero numero, "
                    . " com.total_a_pagar total_a_pagar, "
                    . " com.total_gravada total_gravada, "
                    . " com.total_exonerada total_exonerada, "
                    . " com.total_inafecta total_inafecta, "
                    . " com.total_igv total_igv, "
                    . " com.total_gratuita total_gratuita, "
                    . " com.total_otros_cargos total_otros_cargos, "
                    . " com.detraccion detraccion, "
                    . " com.operacion_cancelada operacion_cancelada, "
                    . " com.operacion_gratuita operacion_gratuita, "
                    . " com.porcentaje_de_detraccion porcentaje_de_detraccion, "
                    . " com.total_detraccion total_detraccion, "
                    . " com.cliente_id cliente_id, "
                    . " com.tipo_nota_codigo tipo_nota_codigo, "
                    . " com.moneda_id moneda_id, "
                    . " com.tipo_de_cambio tipo_de_cambio, "
                    . " com.observaciones observaciones, "
                    . " com.anulado anulado, "
                    . " com.enviado_sunat enviado_sunat, "
                    . " com.estado_sunat estado_sunat, "
                    . " com.enviado_cliente enviado_cliente, "
                    . " com.enviado_equipo enviado_equipo, " 
                    . " cli.ruc cliente_ruc, "
                    . " cli.tipo_cliente_id tipo_cliente_id, "
                    . " cli.nombres cli_nombres, "
                    . " cli.razon_social cli_razon_social, "
                    . " cli.razon_social razon_social, "
                    . " cli.razon_social_sunat cli_razon_social_sunat, "
                    . " cli.domicilio1 cli_domicilio1, "
                    . " cli.email cli_email, "                    
                    . " tpc.codigo tipo_cliente_codigo, "                    
                    . " tdc.codigo tipo_documento_codigo, "
                    . " tdc.tipo_documento tipo_documento, "
                    . " eaa.id eee_id, "
                    . " eaa.codigo codigo, "
                    . " eaa.descripcion elemento_adicional_descripcion, "
                    . " mon.moneda moneda, "
                    . " mon.abrstandar abrstandar, "
                    . " mon.simbolo simbolo"
                    . " FROM comprobantes com "
                    . " JOIN clientes cli ON com.cliente_id = cli.id "
                    . " JOIN tipo_clientes tpc ON tpc.id = cli.tipo_cliente_id "
                    . " JOIN monedas mon ON com.moneda_id = mon.id "
                    . " JOIN tipo_documentos tdc ON com.tipo_documento_id = tdc.id "
                    . " JOIN tipo_pagos tpp ON com.tipo_pago_id = tpp.id "
                    . " JOIN empresas epr ON com.empresa_id = epr.id "
                    . " LEFT JOIN elemento_adicionales eaa ON com.elemento_adicional_id = eaa.id "                  
                    . " WHERE 1=1 AND eliminado=0 AND com.fecha_delete IS NULL ".$where." ORDER BY comprobante_id DESC".$limit;
            //echo $sql;
            $query = $this->db->query($sql);
            //var_dump($query->result_array());exit;
            return $query->result_array();        
    }
    /*
     * $fecha_de_emision desde - hasta con between
     */    
    public function selectCountVersion2($id = '',$serie = '',$numero = '',$fecha_de_emision_desde = '',$fecha_de_emision_hasta = '',$cliente_id = '',$tipo_documento_id = '',$com_adjuntos = '',$anulado = '',$inicio = FALSE,$limite = FALSE, $empresa_id = ''){
                        
        $where = '';$limit = '';
        if($serie != ''){$where.= ' AND serie = "'.$serie.'"';}
        if($numero != ''){$where.= ' AND numero = "'.$numero.'"';}        
        if(($fecha_de_emision_desde != '') && ($fecha_de_emision_hasta != '')){$where.= " AND fecha_de_emision BETWEEN '" . $fecha_de_emision_desde . "' AND '" . $fecha_de_emision_hasta ."'";}
        if($cliente_id != ''){$where.= ' AND cliente_id = '.$cliente_id;}
        if($tipo_documento_id != ''){$where.= ' AND tipo_documento_id = '.$tipo_documento_id;}
        if($tipo_documento_id != ''){$where.= ' AND tipo_documento_id = '.$tipo_documento_id;}
        if($com_adjuntos != ''){$where.= ' AND tipo_documento_id IN(1,3)';}
        if($anulado != ''){$where.= ' AND anulado ='.$anulado;}
        if($empresa_id != ''){$where.= ' AND com.empresa_id ='.$empresa_id;}
        if($inicio !== FALSE && $limite !== FALSE){$limit.= ' LIMIT '.$inicio.','.$limite;}
        
        
        $sql = "SELECT COUNT(com.id) as comprobante_id "
                    . " FROM comprobantes com "
                    . " JOIN clientes cli ON com.cliente_id = cli.id "
                    . " JOIN tipo_clientes tpc ON tpc.id = cli.tipo_cliente_id "
                    . " JOIN monedas mon ON com.moneda_id = mon.id "
                    . " JOIN tipo_documentos tdc ON com.tipo_documento_id = tdc.id "
                    . " JOIN tipo_pagos tpp ON com.tipo_pago_id = tpp.id "
                    . " JOIN empresas epr ON com.empresa_id = epr.id "
                    . " LEFT JOIN elemento_adicionales eaa ON com.elemento_adicional_id = eaa.id "                  
                    . " WHERE 1=1 AND eliminado=0 AND com.fecha_delete IS NULL ".$where." ORDER BY comprobante_id DESC";
            //echo $sql;exit;
            $query = $this->db->query($sql);
            //var_dump($query->result_array());exit;
            $resultado = '';
            if ($query->num_rows() > 0) {
                    $row = $query->row_array();
                    $resultado = $row['comprobante_id'];
            }
            return $resultado;                        
    }            
    
    
    public function seleccion($modo, $select = array(), $condicion = array(), $order = '') {

        if ($select == '')
            $select = array();
        if ($condicion == '')
            $condicion = array();

        $where = '';
        foreach ($condicion as $key => $value) {
            if (($value == 'IS NULL') || (substr($value, 0, 7) == 'BETWEEN')) {
                $where .= " AND $key " . $value;
            } else {
                $where .= " AND $key = '" . $value . "' ";
            }
        }

        $campos = ($select == array()) ? '*' : implode(", ", $select);
        $sql = "SELECT " . $campos . " FROM comprobantes WHERE 1 = 1 " . $where . " " . $order;
        $query = $this->db->query($sql);

        switch ($modo) {
            case '1':
                $resultado = '';
                if ($query->num_rows() > 0) {
                    $row = $query->row_array();
                    $resultado = $row[$campos];
                }
                return $resultado;

            case '2':
                $row = array();
                if ($query->num_rows() > 0) {
                    $row = $query->row_array();
                }
                return $row;

            case '3':
                $rows = array();
                foreach ($query->result_array() as $row) {
                    $rows[] = $row;
                }
                return $rows;
        }
    }
    
    public function selecMaximoNumero($empresa_id, $tipo_documento_id, $serie){
        
        $sql = "SELECT MAX(CAST(numero AS UNSIGNED)) resultado FROM comprobantes WHERE 1 = 1 AND empresa_id = $empresa_id AND tipo_documento_id = $tipo_documento_id AND serie = '".$serie."'";
        $query = $this->db->query($sql);
        $resultado = '';
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            $resultado = $row['resultado'];
        }
        return $resultado;        
    }
    
    //para coprobante/nuevo de forma individual
    public function selecMaximoNumero2($empresa_id, $tipo_documento_id, $serie){
        
        $sql = "SELECT MAX(CAST(numero AS UNSIGNED)) numero FROM comprobantes WHERE 1 = 1 AND empresa_id = $empresa_id AND tipo_documento_id = $tipo_documento_id AND serie = '".$serie."'";
        $query = $this->db->query($sql);
        return $query->row_array();
    }
    
    public function jsonComprobante($id = ''){
        $where = '';
        if($id != ''){ $where.= ' AND fac.id ='.$id;}
        $sql = "SELECT DATE_FORMAT(fecha,'%d-%m-%Y') fecha,"
            . " fac.id comprobante_id,"
            . " fac.cliente_id cliente_id,"
            . " fac.descripcion descripcion,"
            . " fac.importe importe,"
            . " cli.razon_social razon_social,"
            . " mon.id moneda_id,"
            . " mon.moneda moneda"
            . " FROM facturas fac"
            . " JOIN clientes cli"
            . " ON fac.cliente_id = cli.id"
            . " JOIN monedas mon"
            . " ON fac.moneda_id = mon.id"
            . " WHERE 1=1 ".$where;
    //echo $sql;exit();
        $query = mysql_query($sql);
        while ($row = mysql_fetch_assoc($query)) {
                $rows[] = $row;
        }
        return $rows;
    }

    public function insertar($data) {
        $rs = $this->db->insert('comprobantes_compras', $data);
        if(!$rs){
            $this->session->set_flashdata('mensaje', 'Comprobante no registrado! | Error ----> '.$this->db->_error_message());
        } else {        
        $this->session->set_flashdata('mensaje', 'Compra: Ingresado exitosamente');
        return $this->db->insert_id();
        }       
    }

    public function modificar($data, $where){
        $this->db->where('id',$where);
        $this->db->update('comprobantes_compras', $data);
        $this->session->set_flashdata('mensaje', 'Comprobante modificado exitosamente');
    }

    public function eliminar($comprobante_id){
        /*$sql_eli = "UPDATE comprobantes_compras SET eliminado = 1 WHERE id = " . $comprobante_id;
        mysql_query($sql_eli);
        $this->session->set_flashdata('mensaje', 'comprobante: eliminado exitosamente');*/

        $this->db->where('id',$comprobante_id);
        $com = $this->db->get('comprobantes_compras')->row();

        $this->db->where('comprobante_id',$comprobante_id);
        $items = $this->db->get('items_compras')->result();

            ////// STOCK 
            foreach ($items as $i) {
                if($i->producto_id!=0){
                    if($com->tipo_documento_id!=7 and $com->tipo_documento_id!=9){
                         $stock = $this->productos_model->getStockProductos($i->producto_id,$this->session->userdata("almacen_id"));
                         $nueva_cantidad = floatval($stock)-floatval($i->cantidad);

                         $kardex = array(
                          'k_fecha' => date('Y-m-d'),
                          'k_almacen' => $this->session->userdata("almacen_id"),
                          'k_tipo' => 1,
                          'k_operacion_id' => 0,
                          'k_serie' => '-',
                          'k_concepto' => 'Documento Eliminado',     
                          'k_producto' => $i->producto_id,
                          'k_scantidad' => $i->cantidad,
                          'k_excantidad' => $nueva_cantidad,
                                           
                         );

                         $this->db->insert('kardex', $kardex);
                    } else{
                         $stock = $this->productos_model->getStockProductos($i->producto_id,$this->session->userdata("almacen_id"));
                         $nueva_cantidad = floatval($stock)+floatval($i->cantidad);

                         $kardex = array(
                          'k_fecha' => date('Y-m-d'),
                          'k_almacen' => $this->session->userdata("almacen_id"),
                          'k_tipo' => 1,
                          'k_operacion_id' => 0,
                          'k_serie' => '-',
                          'k_concepto' => 'Documento Eliminado',     
                          'k_producto' => $i->producto_id,
                          'k_ecantidad' => $i->cantidad,
                          'k_excantidad' => $nueva_cantidad,
                                           
                         );

                         $this->db->insert('kardex', $kardex);  
                    }
                }  
            } 

        $this->db->where('id',$comprobante_id);
        $this->db->delete('comprobantes_compras');

        $this->db->where('comprobante_id',$comprobante_id);
        $this->db->delete('items_compras'); 
    }

    public function Modificar_boleta($comprobante_id='')
    {
        $dataUpdate = [
                        'enviado_sunat' => 1
                    ];
        $this->db->where('id',$comprobante_id);
        $this->db->update('comprobantes',$dataUpdate);

    }
    public function selectComprobanteItems($comprobante_id = ''){               
        $where = '';
        if($comprobante_id != ''){$where.= ' AND comprobante_id = '.$comprobante_id;}

            $sql = "SELECT *,com.id comprobante_id FROM comprobantes com"
                    . " JOIN items itm"
                    . " ON com.id = itm.comprobante_id"
                    . " WHERE 1=1 ".$where;

            $query = $this->db->query($sql);

            //var_dump($query->result());exit;
            return $query->result_array();
    }

    public function selectUltimoReg($serieId = '') {
        $sql = "SELECT *FROM comprobantes WHERE serie ='".$serieId ."' ORDER BY id DESC limit 1 ";
        //echo $sql;
        $query = $this->db->query($sql);
        return $query->row_array();
    }
    
    public function formatVoucher($data){
        
        $array = array();
        $i = 1;
                
        foreach ($data as $value){
            $array[$i]['fecha_sunat'] = $value['fecha_sunat'];
            $array[$i]['fecha_de_emision'] = $value['fecha_de_emision'];
            $array[$i]['fecha_de_vencimiento'] = $value['fecha_de_vencimiento'];
            $array[$i]['fecha_de_baja'] = $value['fecha_de_baja'];
            $array[$i]['empresa_ruc'] = $value['empresa_ruc'];
            $array[$i]['empresa'] = $value['empresa'];
            $array[$i]['descripcion1'] = $value['descripcion1'];
            $array[$i]['comprobante_id'] = $value['comprobante_id'];
            $array[$i]['empresa_id'] = $value['empresa_id'];
            $array[$i]['tipo_documento_id'] = $value['tipo_documento_id'];
            
            $array[$i]['serie'] = $value['serie'];
            $array[$i]['numero'] = $value['numero'];
            $array[$i]['total_a_pagar'] = $value['total_a_pagar'];
            $array[$i]['total_gravada'] = $value['total_gravada'];
            $array[$i]['total_exonerada'] = $value['total_exonerada'];
            $array[$i]['total_inafecta'] = $value['total_inafecta'];
            $array[$i]['total_igv'] = $value['total_igv'];
            $array[$i]['total_gratuita'] = $value['total_gratuita'];
            $array[$i]['total_otros_cargos'] = $value['total_otros_cargos'];
            $array[$i]['detraccion'] = $value['detraccion'];
            
            $array[$i]['operacion_cancelada'] = $value['operacion_cancelada'];
            $array[$i]['operacion_gratuita'] = $value['operacion_gratuita'];
            $array[$i]['porcentaje_de_detraccion'] = $value['porcentaje_de_detraccion'];
            $array[$i]['total_detraccion'] = $value['total_detraccion'];
            $array[$i]['cliente_id'] = $value['cliente_id'];
            $array[$i]['tipo_nota_codigo'] = $value['tipo_nota_codigo'];
            $array[$i]['moneda_id'] = $value['moneda_id'];
            $array[$i]['tipo_de_cambio'] = $value['tipo_de_cambio'];
            $array[$i]['observaciones'] = $value['observaciones'];
            $array[$i]['anulado'] = $value['anulado'];
            
            $array[$i]['enviado_sunat'] = $value['enviado_sunat'];
            $array[$i]['estado_sunat'] = $value['estado_sunat'];
            $array[$i]['enviado_cliente'] = $value['enviado_cliente'];
            $array[$i]['enviado_equipo'] = $value['enviado_equipo'];
            $array[$i]['cliente_ruc'] = $value['cliente_ruc'];
            $array[$i]['tipo_cliente_id'] = $value['tipo_cliente_id'];
            $array[$i]['cli_nombres'] = $value['cli_nombres'];
            $array[$i]['cli_razon_social'] = $value['cli_razon_social'];
            $array[$i]['razon_social'] = $value['razon_social'];
            $array[$i]['cli_razon_social_sunat'] = $value['cli_razon_social_sunat'];
            
            $array[$i]['cli_domicilio1'] = $value['cli_domicilio1'];
            $array[$i]['cli_email'] = $value['cli_email'];
            $array[$i]['tipo_cliente_codigo'] = $value['tipo_cliente_codigo'];
            $array[$i]['tipo_documento_codigo'] = $value['tipo_documento_codigo'];
            $array[$i]['tipo_documento'] = $value['tipo_documento'];
            $array[$i]['eee_id'] = $value['eee_id'];
            $array[$i]['codigo'] = $value['codigo'];
            $array[$i]['elemento_adicional_descripcion'] = $value['elemento_adicional_descripcion'];
            $array[$i]['moneda'] = $value['moneda'];
            $array[$i]['abrstandar'] = $value['abrstandar'];
            
            $array[$i]['simbolo'] = $value['simbolo'];
            $array[$i]['cdr_exis'] = $this->existeCDR($value['empresa_id'], $value['empresa_ruc'], $value['tipo_documento_codigo'], $value['serie'], $value['numero']);
            $i++;
        }
        //var_dump($array);exit;
        return $array;        
    }
    
    public function existeCDR($empresa_id, $ruc_emisor, $tipo_documento_codigo, $serie, $numero){
        $nombre_fichero = "files/facturacion_electronica/CDR/".$ruc_emisor."-".$tipo_documento_codigo."-".$serie."-".$numero.".xml";

        $existe = 0;
        if (file_exists($nombre_fichero)) {                        
            $existe = 1;
        }
        return $existe;
    }

    public function guardarAnticipo()
    {
        $this->db->where("id", $_POST['anticipo_id']);
        $query = $this->db->get("comprobantes");
        $rsComprobante = $query->row();
        if($rsComprobante)
        {
            $datos = (object)[
                        'id'              => $rsComprobante->id,
                        'anticipo_numero' => $rsComprobante->serie.'-'.$rsComprobante->numero,
                        'anticipo_total'  => $rsComprobante->total_a_pagar
                     ];        
            $anticipos = $this->session->userdata("comprobantes_anticipos");
            $anticipos[$rsComprobante->id] = $datos;
            $this->session->set_userdata("comprobantes_anticipos", $anticipos);  
            //calculamos el total
            $totalAnticipos = 0;
            foreach($this->session->userdata("comprobantes_anticipos") as $anticipo)
            {
                $totalAnticipos += $anticipo->anticipo_total;
            }

            sendJsonData(['status'=>STATUS_OK, 'totalAnticipo'=>round($totalAnticipos,2)]);        


        }else
        {
            sendJsonData(['status'=>STATUS_FAIL, 'tipo'=>4]);
            exit();
        }

    }
    
    public function listaAnticiposClientes()
    {
        //obtenemos la lista de anticipos del usuario
        $anticiposAgregados = array();
        foreach($this->session->userdata("comprobantes_anticipos") as $index => $value)
        {
            $anticiposAgregados[] = $index;
        }
        $this->db->where('cliente_id', $_POST['cliente']);
        $this->db->where('comprobante_anticipo', 1);
        $this->db->where('comprobante_anticipo_usado', 0);
        if(count($anticiposAgregados) > 0)
        {
            $this->db->where_not_in('id',$anticiposAgregados);
        }
        $this->db->order_by('id','DESC');
        $query = $this->db->get("comprobantes");
        $rsAnticipos = $query->result();
        return $rsAnticipos;
    }

    public function exist_xml($comprobante_id) {
       $rsComprobante = $this->db->select("comp.serie as serie,comp.numero as numero, tdoc.codigo as codigo, emp.ruc as empresa_ruc")
                                  ->from("comprobantes as comp")
                                  ->join("tipo_documentos as tdoc", "comp.tipo_documento_id=tdoc.id")
                                  ->join("empresas as emp", "comp.empresa_id=emp.id")
                                  ->where("comp.id", $comprobante_id)
                                  ->get()
                                  ->row();

        $archivoXML = "{$rsComprobante->empresa_ruc}-{$rsComprobante->codigo}-{$rsComprobante->serie}-{$rsComprobante->numero}.xml";

        $rutaFirma = DISCO.":/SFS_v1.2/sunat_archivos/sfs/PARSE/{$archivoXML}";

        if (file_exists($rutaFirma)) {
            return true;
        
        } else {
            return false;
        }
    }

    public function getdash($fecha_inicio='',$fecha_fin=''){

        $this->db->select('fecha_de_emision,tipo_documento_id as doc,tipo_pago_id as pago,moneda_id as moneda,serie,total_a_pagar as total');
        ($fecha_inicio!='')?$this->db->where("DATE(fecha_de_emision) >=",$fecha_inicio):'';
        ($fecha_fin!='')?$this->db->where("DATE(fecha_de_emision) <=",$fecha_fin):'';
        $res= $this->db->from('comprobantes')->get()->result();
        
        $datos = [];
        $datos['e_boleta_soles']  =0.00;
        $datos['cantebsoles']=0;
        $datos['e_boleta_dolar']  =0.00;
        $datos['cantebdolar'] =0 ;
        $datos['t_boleta_soles']  =0.00;
        $datos['canttbsoles'] =0;
        $datos['t_boleta_dolar']  =0.00;
        $datos['canttbdolar'] =0;
        $datos['e_factura_soles']  =0.00;
        $datos['cantefsoles']=0;
        $datos['e_factura_dolar']  =0.00;
        $datos['cantefdolar']=0;
        $datos['t_factura_soles']  =0.00;
        $datos['canttfsoles']=0;
        $datos['t_factura_dolar']  =0.00;
        $datos['canttfdolar']=0;

        foreach($res as $comprobante){
            switch ($comprobante->doc) 
            {
                /* FACTURA */ 
                case 1:
                     /*soles*/
                    if ($comprobante->moneda==1) {  
                        /*Efectivo*/
                        if ($comprobante->pago==1) {   
                            $datos['e_factura_soles'] += $comprobante->total;
                            $datos['cantefsoles']++;
                        }

                        /*Tarjeta*/
                        if ($comprobante->pago==2) {  
                            $datos['t_factura_soles'] += $comprobante->total;
                            $datos['canttfsoles']++;
                        }
                    } 

                    /*dolar*/
                    if($comprobante->moneda==2) { 
                        /*Efectivo*/ 
                        if ($comprobante->pago==1) { 
                            $datos['e_factura_dolar'] += $comprobante->total;
                            $datos['cantefdolar']++;    
                        }
                        
                        /*tarjeta*/
                        if ($comprobante->pago==2) { 
                            $datos['t_factura_dolar'] += $comprobante->total;
                            $datos['canttfdolar']++;
                        }
                    }
                break;
               
                /* BOLETA */
                case 3:
                     /*soles*/
                    if ($comprobante->moneda==1) {   
                        /*Efectivo*/
                        if ($comprobante->pago==1) {
                            $datos['e_boleta_soles'] += $comprobante->total;
                            $datos['cantebsoles']++;
                        } 
                        /*tarjeta*/
                        if ($comprobante->pago==2) {
                            $datos['t_boleta_soles'] += $comprobante->total;
                            $datos['canttbsoles']++;
                        }
                        
                    }

                    /*dolar*/
                    if ($comprobante->moneda==2) {
                        /*Efectivo*/
                        if ($comprobante->pago==1) {
                            $datos['e_boleta_dolar'] += $comprobante->total;
                            $datos['cantebdolar']++;
                        }

                        /*tarjeta*/ 
                        if ($comprobante->pago==2) {
                            $datos['t_boleta_dolar'] += $comprobante->total;
                            $datos['canttbdolar']++;
                        }
                        
                    }   
                   
                break;
            }
        }

        return $datos;

    }
    public function getMainList() {

        if (isset($_POST['cliente']) && !empty($_POST['cliente'])) {
            $this->db->where('comp.cliente_id',$_POST['cliente']);
        }

        if($_POST['fecha_desde']!='')
        {
            $this->db->where("DATE_FORMAT(comp.fecha_de_emision, '%d-%m-%Y') >=", $_POST['fecha_desde']);
        }
        if($_POST['fecha_hasta']!='')
        {
            $this->db->where("DATE_FORMAT(comp.fecha_de_emision, '%d-%m-%Y') <=", $_POST['fecha_hasta']);
        }

        if (isset($_POST['serie']) && !empty($_POST['serie'])) {
            $this->db->where('comp.serie',$_POST['serie']);
        }
        if (isset($_POST['numero']) && !empty($_POST['numero'])) {
            $this->db->where('comp.numero',$_POST['numero']);
        }
        if (isset($_POST['numero_pedido']) && !empty($_POST['numero_pedido'])) {
            $this->db->where('comp.numero_pedido',$_POST['numero_pedido']);
        }
        if (isset($_POST['orden_compra']) && !empty($_POST['orden_compra'])) {
            $this->db->where('comp.orden_compra',$_POST['orden_compra']);
        }
        if (isset($_POST['numero_guia']) && !empty($_POST['numero_guia'])) {
            $this->db->where('comp.numero_guia_remision',$_POST['numero_guia']);
        }
        
        if ($this->session->userdata('empleado_id')!=1) {            
            //$this->db->where('comp.empleado_insert', $this->session->userdata('empleado_id'));
        }

        if (isset($_POST['vendedor']) && !empty($_POST['vendedor'])) {
            $this->db->where('comp.empleado_select',$_POST['vendedor']);
        }

        if($_POST['tipo_documento']!='')
        {
            $this->db->where("comp.tipo_documento_id", $_POST['tipo_documento']);
        }
        /*$this->db->where('comp.comp_estado', ST_ACTIVO);*/

        $select = $this->db->select('emp.ruc as ruc, comp.id as comp_id,cli.prov_razon_social as cliente,comp.tipo_documento_id, tpd.codigo as codigo,
                                    tpd.tipo_documento as documento,comp.total_gravada as total_gravada,comp.fecha_de_emision as fecha_de_emision,
                                    comp.fecha_de_vencimiento as fecha_de_vencimiento, comp.enviado_sunat as enviado_sunat,comp.estado_sunat,
                                    comp.cliente_id as cliente_id, comp.anulado as anulado,comp.serie as serie,comp.numero as numero,
                                    comp.total_igv as total_igv,comp.total_a_pagar,CONCAT(e.nombre," ",e.apellido_paterno) as vendedor',false)
                           ->from("comprobantes_compras comp")
                           ->join("proveedores cli", "comp.cliente_id = cli.prov_id")
                           ->join("tipo_documentos tpd", "comp.tipo_documento_id = tpd.id")
                           ->join("empresas emp", "comp.empresa_id=emp.id")
                           ->join("empleados e", "e.id=comp.empleado_select")
                           ->where("comp.venta_almacen_id", $this->session->userdata("almacen_id"))
                           ->order_by("comp.fecha_de_emision", "desc");
                           //->get()
                           //->result();
        
        
        /*obtener el total*/
        $selectCount = clone $select;
        $rsCount = $selectCount->get()
                               ->result();
        $rows = count($rsCount);                       

        $rsComprobante = $select->limit($_POST['pageSize'], $_POST['skip'])
                                ->get()
                                ->result();   

        $i=1;        
       /*echo $this->db->last_query();*/
        /*print_r($rsComprobante);exit();*/
         
        foreach($rsComprobante as $comprobante)  {


                   $comprobante->btn_xml = '<span class="glyphicon glyphicon-remove"></span>';

                        $comprobante->btn_mail = '<span class="glyphicon glyphicon-remove"></span>';
                        $comprobante->btn_cdr = '<span class="glyphicon glyphicon-remove"></span>';
                        $comprobante->num_rows = $i;
                        $comprobante->cliente =$comprobante->cliente;
                        $comprobante->Tipo_doc =$comprobante->documento;
                        $comprobante->Monto_bruto =$comprobante->total_gravada;
                        $comprobante->num_doc = $comprobante->serie."-".$comprobante->numero;

                        $comprobante->fecha_de_emision = (new DateTime($comprobante->fecha_de_emision))->format("d/m/Y");
                        $comprobante->fecha_de_vencimiento = (new DateTime($comprobante->fecha_de_vencimiento))->format("d/m/Y");

                        $comprobante->btn_pdf = '<a class="show_pdf" title="ver pdf" href="#" idval="'.$comprobante->comp_id.'" > <img title="Ver Pdf" src="'.base_url().'/images/pdf.png"> </a>';
  

                if ($comprobante->anulado == 1) {         
                        $comprobante->btn_estado_sunat = '<label><span>Anulado</span></label>';
                        //$comprobante->btn_xml = '<a class="_dow_xml" target="_blank" href="'.base_url().'index.php/comprobantes/dowload_xml/'.$comprobante->comp_id.'"><span class="glyphicon glyphicon-file esunat"></span></a>';
                        $comprobante->btn_xml = '<span class="glyphicon glyphicon-remove"></span>';

                        $comprobante->btn_mail = '<span class="glyphicon glyphicon-remove"></span>';
                        $comprobante->btn_cdr = '<span class="glyphicon glyphicon-remove"></span>';

                   $comprobante->btn_action ='<div class="btn-group">
                      <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Anulado </button></div>';

                } else {

                   
                 

                  //$comprobante->btn_action = '<div class="btn-group"><button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"> Accion <span class="caret"></span> </button><ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu2"><li><a href="'.base_url().'index.php/comprobantes_compras/modificar/'.$comprobante->comp_id.'"> Modificar </a></li> <li><a href="'.base_url().'index.php/comprobantes_compras/txt/1/'.$comprobante->comp_id.'" >Anular </a></li></ul></div>';

                  /* $comprobante->btn_action = '<div class="btn-group"><button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"> Accion <span class="caret"></span> </button><ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu2"><li></li> <li><a href="'.base_url().'index.php/comprobantes_compras/txt/1/'.$comprobante->comp_id.'" >Anular </a></li></ul></div>';*/

                    $comprobante->btn_action ='<div class="btn-group">
                      <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border:0;"> &nbsp;&nbsp;&nbsp;Accion&nbsp;&nbsp; <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu2">';

                      //$comprobante->btn_action = '<li><a href="'.base_url().'index.php/comprobantes_compras/modificar/'.$comprobante->comp_id.'"> Modificar </a></li>';

                      $comprobante->btn_action.= '<li><a  class="delete" idval="'.$comprobante->comp_id.'"> Eliminar </a></li>'; 
                      $comprobante->btn_action.= '</ul></div>';



                }
                

           

           
            $i++;            
        } 
   


        $datos = [
                    'data' => $rsComprobante,
                    'rows' => $rows
                 ];

        return $datos;      
    }



   //////// LE //////////
    public function getComprobantes($where){
      $this->db->select('com.*,cli.ruc,cli.razon_social,cli.tipo_cliente_id,mo.abrstandar,mo.id moneda_id,tcli.codigo as tipo_cliente_codigo,td.codigo as tipo_documento_codigo');
      $this->db->from('comprobantes com');
      $this->db->join('clientes cli','cli.id = com.cliente_id');
      $this->db->join('tipo_clientes tcli','tcli.id = cli.tipo_cliente_id');
      $this->db->join('monedas mo','mo.id = com.moneda_id');
      $this->db->join('tipo_documentos td','td.id = com.tipo_documento_id');
      $this->db->order_by('com.id','ASC');
      $this->db->where($where);
      $result = $this->db->get();
      $json =  $result->result();
      return $json;
    }


       //////// LE //////////
    public function getCompras($where){
        $this->db->select('com.*,cli.prov_ruc,cli.prov_razon_social,mo.abrstandar,mo.id moneda_id,td.codigo as tipo_documento_codigo');
      $this->db->from('comprobantes_compras com');
      $this->db->join('proveedores cli','cli.prov_id = com.cliente_id');
      //$this->db->join('tipo_clientes tcli','tcli.id = cli.tipo_cliente_id');
      $this->db->join('monedas mo','mo.id = com.moneda_id');
      $this->db->join('tipo_documentos td','td.id = com.tipo_documento_id');
      $this->db->order_by('com.id','ASC');
      $this->db->where($where);
      $result = $this->db->get();
      $json =  $result->result();
      return $json;
    }













}