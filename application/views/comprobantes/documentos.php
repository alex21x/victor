<script type="text/javascript">
$( document ).ready(function() {
        $("#fecha_de_emision_inicio").datepicker();
        $("#fecha_de_emision_final").datepicker();
    
        $("#cliente").autocomplete( {
                source: '<?PHP echo base_url(); ?>index.php/comprobantes/buscador_cliente',
                minLength: 2,
                select: function(event, ui) {
                    var data_cli ='<input type="hidden" value="' + ui.item.id + '" name = "cliente_id" id = "cliente_id" >';
                    $('#data_cli').html(data_cli);
                }
        });
});
</script>
<div class="container">
    
    <form method="post" action="<?PHP echo base_url()?>index.php/comprobantes/documentosBuscar" name="form1" id="form1">
        <h2>Lista de Comprobantes: <?php echo $empresa['empresa']?></h2>
            <div class="row">                
                <div class="col-xs-4">
                    <label>Cliente:</label><br>
                    <input type="text" class="form-control input-sm" id="cliente" name="cliente" placeholder="cliente" value="<?= $cliente_select;?>">
                    <div id="data_cli">
                        <input type="hidden" value="<?= $cliente_select_id?>" name = "cliente_id" id = "cliente_id" >                        
                    </div>
                </div>
                <div class="col-xs-2">
                    <label>Tip.Doc</label><br>
                    <select class="form-control input-sm" name="tipo_documento" id="tipo_documento">
                        <?PHP foreach ($tipo_documentos as $value) {
                            $selected =  ($value['id'] == $tipo_documento_id) ? 'SELECTED' : '';?>
                        <option <?= $selected;?> value="<?PHP echo $value['id']?>"><?PHP echo $value['tipo_documento']?></option>
                        <?PHP }?>
                    </select>
                </div>
                <div class="col-xs-4 form-inline">
                    <label>Fec.Emision</label><br>
                    <input class="form-control input-sm" type="text" name="fecha_de_emision_inicio" id="fecha_de_emision_inicio" value="<?PHP echo $fecha_de_emision_inicio_select;?>" placeholder="Desde">
                    <input class="form-control input-sm" type="text" name="fecha_de_emision_final" id="fecha_de_emision_final" value="<?PHP echo $fecha_de_emision_final_select; ?>" placeholder="Hasta">
                </div>                
            </div>
            <div class="row" style="padding-top: 10px">
                <div class="col-xs-4 form-inline">
                    <input type="text" class="form-control input-sm" id="serie" name="serie" value="<?= $serie_select;?>" placeholder="serie">
                    <input type="text" class="form-control input-sm" id="numero" name="numero" value="<?= $numero_select;?>" placeholder="numero">
                </div>
                <div class="col-xs-8">
                    <a style="padding-right: 10px" href="#"><img id="exportarExcel" src="<?= base_url()?>/images/mantenimiento/excel.png"></a>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12" align="center">
                    <input type="submit" class="btn btn-primary" value="Buscar">
                    <input type="hidden" value="<?php echo $empresa['id']?>" name="empresa_id" id="empresa_id">                
                </div>
            </div>         
    </form>
</div>
<br>
<div class="row" align="right" style="padding-right: 25%;">
    <a href="<?PHP echo base_url(); ?>index.php/comprobantes/nuevo/<?php echo $empresa['id']?>" class="btn btn-success">Nuevo Comprobante</a>
