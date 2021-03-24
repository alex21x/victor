<h2 align="center"><strong>Unidad/Medida</strong></h2>
<br>
<div class="container">
    <div class="row">
        <div class="col-md-4">
           <!-- <button id="btn_nuevo_medida" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal">Nueva Unidad/Medida </button> -->
        </div>
        <div class="col-md-4 col-md-offset-4">
            <div class="form">
                <div class="input-group">
                  <input type="text" class="form-control" id="search" placeholder="Buscar por nombre">
                  <span class="input-group-btn">
                    <button class="btn btn-default" type="button" id="btn_buscar_medida"><span class="glyphicon glyphicon-search"></span></button>
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
                    url:"<?php echo base_url()?>index.php/medida/getMainList/",
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
                    {field:'medida_nombre',title:'NOMBRE',width:'150px'},
                    {field:'medida_codigo_unidad',title:'CODIGO',width:'150px'},
                    {field:'medida_estado', title:'&nbsp;',width:'60px',template:"#= medida_estado #"},
                    //{field:'medida_eliminar', title:'&nbsp;',width:'60px',template:"#= medida_eliminar #"},
        ],
        dataBound:function(e){

            //cambiar estado
            $(".cambiarEstado").click(function(e){
                var datos = {
                                accion:$(this).data('accion'),
                                medida:$(this).data('id')
                            };
                $.ajax({
                    url:'<?php echo base_url()?>index.php/medida/cambiarEstado',
                    method:'post',
                    dataType:'json',
                    data:datos,
                    success:function(response){
                        if(response.status == STATUS_OK)
                        {
                            
                            dataSource.read();
                        }
                    }
                });            
                console.log(datos);           
            });                          
        }
    });    

    //buscar seccion
    $("#btn_buscar_medida").click(function(e){
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