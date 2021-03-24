<h2 align="center"><strong>Tipo de pacientes</strong></h2>
<br>

<div class="container">
	<div class="row">
		<div class="col-md-4">
			<button id="btn_nuevo_tipoPaciente" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal">Nuevo Paciente</button>
		</div>
		<div class="col-md-4 col-md-offset-4">
			<div class="form">
				<div class="input-group">
					<input type="text" class="form-control" id="search" placeholder="Buscar por nombre">
					<span class="input-group-btn">
						<button class="btn btn-default" type="button" id="btn_buscar_tipoPaciente"><span class="glyphicon glyphicon-search"></span></button>
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
				url:"<?PHP echo base_url()?>index.php/tipo_pacientes/getMainList/",
				dataType: "json",
				method: "post",
				data: function(){
					return{
						search:function(){
							return $("#search").val();
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

	$("#grid").kendoGrid({
		dataSource: dataSource,
		height: 550,
		sortable: true,
		pageable: true,
		columns: [  {field:'id',title:'N°',width:'80px',template:"#= id #"},
					{field:'tipo_pac_descrip',title:'DESCRIPCION',width:'150px'},					
					{field:'btn_editar',title:'&nbsp',width:'60px',template:"#= btn_editar #"},
					{field:'btn_eliminar',title:'&nbsp',width:'60px',template:"#= btn_eliminar #"}
		],
		dataBound: function(e){

			//GALERIA
			$(".show_galeria").click(function(e) {				
                var _val = $(this).data("id");
                javascript:window.open('<?PHP echo base_url() ?>index.php/especialidades/show_galeria/'+_val,'','width=750,height=600,scrollbars=yes,resizable=yes');
                
            });            

			//MODIFICAR NIVEL TIPO PACIENTE
			$('.btn_modificar_tipoPaciente').click(function(e){
				var idTipoPaciente = $(this).data('id');
				$("#myModal").load('<?= base_url()?>index.php/tipo_pacientes/editar/'+idTipoPaciente,{});
			});

			$('.btn_eliminar_tipoPaciente').click(function(e){
				e.preventDefault();
				var idTipoPaciente = $(this).data('id');
				var msg = $(this).data('msg');				

				var url = '<?= base_url()?>index.php/tipo_pacientes/eliminar/'+idTipoPaciente;
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
											toast('success',1500,'Tipo Paciente eliminado');
											dataSource.read();
										}
										if(response.status == STATUS_FAIL){
											toast('error',2000,'No se pudo eliminar el Tipo Paciente porque tiene Tipo paciente agregado');
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

	//nuevo nivel
	$('#btn_nuevo_tipoPaciente').click(function(e){
		e.preventDefault();
		$('#myModal').load('<?= base_url()?>index.php/tipo_pacientes/crear',{});
	});

	//buscar Tipo Paciente
	$('#btn_buscar_tipoPaciente').click(function(e){
		e.preventDefault();
		dataSource.read();
	});

	//buscar Tipo Paciente
	$("#search").keyup(function(e){
		e.preventDefault();
		var enter = 13;
		if(e.which == enter){
			dataSource.read();
		};
	});
</script>