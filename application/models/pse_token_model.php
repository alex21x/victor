<?PHP

class Pse_token_model extends CI_Model
{
	
	public function __construct()
	{
		parent::__construct();
    	date_default_timezone_set('America/Lima');
	}

	public function select($idPseToken = '') {
        if($idPseToken == '') {                        
            $this->db->where('almacen_id',$this->session->userdata("almacen_id"));
            $rsPseTokens = $this->db->from("pse_token")
	                                ->where("estado", ST_ACTIVO)
	                                ->get()
	                                ->result();
            return $rsPseTokens;
        } else {
            $rsPseToken = $this->db->from("pse_token")
	                               ->where("id", $idPseToken)
	                               ->get()
	                               ->row();
            return $rsPseToken;
        }           
    }



    public function pseToken($idAlmacen){
        $rsPseToken = $this->db->from('pse_token')
                                ->where('almacen_id',$idAlmacen)
                                ->get()
                                ->row();


        return $rsPseToken;
    }

    public function guardar() {
        if($_POST['id']!='') {
            $dataUpdate = [                            
                            'almacen_id' => $_POST['almacen'],
                            'ruta'    => $_POST['ruta'],
                            'token'   => $_POST['token']
                          ];
            $this->db->where('id', $_POST['id']);
            $this->db->update('pse_token', $dataUpdate);
        } else {
            $dataInsert = [                            
                            'almacen_id' => $_POST['almacen'],                                                                                   
                            'ruta'    => $_POST['ruta'],
                            'token'   => $_POST['token'],
                            'estado'  => ST_ACTIVO
                          ];
            $this->db->insert('pse_token', $dataInsert);
        }
        return true;
    } 

    public function eliminar($idPseToken) {

        $pseTokenUpdate = [
                              "estado" => ST_ELIMINADO
                           ];
        $this->db->where("id", $idPseToken);
        $this->db->update("pse_token", $pseTokenUpdate);
        return true; 
    }   

    public function getMainList()
    {
        $select = $this->db->select('pst.id pst_id,pst.ruta pst_ruta,pst.token pst_token,alm.alm_id almacen_id,alm.alm_nombre alm_nombre')
                           ->from("pse_token pst")
                           ->join('almacenes alm','alm.alm_id = pst.almacen_id')
                           ->where("pst.estado", ST_ACTIVO);

        if($_POST['search'] != '')
        {
            //$select->like("serie", $_POST['search']);
        }                   

        $selectCount = clone $select;
        $rsCount = $selectCount->get()
                               ->result();

        $rows = count($rsCount);        
        $rsPseTokens = $select->limit($_POST['pageSize'],$_POST['skip'])
                              ->order_by("pst.id", "desc")
                              ->get()
                              ->result();

        $i = 1;
        foreach($rsPseTokens as $rsPseToken)
       {               
            $rsPseToken->pst_editar  = "<a class='btn btn-default btn-xs btn_modificar_pseToken' data-id='{$rsPseToken->pst_id}' data-toggle='modal' data-target='#myModal'>Modificar</a>";
            $rsPseToken->pst_eliminar = "<a class='btn btn-danger btn-xs btn_eliminar_pseToken' data-id='{$rsPseToken->pst_id}' data-msg='Desea eliminar PseToken: ?'>Eliminar</a>";
            $i++;
        }

        $datos = [
                    'data' => $rsPseTokens,
                    'rows' => $rows
                 ];
        return $datos;      
    }
}


