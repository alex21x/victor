<?PHP
class Monedas extends CI_Controller
{	
	function __construct()
	{
		parent::__construct();
		$this->load->model("monedas_model");
		
		  $empleado_id = $this->session->userdata('empleado_id');
          $almacen_id = $this->session->userdata("almacen_id");
          if (empty($empleado_id) or empty($almacen_id)) {
            $this->session->set_flashdata('mensaje', 'No existe sesion activa');
            redirect(base_url());
        }
	}

	public function index(){

    $this->load->view('templates/header_administrador');
    $this->load->view("monedas/index");
    $this->load->view('templates/footer');
	}

	public function vista(){ 	
	   $vista=$this->monedas_model->pintar();
	   echo json_encode($vista);
	}


	public function crear(){
	    $data = array();
	    $data['monedas'] = $this->monedas_model->select();
	 	echo $this->load->view('monedas/modal_crear',$data);
	}


	public function editar($monedas){
	    $data['moneda']=$this->monedas_model->select($monedas);
	    $this->load->view("monedas/modal_crear",$data);
	}

	public function guardar(){
		$res=$this->monedas_model->guardar();
		if ($res) {
			echo json_encode(['status'=>STATUS_OK]);
		 	exit();
		} else{
			echo json_encode(['status'=>STATUS_FAIL, 'tipo'=>2]);
		 	exit();
 		}
	}

	public function eliminar($monedas){
		$result=$this->monedas_model->eliminar($monedas);
		if ($result) {
			echo json_encode(['status' => STATUS_OK]);
			exit();
		}else{


		echo json_encode(['status' => STATUS_FAIL]);
		exit();

		}
	}
}