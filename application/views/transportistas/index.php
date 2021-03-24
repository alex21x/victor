<h2 align="center"><strong>transportistas</strong></h2>
<br>
<div class="container">
	<div class="row">
		<div class="col-md-4">
			<button id="btn_transportistas" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal">transportistas</button>
			<button id="btn_importa" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal">importar</button>

		</div>
		<div class="col-md-4 col-md-offset-4">
			<div class="form">
				<div class="input-group">
					<input type="text" class="form-control" id="search" placeholder="Buscar por nombre">
					<span class="input-group-btn">
						<button class="btn btn-default" type="button" id="btn_buscar_transportistas"><span class="glyphicon glyphicon-search"></span></button>
					</span>
				</div>
			</div>
		</div>
	</div>
	<br>
	<div id="grid"></div>
</div>

<script type="text/javascript">
	
var dataSource = new kendo.data.DataSource({
		transport: {
			read: {
				url:"<?PHP echo base_url()?>index.php/transportistas/vista/",
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
				
				{field:'transp_id',title:'ID',width:'100px',color:'red'},
				{field:'transp_ruc',title:'RUC',width:'100px'},
				{field:'transp_nombre',title:'NOMBRE',width:'100px'},
				{field:'transp_direccion',title:'DIRECCION',width:'100px'},
				{field:'transp_telefono',title:'TELEFONO',width:'100px'},
				{field:'transp_tipounidad',title:'TIPO UNIDAD',width:'100px'},
				{field:'transp_placa',title:'PLACA',width:'100px'},
				{field:'transp_licencia',title:'LICENCIA',width:'100px'},
				{field:'transp_observacion',title:'OBSERVACION',width:'100px'},
				{field:'hab_editar',title:'&nbsp',width:'60px',template:"#=hab_editar #"},					
				{field:'hab_eliminar',title:'&nbsp',width:'60px',template:"#=hab_eliminar #"}

		],

		dataBound:function(e){

       $('.btn_modificar_transportistas').click(function(e){
        var idpagos=$(this).data('id');
        $("#myModal").load('<?= base_url()?>index.php/transportistas/editar/'+idpagos,{});
       });
         $('.btn_eliminar_transportistas').click(function(e){
         e.preventDefault();
         var idpagos=$(this).data('id');
         var msg=$(this).data(msg);
         var url='<?= base_url()?>index.php/transportistas/eliminar/'+idpagos
         $.confirm({
         title:'Confirmar',
         content:msg,
         buttons:{
         confirm:{
         text:'aceptar',
         btnClass:'btn-blue',
         action:function(){
         $.ajax({
         url:url,
         dataType:'json',
         method:'get',
         success:function(response){
         if (response.status==STATUS_OK) {
         toast('success',1500,'transportistas eliminados');
         dataSource.read();
          }

         if (response.status==STATUS_FAIL) {
         toast('error',2000,'No se pudo eliminar');

         
         }
         }

        });
        }
        },

        cancel:function(){



        }

        }
       });
       });






		}

        });

$('#btn_transportistas').click(function(e){
     e.preventDefault();
     $('#myModal').load('<?= base_url()?>index.php/transportistas/crear',{});
   });
$("#btn_importa").click(function(e){
	e.preventDefault();
	$('#myModal').load('<?= base_url()?>index.php/transportistas/mostrar',{});
});

     

</script>