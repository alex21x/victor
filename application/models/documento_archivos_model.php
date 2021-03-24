<?PHP

class Documento_archivos_model extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
	}

 public function select($documentoArchivoId = '',$documentoId = ''){

    if ($documentoArchivoId == '') {
      if($documentoId != '')
        $this->db->where('doc_id',$documentoId);

       $rsDocumentoArchivos = $this->db->from("documento_archivos")
                                    ->where('estado',ST_ACTIVO)
                                    ->get()
                                    ->result();

         return $rsDocumentoArchivos;

    } else{
        $rsDocumentoArchivo = $this->db->from("documento_archivos")
                                     ->where("archi_id",$documentoArchivoId)                                     
                                     ->get()
                                     ->row();
        return $rsDocumentoArchivo; 
    }
  }

  public function eliminar($documentoArchivoId){
      
    $documentoArchivo = [
          "estado" => ST_ELIMINADO
    ];

    $this->db->where("archi_id",$documentoArchivoId);
    $this->db->update("documento_archivos", $documentoArchivo);
    return true;
  }
}
