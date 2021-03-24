<?php
class Libros_electronicos_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function listar_le($fecha_insert_1,$fecha_insert_2,$select_mes,$select_anio){

      $this->db->select('le.*,e.nombre,e.apellido_paterno');

      if($fecha_insert_1!=''){
        $this->db->where('DATE(le.fecha_insert)>=',$fecha_insert_1);
        if($fecha_insert_2!=''){
          $this->db->where('DATE(le.fecha_insert)<=',$fecha_insert_2);
        }
      }
      if($select_mes!=0){
        $this->db->where('le.mes',$select_mes);
      }
      if($select_anio!=0){
        $this->db->where('le.anio',$select_anio);
      }

      //$libros = array('140100','140200');
      //$this->db->where_in('le.libro_id',$libros);
      $this->db->from('libros_electronicos le');
      $this->db->join('empleados e','le.empleado_id=e.id');
      $this->db->order_by('id','DESC');
      $result = $this->db->get();
      $json = $result->result();
      return $json;
    }

    public function listar_lecompras($fecha_insert_1,$fecha_insert_2,$select_mes,$select_anio){
      $this->db->select('le.*,e.nombre,e.apellido_paterno');

      if($fecha_insert_1!=''){
        $this->db->where('DATE(le.fecha_insert)>=',$fecha_insert_1);
        if($fecha_insert_2!=''){
          $this->db->where('DATE(le.fecha_insert)<=',$fecha_insert_2);
        }
      }
      if($select_mes!=0){
        $this->db->where('le.mes',$select_mes);
      }
      if($select_anio!=0){
        $this->db->where('le.anio',$select_anio);
      }

      $libros = array('080100','080200','080300');
      $this->db->where_in('le.libro_id',$libros);
      $this->db->from('libros_electronicos le');
      $this->db->join('empleados e','le.empleado_id=e.id');
      $result = $this->db->get();
      $json = $result->result();
      return $json;
    }

    public function listar_leventas($fecha_insert_1,$fecha_insert_2,$select_mes,$select_anio){

      $this->db->select('le.*,e.nombre,e.apellido_paterno');

      if($fecha_insert_1!=''){
        $this->db->where('DATE(le.fecha_insert)>=',$fecha_insert_1);
        if($fecha_insert_2!=''){
          $this->db->where('DATE(le.fecha_insert)<=',$fecha_insert_2);
        }
      }
      if($select_mes!=0){
        $this->db->where('le.mes',$select_mes);
      }
      if($select_anio!=0){
        $this->db->where('le.anio',$select_anio);
      }

      $libros = array('140100','140200');
      $this->db->where_in('le.libro_id',$libros);
      $this->db->from('libros_electronicos le');
      $this->db->join('empleados e','le.empleado_id=e.id');
      $result = $this->db->get();
      $json = $result->result();
      return $json;
    }

    public function insert_le($data){
       $this->db->insert('libros_electronicos',$data);
    }

    public function select_le($data){
      $this->db->where($data);
      $result = $this->db->get('libros_electronicos');
      $json = $result->result();
      return $json;
    }

  
}
