<?PHP

    if(!defined('BASEPATH'))
        exit ('No direct script access allowed');
    
    
    class Tipo_documentos_model extends CI_Model {
     
        public function __construct() {
            parent::__construct();
            $this->load->database();
        }
        
        
        public function select($id = '' , $tipo_documento = '',$documentosId= '') {
            
            if($id != ''){
                $sql = "SELECT *FROM tipo_documentos
                        WHERE id = ". $id;                
                $query = mysql_query($sql);
                return mysql_fetch_assoc($query);
            }
            
            $where = '';
            $where.= ($tipo_documento != '') ? " AND tipo_documento LIKE  '%".$tipo_documento."%'" : '';
            $where.= ($documentosId != '') ? "AND id < ".$documentosId : '';
            
            
            $sql = "SELECT *FROM tipo_documentos WHERE 1=1 ".$where;
            
            $query = $this->db->query($sql);
            $rows  = array(); 
            
            foreach($query->result_array() as $row){            
                    $rows[] = $row;
            }
            return $rows;
        }
    }