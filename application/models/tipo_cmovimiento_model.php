<?PHP


class Tipo_cmovimiento_model extends CI_Model
{
	
	public function __construct()
	{
		parent::__construct();
	}


public function select(){

	$rs = $this->db->from("tipo_cmovimientos")
					->get()
					->result();
	
		return $rs;					
	}
}