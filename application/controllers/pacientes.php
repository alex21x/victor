<?PHP
use PhpOffice\PhpSpreadsheet\Spreadsheet;    
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Pacientes extends CI_controller

{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('accesos_model');
        $this->load->model('pacientes_model');
        $this->load->model('clientes_model');
        $this->load->model('sexos_model');
        $this->load->model('tipo_pacientes_model');

    }

    public function index(){
        $this->accesos_model->menuGeneral();
        $this->load->view('pacientes/basic_index');
        $this->load->view('templates/footer');

    }

    public function crear(){        
       // mandando el array con tipo de pacientes
        $data['sexos'] = $this->sexos_model->select();
        $data['tipo_pacientes'] = $this->tipo_pacientes_model->select();
        echo $this->load->view('pacientes/modal_crear',$data);
    }

    public function editar($idPaciente){       
        // medelo para llenar comb tipo de paicente
        $data['sexos'] = $this->sexos_model->select();
        $data['tipo_pacientes']=$this->tipo_pacientes_model->select();
        $data['paciente'] =  $this->pacientes_model->select($idPaciente);

        //$data['tipo_clientes'] = $this->tipo_clientes_model->select();
        $this->load->view('pacientes/modal_crear',$data);
    }      


    public function guardarPaciente_v(){

        $error = array();
        if($_POST['ruc'] == ''){
            $error['ruc'] =  'falta ingresar ruc';
        }
        if($_POST['razon_social'] == ''){
            $error['razon_social'] =  'falta ingresar razon social';
        }
        if($_POST['lugar_nacimiento'] == ''){
            $error['lugar_nacimiento'] = 'falta ingresar lugar nacimiento';
        }
        if($_POST['fecha_nacimiento'] == ''){
            $error['fecha_nacimiento'] = 'falta ingresar lugar fecha_nacimiento';
        }        

        if($_POST['edad'] == ''){
            $error['edad'] = 'falta ingresar edad';
        }
        if($_POST['mes'] == ''){
            $error['mes'] = 'falta ingresar lugar mes';
        }
        if($_POST['dia'] == ''){
            $error['dia'] = 'falta ingresar dia';
        }

        if($_POST['sexo'] == ''){
            $error['sexo'] = 'falta ingresar sexo';
        }

        if($_POST['telefono'] == ''){
            $error['telefono'] = 'falta ingresar telefono';
            $error['sexo'] = 'falta ingresar telefono';
        }

        if(count($error) > 0){
            $data = ['status' => STATUS_FAIL, 'tipo' => 1, 'errores' => $error];
            echo json_encode($data);
            exit();
        }

        //GUARDARMOS PACIENTE
        $result =  $this->pacientes_model->guardarPaciente_v();
        if($result){
            echo json_encode(['status' => STATUS_OK]);
            exit();
        } else {
            echo json_encode(['status' => STATUS_FAIL,'tipo' => 2]);
            exit();
        }
    }



     public function guardarPaciente_mp(){
        $this->db->db_debug = false;

        $error = array();

        if($_POST['ruc_mp'] == ''){
            $error['ruc_mp'] =  'falta ingresar ruc';
        }

        if($_POST['razon_social_mp'] == ''){
            $error['razon_social_mp'] =  'falta ingresar razon social';
        }
        if($_POST['lugar_nacimiento_mp'] == ''){            
            $error['lugar_nacimiento_mp'] = 'falta ingresar lugar nacimiento';          
        }

        if($_POST['fecha_nacimiento_mp'] == ''){
            $error['fecha_nacimiento_mp'] = 'falta ingresar lugar fecha_nacimiento';
        }

        if($_POST['edad_mp'] == ''){
            $error['edad_mp'] = 'falta ingresar edad';
        }

        if($_POST['mes_mp'] == ''){
            $error['mes_mp'] = 'falta ingresar lugar mes';
        }

        if($_POST['dia_mp'] == ''){
            $error['dia_mp'] = 'falta ingresar dia';
        }        

        if($_POST['sexo_mp'] == ''){
            $error['sexo_mp'] = 'falta ingresar sexo';
        }

        if($_POST['telefono_mp'] == ''){
            $error['telefono_mp'] = 'falta ingresar telefono';
        }

        if(count($error) > 0){
            $data = ['status' => STATUS_FAIL, 'tipo' => 1, 'errores' => $error];
            echo json_encode($data);
            exit();
        }

       //GUARDAR IMAGEN
       $carpeta = 'images/pacientes/';
       opendir($carpeta);
       $destino = $carpeta.$_FILES['foto_mp']['name'];
       copy($_FILES['foto_mp']['tmp_name'], $destino);

       $fecha_nacimiento =  new DateTime($_POST['fecha_nacimiento_mp']);
       $fecha_nacimiento = $fecha_nacimiento->format('Y-m-d');       

       try{
        //GUARDARMOS PACIENTE
        $paciente = array(                       
                        'ruc'  => $_POST['ruc_mp'],
                        'razon_social' => $_POST['razon_social_mp'],
                        'lugar_nacimiento' => $_POST['lugar_nacimiento_mp'],
                        'fecha_nacimiento' => $fecha_nacimiento,
                        'edad' => $_POST['edad_mp'],
                        'mes'  => $_POST['mes_mp'],
                        'dia'  => $_POST['dia_mp'],
                        'sexo'  => $_POST['sexo_mp'],
                        'alergia'  => $_POST['alergia_mp'],
                        'telefono' => $_POST['telefono_mp'],
                        'pac_tipo_id' => $_POST['tipo_paciente_mp'],
                        'foto' => $_FILES['foto_mp']['name'],
                        'responsable'  => $_POST['responsable_mp'],
                        'observacion'  => $_POST['observacion_mp'],
                        'estado_civil' => $_POST['estado_civil_mp']
                    );

        $result = $this->db->insert('pacientes',$paciente);
        $paciente_id =  $this->db->insert_id();        

        $paciente = array_merge($paciente,array('paciente_id' => $paciente_id));
       } catch(Exception $e){
         $result = 0;
       }              

        if($result != 0){        
            echo json_encode(['status' => STATUS_OK,'paciente'=> $paciente]);
            exit();
        } else{        
            echo json_encode(['status' => STATUS_FAIL,'tipo' => 2]);
            exit();
        }
    }


    public function guardarPaciente(){

        $fecha_nacimiento =  new DateTime($_POST['fecha_nacimiento']);
        $fecha_nacimiento = $fecha_nacimiento->format('Y-m-d');
        $data = array('ruc' => $_POST['ruc'],
                      'razon_social' => strtoupper($_POST['razon_social']),
                      'lugar_nacimiento' => strtoupper($_POST['lugar_nacimiento']),
                      'fecha_nacimiento' => $fecha_nacimiento,
                      'edad' => $_POST['edad'],
                      'mes' => $_POST['mes'],
                      'dia' => $_POST['dia'],
                      'sexo' => strtoupper($_POST['sexo']),
                      'telefono' => $_POST['telefono']);

        $rs = $this->pacientes_model->guardarPaciente($data);
        if($rs){
            echo json_encode(['status' => STATUS_OK]);
            exit();
        } else {
            echo json_encode(['status' => STATUS_FAIL]);
            exit();
        }
    }   



    public function buscarPaciente(){               
        $rs =  $this->pacientes_model->select('',$_POST['ruc']);
        $rsCliente = [];

        if($rs[0]->cliente_id != NULL){
            $rsCliente =  $this->clientes_model->select($rs[0]->cliente_id);            
        }

        if($rs){
            echo json_encode(array('status' => STATUS_OK,'data' => $rs,'dataCliente' => $rsCliente));
            exit();
         } else{
            echo json_encode(array('status' => STATUS_FAIL));
            exit();
         }
    }

    public function buscador_paciente(){
        $paciente = $this->input->get('term');      
        echo json_encode($this->pacientes_model->selectAutocomplete($paciente,ST_ACTIVO));

    }   

    public function eliminar($idPaciente){
        $result = $this->pacientes_model->eliminar($idPaciente);    
        if($result){
            echo json_encode(['status' => STATUS_OK]);
            exit();
        } else{
            echo json_encode(['status' => STATUS_FAIL]);
            exit();
        }
    }

    public function getMainList(){
        $rsDatos = $this->pacientes_model->getMainList();
        echo json_encode($rsDatos);
    }

    public function modal_nuevoPaciente(){
        $data['sexos'] = $this->sexos_model->select();
        $data['tipo_pacientes']=$this->tipo_pacientes_model->select();
        echo $this->load->view('pacientes/modal_nuevoPaciente',$data);        
    }

    //SeachPaciente
    public function searchCustomer(){ 
        $ruc =  $_POST['ruc'];        
        //BUSCAMOS EN EL CLIENTE EN LA BASE DE DATOS        
        $paciente = $this->pacientes_model->pacientePorRuc($ruc);
        if($paciente['id'] == 0){
        //OBTENEMOS EL VALOR                           
        $consultaApi = file_get_contents('http://mundosoftperu.com/reniec/consulta_reniec.php?dni='.$ruc);
        $consulta = json_decode($consultaApi);
            if($consultaApi != ''){                
                sendJsonData(['status'=>STATUS_OK,'typeCustomer' => $typeCustomer,'paterno' => $consulta[2],'materno' => $consulta[3],'nombres' => $consulta[1]]);
            }
            else{
                sendJsonData(['status'=>STATUS_FAIL, 'msg'=> 'DNI NO ENCONTRADO']);
                exit();
            }                    
       }
        else{
            sendJsonData(['status'=>STATUS_FAIL, 'msg'=> 'Paciente ya se encuentra registrado']);
            exit();
    }}

    //DNI AUTOMATICO 19/09/2020
    public function dni_auto(){
        $rsCliente = $this->db->from('pacientes')
                              ->where('SUBSTRING(ruc,1,1)',9)                                                           
                              ->order_by('id','DESC')
                              ->limit(1)
                              ->get()
                              ->row();                              
                
            if(count($rsCliente) > 0){
                $dni_auto = $rsCliente->ruc+1;
            }else{
                $dni_auto = 90000000;
            }
                echo json_encode(['status'=>STATUS_OK,'dni_auto'=> $dni_auto]);
                exit();     
    }


    public function exportarExcel(){

        $rsPacientes = $this->db->from('pacientes pac')
                                ->join('sexos sex','pac.sexo = sex.id')
                                ->where('pac.estado',ST_ACTIVO)
                                ->get()
                                ->result();   
                               //var_dump($rsPacientes);exit;

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()->setCreator('Alexander Fernández')
                    ->setLastModifiedBy('Alexander Fernández')
                    ->setTitle('Pacientes')
                    ->setSubject('PHPHojaCalculo')
                    ->setDescription('Hoja de Cálculo para reporte Pacientes')
                    ->setKeywords('Office 2013')
                    ->setCategory('Test Archivo');


    
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(20);


        $spreadsheet->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('F1')->getFont()->setBold(true);


        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B1','REPORTE DE PACIENTES');

        $spreadsheet->getActiveSheet()
                    ->setCellValue('A3','NOMBRES')
                    ->setCellValue('B3','DNI')
                    ->setCellValue('C3','TELEFONO')
                    ->setCellValue('D3','DIRECCION')
                    ->setCellValue('E3','FECHA NACIMIENTO')
                    ->setCellValue('F3','EDAD')
                    ->setCellValue('G3','SEXO')
                    ->setCellValue('H3','ALERGIA')
                    ->setCellValue('I3','RESPONSABLE')
                    ->setCellValue('J3','ESTADO CIVIL')
                    ->setCellValue('K3','OBSERVACIÓN');

        $i = 4;
        foreach ($rsPacientes as $value) {

                $spreadsheet->getActiveSheet()
                            ->setCellValue('A'.$i, $value->razon_social)
                            ->setCellValue('B'.$i, $value->ruc)
                            ->setCellValue('C'.$i, $value->telefono)
                            ->setCellValue('D'.$i, $value->lugar_nacimiento)
                            ->setCellValue('E'.$i, $value->fecha_nacimiento)
                            ->setCellValue('F'.$i, $value->edad.' años '.$value->mes.' mes '.$value->dia.' días')
                            ->setCellValue('G'.$i, $value->sexo)
                            ->setCellValue('H'.$i, $value->alergia)
                            ->setCellValue('I'.$i, $value->responsable)
                            ->setCellValue('J'.$i, $value->estado_civil)
                            ->setCellValue('K'.$i, $value->observacion);                        
            $i++;            
        }


        $spreadsheet->setActiveSheetIndex(0);
        header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="pacientes.xlsx"');
        header('Cache-Control: max-age=0');

        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');

        $writer = IOFactory::createWriter($spreadsheet,'Xlsx');
        $writer->save('php://output');
        exit;
    }
}    