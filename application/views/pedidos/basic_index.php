

<style>
    #refresh img{
        margin-left: 50px;
    }
</style>

<div class="container">
    <form>    
        <h2>Lista de Pedidos: <?php echo $empresa[0]['empresa']?></h2>

            <div class="row">
                <div class="col-xs-4">
                    <label>Cliente:</label><br>
                    <input type="text" class="form-control input-sm" id="cliente" name="cliente" placeholder="Cliente" value="<?= $cliente_select;?>">
                    <div id="data_cli">
                        <?php
                            if(isset($cliente_select_id) && ($cliente_select_id != '')){
                            echo '<input type="hidden" value="' . $cliente_select_id . '" name = "cliente_id" id = "cliente_id" >';
                            }
                        ?>
                    </div>
                </div> 
                <div class="col-xs-1">
                    <label>Correlativo</label>
                    <input type="text" class="form-control input-sm" id="correlativo" name="correlativo">
                </div>
                <div class="col-xs-2">
                    <label>Fecha</label>
                    <input type="text" class="form-control input-sm" id="fecha" name="fecha" >
                </div> 
                 <div class="col-xs-2">
                    <label>Vendedor</label>
                        <select class="form-control" name="vendedor" id="vendedor">
                                        <option value="">Seleccione vendedor</option>
                                        <?php foreach($vendedores as $v){?>
                                             $selected =  ($v->id == $_POST['vendedor']) ? 'SELECTED' : '';?>
                                          <option <?= $selected;?> value="<?php echo $v->id?>"><?php echo $v->nombre.' '.$v->apellido_paterno?></option>
                                        <?php }?>    
                            </select>
                     </div>
                <div class="col-xs-4">
                    <input type="button" class="btn btn-primary " id="btn_search" value="Buscar">
                    <input type="button" class="btn btn-primary " id="btn_limpiar" value="Limpiar">
                    <input type="hidden" value="<?php echo $empresa['id']?>" name="empresa_id" id="empresa_id">                      
                </div>             
            </div> 
    </form>
</div>
<div class="row" align="right" style="padding-right: 25%;">
    <br>
    <a href="<?PHP echo base_url(); ?>index.php/pedidos/nuevo/<?php echo $empresa['id']?>" class="btn btn-success">Nuevo Pedido</a>
    <a id="exportarExcel" class="btn btn-primary"><i class="glyphicon glyphicon-save"></i> EXCEL</a>
</div>

<p class="bg-info" style="font-weight: 600;color:#000;text-align: center;padding: 10px 0;font-size: 16px;margin-top:10px;">
    <?PHP echo $this->session->flashdata('mensaje'); ?>
</p>


<div class="container-fluid">
    <br>
    <div id="grid"></div>    
