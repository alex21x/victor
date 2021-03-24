<?PHP

class Ubigeo_inei_model extends CI_Model
{	
 public function __construct()
 {
		parent::__construct();
 }

 public function select($ubigeo_inei_id = ''){

    if ($ubigeo_inei_id == '') {
      $result = $this->db->from("ubigeo_inei")
                         ->get()
                         ->result();
         return $result;
    } else {
      $result = $this->db->from("ubigeo_inei")
                         ->where("id",$ubigeo_inei_id)
                         ->get()
                         ->row();
         return $result;
    }
}}
