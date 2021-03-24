<?PHP if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Unidad_medida extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function select($tipo_item = ''){
        $where = '';
        if ($tipo_item != '') {$where .= " AND tipo_item = " . $tipo_item;}

        $sql = "SELECT *FROM tipo_items                 
                WHERE 1 = 1 ".$where;

        $query = $this->db->query($sql)  ;
        $rows = array();
        foreach($query->result_array() as $row){
            $rows[] = $row;
        }
        return $rows;
    }
    public function ListarMedida() {
        $result = $this->db->from('medida')
                        ->get()
                        ->result();
        return $result;
    }
}   