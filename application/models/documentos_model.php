
<?php 
  class Documentos_model extends CI_Model
  {
    public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}



  public function select($documentoId = ''){    
    if($documentoId == ''){      
      $rsDocumentos =  $this->db->from('documentos')
                                ->get()
                                ->result();
        return $rsDocumentos;                              

    } else{
      $rsDocumento =  $this->db->from('documentos')
                               ->where('doc_id',$documentoId)
                               ->where('estado',ST_ACTIVO)
                               ->get()
                               ->row();

      $rsDetalles =  $this->db->from('documento_archivos')                               
                              ->where('doc_id',$documentoId)
                              ->where('estado',ST_ACTIVO)
                              ->get()
                              ->result();

      $rsDocumento->detalles = $rsDetalles;
      return $rsDocumento;
    }
  }


	public function guardar_documento()   
   {              

      $epp = ($_POST['epp'] == 'on') ? 1 : 0;
      $fecha_evento   =  (new DateTime($_POST['fecha_evento']))->format('Y-m-d h:i:s');
       if ($_POST['id'] != '') {
       	        $dataUpdate =  [ 
       	        	             'descri_doc' =>$_POST['descripcion']
       	           			      ];
       	         $this->db->where('doc_id',$_POST['id']);
       	         $this->db->update('documentos',$dataUpdate);

       } else{       
               $dataInsert = [ 
                                'descri_doc'=> $_POST['descripcion'],
                                'empleado_insert'=> $this->session->userdata('empleado_id'),
                                'fecha_insert'=> date('Y-m-d h:i:s'),
                                'estado'=> ST_ACTIVO
                            ];       
                    $this->db->insert('documentos',$dataInsert);
                    $_POST['id'] = $this->db->insert_id();

        }
  // codigo para guardar los archivos        
        if($_FILES['images']['name'][0] != ""){
      
        if(is_array($_FILES))  
        {  
            foreach($_FILES['images']['name'] as $name => $value)
                {  
               $file_name = explode(".", $_FILES['images']['name'][$name]);
               //$allowed_extension = array("jpg", "jpeg", "png", "gif","xml","xls","xlsx","pdf","doc","docx","ppt","pptx","txt","zip","rar");  
               //if(in_array($file_name[1], $allowed_extension))  
               //{                      
                    $sourcePath = $_FILES["images"]["name"][$name];
                    $destino = "files/documentos/".$sourcePath;
                    //move_uploaded_file($sourcePath, $targetPath);
                    copy($_FILES['images']['tmp_name'][$name], $destino);
                    $data = array(
                            'doc_id' => $_POST['id'],
                            'descri_archi' => $_FILES['images']['name'][$name],
                            'estado'  => ST_ACTIVO
                    );                                                                
                    $this->db->insert("documento_archivos", $data);
               //}  
        }}}
        return $_POST['id'];
    }

     public function getMainList()
     {      
      
          if ($_POST['ver_todos'] == 'on') {
            $fecha_desde = '';
            $fecha_hasta = '';
          } else {
            $fecha_desde = isset($_POST['fecha_desde']) ? $_POST['fecha_desde'] : $_GET['fecha_desde'];
            $fecha_hasta = isset($_POST['fecha_hasta']) ? $_POST['fecha_hasta'] : $_GET['fecha_hasta'];
          }   
          $nombre_documento  = isset($_POST['nombre_documento']) ? $_POST['nombre_documento'] : $_GET['nombre_documento'];         
          $empleado  = isset($_POST['empleado']) ? $_POST['empleado'] : $_GET['empleado'];          

         if($fecha_desde != ''){
            $fecha_desde =  (new DateTime($fecha_desde))->format('Y-m-d');
            $this->db->where("DATE(doc.fecha_insert) >=", $fecha_desde);
         }
         if($fecha_hasta != ''){
            $fecha_hasta =  (new DateTime($fecha_hasta))->format('Y-m-d');
            $this->db->where("DATE(doc.fecha_insert) <=", $fecha_hasta);
         }       
         if($nombre_documento != ''){            
            $this->db->like('doc.descri_doc',$nombre_documento);
         }
         if($empleado != ''){            
          $this->db->where('doc.empleado_insert',$empleado);
         }

        $select = $this->db->select("doc.doc_id documento_id, doc.descri_doc   nombre_doc,doc.empleado_insert empleado, 
              doc.fecha_insert fecha_creacion, doc.estado estado_documento,CONCAT(epl.nombre,' ',epl.apellido_paterno) empleado",FALSE)                           
                           ->from("documentos doc")
                           ->join("empleados epl","doc.empleado_insert = epl.id")
                           ->where("doc.estado", ST_ACTIVO);

        if($_POST['search'] != '')
        {
            $select->like("doc.descri_doc", $_POST['search']);
        }                   

        $selectCount = clone $select;
        $rsCount = $selectCount->get()
                               ->result();

        $rows = count($rsCount);        
        $rsDocumentos = $select->limit($_POST['pageSize'],$_POST['skip'])
                            ->order_by("doc.doc_id", "desc")
                            ->get()
                            ->result();                                          

        foreach($rsDocumentos as $rsDocumento)
        {            
                $rsDocumento->btn_ticket = '<a href="'.base_url().'index.php/documentos/descargarPdf_ticket/'.$rsDocumento->documento_id.'" target="_seld" ><img src="'.base_url().'/images/pdf.png" data-id="'.$rsDocumento->documento_id.'" class="descargar-pdf"></a>';
                $rsDocumento->btn_pdf = '<a href="'.base_url().'index.php/documentos/descargarPdf/'.$rsDocumento->documento_id.'" target="_seld" ><img src="'.base_url().'/images/pdf.png" data-id="'.$rsDocumento->documento_id.'" class="descargar-pdf"></a>';
                $rsDocumento->doc_editar   = "<a class='btn btn-default btn-xs btn_modificar_documento' data-id='{$rsDocumento->documento_id}' data-toggle='modal' data-target='#myModal'>Modificar</a>";
                $rsDocumento->doc_eliminar = "<a class='btn btn-danger btn-xs btn_eliminar_documento' data-id='{$rsDocumento->documento_id}' data-msg='Desea eliminar el documento'>Eliminar</a>";            
        }

        $datos = [
                    'data' => $rsDocumentos,
                    'rows' => $rows
                 ];

        return $datos;  
     }

     public function getMainListDetail()
    {
        $this->db->where('estado', ST_ACTIVO);
        $select = $this->db->from("documento_archivos")
                           ->where("doc_id", $_POST['doc_id']);
        //cantidad de registros
        $selectCount = clone $select;                               
        $rsCount = $selectCount->get()
                               ->row();
        $rsCount = count($rsCount);
        
        $rsDetalle = $select->limit($_POST['pageSize'], $_POST['skip'])
                            ->get()
                            ->result();  

       foreach($rsDetalle as $archivos)
        {
    $archivos->boton_descargar = '<a href="'.base_url().'files/documentos/'.$archivos->descri_archi.'" target="_blank" ><span class="glyphicon glyphicon-download-alt" data-id="'.$archivos->archi_id.'"></a>';          
        }
                                                  
        $datos = [
                'data' => $rsDetalle,
                'rows' => $rsCount
             ];

        return $datos;       
    }

     public function eliminar($documentoId)
     {
       $documentoUpdate=[
                     'estado'=>ST_ELIMINADO
                         ];  
        $this->db->where('doc_id',$documentoId);
        $this->db->update('documentos',$documentoUpdate);
        return true; 
     }

   }
