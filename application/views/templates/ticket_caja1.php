
<?php

?>
<style>

    html, body {
                margin: 0;
                padding: 0;
                font-family: sans-serif;
            }

span #height-container { position: absolute; left: 0px; right: 0px; top: 0px; }
            .datos_titulo1{
                font-size: 5px;}


</style> 

<html>             
              <span id="height-container">
                
               <p align="center" class ="datos_titulo1">
                         REPORTE DE CAJA<br>
          --------------------------------------------------------------------<br>            
            Fecha Apertura : <?php echo $cajasticket->fecha;?><br><br>
            Fecha Cierre : <?php echo $cajasticket->fechaCierre;?><br><br>
            Saldo Inicial : <?php echo $cajasticket->saldo_inicial;?><br><br>
            Saldo Final : <?php echo $cajasticket->saldo_final;?><br><br>            
            Total Contado : <?php echo $cajasticket->totalContado;?><br><br>
            Total Deposito : <?php echo $cajasticket->totalDeposito;?><br><br>
            Total Cheque : <?php echo $cajasticket->totalCheque;?><br><br>
            Total Tarjeta : <?php echo $cajasticket->totalTarjeta;?><br><br>
            Total Crédito : <?php echo $cajasticket->totalCredito;?><br><br>
            TOTAL VENTA : <?php echo $cajasticket->totalVenta;?><br><br>            
        -------------------------------------------------------------------------------                                              
            </p>     
             </span>       
</html>