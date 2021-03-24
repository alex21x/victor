<!--<meta http-equiv="refresh" content="20">-->
<style type="text/css">
#btnComprobante{
    display: none;
}

</style>

<script src="<?PHP echo base_url(); ?>assets/js/funciones.js"></script>
<script type="text/javascript">

   

    //Imprimir Boleto
    $(document).ready(function () {
        $('#btnImprimir').click(function () {
            $.ajax({
                url: '<?= base_url();?>index.php/comprobantes/detalle',
                type: 'POST',
                success: function (response) {
                    if (response == 1) {
                        alert('imprimiendo...');
                    } else {
                        alert('ERROR');
                    }
                }
            });
        });


    });
        // AUTOCOMPLETE CLIENTE
        $(document).on('ready',function() {
            $("#cliente").autocomplete( {
                source: '<?PHP echo base_url(); ?>index.php/comprobantes/buscador_cliente',
                minLength: 2,
                select: function(event, ui) {
                    var data_cli ='<input type="hidden" value="' + ui.item.id + '" name = "cliente_id" id = "cliente_id" >';
                    $('#data_cli').html(data_cli);
                },
                change : function(event,ui){
                    if(!ui.item){
                        this.value = '';
                        $('#cliente_id').val(''); 
                    }                   
                } 
            });
                        
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
                                    //console.log(data.codSunat);
                                    if(data.codSunat === '0'){
                                        //console.log(object);                                        
                                        $(object).attr('class','glyphicon glyphicon-ok esunat');
                                    }                                    
                                 },                                         
                                 async: false}).responseText;
                    }
                }).click(function(e) {
                    $(this).popover('toggle');
                });
            });
            
            $('#exportarExcel').click(function(){

                var cliente_id = ($("#cliente_id").val() =='' ) ? null :  $("#cliente_id").val();
                var tipo_documento_id = ( $("#tipo_documento").val() == "") ?  null : $("#tipo_documento").val();
                var fecha_de_emision_inicio = ( $("#fecha_de_emision_inicio").val() == "") ?  null : $("#fecha_de_emision_inicio").val();
                var fecha_de_emision_final  = ( $("#fecha_de_emision_final").val() == "") ?  null : $("#fecha_de_emision_final").val();
                var serie  = ( $("#serie").val() == "") ?  null : $("#serie").val();
                var numero = ( $("#numero").val() == "") ? null : $("#numero").val();
                var empresa_id = ( $("#empresa_id").val() == "") ? null : $("#empresa_id").val();
                var numero_pedido = ( $("#numero_pedido").val() == "") ? null : $("#numero_pedido").val();
                var orden_compra  = ( $("#orden_compra").val() == "") ? null : $("#orden_compra").val();
                var numero_guia   = ( $("#numero_guia").val() == "") ? null : $("#numero_guia").val();
                var vendedor      = ( $("#vendedor").val() == "") ? null : $("#vendedor").val();
                
                
                /* Tanto las fecha de emision de inicio y final deben o no existir pero hambas por igual*/
                if(((fecha_de_emision_inicio != null) && (fecha_de_emision_final == null)) || ((fecha_de_emision_inicio == null) && (fecha_de_emision_final != null))){
                    alert('Falta llenar o vaciar hambas fechas de emisión');return;
                }
                
                var url = '<?PHP echo base_url() ?>index.php/comprobantes/exportarExcel/' + cliente_id + '/'+tipo_documento_id+ '/' +  fecha_de_emision_inicio + '/' + fecha_de_emision_final + '/' + serie + '/' + numero + '/' + empresa_id + '/'+ numero_pedido+ '/' + orden_compra + '/' + numero_guia+ '/'+ vendedor;
                window.open(url, '_blank');

            });


            $('#exportarExcel_rd').click(function(){
                console.log($("#cliente_id").val());
                var cliente_id = ($("#cliente_id").val() =='' ) ? null :  $("#cliente_id").val();
                var tipo_documento_id = ( $("#tipo_documento").val() == "") ?  null : $("#tipo_documento").val();
                var fecha_de_emision_inicio = ( $("#fecha_de_emision_inicio").val() == "") ?  null : $("#fecha_de_emision_inicio").val();
                var fecha_de_emision_final = ( $("#fecha_de_emision_final").val() == "") ?  null : $("#fecha_de_emision_final").val();
                var serie = ( $("#serie").val() == "") ?  null : $("#serie").val();
                var numero = ( $("#numero").val() == "") ? null : $("#numero").val();
                var empresa_id = ( $("#empresa_id").val() == "") ? null : $("#empresa_id").val();
                var numero_pedido = ( $("#numero_pedido").val() == "") ? null : $("#numero_pedido").val();
                var orden_compra = ( $("#orden_compra").val() == "") ? null : $("#orden_compra").val();
                var numero_guia = ( $("#numero_guia").val() == "") ? null : $("#numero_guia").val();
                var vendedor      = ( $("#vendedor").val() == "") ? null : $("#vendedor").val();          
                
                /* Tanto las fecha de emision de inicio y final deben o no existir pero hambas por igual*/
                if(((fecha_de_emision_inicio != null) && (fecha_de_emision_final == null)) || ((fecha_de_emision_inicio == null) && (fecha_de_emision_final != null))){
                    alert('Falta llenar o vaciar hambas fechas de emisión');return;
                }
                
                var url = '<?PHP echo base_url() ?>index.php/comprobantes/exportarExcel_rd/' + cliente_id + '/'+tipo_documento_id+ '/' +  fecha_de_emision_inicio + '/' + fecha_de_emision_final + '/' + serie + '/' + numero + '/' + empresa_id + '/'+ numero_pedido+ '/' + orden_compra + '/' + numero_guia+ '/'+ vendedor;
                window.open(url, '_blank');

            });

            $('#exportarExcel_mes').click(function(){
                
                var url = '<?PHP echo base_url() ?>index.php/comprobantes/exportarExcel_mes';
                window.open(url, '_blank');

            });                                    
        });              
