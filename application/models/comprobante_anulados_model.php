<?PHP

class Comprobante_anulados_model extends CI_Model {

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
        $sql = "SELECT " . $campos . " FROM comprobante_anulados WHERE 1 = 1 " . $where . " " . $order;
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

    //hallar maximo numero de una fecha
    public function maxNumero($fecha){
//        $sql = "SELECT MAX(numero) numero FROM `comprobante_anulados` WHERE fecha = '" . $fecha . "'";
//        $query = mysql_query($sql);
//
//        $resultado = mysql_result($query, 0, "numero");
//        if($resultado == NULL) $resultado = 0;
        
        $sql = "SELECT MAX(numero) numero FROM `comprobante_anulados` WHERE fecha = '" . $fecha . "'";
        $query = $this->db->query($sql);           
        $row = $query->row_array();
        $resultado = $row['numero'];
        if($resultado == NULL) $resultado = 0;

        return $resultado;
    }

    public function insertar($data, $mensaje = '') {
        if($mensaje == '') $mensaje = 'Registro ingresado correctament';

        $this->db->insert('comprobante_anulados',$data);
        $this->session->set_flashdata('mensaje',$mensaje);
    }

    public function modificar($id,$data) {
        $this->db->where('id', $id);
        $rs = $this->db->update('comprobante_anulados',$data);

        if (!$rs)
            $this->session->set_flashdata('mensaje', 'Error '.$this->db->_error_message());
        else
            $this->session->set_flashdata('mensaje', 'Registro actualizado correctamente');
    }

}