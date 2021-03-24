<h2 align="center"><strong>PSE/TOKENS</strong></h2>
<br>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <button id="btn_nuevo_pseToken" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal">Nueva pse/Token</button>
        </div>
        <div class="col-md-4 col-md-offset-4">
            <div class="form">
                <div class="input-group">
                  <input type="text" class="form-control" id="search" placeholder="Buscar por nombre">
                  <span class="input-group-btn">
                    <button class="btn btn-default" type="button" id="btn_buscar_pseToken"><span class="glyphicon glyphicon-search"></span></button>
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
                    url:"<?php echo base_url()?>index.php/pse_token/getMainList/",
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
                    {field:'pst_id',title:'CODIGO',width:'150px'},
                    {field:'alm_nombre',title:'ALMACEN',width:'150px'},
                    {field:'pst_ruta',title:'RUTA',width:'150px'},
                    {field:'pst_token',title:'TOKEN',width:'150px'},                    
                    {field:'pst_editar', title:'&nbsp;',width:'60px',template:"#= pst_editar #"},
                    {field:'pst_eliminar', title:'&nbsp;',width:'60px',template:"#= pst_eliminar #"},
        ],
        dataBound:function(e){
            //modificar pseToken
            $(".btn_modificar_pseToken").click(function(e){
               var idPseToken = $(this).data('id');
                $("#myModal").load('<?php echo base_url()?>index.php/pse_token/editar/'+idPseToken,{});
            });
            //editar producto
            $(".btn_eliminar_pseToken").click(function(e){
                e.preventDefault();
                var idPseToken = $(this).data('id');
                var msg = $(this).data('msg');
                var url = '<?php echo base_url()?>index.php/pse_token/eliminar/'+idPseToken
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
                                            toast('success', 1500, 'pseToken eliminado');
                                            dataSource.read();
                                        }
                                        if(response.status == STATUS_FAIL)
                                        {
                                            toast('error', 2000 ,'No se puedo eliminar la pseToken porque tiene pseToken agregados.');
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
    $("#btn_nuevo_pseToken").click(function(e){
        e.preventDefault();
        $("#myModal").load('<?php echo base_url()?>index.php/pse_token/crear',{});
    }); 
    //buscar seccion
    $("#btn_buscar_pseToken").click(function(e){
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