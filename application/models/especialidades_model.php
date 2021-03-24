<?PHP if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Especialidades_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function select($idEspecialidad = ''){
      if ($idEspecialidad == '') {
          $rsEspecialidades = $this->db->from("especialidades")
                                       ->get()
                                       ->result();
           return $rsEspecialidades;

      }else{
          $rsEspecialidad = $this->db->from("especialidades")
                                     ->where("esp_id",$idEspecialidad)
                                     ->get()
                                     ->row();

                         return $rsEspecialidad;
        }
    }

    public function guardarEspecialidad()
    {      
        if($_POST['id']!='')
      {
        $dataUpdate = [
                  'esp_descripcion'  => strtoupper($_POST['descripcion']),
                  'esp_fecha_insert'  => date('Y-m-d'),
                  'esp_estado' => ST_ACTIVO                
                ];
          $this->db->where('esp_id', $_POST['id']);
          $this->db->update('especialidades', $dataUpdate);
      }else
      {
        $dataInsert = [
                  'esp_descripcion'  => strtoupper($_POST['descripcion']),
                  'esp_fecha_insert'  => date('Y-m-d'),
                  'esp_estado' => ST_ACTIVO                  
                ];
        $this->db->insert('especialidades', $dataInsert);
      }
      return true;
    }       



    public function getMainList(){

      $select = $this->db->from('especialidades')
                         ->where("esp_estado",ST_ACTIVO);

      if($_POST['search'] != ''){
        $select->like("esp_descripcion",$_POST['search']);
      }

      $selectCount = clone $select;
      $rsCount = $selectCount->get()->result();

      $rows = count($rsCount);

      $rsEspecialidades = $select->limit($_POST['pageSize'],$_POST['skip'])
                                 ->order_by("esp_id","desc")
                                 ->get()
                                 ->result();
      $i=1;
      foreach ($rsEspecialidades as $especialidad) {
          $especialidad->id = "<a class='show_galeria' title ='ver' href= '#' data-id='{$especialidad->esp_id}'>{$especialidad->esp_id}</a>";
          $especialidad->esp_editar  = "<a class='btn btn-default btn-xs  btn_modificar_especialidad' data-id='{$especialidad->esp_id}' data-toggle='modal' data-target='#myModal'>Modificar</a>";

          $especialidad->esp_eliminar= "<a class='btn btn-default btn-xs btn_eliminar_especialidad' data-id='{$especialidad->esp_id}' data-msg='Desea Eliminar Especialidad: {$especialidad->esp_descripcion} ?'>Eliminar</a>";
        $i++;
      }

      $datos = [
          'data' => $rsEspecialidades,
          'rows' => $rows
      ];

      return $datos;
    }


    public function eliminar($idEspecialidad){

      $especialidadUpdate = [
          "esp_estado" => ST_ELIMINADO
        ];

      $this->db->where("esp_id",$idEspecialidad);
      $this->db->update("especialidades", $especialidadUpdate);
      return true;
    } 
}