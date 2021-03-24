
<style>
    #refresh img{
        margin-left: 50px;
    }
</style>
<p class="bg-info">
    <?PHP echo $this->session->flashdata('mensaje'); ?>
</p>
<div class="container">
    <form>    
        <h2>Lista de Ingresos: <?php echo $empresa[0]['empresa']?></h2>

            <div class="row">
                <div class="col-xs-4">
                    <label>Proveedor:</label><br>
                    <input type="text" class="form-control input-sm" id="proveedor" name="proveedor" placeholder="Proveedor" value="<?= $proveedor_select;?>">
                    <div id="data_prov">
                        <input type="hidden" name = "proveedor_id" id = "proveedor_id" >
                        <?php
                            if(isset($proveedor_select_id) && ($proveedor_select_id != '')){
                            echo '<input type="hidden" value="' . $proveedor_select_id . '" name = "proveedor_id" id = "proveedor_id" >';
                            }
                        ?>
                    </div>
                </div>                 
                <div class="col-xs-2">
                    <label>Serie Número</label>
                    <input type="text" class="form-control input-sm" id="serie_numero" name="serie_numero">
                </div>
                <div class="col-xs-2" style="display: none;">
                    <label>Fecha</label>
                    <input type="text" class="form-control input-sm" id="fecha" name="fecha" >
                </div> 
                <div class="col-xs-2" style="display: none;">
                    <label>Documento</label>
                    <input type="text" class="form-control input-sm" id="documento" name="documento" >
                </div> 
                <div class="col-xs-2" >
                    <label >Almacén</label>
                      <select id="almacen" class="form-control input-sm" name="almacen">
                        <?php foreach($almacenes as $almacen):?>
                       
                               <option value="<?php echo $almacen->alm_id?>" <?php if($this->session->userdata("almacen_id")==$almacen->alm_id):?> selected <?php endif?> ><?php echo $almacen->alm_nombre?></option>
                            
                        <?php endforeach?>
                      </select>
                </div>                
                <div class="col-xs-3">
                    <br>
                    <input type="button" class="btn btn-primary " id="btn_search" value="Buscar">
                    <!--<input type="button" class="btn btn-primary " id="btn_limpiar" value="Limpiar">-->
                    <input type="hidden" value="<?php echo $empresa['id']?>" name="empresa_id" id="empresa_id">                      
                </div> 
                <div class="col-md-3" >
                    <br>
                    <a href="<?PHP echo base_url(); ?>index.php/compras/nuevo/<?php echo $empresa['id']?>" class="btn btn-success">Nuevo Ingreso</a>
                    <a id="exportarExcel" class="btn btn-primary"> Exportar a Excel </a>
                </div>            
            </div> 
    </form>
</div>

<div class="container-fluid">
    <br>
    <div id="grid"></div>    
