<?PHP
    if(!defined('BASEPATH')) exit ('No direct script access allowed');
        
    class Ser_nums_model extends CI_Model{
        
        public function __construct() {
            parent::__construct();            
        }

        public function select($id = '' , $tipo_documento_id = '', $empresa_id = '') {
            if($id != ''){
                $sql = "SELECT *FROM ser_nums WHERE id  =".$id;
                $query = $this->db->query($sql);
                return $query->row_array();
            }
            
            $where = '';
            $where.= " WHERE eliminado = 0";
            if( $tipo_documento_id != ''){$where.= " AND tipo_documento_id = ".$tipo_documento_id;}
            if( $empresa_id != ''){$where.= " AND empresa_id = ".$empresa_id;}
            $sql = "SELECT *FROM ser_nums".$where;
            $query = $this->db->query($sql);
            return $query->result_array();
        }
        
        public function seleccion($modo, $select = array(), $condicion = array(), $order = '') {

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
            $sql = "SELECT " . $campos . " FROM ser_nums WHERE 1 = 1 " . $where . " " . $order;
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
        
        //contamos si series con un determinado tipo_documento_id
        public function count($tipo_documento_id, $empresa_id){
            $sql = "SELECT COUNT(id) filas FROM ser_nums WHERE tipo_documento_id = " . $tipo_documento_id . " AND empresa_id = " . $empresa_id;
            $query = $this->db->query($sql);
            $row = $query->row();
            $filas = '';
            if (isset($row)){
                $filas = $row->filas;
            }            
            return $filas;
        }
                                        
        public function insertar($data, $mensaje = '') {
            if($mensaje == ''){
                $mensaje = 'Registro ingresado correctament';
            }
            $this->db->insert('ser_nums',$data);
            if($mensaje != '')
                $this->session->set_flashdata('mensaje',$mensaje);
        }
        
        public function actualizar($empresa_id, $tipo_documento_id, $serie, $mensaje = ''){
            $sql = "UPDATE ser_nums SET serie = '" . $serie . "' WHERE empresa_id = " . $empresa_id . " AND tipo_documento_id = " . $tipo_documento_id;
            $this->db->query($sql);
            
            if($mensaje == '')
                $mensaje = 'Registro modificado con exito';
            $this->session->set_flashdata('mensaje',$mensaje);
        }
        
        public function actualizar2($id, $serie, $mensaje = ''){
            $sql = "UPDATE ser_nums SET serie = '" . $serie . "' WHERE id = " . $id;
            $this->db->query($sql);
            
            if($mensaje == '')
                $mensaje = 'Registro modificado con exito';
            $this->session->set_flashdata('mensaje',$mensaje);
        }                
        
        public function modificar($id,$data,$mensaje='') {
            $this->db->where('id',$id);
            $this->db->update('ser_nums',$data);
            $this->session->set_flashdata('mensaje',$mensaje);
        }
        
        public function eliminar() {
            $sql  = 'UPDATE ser_nums SET eliminado = 1 WHERE id = '.$this->uri->segment(3);
            mysql_query($sql);
            $this->session->set_flashdata('mensaje','Registro eliminado con éxito');
        }
    }
