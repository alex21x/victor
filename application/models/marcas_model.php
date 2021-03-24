<?PHP if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Marcas_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function select($idMarca='') {
        if($idMarca == '') {

            $rsMarcas = $this->db->from("marcas")
                                  ->where("mar_estado", ST_ACTIVO)
                                  ->get()
                                  ->result();
            return $rsMarcas;
        } else {
            $rsMarca = $this->db->from("marcas")
                            ->where("mar_id", $idMarca)
                            ->get()
                            ->row();
            return $rsMarca;          
        }           
    }

    public function guardar() {        
        if($_POST['id']!='') {
            $dataUpdate = [
                            'mar_nombre'    => $_POST['nombre'],                          
                          ];
            $this->db->where('mar_id', $_POST['id']);
            $this->db->update('marcas', $dataUpdate);                          
        } else {
            $dataInsert = [
                            'mar_nombre'    => $_POST['nombre'],                            
                            'mar_estado'    => ST_ACTIVO
                          ];
            $this->db->insert('marcas', $dataInsert);              
        }
        return true;
    } 

    public function eliminar($idMarca) {
      //vericamos que esa seccion no tenga asiganda a un producto
      //echo $idLinea;exit;
        $marcaUpdate = [
                          "mar_estado" => ST_ELIMINADO
                           ];
        $this->db->where("mar_id", $idMarca) ;
        $this->db->update("marcas", $marcaUpdate);
        return true; 
    }   

    public function getMainList()
    {
        $select = $this->db->from("marcas")
                           ->where("mar_estado",ST_ACTIVO);
        if($_POST['search'] != '')
        {
            $select->like("mar_nombre", $_POST['search']);
        }                   

        $selectCount = clone $select;
        $rsCount = $selectCount->get()
                               ->result();
        $rows = count($rsCount);
        
        $rsMarcas = $select->limit($_POST['pageSize'],$_POST['skip'])
                           ->order_by("mar_id", "desc")
                           ->get()
                           ->result();                                          

          foreach ($rsMarcas as $marca) {
            $marca->mar_editar  = "<a class='btn btn-default btn-xs  btn_modificar_marca' data-id='{$marca->mar_id}'
              data-toggle='modal' data-target='#myModal'>Modificar</a>";

            $marca->mar_eliminar= "<a class='btn btn-default btn-xs btn_eliminar_marca' data-id='{$marca->mar_id}' data_msg='Desea Eliminar Marca: {$marca->mar_nombre} ?'>Eliminar</a>";
          } 

        $datos = [
                    'data' => $rsMarcas,
                    'rows' => $rows
                 ];
        return $datos;      
    }

    //SELECT AUTOCOMPLETE 06-10-2020
    public function selectAutocomplete($buscar){        
        $where = '(mar.mar_estado='.ST_ACTIVO.' AND mar.mar_nombre LIKE "%'.$buscar.'%")';
        $result = $this->db->from('marcas mar')
                            ->where($where)
                            ->get()
                            ->result();
                        
        $data = array();    
        foreach ($result as $mar){
            $data[] = array(
              "value" => $mar->mar_nombre,             
              "id" => $mar->mar_id 
            );
        }        
        return $data;
    }
}   
