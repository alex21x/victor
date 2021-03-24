<h2 align="center"><strong>Turnos</strong></h2>
<br>

<div class="container">
	<div class="row">
		<div class="col-md-4">
			<button id="btn_nuevo_turno" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal">Nueva Turno</button>
		</div>
		<div class="col-md-4 col-md-offset-4">
			<div class="form">
				<div class="input-group">
					<input type="text" class="form-control" id="search" placeholder="Buscar por nombre">
					<span class="input-group-btn">
						<button class="btn btn-default" type="button" id="btn_buscar_turno"><span class="glyphicon glyphicon-search"></span></button>
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
				url:"<?PHP echo base_url()?>index.php/turnos/getMainList/",
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
		columns: [  {field:'id',title:'N°',width:'80px',template:"#= tur_id #"},
					{field:'turno',title:'DESCRIPCION',width:'150px'},					
					{field:'tur_editar',title:'&nbsp',width:'60px',template:"#= tur_editar #"},
					{field:'tur_eliminar',title:'&nbsp',width:'60px',template:"#= tur_eliminar #"}
		],
		dataBound: function(e){

			//GALERIA
			$(".show_galeria").click(function(e) {				
                var _val = $(this).data("id");
                javascript:window.open('<?PHP echo base_url() ?>index.php/turnos/show_galeria/'+_val,'','width=750,height=600,scrollbars=yes,resizable=yes');
                
            });
			//modificar turno
			$('.btn_modificar_turno').click(function(e){
				var idTurno = $(this).data('id');
				$("#myModal").load('<?= base_url()?>index.php/turnos/editar/'+idTurno,{});
			});

			$('.btn_eliminar_turno').click(function(e){
				e.preventDefault();
				var idTurno = $(this).data('id');				
				var msg = $(this).data('msg');				

				var url = '<?= base_url()?>index.php/turnos/eliminar/'+idTurno;
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
											toast('success',1500,'turno eliminado');
											dataSource.read();
										}
										if(response.status == STATUS_FAIL){
											toast('error',2000,'No se pudo eliminar el turno porque tiene turno agregados');
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

	//nuevo turno
	$('#btn_nuevo_turno').click(function(e){
		e.preventDefault();
		$('#myModal').load('<?= base_url()?>index.php/turnos/crear',{});
	});

	//buscar turno
	$('#btn_buscar_turno').click(function(e){
		e.preventDefault();
		dataSource.read();
	});

	//buscar turno por campo texto
	$("#search").keyup(function(e){
		e.preventDefault();
		var enter = 13;
		if(e.which == enter){
			dataSource.read();
		};
	});
</script>