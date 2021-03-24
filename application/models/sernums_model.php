<?PHP if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SerNums_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function select($idSerNum = '', $tipoDocumento_id = '',$empresa_id = '') {
        if($idSerNum == '') {
            if($tipoDocumento_id != '')
                $this->db->where('tipo_documento_id',$tipoDocumento_id);
            if($empresa_id != '')
                $this->db->where('empresa_id',$empresa_id);

            $this->db->where('almacen_id',$this->session->userdata("almacen_id"));            


            $rsSerNums = $this->db->from("ser_nums")
                                     ->where("estado", ST_ACTIVO)
                                     ->get()
                                     ->result();
            return $rsSerNums;
        } else {
            $rsSerNum = $this->db->from("ser_nums")
                                ->where("id", $idSerNum)
                                ->get()
                                ->row();
            return $rsSerNum;
        }           
    }

    public function guardar() {        
        if($_POST['id']!='') {
            $dataUpdate = [
                            'empresa_id'    => $_POST['empresa'],
                            'almacen_id'    => $_POST['almacen'],
                            'tipo_documento_id' => $_POST['tipo_documento'],
                            'serie'    => strtoupper($_POST['serie']),
                            'numero'   => $_POST['numero']

                          ];
            $this->db->where('id', $_POST['id']);
            $this->db->update('ser_nums', $dataUpdate);                          
        } else {
            $dataInsert = [
                            'empresa_id'    => $_POST['empresa'],
                            'almacen_id'    => $_POST['almacen'],
                            'tipo_documento_id' => $_POST['tipo_documento'],
                            'serie'   => strtoupper($_POST['serie']),
                            'numero'  => $_POST['numero'],
                            'fecha_insert' => date('Y-m-d h:i:s'),
                            'estado'  => ST_ACTIVO
                          ];
            $this->db->insert('ser_nums', $dataInsert);
        }
        return true;
    } 

    public function eliminar($idSerNum) {

        $serNumUpdate = [
                              "estado" => ST_ELIMINADO
                           ];
        $this->db->where("id", $idSerNum);
        $this->db->update("ser_nums", $serNumUpdate);            
        return true; 
    }   

    public function getMainList()
    {
        $select = $this->db->select('nse.id nse_id,nse.almacen_id almacen_id,nse.serie serie,nse.numero numero,alm.alm_nombre alm_nombre,tdc.tipo_documento tipo_documento')
                           ->from("ser_nums nse")
                           ->join('tipo_documentos tdc','tdc.id   = nse.tipo_documento_id')
                           ->join('almacenes alm','alm.alm_id = nse.almacen_id')
                           ->where("nse.estado", ST_ACTIVO);
        if($_POST['search'] != '')
        {
            $select->like("serie", $_POST['search']);
        }                   

        $selectCount = clone $select;
        $rsCount = $selectCount->get()
                               ->result();

        $rows = count($rsCount);        
        $rsSerNums = $select->limit($_POST['pageSize'],$_POST['skip'])
                              ->order_by("nse.id", "desc")
                              ->get()
                              ->result();

        $i = 1;
        foreach($rsSerNums as $rsSerNum)
        {   
            $rsSerNum->correlativo = $i;
            $rsSerNum->nse_editar  = "<a class='btn btn-default btn-xs btn_modificar_serNum' data-id='{$rsSerNum->nse_id}' data-toggle='modal' data-target='#myModal'>Modificar</a>";
            $rsSerNum->nse_eliminar = "<a class='btn btn-danger btn-xs btn_eliminar_serNum' data-id='{$rsSerNum->nse_id}' data-msg='Desea eliminar la Serie: {$rsSerNum->serie}?'>Eliminar</a>";
            $i++;
        }

        $datos = [
                    'data' => $rsSerNums,
                    'rows' => $rows
                 ];
        return $datos;      
    }
}   
