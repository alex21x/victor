<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Items_orden_compras_model extends CI_Model {

    public function __construct() {
        parent::__construct();
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
                    . " tig.codigo tipo_igv_codigo"
                    . " FROM items_orden_compras its"
                    . " JOIN comprobantes_orden_compras com"
                    . " ON its.comprobante_id = com.id"
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
        $this->db->insert('items_orden_compras', $data);        
        $this->session->set_flashdata('mensaje', 'Contacto: Ingresado exitosamente');
        //return mysql_insert_id();
    }

    public function modificar($data, $where){
        $this->db->where('id',$where);
        $this->db->update('items_orden_compras', $data);
        $this->session->set_flashdata('mensaje', 'contacto modificado exitosamente');
    }

    public function eliminar($id){  
        $sql_eli = "UPDATE items_orden_compras SET eliminado = 1 WHERE id = " . $id;
        $this->db->query($sql_eli);
        $this->session->set_flashdata('mensaje', 'Item eliminado exitosamente');
    }        
}
