<?PHP

class Tipo_eventos_model extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
	}

 public function select($tipoEventoId = ''){

    if ($tipoEventoId == '') {
       $rsTipoEventos = $this->db->from("tipo_eventos")
                                 ->get()
                                 ->result();

         return $rsTipoEventos;

    } else{

      $rsTipoEvento = $this->db->from("tipo_eventos")
                         ->where("id",$tipoEventoId)
                         ->get()
                         ->row();
        return $rsTipoEvento;
 
    }
  }
}
