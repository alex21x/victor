
<style>
    #refresh img{
        margin-left: 50px;
    }
</style>
<p class="bg-info">
    <?PHP echo $this->session->flashdata('mensaje'); ?>
</p>
<div class="container">
    <form>    
        <h2>Lista de Guias: <?php echo $empresa[0]['empresa']?></h2>

            <div class="row">
                <div class="col-xs-12 col-md-2 col-lg-2">
                    <br>
                    <a href="<?PHP echo base_url(); ?>index.php/guias/nuevo/<?php echo $empresa['id']?>" class="btn btn-success btn-block">Nueva Guia</a>
                    <!--<a id="exportarExcel" class="btn btn-primary"> Exportar a Excel </a>-->
                </div>    
                <div class="col-xs-12 col-md-4 col-lg-4">
                    <label>Cliente:</label><br>
                    <input type="text" class="form-control input-sm" id="cliente" name="cliente" placeholder="Cliente" >
                    <div id="data_cli">
                    </div>
                </div>                 
                <div class="col-xs-12 col-md-4 col-lg-4">
                    <label>Factura</label>
                    <input type="text" class="form-control input-sm" id="factura" name="factura">
                </div>
                <div class="col-xs-12 col-md-4 col-lg-4" style="display: none;">
                    <label>Fecha</label>
                    <input type="text" class="form-control input-sm" id="fecha" name="fecha" >
                </div>                 
                <div class="col-xs-12 col-md-2 col-lg-2">
                    <br>
                    <input type="button" class="btn btn-primary" id="btn_search" value="Buscar">
                    <input type="button" class="btn btn-primary" id="btn_limpiar" value="Limpiar">
                    <input type="hidden" value="<?php echo $empresa['id']?>" name="empresa_id" id="empresa_id">                      
                </div> 
                        
            </div> 
    </form>
</div>

<div class="container-fluid">
    <br>
    <div id="grid"></div>    