</div>
<div class="container-fluid">
    <table class="table table-striped text-center">
        <tr style="font-weight: bold;">
            <td>N°</td>
            <td>Cliente</td>
            <td>T.Documento</td>
            <td>Serie</td>
            <td>Numero</td>
            <td>F.Emisión</td>
            <td>F.Vencimiento</td>
            <td>Monto<br>Bruto</td>
            <td>Igv</td>
            <td>Total</td>
            <td>Cancelado</td>
            <td>Estado Sunat</td>
            <td>PDF</td>
            <td>XML</td>
            <td>CDR</td>
            <td>Accion</td>
        </tr>        
        <?php
        foreach ($comprobantes as $value) {?>
        <tr <?PHP if($value['anulado']== 1){?> class="danger" <?PHP }?> id="<?= $value['comprobante_id']?>">
            <td class="col-sm-1"><a onclick="javascript:window.open('<?PHP echo base_url() ?>index.php/comprobantes/detalle/<?PHP echo $value['comprobante_id']?>','','width = 750, height = 600,scrollbars = yes,resizable = yes')" href="#"><?PHP echo $numero_inicio;?></a></td>
            <td class="col-sm-4 text-left"><?PHP echo $value['razon_social'].'-'.$value['cliente_ruc']?></td>
            <td class="col-sm-1"><?PHP echo $value['tipo_documento']?></td>
            <td class="col-sm-1 text-center"><?PHP echo $value['serie']?></td>
            <td class="text-left"><?PHP echo $value['numero']?></td>
            <td class="col-xs-1"><?PHP echo $value['fecha_de_emision']?></td>
            <td><?PHP echo $value['fecha_de_vencimiento']?></td>
            <td><?PHP echo $value['total_gravada']?></td>
            <td class="col-xs-1 text-center"><?PHP echo $value['total_igv']?></td>
            <td class="text-right"><?PHP echo $value['total_a_pagar']?></td>
            <?PHP if($value['operacion_cancelada'] == 1){?>
                <td><span class="glyphicon glyphicon glyphicon-ok"></span></td>
            <?PHP } else {?>
                <td class="col-xs-1"><span class="glyphicon glyphicon glyphicon-remove"></span></td>
            <?PHP }                
            if ($value['enviado_sunat'] == 1) { 
                    if ($value['estado_sunat']  == 0) { ?>
                <td class="col-xs-1">
                    <a href="#" data-toggle="popover" data-html="true" title="Estado Sunat" data-trigger="hover">
                        <span class="glyphicon glyphicon-ok esunat">
                            <input type="hidden" value="<?= $value['comprobante_id'].'/'.$value['cliente_id']?>"/>
                        </span>
                    </a>
                </td>
                <td class="col-xs-1"><a title="Ver Pdf" onclick="javascript:window.open('<?PHP echo base_url()?>index.php/comprobantes/pdfGeneraComprobanteOffLine/<?PHP echo $value['comprobante_id'].'/0'?>','','width=750,height=600,scrollbars=yes,resizable=yes')" href="#"><img title="Ver Pdf" src="<?PHP echo base_url()."images/pdf.png";?>"></a></td>
                <td class="col-xs-1"><a href="<?= base_url('index.php/comprobantes/xmlSunat/'.$value['comprobante_id'].'/'.$value['cliente_id']);?>" target="_blank"><span class="glyphicon glyphicon-file"></span></a></td>
                <!--<td class="col-xs-1"><a href="<?//= base_url('index.php/comprobantes/cdrSunat/'.$value['comprobante_id'].'/'.$value['cliente_id']);?>" target="_blank"><span class="glyphicon glyphicon-list-alt"></span></a></td>-->                
                <?php
                if($value['cdr_exis'] == 1){
                    $style = "";
                }else{
                    $style = 'style="opacity:0.5"';
                }
                ?>
                <td class="col-xs-1">
                    <a href="<?= base_url('index.php/comprobantes/selectCDR/'.$empresa['id'].'/'.$empresa['ruc'].'/'.$value['tipo_documento_codigo'].'/'.$value['serie'].'/'.$value['numero']);?>" target="_blank">
                        <img <?php echo $style; ?> width="12px" src="../../../images/folder.png"/>
                    </a>
                </td>
                    <?PHP
                    } else { ?>
                <td class="col-xs-1">
                    <a href="#" data-toggle="popover" data-html="true" title="Estado Sunat" data-trigger="hover">
                        <span class="glyphicon glyphicon-globe esunat">
                            <input type="hidden" value="<?= $value['comprobante_id'].'/'.$value['cliente_id']?>"/>
                        </span>
                    </a>
                </td>
                <!--<td class="col-xs-1"><a title="Ver Pdf" onclick="javascript:window.open('<?PHP //echo base_url()?>index.php/comprobantes/pdfGeneraComprobante/0/<?PHP echo $value['comprobante_id']?>/<?PHP echo $value['cliente_id']?>','','width=750,height=600,scrollbars=yes,resizable=yes')" href="#"><img title="Ver Pdf" src="<?PHP //echo base_url()."images/pdf.png";?>"></a></td>-->
                <td class="col-xs-1"><a title="Ver Pdf" onclick="javascript:window.open('<?PHP echo base_url()?>index.php/comprobantes/pdfGeneraComprobanteOffLine/<?PHP echo $value['comprobante_id'].'/0'?>','','width=750,height=600,scrollbars=yes,resizable=yes')" href="#"><img title="Ver Pdf" src="<?PHP echo base_url()."images/pdf.png";?>"></a></td>
                <td class="col-xs-1"><span class="glyphicon glyphicon-file"></span></td>
                <td class="col-xs-1"><span class="glyphicon glyphicon-list-alt"></span></td>
            <?PHP   }
                } else {?>
            <td class="col-xs-1"><span class="glyphicon glyphicon-globe esunat"></span></td>
            <td class="col-xs-1"><img title="Ver Pdf" style="opacity:0.5" src="<?PHP echo base_url()."images/pdf.png";?>"></td>
            <td class="col-xs-1"><span class="glyphicon glyphicon-file"></span></td>
            <td class="col-xs-1"><span class="glyphicon glyphicon-list-alt"></span></td>
            <?PHP
                }?>
            <td><div class="dropup">
                  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Accion                    
                    <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu2">
                    <?PHP 
                    if($value['enviado_sunat'] == 1){
                        if($value['estado_sunat'] == 0) {
                            $estado_enviar_cliente = ($value['enviado_cliente'] == 1) ? '<img src="'. base_url().'/images/estados/check_activo.jpg">' : '';
                            $estado_enviar_grupo = ($value['enviado_equipo'] == 1) ? '<img src="'. base_url().'/images/estados/check_activo.jpg">' : '';
                            ?>
                    <li><a href="<?= base_url();?>index.php/comprobantes/mailEnviarComprobante/<?= $value['comprobante_id']?>/enviar_cliente">Enviar al Cliente <?php echo $estado_enviar_cliente;?></a></li>
                    <li><a href="<?= base_url();?>index.php/comprobantes/mailEnviarComprobante/<?= $value['comprobante_id']?>/enviar_equipo">Enviar al Equipo <?php echo $estado_enviar_grupo;?></a></li>
                        <?PHP
                        } if($value['operacion_cancelada'] == 0){ ?>
                    <li><a href="<?= base_url()?>index.php/comprobantes/updateEstadoComprobante/<?= $value['comprobante_id']?>/1">Marcar como CANCELADO</a></li>
                        <?PHP
                        } if($value['operacion_cancelada'] == 1) { ?>
                    <li><a href="<?= base_url()?>index.php/comprobantes/updateEstadoComprobante/<?= $value['comprobante_id']?>/0">Marcar como PENDIENTE</a></li>
                        <?PHP
                        } if($value['anulado'] == 0){ ?>
                    <li role="separator" class="divider"></li>
                    <li><a href="#" onclick="eliminar('<?PHP echo base_url()?>','comprobantes','txt','1','<?PHP echo $value['comprobante_id']?>')">Anular o Comunicar de Baja</a></li>
                        <?PHP
                        } else {?>
                    <li><a href="#">Consultar estado de Anulación</a></li>
                        <?PHP
                        }
                    } else {?>
                    <li><a href="<?= base_url();?>index.php/comprobantes/modificar/<?= $value['comprobante_id']?>">Editar</a></li>
                    <li><a href="#" onclick="eliminar('<?PHP echo base_url()?>','comprobantes','txt','1','<?PHP echo $value['comprobante_id']?>')">Anular o Comunicar de Baja</a></li>
                    <li><a class="envSunat" href="<?PHP echo base_url();?>index.php/comprobantes/txt/0/<?PHP echo $value['comprobante_id']?>"><input type="hidden" value="<?= $value['comprobante_id'].'/'.$value['cliente_id']?>"/>Enviar Sunat</a></li>
                    <?PHP                     
                    }?>
                  </ul>
                </div>      
            </td>
        </tr>    
        <?php
            $numero_inicio ++;
        }
        ?>            
    </table>
</div>

<div class="container-fluid">
    <div class="row text-center">
        <?PHP echo $this->pagination->create_links();?>
    </div>
</div>