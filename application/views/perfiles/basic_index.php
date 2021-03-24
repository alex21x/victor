<h2 align="center"><strong>Perfiles</strong></h2>
<br>
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <button id="btn_nuevo_perfil" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal">Nueva perfil </button>
        </div>
        <div class="col-md-4 col-md-offset-4">
            <div class="form">
                <div class="input-group">
                  <input type="text" class="form-control" id="search" placeholder="Buscar por nombre">
                  <span class="input-group-btn">
                    <button class="btn btn-default" type="button" id="btn_buscar_perfil"><span class="glyphicon glyphicon-search"></span></button>
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
                    url:"<?php echo base_url()?>index.php/perfiles/getMainList/",
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
                    {field:'tipo_empleado',title:'PERFIL',width:'150px'},
                    {field:'per_editar', title:'&nbsp;',width:'60px',template:"#= per_editar #"},
                    {field:'per_eliminar', title:'&nbsp;',width:'60px',template:"#= per_eliminar #"},
        ],
        dataBound:function(e){
            //modificar producto
            $(".btn_modificar_perfil").click(function(e){
               var idPerfil = $(this).data('id');               
                $("#myModal").load('<?php echo base_url()?>index.php/perfiles/editar/'+idPerfil,{});
            });
            //editar producto
            $(".btn_eliminar_perfil").click(function(e){
                e.preventDefault();
                var idPerfil = $(this).data('id');
                var msg = $(this).data('msg');
                var url = '<?php echo base_url()?>index.php/perfiles/eliminar/'+idPerfil
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
                                            toast('success', 1500, 'perfil eliminado');
                                            dataSource.read();
                                        }
                                        if(response.status == STATUS_FAIL)
                                        {
                                            toast('error', 2000 ,'No se puedo eliminar perfil.');
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
    $("#btn_nuevo_perfil").click(function(e){
        e.preventDefault();
        $("#myModal").load('<?php echo base_url()?>index.php/perfiles/crear',{});
    }); 
    //buscar seccion
    $("#btn_buscar_perfil").click(function(e){
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