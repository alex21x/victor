


<h2 align="center"><strong>RESTAURAR COPIA DE SEGURIDAD</strong></h2>
<br>
<div class="container">	
		<button type="button" class="btn btn-primary btn_copia_seguridad">REALIZAR COPIA DE SEGURIDAD</button>
        <a type="button" class="btn btn-success btn_subir_backup" data-toggle="modal" data-target="#myModal">SUBIR COPIA DE SEGURIDAD</a>
	</label>
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
                    url:"<?php echo base_url()?>index.php/copia_respaldos/getMainList/",
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
                    {field:'copia_respaldo',title:'NOMBRE',width:'150px'},
                    {field:'fecha_de_emision', title:'FECHA',width:'60px'},
                    {field:'copiaRespado_restaurar', title:'RESTAURAR',width:'60px',template:"#= copiaRespado_restaurar #"},
                    {field:'copiaRespaldo_eliminar', title:'&nbsp;',width:'60px',template:"#= copiaRespaldo_eliminar #"},
        ],
        dataBound:function(e){

            //cambiar estado
            $(".btn_restaurar_copiaRespaldo").click(function(e){  
            	e.preventDefault();
                var idCopiaRespaldo = $(this).data('id');
                var msg = $(this).data('msg');
                var url = '<?php echo base_url()?>index.php/copia_respaldos/restaurar_exec';

            	var nombre = $(this).data('nombre');


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
                                    data:{nombre : nombre},
                                    method:'post',
                                    success:function(response){
                                        if(response.status == STATUS_OK)
                                        {
                                            toast('success', 1500, 'Copia restaurada');
                                            dataSource.read();
                                        }
                                        if(response.status == STATUS_FAIL)
                                        {
                                            toast('error', 1500 ,'No se puedo restaurar Copia.');
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

            //eliminar
            $(".btn_eliminar_copiaRespaldo").click(function(e){
                e.preventDefault();
                var idCopiaRespaldo = $(this).data('id');
                var msg = $(this).data('msg');
                var url = '<?php echo base_url()?>index.php/copia_respaldos/eliminar/'+idCopiaRespaldo
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
                                    method:'GET',
                                    success:function(response){
                                        if(response.status == STATUS_OK)
                                        {
                                            toast('success', 1500, 'Copia eliminado');
                                            dataSource.read();
                                        }
                                        if(response.status == STATUS_FAIL)
                                        {
                                            toast('error', 1500 ,'No se puedo eliminar Copia.');
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
<br><br>
<script type="text/javascript">


	$(document).ready(function(){
	$('.btn_copia_seguridad').on('click',function(){

		$.ajax({
			url: '<?= base_url()?>index.php/copia_respaldos/respaldo_exec',
			dataType: 'JSON',
			method: 'POST',
			success:  function(response){
				if(response.status == 2)
                   {
                     toast('success', 1500, 'Copia registrada');
  				     dataSource.read();
                   }
			}
		});
	});

    $(".btn_subir_backup").on('click',function(e){
        e.preventDefault();
        $("#myModal").load("<?= base_url()?>index.php/copia_respaldos/subir_copia",{});
    })
});
</script>