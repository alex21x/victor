<?PHP

class Empresas_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function select($id = '', $activo = ''){
        if ($id != '') {
            $sql = "SELECT *FROM empresas
                    WHERE id = " . $id;
            $query = $this->db->query($sql);
            return $query->row_array();
        }

        $where = '';
        $where = ($activo != '') ? " AND activo = ".$activo : '';

        $sql = "SELECT *FROM empresas WHERE 1 = 1 ".$where;
        $query = $this->db->query($sql);
       
        //var_dump($rows);exit;        
        return $query->result_array();
    }
    
    public function modificar($id,$data) {
        $this->db->where('id', $id);
        $rs = $this->db->update('empresas',$data);

        if (!$rs)
            $this->session->set_flashdata('mensaje', 'Error '.$this->db->_error_message());
        else
            $this->session->set_flashdata('mensaje', 'Operación realizada correctamente');
    }
    
    public function save($data, $mensaje = '') {          
        $this->db->insert('empresas', $data);
        echo $this->db->last_query();
        if($mensaje != ''){
            $this->session->set_flashdata('mensaje_cliente_index', $mensaje);
        }
    }

    public function getEmpresa(){
      $result = $this->db->get('empresas');
      $json = $result->result();
      return $json;
    }

}