<?PHP

class Pse_token extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('pse_token_model');
		$this->load->model('almacenes_model');
	}


	public function index(){
		$this->load->view('templates/header_administrador');
		$this->load->view('pse_token/basic_index');
		$this->load->view('templates/footer');
	}

	public function crear()
    {
        $data = array();        
        $data['almacenes'] = $this->almacenes_model->select();
        echo $this->load->view('pse_token/modal_crear', $data);
    }

    public function editar($idPseToken)
    {   
    	$data['almacenes'] = $this->almacenes_model->select();
        $data['pseToken'] = $this->pse_token_model->select($idPseToken);
              
        $this->load->view('pse_token/modal_crear', $data);
    }
    public function guardarPseToken() {
        $error = array();        
        if($_POST['almacen'] == '')
        {
            $error['almacen'] = 'falta ingresar almacen';
        }        
        if($_POST['ruta'] == '')
        {
            $error['ruta'] = 'falta ingresar ruta';
        }
        if($_POST['token'] == '')
        {
            $error['token'] = 'falta ingresar token';
        }
        if(count($error) > 0)
        {
            $data = ['status'=>STATUS_FAIL,'tipo'=>1, 'errores'=>$error];
            sendJsonData($data);
            exit();
        }   

        //guardamos la pseToken
        $result = $this->pse_token_model->guardar();
        
        if($result)
        {
            sendJsonData(['status'=>STATUS_OK]);
            exit();
        }else
        {
            sendJsonData(['status'=>STATUS_FAIL, 'tipo'=>2]);
            exit();
        }   

    }

    public function eliminar($idPseToken)
    {
        $result = $this->pse_token_model->eliminar($idPseToken);
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
    public function getMainList()
    {
        $rsDatos = $this->pse_token_model->getMainList();
        sendJsonData($rsDatos);
    }
}