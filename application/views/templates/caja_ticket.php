<style>
    html, body {
                margin: 0;
                padding: 0;
                font-family: sans-serif;
    }
    .cabecera, h2{
      text-align: center;
      font-size: 5px;
    }
    .detalle{
      text-align: right;
      margin-left: 10px;
      font-size: 5px;
    }        
    
</style> 

<html>        
  
  <table class="cabecera">
  <tr>
    <td><img src="<?PHP echo FCPATH;?>images/<?php echo $empresa['foto'];?>" height="80" width="100%" style="text-align:center;" border="0"></td></tr>
  <tr>
    <td><?php echo $empresa['ruc'];?></td></tr>
  <tr>
    <td><?php echo $empresa['empresa'];?></td></tr>
  <tr>
    <td><?php echo $empresa['domicilio_fiscal'];?></td></tr>
  <tr>
    <td><?php echo $caja->empleado;?></td></tr>
  <tr>
    <td><?php echo $this->session->userdata('almacen_nom');?></td></tr>  
</table>

<h2 class="datos_titulo1">REPORTE DE CAJA</h2>
<table class="detalle">
  <tr>    
    <td>Fecha apertura :</td>
    <td><?=$caja->fechaApertura?></td>
  </tr> 
  <tr>    
    <td>Fecha cierre :</td>
    <td><?=$caja->fechaCierre?></td>
  </tr>
  <tr>    
    <td colspan="2">-------------------</td>    
  </tr>
  <tr>    
    <td>Saldo Inicial :</td>
    <td><?= $caja->saldo_inicial?></td>   
  </tr> 
  <tr>    
    <td colspan="2">-------------------</td>    
  </tr>
  <tr>    
    <td>Total Efectivo :</td>
    <td><?= $caja->totalContado?></td>
  </tr> 
  <tr>    
    <td>Total Desposito :</td>
    <td><?= $caja->totalDeposito?></td>
  </tr> 
  <tr>    
    <td>Total Cheque :</td>
    <td><?= $caja->totalCheque?></td>
  </tr> 
  <tr>    
    <td>Total Tarjeta :</td>
    <td><?= $caja->totalTarjeta?></td>
  </tr> 
  <tr>    
    <td>Total Cupón :</td>
    <td><?= $caja->totalCupon?></td>
  </tr>
     <tr>   
    <td>Total Crédito :</td>
    <td><?= $caja->totalCredito?></td>
  </tr>
  <tr>    
    <td>Total Venta :</td>
    <td><?= $caja->totalVenta?></td>
  </tr>  
  <tr>    
    <td colspan="2">-------------------</td>    
  </tr>
  <tr>    
    <td>COBROS</td>
    <td>&nbsp;</td> 
  </tr>
  <tr>    
    <td>Total Efectivo :</td>
    <td><?= $caja->cobroTotalContado?></td>
  </tr> 
  <tr>    
    <td>Total Desposito :</td>
    <td><?= $caja->cobroTotalDeposito?></td>
  </tr> 
  <tr>    
    <td>Total Cheque :</td>
    <td><?= $caja->cobroTotalCheque?></td>
  </tr> 
  <tr>    
    <td>Total Tarjeta :</td>
    <td><?= $caja->cobroTotalTarjeta?></td>
  </tr> 
  <tr>    
    <td>Total Cupón :</td>
    <td><?= $caja->cobroTotalCupon?></td>
  </tr>

  <tr>    
    <td>Total Cobro :</td>
    <td><?= $caja->totalCobro?></td>
  </tr>  
  <tr>    
    <td colspan="2">-------------------</td>    
  </tr>
  <tr>    
    <td>Total Ingreso :</td>
    <td><?= $caja->movCajIngreso?></td>
  </tr>
  <tr>    
    <td>Total Gastos :</td>
    <td><?= $caja->movCajSalida?></td>
  </tr> 
  <tr>    
    <td>Total Efectivo :</td>
    <td><?= number_format($caja->totalContado + $caja->movCajIngreso - $caja->movCajSalida,2);?></td>
  </tr> 
  <tr>    
    <td colspan="2">-------------------</td>    
  </tr>
  <tr>    
    <td>Saldo Final :</td>
    <td><?= $caja->saldo_final?></td>
  </tr>-->
  
</table>             
</html>