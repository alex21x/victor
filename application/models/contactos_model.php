<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contactos_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }
    
    public function select($id = '', $cliente_id = '', $activo = '', $apellido_paterno = '', $apellido_materno = '', $nombres = '', $eliminado = ''){
        if ($id != '') {
            $sql = "SELECT *, DATE_FORMAT(fecha_nacimiento, '%d-%m-%Y') AS fecha_nacimiento FROM contactos
                    WHERE id = " . $id;
            $query = mysql_query($sql);
            return mysql_fetch_assoc($query);
        }
        
        $where = '';
        $where .= ($cliente_id != '') ? " AND cliente_id = ".$cliente_id : '';
        $where .= ($activo != '') ? " AND activo = '".$activo."' " : '';
        $where .= ($apellido_paterno != '') ? " AND apellido_paterno = ".$apellido_paterno : '';
        $where .= ($apellido_materno != '') ? " AND apellido_materno = ".$apellido_materno : '';
        $where .= ($nombres != '') ? " AND nombres = ".$nombres : '';
        $where .= ($eliminado != '') ? " AND eliminado = ".$eliminado : '';

        $sql = "SELECT *FROM contactos WHERE 1 = 1 " . $where .  " ORDER BY contactos.`apellido_paterno`, `contactos`.`apellido_materno`, `contactos`.`nombres` ";
        $query = mysql_query($sql);
        $rows = array();
        while ($row = mysql_fetch_assoc($query)){
            $rows[] = $row;
        }
        return $rows;
    }
    
    public function selectCargarCombo($id = '', $cliente_id = '', $activo = '', $apellido_paterno = '', $apellido_materno = '', $nombres = '', $eliminado = ''){
        if ($id != '') {
            $sql = "SELECT id, apellido_paterno, apellido_materno, nombres FROM contactos
                    WHERE id = " . $id;
            $query = mysql_query($sql);
            return mysql_fetch_assoc($query);
        }
        
        $where = '';
        $where .= ($cliente_id != '') ? " AND cliente_id = ".$cliente_id : '';
        $where .= ($activo != '') ? " AND activo = '".$activo."' " : '';
        $where .= ($apellido_paterno != '') ? " AND apellido_paterno = ".$apellido_paterno : '';
        $where .= ($apellido_materno != '') ? " AND apellido_materno = ".$apellido_materno : '';
        $where .= ($nombres != '') ? " AND nombres = ".$nombres : '';
        $where .= ($eliminado != '') ? " AND eliminado = ".$eliminado : '';
        $sql = "SELECT id, apellido_paterno, apellido_materno, nombres FROM contactos WHERE 1 = 1 " . $where .  " ORDER BY contactos.`apellido_paterno`, `contactos`.`apellido_materno`, `contactos`.`nombres` ";
        $query = mysql_query($sql);
        $rows = array();
        while ($row = mysql_fetch_assoc($query)){
            $rows[] = $row;
        }
        return $rows;
    }

    public function insertar($data) {
        $this->db->insert('contactos', $data);        
        $this->session->set_flashdata('mensaje', 'Contacto: Ingresado exitosamente');
        return mysql_insert_id();
    }

    public function modificar($data, $where){
        $this->db->where('id',$where);
        $this->db->update('contactos', $data);
        $this->session->set_flashdata('mensaje', 'contacto modificado exitosamente');
    }

    public function eliminar($id, $apellido_paterno){  
        $sql_eli = "UPDATE contactos SET eliminado = 1 WHERE id = " . $id;
        mysql_query($sql_eli);
        $this->session->set_flashdata('mensaje', 'contacto: '. $apellido_paterno .' eliminado exitosamente');
    }

    public function selectAutocomplete($buscar, $cliente_id = ''){
        $where = ($cliente_id != '') ? " AND cliente_id = ".$cliente_id : '';

        $sql = "SELECT
                id,
                apellido_paterno,
                apellido_materno,
                nombres
                FROM contactos
                WHERE 
                ( apellido_paterno LIKE '%$buscar%'
                OR apellido_materno LIKE '%$buscar%'
                OR nombres LIKE '%$buscar%' ) " . $where . "
                ORDER BY apellido_paterno, apellido_materno, nombres";
        $query = mysql_query($sql);

        $data = array();
        if (mysql_num_rows($query) > 0) {
            while ($tsArray = mysql_fetch_assoc($query)){
                $data[] = array(
                    "value" => $tsArray['apellido_paterno'] . ' ' . $tsArray['apellido_materno'] . ', ' . $tsArray['nombres'],
                    "apellido_paterno" => $tsArray['apellido_paterno'],
                    "apellido_materno" => $tsArray['apellido_materno'],
                    "nombres" => $tsArray['nombres'],
                    "id" => $tsArray['id']
                );
            }
        }
        return $data;
    }
}