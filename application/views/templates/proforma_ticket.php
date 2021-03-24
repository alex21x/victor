<?php
//var_dump($cliente);exit;
?>
<html>
    <head>
        <style>
            html, body {
                margin: 0;
                padding: 0;
                font-family: sans-serif;
            }
            span #height-container { position: absolute; left: 0px; right: 0px; top: 0px; }
            .datos_titulo1{
                font-size: 5px;
            }
            .tabla_cabecera{
                font-size: 5px;
            }
            .tabla_datos{
                font-size: 4px;
                text-align: right;
            }
            .datos_totales{
                font-size: 6px;
                font-weight: bold;
            }
        </style>
        <title>Proforma</title>
    </head>
    <body>
<?php 
    $ruta_foto = base_url()."images/".$empresa->foto;
 ?>
        
        <img src="<?php echo "images/".$empresa->foto;?>" height="80" width="100%" style="text-align:center;" border="0">
        <span id="height-container">
            <p align="center" class ="datos_titulo1">
                <?php echo $empresa->empresa?><br><br>
                RUC : <?php echo $empresa->ruc?><br><br>
                <?php echo $empresa->domicilio_fiscal?><br>
                -------------------------------------------------------<br><br>
                <?php echo "Proforma"; ?>&nbsp;&nbsp;<?php echo " ".str_pad($proforma->prof_correlativo, 8, "0", STR_PAD_LEFT)?><br>
                Fecha/hora emision: <?php echo $proforma->prof_doc_fecha; ?><br>
                Vendedor : <?php echo $this->session->userdata('usuario'). " ". $this->session->userdata('apellido_paterno'); ?><br>
                -------------------------------------------------------<br><br>
                Cliente: <?php echo $cliente->razon_social?> <?php echo $comprobante->nombres?></br><?php if($nota->tipo_cliente_id==1):?>D.N.I: <?php else:?> R.U.C <?php endif?><?php echo "  ". $cliente->ruc?><br><br>
                DIRECCION:<?php echo "  ". $proforma->prof_direccion?><br>
                -------------------------------------------------------<br>                
            </p>            
            <table width="100%">
                <thead>
                    <tr>
                        <th width="8" align="center" class="tabla_cabecera">Cant.</th>
                        <th class="tabla_cabecera">Producto</th>
                        <th width="13" align="center" class="tabla_cabecera">Precio</th>
                        <th width="13" align="center" class="tabla_cabecera">Importe</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($proforma->detalles as $item){
                            $total = ($nota->incluye_igv==1) ? $item->profd_subtotal : $item->profd_subtotal ;?>
                    <tr>
                        <td class="tabla_datos"><?php echo $item->profd_cantidad?></td>
                        <td class="tabla_cabecera"><?php echo $item->profd_descripcion?></td>
                        <td class="tabla_datos"><?php echo $proforma->simbolo?> <?php echo $item->profd_precio_unitario?></td>
                        <td class="tabla_datos"><?php echo $proforma->simbolo." ".$total?></td>
                    </tr>
                    <?php                     
                    }
                    ?>
                </tbody>
            </table>
            <p align="center" class ="datos_titulo1">
            -------------------------------------------------------<br>
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
                    <td class="datos_totales">IMPORTE TOTAL:</td>
                    <td class="datos_totales"><?php echo $proforma->simbolo." ".$proforma->prof_doc_total?></td>
                </tr>
                <tr>
                    <td class="datos_totales">OBSERVACIONES :</td>
                    <td class="datos_totales"><?php echo $proforma->prof_doc_observacion?></td>   
            </table>
        </span><br>
        <div align="center" style="width: 90%" class="datos_totales" >
            Proforma válido 5 días previa verificación de stock
        </div>
        <div align="center" class="datos_totales">
            <h3><center><?= $this->session->userdata('empresa_pie_pagina')?></center></h3>
        </div>
    </body>
</html>