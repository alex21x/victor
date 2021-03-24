<h2 align="center"><strong>Profesionales</strong></h2>
<br>

<div class="container">
	<div class="row">
		<div class="col-md-4">
			<button id="btn_nuevo_profesional" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal">Nuevo Profesional</button>
			<button id="exportar_repo" href="#" class="btn btn-primary btn-sm colbg">Eportar excel</button>
		</div>
		<div class="col-md-4 col-md-offset-4">
			<div class="form">
				<div class="input-group">
					<input type="text" class="form-control" id="search" placeholder="Buscar por nombre">
					<span class="input-group-btn">
						<button class="btn btn-default" type="button" id="btn_buscar_profesional"><span class="glyphicon glyphicon-search"></span></button>
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
				url:"<?PHP echo base_url()?>index.php/profesionales/getMainList/",
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
					{field:'prof_codigo',title:'CODIGO',width:'150px'},					
					{field:'prof_nombre',title:'NOMBRE',width:'150px'},					
					{field:'esp_descripcion',title:'PROFESION',width:'150px'},				
					{field:'prof_editar',title:'&nbsp',width:'60px',template:"#= prof_editar #"},
					{field:'prof_eliminar',title:'&nbsp',width:'60px',template:"#= prof_eliminar #"}
		],
		dataBound: function(e){

			//GALERIA
			$(".show_galeria").click(function(e) {				
                var _val = $(this).data("id");
                javascript:window.open('<?PHP echo base_url() ?>index.php/profesionales/show_galeria/'+_val,'','width=750,height=600,scrollbars=yes,resizable=yes');
                
            });
			//modificar nivel
			$('.btn_modificar_profesional').click(function(e){
				var idProfesional = $(this).data('id');
				$("#myModal").load('<?= base_url()?>index.php/profesionales/editar/'+idProfesional,{});
			});

			$('.btn_eliminar_profesional').click(function(e){				
				e.preventDefault();
				var idProfesional = $(this).data('id');				
				var msg = $(this).data('msg');				

				var url = '<?= base_url()?>index.php/profesionales/eliminar/'+idProfesional;
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
											toast('success',1500,'profesional eliminado');
											dataSource.read();
										}
										if(response.status == STATUS_FAIL){
											toast('error',2000,'No se pudo eliminar el profesional porque tiene profesional agregados');
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
	$('#btn_nuevo_profesional').click(function(e){
		e.preventDefault();
		$('#myModal').load('<?= base_url()?>index.php/profesionales/crear',{});
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


	$('#exportar_repo').click(function() {                              
        var url ='<?PHP echo base_url() ?>index.php/profesionales/exportarExcel';
        window.open(url, '_blank');
   	});
</script>