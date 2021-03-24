<!--<meta http-equiv="refresh" content="20">-->
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
                source: '<?PHP echo base_url(); ?>index.php/comprobantes_orden_compras/buscador_cliente',
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
            // FECHA JAVASCRIPT
            $("#fecha_de_emision_inicio").datepicker();
            $("#fecha_de_emision_final").datepicker();
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
                /* Tanto las fecha de emision de inicio y final deben o no existir pero hambas por igual*/
                if(((fecha_de_emision_inicio != null) && (fecha_de_emision_final == null)) || ((fecha_de_emision_inicio == null) && (fecha_de_emision_final != null))){
                    alert('Falta llenar o vaciar hambas fechas de emisión');return;
                }
                
                var url = '<?PHP echo base_url() ?>index.php/comprobantes_orden_compras/exportarExcel/' + cliente_id + '/' +  fecha_de_emision_inicio + '/' + fecha_de_emision_final + '/' + serie + '/' + numero + '/' + empresa_id + '/'+ numero_pedido+ '/' + orden_compra + '/' + numero_guia;
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
<p class="bg-info" style="padding:15px 10px;margin:0 35px;border-radius:5px;text-align: center;background: #1ABC9C;color:#fff;font-weight: 600;font-size: 20px;">
    <?PHP echo $this->session->flashdata('mensaje'); ?>
</p>
<?php } ?>
<div class="container-fluid" style="margin: 0 25px;">
    <form method="post" action="<?PHP echo base_url()?>index.php/comprobantes_orden_compras/index" name="form1" id="form1">    
        <h2>Lista de Orden de Compras: <b><?php echo $empresa['empresa']?></b></h2>

            <div class="row" >
                <div class="col-xs-5">
                    <label>Proveedor:</label><br>
                    <input type="text" class="form-control input-sm" id="cliente" name="cliente" placeholder=" Proveedor" value="<?= $cliente_select;?>">
                    <div id="data_cli">
                        <input type="hidden"  name = "cliente_id" id = "cliente_id" >

                        <?php
                            if(isset($cliente_select_id) && ($cliente_select_id != '')){
                            /*echo '<input type="hidden" value="' . $cliente_select_id . '" name = "cliente_id" id = "cliente_id" >';*/
                            }
                        ?>
                    </div>
                </div>
                <div class="col-xs-4 form-inline"  >
                    <label>Fec.Emision</label><br>
                    <input class="form-control input-sm" type="text" name="fecha_de_emision_inicio" id="fecha_de_emision_inicio" value="<?PHP
                        if(isset($_POST['fecha_de_emision_inicio'])){
                            echo $_POST['fecha_de_emision_inicio'];
                        }?>" placeholder="Desde">
                    
                    <input class="form-control input-sm" type="text" name="fecha_de_emision_final" id="fecha_de_emision_final" value="<?PHP
                        if(isset($_POST['fecha_de_emision_final'])){
                            echo $_POST['fecha_de_emision_final'];
                        }?>" placeholder="Hasta">
                </div>                
            </div>
            <div class="row" style="padding-top: 10px">
                
                <div class="col-xs-6 form-inline">
                    <input type="text" class="form-control input-sm" id="serie" name="serie" value="<?= $serie_select;?>" placeholder="serie">
                    <input type="text" class="form-control input-sm" id="numero" name="numero" value="<?= $numero_select;?>" placeholder="numero">

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
                     <input type="submit" id="buscar_comprobante" class="btn btn-primary" value="Buscar">
                </div>

               
                

            </div>
    </form>
</div>
<div style="width: 98%;margin: 0 auto;margin-top: 1rem;margin-bottom: 0;">
    <div class="row" style="padding-bottom: 0;margin-bottom: 0;" align="right">
        <div class="d-flex justify-content-end" style="padding-bottom: 0;margin-bottom: 0;overflow: hidden;"> 
            <div class="col-md-3">                
                <!--<div class="bg-info" id="refresh" style="margin-left: 2rem; padding: .5rem;margin-bottom: 0;" ></div>-->
            </div>
            <div class="col-md-8">
                <a href="<?PHP echo base_url(); ?>index.php/comprobantes_orden_compras/nuevo/<?php echo $empresa['id']?>" class="btn btn-success">Nueva Orden de Compra</a>
                <!--<a id="exportarExcel" class="btn btn-primary"><i class="glyphicon glyphicon-save"></i> REPORTE ORDEN DE COMPRAS</a>-->
                <!--<a id="exportarExcel_mes" class="btn btn-primary"><i class="glyphicon glyphicon-save"></i> VENDEDORES X MES</a>-->
            </div>            
        </div>
    </div>
