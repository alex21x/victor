<?PHP if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Turnos_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function select($idTurno = ''){
      if ($idTurno == '') {
          $rsTurnos = $this->db->from("turnos")
                               ->get()
                               ->result();
           return $rsTurnos;

      }else{
          $rsTurno = $this->db->from("turnos")
                              ->where("id",$idTurno)
                              ->get()
                              ->row();

                         return $rsTurno;
        }
    }

    public function guardarTurno()
    {      
        if($_POST['id']!='')
      {
        $dataUpdate = [
                  'turno'  => strtoupper($_POST['turno']),                  
                  'estado' => ST_ACTIVO                
                ];
          $this->db->where('id', $_POST['id']);
          $this->db->update('turnos', $dataUpdate);
      }else
      {
        $dataInsert = [
                  'turno'  => strtoupper($_POST['turno']),
                  'estado' => ST_ACTIVO                  
                ];
        $this->db->insert('turnos', $dataInsert);
      }
      return true;
    }       

    public function getMainList(){

      $select = $this->db->from('turnos')
                         ->where("estado",ST_ACTIVO);

      if($_POST['search'] != ''){
        $select->like("turno",$_POST['search']);
      }

      $selectCount = clone $select;
      $rsCount = $selectCount->get()->result();

      $rows = count($rsCount);

      $rsTurnos = $select->limit($_POST['pageSize'],$_POST['skip'])
                                 ->order_by("id","desc")
                                 ->get()
                                 ->result();

      foreach ($rsTurnos as $rsTurno) {

          $rsTurno->tur_id = "<a class='show_galeria' title ='ver' href= '#' data-id='{$rsTurno->id}'>{$rsTurno->id}</a>";
          $rsTurno->tur_editar  = "<a class='btn btn-default btn-xs btn_modificar_turno' data-id='{$rsTurno->id}' data-toggle='modal' data-target='#myModal'>Modificar</a>";

          $rsTurno->tur_eliminar= "<a class='btn btn-default btn-xs btn_eliminar_turno' data-id='{$rsTurno->id}' data-msg='Desea Eliminar Turno: {$rsTurno->turno} ?'>Eliminar</a>";        
      }

      $datos = [
          'data' => $rsTurnos,
          'rows' => $rows
      ];

      return $datos;
    }

    public function eliminar($idTurno){

      $turnoUpdate = [
          "estado" => ST_ELIMINADO
        ];

      $this->db->where("id",$idTurno);
      $this->db->update("turnos", $turnoUpdate);
      return true;
    } 
}