<div class="container">
    <h4 align="center">Cargar facturas permanente</h4>
    <br>
    <form action="<?= base_url()?>index.php/comp_cli_per/seleccionar" method="post" enctype="multipart/form-data">
        <table class="table table-striped">
            <tr>
                <td>Año
                    <select class="form-control" name="anio">
                        <?php
                        $anio = date("Y");
                        for($i = ($anio - 5); $i <= ($anio + 5); $i++){
                            $selected = (isset($envio_anio)) ? ($envio_anio == $i) ? 'selected' : '' : '';
                            ?>
                        <option <?php echo $selected;?> value="<?php echo $i;?>"><?php echo $i;?></option>
                        <?php
                        }
                        ?>
                    </select>
                </td>
                <td>Mes
                    <select class="form-control" name="mes">
                        <?php
                        for($i = 1; $i <= 12; $i++){
                            $selected = (isset($envio_mes)) ? ($envio_mes == $i) ? 'selected' : '' : '';
                            
                            $mostrar = $i;
                            if($i<10) $mostrar = "0".$i;
                            ?>
                        <option <?php echo $selected;?> value="<?php echo $mostrar;?>"><?php echo $mostrar;?></option>
                        <?php
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <input  dir="ltr" class="btn btn-primary" type="submit" value="Seleccionar" name="submit">
                </td>
                <td><a class="btn btn-success" href="<?php echo base_url()?>/files/xlsx/descargar_formato/formato_clientes_permanentes.xlsx">Descargar Formato</a></td>
            </tr>
        </table>
    </form>    
    Seleccionar Excel a cagar:
    <form action="<?= base_url()?>index.php/comp_cli_per/carga_g" method="post" enctype="multipart/form-data">
        <table class="table table-striped">
            <tr>
                <td><input class="btn btn-default" type="file" name="fileToUpload" id="fileToUpload"></td>
                <td><input  dir="ltr" class="btn btn-primary" type="submit" value="Cargar Formato" name="submit"></td>
            </tr>
        </table>
    </form>
    <br>

    <table class="table table-striped">
        <tr>
            <td>N.</td>
            <td>Periodo</td>
            <td>Empresa</td>
            <td>Tipo</td>
            <td>RUC</td>
            <td>Razon</td>
            <td>Mon.</td>
            <td>Monto</td>
            <td>Descripción</td>
            <td>Facturar</td>
            <td>Enviar mail<br>Cliente</td>
            <td>Estado<br>SUNAT</td>            
            <td>PDF</td>
            <td>XML</td>
            <td>CDR</td>
            <td>Eliminar</td>
        </tr>
        <?php
        $i = 1;
        foreach ($datos as $value) {?>
        <tr>
            <td><?php echo $i; $i++;?></td>
            <td><?php echo $value['anio'].'-'.$value['mes']?></td>
            <td><?php echo $value['empresa']?></td>
            <td><?php echo $value['tipo_documento']?></td>
            <td><?php echo $value['ruc_cliente']?></td>
            <td><?php echo $value['razon_social']?></td>
            <td><?php echo $value['moneda']?></td>
            <td dir="rtl"><?php echo number_format($value['monto'],2);?></td>
            <td><?php echo $value['descripcion']?></td>
            <?php
            if($value['comprobante_id'] == NULL){?>
            <td><a href="<?php echo base_url()."index.php/comp_cli_per/insertarComprobante/" . $value['empresa_id'] . "/" . $value['tipo_documento_id'] . "/" . $value['comp_cli_per_id'] . "/" . $value['anio_de_permanente'] . "/" . $value['mes_de_permanente']; ?>"><div align="center">Facturar<br>Sunat</div></a></td>
            <?php
            }else{?>
            <td>Facturado<br><?php echo $value['serie_comprobante']." ".$value['numero_comprobante']?></td>
            <?php
            }
            ?>              
            <?PHP 
            if ($value['enviado_sunat'] == 1) { 
                if ($value['estado_sunat']  == 0) { ?>
                <td class="col-xs-1"><a href="<?php echo base_url();?>index.php/comprobantes/mailEnviarComprobante/<?php echo $value['comprobante_id']?>/<?php echo $value['cliente_id']?>/viene_de_permanente/<?php echo $value['anio_de_permanente'] ?>/<?php echo $value['mes_de_permanente'];?>">Enviar mail</a></td>
                <td class="col-xs-1"><a href="#" data-toggle="popover" data-html="true" title="Estado Sunat" data-trigger="hover"><span class="glyphicon glyphicon-ok esunat"><input type="hidden" value="<?= $value['comprobante_id'].'/'.$value['cliente_id']?>"/></span></a></td>
                <td class="col-xs-1"><a title="Ver Pdf" onclick="javascript:window.open('<?php echo base_url()?>index.php/comprobantes/pdfGeneraComprobante/<?PHP echo $value['comprobante_id'].'/0'?>','','width=750,height=600,scrollbars=yes,resizable=yes')" href="#"><img title="Ver Pdf" src="<?php echo base_url()."images/pdf.png";?>"></a></td>
                <td class="col-xs-1"><a href="<?= base_url('index.php/comprobantes/xmlSunat/'.$value['comprobante_id'].'/'.$value['cliente_id']);?>" target="_blank"><span class="glyphicon glyphicon-file"></span></a></td>
                <td class="col-xs-1"><a href="<?= base_url('index.php/comprobantes/cdrSunat/'.$value['comprobante_id'].'/'.$value['cliente_id']);?>" target="_blank"><span class="glyphicon glyphicon-list-alt"></span></a></td>
                <?php
                } else { ?>
                <td>---</td>
                <td class="col-xs-1"><a href="#" data-toggle="popover" data-html="true" title="Estado Sunat" data-trigger="hover"><span class="glyphicon glyphicon-globe esunat"><input type="hidden" value="<?= $value['comprobante_id'].'/'.$value['cliente_id']?>"/><?php echo $value['enviado_sunat'];?></span></a></td>                
                <td class="col-xs-1"><a onclick=""><img title="Ver Pdf " src="<?php echo base_url()."images/pdf.png";?>"></a></td>
                <td class="col-xs-1"><span class="glyphicon glyphicon-file"></span></td>
                <td class="col-xs-1"><span class="glyphicon glyphicon-list-alt"></span></td>            
            <?php          
                }
            } else {?>
            <td>---</td>
            <td class="col-xs-1"><span class="glyphicon glyphicon-globe esunat"></span></td>
            <td class="col-xs-1"><img title="Ver Pdf " src="<?php echo base_url()."images/pdf.png";?>"></td>
            <td class="col-xs-1"><span class="glyphicon glyphicon-file"></span></td>
            <td class="col-xs-1"><span class="glyphicon glyphicon-list-alt"></span></td>
            <?php             
            }?>
            <td>Eliminar</td>
        </tr>
        <?php
        }
        ?>
    </table>
</div>