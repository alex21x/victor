<?PHP
class Endpoints extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();	
		$this->load->model("accesos_model");	
		$this->load->model("endpoints_model");
		$this->load->model("activos_model");
		$this->load->model("modos_model");

		$empleado_id = $this->session->userdata('empleado_id');
        $almacen_id = $this->session->userdata("almacen_id");
        if (empty($empleado_id) or empty($almacen_id)) {
            $this->session->set_flashdata('mensaje', 'No existe sesion activa');
            redirect(base_url());
        }
	}


	public function index(){

 		$this->accesos_model->menuGeneral();
 		$this->load->view('endpoints/basic_index');
 		$this->load->view('templates/footer');
 	}

 	public function crear(){
 		$data = array();    

 		$data['activos'] = $this->activos_model->select();
 		$data['modos'] = $this->modos_model->select();
 		echo $this->load->view('endpoints/modal_crear',$data);
 	}

 	public function editar($idEndpoint){ 		
 		$data['activos'] = $this->activos_model->select();
 		$data['modos'] = $this->modos_model->select();
 		$data['endpoint'] =  $this->endpoints_model->select($idEndpoint);                
 		$this->load->view('endpoints/modal_crear',$data);
 	}

 	public function guardarEndpoint(){
 		$error = array();

        if($_POST['endpoint'] == '')
        {
            $error['endpoint'] = 'falta ingresar endpoint';
        }
        if(count($error) > 0)
        {
            $data = ['status'=>STATUS_FAIL,'tipo'=>1, 'errores'=>$error];
            echo json_encode($data);
            exit();
        }   

        //guardamos endpoint
        $result = $this->endpoints_model->guardar();
        $this->cambioEndpoint();

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

 	public function eliminar($idEndpoint){
 		$result = $this->endpoints_model->eliminar($idEndpoint);
		
		if($result){
			echo json_encode(['status' => STATUS_OK]);
			exit();
		} else{
			echo json_encode(['status' => STATUS_FAIL]);
			exit();
		}
 	}

 	public function getMainList(){
 		$rsDatos = $this->endpoints_model->getMainList();
 		 echo json_encode($rsDatos);
 	}

	
	public function cambioEndpoint(){


		$rsEndpoints = $this->db->from("endpoints")
                                     ->where("estado", ST_ACTIVO)
                                     ->where("activo", "activo")
                                     ->get()
                                     ->row();

		$file = APPPATH.'config/constants.php';
		$lines = file($file, FILE_IGNORE_NEW_LINES);
		    //foreach($lines as $key => $line) {
		        unset($lines[41]);
		        unset($lines[42]);
		    //}

		    $data = implode(PHP_EOL, $lines);
		    file_put_contents($file, $data);

		$fh = fopen(APPPATH.'config/constants.php', 'r+') or die('Ocurrio un error al abrir el archivo');
		$texto =  'define("RUTA_API","'.$rsEndpoints->endpoint.'");';
		$texto2 = 'define("MODO","'.$rsEndpoints->modo.'");';

		fseek($fh, 0, SEEK_END);
  		fwrite($fh, "\n$texto\n$texto2") or die("No se puede escribir en el archivo");
  		fclose($fh);    		
	}		
}