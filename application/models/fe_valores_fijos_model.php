<?PHP

class Fe_valores_fijos_model extends CI_Model {
        
    public $UBLVersionID = "2.1";
    public $CustomizationID = "2.0";

    public function __construct() {
        parent::__construct();
        $this->load->database();        
    }


}