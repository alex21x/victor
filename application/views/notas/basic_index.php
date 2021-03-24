<style>
    #refresh img{
        margin-left: 50px;
    }
    #btn_notaPedido{
        display: none;
    }
</style>
<div class="container">
    <form id="formNotaVenta">    
        <h2>Lista de Notas: <?php echo $empresa[0]['empresa']?></h2>
            <div class="row">
                <div class="col-xs-6 col-md-6 col-lg-4">
                    <label>Fecha de Inicio:</label><br>
                    <input class="form-control input-sm" type="text" name="fecha_inicio" id="fecha_inicio" value="<?PHP
                        if(isset($_POST['fecha_inicio'])){
                            echo $_POST['fecha_inicio'];
                        }else{
                            //echo date('d-m-Y');
                        }?>" placeholder="Desde">
                </div>
                <div class="col-xs-6 col-md-6 col-lg-4">
                    <label>Fecha de Fin:</label><br>
                    <input class="form-control input-sm" type="text" name="fecha_fin" id="fecha_fin" value="<?PHP
                        if(isset($_POST['fecha_fin'])){
                            echo $_POST['fecha_fin'];
                        }else{
                            //echo date('d-m-Y');
                        }?>" placeholder="Hasta">
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-md-6 col-lg-4">
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
                <div class="col-xs-6 col-md-6 col-lg-2">
                    <label>Correlativo</label>
                    <input type="text" class="form-control input-sm" id="correlativo" name="correlativo">
                </div>
                <div class="col-xs-6 col-md-6 col-lg-2">
                    <label>Usuario</label>
                    <select class="form-control" id="vendedor_id">
                        <option value="">Seleccionar</option>
                        <?php
                        foreach ($empleados as $value_empleado){?>
                        <option value="<?php echo $value_empleado['id']?>"><?php echo $value_empleado['apellido_paterno']." ".$value_empleado['apellido_materno'].", ".$value_empleado['nombre'] ?></option>
                        <?php
                           }
                        ?>
            </select>
                </div> 
                <div class="col-xs-12 col-md-6 col-lg-4">
                    <br>
                    <input type="button" class="btn btn-primary col-xs-5 " id="btn_search" value="Buscar">
                    <input type="button" class="btn btn-primary col-xs-5 col-xs-offset-1" id="btn_limpiar" value="Limpiar">
                    <input type="hidden" value="<?php echo $empresa['id']?>" name="empresa_id" id="empresa_id">                      
                    <input type="hidden" value="<?php echo $notap_id;?>" name="notap_id" id="notap_id">
                </div>
                <div class="col-xs-12 col-md-8 col-lg-8"><br>
                    <div id="totalRows"></div>
                </div>            
        <div class="col-xs-12 col-md-12 col-lg-4">
            <br>
            <a  href="<?PHP echo base_url(); ?>index.php/notas/nuevo/<?php echo $empresa['id']?>" class="btn btn-success col-xs-5">Nueva Nota</a>
            <a id="exportarExcel" class="btn btn-primary col-xs-5 col-xs-offset-1"><i class="glyphicon glyphicon-save"></i> Reporte Detallado</a>
        </div>
        <div class="col-lg-4">            
        </div>
        <div class="col-lg-4">
        </div>
        <div class="col-xs-12 col-md-12 col-lg-4" >
            <br>
            <a id="exportarExcel_rd" class="btn btn-primary col-xs-5"><i class="glyphicon glyphicon-save"></i> Reporte Listado</a>                    
        </div>
    </div>
    </form>
</div>
<p class="bg-info" style="font-weight: 600;color:#000;text-align: center;padding: 10px 0;font-size: 16px;margin-top:10px;">
    <?PHP echo $this->session->flashdata('mensaje'); ?>
</p>


<div class="container-fluid">
    <button type="button" id="btn_notaPedido" data-toggle="modal" data-target="#myModal">MODAL</button>
    <br>
    <div class="col-xs-12 col-md-12 col-lg-12">
        <div id="grid"></div> 
    </div>  
