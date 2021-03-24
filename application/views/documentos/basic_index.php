<script type="text/javascript">
    
    $(document).ready(function(){       

        $("#fecha_desde").datepicker({
            dateFormat: 'dd-mm-yy',
            firstDay: 1
        }).datepicker("setDate", new Date());


        $("#fecha_hasta").datepicker({
            dateFormat: 'dd-mm-yy',
            firstDay: 1
        }).datepicker("setDate", new Date());   
});
</script>


<h2 align="center"><strong>REGISTRO DE NUEVOS DOCUMENTOS</strong></h2>
<br>

<div class="container">
    <form id="formEvento_s">
    <input type="hidden" name="doc_id" id="doc_id" value="<?= $doc_id?>"> 
    <div class="row">
        <div class="col-md-4">
            <button id="btn_nuevo_documento" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal" data-keyboard='false' data-backdrop='static'>Nueva Documento</button>
        </div>
        <div class="col-xs-12 col-md-10 col-md-offset-2">
                <div class="row">
                <div class="col-xs-6 col-md-3 col-lg-3">
                    <label>Fecha Desde
                        <input class="form-control" name="fecha_desde" id="fecha_desde">
                    </label>
                </div>  
                <div class="col-xs-6 col-md-3 col-lg-3">
                    <label>Fecha Hasta
                        <input class="form-control" name="fecha_hasta" id="fecha_hasta">
                    </label>
                </div> 
                <div class="col-xs-6 col-md-3 col-lg-2" style="text-align: right;">
                    <label><br>
                        <input type="checkbox" id="ver_todos" name="ver_todos"> Ver Todos                                     
                    </label>
                </div>
                <div class="col-xs-6 col-md-3 col-lg-3">
                    <label>Nombre Documento&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input class="form-control" name="nombre_documento" id="nombre_documento">                        
                    </label>
                </div>  
                                 
                <div class="col-xs-6 col-md-3 col-lg-3">
                    <label>Usuario
                        <select class="form-control" name="empleado" id="empleado">
                            <option value="">Seleccionar</option>
                        <?PHP foreach($vendedores as $empleado){?>          
                            <option value="<?php echo $empleado['id']?>"><?php echo $empleado['apellido_paterno']." ".$empleado['apellido_materno'].", ".$empleado['nombre'] ?></option>
                        <?PHP }?>
                        </select>   
                    </label>
                </div>  
                <div class="col-xs-6 col-md-4 col-lg-5">
                    <label>&nbsp;<br>
                        <button id="buscarReporte" type="button" class="btn btn-primary btn-sm">BUSCAR</button>                        
                        <button type="button" class="btn btn-default btn-sm" id="btn_limpiar">Limpiar</button>                        
                    </label>
                </div>                                                                            
            </div>
        </div>
        
    </div>
    </form>
    <br>
</div>
<div class="container-fluid">
    <div id="gridEventos"></div>
</div>


