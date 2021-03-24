<h2 align="center">Módulos</h2>
<br>
<div class="container-fluid">
    <div class="row">                        
        <div class="col-md-5">
            <button id="btn_nuevo_modulo" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal" data-backdrop="static" data-keyboard="false">Nuevo Modulo</button>                        
        </div>        
         <div class="col-md-3" >                  
                <select id="almacen" class="form-control input-sm" name="almacen" style="display: none;">
                        <?php foreach($almacenes as $almacen):?>
                       
                               <option value="<?php echo $almacen->alm_id?>" <?php if($this->session->userdata("almacen_id")==$almacen->alm_id):?> selected <?php endif?> ><?php echo $almacen->alm_nombre?></option>
                            
                        <?php endforeach?>
                      </select>
                </div> 
        <div class="col-md-3">
            <input type="text" class="form-control" id="search" placeholder="Buscar por nombre">
        </div>   

        <div class="col-md-1" >
          <button class="btn btn-default" type="button" id="btn_buscar_modulo"><span class="glyphicon glyphicon-search"></span></button>   
        </div>               
    </div>
    <br>
    <div id="grid"></div>
</div>

<script>
    var dataSource = new kendo.data.DataSource({
             transport: {
                read: {
                    url:"<?php echo base_url()?>index.php/modulos/getMainList/",
                    dataType:"json",
                    method:'post',
                    data:function(){
                        return {
                            search:function(){
                                return $("#search").val().trim();
                            },
                            almacen:function(){
                                return $("#almacen").val().trim();
                            }
                        }
                    }
                }
            },
            schema:{
                data:'data',
                total:'rows'
            },
            pageSize: 10,
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
                    {field:'mod_correlativo',title:'CODIGO',width:'50px'},
                    {field:'mod_descripcion',title:'NOMBRE/DESCRIPCIÓN',width:'100px'},                    
                    {field:'mod_padre',title:'REFERENCIA',width:'50px'},
                    {field:'mod_orden',title:'ORDEN',width:'50px'},
                    {field:'mod_enlace',title:'ENLACE',width:'80px'},                                        
                    {field:'mod_editar', title:'&nbsp;',width:'30px',template:"#= mod_editar #"},
                    {field:'mod_eliminar', title:'&nbsp;',width:'30px',template:"#= mod_eliminar #"},
        ],
        dataBound:function(e){
            //modificar producto
            $(".btn_modificar_modulo").click(function(e){
               var idModulo = $(this).data('id');
                $("#myModal").load('<?php echo base_url()?>index.php/modulos/editar/'+idModulo,{});
            });
            //editar producto
            $(".btn_eliminar_modulo").click(function(e){
                e.preventDefault();
                var idModulo = $(this).data('id');
                var msg = $(this).data('msg');
                var url = '<?php echo base_url()?>index.php/modulos/eliminar/'+idModulo
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
                                            toast('success', 1500, 'Modulo eliminado');
                                            dataSource.read();
                                        }
                                        if(response.status == STATUS_FAIL)
                                        {
                                            toast('error', 1500 ,'No se puedo eliminar modulo.');
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

            var grid = $("#gridSellIn").data("kendoGrid");
            var data = dataSource.data();
            $.each(data,function(e, row){
            
                if(parseFloat(row.prod_stock) <= parseFloat(row.prod_cantidad_minima))
                {
                    $('tr[data-uid="' + row.uid + '"] ').css("background-color", "#F0B27A");
                }
            });                                 
        }
    });   

    //nuevo modulo
    $("#btn_nuevo_modulo").click(function(e){
        e.preventDefault();
        $("#myModal").load('<?php echo base_url()?>index.php/modulos/crear',{});
    }); 

    //buscar modulo
    $("#btn_buscar_modulo").click(function(e){
        e.preventDefault();
        dataSource.read();
    });

    //buscar modulo por campo texto
    $("#search").keyup(function(e){
        e.preventDefault();
        var enter = 13;
        if(e.which == enter)
        {
            dataSource.read();
        };
    })
</script>