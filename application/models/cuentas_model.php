<?PHP

class Cuentas_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

        // $modo: 1 = campo (1 solo campo), 2 = registro (mas de un campo), 2 = tabla (mas de 1 registro)
    public function select($modo, $select = array(), $condicion = array(), $order = '') {

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
        $sql = "SELECT " . $campos . " FROM cuentas WHERE 1 = 1 " . $where . " " . $order;
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
    
    public function formatCuentas($data){
        
        $cuentas = array();
        foreach ($data as $values){
            $cuentas[$values['empresa_id']][$values['banco_id']][$values['moneda_id']]['cuenta'] = $values['cuenta'];
            $cuentas[$values['empresa_id']][$values['banco_id']][$values['moneda_id']]['interbancario'] = $values['interbancario'];
        }
        return $cuentas;
    }
}
