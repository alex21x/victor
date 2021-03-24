<!--<meta http-equiv="refresh" content="20">-->
<script src="<?PHP echo base_url(); ?>assets/js/funciones.js"></script>
<script type="text/javascript">
        // AUTOCOMPLETE CLIENTE
        $(document).ready(function() {
            $("#cliente").autocomplete( {
                source: '<?PHP echo base_url(); ?>index.php/comprobantes/buscador_cliente',
                minLength: 2,
                select: function(event, ui) {
                    var data_cli ='<input type="hidden" value="' + ui.item.id + '" name = "cliente_id" id = "cliente_id" >';
                    $('#data_cli').html(data_cli);
            }
        });
        // FECHA JAVASCRIPT
        $("#fecha_de_emision").datepicker();
        // POPOVER ESTADO SUNAT
        //$.ajaxSetup({ cache: true });
        $('.esunat').on("mouseover",function(){
            object = $(this);
            var valor =  $(this).children().val();
            valor = valor.split("/");
        
        $(this).parent().popover({
                html: true,
                trigger: 'hover',
                content: function() {
                  return $.ajax({url: '<?= base_url();?>index.php/comprobantes/popoverSunat',
                                 type: 'GET',
                                 dataType : 'JSON',
                                 data: {comprobanteId : valor[0], clienteId : valor[1] },                                 
                                 success: function(data){
                                    console.log(data.codSunat);
                                    if(data.codSunat == 0){
                                        console.log(object);                                        
                                        $(object).attr('class','glyphicon glyphicon-ok esunat');
                                    }                                    
                                 },                                         
                                 async: false}).responseText;
                }
                }).click(function(e) {
                $(this).popover('toggle');
        });
        });                    
                
        var resSunat = function(estado){
                        var t;
                                $.ajax({url: '<?= base_url();?>index.php/comprobantes/rptaSunat',
                                 type: 'GET',
                                 dataType : 'JSON',          
                                 async: false,
                                 data: {comprobanteId : estado},
                                 success: function(data){
                                    clearInterval(t);
                                    //console.log(data.codSunat);
                                    if(data.status === 'resultados'){
                                        //alert(2);
                                        t = setTimeout(function(){
                                            resSunat(1);
                                    },1000);                                 
                                    alert(2);
                                    jQuery.each(data.datos, function(i,msg){
                                    var i2 = i;    
                                    i2++;
                                    var object = $('#'+msg.comprobante_id);
                                    //console.log(object);
                                        if(msg.codSunat === 0){
                                            alert(1);
                                            //$('tbody tr:eq('+foc[1]+')'+' .'+foc[0]).focus();   
                                            //$(object).attr('class','glyphicon glyphicon-ok esunat');                                            
                                            $(object).children('td:eq(11)').children().children().attr('class','glyphicon glyphicon-ok esunat');
                                            $(object).children('td:eq(12)').children().attr('onclick',"javascript:window.open('<?= base_url().'index.php/comprobantes/pdfGeneraComprobante/0/';?>"+msg.comprobante_id+"/"+msg.cliente_id+"','','width=750,height=600,scrollbars=yes,resizable=yes')");
                                            $(object).children('td:eq(13)').html("<a href='<?= base_url().'index.php/comprobantes/xmlSunat/';?>"+msg.comprobante_id+'/'+msg.cliente_id+"' target='_blank'><span class='glyphicon glyphicon-file'></span></a>");
                                            $(object).children('td:eq(14)').html("<a href='<?= base_url().'index.php/comprobantes/cdrSunat/';?>"+msg.comprobante_id+'/'+msg.cliente_id+"' target='_blank'><span class='glyphicon glyphicon-list-alt'></span></a>");
                                            $(object).children('td:eq(15)').children().children('ul').prepend('<li><a href="<?= base_url();?>index.php/comprobantes/mailEnviarComprobante/'+msg.comprobante_id+'/'+msg.cliente_id+'">Enviar al Cliente</a></li>');
                                            //<li><a href="<?PHP //echo base_url();?>index.php/comprobantes/mailEnviarComprobante/<?PHP //echo $value['comprobante_id']?>/<?PHP //echo $value['cliente_id']?>">Enviar al Cliente</a></li>
                                            //$('.dropup ul').append('');
                                        }                                                                                                                                                                                                                                  
                       });                                                                                                            
                                  }
                                },
                                error    : function(retorno){
                                clearInterval(t);
                                t = setTimeout(function(){
                                    resSunat(1);;
                                },15000);
           }
       });
    };                                       
        
        resSunat(1);                       
    });              
</script>

<p class="bg-info">
    <?PHP echo $this->session->flashdata('mensaje'); ?>