</script>

<style>
    #refresh img{
        margin-left: 50px;
    }
</style>

<?php if($this->session->flashdata('mensaje')!=''){ ?>
<p class="bg-info" style="padding:5px 10px;margin:0 35px;border-radius:5px;text-align: center;background: #1ABC9C;color:#fff;font-weight: 600;font-size: 15px;">
    <?PHP echo $this->session->flashdata('mensaje'); ?>
</p>
<?php } ?>

<div class="container-fluid" style="margin: 0 25px;">
    <form method="post" action="<?PHP echo base_url()?>index.php/comprobantes/index" name="form1" id="form1">    
        <input type="hidden" id="comprobante_id" name="comprobante_id" value="<?= $comprobante_id?>">        
        <h3>Lista de Comprobantes: <b><?php echo $empresa['empresa']?></b></h3>
        
        <div class="row"><br>
        <div class="col-xs-12 col-md-2 col-lg-2 text-left">
            <a href="<?PHP echo base_url(); ?>index.php/comprobantes/nuevo/<?php echo $empresa['id']?>/0" class="btn btn-success btn-block">Nuevo Comprobante</a>
        </div></div><br>
        <div class="panel panel-info" >
                <div class="panel-heading" >
                    <div class="panel-title">FILTRO DE BUSQUEDA</div>                        
                </div>
                <div class="panel-body">   
            <div class="row" >
                <div class="col-md-6 col-lg-6">
                    <label>Cliente:</label><br>
                    <input type="text" class="form-control input-sm" id="cliente" name="cliente" placeholder="Cliente" value="<?= $cliente_select;?>">
                    <div id="data_cli">
                        <input type="hidden"  name = "cliente_id" id = "cliente_id" >

                        <?php
                            if(isset($cliente_select_id) && ($cliente_select_id != '')){
                            /*echo '<input type="hidden" value="' . $cliente_select_id . '" name = "cliente_id" id = "cliente_id" >';*/
                            }
                        ?>
                    </div>
                </div>
                <div class="col-md-3 col-lg-2" >
                    <label>Tip.Doc</label><br>
                    <select class="form-control input-sm" name="tipo_documento" id="tipo_documento">
                        <option value="">Todos</option>
                        <?PHP foreach ($tipo_documentos as $value) {
                            $selected =  ($value['id'] == $tipo_documento_id) ? 'SELECTED' : '';?>
                        <option <?= $selected;?> value="<?PHP echo $value['id']?>"><?PHP echo $value['tipo_documento']?></option>
                        <?PHP }?>
                    </select>
                </div>
                <!--
                <div class="col-md-3 col-lg-2" >
                    <label>Tipo Pago</label><br>
                    <select class="form-control input-sm" name="tipo_pago" id="tipo_pago">
                        <option value="">Todos</option>
                        <?PHP foreach ($tipo_pagos as $value) {
                            $selected =  ($value->id == $tipo_pago_id) ? 'SELECTED' : '';?>
                        <option <?= $selected;?> value="<?PHP echo $value->id?>"><?PHP echo $value->tipo_pago?></option>
                        <?PHP }?>
                    </select>
                </div>-->

                <div class="col-md-3 col-lg-2" >
                    <label>Serie</label><br>
                  <input type="text" class="form-control input-sm" id="serie" name="serie" value="<?= $serie_select;?>" placeholder="serie">
                </div>
                <div class="col-md-3 col-lg-2" >
                    <label>Número</label><br>
                   <input type="text" class="form-control input-sm" id="numero" name="numero" value="<?= $numero_select;?>" placeholder="numero">
                </div>                
                <div class="col-md-3 col-lg-2">
                    <label>Usuario</label>
                    <select class="form-control" id="vendedor" name="vendedor">
                        <option value="">Seleccionar</option>
                            <?PHP foreach ($vendedores as $value_empleado){?>
                        <option value="<?php echo $value_empleado['id']?>"><?php echo $value_empleado['apellido_paterno']." ".$value_empleado['apellido_materno'].", ".$value_empleado['nombre'] ?></option>
                            <?php }?>
                    </select>
                </div>
                <div class="col-md-6 col-lg-2 form-inline">
                    <label>Fec.Emision</label><br>
                    <input class="form-control input-sm" type="text" name="fecha_de_emision_inicio" id="fecha_de_emision_inicio" placeholder="Desde">                    
                    <input class="form-control input-sm" type="text" name="fecha_de_emision_final"  id="fecha_de_emision_final" placeholder="Hasta">    
                    <input type="button" id="buscar_comprobante" class="btn btn-primary" value="Buscar">
                    <input type="hidden" value="<?php echo $empresa['id']?>" name="empresa_id" id="empresa_id">                       
                </div> 
                <div class="col-xs-12 col-md-6 col-lg-6"><br>
                    <div id="totalRows"></div>
                </div>     
            </div>
            <div class="row" style="padding-top: 10px">
                
                <div class="col-xs-6 form-inline">
                    <!--<input type="text" class="form-control input-sm" id="serie" name="serie" value="<?= $serie_select;?>" placeholder="serie">
                    <input type="text" class="form-control input-sm" id="numero" name="numero" value="<?= $numero_select;?>" placeholder="numero">-->

                    <!--<select class="form-control" name="vendedor" id="vendedor">
                                <option value="">Seleccione vendedor</option>
                                <?php foreach($vendedores as $v){?>
                                     $selected =  ($v->id == $_POST['vendedor']) ? 'SELECTED' : '';?>
                                  <option <?= $selected;?> value="<?php echo $v->id?>"><?php echo $v->nombre.' '.$v->apellido_paterno?></option>
                                <?php }?>    
                            </select> -->
                    
                    <!-- numero pedido -->
                    <?php 
                    if ($configuracion->numero_pedido){
                        echo '<input type="text" class="form-control input-sm" id="numero_pedido" name="numero_pedido" value="'.$numero_pedido_select.'" placeholder=" N° Pedido">';                    
                    } else {
                        echo '<input style="display:none;" type="text" class="form-control input-sm" id="numero_pedido" name="numero_pedido" value="'.$numero_pedido_select.'" placeholder="numero N° Pedido">';                    
                    }
                    ?>
                    
                    <!-- numero orden compra -->
                    <!--<?php  if ($configuracion->orden_compra) {
                        echo '<input type="text" class="form-control input-sm" id="orden_compra" name="orden_compra" value="'.$orden_compra_select.'" placeholder="N° Orden de compra">';
                    } else {
                        echo '<input style="display:none;" type="text" class="form-control input-sm" id="orden_compra" name="orden_compra" value="'.$orden_compra_select.'" placeholder="N° Orden de compra">';
                    }
                    ?>-->
                    
                    <!-- numero guia de remision -->
                    <?php 
                    if ($configuracion->numero_guia) {
                        echo '<input type="text" class="form-control input-sm" id="numero_guia" name="numero_guia" value="'.$numero_guia_select.'" placeholder="numero guia">';
                    } else {
                        echo '<input style="display:none;" type="text" class="form-control input-sm" id="numero_guia" name="numero_guia" value="'.$numero_guia_select.'" placeholder="numero guia">';
                    }
                     ?>            
                </div>
            </div>
    </div></div>
    </form>
