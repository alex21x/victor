<?PHP

class Sexos_model extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
	}

 public function select($sexoId = ''){

    if ($sexoId == '') {
       $rsSexos = $this->db->from("sexos")
                           ->get()
                           ->result();

         return $rsSexos;

    } else{

      $rsSexo = $this->db->from("sexos")
                         ->where("id",$sexoId)
                         ->get()
                         ->row();
        return $rsSexo;
 
}}
}
