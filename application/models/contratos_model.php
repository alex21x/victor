<?PHP if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contratos_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function insertar($data, $mensaje) {
        $this->db->insert('contratos', $data);        
        if($mensaje != ''){
            $this->session->set_flashdata('mensaje', $mensaje);
        }
    }
    public function insertar_id($data, $mensaje) {
        $this->db->insert('contratos', $data);        
        $last_id = $this->db->insert_id();        
        if($mensaje != ''){
            $this->session->set_flashdata('mensaje', $mensaje);
        }                        
        return $last_id;
    }
    
    public function modificar($id, $data, $mensaje){
        $this->db->where('id', $id);
        $this->db->update('contratos', $data);        
        //echo $this->db->last_query();exit;
        $this->session->set_flashdata('mensaje', $mensaje);
    }

    public function select($id = '', $tipo_contrato_id = '', $activo = '', $cliente_id = '') {
        if ($id != '') {
            $sql = "SELECT *, DATE_FORMAT(fecha_inicio, '%d-%m-%Y') AS fecha_inicio, DATE_FORMAT(fecha_fin, '%d-%m-%Y') AS fecha_fin, contratos.id contrato_id,
                    contratos.activo contrato_activo
                    FROM contratos
                    JOIN monedas
                    on contratos.moneda_id = monedas.id
                    WHERE contratos.id = " . $id;
            $query = mysql_query($sql);
            return mysql_fetch_assoc($query);
        }

        $where = '';
        if ($activo != '') { $where .= " AND contratos.activo = '" . $activo."' ";}
        if ($tipo_contrato_id != '') { $where .= " AND tipo_contrato_id = " . $tipo_contrato_id;}
        if ($cliente_id != '') { $where .= " AND cliente_id = " . $cliente_id;}

        $sql = "SELECT *, DATE_FORMAT(fecha_inicio, '%d-%m-%Y') AS fecha_inicio, DATE_FORMAT(fecha_fin, '%d-%m-%Y') AS fecha_fin, contratos.id contrato_id,contratos.activo contrato_activo
                FROM contratos
                JOIN monedas
                ON contratos.moneda_id = monedas.id
                WHERE 1 = 1 " . $where;

        $query = mysql_query($sql);
        $rows = array();
        while ($row = mysql_fetch_assoc($query)) {
            $rows[] = $row;
        }
        return $rows;
    }
    
    public function selectConAbogadoResponsable($id) {
            $sql = "SELECT *, con.id contrato_id,
                    con.activo contrato_activo
                    FROM contratos con LEFT JOIN empleados
                    ON (empleado_id_responsable = empleados.id)
                    JOIN monedas mon
                    ON con.moneda_id = mon.id
                    WHERE con.id = " . $id;
            $query = mysql_query($sql);
            return mysql_fetch_assoc($query);

    }
    
    public function selectCargaCombo($tipo_contrato_id = '', $activo = '', $cliente_id = '') {        
        $where = '';
        if ($activo != '') { $where .= " AND contratos.activo = '" . $activo."' ";}
        if ($tipo_contrato_id != '') { $where .= " AND tipo_contrato_id = " . $tipo_contrato_id;}
        if ($cliente_id != '') { $where .= " AND cliente_id = " . $cliente_id;}

        $sql = "SELECT id contra_id, titulo
                FROM contratos                
                WHERE 1 = 1 " . $where;

        $query = mysql_query($sql);
        $rows = array();
        while ($row = mysql_fetch_assoc($query)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function eliminar($id) {
        $sql = "SELECT *FROM actividades WHERE contrato_id = " . $id;
        $query = mysql_query($sql);        

        if (mysql_num_rows($query) > 0) {
            $this->session->set_flashdata('mensaje', 'No se pudo eliminar el Contrato porque tiene actividades ingresadas.');
        } else {
            $sql_eli = "DELETE FROM contratos WHERE id = " . $id;
            mysql_query($sql_eli);
            $this->session->set_flashdata('mensaje', 'Contrato eliminado exitosamente');
        }
    }

}