</div>
<!--<meta http-equiv="refresh" content="20">-->
<script src="<?PHP echo base_url(); ?>assets/js/funciones.js"></script>
<script type="text/javascript">
    //Imprimir Boleto
   jQuery(document).ready(function($) {
        $('#exportarExcel').click(function() {
            var cliente =$('#cliente').val();
            var factura = $('#factura').val();
            var fecha = $('#fecha').val();;

            if(cliente  ==''){
                cliente =0;
            }
            if(factura ==''){
                factura =0;
            }
            if(fecha ==''){
                fecha = 0;
            }
            if(fecha ==''){
                fecha = 0;
            }            
            var url ='<?PHP echo base_url() ?>index.php/guias/ExportarExcel/'+cliente+'/'+factura+'/'+fecha;
            window.open(url, '_blank');

        });

    });
    /*button buscar*/
    $("#btn_search").click(function(e){
        e.preventDefault();
        dataSource.read();
    });
    /*button limpiar*/
    $("#btn_limpiar").click(function(e){
        e.preventDefault();
        $("#cliente_id").val("");
        $("#cliente").val("");
        $("#factura").val("");
        $("#fecha").val("");
        dataSource.read();
    });    
    // AUTOCOMPLETE CLIENTE
    $(document).on('ready',function() {
        /*$("#cliente").autocomplete( {
            source: '<?PHP echo base_url(); ?>index.php/guias/buscadorCliente',
            minLength: 2,
            select: function(event, ui) {
            	console.log(ui.item.razon_social);
                //var data_prov ='<input type="hidden" value="' + ui.item.cli_id + '" name = "cli_id" id = "cli_id" >';
                //$("#cliente").val(ui.item.razon_social);
                //$('#data_cli').html(data_prov);
            }
        });*/
        $('#cliente').autocomplete({
            source : '<?PHP echo base_url();?>index.php/guias/buscadorCliente',
            minLength : 2,
            select : function (event,ui){                                
                var data_cli = '<input type="hidden" value="'+ ui.item.id + '" name = "cliente_id" id = "cliente_id" >';
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
    $("#fecha").datepicker();
                     
    });  

    var dataSource = new kendo.data.DataSource({
             transport: {
                read: {
                    url:"<?php echo base_url()?>index.php/guias/getMainList/",
                    dataType:"json",
                    method:'post',
                    data:function(){
                        return {
                            cliente:function(){
                                return $("#cliente").val();
                            },
                            factura:function(){
                                return $("#factura").val();
                            },
                            fecha:function(){
                                return $("#fecha").val();
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
                    //{field:'numero_factura',title:'ADELANTO PEDIDO',width:'60px'},
                    {field:'numero_factura',title:'SERIE-NUMERO',width:'60px'},
                    {field:'fecha_inicio_traslado',title:'FECHA',width:'60px'},
                    {field:'descripcion_traslado',title:'MOTIVO TRASLADO',width:'80px'},
                    {field:'destinatario_razon_social',title:'CLIENTE',width:'80px'},
                    {field:'factura',title:'FACTURA',width:'80px'},
                    //{field:'descripcion_traslado',title:'MOTIVO TRASLADO',width:'80px'},
                    //{field:'enviar_sunat', title:'&nbsp;',width:'30px',template:"#= enviar_sunat #"},
                    {field:'btn_action', title: 'ESTADO', width: '100px', template: "#= btn_action #", attributes:{"class": "gridcell",style: "overflow: inherit;position: relative"}},
                    {field:'boton_pdf', title:'PDF',width:'30px',template:"#= boton_pdf #"},
                    {field:'btn_ticket', title:'TICKET',width:'30px',template:"#= btn_ticket #"},
                    //{field:'boton_editar', title:'&nbsp;',width:'30px',template:"#= boton_editar #"},
                    //{field:'boton_eliminar', title:'&nbsp;',width:'30px',template:"#= boton_eliminar #"},                    
                    //{field:'notap_total', title:'&nbsp;',width:'100px',template:"#= prod_eliminar #"},
        ],
        detailTemplate: '<div class="lista_guias"></div>',
        detailInit: detailInit,        
        dataBound:function(e){
            //modificar guia
            $(".btn-editar").click(function(e){
               var idGuia = $(this).data('id');
               location.href="<?php echo base_url()?>index.php/guias/editar/"+idGuia;
            });
            //eliminar guia
            $(".btn-eliminar").click(function(e){
                var idGuia = $(this).data('id');
                var msg = $(this).data('msg');
                var url = '<?php echo base_url()?>index.php/guias/eliminar/'+idGuia
                $.confirm({
                    title: 'Confirmar',
                    content: msg,
                    buttons: {
                        confirm:{
                            text:'aceptar',
                            btnClass: 'btn-blue',
                            action:function(){
                                $.ajax({
                                    url:url,
                                    dataType:'json',
                                    method:'get',
                                    success:function(response){
                                        if(response.status == STATUS_OK)
                                        {
                                            toast('success', 1500, 'Guia eliminada');
                                            dataSource.read();
                                        }
                                        if(response.status == STATUS_FAIL)
                                        {
                                            toast('error', 1500 ,'No se puedo eliminar guia.');
                                        }
                                    }
                                });
                            }
                        },
                        cancel: function () {
                            
                        }
                    }
                });                
            });                            
        }
    }); 

    function detailInit(e) {
        var detailRow = e.detailRow;

        detailRow.find(".lista_guias").kendoGrid({
            dataSource: {
                type: "json",
                transport: {
                    read:{
                        url:"<?php echo base_url()?>index.php/guias/getMainListDetail/",
                        dataType:"json",
                        method:'post',
                        data:function(){
                            return {
                                guia_id:e.data.guia_id
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
                { field: "codigo", title:"CODIGO", width: "50px" },
                { field: "descripcion", title:"PRODUCTO", width: "100px" },
                { field: "cantidad", title:"CANTIDAD",width:"70px" }
            ],
            dataBound:function(e){
            }
        });
    }    


    function send_guiaRemision(guia_id){
         toast("info",10000, 'Enviando a SUNAT . . .');
            $.getJSON("<?PHP echo base_url(); ?>index.php/guias/getDatosXML",{guia_id})
            .done(function(json){
                var datosJSON = JSON.stringify(json);
                $.post("<?php echo RUTA_API?>index.php/Sunat/send_guiaRemision",{datosJSON})
                 .done(function(res){
                    var response;
                    try{
                             response = JSON.parse(res);
                             if(response.res == 1){
                                     $.post("<?PHP echo base_url(); ?>index.php/guias/updateEstadoCDR",{guia:guia_id,firma:response.firma})
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


    function send_guiaAnulacion(guia_id){
            $.getJSON("<?PHP echo base_url(); ?>index.php/guias/getDatosAnulacion",{guia_id})
             .done(function(json){                
                    toast("info",10000, 'Enviando COMUNICACIÓN DE BAJA . . .');
                    var datosJSON = JSON.stringify(json);
                    $.post("<?php echo RUTA_API?>index.php/Sunat/send_guiaAnulacion",{datosJSON})
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
                        } catch(e){
                                console.log(res);
                                toast("error",6000,res);
                              
                        }                            
                    })
            });
        }
</script>
