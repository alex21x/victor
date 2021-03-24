<?PHP if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modos_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function select($idModo='') {
        if($idModo == '') {

            $rsModos = $this->db->from("modos")
                                  ->where("estado", ST_ACTIVO)
                                  ->get()
                                  ->result();
            return $rsModos;
        } else {
            $rsModo = $this->db->from("modos")
                            ->where("estado", $idModo)
                            ->get()
                            ->row();
            return $rsModo;          
        }           
    }
}