</div>
<div class="container-fluid" style="margin:0 25px;" >
    <div class="row">
        <button type="button" id="btnComprobante" data-toggle="modal" data-target="#myModal">MODAL</button>
     
                        <div class="col-xs-12 form-inline col-sm-6 col-lg-2" style="padding-bottom: 1rem;margin-top: 8px;">
                            <label> Precio Unit. incluye igv</label>                            
                            <input type="checkbox" name="incluye_igv" id="incluye_igv" <?php echo ($config->pu_igv==1)?"checked":"";?> style="padding-top:15px;">
                        </div>

                        <div class="col-xs-12 form-inline col-sm-6 col-lg-2" style="padding-bottom: 1rem;margin-top: 8px;">
                            <label> Enviar auto. a SUNAT</label>                            
                            <input type="checkbox" name="facturador_auto" id="facturador_auto" <?php echo ($config->facturador_auto==1)?"checked":"";?> style="padding-top:15px;">
                        </div>

                        <!--<div class="col-xs-12 form-inline col-sm-6 col-lg-2" style="padding-bottom: 1rem;margin-top: 8px;">
                            <label> Ticket auto.</label>                            
                            <input type="checkbox" name="ticket_auto" id="ticket_auto" <?php echo ($config->ticket_auto==1)?"checked":"";?> style="padding-top:15px;">
                        </div>-->
          
            <div class="col-md-6" style="text-align: right;">                
                <a id="exportarExcel" class="btn btn-primary"><i class="glyphicon glyphicon-save"></i> REPORTE LISTADO</a>
                <a id="exportarExcel_rd" class="btn btn-primary"><i class="glyphicon glyphicon-save"></i> REPORTE DETALLADO</a>
                <!--<a id="exportarExcel_mes" class="btn btn-primary"><i class="glyphicon glyphicon-save"></i> VENDEDORES X MES</a>-->
            </div>            
     
    </div>