</p>
<form method="post" action="<?PHP echo base_url()?>index.php/comprobantes/index" name="form1" id="form1">
<div class="container">
    <h2>Lista de Comprobantes</h2>
    
    <div class="row">
        <div class="col-md-1">
            <label></label>
        </div>        
        <table class="table table-striped">
            <tr>
                <td>
                    <div class="col-md-4">
                        <label>Cliente</label>
                    </div>
                </td>    
                <td>
                    <div class="col-md-12">
                    <input type="text" class="form-control" id="cliente" name="cliente" placeholder="Cliente">
                    </div>
                    <div id="data_cli"></div>
                </td>
                <td>
                    <div class="col-md-4">
                        <label>Tip.Doc</label>
                    </div>
                </td>
                <td>
                    <div class="col-md-8">
                        <select class="form-control" name="tipo_documento" id="tipo_documento">
                            <?PHP foreach ($tipo_documentos as $value) { ?>                                                                                        
                            <option value="<?PHP echo $value['id']?>"><?PHP echo $value['tipo_documento']?></option>                            
                            <?PHP }?>
                        </select>
                    </div>                    
                </td>
                <td>
                    <label>Fec.Emision</label>
                </td>
                <td>
                    <div class="col-md-8">
                        <input class="form-control" type="text" name="fecha_de_emision" id="fecha_de_emision">
                    </div>
                </td>
                
            </tr>
            <tr>
                <td>
                    <div class="col-md-4">
                        <label>Serie</label>
                    </div>
                </td>    
                <td>
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="serie" name="serie" placeholder="serie">
                    </div>
                </td>                
                <td>
                    <div class="col-md-4">
                        <label>Numero</label>
                    </div>
                </td>    
                <td>
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="numero" name="numero" placeholder="numero">
                    </div>
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td colspan="6" style="text-align:center"><input type="submit" class="btn btn-primary" value="Buscar"></td>
            </tr>
        </table>                
    </div>
</div>
    </form>

