<?php
 class Pagos_model extends CI_Model
 {
 	
 	function __construct()
 	{
 	  parent::__construct();
    date_default_timezone_set('America/Lima');
 	}

 	public function select($pagos = '',$comprobante_id = '',$tipoComprobante = ''){     
    if ($pagos =='') {

      if($comprobante_id != '')
        $this->db->where('comprobante_id', $comprobante_id);      
      if($tipoComprobante != '')
        $this->db->where('tipoComprobante', $tipoComprobante);      

      $res = $this->db->select('pag.id,pag.comprobante_id,pag.monto,DATE_FORMAT(pag.fecha,"%d-%m-%Y") fecha,tpg.tipo_pago,CONCAT(epl.nombre," ",epl.apellido_paterno," ",epl.apellido_materno) empleado',FALSE)
                      ->from("pagos pag")
                      ->join("tipo_pagos tpg","tpg.id = pag.tipo_pago_id")
                      ->join("empleados epl","epl.id =  pag.empleado_id_insert")
                      ->where("pag.estado", ST_ACTIVO)
                      ->get()
                      ->result();
                return $res;
                //var_dump($res);exit;
      }else{
          $res = $this->db->select('pag.id,pag.comprobante_id,pag.monto,DATE_FORMAT(pag.fecha,"%d-%m-%Y") fecha',FALSE)
                   ->from("pagos pag")                   
                   ->where("id",$pagos)
                   ->where("estado", ST_ACTIVO)
                   ->get()
                   ->row();
                 return $res;
      }
 	}


 	public function guardar(){
  $fecha = (new DateTime($_POST['fecha']))->format('Y-m-d H:i:s');  

  if ($_POST['id']=='') {
      $datainsert= ['comprobante_id'=> $_POST['comprobante_id'],
    	              'monto'=> $_POST['monto'],                                     
                    'fecha'=> $fecha,
                    'tipoComprobante'=> $_POST['tipoComprobante'],
                    'tipo_pago_id'=> $_POST['tipo_pago'],
                    'almacen_id' => $this->session->userdata('almacen_id'),
                    'empleado_id_insert' => $this->session->userdata('empleado_id'),
                    'fecha_insert'=> date('Y-m-d h:i:s')
                   ];
      $this->db->insert("pagos",$datainsert);

      } else{


    $datamodificar=[
                    'monto'=>$_POST['monto'],                    
                    'fecha'=>$fecha,
                    'tipoComprobante'=> $_POST['tipoComprobante'],
                    'tipo_pago_id'=> $_POST['tipo_pago']             
                    ];

             $this->db->where("id",$_POST['id']);
             $this->db->update("pagos",$datamodificar);          
      }
      return TRUE;
 	}


    //SELECT COMPROBANTES COMPRAS CRÉDITO
    public function selectComprobantesCredito_ct() {     
        $cliente_id = isset($_POST['cliente_id']) ? $_POST['cliente_id'] : $_GET['cliente_id'];
        $tipo_pago_id  = isset($_POST['tipo_pago_id']) ? $_POST['tipo_pago_id'] : $_GET['tipo_pago_id'];        
        $vendedor      = isset($_POST['vendedor']) ? $_POST['vendedor'] : $_GET['vendedor'];
        $fecha_inicial  = isset($_POST['fecha_inicial']) ? $_POST['fecha_inicial'] : $_GET['fecha_inicial'];
        $fecha_final   = isset($_POST['fecha_final']) ? $_POST['fecha_final'] : $_GET['fecha_final'];


        if($fecha_inicial != ''){
            $fecha_inicial =  (new DateTime($fecha_inicial))->format('Y-m-d');
            $this->db->where('DATE(com.fecha_de_emision) >=', $fecha_inicial);
        }
        if($fecha_final != ''){
            $fecha_final =  (new DateTime($fecha_final))->format('Y-m-d');
            $this->db->where('DATE(com.fecha_de_emision) <=', $fecha_final);
        }
        

        if($cliente_id != ''){            
           $this->db->where('com.cliente_id', $cliente_id);
        }
        if($tipo_pago_id != ''){            
           $this->db->where('cmp.tipo_pago_id', $tipo_pago_id);// 2 COMPROBANTES AL CRÉDITO - ALEXANDER FERNANDEZ 26-10-2020
        }        
        if($vendedor != ''){
            $this->db->where('com.empleado_select',$vendedor);
        }      
        
        //FILTRO ALMACEN 19-10-2020
        $this->db->where('com.venta_almacen_id',$this->session->userdata('almacen_id'));        
        
        $rsComprobantes =  $this->db->select('com.id comprobante_id,1 as tipoComprobante,com.serie serie,concat_ws("-", com.serie, com.numero) numser,epl.id empleado_id,CONCAT(epl.nombre," ",epl.apellido_paterno) as empleado,cli.prov_id cliente_id,cli.prov_razon_social cli_razon_social,tip.tipo_documento tipo_documento,total_a_pagar,cmp.monto total_credito,DATE_FORMAT(com.fecha_de_emision,
          "%d-%m-%Y") fecha_de_emision,DATE_FORMAT(com.fecha_de_vencimiento,"%d-%m-%Y") fecha_de_vencimiento,mon.moneda moneda, mon.simbolo simbolo,tpg.tipo_pago tipo_pago',FALSE)
                                    ->from('comprobante_compras_pagos cmp')
                                    ->join('tipo_pagos tpg','tpg.id = cmp.tipo_pago_id')
                                    ->join('comprobantes_compras com','com.id = cmp.comprobante_id')
                                    ->join('empleados epl','com.empleado_select = epl.id')                                    
                                    ->join("proveedores cli", "com.cliente_id = cli.prov_id") 
                                    ->join('tipo_documentos tip','com.tipo_documento_id = tip.id')
                                    ->join('empresas emp','com.empresa_id = emp.id')
                                    ->join('monedas mon','com.moneda_id = mon.id')                                    
                                    ->where('com.anulado',0)//NO ANULADO
									                  ->order_by('com.id DESC')
                                    ->get()
                                    ->result();



        foreach ($rsComprobantes as $comprobante) {
            $totalCancelado = ($this->totalCanceladoComprobante($comprobante->comprobante_id) != '') ? $this->totalCanceladoComprobante($comprobante->comprobante_id): 0;
            $saldo = $comprobante->total_credito - $totalCancelado;            
            $comprobante->saldo = number_format($saldo,2);
        }        

        //var_dump($rsComprobantes);exit;
        return  $rsComprobantes;                                              
 }

 public function selectComprobantesCredito_nv(){
        $cliente_id  = isset($_POST['cliente_id']) ? $_POST['cliente_id'] : $_GET['cliente_id'];
        $tipo_pago_id  = isset($_POST['tipo_pago_id']) ? $_POST['tipo_pago_id'] : $_GET['tipo_pago_id'];
        $vendedor      = isset($_POST['vendedor']) ? $_POST['vendedor'] : $_GET['vendedor'];     
        $fecha_inicial = isset($_POST['fecha_inicial']) ? $_POST['fecha_inicial'] : $_GET['fecha_inicial'];
        $fecha_final   = isset($_POST['fecha_final']) ? $_POST['fecha_final'] : $_GET['fecha_final'];  


         if($fecha_inicial != ''){
            $fecha_inicial =  (new DateTime($fecha_inicial))->format('Y-m-d');
            $this->db->where("DATE(npe.notap_fecha) >=", $fecha_inicial);
        }
        if($fecha_final != ''){
            $fecha_final =  (new DateTime($fecha_final))->format('Y-m-d');
            $this->db->where("DATE(npe.notap_fecha) <=", $fecha_final);
        }


        if($cliente_id != ''){            
           $this->db->where('npe.notap_cliente_id', $cliente_id);
        }
        if($tipo_pago_id != ''){            
           $this->db->where('cmp.tipo_pago_id', $tipo_pago_id);
        }
        if($vendedor != ''){            
            $this->db->where('npe.notap_vendedor',$vendedor);
        }                 

        //FILTRO ALMACEN 19-10-2020
        $this->db->where('npe.notap_almacen',$this->session->userdata('almacen_id'));          

        $rsNotas =  $this->db->select('npe.notap_id comprobante_id,2 as tipoComprobante,"NP01" serie,CONCAT("NP-",npe.notap_correlativo) numser,epl.id empleado_id,CONCAT(epl.nombre," ",epl.apellido_paterno) as empleado,cli.id cliente_id,cli.razon_social cli_razon_social,"NOTA VENTA" tipo_documento,npe.notap_total total_a_pagar,cmp.monto total_credito,DATE_FORMAT(npe.notap_fecha, "%d-%m-%Y") fecha_de_emision,DATE_FORMAT(npe.notap_fecha, "%d-%m-%Y") fecha_de_vencimiento,mon.moneda moneda, mon.simbolo simbolo,tpg.tipo_pago tipo_pago',FALSE)
                                    ->from('nota_pagos cmp')
                                    ->join('tipo_pagos tpg','tpg.id = cmp.tipo_pago_id')
                                    ->join('nota_pedido npe','npe.notap_id = cmp.nota_id')
                                    ->join('empleados epl','npe.notap_empleado_insert = epl.id')
                                    ->join('clientes cli','npe.notap_cliente_id = cli.id')                                    
                                    ->join('monedas mon','npe.notap_moneda_id = mon.id')
                                    ->where('npe.notap_estado',1)//NO ANULADO
                                    ->order_by('npe.notap_id DESC')
                                    ->get()
                                    ->result();


        //var_dump($rsNotas);exit();
        foreach ($rsNotas as $comprobante) {

            $totalCancelado = ($this->totalCanceladoNotaPedido($comprobante->comprobante_id) != '') ? $this->totalCanceladoNotaPedido($comprobante->comprobante_id): 0;
            $saldo = $comprobante->total_credito - $totalCancelado;            
            $comprobante->saldo = number_format($saldo,2);
        }
        
        return  $rsNotas;
 }

 public function totalCanceladoComprobante($comprobante_id){

    $rsSaldo = $this->db->select('SUM(monto) totalCancelado')
                        ->from('pagos')
                        ->where('comprobante_id',$comprobante_id)
                        ->where('tipoComprobante',ST_COMPROBANTE_TRIBUTARIO)
                        ->where("estado", ST_ACTIVO)
                        ->group_by('comprobante_id')                        
                        ->get()  
                        ->row();
                        //var_dump($rsSaldo);    
      return $rsSaldo->totalCancelado;
 }


 public function totalCanceladoNotaPedido($comprobante_id){

    $rsSaldo = $this->db->select('SUM(monto) totalCancelado')
                        ->from('pagos')
                        ->where('comprobante_id',$comprobante_id)
                        ->where('tipoComprobante',ST_COMPROBANTE_NOTRIBUTARIO)
                        ->where("estado", ST_ACTIVO)
                        ->group_by('comprobante_id')                        
                        ->get()  
                        ->row();
                        //var_dump($rsSaldo);    
                        //echo $rsSaldo->totalCancelado;
      return $rsSaldo->totalCancelado;
 }

 public function eliminar($idCobro){

   $cobroUpdate = [
                          "estado" => ST_ELIMINADO
                        ];

      $this->db->where("id", $idCobro);
      $this->db->update("pagos", $cobroUpdate);
      return true;
 }
}