<?PHP
use PhpOffice\PhpSpreadsheet\Spreadsheet;    
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Profesionales extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('especialidades_model');
		$this->load->model('profesionales_model');
	}

	public function index(){

		$this->load->view('templates/header_administrador');
		$this->load->view('profesionales/base_index');
		$this->load->view('templates/footer');
	}



	public function crear(){

		$data['especialidades'] = $this->especialidades_model->select();
		$this->load->view('profesionales/modal_crear',$data);
	}

	public function editar($idProfesional){

		$data['especialidades'] = $this->especialidades_model->select();
		$data['profesional'] = $this->profesionales_model->select($idProfesional);
		$this->load->view('profesionales/modal_crear',$data);
	}


	public function eliminar($idProfesional){
		$result = $this->profesionales_model->eliminar($idProfesional);		
		if($result){
			echo json_encode(['status' => STATUS_OK]);
			exit();
		} else{
			echo json_encode(['status' => STATUS_FAIL]);
			exit();
		}
	}


	public function guardarProfesional(){

		$error = array();        
        if($_POST['codigo'] == '')
        {
            $error['codigo'] = 'falta ingresar codigo';
        }
        if($_POST['nombre'] == '')
        {
            $error['nombre'] = 'falta ingresar nombre';
        }
        if($_POST['direccion'] == '')
        {
            $error['direccion'] = 'falta ingresar direccion';
        }
        if($_POST['telefono'] == '')
        {
            $error['telefono'] = 'falta ingresar telefono';
        }
        if($_POST['especialidad'] == '')
        {
            $error['especialidad'] = 'falta ingresar especialidad';
        }
        if(count($error) > 0)
        {
            $data = ['status'=>STATUS_FAIL,'tipo'=>1, 'errores'=>$error];
            echo json_encode($data);
            exit();
        }   

        //guardamos la profesion
        $result = $this->profesionales_model->guardarProfesion();
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

		$rsProfesionales = $this->profesionales_model->getMainList();
		echo json_encode($rsProfesionales);
	}

	public function cargarEspecialidad(){

		$rsProfesional =  $this->profesionales_model->select($_POST['idProfesional']);		
		echo json_encode($rsProfesional);
	}



	public function exportarExcel(){

        $rsProfesionales = $this->db->from('profesionales prof')
			                        ->join('especialidades esp','esp.esp_id = prof.prof_especialidad_id')
			                        ->where("prof_estado",ST_ACTIVO)
	                                ->get()
	                                ->result();                                 

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()->setCreator('Alexander Fernández')
                    ->setLastModifiedBy('Alexander Fernández')
                    ->setTitle('Pacientes')
                    ->setSubject('PHPHojaCalculo')
                    ->setDescription('Hoja de Cálculo para reporte Pacientes')
                    ->setKeywords('Office 2013')
                    ->setCategory('Test Archivo');

    
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(20);


        $spreadsheet->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('F1')->getFont()->setBold(true);


        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B1','REPORTE DE MEDICOS');

        $spreadsheet->getActiveSheet()
                    ->setCellValue('A3','CODIGO')
                    ->setCellValue('B3','NOMBRES')
                    ->setCellValue('C3','ESPECIALIDAD')
                    ->setCellValue('D3','DIRECCION')
                    ->setCellValue('E3','TELEFONO');
        $i = 4;
        foreach ($rsProfesionales as $value) {

                $spreadsheet->getActiveSheet()
                            ->setCellValue('A'.$i, $value->prof_codigo)
                            ->setCellValue('B'.$i, $value->prof_nombre)
                            ->setCellValue('C'.$i, $value->esp_descripcion)
                            ->setCellValue('D'.$i, $value->prof_direccion)
                            ->setCellValue('E'.$i, $value->prof_telefono);
            $i++;            
        }

        $spreadsheet->setActiveSheetIndex(0);
        header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="profesionales.xlsx"');
        header('Cache-Control: max-age=0');

        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');

        $writer = IOFactory::createWriter($spreadsheet,'Xlsx');
        $writer->save('php://output');
        exit;
    }
}