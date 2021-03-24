<h2 align="center"><strong>Endpoints</strong></h2>
<br>

<div class="container">
	<div class="row">
		<div class="col-md-4">
			<button id="btn_nuevo_endpoint" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal">Nueva Enpoints</button>
		</div>
		<div class="col-md-4 col-md-offset-4">
			<div class="form">
				<div class="input-group">
					<input type="text" class="form-control" id="search" placeholder="Buscar por nombre">
					<span class="input-group-btn">
						<button class="btn btn-default" type="button" id="btn_buscar_endpoint"><span class="glyphicon glyphicon-search"></span></button>
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
				url:"<?PHP echo base_url()?>index.php/endpoints/getMainList/",
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
		columns: [  {field:'id',title:'N°',width:'40px',template:"#= id #"},
					{field:'endpoint',title:'DESCRIPCION',width:'170px'},					
					{field:'modo',title:'MODO',width:'80px'},
					{field:'activo',title:'ACTIVO',width:'40px'},
					{field:'end_editar',title:'&nbsp',width:'60px',template:"#= end_editar #"},
					{field:'end_eliminar',title:'&nbsp',width:'60px',template:"#= end_eliminar #"},
		],
		dataBound: function(e){

			//modificar nivel
			$('.btn_modificar_endpoint').click(function(e){
				var idEndpoint = $(this).data('id');
				$("#myModal").load('<?= base_url()?>index.php/endpoints/editar/'+idEndpoint,{});
			});

			$('.btn_eliminar_endpoint').click(function(e){
				e.preventDefault();
				var idEndpoint = $(this).data('id');
				var msg = $(this).data(msg);
				var url = '<?= base_url()?>index.php/endpoints/eliminar/'+idEndpoint;
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
											toast('success',1500,'endpoint eliminada');
											dataSource.read();
										}
										if(response.status == STATUS_FAIL){
											toast('error',2000,'No se pudo eliminar el endpoint porque tiene endpoint agregados');
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

	//nuevo endpoint
	$('#btn_nuevo_endpoint').click(function(e){
		e.preventDefault();
		$('#myModal').load('<?= base_url()?>index.php/endpoints/crear',{});
	});

	//buscar endpoint
	$('#btn_buscar_endpoint').click(function(e){
		e.preventDefault();
		dataSource.read();
	});

	//buscar endpoint por campo texto
	$("#search").keyup(function(e){
		e.preventDefault();
		var enter = 13;
		if(e.which == enter){
			dataSource.read();
		};
	});

</script>