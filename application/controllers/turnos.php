<?PHP

class Turnos extends CI_Controller
{
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('turnos_model');        
    }

    public function index(){
        $this->load->view('templates/header_administrador');
        $this->load->view('turnos/base_index');
        $this->load->view('templates/footer');
    }

    public function crear(){
        $this->load->view('turnos/modal_crear');
    }

    public function editar($idTurno){

        $data['turno'] = $this->turnos_model->select($idTurno);
        $this->load->view('turnos/modal_crear',$data);
    }

    public function eliminar($idTurno){

        $result = $this->turnos_model->eliminar($idTurno);       
        if($result){
            echo json_encode(['status' => STATUS_OK]);
            exit();
        } else{
            echo json_encode(['status' => STATUS_FAIL]);
            exit();
        }
    }

    public function guardarTurno(){

        $error = array();        
        if($_POST['turno'] == '')
        {
            $error['turno'] = 'falta ingresar descripcion';
        }
        if(count($error) > 0)
        {
            $data = ['status'=>STATUS_FAIL,'tipo'=>1, 'errores'=>$error];
            echo json_encode($data);
            exit();
        }   

        //guardamos turno
        $result = $this->turnos_model->guardarTurno();
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
        $rsTurnos = $this->turnos_model->getMainList();
        echo json_encode($rsTurnos);
    }    
}