<?PHP if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profesionales_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function select($idProfesional = '',$idEspecialidad = ''){
      if ($idProfesional == '') {
        if($idEspecialidad != '')
          $this->db->where('prof.prof_especialidad_id',$idEspecialidad);

          $rsProfesionales = $this->db->from("profesionales prof")
                                      ->join("especialidades esp","esp.esp_id =  prof.prof_especialidad_id")
                                      ->get()
                                      ->result();
          //var_dump($rsProfesionales);exit;
           return $rsProfesionales;

      }else{
          $rsProfesional = $this->db->from("profesionales prof")
                                    ->join("especialidades esp","esp.esp_id =  prof.prof_especialidad_id")
                                    ->where("prof.prof_id",$idProfesional)
                                    ->get()
                                    ->row();

                         return $rsProfesional;
        }
    }

    public function guardarProfesion()
    {      

      //GUARDAR FOTO
       $carpeta = 'images/profesional/foto/';
       opendir($carpeta);
       $destino = $carpeta.$_FILES['foto']['name'];       
       copy($_FILES['foto']['tmp_name'], $destino);


       //GUARDAR FIRMA
       $carpeta = 'images/profesional/firma/';
       opendir($carpeta);
       $destino = $carpeta.$_FILES['firma']['name'];       
       copy($_FILES['firma']['tmp_name'], $destino);



        if($_POST['id']!='')
      {
        $dataUpdate = [
                  'prof_codigo'  => $_POST['codigo'],
                  'prof_nombre'  => strtoupper($_POST['nombre']),
                  'prof_direccion'  => strtoupper($_POST['direccion']),
                  'prof_telefono'   => $_POST['telefono'],  
                  'prof_foto' => $_FILES['foto']['name'],
                  'prof_firma' => $_FILES['firma']['name'],
                  'prof_especialidad_id'  => $_POST['especialidad']                  
                ];
          $this->db->where('prof_id', $_POST['id']);
          $this->db->update('profesionales', $dataUpdate);
      }else
      {
        $dataInsert = [
                  'prof_codigo'  => $_POST['codigo'],
                  'prof_nombre'  => strtoupper($_POST['nombre']),
                  'prof_direccion'  => strtoupper($_POST['direccion']),
                  'prof_telefono'   => $_POST['telefono'],     
                  'prof_foto' => $_FILES['foto']['name'],
                  'prof_firma' => $_FILES['firma']['name'],
                  'prof_especialidad_id'  => $_POST['especialidad'],             
                  'prof_fecha_insert'  => date('Y-m-d'),
                  'prof_estado' => ST_ACTIVO
                ];
        $this->db->insert('profesionales', $dataInsert);
      }
      return true;
    }       



    public function getMainList(){

      $select = $this->db->from('profesionales prof')
                         ->join('especialidades esp','esp.esp_id = prof.prof_especialidad_id')
                         ->where("prof_estado",ST_ACTIVO);

      if($_POST['search'] != ''){
        $select->like("prof_descripcion",$_POST['search']);
      }

      $selectCount = clone $select;
      $rsCount = $selectCount->get()->result();

      $rows = count($rsCount);

      $rsProfesionales = $select->limit($_POST['pageSize'],$_POST['skip'])
                                 ->order_by("prof_id","desc")
                                 ->get()
                                 ->result();
      $i=1;
      foreach ($rsProfesionales as $profesional) {
          $profesional->id = "<a class='show_galeria' title ='ver' href= '#' data-id='{$profesional->prof_id}'>{$profesional->prof_id}</a>";
          $profesional->prof_editar  = "<a class='btn btn-default btn-xs  btn_modificar_profesional' data-id='{$profesional->prof_id}' data-toggle='modal' data-target='#myModal'>Modificar</a>";

          $profesional->prof_eliminar= "<a class='btn btn-default btn-xs btn_eliminar_profesional' data-id='{$profesional->prof_id}' data-msg='Desea Eliminar Profesional: {$profesional->prof_nombre} ?'>Eliminar</a>";
        $i++;
      }

      $datos = [
          'data' => $rsProfesionales,
          'rows' => $rows
      ];
      return $datos;
    }


    public function eliminar($idProfesional){
      $profesionalUpdate = [
          "prof_estado" => ST_ELIMINADO
        ];

      $this->db->where("prof_id",$idProfesional);
      $this->db->update("profesionales", $profesionalUpdate);
      return true;
    } 
}