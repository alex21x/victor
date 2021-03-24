<style type="text/css">
  .container6  a{
   

    }
</style>
  <div class="container6">
 
</div>
<h2 align="center">Productos</h2>

<br>
<div class="container">
    <div class="row">
                        <div class="col-xs-12 form-inline col-sm-12" >
                            <label> Código Automático</label>                            
                            <input type="checkbox" name="codigo_auto" id="codigo_auto" <?php echo ($config->cod_prod_auto==1)?"checked":"";?> >
                             <input type="hidden" value="<?php echo ($config->cod_prod_auto==1)?"1":"0";?>" name="codigo_auto_num" id="codigo_auto_num">

                        </div>

        <div class="col-md-6">                        

            <button id="btn_nuevo_producto" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal" data-backdrop="static" data-keyboard="false">Nuevo Producto</button>
            <a class="btn btn-primary" id="exportar_product"> Reporte Stock </a>
             <!--<a class="btn btn-primary" id="exportar_product_vendido"> Reporte Cantidad vendida </a>-->
            <button id="btn_subir_producto"  class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal">Importar Productos</button> 
            <a href="<?php echo base_url()?>files/xlsx/descargar_formato/formato_productos.xlsx" class="btn btn-default btn-sm">Descargar Formato xls</a>  

        </div>

         <div class="col-md-2" >
                  
                      <select id="almacen" class="form-control input-sm" name="almacen" readonly>
                        <?php foreach($almacenes as $almacen):?>
                       
                               <option value="<?php echo $almacen->alm_id?>" <?php if($this->session->userdata("almacen_id")==$almacen->alm_id):?> selected <?php endif?> ><?php echo $almacen->alm_nombre?></option>
                            
                        <?php endforeach?>
                      </select>
                </div> 
        <div class="col-md-3">
                  <input type="text" class="form-control" id="search" placeholder="Buscar por nombre">
                  <input type="hidden" class="form-control" id="busProducto" value="1">

        </div>   

        <div class="col-md-1" >
          <button class="btn btn-default" type="button" id="btn_buscar_producto"><span class="glyphicon glyphicon-search"></span></button>   
        </div>
           
        

    </div>
    <br>

    <div id="grid"></div>
</div>
<!-- <?php echo base_url()?>index.php/productos/modificar/<?php echo $producto->prod_id?>-->
<script type="text/javascript">
    jQuery(document).ready(function($) {

         $("#codigo_auto").click(function(){
            if( $(this).is(':checked') ) {
                var valor = 1;
                $("#codigo_auto_num").val(1);
            }else{
                var valor = 0;
                $("#codigo_auto_num").val(0);
            }

           /* $.get("<?PHP echo base_url()?>index.php/comprobantes/estado_igv",{valor})
             .done(function(res){
                 if(res==1){
                    toast('success', 1500, 'IGV Incluido');
                 }else{
                    toast('success', 1500, 'IGV No Incluido');
                 }

                 location.reload();
             })*/
        });


        $('#exportar_product').click(function() {
            var Articulo = $('#search').val();
            if(Articulo=='') {
                Articulo=0;
            }
            var url ='<?PHP echo base_url() ?>index.php/productos/ExportarExcel/'+Articulo+'/';
            window.open(url, '_blank');
       });

        $('#exportar_product_vendido').click(function() {
            var Articulo = $('#search').val();
            if(Articulo=='') {
                Articulo=0;
            }
            var url ='<?PHP echo base_url() ?>index.php/productos/ExportarExcel_vendido/'+Articulo+'/';
            window.open(url, '_blank');
       });
    });
</script>
<script>
    var dataSource = new kendo.data.DataSource({
             transport: {
                read: {
                    url:"<?php echo base_url()?>index.php/productos/getMainList/",
                    dataType:"json",
                    method:'post',
                    data:function(){
                        return {
                            search:function(){
                                return $("#search").val().trim();
                            },
                            almacen:function(){
                                return $("#almacen").val().trim();
                            },
                            busProducto:function(){
                                return $("#busProducto").val().trim();
                            },
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
                    {field:'prod_codigo',title:'CÓDIGO',width:'50px'},
                    {field:'prod_nombre',title:'NOMBRE/DESCRIPCIÓN',width:'100px'},
                    {field:'cat_nombre',title:'CATEGORIA',width:'60px'},
                    {field:'medida_nombre',title:'UNIDAD/MEDIDA',width:'80px'},
                    {field:'prod_precio_publico',title:'PRECIO V',width:'50px'},
                    {field:'prod_precio_compra',title:'PRECIO C',width:'50px'},
                    //{field:'prod_cantidad_minima',title:'CANTIDAD MINIMA',width:'160px'},
                    {field:'prod_stock', title:'STOCK',width:'50px'},
                    //{field:'alm_nombre', title:'ALMACÉN',width:'100px'},
                    {field:'prod_editar', title:'&nbsp;',width:'30px',template:"#= prod_editar #"},
                    {field:'prod_eliminar', title:'&nbsp;',width:'30px',template:"#= prod_eliminar #"},
        ],
        dataBound:function(e){
            //modificar producto
            $(".btn_modificar_producto").click(function(e){
               var idProducto = $(this).data('id');
                $("#myModal").load('<?php echo base_url()?>index.php/productos/editar/'+idProducto,{});
            });
            //editar producto
            $(".btn_eliminar_producto").click(function(e){
                e.preventDefault();
                var idProducto = $(this).data('id');
                var msg = $(this).data('msg');
                var url = '<?php echo base_url()?>index.php/productos/eliminar/'+idProducto
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
                                            dataSource.read();
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
    //nuevo producto
    $("#btn_nuevo_producto").click(function(e){
        e.preventDefault();
        $("#myModal").load('<?php echo base_url()?>index.php/productos/crear',{});
    }); 
    //buscar producto
    $("#btn_buscar_producto").click(function(e){
        e.preventDefault();
        dataSource.read();
    });
    //subir producto por excel
    $("#btn_subir_producto").click(function(e){
         e.preventDefault();
        $("#myModal").load('<?php echo base_url()?>index.php/productos/subirProductosUi',{});       
    });
    //buscar producto por campo texto
    $("#search").keyup(function(e){
        e.preventDefault();
        var enter = 13;
        if(e.which == enter)
        {
            dataSource.read();
        };
    })


</script>