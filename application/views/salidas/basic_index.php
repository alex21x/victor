
<h2 align="center">Salidas</h2>
<br>
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <button id="btn_nuevo_salida" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal">Nuevo Salida</button>
             <a id="exportar_salida" class="btn btn-primary btn-sm">Exportar a Excel</a>
        </div>
        <div class="col-md-7 col-md-offset-1" >
            <div class="form-inline">              
                <div class="input-group" style="float:right;">
                    <input type="date" class="form-control" id="fecha">
                </div>                 
            </div>            
        </div>        
        

    </div>
    <br>
    <div id="grid"></div>
</div>
<!-- <?php echo base_url()?>index.php/productos/modificar/<?php echo $producto->prod_id?>-->
<script>
    var dataSource = new kendo.data.DataSource({
             transport: {
                read: {
                    url:"<?php echo base_url()?>index.php/salidas/getMainList/",
                    dataType:"json",
                    method:'post',
                    data:function(){
                        return {
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
                    {field:'sal_codigo',title:'CODIGO',width:'70px'},
                    {field:'sal_fecha',title:'FECHA', width:'70px'},
                    {field:'alm_nombre',title:'ALMACÉN', width:'130px'},
                    {field:'sal_observacion',title:'OBSERVACIONES',width:'180px'},
                    {field:'sal_editar', title:'&nbsp;',width:'50px',template:"#= sal_editar #"},
                    {field:'sal_eliminar', title:'&nbsp;',width:'50px',template:"#= sal_eliminar #"},
        ],
        detailTemplate: '<div class="lista_productos"></div>',
        detailInit: detailInit,
        dataBound:function(e){
            //modificar producto
            $(".btn_modificar_salida").click(function(e){
               var idSalida = $(this).data('id');
                $("#myModal").load('<?php echo base_url()?>index.php/salidas/editar/'+idSalida,{});
            });
            //editar producto
            $(".btn_eliminar_salida").click(function(e){
                e.preventDefault();
                var idSalida = $(this).data('id');
                var msg = $(this).data('msg');
                var url = '<?php echo base_url()?>index.php/salidas/eliminarSalida/'+idSalida
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
                                            toast('success', 1500, 'Salida eliminada');
                                            dataSource.read();
                                        }
                                        if(response.status == STATUS_FAIL)
                                        {
                                            toast('error', 1500 ,'No se puedo eliminar Salida.');
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
    //nuevo producto
    $("#btn_nuevo_salida").click(function(e){
        e.preventDefault();
        $("#myModal").load('<?php echo base_url()?>index.php/salidas/crear',{});
    }); 
    //buscar producto campo fecha
    $("#fecha").change(function(e){
        e.preventDefault();
        dataSource.read();
    });

    function detailInit(e) {
        var detailRow = e.detailRow;

        detailRow.find(".lista_productos").kendoGrid({
            dataSource: {
                type: "json",
                transport: {
                    read:{
                        url:"<?php echo base_url()?>index.php/salidas/getMainListDetail/",
                        dataType:"json",
                        method:'post',
                        data:function(){
                            return {
                                sal_id:e.data.sal_id
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
                { field: "prod_codigo", title:"CODIGO", width: "70px" },
                { field: "prod_nombre", title:"PRODUCTO", width: "120px" },
                { field: "sald_cantidad", title:"CANTIDAD",width:"70px" },
                //{ field: "sald_eliminar", title:"&nbsp;",width:"70px",template:"#= sald_eliminar #"}
            ],
            dataBound:function(e){
                $(".btn_eliminar_detalle").click(function(e){
                    e.preventDefault();
                    var idDetalle = $(this).data('id');
                    var msg = $(this).data('msg');
                    var url = '<?php echo base_url()?>index.php/salidas/eliminarDetalleSalida/'+idDetalle
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
                                                var childGrid = $(e.target).closest(".k-grid").data("kendoGrid");
                                                childGrid.dataSource.read();                                               
                                                //dataSource.read();
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
                    /*var _ingd = $(this).data('detalle');
                    var datos = {
                                  ingd:_ingd
                                };
                    $.ajax({
                        url:'<?php echo base_url()?>index.php/ingresos/eliminarDetalleIngreso',
                        dataType:'json',
                        method:'post',
                        data:datos,
                        success:function(response){

                        }
                    });*/
                });
            }
        });
    }    


</script>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        $('#exportar_salida').click(function() {
            var fecha =$('#fecha').val();            
            if(fecha ==''){
                fecha =0;
            }
            var url ='<?PHP echo base_url() ?>index.php/salidas/ExportarExcel/'+fecha+'/';
            window.open(url, '_blank');

      });

    });
</script>