</div>
<!--<meta http-equiv="refresh" content="20">-->
<script src="<?PHP echo base_url(); ?>assets/js/funciones.js"></script>
<script type="text/javascript">
    //Imprimir Boleto
   jQuery(document).ready(function($) {
        $('#exportarExcel').click(function() {
            var proveedor =$('#proveedor_id').val();
            var serie = $('#serie_numero').val();
            var fecha = $('#fecha').val();
            var documento = $('#documento').val();

            if(proveedor  ==''){
                proveedor =0;
            }
            if(serie ==''){
                serie =0;
            }
            if(fecha ==''){
                fecha = 0;
            }
            if(documento ==''){
                documento = 0;
            }            
            var url ='<?PHP echo base_url() ?>index.php/compras/ExportarExcel/'+proveedor+'/'+serie+'/'+fecha+'/'+documento+'/';
            window.open(url, '_blank');

        });

    });
    /*button buscar*/
    $("#btn_search").click(function(e){
        e.preventDefault();
        dataSource.read();
    });
    /*button limpiar*/
    $("#btn_limpiar").click(function(e){
        e.preventDefault();
        $("#proveedor_id").val("");
        $("#proveedor").val("");
        $("#serie_numero").val("");
        $("#fecha").val("");
        $("#documento").val("");
        dataSource.read();
    });    
    // AUTOCOMPLETE CLIENTE
    $(document).on('ready',function() {
        $("#proveedor").autocomplete( {
            source: '<?PHP echo base_url(); ?>index.php/compras/buscadorProveedor',
            minLength: 2,
            select: function(event, ui) {
            	console.log(ui);
                var data_prov ='<input type="hidden" value="' + ui.item.prov_id + '" name = "proveedor_id" id = "proveedor_id" >';
                $("#proveedor").val(ui.item.prov_razon_social);
                $('#data_prov').html(data_prov);
            }
        });

    // FECHA JAVASCRIPT
    $("#fecha").datepicker();
                     
    });  

    var dataSource = new kendo.data.DataSource({
             transport: {
                read: {
                    url:"<?php echo base_url()?>index.php/compras/getMainList/",
                    dataType:"json",
                    method:'post',
                    data:function(){
                        return {
                            proveedor:function(){
                                return $("#proveedor_id").val();
                            },
                            serie_numero:function(){
                                return $("#serie_numero").val();
                            },
                            fecha_search:function(){
                                return $("#fecha").val();
                            },
                            documento:function(){
                            	return $("#documento").val()
                            },
                            almacen:function(){
                                return $("#almacen").val()
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
                    {field:'comp_correlativo',title:'Nº INGRESO',width:'60px'},
                    {field:'comp_proveedor',title:'PROVEEDOR',width:'160px'},
                    {field:'comp_tipo_ingreso',title:'TIPO INGRESO',width:'160px'},
                    {field:'comp_serie_numero',title:'SERIE NUMERO',width:'80px'},
                    {field:'comp_doc_fecha',title:'FECHA',width:'80px'},
                    {field:'moneda',title:'MONEDA',width:'60px'},
                    {field:'comp_doc_subtotal',title:'SUB TOTAL',width:'60px'},
                    {field:'comp_doc_igv',title:'IGV',width:'60px'},
                    {field:'comp_doc_total',title:'TOTAL',width:'60px'},
                    {field:'boton_editar', title:'&nbsp;',width:'40px',template:"#= boton_editar #"},
                    {field:'boton_eliminar', title:'&nbsp;',width:'40px',template:"#= boton_eliminar #"},
                    {field:'boton_pdf', title:'&nbsp;',width:'40px',template:"#= boton_pdf #"},
                    //{field:'notap_total', title:'&nbsp;',width:'100px',template:"#= prod_eliminar #"},
        ],
        detailTemplate: '<div class="lista_compras"></div>',
        detailInit: detailInit,        
        dataBound:function(e){
            //modificar compra
            $(".btn-editar").click(function(e){
               var idCompra = $(this).data('id');
               location.href="<?php echo base_url()?>index.php/compras/editar/"+idCompra;
            });
            //eliminar compra
            $(".btn-eliminar").click(function(e){
                var idCompra = $(this).data('id');
                var msg = $(this).data('msg');
                var url = '<?php echo base_url()?>index.php/compras/eliminar/'+idCompra
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
                                            toast('success', 1500, 'Compra eliminada');
                                            dataSource.read();
                                        }
                                        if(response.status == STATUS_FAIL)
                                        {
                                            toast('error', 1500 ,'No se puedo eliminar compra.');
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

    function detailInit(e) {
        var detailRow = e.detailRow;

        detailRow.find(".lista_compras").kendoGrid({
            dataSource: {
                type: "json",
                transport: {
                    read:{
                        url:"<?php echo base_url()?>index.php/compras/getMainListDetail/",
                        dataType:"json",
                        method:'post',
                        data:function(){
                            return {
                                comp_id:e.data.comp_id
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
                { field: "compd_descripcion", title:"PRODUCTO", width: "120px" },
                { field: "compd_precio_unitario", title:"PRECIO UNITARIO", width: "50px" },
                { field: "compd_cantidad", title:"CANTIDAD",width:"70px" },
                { field: "compd_subtotal", title:"SUB TOTAL",width:"70px" }
            ],
            dataBound:function(e){
            }
        });
    }                   
</script>
