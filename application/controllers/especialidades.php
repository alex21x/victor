<?PHP

class Especialidades extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();


		$this->load->model('especialidades_model');
		$this->load->model('profesionales_model');
	}



	public function index(){

		$this->load->view('templates/header_administrador');
		$this->load->view('especialidades/base_index');
		$this->load->view('templates/footer');
	}



	public function crear(){

		$this->load->view('especialidades/modal_crear');
	}

	public function editar($idEspecialidad){

		//echo $idEspecialidad;exit;
		$data['especialidad'] = $this->especialidades_model->select($idEspecialidad);
		$this->load->view('especialidades/modal_crear',$data);
	}


	public function eliminar($idEspecialidad){

		$result = $this->especialidades_model->eliminar($idEspecialidad);		
		if($result){
			echo json_encode(['status' => STATUS_OK]);
			exit();
		} else{
			echo json_encode(['status' => STATUS_FAIL]);
			exit();
		}
	}


	public function guardarEspecialidad(){

		$error = array();        
        if($_POST['descripcion'] == '')
        {
            $error['descripcion'] = 'falta ingresar descripcion';
        }
        if(count($error) > 0)
        {
            $data = ['status'=>STATUS_FAIL,'tipo'=>1, 'errores'=>$error];
            echo json_encode($data);
            exit();
        }   

        //guardamos la especialidad
        $result = $this->especialidades_model->guardarEspecialidad();
        if($result)
        {
            echo json_encode(['status'=>STATUS_OK]);
            exit();
        }else
        {
            echo json_encode(['status'=>STATUS_FAIL, 'tipo'=>2]);
            exit();
        }   
	}


	public function getMainList(){

		$rsEspecialidades = $this->especialidades_model->getMainList();
		echo json_encode($rsEspecialidades);
	}


	public function cargarProfesionales(){

		//echo $_POST['idEspecialidad'];exit();
		$rsProfesionales = $this->profesionales_model->select('',$_POST['idEspecialidad']);
		$rs = '';								
		$rs .=  '<option value="">Seleccione</option>';
		foreach ($rsProfesionales as $key => $value) {			
			$rs .=  '<option value="'.$value->prof_id.'">'.$value->prof_nombre.'</option>';
		}				
		echo $rs;
	}
}