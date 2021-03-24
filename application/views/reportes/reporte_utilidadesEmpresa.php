<style type="text/css">
	
	label{
		width: 100%;
	}
</style>

<br><br>



<script type="text/javascript">
	
	$(document).ready(function(){
		$("#fecha_desde").datepicker();
		$("#fecha_hasta").datepicker();	


		$("#categoria").autocomplete({
			source: '<?= base_url()?>index.php/categoria/selectAutocomplete',
			minLength : 2,
	            select : function (event,ui){                                         
	                var data_cat = '<input type="hidden" value="'+ ui.item.id + '" name = "categoria_id" id = "categoria_id">';
	                $('#data_cat').html(data_cat);                               
	            }        
		});
	});
</script>
<div class="container">
	<h3>REPORTE UTILIDADES</h3><br>
	<form id="formReporte">
	<div class="row">
		<div class="col-xs-4 col-md-4 col-lg-4">
			<label>Fecha Desde
				<input class="form-control" name="fecha_desde" id="fecha_desde">
			</label>
		</div>	
		<div class="col-xs-4 col-md-4 col-lg-4">
			<label>Fecha Hasta
				<input class="form-control" name="fecha_hasta" id="fecha_hasta">
			</label>
		</div>
		<div class="col-xs-4 col-md-4 col-lg-4">
			<label>Categoría
				<input class="form-control" name="categoria" id="categoria">
				<div id="data_cat"><input type="hidden" name="categoria_id" id="categoria_id"></div>
			</label>
		</div>
		<div class="col-xs-12 col-md-6 col-lg-6">		
				<a id="buscarReporte" type="button" class="btn btn-primary btn-sm">Buscar</a>
				<a id="btn_limpiar" href="#" class="btn btn-primary btn-sm">Limpiar</a>					
				<a id="exportar_repo" href="#" class="btn btn-success btn-sm colbg">Eportar excel</a>			
				<a id="exportar_repo_pdf" href="#" class="btn btn-danger btn-sm colbg">Eportar PDF</a>
			</label>	
		</div>
	</div>		
	</form>
</div>
<div class="container-fluid"> 
	<br><br>
	<div id="contenido"></div>
</div>


<script type="text/javascript">
	
	$(document).ready(function(){


		function buscarReporte(){
			$.ajax({
				url: '<?= base_url()?>index.php/reportes/reporte_utilidadesEmpresa_bs',
				dataType: 'HTML',
				method: 'POST',
				data: $("#formReporte").serialize(),
				success: function(response){
					$("#contenido").html(response);
				}
			});
		}
		buscarReporte();


		$(document).on("click","#buscarReporte",function(){
			buscarReporte();
		});		



		$('#exportar_repo').click(function() {
        datos = $("#formReporte").serialize();       
               
        var url ='<?PHP echo base_url() ?>index.php/reportes/exportarReporteUtilidad?'+datos;
        window.open(url, '_blank');

        });

        $('#exportar_repo_pdf').click(function() {
        datos = $("#formReporte").serialize();       
               
        var url ='<?PHP echo base_url() ?>index.php/reportes/reporte_utilidades_pdf?'+datos;
        window.open(url, '_blank');

        });


		/* BOTON LIMPIAR */
		$(document).on("click","#btn_limpiar",function(){	    	        
	        $("#formReporte")[0].reset();	      	        
	        $("#categoria_id").val('');	        
	        buscarReporte();
	    });
});
</script>