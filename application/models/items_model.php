<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Items_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }
    
    public function select_2($modo, $select = array(), $condicion = array(), $order = '') {

        if ($select == '')
            $select = array();
        if ($condicion == '')
            $condicion = array();

        $where = '';
        foreach ($condicion as $key => $value) {
            if ($value == 'IS NULL') {
                $where .= " AND $key " . $value;
            } else {
                $where .= " AND $key = '" . $value . "' ";
            }
        }

        $campos = ($select == array()) ? '*' : implode(", ", $select);
        $sql = "SELECT ctt.nombre nombre_tributo, ctt.codigo_internacional, ite.id item_id, ite.producto_id,  ite.comprobante_id,  ite.categoria_id,  ite.unidad_id,  ite.descripcion, ite.cantidad,  ite.tipo_igv_id,  ite.precio_base,  ite.importe,  ite.subtotal, ite.igv, ite.total, ite.eliminado, ite.descuento, tig.codigo, tig.codigo_de_tributo 
            FROM items ite 
            JOIN tipo_igv tig ON ite.tipo_igv_id = tig.id
            JOIN codigo_tipo_tributos ctt ON ctt.codigo = tig.codigo_de_tributo
            WHERE 1 = 1 
            " . $where . " " . $order;
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
    
     
    public function select($id = '',$comprobante_id = '') {        
         if($id != ''){
            $sql = "SELECT * FROM items WHERE id = ".$id;            
            $query = $this->db->query($sql);
            
            return $query->result_array();
        }
        
        $where = '';
        if($comprobante_id != ''){$where.= ' AND comprobante_id = '.$comprobante_id;}                                
        
        
            $sql = "SELECT *,com.id, its.producto_id,its.categoria_id,its.unidad_id,pr.prod_codigo,tp.tipo_pago,comprobante_id,its.id item_id,"
                    . " tig.codigo tipo_igv_codigo, med.medida_nombre,med.medida_codigo_unidad unidad "
                    . " FROM items its"
                    . " JOIN comprobantes com"
                    . " ON its.comprobante_id = com.id"
                    . " JOIN medida as med"
                    . " ON med.medida_id = its.unidad_id"
                    . " JOIN tipo_pagos tp"
                    . " ON com.tipo_pago_id = tp.id"
                    . " JOIN tipo_igv tig"
                    . " ON its.tipo_igv_id = tig.id"
                    . " LEFT JOIN productos pr"
                    . " ON its.producto_id = pr.prod_id"
                    . " WHERE 1=1 ".$where.' AND its.eliminado = 0 ORDER BY its.id ASC';
            
            $query = $this->db->query($sql);
            //var_dump($query->result());exit;
            return $query->result_array();                
    }

    public function insertar($data) {
        $this->db->insert('items', $data);        
        $this->session->set_flashdata('mensaje', 'Contacto: Ingresado exitosamente');
        //return mysql_insert_id();
    }

    public function modificar($data, $where){
        $this->db->where('id',$where);
        $this->db->update('items', $data);
        $this->session->set_flashdata('mensaje', 'contacto modificado exitosamente');
    }

    public function eliminar($id){  
        $sql_eli = "UPDATE items SET eliminado = 1 WHERE id = " . $id;
        $this->db->query($sql_eli);
        $this->session->set_flashdata('mensaje', 'Item eliminado exitosamente');
    }        
}
