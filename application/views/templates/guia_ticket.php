<?php
//var_dump($cliente);exit;
?>
<html>
    <head>
        <style>
            html, body {
                margin: 2px;
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
        <title>GUIA DE REMISION</title>
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
                <?php echo "Guia"; ?>&nbsp;&nbsp;<?php echo $guia->guia_serie."-".$guia->guia_numero;?><br>
                Fecha/hora emision: <?php echo $guia->fecha_inicio_traslado; ?><br>
                fecha/traslado    : <?php echo $guia->fecha_inicio_traslado; ?><br>
                Vendedor : <?php echo $this->session->userdata('usuario'). " ". $this->session->userdata('apellido_paterno'); ?><br>
                -------------------------------------------------------<br><br>
                Cliente: <?php echo $cliente->razon_social?> <?php echo $comprobante->nombres?></br><?php if($nota->tipo_cliente_id==1):?>D.N.I: <?php else:?><br> R.U.C <?php endif?><?php echo "  ". $cliente->ruc?><br><br>
                Direccion:<?php echo "  ". $cliente->domicilio1?><br>
                -------------------------------------------------------<br>
                                  <strong>DATOS ENVIO</strong>  <br>
                -------------------------------------------------------<br>
                MOTIVO TRASLADO       : <?php echo $guia->descripcion?> <br>
                MODALIDAD TRANSPORTE  : <?php echo $guia->modalidad?> <br>
                 PESO BRUTO TOTAL (KG): <?php echo $guia->peso_total ?><br>
                    N° BULTOS         : <?php echo $guia->numero_bultos?> <br>
                    P. PARTIDA        :  <?php echo $guia->partida_direccion?><br>
                    P. LLEGADA        :  <?php echo $guia->llegada_direccion?><br>
                 ------------------------------------------------------<br> 
                                   <strong>DATOS TRANSPORTE</strong>  <br>   
                 ------------------------------------------------------<br>
                 RAZON SOCIAL         :<?php  echo $guia->transporte_razon_social?><br>
                 PLACA                :<?php echo $guia->vehiculo_placa?><br>
                 MARCA                :<?php echo $guia->vehiculo_marca?><br>
                 RUC                  :<?php echo $guia->conductor_ruc?><br> 
                 LICENCIA             :<?php echo $guia->vehiculo_licencia?><br>      
            </p>                      
            <table width="100%">
                <thead>
                    <tr>
                        <th width="8" align="center" class="tabla_cabecera">Item</th>
                        <th class="tabla_cabecera">Descripcion</th>
                        <th width="13" align="center" class="tabla_cabecera">Unidad</th>
                        <th width="13" align="center" class="tabla_cabecera">Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=1; ?>
                    <?php foreach($guia->detalles as $item){
                            $total = ($nota->incluye_igv==1) ? $item->profd_subtotal : $item->profd_subtotal ;?>
                    <tr>  
                        <td class="tabla_datos"><?php echo $i; ?></td>
                        <td class="tabla_cabecera"><?php echo $item->descripcion?></td>
                        <td class="tabla_datos"><?php echo $item->medida_codigo_unidad?> <?php echo $item->profd_precio_unitario?></td>
                        <td class="tabla_datos"><?php echo $item->cantidad?></td>
                    </tr>
                    <?php  $i++;                   
                    }
                    ?>
                </tbody>
            </table>
            <p align="center" class ="datos_titulo1">
            -------------------------------------------------------<br>
            </p>
           
        </span><br><hr>
        <div align="center" class="datos_totales" >
            <?= $guia->firma_sunat;?>    
        </div><br>
        
        <div align="center" class="datos_totales">
            <h3><center><?= $this->session->userdata('empresa_pie_pagina')?></center></h3>
        </div>        
    </body>
</html>