<?php
//var_dump($cliente);exit;
?>
<html>
    <head>
        <style>
            html, body {
                margin: 0 2px;
                padding: 0;
                font-family: sans-serif;
            }
            span #height-container { position: absolute; left: 0px; right: 0px; top: 0px; }
            .datos_titulo1{
                font-size: 5px;
                text-align: center;
                line-height: 1em;                
            }            

            .tabla_cabecera{
                font-size: 5px;                
            }
            .tabla_datos{
                font-size: 4px;
                text-align: center;
            }
            .tabla_datos_cantidad{
                font-size: 5px;
                text-align: center;
            }
            .datos_totales{
                font-size: 5px;                                
            }
            .datos_totales_bold{
                font-size: 6px;
                font-weight: bold;                  
            }
            .datos_cabecera{
                font-size: 4.3px;                
                text-align: center;       
                line-height: 1em;         
            }            
            .datos_cabecera_bold{
                font-weight: bold;
            }
            .datos_cliente{
                text-align: left;   
                margin-left: 4px;             
                font-size: 4.5px;                
                line-height: 1em;                
            }

        </style>
        <title>Nota de Venta</title>
    </head>
    <body>
<?php 
    $ruta_foto = base_url()."images/".$empresa->foto;
 ?>
        
        <img src="<?php echo "images/".$empresa->foto;?>" height="80" width="100%" style="text-align:center;" border="0">
        <span id="height-container">
            <p class ="datos_titulo1 cabecera">
                <span class="datos_cabecera_bold"><?php echo $empresa->empresa?></span><br>
                <span class="datos_cabecera_bold">RUC : <?php echo $empresa->ruc?></span><br>
                <span class="datos_cabecera"><?php echo $empresa->domicilio_fiscal?></span>
                --------------------------------------------------------------
                <b><?php echo "Nota de Venta"; ?>&nbsp;&nbsp;<?php echo "NP-".str_pad($nota->notap_correlativo, 8, "0", STR_PAD_LEFT)?></b><br>
                Fecha/hora emision: <?php echo $nota->notap_fecha; ?><br>
                Vendedor : <?php echo $this->session->userdata('usuario'). " ". $this->session->userdata('apellido_paterno'); ?><br>
                Transportista : <?php echo $nota->transp_nombre; ?><br>
                --------------------------------------------------------------<br></p>
            <p class="datos_cliente"> CLIENTE<br>
                <?php echo $cliente->razon_social?> <?php echo $comprobante->nombres?></br><?php if($nota->tipo_cliente_id==1):?><?php else:?><?php endif?><br><?php echo "  ". $cliente->ruc?><br>
                DIRECCION:<?php echo "  ". $cliente->domicilio1;?><br>
                ----------------------------------------------------------------------<br>                
            </p>            
            <table width="100%">
                <thead>
                    <tr>
                        <th width="8" align="center" class="tabla_cabecera">CANT.</th>
                        <th width="35" class="tabla_cabecera">PRODUCTO</th>
                        <th width="10" align="center" class="tabla_cabecera">P/U</th>
                        <th width="10" align="center" class="tabla_cabecera">TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($nota->detalles as $item){
                            $total = ($nota->incluye_igv==1) ? $item->notapd_total : $item->notapd_total ;?>
                    <tr>
                        <td class="tabla_datos_cantidad"><?php echo $item->notapd_cantidad?></td>
                        <td class="tabla_cabecera"><?php echo $item->notapd_descripcion?></td>
                        <td class="tabla_datos_cantidad"><?php echo $item->notapd_precio_unitario?></td>
                        <td class="tabla_datos_cantidad"><?php echo $total?></td>
                    </tr>
                    <?php                     
                    }
                    ?>
                </tbody>
            </table>
            <p align="center" class ="datos_titulo1">
            ---------------------------------------------------------------<br>
            </p>
            <table>
<!--                <tr>
                    <td class="datos_totales">Op. Gravadas:</td>
                    <td class="datos_totales"><?php //echo $nota->simbolo?> <?php //echo $nota->notap_subtotal?></td>
                </tr>
                <tr>
                    <td class="datos_totales">IGV (18%):</td>
                    <td class="datos_totales"><?php //echo $nota->simbolo." ".$nota->notap_igv?></td>
                </tr>-->
                <tr>
                    <td class="datos_totales_bold">IMPORTE TOTAL :</td>                    
                    <td class="datos_totales_bold"><?php echo $nota->simbolo." ".$nota->notap_total?></td>                   
                </tr>
                <tr>
                     <td class="datos_totales">TIPO PAGO :</td>
                    <td class="datos_totales"><?php echo $nota->tipo_pago?></td>
                </tr>
                <?PHP if($nota->placa != NULL){?>                
                <tr>
                     <td class="datos_totales">PLACA :</td>
                    <td class="datos_totales"><?php echo $nota->placa?></td>
                </tr>
                <?PHP }?>
                <tr>
                    <td class="datos_totales">OBSERVACIONES :</td>
                    <td class="datos_totales"><?php echo $nota->notap_observaciones?></td>                    
            </table>
        </span><br>
        <div align="center" style="width: 90%" class="datos_totales" >
            Válido sólo para entrega  de productos pedir su boleta o factura
        </div>
    </body>
</html>