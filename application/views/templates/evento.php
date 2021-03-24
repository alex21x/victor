<html>
    <head>
        <style>
            html, body {
                margin: 10 20px;
                padding: 0;
                font-family: sans-serif;
            }
            span #height-container { position: absolute; left: 0px; right: 0px; top: 0px; }
            .datos_titulo1{
                font-size: 11px;
            }
            .tabla_cabecera{
                font-size: 11px;
            }
            .tabla_datos{
                font-size: 10px;
                text-align: right;
            }
            .datos_totales{
                font-size: 12px;                
            }

            .datos_titulo1{
                font-size: 14px;                
                font-weight: bold;
                background-color: #4169E1;   
                padding: 1px; 
                color: #FFF;            
                text-align: left;
                line-height: 1.1em;                
            }
            .cabecera{
                text-align: center;
                font-size: 12px;
            }     
            img{
                width: 240px;
                height: 150px;   
                margin-left: 15px;
            }
        </style>
        <title>Evento</title>
    </head>
    <body>
<?php 
    $ruta_foto = base_url()."images/".$empresa->foto;
 ?>
        
        <table class="cabecera">
            <tr>
                <td><img src="<?php echo "images/".$empresa->foto;?>" height="80" width="50%" style="text-align:center;" border="0"></td>            
                <td>
                <?php echo $empresa->ruc;?><br>            
                <?php echo $empresa->empresa;?><br>
                <?php echo $empresa->domicilio_fiscal;?><br>            
                <?php echo $empresa->telefono_movil;?>
                </td>

            </tr>            
        </table>
        <br><br>
        EVENTO <?= str_pad($evento->evento_id, 8, "0", STR_PAD_LEFT)?>
        <br><br>        
            <table width="100%">
                <tr class="datos_titulo1">
                    <td class="datos_totales">N°</td>
                    <td class="datos_totales">T.EVENTO </td>
                    <td class="datos_totales">FECHA_EVENTO </td>
                    <td class="datos_totales">CLIENTE </td>
                    <td class="datos_totales">HORA_INGRESO </td>
                    <td class="datos_totales">HORA_SALIDA </td>
                    <td class="datos_totales">TOTAL HORAS </td>
                    <td class="datos_totales">TURNO </td>                    
                    <td class="datos_totales">RESPONSABLE </td>   
                    <td class="datos_totales">PLACA </td>   
                    <td class="datos_totales">N DOCUMENTO </td>   
                    <td class="datos_totales">N GUIA </td>
                    <td class="datos_totales">OTROS </td>               
                    <td class="datos_totales">USUARIO </td>   
                </tr>        
                <tr>
                    <td class="datos_totales"><?php echo $evento->evento_id?></td>
                    <td class="datos_totales"><?php echo $evento->tipo_evento?></td>
                    <td class="datos_totales"><?php echo $evento->fecha_evento?></td>
                    <td class="datos_totales"><?php echo $evento->cli_razon_social?></td>
                    <td class="datos_totales"><?php echo $evento->ingreso?></td>
                    <td class="datos_totales"><?php echo $evento->salida?></td>
                    <td class="datos_totales"><?php echo $evento->totalHoras?></td>
                    <td class="datos_totales"><?php echo $evento->turno?></td>
                    <td class="datos_totales"><?php echo $evento->responsable?></td>
                    <td class="datos_totales"><?php echo $evento->placa?></td>
                    <td class="datos_totales"><?php echo $evento->num_documento?></td>
                    <td class="datos_totales"><?php echo $evento->num_guia?></td>
                    <td class="datos_totales"><?php echo $evento->otros?></td>
                    <td class="datos_totales"><?php echo $evento->empleado?></td>
                </tr>                                
            </table><br><br><br>        
            <table width="100%" style="text-align: center">
                <tr>
                    <?PHP $i = 1; foreach($evento->detalles as $image){?>
                        <td><img src="<?php echo "images/eventos/".$image->evento_imagen;?>"></td>
                        <?PHP if($i%2 == 0) echo '</tr><tr>';$i++;
                         }?>
                </tr>
            </table>       
        </span>       
        <div align="center" class="datos_totales">
            <h3><center><?= $this->session->userdata('empresa_pie_pagina')?></center></h3>
        </div>
    </body>
</html>