<div class="container-fluid">            
    <div class="row">
    <table class="table table-striped text-center">    
        <tr style="font-weight: bold;">
            <td>N°</td>
            <td>Cliente</td>
            <td>T.Documento</td>
            <td>Serie</td>
            <td>Numero</td>
            <td>F.Emisión</td>
            <td>F.Vencimiento</td>                        
            <td>Igv</td>
            <td>Total</td>
            <td>Cancelado</td>
            <td>Enviado al Cliente</td>
            <td>Estado Sunat</td>
            <td>PDF</td>
            <td>XML</td>
            <td>CDR</td>
            <td>Accion</td>
        </tr>        
        <?PHP foreach ($comprobantes as $value) {?>                                    
        <tr <?PHP if($value['anulado']== 1){?> class="danger" <?PHP }?> id="<?= $value['comprobante_id']?>">
            <td class="col-sm-1"><a onclick="javascript:window.open('<?PHP echo base_url() ?>index.php/comprobantes/detalle/<?PHP echo $value['comprobante_id']?>','','width = 750, height = 600,scrollbars = yes,resizable = yes')" href="#"><?PHP echo $value['comprobante_id']?></a></td>
            <td class="col-sm-4 text-left"><?PHP echo $value['razon_social']?></td>
            <td class="col-sm-1"><?PHP echo $value['tipo_documento']?></td>
            <td class="col-sm-1 text-center"><?PHP echo $value['serie']?></td>
            <td class="text-left"><?PHP echo $value['numero']?></td>
            <td class="col-xs-1"><?PHP echo $value['fecha_de_emision']?></td>
            <td><?PHP echo $value['fecha_de_vencimiento']?></td>
            <td class="col-xs-1 text-center"><?PHP echo $value['total_igv']?></td>
            <td class="text-right"><?PHP echo $value['total_a_pagar']?></td>
            <?PHP if($value['operacion_cancelada'] == 1){?>
            <td><span class="glyphicon glyphicon glyphicon-ok"></span></td>
            <?PHP } else {?>
            <td class="col-xs-1"><span class="glyphicon glyphicon glyphicon-remove"></span></td>
            <?PHP }if($value['enviado_cliente'] == 1){?>
            <td><span class="glyphicon glyphicon glyphicon-ok"></span></td>
            <?PHP } else {?>            
            <td class="col-xs-1"><span class="glyphicon glyphicon glyphicon-remove"></span></td>
            <?PHP }?>            
            <?PHP if ($value['enviado_sunat'] == 1) { 
                  if ($value['estado_sunat']  == 0) { ?>
            <td class="col-xs-1"><a href="#" data-toggle="popover" data-html="true" title="Estado Sunat" data-trigger="hover"><span class="glyphicon glyphicon-ok esunat"><input type="hidden" value="<?= $value['comprobante_id'].'/'.$value['cliente_id']?>"/></span></a></td>
            <td class="col-xs-1"><a title="Ver Pdf" onclick="javascript:window.open('<?PHP echo base_url()?>index.php/comprobantes/pdfGeneraComprobante/0/<?PHP echo $value['comprobante_id']?>/<?PHP echo $value['cliente_id']?>','','width=750,height=600,scrollbars=yes,resizable=yes')" href="#"><img title="Ver Pdf" src="<?PHP echo base_url()."images/pdf.png";?>"></a></td>
            <td class="col-xs-1"><a href="<?= base_url('index.php/comprobantes/xmlSunat/'.$value['comprobante_id'].'/'.$value['cliente_id']);?>" target="_blank"><span class="glyphicon glyphicon-file"></span></a></td>
            <td class="col-xs-1"><a href="<?= base_url('index.php/comprobantes/cdrSunat/'.$value['comprobante_id'].'/'.$value['cliente_id']);?>" target="_blank"><span class="glyphicon glyphicon-list-alt"></span></a></td>
                  <?PHP } else { ?>
            <td class="col-xs-1"><a href="#" data-toggle="popover" data-html="true" title="Estado Sunat" data-trigger="hover"><span class="glyphicon glyphicon-globe esunat"><input type="hidden" value="<?= $value['comprobante_id'].'/'.$value['cliente_id']?>"/></span></a></td>
            <!--<td class="col-xs-1"><a title="Ver Pdf" onclick="javascript:window.open('<?PHP //echo base_url()?>index.php/comprobantes/pdfGeneraComprobante/0/<?PHP echo $value['comprobante_id']?>/<?PHP echo $value['cliente_id']?>','','width=750,height=600,scrollbars=yes,resizable=yes')" href="#"><img title="Ver Pdf" src="<?PHP //echo base_url()."images/pdf.png";?>"></a></td>-->
            <td class="col-xs-1"><a onclick=""><img title="Ver Pdf " src="<?PHP echo base_url()."images/pdf.png";?>"></a></td>
            <td class="col-xs-1"><span class="glyphicon glyphicon-file"></span></td>
            <td class="col-xs-1"><span class="glyphicon glyphicon-list-alt"></span></td>            
                  <?PHP }} else {?>
                <td class="col-xs-1"><span class="glyphicon glyphicon-globe esunat"></span></td>
                <td class="col-xs-1"><img title="Ver Pdf " src="<?PHP echo base_url()."images/pdf.png";?>"></td>
                <td class="col-xs-1"><span class="glyphicon glyphicon-file"></span></td>
                <td class="col-xs-1"><span class="glyphicon glyphicon-list-alt"></span></td>
            <?PHP }?>                                    
            <td><div class="dropup">
                  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Accion
                    <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu2">
                    <?PHP if($value['enviado_sunat'] == 1){
                          if($value['estado_sunat'] == 0) {?>
                    <li><a href="<?= base_url();?>index.php/comprobantes/mailEnviarComprobante/<?= $value['comprobante_id']?>/<?= $value['cliente_id']?>">Enviar al Cliente</a></li>
                    <?PHP } if($value['operacion_cancelada'] == 0){ ?>
                    <li><a href="<?= base_url()?>index.php/comprobantes/updateEstadoComprobante/<?= $value['comprobante_id']?>/1">Marcar como CANCELADO</a></li>
                    <?PHP } if($value['operacion_cancelada'] == 1) { ?>
                    <li><a href="<?= base_url()?>index.php/comprobantes/updateEstadoComprobante/<?= $value['comprobante_id']?>/0">Marcar como PENDIENTE</a></li>
                    <?PHP } if($value['anulado'] == 0){ ?>
                    <li role="separator" class="divider"></li>
                    <li><a href="#" onclick="eliminar('<?PHP echo base_url()?>','comprobantes','txt','1','<?PHP echo $value['comprobante_id']?>')">Anular o Comunicar de Baja</a></li>
                    <?PHP } else {?>
                    <li><a href="#">Consultar estado de Anulación</a></li>                                                                                                            
                    <?PHP }} else {?>
                    <li><a href="<?= base_url();?>index.php/comprobantes/modificar/<?= $value['comprobante_id']?>">Editar</a></li>
                    <li><a href="#">Eliminar</a></li>
                    <li><a class="envSunat" href="<?PHP echo base_url();?>index.php/comprobantes/txt/0/<?PHP echo $value['comprobante_id']?>"><input type="hidden" value="<?= $value['comprobante_id'].'/'.$value['cliente_id']?>"/>Enviar Sunat</a></li>
                    <?PHP }?>
                  </ul>
                </div>      
            </td>
        </tr>
        <?PHP }?>
    </table>    
</div>
    </div>