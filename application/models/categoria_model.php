<?PHP if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Categoria_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function select($idCategoria='') {
        if($idCategoria == '') {

            $rsCategorias = $this->db->from("categoria")
                                     ->where("cat_estado", ST_ACTIVO)
                                     ->get()
                                     ->result();
            return $rsCategorias;
        } else {
            $rsCategoria = $this->db->from("categoria")
                            ->where("cat_id", $idCategoria)
                            ->get()
                            ->row();
            return $rsCategoria;          
        }           
    }

    public function guardar() {        
        if($_POST['id']!='') {
            $dataUpdate = [
                            'cat_nombre'    => strtoupper($_POST['nombre'])
                          ];
            $this->db->where('cat_id', $_POST['id']);
            $this->db->update('categoria', $dataUpdate);                          
        } else {
            $dataInsert = [
                            'cat_nombre'    => strtoupper($_POST['nombre']),
                            'cat_estado'    => ST_ACTIVO
                          ];
            $this->db->insert('categoria', $dataInsert);              
        }
        return true;
    } 

    public function eliminar($idCategoria) {

        $categoriaUpdate = [
                              "cat_estado" => ST_ELIMINADO
                           ];
        $this->db->where("cat_id", $idCategoria);
        $this->db->update("categoria", $categoriaUpdate);                   
        return true; 
    }   

    public function getMainList()
    {
        $select = $this->db->from("categoria")
                           ->where("cat_estado", ST_ACTIVO);
        if($_POST['search'] != '')
        {
            $select->like("cat_nombre", $_POST['search']);
        }                   

        $selectCount = clone $select;
        $rsCount = $selectCount->get()
                               ->result();
        $rows = count($rsCount);
        
        $rsCategoria = $select->limit($_POST['pageSize'],$_POST['skip'])
                              ->order_by("cat_id", "desc")
                              ->get()
                              ->result();                                          

        foreach($rsCategoria as $categoria)
        {
            if($categoria->cat_id!=1){
                $categoria->cat_editar = "<a class='btn btn-default btn-xs btn_modificar_categoria' data-id='{$categoria->cat_id}' data-toggle='modal' data-target='#myModal'>Modificar</a>";
                $categoria->cat_eliminar = "<a class='btn btn-danger btn-xs btn_eliminar_categoria' data-id='{$categoria->cat_id}' data-msg='Desea eliminar la Categoria: {$categoria->cat_nombre}?'>Eliminar</a>";
            }else{
                $categoria->cat_editar = "";
                $categoria->cat_eliminar = "";
            }
            
        }

        $datos = [
                    'data' => $rsCategoria,
                    'rows' => $rows
                 ];
        return $datos;      
    }


    //SELECT AUTOCOMPLETE 06-10-2020
    public function selectAutocomplete($buscar){        
        $where = '(cat.cat_estado='.ST_ACTIVO.' AND cat.cat_nombre LIKE "%'.$buscar.'%")';
        $result = $this->db->from('categoria cat')                                        
                            ->where($where)
                            ->get()
                            ->result();
                        
        $data = array();    
        foreach ($result as $cat){
            $data[] = array(
              "value" => $cat->cat_nombre,             
             "id" => $cat->cat_id 
            );
        }        
        return $data;
    }
}   
