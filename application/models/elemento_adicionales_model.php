<?PHP if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Elemento_adicionales_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function select($codigo = '', $descripcion = '',$activo = ''){
        $where = '';
        if ($codigo != '') {$where .= " AND codigo = " . $codigo;}
        if ($descripcion != '') {$where .= " AND descripcion = " . $descripcion;}
        if ($activo != '') {$where .= " AND activo = '" . $activo."'";}

        $sql = "SELECT *FROM elemento_adicionales                 
                WHERE 1 = 1 ".$where.' ORDER BY descripcion DESC';
        
        $query = $this->db->query($sql);
        $rows = array();
        foreach($query->result_array() as $row){
            $rows[] = $row;
        }
        return $rows;
    }
}
