<?PHP if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Perfiles_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function select($idPerfil='') {
        if($idPerfil == '') {

            $rsPerfiles = $this->db->from("tipo_empleados")
                                     ->where("estado", ST_ACTIVO)
                                     ->get()
                                     ->result();
            return $rsPerfiles;
        } else {
            $rsPerfil = $this->db->from("tipo_empleados")
                            ->where("id", $idPerfil)
                            ->get()
                            ->row();
            return $rsPerfil;          
        }           
    }

    public function guardarPerfil() {        
        if($_POST['id']!='') {
            $dataUpdate = [
                            'tipo_empleado'    => $_POST['nombre']
                          ];
            $this->db->where('id', $_POST['id']);
            $this->db->update('tipo_empleados', $dataUpdate);
            //eliminamos los modulos existentes para volver a ingresar
            $this->db->where("tipo_empleado_id", $_POST['id']);
            $this->db->delete("tipo_empleado_modulo");
            $idPerfil = $_POST['id'];                          
        } else {
            $dataInsert = [
                            'tipo_empleado'    => strtoupper($_POST['nombre']),
                            'estado'    => ST_ACTIVO
                          ];
            $this->db->insert('tipo_empleados', $dataInsert);  
            $idPerfil = $this->db->insert_id();            
        }
        /*registramos los modulos*/
        foreach($_POST['modulos'] as $modulo)
        {
            $dataInsert = [
                            'tipo_empleado_id' => $idPerfil,
                            'modulo_id'        => $modulo
                          ];
            $this->db->insert("tipo_empleado_modulo", $dataInsert);              
        }
        return true;
    } 

    public function eliminar($idPerfil) {

        $perfilUpdate = [
                              "estado" => ST_ELIMINADO
                           ];
        $this->db->where("id", $idPerfil);
        $this->db->update("tipo_empleados", $perfilUpdate);   

		/*eliminamos los modulos de ese perfil*/	
		$this->db->where("tipo_empleado_id", $idPerfil);
		$this->db->delete("tipo_empleado_modulo");
		
        return true; 
    }   

    public function getMainList()
    {
        $select = $this->db->from("tipo_empleados")
                           ->where("estado", ST_ACTIVO);
        if($_POST['search'] != '')
        {
            $select->like("tipo_empleado", $_POST['search']);
        }                   

        $selectCount = clone $select;
        $rsCount = $selectCount->get()
                               ->result();
        $rows = count($rsCount);
        
        $rsPerfiles = $select->limit($_POST['pageSize'],$_POST['skip'])
                             ->order_by("id", "desc")
                             ->get()
                             ->result();                                          

        foreach($rsPerfiles as $perfil)
        {
            $perfil->per_editar = "<a class='btn btn-default btn-xs btn_modificar_perfil' data-id='{$perfil->id}' data-toggle='modal' data-target='#myModal'>Modificar</a>";

      
            if($perfil->fla_abogado!=1){
                $perfil->per_eliminar = "<a class='btn btn-danger btn-xs btn_eliminar_perfil' data-id='{$perfil->id}' data-msg='Desea eliminar el perfil: {$perfil->tipo_empleado}?'>Eliminar</a>";
            }else{
                $perfil->per_eliminar = "";
            }
            
        }

        $datos = [
                    'data' => $rsPerfiles,
                    'rows' => $rows
                 ];
        return $datos;      
    }
    public function obtenerModulos($idPerfil='')
    {
        /*obtenemos los modulos de ese perfil*/
        $rsModulosPadres = $this->db->from("modulos")
                                    ->where("mod_es_padre", 1)
                                    ->where("mod_estado", ST_ACTIVO)
                                    ->order_by("mod_orden", "asc")
                                    ->get()
                                    ->result();

        /*obtenemos los modulos agregados a ese perfil*/
        $rsModulosPerfil = $this->db->from("tipo_empleado_modulo")
                                    ->where("tipo_empleado_id", $idPerfil)
                                    ->get()
                                    ->result();
        //print_r($rsModulosPerfil);exit();                            

        /*obtebnemos los modulos hijos*/
        foreach($rsModulosPadres as $padre)
        {
            $rsModulosHijos = $this->db->from("modulos")
                                       ->where("mod_referencia", $padre->mod_id)
                                       ->where("mod_estado", ST_ACTIVO)
                                       ->order_by("mod_orden", "asc")
                                       ->get()
                                       ->result();

            foreach($rsModulosHijos as $hijo)
            {
                foreach($rsModulosPerfil as $perfil)
                {
                    if($hijo->mod_id==$perfil->modulo_id)
                    {
                        $hijo->checkbox = '1';
                    }
                }
            }                           
            $padre->modulos_hijos = $rsModulosHijos;                           
        }    

        return $rsModulosPadres;                                                    

    }
    


}   
