<?PHP if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Medida_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function select($idmedida='') {
        if($idmedida == '') {

            $rsMedidas = $this->db->from("medida")
                                  ->where("medida_activo", 1)
                                  ->get()
                                  ->result();
            return $rsMedidas;
        } else {
            $rsMedida = $this->db->from("medida")
                            ->where("medida_id", $idmedida)

                            ->get()
                            ->row();
            return $rsMedida;          
        }           
    }

    public function guardar() {        
        if($_POST['id']!='') {
            $dataUpdate = [
                            'medida_nombre'    => $_POST['nombre']
                          ];
            $this->db->where('medida_id', $_POST['id']);
            $this->db->update('medida', $dataUpdate);                          
        } else {
            $dataInsert = [
                            'medida_nombre'    => $_POST['nombre'],
                            'medida_estado'    => ST_ACTIVO
                          ];
            $this->db->insert('medida', $dataInsert);              
        }
        return true;
    } 

    public function eliminar($idmedida) {
      //vericamos que esa seccion no tenga asiganda a un producto
      $rsProductos = $this->db->from("productos")
                              ->where("prod_medida_id", $idmedida)
                              ->get()
                              ->result();
      if(count($rsProductos) > 0) {
        return false;
      } 
                    
        $this->db->delete('medida', ['medida_id'=>$idmedida]);
        return true; 
    }   

    public function getMainList()
    {
        $select = $this->db->from("medida");
        if($_POST['search'] != '')
        {
            $select->like("medida_nombre", $_POST['search']);
        }                   

        $selectCount = clone $select;
        $rsCount = $selectCount->get()
                               ->result();
        $rows = count($rsCount);
        
        $rsMedida = $select->limit($_POST['pageSize'],$_POST['skip'])
                              ->order_by("medida_id", "desc")
                              ->get()
                              ->result();                                          

        foreach($rsMedida as $medida) {
            if($medida->medida_activo == 1)
            {
              $medida->medida_estado = "<button class='btn btn-success btn-xs cambiarEstado' data-id='{$medida->medida_id}' data-accion='0'>Desactivar</button>";
            }
            if($medida->medida_activo == 0)
            {
              $medida->medida_estado = "<button class='btn  btn-xs cambiarEstado' data-id='{$medida->medida_id}' data-accion='1'>Activar</button>";
            }
        }

        $datos = [
                    'data' => $rsMedida,
                    'rows' => $rows
                 ];
        return $datos;      
    }

    public function cambiarEstado()
    {
      $medidaUpdate = [
                        'medida_activo' => $_POST['accion']
                      ];

      $this->db->where("medida_id", $_POST['medida']);
      $this->db->update("medida", $medidaUpdate);                
      return true;
    }    


}   
