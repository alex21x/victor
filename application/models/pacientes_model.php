<?PHP
class Pacientes_model extends CI_Model
{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function select($idPaciente = '', $ruc = ''){				

		if($ruc != ''){
				$this->db->where('ruc',$ruc);		
		}
		if($idPaciente == ''){
			
			$rsPacientes = $this->db->from('pacientes')
					 				->get()
					 				->result();					
					 return $rsPacientes;
		} else{
			$rsPaciente =  $this->db->from('pacientes')
									->where('id',$idPaciente)
									->get()
									->row();									
			return $rsPaciente;
		}
	}

	public function guardarPaciente_v(){

	   //GUARDAR IMAGEN
       $carpeta = 'images/pacientes/';
       opendir($carpeta);
       $destino = $carpeta.$_FILES['foto']['name'];

       copy($_FILES['foto']['tmp_name'], $destino);

		$fecha_nacimiento =  new DateTime($_POST['fecha_nacimiento']);
		$fecha_nacimiento = $fecha_nacimiento->format('Y-m-d');

		if($_POST['id']  == '' ){
		//echo '121';exit;
			$dataInsert = array(
						'ruc' => $_POST['ruc'],
						'foto'=> $_FILES['foto']['name'],
						'razon_social' => strtoupper($_POST['razon_social']),
						'lugar_nacimiento' => strtoupper($_POST['lugar_nacimiento']),
						'fecha_nacimiento' => $fecha_nacimiento,
						'edad' => $_POST['edad'],
						'mes'  => $_POST['mes'],
						'dia'  => $_POST['dia'],
						'sexo' => $_POST['sexo'],
						'telefono' => $_POST['telefono'],
						'alergia' => $_POST['alergia'],
						'pac_tipo_id'=> $_POST['tipo_paciente'],
						'responsable'=> $_POST['responsable'],
						'observacion'=> $_POST['observacion'],
						'estado_civil'=> $_POST['estado_civil']
						);

			//var_dump($dataInsert);exit();
			$this->db->insert('pacientes',$dataInsert);
		} else{			
			$dataUpdate = array(
						'ruc' => $_POST['ruc'],
						'foto'=> $_FILES['foto']['name'],
						'razon_social' => strtoupper($_POST['razon_social']),
						'lugar_nacimiento' => strtoupper($_POST['lugar_nacimiento']),
						'fecha_nacimiento' => $fecha_nacimiento,
						'edad' => $_POST['edad'],
						'mes'  => $_POST['mes'],
						'dia'  => $_POST['dia'],
						'sexo' => $_POST['sexo'],
						'telefono' => $_POST['telefono'],
						'alergia' => $_POST['alergia'],
						'pac_tipo_id'=>$_POST['tipo_paciente'],
						'responsable'=> $_POST['responsable'],
						'observacion'=> $_POST['observacion'],
						'estado_civil'=> $_POST['estado_civil']
						);

			$this->db->where('id',$_POST['id']);
			$this->db->update('pacientes', $dataUpdate);			
		}

		return TRUE;
	}

	public function guardarPaciente($data){	
		$this->db->insert('pacientes',$data);
		return true;
	}	


	public function selectAutocomplete($buscar, $activo = ''){				
		$pacientes = $this->db->from('pacientes')
						      ->where('estado',$activo)
						      ->like("razon_social",$buscar)
						      ->or_like("ruc",$buscar)
						      ->order_by('razon_social')
				  		      ->get()
				  		      ->result();
		$data = array();
		foreach ($pacientes as $tsArray) {
			$data[] =  array(
							"value" => $tsArray->razon_social,
							"ruc"   => $tsArray->ruc,
							"lugar_nacimiento"   => $tsArray->lugar_nacimiento,
							"fecha_nacimiento"   => $tsArray->fecha_nacimiento,
							"edad"   => $tsArray->edad,
							"mes"   => $tsArray->mes,
							"dia"   => $tsArray->dia,
							"sexo"   => $tsArray->sexo,
							"telefono"   => $tsArray->telefono,							
							"id" => $tsArray->id
							);
		}		
		return $data;
	}	

	public function getMainList(){
	$select = $this->db->from("pacientes")
						->where("estado",ST_ACTIVO);

	if($_POST['search'] != ''){
		$select->like("razon_social",$_POST['search']);
	}

	$selectCount = clone $select;
	$rsCount = $selectCount->get()->result();

	$rows = count($rsCount);

	$rsPacientes = $select->limit($_POST['pageSize'],$_POST['skip'])
						->order_by("id","desc")
						->get()
						->result();

	foreach ($rsPacientes as $paciente) {		
		
		$paciente->pac_editar  = "<a class='btn btn-default btn-xs	btn_modificar_paciente' data-id='{$paciente->id}'
			data-toggle='modal' data-target='#myModal'>Modificar</a>";

		$paciente->pac_eliminar= "<a class='btn btn-default btn-xs btn_eliminar_paciente' data-id='{$paciente->id}' data_msg='Desea Eliminar Paciente: {$paciente->razon_social} ?'>Eliminar</a>";
	}

	$datos = [
			'data' => $rsPacientes,
			'rows' => $rows
	];

	return $datos;
}

public function eliminar($idPaciente){
	$pacienteUpdate = [
					"estado" => ST_ELIMINADO
		];

	$this->db->where("id",$idPaciente);
	$this->db->update("pacientes", $pacienteUpdate);
	return true;
}		

public function pacientePorRuc($ruc){
		$paciente = $this->db->from('pacientes')
							 ->where('ruc',$ruc)
							 ->get()
							 ->row();
        
        if(count($paciente) > 0){
            $respuesta['razon_social'] = $paciente->razon_social;
            $respuesta['id'] = $paciente->id;
        }
        return $respuesta;        
    }
}