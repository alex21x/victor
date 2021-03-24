<h2 align="center"><strong>Especialidades</strong></h2>
<br>

<div class="container">
	<div class="row">
		<div class="col-md-4">
			<button id="btn_nueva_especialidad" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal">Nueva Especialidad</button>
		</div>
		<div class="col-md-4 col-md-offset-4">
			<div class="form">
				<div class="input-group">
					<input type="text" class="form-control" id="search" placeholder="Buscar por nombre">
					<span class="input-group-btn">
						<button class="btn btn-default" type="button" id="btn_buscar_habitacion"><span class="glyphicon glyphicon-search"></span></button>
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
				url:"<?PHP echo base_url()?>index.php/especialidades/getMainList/",
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
					{field:'esp_descripcion',title:'DESCRIPCION',width:'150px'},					
					{field:'esp_editar',title:'&nbsp',width:'60px',template:"#= esp_editar #"},
					{field:'esp_eliminar',title:'&nbsp',width:'60px',template:"#= esp_eliminar #"}
		],
		dataBound: function(e){

			//GALERIA
			$(".show_galeria").click(function(e) {				
                var _val = $(this).data("id");
                javascript:window.open('<?PHP echo base_url() ?>index.php/especialidades/show_galeria/'+_val,'','width=750,height=600,scrollbars=yes,resizable=yes');
                
            });
			//modificar nivel
			$('.btn_modificar_especialidad').click(function(e){
				var idEspecialidad = $(this).data('id');
				$("#myModal").load('<?= base_url()?>index.php/especialidades/editar/'+idEspecialidad,{});
			});

			$('.btn_eliminar_especialidad').click(function(e){				
				e.preventDefault();
				var idEspecialidad = $(this).data('id');				
				var msg = $(this).data('msg');				

				var url = '<?= base_url()?>index.php/especialidades/eliminar/'+idEspecialidad;
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
											toast('success',1500,'especialidad eliminada');
											dataSource.read();
										}
										if(response.status == STATUS_FAIL){
											toast('error',2000,'No se pudo eliminar el especialidad porque tiene especialidad agregados');
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
	$('#btn_nueva_especialidad').click(function(e){
		e.preventDefault();
		$('#myModal').load('<?= base_url()?>index.php/especialidades/crear',{});
	});

	//buscar nivel
	$('#btn_buscar_especialidad').click(function(e){
		e.preventDefault();
		dataSource.read();
	});

	//buscar nivel por campo texto
	$("#search").keyup(function(e){
		e.preventDefault();
		var enter = 13;
		if(e.which == enter){
			dataSource.read();
		};
	});

</script>