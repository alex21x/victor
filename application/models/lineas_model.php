<?PHP if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lineas_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function select($idLinea='') {
        if($idLinea == '') {

            $rsLineas = $this->db->from("lineas")
                                  ->where("lin_estado", ST_ACTIVO)
                                  ->get()
                                  ->result();
            return $rsLineas;
        } else {
            $rsLinea = $this->db->from("lineas")
                            ->where("lin_id", $idLinea)

                            ->get()
                            ->row();
            return $rsLinea;          
        }           
    }

    public function guardar() {        
        if($_POST['id']!='') {
            $dataUpdate = [
                            'lin_nombre'    => $_POST['nombre'],                          
                          ];
            $this->db->where('lin_id', $_POST['id']);
            $this->db->update('lineas', $dataUpdate);                          
        } else {
            $dataInsert = [
                            'lin_nombre'    => $_POST['nombre'],                            
                            'lin_estado'    => ST_ACTIVO
                          ];
            $this->db->insert('lineas', $dataInsert);              
        }
        return true;
    } 

    public function eliminar($idLinea) {

      //echo $idLinea;exit;
        $lineaUpdate = [
                          "lin_estado" => ST_ELIMINADO
                           ];
        $this->db->where("lin_id", $idLinea);
        $this->db->update("lineas", $lineaUpdate);                   
        return true; 
    }   

    public function getMainList()
    {
        $select = $this->db->from("lineas")
                           ->where("lin_estado",ST_ACTIVO);
        if($_POST['search'] != '')
        {
            $select->like("lin_nombre", $_POST['search']);
        }                   

        $selectCount = clone $select;
        $rsCount = $selectCount->get()
                               ->result();
        $rows = count($rsCount);
        
        $rsLineas = $select->limit($_POST['pageSize'],$_POST['skip'])
                           ->order_by("lin_id", "desc")
                           ->get()
                           ->result();                                          

          foreach ($rsLineas as $linea) {
            $linea->lin_editar  = "<a class='btn btn-default btn-xs  btn_modificar_linea' data-id='{$linea->lin_id}'
              data-toggle='modal' data-target='#myModal'>Modificar</a>";

            $linea->lin_eliminar= "<a class='btn btn-default btn-xs btn_eliminar_linea' data-id='{$linea->lin_id}' data_msg='Desea Eliminar Linea: {$linea->lin_nombre} ?'>Eliminar</a>";
          } 

        $datos = [
                    'data' => $rsLineas,
                    'rows' => $rows
                 ];
        return $datos;      
    }


    //SELECT AUTOCOMPLETE 06-10-2020
    public function selectAutocomplete($buscar){        
        $where = '(lin.lin_estado='.ST_ACTIVO.' AND lin.lin_nombre LIKE "%'.$buscar.'%")';
        $result = $this->db->from('lineas lin')                                        
                            ->where($where)
                            ->get()
                            ->result();
                        
        $data = array();    
        foreach ($result as $lin){
            $data[] = array(
              "value" => $lin->lin_nombre,             
             "id" => $lin->lin_id 
            );
        }        
        return $data;
    }
}   
