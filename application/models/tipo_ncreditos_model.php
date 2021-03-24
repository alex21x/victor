<?PHP
    if(!defined('BASEPATH'))
        exit('No direct script access allowed');
    
    class Tipo_ncreditos_model extends CI_Model{
        
        public function __construct() {
            parent::__construct();
            $this->load->database();
        }
        
        public function select($id='',$codigo='',$tipo_ncredito='',$eliminado='') {
            if($id !=''){
                $sql = "SELECT *FROM tipo_ncreditos WHERE id =".$id;
                $query = $this->db->query($sql);
                return $query->row_array();
            }
            
            $where = '';
            if($codigo !='')       {$where.=" AND codigo = ". $codigo;}
            if($tipo_ncredito !=''){$where.=" AND tipo_ncredito = ". $tipo_ncredito;}
            if($eliminado !='')    {$where.=" AND eliminado = ". $eliminado;}
            
            $sql = "SELECT *from tipo_ncreditos where 1=1 ". $where;
                        
            $query = $this->db->query($sql);            
            return $query->result_array();
        }
    }

?>