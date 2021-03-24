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

    //PACIENTE
    $("#cliente_s").autocomplete({
        source: '<?= base_url()?>index.php/clientes/buscador_cliente',
        minLength: 2,
        select: function(event,ui){
          var data_cli_s = '<input type="hidden" value="'+ ui.item.id+'" name="cliente_s_id" id="cliente_s_id">';
          $("#data_cli_s").html(data_cli_s);      
          //$("#exportar_pdf").prop('disabled',false);
        }
    });
});
</script>
<style type="text/css">
    #formEvento_s label{width: 100%}
</style>
<h2 align="center"><strong>REGISTRO DE NUEVO EVENTO</strong></h2>
<br>

<div class="container">
    <form id="formEvento_s">
    <input type="hidden" name="historia_id" id="historia_id" value="<?= $historia_id?>"> 
    <div class="row">
        <div class="col-xs-12 col-md-2 col-lg-2">
            <button id="btn_nuevo_evento" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal" data-keyboard='false' data-backdrop='static'>Nueva evento</button>
        </div><br>
        <div class="col-xs-12 col-md-10 col-lg-10">
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
                    <label>N°
                        <input class="form-control" name="numero_documento" id="numero_documento">
                    </label>
                </div>
                <div class="col-xs-6 col-md-3 col-lg-3">
                    <label>Cliente&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<!--<input type="checkbox" id="paciente_check" name="paciente_check"> Ver Todos-->
                        <input class="form-control" name="cliente_s" id="cliente_s">
                        <div id="data_cli_s"><input type="hidden" name="cliente_s_id" id="cliente_s_id"></div>
                    </label>
                </div> 
                <div class="col-xs-6 col-md-3 col-lg-3">
                    <label>Placa
                        <input class="form-control" name="placa" id="placa">
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
                <div class="col-xs-12 col-md-6 col-lg-5">
                    <label>&nbsp;<br>
                        <button id="buscarReporte" type="button" class="btn btn-primary btn-sm">BUSCAR</button>                        
                        <button type="button" class="btn btn-default btn-sm" id="btn_limpiar">Limpiar</button>
                        <button type="button" class="btn btn-success btn-sm" id="btn_exportar_excel">Exportar Excel</button> 
                        <button type="button" class="btn btn-danger btn-sm" id="btn_exportar_pdf">Exportar Pdf</button>                                                    
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
                url:"<?PHP echo base_url()?>index.php/eventos/getMainList/",
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
                        numero_documento: function(){
                            return $("#numero_documento").val();
                        },
                        cliente: function(){
                            return $("#cliente_s_id").val();
                        },
                        placa:function(){
                            return $("#placa").val();
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
        columns: [  {field:'evento_id',title:'N°',width:'80px',template:"#= evento_id #"},
                    {field:'cli_razon_social',title:'CLIENTE',width:'80px'},
                    {field:'placa',title:'PLACA',width:'80px'},
                    {field:'tipo_evento',title:'T.EVENTO',width:'80px'},
                    {field:'fecha_evento',title:'FECHA EVENTO',width:'80px'},
                    {field:'ingreso',title:'INGRESO',width:'80px'},
                    {field:'salida',title:'SALIDA',width:'80px'},
                    {field:'empleado',title:'USUARIO',width:'150px'},
                    //{field:'responsable',title:'RESPONSABLE',width:'150px'},                    
                    //{field:'btn_ticket', title:'Ticket',width:'40px',template:"#= btn_ticket #"},
                    //{field:'boton_pdf', title:'A4',width:'40px',template:"#= boton_pdf #"},
                    {field:'btn_ticket',title:'Ticket',width:'60px',template:"#= btn_ticket #"},
                    {field:'btn_pdf',title:'A4',width:'60px',template:"#= btn_pdf #"},
                    {field:'eve_editar',title:'&nbsp',width:'60px',template:"#= eve_editar #"},
                    <?PHP if($this->session->userdata('accesoEmpleado') == ''){?>
                    {field:'eve_eliminar',title:'&nbsp',width:'60px',template:"#= eve_eliminar #"}
                    <?PHP }?>
        ],
        dataBound: function(e){

            if($("#historia_id").val() != '')
            $('#btnHistoria').trigger('click');

            //GALERIA
            $(".show_galeria").click(function(e) {
                var _val = $(this).data("id");
                javascript:window.open('<?PHP echo base_url() ?>index.php/historias/show_galeria/'+_val,'','width=750,height=600,scrollbars=yes,resizable=yes');                
            });
            //modificar evento
            $('.btn_modificar_evento').click(function(e){
                var idEvento = $(this).data('id');
                $("#myModal").load('<?= base_url()?>index.php/eventos/editar/'+idEvento,{});
            });

            $('.btn_eliminar_evento').click(function(e){              
                e.preventDefault();
                var idEvento = $(this).data('id');                
                var msg = $(this).data('msg');              

                var url = '<?= base_url()?>index.php/eventos/eliminar/'+idEvento;
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
                                            toast('success',1500,'evento eliminado');
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
    $('#btn_nuevo_evento').click(function(e){
        e.preventDefault();
        $('#myModal').load('<?= base_url()?>index.php/eventos/crear',{});
    });

    /* BOTON EXPORTAR EXCEL*/
    $('#btn_exportar_excel').click(function() {
        datos = $("#formEvento_s").serialize();                      
        var url ='<?PHP echo base_url() ?>index.php/eventos/exportarReporteEvento?'+datos;
        window.open(url, '_blank');

    });

    /* BOTON EXPORTAR PDF - 05-03-2021 - ALEXANDER FERNANDEZ */
    $('#btn_exportar_pdf').click(function() {
        datos = $("#formEvento_s").serialize();                      
        var url ='<?PHP echo base_url() ?>index.php/eventos/exportarReporteEvento_pdf?'+datos;
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


    //IMPRESION PDF - ALEXANFER FERNANDEZ - 05-03-2021
    function impresionTicket(eventoId){
        var _val = $(this).attr("idval");
        $.confirm({
            title: 'Imprmir!',
            content: 'Desea imprimir Ticket!',
            buttons: {
                confirmar: function () {
                    javascript:window.open('<?PHP echo base_url() ?>index.php/eventos/descargarPdf_ticket/'+eventoId,'','width=750,height=600,scrollbars=yes,resizable=yes');                    
                },
                cancelar: function () {
                    //$.alert('Cancelado!');
                }                
            }
        });            
    }
</script>