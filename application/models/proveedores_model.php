<?php
class Proveedores_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function select($idProveedor = '', $activo = '')
    {
    	if($idProveedor != '')
    	{
    		$rsProveedor = $this->db->from("proveedores")
    		                        ->where("prov_id", $idProveedor)
    		                        ->get()
    		                        ->row();
    		return $rsProveedor;                        
    	}

    	$rsProveedores = $this->db->from("proveedores")
                                  ->where("prov_estado", ST_ACTIVO)
    	                          ->get()
    	                          ->result();
    	return $rsProveedores;                          
    }  

    public function obtener_codigo() {  
        
        $this->db->select_max('prov_id');
        $result = $this->db->get('proveedores');
        $id = $result->row()->prov_id + 1;
        return $id;
    }

    public function guardar()
    {
    	/*validamos eque no exista un proveedor con el mismo ruc*/
    	$rsProveedor = $this->db->from("proveedores")
    							->where("prov_ruc", $_POST['ruc'])
    							->where("prov_id !=", $_POST['id'])
    							->get()
    							->result();
    	if($rsProveedor)
    	{
    		return false;
    	}				

    	if($_POST['id']!='')
    	{
    		$dataUpdate = [
    						'prov_ruc'		    => $_POST['ruc'],
    						'prov_razon_social' => strtoupper($_POST['razon_social']),
    						'prov_celular'      => $_POST['telefono'],
    						'prov_direccion'    => strtoupper($_POST['direccion'])
    					  ];
	        $this->db->where('prov_id', $_POST['id']);
	        $this->db->update('proveedores', $dataUpdate);    					  
    	}else
    	{
    		$dataInsert = [
    						'prov_ruc'		    => $_POST['ruc'],
    						'prov_razon_social' => strtoupper($_POST['razon_social']),
    						'prov_celular'      => $_POST['telefono'],
    						'prov_direccion'    => strtoupper($_POST['direccion']),
    						'prov_estado' 		=> ST_ACTIVO
    					  ];
    		$this->db->insert('proveedores', $dataInsert); 			  
    	}

    	return true;
    } 

    public function eliminar($idProveedor)
    {
        $proveedorUpdate = [
                            "prov_estado" => ST_ELIMINADO
                           ];
        $this->db->where("prov_id", $idProveedor);
        $this->db->update("proveedores", $proveedorUpdate);
    	return true; 
    } 	

    public function getMainList()
    {
    	$select = $this->db->from("proveedores")
    	                   ->where("prov_estado", ST_ACTIVO);
    	if($_POST['search'] != '')
    	{
    		$select->like("prov_ruc", $_POST['search']);
    		$select->or_like("prov_razon_social", $_POST['search']);
    	}                   
    	/*obtenemos la cantidad de registros encontrados*/
    	$selectCount = clone $select;
    	$rsCount = $selectCount->get()
    	                       ->result();
    	$rows = count($rsCount);

    	/*obtenemos los registros encontrados*/
    	$rsProveedores = $select->limit($_POST['pageSize'], $_POST['skip'])
    	                        ->get()
    	                        ->result();                       

        foreach($rsProveedores as $proveedor)
        {
        	$proveedor->prov_editar = "<a class='btn btn-default btn-xs btn_modificar_proveedor' data-id='{$proveedor->prov_id}' data-toggle='modal' data-target='#myModal'>Modificar</a>";
        	$proveedor->prov_eliminar = "<a class='btn btn-danger btn-xs btn_eliminar_proveedor' data-id='{$proveedor->prov_id}' data-msg='Desea eliminar producto: {$proveedor->prov_razon_social}?'>Eliminar</a>";
        }

       	$datos = [
       				'data' => $rsProveedores,
       				'rows' => $rows
       			 ];

        return $datos;    	
    }


    //BUSCADOR PROVEEDOR 06-12-2020 //ALEXANDER FERNANDEZ
    public function selectAutocomplete($buscar, $activo = ''){
        $rsProveedores = $this->db->from('proveedores')
                                  ->where('prov_estado',ST_ACTIVO)
                                  ->like('prov_ruc',$buscar)
                                  ->or_like('prov_razon_social',$buscar)
                                  ->order_by('prov_razon_social,prov_ruc')
                                  ->get()
                                  ->result_array();

        $data = array();
        foreach ($rsProveedores as $value) {

            $data[] = array(
                    "value" => $value['prov_id'].' - '.$value['prov_ruc'].' '.$value['prov_razon_social'],
                    "ruc"   => $value['prov_ruc'],
                    "domicilio1" => $value['prov_direccion'],                    
                    "id" => $value['prov_id']
                );                                                
        }                                        
        return $data;
    }
}