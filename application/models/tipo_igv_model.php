<?PHP
    if(!defined('BASEPATH'))
        exit('No direct script access allowed');
    
    class Tipo_igv_model extends CI_Model{
        
        public function __construct() {
            parent::__construct();
            $this->load->database();
        }
        
        public function select($id='',$codigo='',$tipo_igv='',$eliminado='') {                       
            if($id !=''){
                $sql = "SELECT *from tipo_igv";
                $query = mysql_query($sql);
                return mysql_fetch_assoc($query);
            }
            
            $where = '';
            if($codigo !=''){$where.=" AND codigo = ". $codigo;}
            if($tipo_igv !=''){$where.=" AND tipo_igv = ". $tipo_igv;}
            if($eliminado !=''){$where.=" AND eliminado = ". $eliminado;}
            
            $sql = "SELECT *from tipo_igv where 1=1 ". $where;
                        
            $query = $this->db->query($sql);            
            $rows = array();
            foreach($query->result_array() as $row){
                    $rows[] = $row;
            }
            
            return $rows;
        }
    }

?>