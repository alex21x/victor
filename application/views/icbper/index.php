<h2 align="center"><strong>ICBPER</strong></h2>
<br>

<div class="container">
	<div class="row">
		<div class="col-md-4">
			<button id="btn_icbper" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal">Nuevos Tipo Pago</button>
		</div>
		<div class="col-md-4 col-md-offset-4">
			<div class="form">
				<div class="input-group">
					<input type="text" class="form-control" id="search" placeholder="Buscar por nombre">
					<span class="input-group-btn">
						<button class="btn btn-default" type="button" id="btn_buscar_icbper"><span class="glyphicon glyphicon-search"></span></button>
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
				url:"<?PHP echo base_url()?>index.php/icbper/vista/",
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
					{field:'icbPer_id',title:'icbPer_id',width:'150px'},
					{field:'icbPer_nombre',title:'icbPer_nombre',width:'150px'},
					{field:'icbPer_valor',title:'icbPer_valor',width:'150px'},
					{field:'icbPer_fecha',title:'icbPer_fecha',width:'150px'},
					{field:'icbPer_activo',title:'icbPer_activo',width:'150px'},
					{field:'modificar',title:'&nbsp',width:'80px',template:"#=modificar #"},
					{field:'eliminar',title:'&nbsp',width:'80px',template:"#= eliminar #"},

		],


		dataBound:function(e){

       $('.btn_modificar').click(function(e){

        var idicbper=$(this).data('id');
       $("#myModal").load('<?= base_url()?>index.php/icbper/editar/'+idicbper,{});
       });

       $('.btn_eliminar').click(function(e){
         e.preventDefault();
         var idicbper=$(this).data('id');
         var msg=$(this).data(msg);
         var url='<?= base_url()?>index.php/icbper/eliminar/'+idicbper
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
         toast('success',1500,'ICBPER eliminados');
         dataSource.read();
          }

         if (response.status==STATUS_FAIL) {
         toast('error',2000,'No se pudo eliminar ICBPER');

         
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


 $('#btn_icbper').click(function(e){
     e.preventDefault();
     $('#myModal').load('<?= base_url()?>index.php/icbper/crear',{});
   });





	

</script>