</div>
<!--<meta http-equiv="refresh" content="20">-->
<script src="<?PHP echo base_url(); ?>assets/js/funciones.js"></script>
<script type="text/javascript">

    $('#exportarExcel').click(function(){
                console.log($("#cliente_id").val());
                var cliente_id = ($("#cliente_id").val() =='' ) ? null :  $("#cliente_id").val();
                var correlativo = ($("#correlativo").val() =='' ) ? null :  $("#correlativo").val();
                var fecha = ( $("#fecha").val() == "") ?  null : $("#fecha").val();
                var vendedor = ( $("#vendedor").val() == "") ?  null : $("#vendedor").val();
              
                
                var url = '<?PHP echo base_url() ?>index.php/pedidos/exportarExcel?cliente=' + cliente_id + '&fecha=' +  fecha + '&correlativo=' + correlativo+'&vendedor='+vendedor;
                window.open(url, '_blank');

            });
    //Imprimir Boleto
    $(document).ready(function () {


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
        $("#correlativo").val("");
        $("#fecha").val("");
        $("#vendedor").val("");
        dataSource.read();
    });    
    // AUTOCOMPLETE CLIENTE
    $(document).on('ready',function() {
        $("#cliente").autocomplete( {
            source: '<?PHP echo base_url(); ?>index.php/comprobantes/buscador_cliente',
            minLength: 2,
            select: function(event, ui) {
                var data_cli ='<input type="hidden" value="' + ui.item.id + '" name = "cliente_id" id = "cliente_id" >';
                $('#data_cli').html(data_cli);
            }
        });

    // FECHA JAVASCRIPT
    $("#fecha").datepicker();
                     
    });  

    var dataSource = new kendo.data.DataSource({
             transport: {
                read: {
                    url:"<?php echo base_url()?>index.php/pedidos/getMainList/",
                    dataType:"json",
                    method:'post',
                    data:function(){
                        return {
                            cliente_search:function(){
                                return $("#cliente_id").val();
                            },
                            correlativo_search:function(){
                                return $("#correlativo").val();
                            },
                            fecha_search:function(){
                                return $("#fecha").val();
                            },
                            vendedor:function(){
                                return $("#vendedor").val();
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
                    {field:'notap_correlativo',title:'Nº PEDIDO',width:'60px'},
                    {field:'razon_social',title:'CLIENTE',width:'160px'},
                    {field:'notap_fecha',title:'FECHA',width:'60px'},
                    {field:'moneda',title:'MONEDA',width:'60px'},
                    {field:'notap_subtotal',title:'SUB TOTAL',width:'60px'},
                    {field:'notap_igv',title:'IGV',width:'40px'},
                    {field:'notap_total',title:'TOTAL',width:'60px'},
                    {field:'empleado',title:'VENDEDOR',width:'60px'},
                    {field:'estado',title:'ESTADO',width:'60px'},
                    {field:'boton_editar', title:'&nbsp;',width:'40px',template:"#= boton_editar #"},
                    {field:'boton_eliminar', title:'&nbsp;',width:'40px',template:"#= boton_eliminar #"},
                    {field:'boton_pdf', title:'&nbsp;',width:'40px',template:"#= boton_pdf #"},
                    //{field:'notap_total', title:'&nbsp;',width:'100px',template:"#= prod_eliminar #"},
        ],
        detailTemplate: '<div class="lista_notas"></div>',
        detailInit: detailInit,        
        dataBound:function(e){
            //modificar nota
            $(".btn-editar").click(function(e){
               var idNota = $(this).data('id');
               location.href="<?php echo base_url()?>index.php/pedidos/editar/"+idNota;
            });

             $(".btn-eliminar").click(function(e){
               var idNota = $(this).data('id');
               if(confirm("¿Eliminar nota de pedido?")){
                location.href="<?php echo base_url()?>index.php/pedidos/eliminar_nota/"+idNota;
               }
               
            });
            //descargar pdf de nota
           /* $(".descargar-pdf").click(function(e){
                e.preventDefault();
                var idNota = $(this).data('id');
                location.href="<?php echo base_url()?>index.php/notas/decargarPdf/"+idNota;
            });*/
            //editar producto
            /*$(".btn_eliminar_producto").click(function(e){
                e.preventDefault();
                var idProducto = $(this).data('id');
                var msg = $(this).data('msg');
                var url = '<?php echo base_url()?>index.php/productos/eliminar/'+idProducto
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
                                            toast('success', 1500, 'Producto eliminado');
                                            dataSource.read();
                                        }
                                        if(response.status == STATUS_FAIL)
                                        {
                                            toast('error', 1500 ,'No se puedo eliminar producto.');
                                        }
                                    }
                                });
                            }
                        },
                        cancel: function () {
                            
                        }
                    }
                });
            });*/                            
        }
    }); 

    function detailInit(e) {
        var detailRow = e.detailRow;

        detailRow.find(".lista_notas").kendoGrid({
            dataSource: {
                type: "json",
                transport: {
                    read:{
                        url:"<?php echo base_url()?>index.php/pedidos/getMainListDetail/",
                        dataType:"json",
                        method:'post',
                        data:function(){
                            return {
                                notap_id:e.data.notap_id
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
                { field: "notapd_descripcion", title:"PRODUCTO", width: "120px" },
                { field: "notapd_precio_unitario", title:"PRECIO UNITARIO", width: "50px" },
                { field: "notapd_cantidad", title:"CANTIDAD",width:"70px" },
                { field: "notapd_subtotal", title:"SUB TOTAL",width:"70px" }
            ],
            dataBound:function(e){
            }
        });
    }                   
</script>
