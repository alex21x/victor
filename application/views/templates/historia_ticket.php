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



            img.historia{        

                width: 70px;

                height: 70px;   

                margin-left: 15px;              

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

        <title>Historia</title>

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

                <span class="datos_cabecera"><?php echo $empresa->domicilio_fiscal?></span><br>

                <?php

                if($almacen_principal->ver_direccion_comprobante == 1){?>

                <span class="datos_cabecera_bold">Dirección almacén: <?php echo $almacen_principal->alm_direccion?></span><br>

                <?php }?>

                <b><?php echo "Receta Médica"; ?>&nbsp;&nbsp;<?php echo "RE-".str_pad($historia->his_correlativo, 8, "0", STR_PAD_LEFT)?></b><br>

                --------------------------------------------------------------

                <!--<b><?php echo "Nota de Venta"; ?>&nbsp;&nbsp;<?php echo "NP-".str_pad($historia->notap_correlativo, 8, "0", STR_PAD_LEFT)?></b><br>-->

                Fecha/hora emision: <?php echo $historia->his_fecha; ?><br>                

                Usuario : <?php echo $this->session->userdata('usuario'). " ". $this->session->userdata('apellido_paterno'); ?><br>



                Profesional : <?php echo $historia->prof_nombre; ?><br>

                Especialidad : <?php echo $historia->esp_descripcion; ?><br>

                --------------------------------------------------------------<br></p>

            <p class="datos_cliente">

                Paciente: <?php echo "  ". $paciente->razon_social;?><br>

                DNI: <?php echo "  ". $paciente->ruc." EDAD: ".$paciente->edad;?><br>

                ----------------------------------------------------------------------<br>                

            </p>         

            <p class="datos_cliente">

                Peso: <?php echo "  ". $historia->his_ini_peso." Talla ".$historia->his_ini_talla." Presion ".$historia->his_ini_presion_arterial;?><br>

                Temperatura: <?php echo "  ". $historia->his_ini_temperatura;?><br>

                Otros: <?php echo "  ". $historia->his_ini_otros;?><br>

                ----------------------------------------------------------------------<br>                

            </p>       



            <table width="100%">

                <thead>

                    <tr>

                        <th width="8" align="center" class="tabla_cabecera">CANT.</th>

                        <th width="35" class="tabla_cabecera">PRODUCTO</th>

                        <th width="10" align="center" class="tabla_cabecera">DOSCIFICACIÓN</th>                        

                    </tr>

                </thead>

                <tbody>

                    <?php foreach($historia->detalles as $item){

                            //$total = ($historia->incluye_igv==1) ? $item->notapd_total : $item->notapd_total ;?>

                    <tr>

                        <td class="tabla_datos_cantidad"><?php echo $item->hid_cantidad?></td>

                        <td class="tabla_cabecera"><?php echo $item->hid_descripcion?></td>

                        <td class="tabla_datos_cantidad"><?php echo $item->hid_dosificacion?></td>                        

                    </tr>

                    <?php }?>                    

                </tbody>

            </table>

            <p align="center" class ="datos_titulo1">

            ---------------------------------------------------------------<br>

            </p>

            <table>                                                        
                
                <tr>

                    <td class="datos_totales">CODIGO CIE</td>

                    <td class="datos_totales"><?php echo $historia->his_codigoCEI_descripcion?></td>

                </tr>


                <tr>                    

                    <td class="datos_totales">ENFERMEDAD ACTUAL :</td>

                    <td class="datos_totales"><?php echo $historia->his_enfermedad_actual?></td>

                </tr>

                <tr>                    

                    <td class="datos_totales">MOTIVO :</td>

                    <td class="datos_totales"><?php echo $historia->his_motivo?></td>

                </tr>

                <tr>

                     <td class="datos_totales">DIAGNOSTICO :</td>

                    <td class="datos_totales"><?php echo $historia->his_diagnostico?></td>

                </tr>

                <tr>

                     <td class="datos_totales">EXÁMEN FÍSICO :</td>

                    <td class="datos_totales"><?php echo $historia->his_tratamiento?></td>

                </tr>

                <tr>

                     <td class="datos_totales">OTROS EXÁMENES Y RECOMENDACIÓN</td>

                     <td class="datos_totales"><?php echo $historia->his_recomendacion?></td>

                </tr>

                <tr>

                     <td class="datos_totales">PRÓXIMA CITA :</td>

                    <td class="datos_totales"><?php echo $historia->his_fecha_cita?></td>

                </tr> 

                

                <tr>

                    <td colspan="2">                        

                        <div id="images_gallery">

                          <img class="historia" src="<?php echo 'images/profesional/firma/'.$historia->prof_firma;?>">

                      </div>

                    </td>

                </tr>                  

            </table>

        </span><br>        

    </body>

</html>