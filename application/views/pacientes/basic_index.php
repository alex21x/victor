<h2 align="center"><strong>Pacientes</strong></h2>
<br>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-4">
			<button id="btn_nuevo_paciente" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal">Nuevo Paciente</button>
			<button id="exportar_repo" href="#" class="btn btn-primary btn-sm colbg">Eportar excel</button>
		</div>
		<div class="col-md-4 col-md-offset-4">
			<div class="form">
				<div class="input-group">
					<input type="text" class="form-control" id="search" placeholder="Buscar por nombre">
					<span class="input-group-btn">
						<button class="btn btn-default" type="button" id="btn_buscar_paciente"><span class="glyphicon glyphicon-search"></span></button>
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
				url:"<?PHP echo base_url()?>index.php/pacientes/getMainList/",
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
		columns: [						
					{field:'razon_social',title:'RAZON SOCIAL',width:'150px'},
					{field:'ruc',title:'DNI',width:'150px'},
					{field:'telefono',title:'TELEFONO',width:'150px'},
					{field:'lugar_nacimiento',title:'DIRECCION',width:'150px'},
					{field:'alergia',title:'ALERGIA',width:'150px'},					
					{field:'pac_editar',title:'&nbsp',width:'60px',template:"#= pac_editar #"},
					<?PHP if($this->session->userdata('accesoEmpleado') == ''){?>
						{field:'pac_eliminar',title:'&nbsp',width:'60px',template:"#= pac_eliminar #"},
					<?PHP }?>
		],
		dataBound: function(e){
			//modificar nivel
			$('.btn_modificar_paciente').click(function(e){
				var idPaciente = $(this).data('id');
				$("#myModal").load('<?= base_url()?>index.php/pacientes/editar/'+idPaciente,{});
			});

			$('.btn_eliminar_paciente').click(function(e){
				e.preventDefault();
				var idPaciente = $(this).data('id');
				var msg = $(this).data(msg);
				var url = '<?= base_url()?>index.php/pacientes/eliminar/'+idPaciente
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
											toast('success',1500,'paciente eliminado');
											dataSource.read();
										}
										if(response.status == STATUS_FAIL){
											toast('error',2000,'No se pudo eliminar el paciente porque tiene paciente agregados');
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

	//nuevo paciente
	$('#btn_nuevo_paciente').click(function(e){
		e.preventDefault();
		$('#myModal').load('<?= base_url()?>index.php/pacientes/crear',{})
	});

	//buscar paciente
	$('#btn_buscar_paciente').click(function(e){
		e.preventDefault();
		dataSource.read();
	});

	//buscar paciente por campo texto
	$("#search").keyup(function(e){
		e.preventDefault();
		var enter = 13;
		if(e.which == enter){
			dataSource.read();
		};
	});


	$('#exportar_repo').click(function() {       
                        
        var url ='<?PHP echo base_url() ?>index.php/pacientes/exportarExcel';
        window.open(url, '_blank');
   	});

</script>