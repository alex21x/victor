<?PHP

class Comprobantes_comp_cli_per_model extends CI_Model {

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
        $sql = "SELECT " . $campos . " FROM comprobantes_comp_cli_per WHERE 1 = 1 " . $where . " " . $order;
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
    
    public function insertar($data, $mensaje = '') {  
        
        $this->db->insert('comprobantes_comp_cli_per', $data);
        if($mensaje == ''){
            $mensaje = "operación correcta.";
        }
        $this->session->set_flashdata('mensaje', $mensaje);
    }
    
    public function modificar($comprobante_id, $comp_cli_per_id) {
        $sql = "UPDATE comprobantes_comp_cli_per SET fecha_eliminado = " . date("Y-m-d H-i-s") . " WHERE comprobante_id = " . $comprobante_id . " AND comp_cli_per_id = " . $comp_cli_per_id;
        $query = mysql_query($sql);        
        $this->session->set_flashdata('mensaje', 'Registro actualizado correctamente');
    }
    

}
