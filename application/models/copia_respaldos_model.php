<?PHP if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Copia_respaldos_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('America/Lima');
    }

    public function select($idCopiaRespaldo='') {
        if($idCopiaRespaldo == '') {

            $rsCopiaRespaldos = $this->db->from("copia_respaldos")
		                                  ->where("estado", ST_ACTIVO)
		                                  ->get()
		                                  ->result();
            return $rsCopiaRespaldos;
        } else {
            $rsCopiaRespaldo = $this->db->from("copia_respaldos")
			                            ->where("idCopiaRespaldo", $idCopiaRespaldo)
			                            ->get()
			                            ->row();
            return $rsCopiaRespaldo;          
        }           
    }

    public function guardar() {  
        if($_POST['id']!='') {
            $dataUpdate = [
                            'copia_respaldo'    => $_POST['nombre'],                            
                          ];
            $this->db->where('medida_id', $_POST['id']);
            $this->db->update('medida', $dataUpdate);                          
        } else {
            $dataInsert = [
                            'copia_respaldo'    => $_POST['nombre'],
                            'medida_estado'    => ST_ACTIVO
                          ];
            $this->db->insert('copia_respaldos', $dataInsert);              
        }
        return true;
    } 

    public function eliminar($idCopiaRespaldo) {
      //vericamos que esa seccion no tenga asiganda a un producto
      $rsCopiaRespaldos = $this->db->from("copia_respaldos")
                              ->where("estado", $idCopiaRespaldo)
                              ->get()
                              ->result();
      if(count($rsCopiaRespaldos) > 0) {
        return false;
      } 
                    
		$this->db->where('id',$idCopiaRespaldo );           
        $this->db->update('copia_respaldos', ['estado'=> ST_ELIMINADO]);
        return true; 
    }   

    public function getMainList()
    {
        $select = $this->db->from("copia_respaldos");
        if($_POST['search'] != '')
        {
            $select->like("copia_respaldo", $_POST['search']);
        }                   

        $selectCount = clone $select;
        $rsCount = $selectCount->get()
                               ->result();
        $rows = count($rsCount);
        
        $rsCopiaRespaldos = $select->limit($_POST['pageSize'],$_POST['skip'])
                              ->order_by("id", "desc")
                              ->where("estado",ST_ACTIVO)
                              ->get()
                              ->result();                                          

        foreach($rsCopiaRespaldos as $rsCopiaRespaldo) {
        	$rsCopiaRespaldo->fecha_de_emision = date("d/m/Y h.i A", strtotime($rsCopiaRespaldo->fecha));
        	$rsCopiaRespaldo->copiaRespado_restaurar = "<a class='btn btn-xs btn_restaurar_copiaRespaldo' data-id = '{$rsCopiaRespaldo->id}' data-nombre = '{$rsCopiaRespaldo->copia_respaldo}' data-msg ='Está restaurar copia de seguridad?'><span class='glyphicon glyphicon-indent-right'></a>";
            $rsCopiaRespaldo->copiaRespado_editar = "<a class='btn btn-xs btn_editar_copiaRespaldo' data-id = '{$rsCopiaRespaldo->id}' data-toggle='modal' data-target ='#myModal'><span class='glyphicon glyphicon-edit'></a>";
            $rsCopiaRespaldo->copiaRespaldo_eliminar = "<a class='btn btn-xs btn_eliminar_copiaRespaldo' data-id = '{$rsCopiaRespaldo->id}' data-msg ='Está seguro de  eliminar Copia de Seguridad?'><span class='glyphicon glyphicon-remove-circle'></a>";            
        }

        $datos = [
                    'data' => $rsCopiaRespaldos,
                    'rows' => $rows
                 ];
        return $datos;      
    }    
}   


