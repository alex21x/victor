<?php
  class Cajas_model extends CI_Model{
  
  public function __construct()
  {
    parent::__construct();
    date_default_timezone_set('America/Lima');
  }

  public function select($cajas_id = '') {


    $fechaApertura = isset($_POST['fechaApertura']) ? $_POST['fechaApertura'] : $_GET['fechaApertura'];
    $fechaCierre = isset($_POST['fechaCierre']) ? $_POST['fechaCierre'] : $_GET['fechaCierre']; 
    $vendedor    = isset($_POST['vendedor']) ? $_POST['vendedor'] : $_GET['vendedor'];


    if($fechaApertura != ''){      
        $fechaApertura =  (new DateTime($fechaApertura))->format('Y-m-d');        
        $this->db->where("DATE(caj.fecha) >=", $fechaApertura);
    }
    if($fechaCierre != ''){      
      $fechaCierre =  (new DateTime($fechaCierre))->format('Y-m-d');
      $this->db->where("DATE(caj.fechaCierre) <=", $fechaCierre);        
    }
    if($vendedor != ''){            
            $this->db->where('caj.empleado_id',$vendedor);
        }
    if($this->session->userdata('accesoEmpleado') != ''){
            $this->db->where('caj.empleado_id',$this->session->userdata('empleado_id'));
    }  


	if($cajas_id == ''){
     $rsjairo = $this->db->select('caj.id,saldo_inicial,saldo_final,DATE_FORMAT(caj.fecha,"%d-%m %h:%i") fecha,tct.tipo_cTransaccion,DATE_FORMAT(caj.fechaCierre,"%d-%m %h:%i") fechaCierre,tct.tipo_cTransaccion, totalContado, totalDeposito,totalCheque,totalCheque,totalTarjeta,totalCupon,totalCredito,cobroTotalContado,cobroTotalDeposito,cobroTotalCheque,cobroTotalTarjeta,cobroTotalCupon,totalCobro,totalVenta,movCajIngreso,movCajSalida,CONCAT(epl.nombre," ",epl.apellido_paterno) empleado',false)
                        ->from("cajas caj")
                        ->join("tipo_ctransaccion tct","caj.tipo_transaccion_id = tct.id")
                        ->join('empleados epl','caj.empleado_id = epl.id')
                        ->order_by('caj.id','desc')
                        ->get()
                        ->result();

                        return $rsjairo;
	}
	else{
       $rsjairo=$this->db->select('caj.id,saldo_inicial,saldo_final,fecha,DATE_FORMAT(caj.fecha,"%d-%m %h:%i") fechaApertura,DATE_FORMAT(caj.fechaCierre,"%d-%m %h:%i") fechaCierre,totalContado,totalDeposito,totalCheque,totalTarjeta,totalCupon,totalCredito,cobroTotalContado,cobroTotalDeposito,cobroTotalCheque,cobroTotalTarjeta,cobroTotalCupon,totalCobro,totalVenta,movCajIngreso,movCajSalida,CONCAT(epl.nombre," ",epl.apellido_paterno) empleado',false)
                               ->from("cajas caj") 
                               ->join("tipo_ctransaccion tct","caj.tipo_transaccion_id = tct.id")
                               ->join('empleados epl','caj.empleado_id = epl.id')                              
                               ->where("caj.id",$cajas_id)
                               ->get()
                               ->row();
                          return $rsjairo;
	}
}

public function guardarApertura(){  

    //echo $this->session->userdata('empleado_id');exit;
    $data = array('saldo_inicial'=>$_POST["saldo_inicial"],
                  'tipo_transaccion_id' => 1,
                  'empleado_id' => $this->session->userdata('empleado_id'),
                  'almacen_id'=> $this->session->userdata('almacen_id'),
                  'fecha' => date('Y-m-d H:i:s')
                );

    $this->db->insert("cajas",$data);
    $caja_id = $this->db->insert_id();

    return $caja_id;    
  }


  public function guardarCierre(){

    $data = array('totalContado' => $_POST['totalContado'],
                  'totalDeposito' => $_POST['totalDeposito'],
                  'totalCheque'   => $_POST['totalCheque'],
                  'totalTarjeta'   => $_POST['totalTarjeta'],
                  'totalCupon'     => $_POST['totalCupon'],
                  'totalCredito'   => $_POST['totalCredito'],
                  'cobroTotalContado'   => $_POST['cobroTotalContado'],
                  'cobroTotalDeposito'   => $_POST['cobroTotalDeposito'],
                  'cobroTotalCheque'   => $_POST['cobroTotalCheque'],
                  'cobroTotalTarjeta'   => $_POST['cobroTotalTarjeta'],
                  'cobroTotalCupon'   => $_POST['cobroTotalCupon'],
                  'saldo_final'   => $_POST['montoTotalEfectivo'],
                  'totalVenta'    => $_POST['montoTotal'],
                  'totalCobro'    => $_POST['montoTotalCobro'],
                  'movCajIngreso' => $_POST['movCajIngreso'],
                  'movCajSalida'  => $_POST['movCajSalida'],
                  'fechaCierre'   => date('Y-m-d h:i:s'),                  
                  'tipo_transaccion_id' => 2
                );    

    $this->db->where('id', $_POST['caja_id']);
    $this->db->update('cajas', $data);

    return TRUE;
  }


  public function ultimoRegCaja(){

        $this->db->limit(1);
        $this->db->where('almacen_id',$this->session->userdata('almacen_id'));
        $this->db->order_by('id','desc');
        $rsRow = $this->db->get('cajas')                          
                          ->row();
        return $rsRow;
  }


  //ALEXANDER FERNANDEZ 21/07/2020 - REPORTE PARA CIERRE DE CAJA
    public function selecReporteCaja_ct($fecha = ''){//COMPROBANTES TRIBUTARIOS

        $rsComprobante = $this->db->select("sum(cmp.monto) montoTotal,tpg.tipo_pago tipo_pago")
                                    ->from('comprobantes com')
                                    ->join('comprobante_pagos cmp','cmp.comprobante_id = com.id')
                                    ->join('tipo_pagos tpg','cmp.tipo_pago_id =  tpg.id')
                                    ->where('com.fecha_de_emision >',$fecha)
                                    ->where('com.fecha_de_emision <',date('Y-m-d H:i:s'))
                                    ->where('venta_almacen_id',$this->session->userdata('almacen_id'))
                                    ->where('com.anulado',0)
                                    ->where('com.eliminado',0)
                                    ->group_by('cmp.tipo_pago_id')
                                    ->get()
                                    ->result_array();
        //var_dump($rsComprobante);exit;
        return $rsComprobante;      
    }


    public function selecReporteCaja_np($fechaApertura = ''){//NOTAS DE PEDIDO        
        $rsComprobante = $this->db->select("SUM(cmp.monto) montoTotal,tpg.tipo_pago tipo_pago")
                                  ->from('nota_pedido npe')  
                                  ->join('nota_pagos cmp','cmp.nota_id = npe.notap_id')
                                  ->join('tipo_pagos tpg','cmp.tipo_pago_id =  tpg.id')
                                  ->where('npe.notap_fecha >' , $fechaApertura)
                                  ->where('npe.notap_fecha <',date('Y-m-d H:i:s'))
                                  ->where('notap_almacen',$this->session->userdata('almacen_id'))
                                  ->where('npe.notap_estado',1)
                                  ->group_by('cmp.tipo_pago_id')
                                  ->get()
                                  ->result_array();        

        return $rsComprobante;                                  
    }

    public function selectReporteCobros($fechaApertura = ''){
        $rsCobro =  $this->db->select('SUM(cob.monto) montoTotal,tpg.tipo_pago tipo_pago')
                              ->from('cobros cob')
                              ->join('tipo_pagos tpg','cob.tipo_pago_id =  tpg.id')
                              ->where('cob.fecha >',$fechaApertura)
                              ->where('cob.fecha <',date('Y-m-d H:i:s'))
                              ->where('cob.estado',ST_ACTIVO)
                              ->where('cob.almacen_id',$this->session->userdata('almacen_id'))
                              ->group_by('cob.tipo_pago_id')
                              ->get()
                              ->result_array();
                              
      return $rsCobro;
    }



    //CAMBIO 10-02-2021 TRIBUTARIOS - ALEXANDER FERNANDEZ
    public function selectCambio_ct($fechaApertura = ''){
        $rsCambio = $this->db->select('SUM(com.cambio) totalCambio')
                             ->from("comprobantes com")
                             ->where('com.fecha_de_emision >',$fechaApertura)
                             ->where('com.fecha_de_emision <',date('Y-m-d H:i:s'))
                             ->where('venta_almacen_id',$this->session->userdata('almacen_id'))
                             ->where('com.anulado',0)
                             ->where('com.eliminado',0)                             
                             ->get()
                             ->row();
                             //var_dump($rsCambio->totalCambio);exit();
        return $rsCambio->totalCambio;
    }

    //CAMBIO 10-02-2021 NO TRIBUTARIOS - ALEXANDER FERNANDEZ
    public function selectCambio_np($fechaApertura = ''){
        $rsCambio = $this->db->select('SUM(npe.notap_cambio) totalCambio')
                             ->from("nota_pedido npe")
                             ->where('npe.notap_fecha >' , $fechaApertura)
                             ->where('npe.notap_fecha <',date('Y-m-d H:i:s'))
                             ->where('notap_almacen',$this->session->userdata('almacen_id'))
                             ->where('npe.notap_estado',1)
                             ->get()
                             ->row();

        return $rsCambio->totalCambio;                             
    }
}