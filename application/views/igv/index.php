<h2 align="center"><strong>IGV</strong></h2>
<br>

<div class="container">
	<div class="row">
		<div class="col-md-4">
			<button id="btn_buscar_igv" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal">IGV</button>
		</div>
		<div class="col-md-4 col-md-offset-4">
			<div class="form">
				<div class="input-group">
					<input type="text" class="form-control" id="search" placeholder="Buscar por nombre">
					<span class="input-group-btn">
						<button class="btn btn-default" type="button" id="btn_buscar_igv"><span class="glyphicon glyphicon-search"></span></button>
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
				url:"<?PHP echo base_url()?>index.php/igv/vista/",
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
					{field:'id',title:'id',width:'150px'},
					{field:'valor',title:'valor',width:'150px'},
                    {field:'fecha',title:'fecha',width:'150px'},
                    {field:'activo',title:'activo',width:'50px'},
                    {field:'hab_editar',title:'&nbsp',width:'60px',template:"#= hab_editar #"},
					{field:'hab_eliminar',title:'&nbsp',width:'60px',template:"#= hab_eliminar #"},

		],
		dataBound:function(e){
        $('.btn_modificar_igv').click(function(e){
        	var idigv = $(this).data('id');
        	$("#myModal").load('<?= base_url()?>index.php/igv/editar/'+idigv,{});
       });
       
       $('.btn_eliminar_igv').click(function(e){
         e.preventDefault();
         var idigv=$(this).data('id');
         var msg=$(this).data(msg);
         var url='<?= base_url()?>index.php/igv/eliminar/'+idigv
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
         toast('success',1500,'igv eliminado');
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

$('#btn_buscar_igv').click(function(e){
     e.preventDefault();
     $('#myModal').load('<?= base_url()?>index.php/igv/crear',{});
   });







</script>