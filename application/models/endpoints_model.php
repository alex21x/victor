<?PHP
class Endpoints_model extends CI_Model {

	public function __contruct(){
		parent::__contruct();
		$this->load->database();
	}

public function select($idEndpoint = ''){	
			
	if($idEndpoint == '') {			
            $rsEndpoints = $this->db->from("endpoints")
                                     ->where("estado", ST_ACTIVO)
                                     ->get()
                                     ->result();
            return $rsEndpoints;
        } else {
            $rsEndpoint = $this->db->from("endpoints")
                            ->where("id", $idEndpoint)
                            ->get()
                            ->row();
            return $rsEndpoint;
        }           
}

public function selectEnpointActivo(){

	$rsEndpoints = $this->db->from("endpoints")
             				->where("estado", ST_ACTIVO)
             				->where("activo", "activo")
				            ->get()
				            ->row();
}


public function guardar(){
	$rsActivo =  $this->db->from('endpoints')
						  ->where('activo','activo')
						  ->where('estado', ST_ACTIVO)
						  ->get()
						  ->row();

	$rowActivo = count($rsActivo);
	//SOLO PUEDE ESTAR HABILITADO UN ENDPOINT
	if($_POST['activo'] == 'activo' && $rowActivo > 0){ return FALSE;}

	if($_POST['id'] != ''){				
		$dataUpdate = [						
						'endpoint' => strtoupper($_POST['endpoint']),				
						'modo' => $_POST['modo'],
						'activo' => $_POST['activo']
					];

		$this->db->where('id',$_POST['id']);
		$this->db->update('endpoints',$dataUpdate);
	} else {
		$dataInsert = [
						'endpoint' => strtoupper($_POST['endpoint']),
						'modo' => $_POST['modo'],
						'estado' => ST_ACTIVO
			];
			$this->db->insert('endpoints',$dataInsert);
	}
	return true;
}

public function eliminar($idEndpoint){	
	$endpointUpdate = [
					"estado" => ST_ELIMINADO
		];

	$this->db->where("id",$idEndpoint);
	$this->db->update("endpoints", $endpointUpdate);
	return true;
}		

public function getMainList(){

	$select = $this->db->from("endpoints end")												
						->where("end.estado",ST_ACTIVO);

	if($_POST['search'] != ''){
		$select->like("endpoint",$_POST['search']);
	}

	$selectCount = clone $select;
	$rsCount = $selectCount->get()->result();

	$rows = count($rsCount);

	$rsEndpoints = $select->limit($_POST['pageSize'],$_POST['skip'])
						->order_by("end.id","desc")
						->get()
						->result();
	$i=1;
	foreach ($rsEndpoints as $endpoint) {

			$endpoint->end_editar  = "<a class='btn btn-default btn-xs	btn_modificar_endpoint' data-id='{$endpoint->id}'
			data-toggle='modal' data-target='#myModal'>Modificar</a>";

			$endpoint->end_eliminar= "<a class='btn btn-default btn-xs btn_eliminar_endpoint' data-id='{$endpoint->id}' data_msg='Desea Eliminar Endpoint: {$endpoint->endpoint} ?'>Eliminar</a>";
		$i++;
	}

	$datos = [
			'data' => $rsEndpoints,
			'rows' => $rows
	];

	return $datos;
}}
