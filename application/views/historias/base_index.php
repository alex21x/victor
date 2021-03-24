<style type="text/css">
	
	label{
		width: 100%;
	}	
</style>
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
	$("#paciente_s").autocomplete({
	    source: '<?= base_url()?>index.php/pacientes/buscador_paciente',
	    minLength: 2,
	    select: function(event,ui){
	      var data_pac_s = '<input type="hidden" value="'+ ui.item.id+'" name="paciente_s_id" id="paciente_s_id">';
	      $("#data_pac_s").html(data_pac_s);      
	      $("#exportar_pdf").prop('disabled',false);
	    }
  	});
});
</script>


<h2 align="center"><strong>REGISTRO DE ATENCIÓN - CITAS - HISTORIAS CLÍNICAS</strong></h2>
<br>

<div class="container">
	<form id="formHistoria_s">
	<input type="hidden" name="historia_id" id="historia_id" value="<?= $historia_id?>"> 
	<div class="row">
		<div class="col-md-4">
			<button id="btn_nueva_historia" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal" data-keyboard='false' data-backdrop='static'>Nueva Historia</button>
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
				<div class="col-xs-6 col-md-4 col-lg-5">
					<label>&nbsp;<br>
						<button id="buscarReporte" type="button" class="btn btn-primary btn-sm">BUSCAR</button>
						<a id="exportar_repo" href="#" class="btn btn-success btn-sm colbg">Eportar excel</a>
						<button id="exportar_pdf"  type="button" class="btn btn-warning btn-sm" disabled="">VER HISTORIA</button>							
						<button type="button" class="btn btn-default btn-sm" id="btn_limpiar">Limpiar</button>			
					</label>
				</div>										
			</div>

			<div class="row">
				<div class="col-xs-6 col-md-3 col-lg-3">
					<label>Especialidad
						<select class="form-control" name="especialidad_s" id="especialidad_s">
							<option value="">Seleccionar</option>
						<?PHP foreach($especialidades as $especialidad){?>			
							<option value="<?= $especialidad->esp_id?>"><?= $especialidad->esp_descripcion?></option>
						<?PHP }?>
						</select>	
					</label>
				</div>	
				<div class="col-xs-6 col-md-3 col-lg-3">
					<label>Médico
						<select class="form-control" name="profesional_s" id="profesional_s">	
							<option value="">Seleccionar</option>						
						</select>	
					</label>
				</div>	
				<div class="col-xs-6 col-md-3 col-lg-4">
					<label>Paciente&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" id="paciente_check" name="paciente_check"> Ver Todos
						<input class="form-control" name="paciente_s" id="paciente_s">
						<div id="data_pac_s"><input type="hidden" name="paciente_s_id" id="paciente_s_id"></div>
					</label>
				</div>	
				<div class="col-xs-12 col-md-6 col-lg-2">
					<label>Estado
						<select class="form-control" name="estado_s" id="estado_s">
							<option value="">Seleccionar</option>
						<?PHP foreach($historia_estados as $value){?>			
							<option value="<?php echo $value->hie_id?>"><?= $value->hie_descripcion?></option>
						<?PHP }?>
						</select>	
					</label>
				</div>	
			</div>
			<div class="row">
				<div class="col-xs-12 col-md-6 col-lg-3" style="display: none">
				<div class="input-group">
					<input type="text" class="form-control" id="search" placeholder="Buscar por nombre">
					<span class="input-group-btn">
						<button class="btn btn-default" type="button" id="btn_buscar_historia"><span class="glyphicon glyphicon-search"></span></button>
						<button type="button" id="btnHistoria" data-toggle="modal" data-target="#myModal">MODAL</button>
					</span>					
				</div>
				</div>
			</div><br>
		</div>
	</div>
	</form>
	<br>
</div>
<div class="container-fluid">
	<div id="gridHistoria"></div>
