<?php
/**
 * 
 */
class Igv extends CI_Controller
{
	
	function __construct(){
		parent::__construct();
		$this->load->model("igv_model");
		$this->load->model('activos_model');	
	}

	public function index(){
    	$this->load->view('templates/header_administrador');
    	$this->load->view("igv/index");
    	$this->load->view('templates/footer');
	}

	public function vista(){

	   $vista=$this->igv_model->pintar();
	   echo json_encode($vista);
	}
	public function crear(){
	     	     
     	$data['activos']  = $this->activos_model->select();     	
 		echo $this->load->view('igv/modal_crear',$data);
	}


	public function editar($idIgv){

		$data['activos']  = $this->activos_model->select();
    	$data['valor'] = $this->igv_model->select($idIgv);    	
    	$this->load->view("igv/modal_crear",$data);
	}

	public function guardar(){
    
	 	$res=$this->igv_model->guardar();
	 	if ($res) {
	 		 echo json_encode(['status'=>STATUS_OK]);
	 	  	exit();
	 	} else {

		echo json_encode(['status'=>STATUS_FAIL, 'tipo'=>2]);
 		exit();
 }
}

public function eliminar($igv){

$result=$this->igv_model->eliminar($igv);
if ($result) {
	echo json_encode(['status' => STATUS_OK]);
	exit();
}else{


echo json_encode(['status' => STATUS_FAIL]);
exit();

}


}


}