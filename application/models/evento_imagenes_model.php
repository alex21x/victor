<?PHP

class Evento_imagenes_model extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
	}

 public function select($eventoImagenId = '',$eventoId = ''){

    if ($eventoImagenId == '') {
      if($eventoId != '')
        $this->db->where('evento_id',$eventoId);

       $rsEventoImagenes = $this->db->from("evento_imagenes")
                                    ->where('estado',ST_ACTIVO)
                                    ->get()
                                    ->result();

         return $rsEventoImagenes;

    } else{
      $rsEventoImagen = $this->db->from("evento_imagenes")
                                 ->where("id",$eventoImagenId)
                                 ->get()
                                 ->row();
        return $rsEventoImagen; 
    }
  }

  public function eliminar($idEventoImagen){
    $eventoImagen = [
          "estado" => ST_ELIMINADO
    ];

    $this->db->where("id",$idEventoImagen);
    $this->db->update("evento_imagenes", $eventoImagen);
    return true;
  }
}