</div>

<div style="width: 96%;margin: 0 auto; padding: .4rem 0;">
    <div class="row" style="padding-bottom: 0;margin-bottom: 0;">        
    </div>    
    
    <div id="grid" style="margin: 0 auto"></div>   
    
</div>


    <script>    

        // FECHA JAVASCRIPT            
        var fecha = new Date(); //Fecha actual
        var mes = fecha.getMonth()+1; //obteniendo mes
        var dia = 1; //obteniendo dia
        var ano = fecha.getFullYear(); //obteniendo año
        if(dia<10)
          dia='0'+dia; //agrega cero si el menor de 10
        if(mes<10)
          mes='0'+mes //agrega cero si el menor de 10
          fecha_de_emision_inicio =dia+"-"+mes+"-"+ano;

          ultimoDiaMes = new Date(ano, mes, 0);
          fecha_de_emision_final = ultimoDiaMes.getDate()+"-"+mes+"-"+ano;
            
          $("#fecha_de_emision_inicio").val(fecha_de_emision_inicio);
          $("#fecha_de_emision_final").val(fecha_de_emision_final);
            //var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);
          $("#fecha_de_emision_inicio").datepicker({
                dateFormat: 'dd-mm-yy' ,                
                firstDay: 1
          });

          $("#fecha_de_emision_final").datepicker({
                dateFormat: 'dd-mm-yy',                
                firstDay: 1
          });           

        function generate_xml(comprobante_id){
            toast("info",10000, 'Generando XML . . .');
            $.getJSON("<?PHP echo base_url(); ?>index.php/comprobantes/getDatosXML",{comprobante_id})
            .done(function(json){
                var datosJSON = JSON.stringify(json);
                $.post("<?php echo RUTA_API?>index.php/Sunat/generate_xml",{datosJSON})
                 .done(function(res){
                    var response;
                    try{                        
                             response = JSON.parse(res);
                             if(response.res == 1){
                                toast("success", 4000, response.msg);
                                     $.post("<?PHP echo base_url(); ?>index.php/comprobantes/updateEstadoXML",{comprobante:comprobante_id,firma:response.firma})
                                     .done(function(res){
                                         toast("success", 1500, 'XML Generado correctamente !!'); 
                                          dataSource.read();
                                     })
                             }else{
                                toast("error",5000, response.msg);
                             }
                    }catch(e){
                            console.log(res);
                            toast("error",5000, "Error al generar XML");                          
                    }                           
                })
            });
        }

        function send_xml(comprobante_id){
            toast("info",10000, 'Enviando a SUNAT . . .');
            $.getJSON("<?PHP echo base_url(); ?>index.php/comprobantes/getDatosXML",{comprobante_id})
            .done(function(json){
                var datosJSON = JSON.stringify(json);
                $.post("<?php echo RUTA_API?>index.php/Sunat/send_xml",{datosJSON})
                 .done(function(res){
                    var response;
                    try{
                             response = JSON.parse(res);
                             if(response.res == 1){
                                
                                     $.post("<?PHP echo base_url(); ?>index.php/comprobantes/updateEstadoCDR",{comprobante:comprobante_id,firma:response.firma})
                                     .done(function(res){
                                          toast("success", 4000, response.msg); 
                                          dataSource.read();
                                     })
                             }

                    }catch(e){
                            console.log(res);
                            toast("error",6000,res);
                          
                    }

                           
                })

            });
        }

        function send_anulacion(opc,comprobante_id){
            $.getJSON("<?PHP echo base_url(); ?>index.php/comprobantes/getDatosAnulacion",{opc,comprobante_id})
             .done(function(json){
                if(json['go'] == 1){
                    toast("info",10000, 'Enviando COMUNICACIÓN DE BAJA . . .');
                    var datosJSON = JSON.stringify(json);
                    $.post("<?php echo RUTA_API?>index.php/Sunat/send_anulacion",{datosJSON})
                     .done(function(res){
                        var response;
                        try{
                                 response = JSON.parse(res);
                                 if(response.res == 1){
                                    
                                         $.post("<?PHP echo base_url(); ?>index.php/comprobantes/updateEstadoANULACION",{comprobante:comprobante_id,estado:response.estado,ticket:response.ticket,numero:response.numero})
                                         .done(function(res){
                                              toast("success", 4000, response.msg); 
                                              dataSource.read();
                                         })
                                 }
                        }catch(e){
                                console.log(res);
                                toast("error",6000,res);                            
                        }                               
                    })
                }else{
                   toast("info",10000, 'Enviado a RESUMENES DE BOLETAS ANULADAS . . .');
                   dataSource.read();
                }     
            });
        }              

        function send_anulacionPASSWORD(opc,comprobante_id){    
            $.confirm({
                title: 'Anular Comprobante!',
                content: '' +
                '<form action="" class="formName">' +
                '<div class="form-group">' +
                '<label>Ingrese Password</label>' +
                '<input type="password" placeholder="Password" class="password form-control" required />' +
                '</div>' +
                '</form>',
                buttons: {
                    formSubmit: {
                        text: 'Confirmar',
                        btnClass: 'btn-blue',
                        action: function () {
                            var password = this.$content.find('.password').val();

                            $.getJSON("<?PHP echo base_url(); ?>index.php/comprobantes/getDatosAnulacionPASSWORD",{opc,comprobante_id,password})
                             .done(function(json){

                                if(json['password'] == -1){
                                        toast("error",6000,'CONTRASEÑA INCORRECTA');
                                } else {

                                if(json['go'] == 1){
                                    toast("info",10000, 'Enviando COMUNICACIÓN DE BAJA . . .');
                                    var datosJSON = JSON.stringify(json);
                                    $.post("<?php echo RUTA_API?>index.php/Sunat/send_anulacion",{datosJSON})
                                     .done(function(res){
                                        var response;
                                        try{
                                                 response = JSON.parse(res);
                                                 if(response.res == 1){
                                                         $.post("<?PHP echo base_url(); ?>index.php/comprobantes/updateEstadoANULACION",{comprobante:comprobante_id,estado:response.estado,ticket:response.ticket,numero:response.numero})
                                                         .done(function(res){
                                                              toast("success", 4000, response.msg); 
                                                              dataSource.read();
                                                         })
                                                 }
                                        }catch(e){
                                                console.log(res);
                                                toast("error",6000,res);                                              
                                        }                                               
                                    })

                                }else{
                                   toast("info",10000, 'Enviado a RESUMENES DE BOLETAS ANULADAS . . .');
                                   dataSource.read();
                                }} 
                            });                           
                        }
                    },
                    cancel: function () {
                        //close
                    },
                },
                onContentReady: function () {
                    // bind to events
                    var jc = this;
                    this.$content.find('form').on('submit', function (e) {
                        // if the user submits the form by pressing enter in the field.
                        e.preventDefault();
                        jc.$$formSubmit.trigger('click'); // reference the button and click it
                    });
                }
            });        
        }      
        
            ///INCLUYE IGV
            $("#incluye_igv").click(function(){
                if( $(this).is(':checked') ) {
                    var valor = 1;
                }else{
                    var valor = 0;
                }
                window.location.href = "<?PHP echo base_url()?>index.php/comprobantes/estado_igv/"+valor;                
            });

            ///FACTURADOR AUTO
            $("#facturador_auto").click(function(){
                if( $(this).is(':checked') ) {
                    var valor = 1;
                }else{
                    var valor = 0;
                }

                 window.location.href = "<?PHP echo base_url()?>index.php/comprobantes/facturador_auto/"+valor;                
            });

            ///TICKET AUTO
            $("#ticket_auto").click(function(){
                if( $(this).is(':checked') ) {
                    var valor = 1;
                }else{
                    var valor = 0;
                }
                 window.location.href = "<?PHP echo base_url()?>index.php/comprobantes/ticket_auto/"+valor;                
            });

     
    
    /*kendo framewrork list tables*/
    var dataSource = new kendo.data.DataSource({
         transport: {
            read: {
                url:"<?php echo base_url()?>index.php/comprobantes/getMainList/",
                dataType:"json",
                method:'post',
                data:function(){
                    return {
                        cliente:function(){
                            return $("#cliente_id").val();
                        },
                        tipo_documento:function(){
                            return $("#tipo_documento").val();
                        },
                        fecha_desde:function(){
                            console.log($("#fecha_de_emision_inicio").val());
                            return $("#fecha_de_emision_inicio").val();
                        },
                        fecha_hasta:function(){
                            console.log($("#fecha_de_emision_final").val());
                            return $("#fecha_de_emision_final").val();
                        },
                        serie:function(){
                            return $.trim($("#serie").val());
                        },
                        numero:function(){
                            return $.trim($("#numero").val());
                        },
                        numero_pedido:function(){
                            return $.trim($("#numero_pedido").val());
                        },
                        orden_compra:function(){
                            return $.trim($("#orden_compra").val());
                        },
                        numero_guia:function(){
                            return $.trim($("#numero_guia").val());
                        },
                        vendedor:function(){
                            return $.trim($("#vendedor").val());
                        }

                    }
                }
            }
        },
        schema:{
            data:'data',
            total:'rows'
        },
        pageSize: 20,
        serverPaging: true,
        serverFiltering: true,
        serverSorting: true
       
    });
    $("#grid").kendoGrid({

        dataSource: dataSource,
        height: 550,
        sortable: true,
        pageable: true,        
        columns: [
                    {field:'num_rows',title:'N°',width:'30px'},
                    {field:'cliente',title:'CLIENTE',width:'150px',template:"#= cliente #"},
                    {field:'cli_ruc',title:'RUC/DNI',width:'70px'},
                    {field:'Tipo_doc',title:'T. DOC',width:'50px'},
                    {field:'num_doc',title:'NUMERO',width:'70px',template:"#= num_doc #"},                    
                    {field:'fecha_de_emision',title:'F.EMISIÓN',width:'110px'},
                    {field:'vendedor', title:'USUARIO',width:'110px'},
                    {field:'moneda',title:'MONEDA',width:'70px'},
                    //{field:'fecha_de_vencimiento', title:'F.VENCIMIENTO',width:'140px'},
                    {field:'Monto_bruto',title:'SUBTOTAL',width:'70px'},
                    {field:'total_igv',title:'IGV',width:'50px'},
                    {field:'total_a_pagar', title:'TOTAL',width:'70px'},                    
                    //{field:'btn_estado_sunat', title:'ESTADO SUNAT',width:'120px',template:"#= btn_estado_sunat #"},
                    
                    {field:'btn_pdf',title:'PDF',width:'50px',template:"#= btn_pdf #"},
                    {field:'btn_ticket',title:'TICKET',width:'50px',template:"#= btn_ticket #"},
                    {field:'btn_popup',title:'ENVIAR',width:'50px',template:"#= btn_popup #"},
                    //{field:'btn_ticket_58',title:'TICKET 58',width:'50px',template:"#= btn_ticket_58 #"},
                    //{field:'btn_xml', title:'XML',width:'40px',template:"#= btn_xml #"},
                    //{field:'btn_cdr', title:'CDR',width:'40px',template:"#= btn_cdr #"},

                    //{field:'btn_mail',title:'EMAIL',width:'50px',template:"#= btn_mail #"},
                    {
                        title:'ESTADO',
                        width:'100px',
                        template:"#= btn_action #",
                        attributes: {
                          "class": "gridcell",
                          style: "overflow: inherit;position:relative;"
                        }
                    },
                   /* {field:'prod_eliminar', title:'&nbsp;',width:'100px',template:"#= prod_eliminar #"},*/
        ],
        detailTemplate: '<div class="lista_comprobantes"></div>',
        detailInit: detailInit,        
        dataBound:function(e){            


            if($("#comprobante_id").val() != '')
            $('#btnComprobante').trigger('click');
                     
            var grid = $("#gridSellIn").data("kendoGrid");
            var data = dataSource.data();
            $.each(data,function(e, row){
                if(row.anulado ==1)
                {
                    $('tr[data-uid="' + row.uid + '"] ').css("background-color", "rgb(241, 204, 206)");
                }         
            });
            
            var totalRecords = dataSource.total();
            $("#totalRows").html('');
            $("#totalRows").html('<p class="bg-primary" style="text-align:center;font-size:16px;"><b>TOTAL DE COMPROBANTES: '+totalRecords+'</b></p>');
            $("#buscar_comprobante").click(function(e){
                e.preventDefault();
                dataSource.read();
            });
            
            /*Imprimir ticket*/                        
            var PrintWindow;
            function openWindow(id) {
              PrintWindow = window.open('<?PHP echo base_url() ?>index.php/comprobantes/data_impresion_pos_ticket/'+id,'popup', 'width=400px,height=800px');
              //setTimeout(function() {closeOpenedWindow();}, 400);
            }
            function closeOpenedWindow() {
              PrintWindow.close();
            }
           
            $('.print_ticket_pdf').click(function() {
                var _val = $(this).attr("idval");
                javascript:window.open('<?PHP echo base_url() ?>index.php/comprobantes/pdf/'+_val+'','','width=750,height=600,scrollbars=yes,resizable=yes');            
                var id= $(this).attr('idval');
                //openWindow(id);
            });

            $('.print_ticket_pdf_58').click(function() {
                var _val = $(this).attr("idval");
                javascript:window.open('<?PHP echo base_url() ?>index.php/comprobantes/pdf_58/'+_val+'','','width=750,height=600,scrollbars=yes,resizable=yes');            
                var id= $(this).attr('idval');
                //openWindow(id);
            });


            /*Enviar correo*/
            var popup;
            function Sendmail(url) {
               popup = window.open(url,'popup', 'width=400px,height=500px');
            }
            function Closemail() {
                popup.close();
            }
            
            $(".show_pdf").click(function(e) {               
                var _val = $(this).attr("idval");
                javascript:window.open('<?PHP echo base_url() ?>index.php/comprobantes/pdfGeneraComprobanteOffLine/'+_val,'','width=750,height=600,scrollbars=yes,resizable=yes');
                
            });

            $(".delete").click(function(e) {               
                var _val = $(this).attr("idval");
                if(confirm("¿ Esta seguro eliminar comprobante ?")){
                   window.location.href = "<?PHP echo base_url() ?>index.php/comprobantes/eliminar/"+_val;
                }
                
                
            });
            /*descargar xml*/
            var popup_xml;
            function open_xml(url) {
               popup_xml = window.open(url,'popup', 'width=400px,height=500px');
            }
            function close_xml() {
                popup_xml.close();
            }
            $('body').delegate('.dow_xml', 'click', function(e) {
                e.preventDefault();
                var url = $(this).attr('_href');
                open_xml('');
                $.ajax({
                    url:url,
                    dataType:'json',
                    method:'post',
                    success:function(response){
                        
                        if(response.status == STATUS_FAIL)
                        {                                
                            toast('error', 2000 ,response.msg);
                        } else
                        //if(response.status == STATUS_OK)
                        {
                            toast('success', 2000, "se descargo correctamente");
                            console.log(response.msg);
                             window.open(url, 'Download');
                            //javascript:window.open(url+'','width=750,height=600,scrollbars=yes,resizable=yes');
                        }
                        setTimeout(function() {close_xml();}, 1800);
                    }
                });

            });
            $('body').delegate('.enviar_correo', 'click', function(e) {
                e.preventDefault();   
                e.stopPropagation();             
                var url = $(this).attr('_href');   
                Sendmail(url);             
                $.ajax({
                    url:url,
                    dataType:'json',
                    method:'post',
                    success:function(response){
                        if(response.status == STATUS_OK)
                        {
                            toast('success', 3000, response.msg);                                
                        }
                        if(response.status == STATUS_FAIL)
                        {                                
                            toast('error', 4000 ,response.msg);
                        }
                        setTimeout(function() {Closemail();}, 1000);
                    }
                });
            });
        }
    });



    function detailInit(e) {
        var detailRow = e.detailRow;

        detailRow.find(".lista_comprobantes").kendoGrid({
            dataSource: {
                type: "json",
                transport: {
                    read:{
                        url:"<?php echo base_url()?>index.php/comprobantes/getMainListDetail/",
                        dataType:"json",
                        method:'post',
                        data:function(){
                            return {
                                comprobante_id:e.data.comp_id
                            }
                        }
                    }
                },
                schema:{
                    data:'data',
                    total:'rows'
                },                
                serverPaging: true,
                serverSorting: true,
                serverFiltering: true,
                pageSize: 7,
            },
            scrollable: false,
            sortable: true,
            pageable: true,
            columns: [
                { field: "descripcion", title:"PRODUCTO", width: "120px" },
                { field: "importe", title:"IMPORTE", width: "50px" },
                { field: "cantidad", title:"CANTIDAD",width:"70px" },
                { field: "total", title:"TOTAL",width:"70px" }
            ],
            dataBound:function(e){
            }
        });
    }

    //dataSource.read();
    //MODAL DE ENVIO DE COMPROBANTES 03-08-2020 ALEXANDER FERNANDEZ
    $(document).on('click',"#btnComprobante",function(){

        var comprobante_id =  $("#comprobante_id").val();
        $("#myModal").load('<?= base_url()?>index.php/comprobantes/modalEnvioComprobante/'+comprobante_id,{}); 

    });   

    //PSE TOKEN 10-01-2021
    function send_xmlPSE(comprobante_id){
            toast("info",10000, 'Enviando a SUNAT . . .');
            $.getJSON("<?PHP echo base_url(); ?>index.php/comprobantes/getDatosXML_PSE",{comprobante_id})
            .done(function(json){
                var datosJSON = JSON.stringify(json);                
                //JAVASCRIPT JQUERY 04-01-2021
                var settings = {
                  "url": "<?= base_url()?>index.php/comprobantes/send_xmlPSE",
                  "method": "POST",
                  "dataType": "json",                  
                  "data": {"json": datosJSON}
                };                
                $.ajax(settings).done(function(response){
                    try{        
                        if(response.codigo != '' && response.codigo == 21 && response.codigo == 50){
                            toast("error", 9000, "<div style='font-size:16px'>"+response.errors+"</div>");
                            //location.href='<?PHP echo base_url()?>index.php/comprobantes/index';
                        } else {                                                
                            if(response.sunat_responsecode == "0" || response.enlace != ""){                                                        
                            $.post("<?PHP echo base_url(); ?>index.php/comprobantes/updateEstadoCDR_PSE",{comprobante:comprobante_id,firma:response.codigo_hash,enlace_del_xml:response.enlace_del_xml,enlace_del_cdr:response.enlace_del_cdr})
                                     .done(function(res){
                                          response.sunat_description = 'Enviado';
                                          toast("success", 4000, response.sunat_description); 
                                          dataSource.read();
                               })
                             }
                    }} catch(e){                            
                            toast("error",6000,response.errors);
                          
                    }
                });
            });
        }

    //PSE ANULACIÓN 10-01-2021
    function send_anulacionPSE(opc,comprobante_id){
            $.getJSON("<?PHP echo base_url(); ?>index.php/comprobantes/getDatosAnulacion_PSE",{opc,comprobante_id})
             .done(function(json){
                if(json['go'] == 1){
                    toast("info",10000, 'Enviando COMUNICACIÓN DE BAJA . . .');
                    var datosJSON = JSON.stringify(json);
                    var settings = {
                      "url": "<?= base_url()?>index.php/comprobantes/send_xmlPSE",
                      "method": "POST",
                      "dataType": "json",                  
                      "data": {"json": datosJSON}
                    };         

                    $.ajax(settings).done(function(response){                        
                    try{                                                                 
                        if(typeof response.sunat_ticket_numero != "undefined"){
                            console.log(response.sunat_ticket_numero);
                            response.estado = 5;
                            $.post("<?PHP echo base_url(); ?>index.php/comprobantes/updateEstadoANULACION",{comprobante:comprobante_id,estado:response.estado,ticket:response.sunat_ticket_numero,numero:response.numero})
                                         .done(function(res){
                                              toast("success", 4000, response.msg);
                                              dataSource.read();
                                })
                            }else{
                                toast("error",6000,response.errors);
                            }
                        } catch(e){
                                console.log(response);
                                toast("error",6000,response.errors);
                    }
                    })

                  } else{
                   toast("info",10000, 'Enviado a RESUMENES DE BOLETAS ANULADAS . . .');
                   dataSource.read();

                }                           
            })            
        }   
</script>
