<?PHP if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Resumenes_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('America/Lima');
    }

    public function select($idResumen='') {
        if($idResumen == '') {
            $rsResumenes = $this->db->from("resumenes")
                                     ->where("estado", ST_ACTIVO)
                                     ->get()
                                     ->result();
            return $rsResumenes;
        } else {
            $rsResumen = $this->db->from("resumenes")
                            ->where("id", $idResumen)
                            ->where('estado',ST_ACTIVO)
                            ->get()
                            ->row();
            return $rsResumen;          
        }           
    }
    public function getListComprobanteResumen($idResumen='') {
    	if ($idResumen=='') {
    		$rsComprobantesResumenes = $this->db->select('compr.id as comprores_id,compr.comprobante_id as comprobante_id, compr.estado as comprores_estado,res.fecha as resumen_fecha, res.correlativo')
    									     ->from('comprobantes_resumen compr')
    									     ->join('resumenes res','res.id=compr.resumen_id')
    									     //->where('res.estado',0)
    									     ->get()
    									     ->result();
    		return $rsComprobantesResumenes;
    	} else {
    		$rsComprobanteResumen = $this->db->select('compr.id as comprores_id,compr.comprobante_id as comprobante_id, compr.estado as comprores_estado,res.fecha as resumen_fecha, res.correlativo')
    									     ->from('comprobantes_resumen compr')
    									     ->join('resumenes res','res.id=compr.resumen_id')
    									     //->where('res.estado',0)
    									     ->where('res.id',$idResumen)
    									     ->get()
    									     ->result();
    		return $rsComprobanteResumen;
    	}
    }

    public function guardarResumen($comprobante_id='',$estado='') {
    	$resultado = $this->db->select('id')
    					   ->from('resumenes')
    					   ->where('estado','!=0')
    					   ->order_by('id','desc')
    					   ->get()
    					   ->row();

    	$val = intval(count($resultado));    	

    	$numero = $this->generarNumero();
    	$correlativo = str_pad($this->generarcorrelativo(), 0,3);

    	if ($val==0) {    		
	        $dataInsert = [
	                        'numero'      => $numero,
	                        'fecha'       => date("Y-m-d H:m:s"),
	                        'correlativo' => $correlativo,
	                        'estado'      => 0
	                      ];
	        $this->db->insert('resumenes', $dataInsert);	        
	        $resumenId = $this->db->insert_id();  
    	} else {
    		$resumenId = $this->db->from('resumenes')
    							  ->select_max('id')
    							  ->get()
    							  ->row();

    		$resumenId =$resumenId->id;

    	}        

        $this->guardarComprobanteresumen($resumenId,$comprobante_id,$estado);

        $this->db->where('id',$comprobante_id);
        $this->db->set('estado_sunat',6);
        $this->db->update('comprobantes');

        /*$this->db->where('comprobante_id',$comprobante_id);
        $items = $this->db->get('items')->result();
        foreach ($items as $i) {
           $this->quitarStock($i->producto_id,$i->cantidad);
        }*/

        return true;
    } 

    public function quitarStock($idProducto,$cantidad)
    {
      //solo quitaremos de stock a los producto que pertenezacan a esa compra
      $this->db->where("ejm_producto_id",$idProducto);
      $this->db->where('ejm_almacen_id',$this->session->userdata('almacen_id'));
      $this->db->where('ejm_estado',ST_PRODUCTO_VENDIDO);
      $ejm = $this->db->get('ejemplar')->result();

      for($x=0;$x<$cantidad;$x++) {
           $this->db->where('ejm_id',$ejm[$x]->ejm_id);
           $this->db->set("ejm_estado", ST_PRODUCTO_DISPONIBLE);
           $this->db->update("ejemplar");  
      }                      
    }

    public function guardarComprobanteresumen($resumenId='',$comprobante_id='',$estado='') {
    	
    	$result = $this->db->from('comprobantes_resumen')
    					   ->where('comprobante_id',$comprobante_id)
    					   ->get()
    					   ->row();

    	if ($result) {
           /* if (($result) && $estado ==3) {
                $dataInsert = [
                        'comprobante_id'    => $comprobante_id,
                        'resumen_id'        => $resumenId,
                        'estado'            => 3,
                        'estado_sunat'      => 0
                      ];
                $this->db->insert('comprobantes_resumen', $dataInsert);
            }
            else {*/
                $dataUpdate = [
                            'estado' => $estado
                          ];
                $this->db->where('comprobante_id',$comprobante_id);
                $this->db->update('comprobantes_resumen',$dataUpdate);
            //}

    	} else {

    		$dataInsert = [
                        'comprobante_id'    => $comprobante_id,
                        'resumen_id'    	=> $resumenId,
                        'estado'			=> $estado,
                        'estado_sunat'      => 0
                      ];
        	$this->db->insert('comprobantes_resumen', $dataInsert);
    	}

        return true;

    }
    public function ActualizarEstado($idComprobante='',$enviado_sunat='') {
    	$dataUpdatecomprobante = [
        							'enviado_sunat' => $enviado_sunat
        						 ];
        $this->db->where('id',$idComprobante);
      	$this->db->update('comprobantes',$dataUpdatecomprobante);    	
    }

    public function generarNumero()
    {
    	$result = $this->db->from('resumenes')
    					   ->where("DATE_FORMAT(fecha, '%Y-%m-%d') =",date("Y-m-d"))
    					   ->order_by('id','desc')
    					   ->get()
    					   ->result();    	
    	$row = (count($result));
    	$numero = date("d-m-Y").'-'.(intval($row) +1);
    	return $numero;
    }
    public function Guardar_Resumen($resumen_id='')  {        
        $dataUpdateresume = [
                        'estado' => 4
                     ];
        $this->db->where('id',$resumen_id);
        $this->db->update('resumenes',$dataUpdateresume);
                 
    }
    public function generarcorrelativo()
    {
    	$result = $this->db->from('resumenes')    					   
    					   ->get()
    					   ->result();    	
    	$row = (count($result));
    	return (intval($row) + 1);
    }

    public function getMainList()
    {
        if($_POST['numero'] != '')
        {
            $this->db->like("numero", $_POST['numero']);
        }                   
        $select = $this->db->from("resumenes");

        $selectCount = clone $select;
        $rsCount = $selectCount->get()
                               ->result();
        $rows = count($rsCount);
        
        $rsResumen = $select->limit($_POST['pageSize'],$_POST['skip'])
                              ->order_by("id", "desc")
                              ->get()
                              ->result();                                          
        $rsResumen->estado = ($rsResumen->estado!=0) ? 'enviado' : '' ;
        $i=1;
        foreach($rsResumen as $resumen)
        {
        	if($resumen->estado == 5) {
        		$resumen->enviar = "<a class='btn btn-success disabled'>Enviado</a>";	
        	}else if($resumen->estado == 0){
        		$resumen->enviar = "<a class='btn btn-primary' onclick='send_anulacion(2,{$resumen->id})' >Enviar a SUNAT</a>";
        	}else if($resumen->estado == 4){
                $resumen->enviar = "<a class='btn btn-warning' onclick='send_anulacion(2,{$resumen->id})' >Consultar recepción</a>";
            }
        	//$resumen->estado = ($resumen->estado==0) ? 'falta enviar' : 'enviado' ;
        	$resumen->indice = $i;
        	$i++;

        }

        $datos = [
                    'data' => $rsResumen,
                    'rows' => $rows
                 ];
        return $datos;      
    }

    public function getMainListDetail() {
    	if ($_POST['resumen_id']) {
    		$this->db->where('resumen_id',$_POST['resumen_id']);
    	}

    	$rsComprobanteresumen = $this->db->select('comp.id as comprobante_id,comp.serie as serie,comp.numero,comp.fecha_de_emision as f_emision,
    											   comp.fecha_de_vencimiento as f_vencimiento, cli.razon_social as cliente, comp.total_gravada as importe,comp.total_igv as igv, comp.total_a_pagar as total,cres.estado')
										 ->from('comprobantes_resumen cres')
										 ->join('comprobantes comp','cres.comprobante_id=comp.id')
										 ->join('clientes cli','cli.id = comp.cliente_id')
										 ->get()
										 ->result();

    	$rows = count($rsComprobanteresumen);
    	$i=1;
        foreach($rsComprobanteresumen as $item)
        {
        	$item->numdoc = $item->serie.'-'.$item->numero;
        	$item->indiced = $i;
        	//$item->estado= ($item->estado==1) ? 'agregar' : 'anular' ;
        	if ($item->estado==1) {
        		$item->estadot ='Adicionar';
        	} elseif ($item->estado==2) {
        		$item->estadot ='Modificar';
        	} else {
        		$item->estadot ='Anulado';
        	}
        	
        	$i++;
           // $item->ingd_eliminar = '<button type="button" class="btn btn-danger btn-xs btn_eliminar_detalle" data-id="'.$item->ingd_id.'" data-msg="Desea eliminar producto: '.$item->prod_nombre.'"><i class="glyphicon glyphicon-remove"></i></button>';
        }

       	$datos = [
       				'data' => $rsComprobanteresumen,
       				'rows' => $rows
       			 ];

        return $datos;         
    }
    


}   
