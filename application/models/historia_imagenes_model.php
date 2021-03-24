<?PHP
class Historia_imagenes_model extends CI_Model
{	
	public function __construct()
	{
		parent::__construct();		
	}

	public function select($idHistoriaImagen = '', $idHistoria = ''){

		
		if($idHistoriaImagen == ''){
			if($idHistoria != ''){
				$this->db->where('hii_his_id',$idHistoria);		
			}
			
			$rsHistoriaImagenes = $this->db->from('historia_imagenes')
										   ->where('hii_estado',ST_ACTIVO)
							 			   ->get()
							 			   ->result();
							 			   
					 return $rsHistoriaImagenes;
		} else{
			$rsHistoriaImagen =  $this->db->from('historia_imagenes')
										  ->where('id',$idHistoriaImagen)										  
									      ->get()
									      ->row();
			return $rsHistoriaImagen;
		}
	}



	public function eliminar($idHistoriaImagen){
		$historiaImagen = [
					"hii_estado" => ST_ELIMINADO
		];

		$this->db->where("hii_id",$idHistoriaImagen);
		$this->db->update("historia_imagenes", $historiaImagen);
		return true;
	}
}

