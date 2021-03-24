<?php

class Tipo_pacientes_model extends CI_Model
{

	public function __construct() 
	{
        parent::__construct();
        $this->load->database();
    }
	  public function guardartipopacientes()
    {      
        if($_POST['id'] != '')
      {
        $dataUpdate = [
                  'tipo_pac_descrip'  => strtoupper($_POST['descripcion']),                                  
                  'tipo_pac_estado'   => ST_ACTIVO                  
                ];
          $this->db->where('tipo_pac_id', $_POST['id']);
          $this->db->update('tipo_pacientes', $dataUpdate);
      }else
      {
        $dataInsert = [
                  'tipo_pac_descrip'  => strtoupper($_POST['descripcion']),
                  'tipo_pac_estado' => ST_ACTIVO
                ];
        $this->db->insert('tipo_pacientes', $dataInsert);
      }
      return true;
    }  


    public function select($idtipopaciente = ''){
      if ($idtipopaciente == '') {
          $rstipopaciente = $this->db->from("tipo_pacientes")
                                     ->where("tipo_pac_estado",ST_ACTIVO)
                                     ->get()
                                     ->result();
           return $rstipopaciente;

      }else{
          $rstipopaciente= $this->db->from("tipo_pacientes")
                                    ->where("tipo_pac_id",$idtipopaciente)
                                     ->where("tipo_pac_estado",ST_ACTIVO)
                                     ->get()
                                     ->row();

                         return $rstipopaciente;
        }
    }

    //para llenar el kendo grid
    public function getMainList(){

      $select = $this->db->from('tipo_pacientes')
                         ->where("tipo_pac_estado",ST_ACTIVO);

      if($_POST['search'] != ''){
        $select->like("tipo_pac_descrip",$_POST['search']);
      }

      $selectCount = clone $select;
      $rsCount = $selectCount->get()->result();

      $rows = count($rsCount);

      $rsTipoPacientes = $select->limit($_POST['pageSize'],$_POST['skip'])
                                 ->order_by("tipo_pac_estado","desc")
                                 ->get()
                                 ->result();
      $i=1;
      foreach ($rsTipoPacientes as $tipoPaciente) {
          $tipoPaciente->id = "<a class='show_galeria' title ='ver' href= '#' data-id='{$tipoPaciente->tipo_pac_id}'>{$tipoPaciente->tipo_pac_id}</a>";
          $tipoPaciente->btn_editar  = "<a class='btn btn-default btn-xs  btn_modificar_tipoPaciente' data-id='{$tipoPaciente->tipo_pac_id}' data-toggle='modal' data-target='#myModal'>Modificar</a>";

          $tipoPaciente->btn_eliminar= "<a class='btn btn-default btn-xs btn_eliminar_tipoPaciente' data-id='{$tipoPaciente->tipo_pac_id}' data-msg='Desea Eliminar Tipo Paciente: {$tipoPaciente->tipo_pac_descrip} ?'>Eliminar</a>";
        $i++;
      }

      $datos = [
          'data' => $rsTipoPacientes,
          'rows' => $rows
      ];

      return $datos;
    }   

    public function eliminar($idtipopaciente) {      

        $tipoPacienteUpdate = [
                          "tipo_pac_estado" => ST_ELIMINADO
                           ];
        $this->db->where("tipo_pac_id", $idtipopaciente) ;
        $this->db->update("tipo_pacientes", $tipoPacienteUpdate);
        return true; 
    }
}
?>