</div>
<script>
	
	var dataSource = new kendo.data.DataSource({
		transport: {
			read: {
				url:"<?PHP echo base_url()?>index.php/historias/getMainList/",
				dataType: "json",
				method: "post",
				data:function(){
                    return {                       
                        fecha_desde:function(){                            
                            return $("#fecha_desde").val();
                        },
                        fecha_hasta:function(){                            
                            return $("#fecha_hasta").val();
                        },
                        paciente:function(){
                            return $("#paciente_s_id").val();
                        },
                        paciente_check:function(){
                            return $("#paciente_check:checked").val();
                        },
                        profesional:function(){
                            return $("#profesional_s").val();
                        },                        
                        especialidad:function(){
                            return $("#especialidad_s").val();
                        },                        
                        estado:function(){
                            return $("#estado_s").val();
                        }
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

	$("#gridHistoria").kendoGrid({
		dataSource: dataSource,
		height: 550,
		sortable: true,
		pageable: true,
		columns: [  {field:'id',title:'N°',width:'80px',template:"#= id #"},
					{field:'his_fecha',title:'FECHA ATENCION',width:'80px'},
					{field:'his_fecha_cita',title:'FECHA CITA',width:'100px'},
					{field:'estado',title:'ESTADO',width:'80px'},
					{field:'pac_razon_social',title:'PACIENTE',width:'150px'},					
					{field:'pac_telefono',title:'TELEFONO',width:'130px'},
					{field:'prof_nombre',title:'MEDICO',width:'130px'},
					{field:'esp_descripcion',title:'ESPECIALIDAD',width:'130px'},
					{field:'empleado',title:'USUARIO',width:'150px'},	
					{field:'estadoComprobante',title:'HONORARIO',width:'120px'},									
					{field:'his_proxima_cita',title:'PROXIMA CITA',width:'100px'},
					{field:'btn_ticket', title:'Ticket',width:'40px',template:"#= btn_ticket #"},
                    {field:'boton_pdf', title:'A4',width:'40px',template:"#= boton_pdf #"},
					{field:'his_editar',title:'&nbsp',width:'60px',template:"#= his_editar #"},
					<?PHP if($this->session->userdata('accesoEmpleado') == ''){?>
					{field:'his_eliminar',title:'&nbsp',width:'60px',template:"#= his_eliminar #"}
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
			//modificar nivel
			$('.btn_modificar_historia').click(function(e){
				var idHistoria = $(this).data('id');
				$("#myModal").load('<?= base_url()?>index.php/historias/editar/'+idHistoria,{});
			});

			$('.btn_eliminar_historia').click(function(e){				
				e.preventDefault();
				var idHistoria = $(this).data('id');				
				var msg = $(this).data('msg');				

				var url = '<?= base_url()?>index.php/historias/eliminar/'+idHistoria;
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
											toast('success',1500,'historia eliminada');
											dataSource.read();
										}
										if(response.status == STATUS_FAIL){
											toast('error',2000,'No se pudo eliminar historia porque tiene historias agregados');
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

	//nuevo historia
	$('#btn_nueva_historia').click(function(e){
		e.preventDefault();
		$('#myModal').load('<?= base_url()?>index.php/historias/crear',{});
	});

	//buscar historia
	$('#btn_buscar_historia').click(function(e){
		e.preventDefault();
		dataSource.read();
	});


	//CARGAR PROFESIONALES - ALEXANDER FERNANDEZ 13-11-20
	$("#especialidad_s").change(function(){
		var idEspecialidad = $("#especialidad_s").val();
	 	$.ajax({
	 		url: '<?= base_url()?>index.php/especialidades/cargarProfesionales',
	 		dataType: 'HTML',
	 		method: 'POST',
	 		data: {idEspecialidad:idEspecialidad},
	 		success: function(response){
	 			$("#profesional_s").html(response);
	 		}
	 	});
	});
	
	buscarReporte();
	$("#buscarReporte").click(function(){        
		 buscarReporte();		    
    });

    function buscarReporte(){
    	$("#historia_id").val('');
        dataSource.read();
    }

    $('#exportar_pdf').click(function() {        
        datos = $("#formHistoria_s").serialize();       
        	var url ='<?PHP echo base_url()?>index.php/historias/reporteHistoria_pdf?'+datos;
        	window.open(url, '_blank');                      
   	});	

   	/*BOTON LIMPIAR*/
	$(document).on("click","#btn_limpiar",function(){	 			
			var today = new Date();
			date = addZero(today.getDate())+'-'+addZero((today.getMonth() + 1))+'-'+today.getFullYear();

	        $("#formHistoria_s")[0].reset();
	        $("#fecha_desde").val(date);
	        $("#fecha_hasta").val(date);
	        $("#historia_id").val('');
	        $("#paciente_s_id").val('');
	        $("#exportar_pdf").prop('disabled',true);
	        dataSource.read();        
	}); 

	//MODAL DE ENVIO DE HISTORIAS CLINICAS 28-11-2020 ALEXANDER FERNANDEZ
    $(document).on('click',"#btnHistoria",function(){
        var historia_id =  $("#historia_id").val();
        $("#myModal").load('<?= base_url()?>index.php/historias/modalEnvioHistoria/'+historia_id,{});
    });

    $('#exportar_repo').click(function() {
        datos = $("#formHistoria_s").serialize();            
        var url ='<?PHP echo base_url() ?>index.php/historias/exportarReporteHistoria?'+datos;
        window.open(url, '_blank');
    });


    //ADD FECHA 0
    function addZero(i) {
	    if (i < 10) {
	        	i = '0' + i;
	    	}
	    return i;
	}
</script>