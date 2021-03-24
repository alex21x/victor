<h2 align="center"><strong>Series</strong></h2>
<br>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <button id="btn_nuevo_serNum" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal">Nueva Serie</button>
        </div>
        <div class="col-md-4 col-md-offset-4">
            <div class="form">
                <div class="input-group">
                  <input type="text" class="form-control" id="search" placeholder="Buscar por nombre">
                  <span class="input-group-btn">
                    <button class="btn btn-default" type="button" id="btn_buscar_serNum"><span class="glyphicon glyphicon-search"></span></button>
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
                    url:"<?php echo base_url()?>index.php/serNums/getMainList/",
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
                    {field:'correlativo',title:'CODIGO',width:'150px'},
                    {field:'alm_nombre',title:'ALMACEN',width:'150px'},
                    {field:'tipo_documento',title:'TIPO DOCUMENTO',width:'150px'},
                    {field:'serie',title:'SERIE',width:'150px'},
                    {field:'numero',title:'NUMERO',width:'150px'},
                    {field:'nse_editar', title:'&nbsp;',width:'60px',template:"#= nse_editar #"},
                    {field:'nse_eliminar', title:'&nbsp;',width:'60px',template:"#= nse_eliminar #"},
        ],
        dataBound:function(e){
            //modificar producto
            $(".btn_modificar_serNum").click(function(e){
               var idSerNum = $(this).data('id');               
                $("#myModal").load('<?php echo base_url()?>index.php/serNums/editar/'+idSerNum,{});
            });
            //editar producto
            $(".btn_eliminar_serNum").click(function(e){
                e.preventDefault();
                var idSerNum = $(this).data('id');
                var msg = $(this).data('msg');
                var url = '<?php echo base_url()?>index.php/serNums/eliminar/'+idSerNum
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
                                            toast('success', 1500, 'serie eliminado');
                                            dataSource.read();
                                        }
                                        if(response.status == STATUS_FAIL)
                                        {
                                            toast('error', 2000 ,'No se puedo eliminar la serie porque tiene series agregados.');
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
    $("#btn_nuevo_serNum").click(function(e){
        e.preventDefault();
        $("#myModal").load('<?php echo base_url()?>index.php/serNums/crear',{});
    }); 
    //buscar seccion
    $("#btn_buscar_serNum").click(function(e){
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


