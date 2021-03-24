<h2 align="center"><strong>Marcas</strong></h2>
<br>
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <button id="btn_nuevo_marca" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal">Nueva Marcas</button>
        </div>
        <div class="col-md-4 col-md-offset-4">
            <div class="form">
                <div class="input-group">
                  <input type="text" class="form-control" id="search" placeholder="Buscar por nombre">
                  <span class="input-group-btn">
                    <button class="btn btn-default" type="button" id="btn_buscar_marca"><span class="glyphicon glyphicon-search"></span></button>
                  </span>
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
                    url:"<?php echo base_url()?>index.php/marcas/getMainList/",
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
                    {field:'mar_nombre',title:'NOMBRE',width:'150px'},
                    {field:'mar_editar', title:'&nbsp;',width:'60px',template:"#= mar_editar #"},
                    {field:'mar_eliminar', title:'&nbsp;',width:'60px',template:"#= mar_eliminar #"},
        ],
        dataBound:function(e){
            //modificar producto
            $(".btn_modificar_marca").click(function(e){
               var idMarca = $(this).data('id');               
                $("#myModal").load('<?php echo base_url()?>index.php/marcas/editar/'+idMarca,{});
            });
            //editar producto
            $(".btn_eliminar_marca").click(function(e){
                e.preventDefault();
                var idMarca = $(this).data('id');
                var msg = $(this).data('msg');
                var url = '<?php echo base_url()?>index.php/marcas/eliminar/'+idMarca
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
                                            toast('success', 1500, 'marca eliminada');
                                            dataSource.read();
                                        }
                                        if(response.status == STATUS_FAIL)
                                        {
                                            toast('error', 2000 ,'No se puedo eliminar la marca porque tiene productos agregados.');
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
    //nuevo seccion
    $("#btn_nuevo_marca").click(function(e){
        e.preventDefault();
        $("#myModal").load('<?php echo base_url()?>index.php/marcas/crear',{});
    }); 
    //buscar seccion
    $("#btn_buscar_marca").click(function(e){
        e.preventDefault();
        dataSource.read();
    });

    //buscar seccion por campo texto
    $("#search").keyup(function(e){
        e.preventDefault();
        var enter = 13;
        if(e.which == enter)
        {
            dataSource.read();
        };
    })


</script>