</div>

<div style="width: 96%;margin: 0 auto; padding: .4rem 0;">
    <div class="row" style="padding-bottom: 0;margin-bottom: 0;">
        
    </div>    
    
    <div id="grid" style="margin: 0 auto"></div>   
    
</div>


    <script>    
    
    /*kendo framewrork list tables*/
    var dataSource = new kendo.data.DataSource({
         transport: {
            read: {
                url:"<?php echo base_url()?>index.php/comprobantes_orden_compras/getMainList/",
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
                    {field:'cliente',title:'PROVEEDOR',width:'200px'},
                    {field:'num_doc',title:'NUMERO',width:'70px'},                    
                    {field:'fecha_de_emision',title:'F.EMISIÓN',width:'70px'},
                    //{field:'fecha_de_vencimiento', title:'F.VENCIMIENTO',width:'140px'},
                    {field:'Monto_bruto',title:'SUBTOTAL',width:'70px'},
                    {field:'total_igv',title:'IGV',width:'50px'},
                    {field:'total_a_pagar', title:'TOTAL',width:'70px'},
                    //{field:'vendedor', title:'USUARIO',width:'70px'},
                    //{field:'btn_estado_sunat', title:'ESTADO SUNAT',width:'120px',template:"#= btn_estado_sunat #"},
                    
                    {field:'btn_pdf',title:'PDF',width:'40px',template:"#= btn_pdf #"},
                    //{field:'btn_ticket',title:'TICKET',width:'50px',template:"#= btn_ticket #"},
                    //{field:'btn_xml', title:'XML',width:'40px',template:"#= btn_xml #"},
                    //{field:'btn_cdr', title:'CDR',width:'40px',template:"#= btn_cdr #"},

                    //{field:'btn_mail',title:'EMAIL',width:'50px',template:"#= btn_mail #"},
                    {
                        title:'ACCIÓN',
                        width:'70px',
                        template:"#= btn_action #",
                        attributes: {
                          "class": "gridcell",
                          style: "overflow: inherit;position:relative;"
                        }
                    },
                   /* {field:'prod_eliminar', title:'&nbsp;',width:'100px',template:"#= prod_eliminar #"},*/
        ],
        dataBound:function(e){

            var grid = $("#gridSellIn").data("kendoGrid");
            var data = dataSource.data();
            $.each(data,function(e, row){
                if(row.anulado ==1)
                {
                    $('tr[data-uid="' + row.uid + '"] ').css("background-color", "rgb(241, 204, 206)");
                }
            });

            $("#buscar_comprobante").click(function(e){
                e.preventDefault();
                dataSource.read();
            });

            /*Imprimir ticket*/                        
            var PrintWindow;
            function openWindow(id) {
              PrintWindow = window.open('<?PHP echo base_url() ?>index.php/comprobantes/data_impresion_pos_ticket/'+id,'popup', 'width=400px,height=800px');
              setTimeout(function() {closeOpenedWindow();}, 400);
            }
            function closeOpenedWindow() {
              PrintWindow.close();
            }
            $('.print_ticket').click(function() {
                var _val = $(this).attr("idval");
                //javascript:window.open('<?PHP echo base_url() ?>index.php/comprobantes/show_ticket/'+_val+'','','width=750,height=600,scrollbars=yes,resizable=yes');
                
                var id= $(this).attr('idval');
                openWindow(id);
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
                javascript:window.open('<?PHP echo base_url() ?>index.php/comprobantes_orden_compras/pdfGeneraComprobanteOffLine/'+_val+'','','width=750,height=600,scrollbars=yes,resizable=yes');
                
            });
             $(".delete").click(function(e) {               
                var _val = $(this).attr("idval");
                if(confirm("¿ Esta seguro eliminar orden de compra ?")){
                   window.location.href = "<?PHP echo base_url() ?>index.php/comprobantes_orden_compras/eliminar/"+_val;
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

    /*drop list action*/
    

</script>
