<?PHP if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Activos_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function select($idActivo = ''){
    if ($idActivo == '') {
        $rsActivos = $this->db->from("activos")
                              ->get()
                              ->result();
         return $rsActivos;

    }else{
        $rActivo = $this->db->from("activos")
                            ->where("id",$idActivo)
                            ->get()
                            ->row();

                       return $rActivo;
      }
    }
}