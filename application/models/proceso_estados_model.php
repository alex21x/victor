<?PHP

class Proceso_estados_model extends CI_Model
{
	
	 public function __construct()
	{
		parent::__construct();
	}

 public function select($proceso_estadoId = ''){

    if ($proceso_estadoId == '') {
       $rsProcesoEstados = $this->db->from("proceso_estados")
                                    ->get()
                                    ->result();
         return $rsProcesoEstados;
    } else{
       $rsProcesoEstado = $this->db->from("proceso_estados")
                                   ->where("id",$proceso_estadoId)
                                   ->get()
                                   ->row();
                                   return $rsProcesoEstado;
    }
}}