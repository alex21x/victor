<?PHP

class Tipo_clientes_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function select($id = '', $tipo_cliente = '',$activo = ''){
        if ($id != '') {
            $sql = "SELECT *FROM tipo_clientes
                    WHERE id = " . $id;
            $query = mysql_query($sql);
            return mysql_fetch_assoc($query);
        }

        $where = '';
        $where   = ($tipo_cliente != '') ? " AND tipo_cliente = ".$tipo_cliente : '';
        $where  .= ($activo != '') ?  " AND activo = '" . $activo."' " : '';
        
        $sql = "SELECT *FROM tipo_clientes WHERE 1 = 1  ".$where;
        $query = $this->db->query($sql);
        
        return $query->result_array();
    }

}