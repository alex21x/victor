<?PHP
    if(!defined('BASEPATH')) exit ('No direct script access allowed');

    class Tipo_cambio_model extends CI_Model{

        public function __construct() {
            parent::__construct();            
        }

        public function select($id = FALSE,$moneda_id = '', $fecha = '') {
            if($id != FALSE){
                $sql = "SELECT *FROM tipo_cambio WHERE id  =".$id;
                $query = $this->db->query($sql);
                return $query->row_array();
            }
            
            $where = "";
            
            if($moneda_id != ''){ $where.= " AND moneda_id = ".$moneda_id;}
            if($fecha != ''){ $where.= " AND fecha = '".$fecha."'";}
            
            $where.= " AND eliminado = 0 ORDER BY fecha DESC";
            
            $sql = "SELECT *FROM tipo_cambio WHERE 1=1".$where;
            //echo $sql;
            $query = $this->db->query($sql);
            return $query->result_array();
        }

        public function selectJson($moneda_id = ''){
            $where = " WHERE eliminado = 0";
            $where.= " AND moneda_id = ".$moneda_id;
            $where.= " ORDER BY fecha DESC LIMIT 1";

            $sql = "SELECT *FROM tipo_cambio" . $where;
            $query = $this->db->query($sql);
            return $query->row_array();
        }
        
        public function selectFechaJson($moneda_id, $fecha){
            $where = " WHERE eliminado = 0";
            $where.= " AND moneda_id = " . $moneda_id . " AND fecha <= '" . $fecha ."' ";
            $where.= " ORDER BY fecha DESC LIMIT 1";

            $sql = "SELECT *FROM tipo_cambio" . $where;
            //echo $sql;exit;
            $query = $this->db->query($sql);
            return $query->row_array();
        }

        public function insertar($data, $mensaje = '') {                        
            $this->db->insert('tipo_cambio',$data);                        
            if($mensaje != '')
                $this->session->set_flashdata('mensaje',$mensaje);
        }

        public function modificar($id,$data,$mensaje='') {
            $this->db->where('id',$id);
            $this->db->update('tipo_cambio',$data);            
            $this->session->set_flashdata('mensaje',$mensaje);
        }

        public function eliminar() {
            $sql  = 'UPDATE tipo_cambio SET eliminado = 1 WHERE id = '.$this->uri->segment(3);
            mysql_query($sql);
            $this->session->set_flashdata('mensaje','Registro eliminado con éxito');
        }
    }