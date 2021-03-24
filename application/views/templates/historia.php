<html>

    <head>

        <style>

            html, body {

                margin: 10px 20px;

                padding: 0;

                font-family: sans-serif;

            }

            span #height-container { position: absolute; left: 0px; right: 0px; top: 0px; }

            .datos_titulo{

                width: 100%;                                    

            }

            .cabecera{

                text-align: center;

                font-size: 9px;

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

            img.historia{        

                width: 70px;

                height: 70px;   

                margin-left: 15px;              

            }           

            .tabla_cabecera{

                font-size: 10px;  

                text-align: center;              

            }

            .tabla_datos{

                font-size: 10px;

                text-align: center;

            }

            .tabla_datos_cantidad{

                font-size: 10px;

                text-align: center;

            }

            .datos_totales{

                font-size: 10px;                                

            }

            .datos_totales_bold{

                font-size: 10px;

                font-weight: bold;                  

            }

            .datos_cabecera{

                font-size: 8.3px;                

                text-align: center;       

                line-height: 1em;         

            }            

            .datos_cabecera_bold{

                font-weight: bold;

            }

            .datos_cliente{

                width: 100%;

                text-align: left;   

                margin-left: 4px;             

                font-size: 10.5px;                

                line-height: 1.8em; 

                border: 1;            

            }



            .datos_imagen{

                width: 100%;

                text-align: center;   

                margin-left: 4px;                            

                border: 1;            

            }

            .datos_firma{

                width: 100%;

                text-align: right;                   

            }



            .imagenHistoria{

                margin-top: 100px;

                width: 600px; 

                height: 800px;

            }

            .imagenFirma{

                margin-top: 10px;

                width: 150px; 

                height: 150px;

            }        

            h2{

                text-align: center;

                margin-left: 100px;

            }            

            h6{

                text-align: right;

            }            



        </style>

        <title>Historia</title>

    </head>

    <body><br><br>

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

            

            <!--<tr>

                <td><?php echo $this->session->userdata('almacen_nom');?></td></tr>-->

        </table>        

        <table class="datos_titulo">

            <tr>

                <td><h2>HISTORIA CLINICA</h2></td>

                <td><h6><?php echo str_pad($historia->his_correlativo, 8, "0", STR_PAD_LEFT)?></h6></td>

            </tr>

        </table>                    

            <table class="datos_cliente">

                <tr class="datos_titulo1">

                    <td colspan="4">DATOS PACIENTE</td>

                </tr>

                <tr>

                    <td class="datos_totales_bold">N° DE HISTORIA</td>

                    <td><?php echo $paciente->ruc?> </td>

                    <td class="datos_totales_bold">PAGINAS</td>

                    <td>----</td>

                </tr>

                <tr>

                    <td class="datos_totales_bold">PACIENTE</td>

                    <td><?php echo $paciente->razon_social;?></td>

                    <td class="datos_totales_bold">EDAD</td>

                    <td><?php echo $paciente->edad.' años '.$paciente->mes.' mes '.$paciente->dia.'  días'?></td>                    

                </tr>      

                <tr>

                    <td class="datos_totales_bold">FECHA ATENCION</td>

                    <td><?php echo $historia->his_fecha;?></td>

                    <td class="datos_totales_bold">TELEFONO</td>

                    <td><?php echo $paciente->telefono?></td>               

                </tr>      

                <tr>

                    <td class="datos_totales_bold">ALERGIAS</td>

                    <td><?php echo $paciente->alergia;?></td>

                    <td>&nbsp;</td>

                    <td>&nbsp;</td>

                </tr>    

            </table><br>          



            <table class="datos_cliente">

                <tr class="datos_titulo1">

                    <td colspan="4">ESPECIALIDAD - MOTIVO</td>

                </tr>

                <tr>

                    <td class="datos_totales_bold">ESPECIALIDAD</td>

                    <td><?php echo $historia->esp_descripcion?> </td>

                    <td class="datos_totales_bold">PROFESIONAL</td>

                    <td><?PHP echo $historia->prof_nombre?></td>

                </tr>

                <tr>

                    <td class="datos_totales_bold">MOTIVO</td>

                    <td><?php echo $historia->his_motivo;?></td>

                    <td class="datos_totales_bold">DOCUMENTO VENTA</td>

                    <td><?php echo $historia->his_documento_venta?></td>                    

                </tr>                      

            </table><br>

            <table class="datos_cliente">

                <tr class="datos_titulo1">

                    <td colspan="6">TRIAJE</td>

                </tr>

                <tr>

                    <td>INICIO</td>

                    <td><b>PESO:</b> <?php echo "  ". $historia->his_ini_peso?></td>                    

                    <td><b>TALLA:</b> <?php echo "  ".$historia->his_ini_talla?></td>

                    <td><b>TEMP:</b> <?php echo "  ". $historia->his_ini_temperatura;?></td>

                    <td><b>PRESION:</b> <?PHP echo " ".$historia->his_ini_presion_arterial;?></td>

                    <td><b>OTROS:</b> <?PHP echo " ".$historia->his_ini_otros;?></td>



                </tr>

                <tr>

                    <td>FINAL</td>

                    <td><b>PESO:</b> <?php echo "  ". $historia->his_ini_peso?></td>                    

                    <td><b>TALLA:</b> <?php echo "  ".$historia->his_ini_talla?></td>

                    <td><b>TEMP:</b> <?php echo "  ". $historia->his_ini_temperatura;?></td>

                    <td><b>PRESION:</b> <?PHP echo " ".$historia->his_ini_presion_arterial;?></td>

                    <td><b>OTROS:</b> <?PHP echo " ".$historia->his_ini_otros;?></td>

                </tr>                      

            </table><br>







            <table class="datos_cliente">

                <tr class="datos_titulo1">

                    <td colspan="2">ENFERMEDAD Y DIAGNOSTICO</td>

                </tr>

                <tr>

                    <td class="datos_totales_bold">CODIGO CIE</td>

                    <td class="datos_totales"><?php echo $historia->his_codigoCEI_descripcion?></td>

                </tr>

                <tr>

                    <td class="datos_totales_bold">ENFERMEDAD ACTUAL :</td>

                    <td class="datos_totales"><?php echo $historia->his_enfermedad_actual?></td>    

                </tr>

                <tr>

                    <td class="datos_totales_bold">MOTIVO :</td>

                    <td class="datos_totales"><?php echo $historia->his_motivo?></td>    

                </tr>

                <tr>

                    <td class="datos_totales_bold">DIAGNOSTICO :</td>

                    <td class="datos_totales"><?php echo $historia->his_diagnostico?></td>                

                </tr>

                <tr>

                    <td class="datos_totales_bold">EXÁMEN FÍSICO :</td>

                    <td class="datos_totales"><?php echo $historia->his_tratamiento?></td>    

                </tr>

                <tr>

                     <td class="datos_totales_bold">OTROS EXÁMENES Y RECOMENDACIÓN</td>

                     <td class="datos_totales"><?php echo $historia->his_recomendacion?></td>

                </tr>



                <tr>

                     <td class="datos_totales_bold">PRÓXIMA CITA :</td>

                    <td class="datos_totales"><?php echo $historia->his_fecha_cita?></td>

                </tr> 

            </table><br>





            <table class="datos_cliente">

                <thead>

                    <tr class="datos_titulo1">

                        <td colspan="3">TRATAMIENTO</td>

                    </tr>

                    <tr>

                        <th class="tabla_cabecera">CANT.</th>

                        <th class="tabla_cabecera">PRODUCTO</th>

                        <th class="tabla_cabecera">DOSCIFICACIÓN</th>

                    </tr>

                </thead>

                <tbody>

                    <?php foreach($historia->detalles as $item){?>                            

                    <tr>

                        <td class="tabla_datos_cantidad"><?php echo $item->hid_cantidad?></td>

                        <td class="tabla_cabecera"><?php echo $item->hid_descripcion?></td>

                        <td class="tabla_datos_cantidad"><?php echo $item->hid_dosificacion?></td>                        

                    </tr>

                    <?php }?>                    

                </tbody>

            </table><br><br><br>

            <table class="datos_firma">                                        

                <tr>

                    <td>                        

                        <div id="images_gallery">

                          <img class="imagenFirma" src="<?php echo 'images/profesional/firma/'.$historia->prof_firma;?>">

                      </div>

                    </td>

                </tr>                  

            </table>





            <table class="datos_imagen">                                        



                <?PHP foreach($imagenes as $value){?>

                <tr>

                    <td>                        

                        <div id="images_gallery">

                          <img class="imagenHistoria" src="<?php echo 'images/historias/'.$value->hii_foto;?>">

                        </div>

                    </td>

                </tr>                  

                <?PHP }?>

            </table>



        </span><br>        

    </body>

</html>