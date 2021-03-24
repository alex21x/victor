<?PHP if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comprobantes_ventas_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function select() {        
            $rsComprobantesVenta = $this->db->from("comprobantes_ventas")                                        
                                            ->get()
                                            ->row();
            return $rsComprobantesVenta;    
    }

    public function guardar() {
            
            $password = ($_POST['password'] == 'on') ? 1 : 0;
            $dataUpdate = [
                            'passwordDelete'    => $password,
                            'textPasswordDelete' => $_POST['textPasswordDelete']
                          ];            
            $this->db->update('comprobantes_ventas', $dataUpdate);
        return true;
    }

}     