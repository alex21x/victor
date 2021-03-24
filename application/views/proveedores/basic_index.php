
<h2 align="center">Proveedores</h2>
<br>
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <button id="btn_nuevo_proveedor" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal" data-backdrop="static" data-keyboard="false">Nuevo Proveedor</button>
            <a class="btn btn-primary" id="exportar_proveedor" href="#"> Exportar Excel </a>
        </div>
        <div class="col-md-4 col-md-offset-4">
            <div class="form">
                <div class="input-group">
                  <input type="text" class="form-control" id="search" placeholder="Buscar por ruc o razon social">
                  <span class="input-group-btn">
                    <button class="btn btn-default" type="button" id="btn_buscar_proveedor"><span class="glyphicon glyphicon-search"></span></button>
                  </span>
                </div>            
            </div>            
        </div>        
        

    </div>
    <br>
    <div id="grid"></div>
</div>
<!-- <?php echo base_url()?>index.php/productos/modificar/<?php echo $producto->prod_id?>-->
<script type="text/javascript">
    $('#exportar_proveedor').click(function() {
        var proveedor =$('#search').val();        
        if(proveedor ==''){
            proveedor = 0;
        }       
        var url ='<?PHP echo base_url() ?>index.php/proveedores/ExportarExcel/'+proveedor+'/';
        window.open(url, '_blank');

    });
</script>

<script>
    var dataSource = new kendo.data.DataSource({
             transport: {
                read: {
                    url:"<?php echo base_url()?>index.php/proveedores/getMainList/",
                    dataType:"json",
                    method:'post',
                    data:function(){
                        return {
                            search:function(){
                                return $("#search").val();
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
                    {field:'prov_ruc',title:'RUC',width:'80px'},
                    {field:'prov_razon_social',title:'RAZÓN SOCIAL', width:"180px"},
                    {field:'prov_direccion',title:'DIRECCIÓN',width:'100px'},
                    {field:'prov_celular',title:'TELÉFONO',width:'80px'},
                    {field:'prov_editar', title:'&nbsp;',width:'70px',template:"#= prov_editar #"},
                    {field:'prov_eliminar', title:'&nbsp;',width:'70px',template:"#= prov_eliminar #"},
        ],
        dataBound:function(e){
            //modificar producto
            $(".btn_modificar_proveedor").click(function(e){
               var idProveedor = $(this).data('id');
                $("#myModal").load('<?php echo base_url()?>index.php/proveedores/editar/'+idProveedor,{});
            });
            //editar producto
            $(".btn_eliminar_proveedor").click(function(e){
                e.preventDefault();
                var idProveedor = $(this).data('id');
                var msg = $(this).data('msg');
                var url = '<?php echo base_url()?>index.php/proveedores/eliminar/'+idProveedor
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
                                            toast('success', 1500, 'Proveedor eliminado');
                                            dataSource.read();
                                        }
                                        if(response.status == STATUS_FAIL)
                                        {
                                            toast('error', 1500 ,'No se puedo eliminar proveedor.');
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
    //nuevo proveedor
    $("#btn_nuevo_proveedor").click(function(e){
        e.preventDefault();
        $("#myModal").load('<?php echo base_url()?>index.php/proveedores/crear',{});

    }); 
    //buscar proveedor
    $("#btn_buscar_proveedor").click(function(e){
        e.preventDefault();
        dataSource.read();
    });
    //buscar proveedor por campo texto
    $("#search").keyup(function(e){
        e.preventDefault();
        var enter = 13;
        if(e.which == enter)
        {
            dataSource.read();
        };
    })


</script>