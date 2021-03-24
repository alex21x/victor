<h2 align="center"><strong>Tipos de Pagos</strong></h2>
<br>

<div class="container">
	<div class="row">
		<div class="col-md-4">
			<button id="btn_nuevo_tipo_pago" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal">Nuevos Tipo Pago</button>
		</div>
		<div class="col-md-4 col-md-offset-4">
			<div class="form">
				<div class="input-group">
					<input type="text" class="form-control" id="search" placeholder="Buscar por nombre">
					<span class="input-group-btn">
						<button class="btn btn-default" type="button" id="btn_buscar_tipo_pago"><span class="glyphicon glyphicon-search"></span></button>
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
				url:"<?PHP echo base_url()?>index.php/tipo_pagos/vista/",
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
					{field:'tipo_pago',title:'tipo_pago',width:'150px'},
					{field:'comentario',title:'comentario',width:'150px'},
					{field:'hab_editar',title:'&nbsp',width:'60px',template:"#= hab_editar #"},
					{field:'hab_eliminar',title:'&nbsp',width:'60px',template:"#= hab_eliminar #"},

		],

      dataBound:function(e){
        $('.btn_modificar_pagos').click(function(e){
        var idpagos=$(this).data('id');
        $("#myModal").load('<?= base_url()?>index.php/tipo_pagos/editar/'+idpagos,{});
       });
       
       $('.btn_eliminar_pagos').click(function(e){
         e.preventDefault();
         var idpagos=$(this).data('id');
         var msg=$(this).data(msg);
         var url='<?= base_url()?>index.php/tipo_pagos/eliminar/'+idpagos
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
         toast('success',1500,'pagos eliminados');
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
   $('#btn_nuevo_tipo_pago').click(function(e){
     e.preventDefault();
     $('#myModal').load('<?= base_url()?>index.php/tipo_pagos/crear',{});
   });





</script>