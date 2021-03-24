<?PHP if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modulos_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function select($idModulo='',$modEsPadre = '') {
        if($idModulo == '') {
            if ($modEsPadre != '') 
            $this->db->where('mod_es_padre',$modEsPadre);

            $rsModulos = $this->db->from("modulos")
                                     ->where("mod_estado", ST_ACTIVO)
                                     ->get()
                                     ->result();
            return $rsModulos;
        } else {
            $rsModulo = $this->db->from("modulos")
                            ->where("mod_id", $idModulo)
                            ->get()
                            ->row();
            return $rsModulo;
        }           
    }

    public function guardar() {    

        $externo = ($_POST['externo'] == 'on') ? 1 : 0;
        if($_POST['id']!='') {
            $dataUpdate = [
                            'mod_descripcion'=> $_POST['nombre'],
                            'mod_icon'=> $_POST['icono'],
                            'mod_enlace'     => $_POST['enlace'],
                            'mod_referencia' => $_POST['referencia'],
                            'mod_orden'    => $_POST['orden'],
                            'mod_es_padre' => $_POST['tipoModulo'],
                            'mod_sunat'    => $externo
                          ];
            $this->db->where('mod_id', $_POST['id']);
            $this->db->update('modulos', $dataUpdate);
        } else {
            $dataInsert = [
                            'mod_descripcion'=> $_POST['nombre'],
                            'mod_icon'=> $_POST['icono'],
                            'mod_enlace'     => $_POST['enlace'],
                            'mod_referencia' => $_POST['referencia'],
                            'mod_orden'    => $_POST['orden'],
                            'mod_es_padre' => $_POST['tipoModulo'],
                            'mod_sunat'    => $externo,
                            'mod_estado'   => ST_ACTIVO
                          ];
            $this->db->insert('modulos', $dataInsert);
        }
        return true;
    } 

    public function eliminar($idModulo) {

        $moduloUpdate = [
                            "mod_estado" => ST_ELIMINADO
                           ];
        $this->db->where("mod_id", $idModulo);
        $this->db->update("modulos", $moduloUpdate);                  
        return true; 
    }   

    public function getMainList()
    {
        $select = $this->db->from("modulos")
                           ->where("mod_estado", ST_ACTIVO);
        if($_POST['search'] != '')
        {
            $select->like("mod_descripcion", $_POST['search']);
        }                   

        $selectCount = clone $select;
        $rsCount = $selectCount->get()
                               ->result();
        $rows = count($rsCount);
        
        $rsModulos = $select->limit($_POST['pageSize'],$_POST['skip'])
                              ->order_by("mod_es_padre")
                              ->order_by("mod_referencia")                              
                              ->order_by("mod_orden")
                              ->get()
                              ->result();                                          

        $i = 1;
        foreach($rsModulos as $rsModulo)
        {
            $rsModulo->mod_correlativo = $i;            
            if($rsModulo->mod_es_padre == 0){
            $rsPadre = $this->db->from('modulos')
                                ->where('mod_id', $rsModulo->mod_referencia)
                                ->get()
                                ->row();
                $rsModulo->mod_padre = strtoupper($rsPadre->mod_descripcion);} else {
                $rsModulo->mod_padre = '--';    
                                }
            
            if($rsModulo->mod_id!=1){
                $rsModulo->mod_editar = "<a class='btn btn-default btn-xs btn_modificar_modulo' data-id='{$rsModulo->mod_id}' data-toggle='modal' data-target='#myModal'>Modificar</a>";
                $rsModulo->mod_eliminar = "<a class='btn btn-danger btn-xs btn_eliminar_modulo' data-id='{$rsModulo->mod_id}' data-msg='Desea eliminar la Módulo: {$rsModulo->mod_descripcion}?'>Eliminar</a>";
            }else{
                $rsModulo->mod_editar = "";
                $rsModulo->mod_eliminar = "";
            }
           $i++; 
        }
        $datos = [
                    'data' => $rsModulos,
                    'rows' => $rows
                 ];
        return $datos;      
    }    
}   