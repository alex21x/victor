<?PHP
    if(!defined('BASEPATH'))
        exit('No direct script access allowed');
    
    class Tipo_ndebitos_model extends CI_Model{
        
        public function __construct() {
            parent::__construct();
            $this->load->database();
        }
        
        public function select($id='',$codigo='',$tipo_ndebito='',$eliminado='') {
            if($id !=''){
                $sql = "SELECT *FROM tipo_ndebitos WHERE id=".$id;
                $query = $this->db->query($sql);
                return $query->row_array();
            }
            
            $where = '';
            if($codigo !='')      {$where.=" AND codigo = ". $codigo;}
            if($tipo_ndebito !=''){$where.=" AND tipo_ndebito = ". $tipo_ndebito;}
            if($eliminado !='')   {$where.=" AND eliminado = ". $eliminado;}
            
            $sql = "SELECT *from tipo_ndebitos where 1=1 ". $where;
                        
            $query = $this->db->query($sql);
            return $query->result_array();
        }
    }

?>