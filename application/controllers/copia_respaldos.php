<?PHP



class Copia_respaldos extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('copia_respaldos_model');
		date_default_timezone_set('America/Lima');
	}



	public function respaldo(){


		$this->load->view('templates/header_administrador');
		$this->load->view('copia_de_seguridad/respaldo');
		$this->load->view('templates/footer');
	}




	public function subir_copia(){
		$this->load->view('copia_de_seguridad/modal_subir');
	}


	public function subir_copia_g(){

	   //GUARDAR BACKUP
       $carpeta = 'files/backup/';
       opendir($carpeta);
       $destino = $carpeta.$_FILES['backup']['name'];
       
       copy($_FILES['backup']['tmp_name'], $destino);


      	$arrayCopiaRespaldo = array(
								'copia_respaldo' => $_FILES['backup']['name'],
								'fecha' => date('Y-m-d H:i:s')
							);
		$result = $this->db->insert('copia_respaldos',$arrayCopiaRespaldo);
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

	public function respaldo_exec(){

		//exec('c:\WINDOWS\system32\cmd.exe /c START C:\xampp\htdocs\laboratorio\BACKUP\CONFIG\script.bat');
		$nombre = 'archivo_out_'.date('d-m-Y').'_'.date('h-i-s').'.sql';
		$directorio =  "files/backup/";
		$dir = $directorio."/".$nombre;

		//echo "Su base está siendo salvada.......\n<br>";
		system("C:/xampp/mysql/bin/mysqldump.exe -h localhost --port 3306 -u root --ignore-table=mundosoft5.0.copia_respaldos mundosoft5.0 > $dir");
		//echo "Fin. Puede recuperar la base por FTP";

		$arrayCopiaRespaldo = array(
								'copia_respaldo' => $nombre,
								'fecha' => date('Y-m-d H:i:s')
							);
		$result = $this->db->insert('copia_respaldos',$arrayCopiaRespaldo);
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

	public function restaurar_exec(){

		$nombre = $_POST['nombre'];
		$directorio =  "files/backup/";
		$dir = $directorio.$nombre;

		system("C:/xampp/mysql/bin/mysql.exe -h localhost --port 3306 -u root mundosoft5.0 < $dir");		


     	sendJsonData(['status'=>STATUS_OK]);
     	exit();
	}

	public function getMainList(){

		$rsCopiaRespaldos = $this->copia_respaldos_model->getMainList();
		echo json_encode($rsCopiaRespaldos);	
	}

 	public function eliminar($idCopiaRespaldo)
    {
    	$result = $this->copia_respaldos_model->eliminar($idCopiaRespaldo);
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










