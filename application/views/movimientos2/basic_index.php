<h2 align="center">Movimientos</h2>
<br>
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <button id="btn_nuevo_movimiento" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal">Nuevo Movimiento</button>
             <a id="exportar_movimiento" class="btn btn-primary btn-sm"> Exportar a excel </a>
        </div>
        <div class="col-md-7 col-md-offset-1" >
            <div class="form-inline">
                <!--<div class="input-group" style="float:right;">
                  <input type="text" class="form-control" id="search" placeholder="Buscar por proveedor">
                  <span class="input-group-btn">
                    <button class="btn btn-default" type="button" id="btn_buscar_proveedor"><span class="glyphicon glyphicon-search"></span></button>
                  </span>
                </div>-->                
                <div class="input-group" style="float:right;">
                    <input type="date" class="form-control" id="fecha">
                </div>                 
            </div>            
        </div>        
        

    </div>
    <br>
    <div id="grid"></div>
</div>
<script>
    var dataSource = new kendo.data.DataSource({
             transport: {
                read: {
                    url:"<?php echo base_url()?>index.php/movimientos/getMainList/",
                    dataType:"json",
                    method:'post',
                    data:function(){
                        return {
                            proveedor:function(){
                                return $("#search").val();
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
                    {field:'mov_codigo',title:'CODIGO',width:'70px'},
                    {field:'mov_fecha',title:'FECHA', width:'70px'},
                    {field:'origen',title:'ORIGEN', width:'130px'},
                    {field:'destino',title:'DESTINO',width:'130px'},
                    {field:'mov_observacion',title:'OBSERVACIONES',width:'180px'},
                    {field:'mov_editar', title:'&nbsp;',width:'50px',template:"#= mov_editar #"},
                    {field:'mov_eliminar', title:'&nbsp;',width:'50px',template:"#= mov_eliminar #"},
        ],
        detailTemplate: '<div class="lista_productos"></div>',
        detailInit: detailInit,
        dataBound:function(e){
            //modificar producto
            $(".btn_modificar_movimiento").click(function(e){
               var idMovimiento = $(this).data('id');
                $("#myModal").load('<?php echo base_url()?>index.php/movimientos/editar/'+idMovimiento,{});
            });
            //editar producto
            $(".btn_eliminar_movimiento").click(function(e){
                e.preventDefault();
                var idMovimiento = $(this).data('id');
                var msg = $(this).data('msg');
                var url = '<?php echo base_url()?>index.php/movimientos/eliminarMovimiento/'+idMovimiento
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
                                            toast('success', 1500, 'Movimiento eliminado');
                                            dataSource.read();
                                        }
                                        if(response.status == STATUS_FAIL)
                                        {
                                            toast('error', 1500 ,'No se puedo eliminar Movimiento.');
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
    //nuevo movimiento
    $("#btn_nuevo_movimiento").click(function(e){
        e.preventDefault();
        $("#myModal").load('<?php echo base_url()?>index.php/movimientos/crear',{});
    }); 
    //buscar proveedor
    $("#btn_buscar_proveedor").click(function(e){
        e.preventDefault();
        dataSource.read();
    });
    //buscar producto por campo texto
    $("#search").keyup(function(e){
        e.preventDefault();
        var enter = 13;
        if(e.which == enter)
        {
            dataSource.read();
        };
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
                        url:"<?php echo base_url()?>index.php/movimientos/getMainListDetail/",
                        dataType:"json",
                        method:'post',
                        data:function(){
                            return {
                                mov_id:e.data.mov_id
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
                { field: "movd_cantidad", title:"CANTIDAD",width:"70px" },
                //{ field: "movd_eliminar", title:"&nbsp;",width:"70px",template:"#= movd_eliminar #"}
            ],
            dataBound:function(e){
                $(".btn_eliminar_detalle").click(function(e){
                    e.preventDefault();
                    var idDetalle = $(this).data('id');
                    var msg = $(this).data('msg');
                    var url = '<?php echo base_url()?>index.php/movimientos/eliminarDetalleMovimiento/'+idDetalle
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
                });
            }
        });
    }    


</script>

<script type="text/javascript">

    jQuery(document).ready(function($) {
        $('#exportar_movimiento').click(function() {        
            var fecha =$('#fecha').val();            
            if(fecha ==''){
                fecha =0;
            }            
            var url ='<?PHP echo base_url() ?>index.php/movimientos/ExportarExcel/'+fecha+'/';
            window.open(url, '_blank');

        });

    });
</script>  