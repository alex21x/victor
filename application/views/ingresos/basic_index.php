<h2 align="center">Ingresos</h2>
<br>
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <button id="btn_nuevo_ingreso" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal">Nuevo Ingreso</button>
            <a id="exportar_entrada" class="btn btn-primary btn-sm"> Exportar a Excel</a>
        </div>
        <div class="col-md-7 col-md-offset-1" >
            <div class="form-inline">
                <div class="input-group" style="float:right;">
                  <input type="text" class="form-control" id="search" placeholder="Buscar por proveedor">
                  <span class="input-group-btn">
                    <button class="btn btn-default" type="button" id="btn_buscar_proveedor"><span class="glyphicon glyphicon-search"></span></button>
                  </span>
                </div>                
                <div class="input-group" style="float:right;">
                    <input type="date" class="form-control" id="fecha">
                </div>                 
            </div>            
        </div>      
        
    </div>
    <br>
    <div id="grid"></div>
</div>
<script src="<?PHP echo base_url(); ?>assets/js/funciones.js"></script>
<!-- <?php echo base_url()?>index.php/productos/modificar/<?php echo $producto->prod_id?>-->

<script type="text/javascript">
    jQuery(document).ready(function($) {
        $('#exportar_entrada').click(function() {
            var fecha =$('#fecha').val();

            var proveedor = $('#search').val();
            
            if(fecha ==''){
                fecha =0;
            }
            if(proveedor ==''){
                proveedor =0;
            }           
            var url ='<?PHP echo base_url() ?>index.php/ingresos/ExportarExcel/'+fecha+'/'+proveedor+'/';
            window.open(url, '_blank');

        });

    });
</script>
<script>
    var dataSource = new kendo.data.DataSource({
             transport: {
                read: {
                    url:"<?php echo base_url()?>index.php/ingresos/getMainList/",
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
                    {field:'ing_codigo',title:'CODIGO',width:'70px'},
                    {field:'ing_fecha',title:'FECHA', width:'70px'},
                    {field:'alm_nombre',title:'ALMACÉN', width:'130px'},
                    {field:'prov_razon_social',title:'PROVEEDOR',width:'130px'},
                    {field:'ing_observaciones',title:'OBSERVACIONES',width:'180px'},
                    {field:'ing_editar', title:'&nbsp;',width:'50px',template:"#= ing_editar #"},
                    {field:'ing_eliminar', title:'&nbsp;',width:'50px',template:"#= ing_eliminar #"},
        ],
        detailTemplate: '<div class="lista_productos"></div>',
        detailInit: detailInit,
        dataBound:function(e){
            //modificar producto
            $(".btn_modificar_ingreso").click(function(e){
               var idIngreso = $(this).data('id');
                $("#myModal").load('<?php echo base_url()?>index.php/ingresos/editar/'+idIngreso,{});
            });
            //editar producto
            $(".btn_eliminar_ingreso").click(function(e){
                e.preventDefault();
                var idIngreso = $(this).data('id');
                var msg = $(this).data('msg');
                var url = '<?php echo base_url()?>index.php/ingresos/eliminarIngreso/'+idIngreso
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
                                            toast('success', 1500, 'Ingreso eliminado');
                                            dataSource.read();
                                        }
                                        if(response.status == STATUS_FAIL)
                                        {
                                            toast('error', 1500 ,'No se puedo eliminar Ingreso.');
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
    $("#btn_nuevo_ingreso").click(function(e){
        e.preventDefault();
        $("#myModal").load('<?php echo base_url()?>index.php/ingresos/crear',{});
    }); 
    //buscar producto
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
                        url:"<?php echo base_url()?>index.php/ingresos/getMainListDetail/",
                        dataType:"json",
                        method:'post',
                        data:function(){
                            return {
                                ing_id:e.data.ing_id
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
                { field: "ingd_cantidad", title:"CANTIDAD",width:"70px" },
               // { field: "ingd_eliminar", title:"&nbsp;",width:"70px",template:"#= ingd_eliminar #"}
            ],
            dataBound:function(e){
                $(".btn_eliminar_detalle").click(function(e){
                    e.preventDefault();
                    var idDetalle = $(this).data('id');
                    var msg = $(this).data('msg');
                    var url = '<?php echo base_url()?>index.php/ingresos/eliminarDetalleIngreso/'+idDetalle
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