<script>    
    var dataSource = new kendo.data.DataSource({
        transport: {
            read: {
                url:"<?PHP echo base_url()?>index.php/documentos/getMainList/",
                dataType: "JSON",
                method: "POST",
                data:function(){
                    return {                       
                        fecha_desde:function(){                
                            return $("#fecha_desde").val();
                        },
                        fecha_hasta:function(){                            
                            return $("#fecha_hasta").val();
                        }, 
                        nombre_documento: function(){
                            return $("#nombre_documento").val();
                        },                        
                        empleado: function(){
                            return $("#empleado").val();
                        },
                        ver_todos:function(){
                            return $("#ver_todos:checked").val();
                        },
                    }
                }
            }
        },

        schema:{
            data: 'data',
            total: 'rows'
        },
        pageSize: 20,
        serverPaging: true,
        serverFiltering: true,
        serverSorting: true
    });
        

    $("#gridEventos").kendoGrid({
        dataSource: dataSource,
        height: 550,
        sortable: true,
        pageable: true,
        columns: [  {field:'documento_id',title:'N°',width:'80px',template:"#= documento_id #"},
                    {field:'nombre_doc',title:'NOMBRE DOCUMENTO',width:'200px'},
                    {field:'empleado',title:'USUARIO',width:'100px'},
                    {field:'fecha_creacion',title:'FECHA DE CREACION',width:'190px'},                    
                    //{field:'btn_ticket', title:'Ticket',width:'40px',template:"#= btn_ticket #"},
                    //{field:'boton_pdf', title:'A4',width:'40px',template:"#= boton_pdf #"},                    
                    {field:'doc_editar',title:'&nbsp',width:'60px',template:"#= doc_editar #"},
                    <?PHP if($this->session->userdata('accesoEmpleado') == ''){?>
                    {field:'doc_eliminar',title:'&nbsp',width:'60px',template:"#= doc_eliminar #"}
                    <?PHP }?>
        ],
        detailTemplate: '<div class="lista_documentos"></div>',
        detailInit: detailInit,
        dataBound: function(e){

            if($("#historia_id").val() != '')
            $('#btnHistoria').trigger('click');

            //GALERIA
            $(".show_galeria").click(function(e) {
                var _val = $(this).data("id");
                javascript:window.open('<?PHP echo base_url() ?>index.php/historias/show_galeria/'+_val,'','width=750,height=600,scrollbars=yes,resizable=yes');                
            });
            //modificar evento
            $('.btn_modificar_documento').click(function(e){
                var idDocumento = $(this).data('id');
                $("#myModal").load('<?= base_url()?>index.php/documentos/editar/'+idDocumento,{});
            });

            $('.btn_eliminar_documento').click(function(e){              
                e.preventDefault();
                var idEvento = $(this).data('id');                
                var msg = $(this).data('msg');              

                var url = '<?= base_url()?>index.php/documentos/eliminar/'+idEvento;
                $.confirm({
                    title: 'Confirmar',
                    content: msg,
                    buttons: {
                        confirm:{
                            text:'aceptar',
                            btnClass: 'btn-blue',
                            action: function(){
                                $.ajax({
                                    url: url,
                                    dataType: 'json',
                                    method: 'get',
                                    success: function(response){
                                        if(response.status ==  STATUS_OK){
                                            toast('success',1500,'documento eliminado');
                                            dataSource.read();
                                        }
                                        if(response.status == STATUS_FAIL){
                                            toast('error',2000,'No se pudo eliminar evento porque tiene eventos agregados');
                                        }
                                    }
                                });
                            }
                        },
                        cancel: function(){
                        }
                    }
                });
            });
        }
    });

     function detailInit(e) {
        var detailRow = e.detailRow;

        detailRow.find(".lista_documentos").kendoGrid({
            dataSource: {
                type: "json",
                transport: {
                    read:{
                        url:"<?php echo base_url()?>index.php/documentos/getMainListDetail/",
                        dataType:"json",
                        method:'post',
                        data:function(){
                            return {
                                doc_id:e.data.documento_id
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
                { field: "archi_id", title:"N°", width: "8px" },
                { field: "descri_archi", title:"DESCRIPCION DOCUMENTOS", width: "50px" },
                { field: "boton_descargar", title:"DESCARGAR",width:"50px",template:"#= boton_descargar #" }
            ],
            dataBound:function(e){
            }
        });
    }

    // boton de busqueda
    buscarReporte();
    $("#buscarReporte").click(function(){        
         buscarReporte();           
    });

    function buscarReporte(){
        $("#historia_id").val('');
        dataSource.read();
    }

    //nuevo evento
    $('#btn_nuevo_documento').click(function(e){
        e.preventDefault();
        $('#myModal').load('<?= base_url()?>index.php/documentos/crear',{});
    });

     /* BOTNO EXPORTAR EXCEL*/
    $('#btn_exportar_excel').click(function() {
        datos = $("#formEvento_s").serialize();       
               
        var url ='<?PHP echo base_url() ?>index.php/eventos/exportarReporteEvento?'+datos;
        window.open(url, '_blank');

        });

        /*BOTON LIMPIAR*/
    $(document).on("click","#btn_limpiar",function(){               
               //alert('dfdf');
               var today = new Date();
            date = addZero(today.getDate())+'-'+addZero((today.getMonth() + 1))+'-'+today.getFullYear();
           
            $("#formEvento_s")[0].reset();
            $("#fecha_desde").val(date);
            $("#fecha_hasta").val(date);            
            $("#cliente_s_id").val('');
             dataSource.read();
    }); 
     //ADD FECHA 0
    function addZero(i) {
        if (i < 10) {
                i = '0' + i;
          }
        return i;
    }
</script>