</div>
<!--<meta http-equiv="refresh" content="20">-->
<script src="<?PHP echo base_url(); ?>assets/js/funciones.js"></script>
<script type="text/javascript">

    $('#exportarExcel').click(function(){
                console.log($("#cliente_id").val());
                var cliente_id = ($("#cliente_id").val() =='' ) ? null :  $("#cliente_id").val();
                var correlativo = ($("#correlativo").val() =='' ) ? null :  $("#correlativo").val();
                var fecha_inicio = ( $("#fecha_inicio").val() == "") ?  null : $("#fecha_inicio").val();
                var fecha_fin = ( $("#fecha_fin").val() == "") ?  null : $("#fecha_fin").val();
                var vendedor_id = $("#vendedor_id").val();
                                
                    var url = '<?PHP echo base_url() ?>index.php/notas/exportarExcel?cliente=' + cliente_id + '&fecha_inicio=' +  fecha_inicio + '&fecha_fin=' +  fecha_fin + '&correlativo=' + correlativo + '&vendedor_id=' + vendedor_id;                            
                
                window.open(url, '_blank');
            });

     $('#exportarExcel_rd').click(function(){
                console.log($("#cliente_id").val());
                var cliente_id = ($("#cliente_id").val() =='' ) ? null :  $("#cliente_id").val();
                var correlativo = ($("#correlativo").val() =='' ) ? null :  $("#correlativo").val();
                var fecha_inicio = ( $("#fecha_inicio").val() == "") ?  null : $("#fecha_inicio").val();
                var fecha_fin = ( $("#fecha_fin").val() == "") ?  null : $("#fecha_fin").val();
                var vendedor_id = $("#vendedor_id").val();
                                
                    var url = '<?PHP echo base_url() ?>index.php/notas/exportarExcel_rd?cliente=' + cliente_id + '&fecha_inicio=' +  fecha_inicio + '&fecha_fin=' +  fecha_fin + '&correlativo=' + correlativo + '&vendedor_id=' + vendedor_id;                            
                
                window.open(url, '_blank');
            });

    
    /*button buscar*/
    $("#btn_search").click(function(e){
        e.preventDefault();
        dataSource.read();
    });
    /*button limpiar*/
    $("#btn_limpiar").click(function(e){
        e.preventDefault();
        $("#formNotaVenta")[0].reset();
        $("#cliente_id").val('');
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
    $("#fecha_inicio").datepicker();
    $("#fecha_fin").datepicker();
                     
    });  

    var dataSource = new kendo.data.DataSource({
             transport: {
                read: {
                    url:"<?php echo base_url()?>index.php/notas/getMainList/",
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
                            vendedor_id:function(){
                            return $("#vendedor_id").val();
                            },
                            fecha_inicio:function(){
                            return $("#fecha_inicio").val();
                            },
                            fecha_fin:function(){
                            return $("#fecha_fin").val();
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
                    {field:'ruc',title:'RUC/DNI',width:'70px'},
                    {field:'notap_fecha',title:'FECHA',width:'60px'},                    
                    {field:'moneda',title:'MONEDA',width:'60px'},
                    //{field:'notap_subtotal',title:'SUB TOTAL',width:'60px'},
                    //{field:'notap_igv',title:'IGV',width:'40px'},
                    {field:'notap_total',title:'TOTAL',width:'60px'},
                    {field:'empleado',title:'USUARIO',width:'60px'},
                    {field:'boton_editar', title:'Editar',width:'40px',template:"#= boton_editar #"},
                    
                    <?PHP if($this->session->userdata('accesoEmpleado') == ''){?>
                    {field:'boton_eliminar', title:'Eliminar',width:'40px',template:"#= boton_eliminar #"},                    
                    <?PHP }?>
                    {field:'btn_ticket', title:'Ticket',width:'40px',template:"#= btn_ticket #"},
                    {field:'boton_pdf', title:'A4',width:'40px',template:"#= boton_pdf #"},
                    {field:'boton_popup', title:'ENVIAR',width:'40px',template:"#= boton_popup #"},
                    {field:'boton_cTributario', title:'Convertir Tributario',width:'60px',template:"#= boton_cTributario #"},
                    //{field:'notap_total', title:'&nbsp;',width:'100px',template:"#= prod_eliminar #"},
        ],
        detailTemplate: '<div class="lista_notas"></div>',
        detailInit: detailInit,        
        dataBound:function(e){

            if($("#notap_id").val() != '')
            $('#btn_notaPedido').trigger('click');

            var grid = $("#gridSellIn").data("kendoGrid");
            var data = dataSource.data();
            $.each(data,function(e, row){
                if(row.notap_estado == 3)
                {
                    $('tr[data-uid="' + row.uid + '"] ').css("background-color", "rgb(241, 204, 206)");                    
                }
            });

            //TOTAL DE COMPROBANTES 17-02-2021 - ALEXANDER FERNANDEZ
            var totalRecords = dataSource.total();
            $("#totalRows").html('');
            $("#totalRows").html('<p class="bg-primary" style="text-align:center;font-size:16px;"><b>TOTAL DE COMPROBANTES: '+totalRecords+'</b></p>');

            //modificar nota
            $(".btn-editar").click(function(e){
               var idNota = $(this).data('id');
               location.href="<?php echo base_url()?>index.php/notas/editar/"+idNota;
            });

             $(".btn-eliminar").click(function(e){
               var idNota = $(this).data('id');
               if(confirm("¿Eliminar nota de pedido?")){
                location.href="<?php echo base_url()?>index.php/notas/eliminar_nota/"+idNota;
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
                        url:"<?php echo base_url()?>index.php/notas/getMainListDetail/",
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

    //MODAL DE ENVIO DE NOTAPEDIDO 03-08-2020 ALEXANDER FERNANDEZ
    $(document).on('click',"#btn_notaPedido",function(){

        //alert(123123);
        var notap_id =  $("#notap_id").val();
        $("#myModal").load('<?= base_url()?>index.php/notas/modal_envio_notaPedido/'+notap_id,{}); 

    });                      
</script>
