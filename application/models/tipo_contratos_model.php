<?php

class Tipo_contratos_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function select($id = FALSE) {
        if ($id != FALSE) {
            $sql = "SELECT *FROM tipo_contratos WHERE id = " . $id;            
            $query = mysql_query($sql);
            return mysql_fetch_assoc($query);            
        }
        
        $sql = "SELECT *FROM tipo_contratos ORDER BY orden";
        $query = $this->db->query($sql);
        
        return $query->result_array();
    }

}