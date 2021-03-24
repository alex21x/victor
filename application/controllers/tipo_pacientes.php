<?php 

class Tipo_pacientes extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();


		$this->load->model('tipo_pacientes_model');
		//$this->load->model('profesionales_model');
	}

	public function index(){

		$this->load->view('templates/header_administrador');
		$this->load->view('tipopacientes/base_index');
		$this->load->view('templates/footer');
	}

	public function crear(){

		$this->load->view('tipopacientes/modal_crear');
	}

	public function editar($idTipoPaciente){

		$data['tipo_paciente'] = $this->tipo_pacientes_model->select($idTipoPaciente);
		$this->load->view('tipopacientes/modal_crear',$data);
	}
   
   public function guardartipopacientes(){

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
        $result = $this->tipo_pacientes_model->guardartipopacientes();
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

		$rsEspecialidades = $this->tipo_pacientes_model->getMainList();
		echo json_encode($rsEspecialidades);
	}


	public function eliminar($idTipoPaciente)
    {
        $result = $this->tipo_pacientes_model->eliminar($idTipoPaciente);
        if($result)
        {
            sendJsonData(['status'=>STATUS_OK]);
            exit();
        }else
        {
            sendJsonData(['status'=>STATUS_FAIL]);
            exit();
        }       
    }

 }