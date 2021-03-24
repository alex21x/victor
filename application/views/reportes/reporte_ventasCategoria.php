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

		$("#linea").autocomplete({
			source: '<?= base_url()?>index.php/lineas/selectAutocomplete',
			minLength : 2,
	            select : function (event,ui){                                     
	                var data_lin = '<input type="hidden" value="'+ ui.item.id + '" name = "linea_id" id = "linea_id">';
	                $('#data_lin').html(data_lin);                               
	            }        
		});

		$("#marca").autocomplete({
			source: '<?= base_url()?>index.php/marcas/selectAutocomplete',
			minLength : 2,
	            select : function (event,ui){                                     
	                var data_mar = '<input type="hidden" value="'+ ui.item.id + '" name = "marca_id" id = "marca_id">';
	                $('#data_mar').html(data_mar);                               
	            }        
		});

		$("#producto").autocomplete({			
			source: '<?= base_url()?>index.php/productos/selectAutocomplete?almacen='+$("#almacen option:selected").val(),
			minLength : 2,
	            select : function (event,ui){                                     
	                var data_pro = '<input type="hidden" value="'+ ui.item.id + '" name = "producto_id" id = "producto_id">';
	                $('#data_pro').html(data_pro);                               
	            }        
		});		
	});
</script>
<div class="container">
	<h3>REPORTE POR CATEGORIA</h3><br>
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
	</div>
	<div class="row">		
		<div class="col-xs-4 col-md-4 col-lg-4">
			<label>Almacén
				<select class="form-control" name="almacen" id="almacen">
					<?PHP foreach($almacenes as $value){?>
					<option value="<?= $value->alm_id?>"><?= $value->alm_nombre?></option>
					<?PHP }?>	
				</select>				
			</label>
		</div>	
		<div class="col-xs-4 col-md-4 col-lg-4">
			<label>Categoría
				<input class="form-control" name="categoria" id="categoria">
				<div id="data_cat"><input type="hidden" name="categoria_id" id="categoria_id"></div>
			</label>
		</div>	
		<div class="col-xs-4 col-md-4 col-lg-4">
			<label>Linea
				<input class="form-control" name="linea" id="linea">
				<div id="data_lin"><input type="hidden" name="linea_id" id="linea_id"></div>
			</label>
		</div>	
		<div class="col-xs-4 col-md-4 col-lg-4">
			<label>Marca
				<input class="form-control" name="marca" id="marca">
				<div id="data_mar"><input type="hidden" name="marca_id" id="marca_id"></div>
			</label>
		</div>	
		<div class="col-xs-4 col-md-4 col-lg-4">
			<label>Producto
				<input class="form-control" name="producto" id="producto">
				<div id="data_pro"><input type="hidden" name="producto_id" id="producto_id"></div>
			</label>
		</div>	
		<div class="col-xs-4 col-md-4 col-lg-4">
			<label>Vendedor
				<select class="form-control" name="vendedor" id="vendedor">
					<option value="">Seleccionar</option>
				<?PHP foreach($vendedores as $empleado){?>			
					<option value="<?php echo $empleado['id']?>"><?php echo $empleado['apellido_paterno']." ".$empleado['apellido_materno'].", ".$empleado['nombre'] ?></option>
				<?PHP }?>
				</select>	
			</label>
		</div>	
		<div class="col-xs-12 col-md-6 col-lg-6">
				<a id="buscarReporte" href="#" class="btn btn-primary btn-sm colbg">Buscar</a>
				<a id="btn_limpiar" href="#" class="btn btn-primary btn-sm colbg" >Limpiar</a>
				<a id="exportar_repo" href="#" class="btn btn-success btn-sm colbg">Eportar excel</a>			
				<a id="exportar_repo_pdf" href="#" class="btn btn-danger btn-sm colbg">Eportar pdf</a>
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
				url: '<?= base_url()?>index.php/reportes/reporte_ventasCategoria_bs',
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
               
        var url ='<?PHP echo base_url() ?>index.php/reportes/exportarReporteCategoria?'+datos;
        window.open(url, '_blank');
    });

		$('#exportar_repo_pdf').click(function() {
        datos = $("#formReporte").serialize();       
               
        var url ='<?PHP echo base_url() ?>index.php/reportes/exportarReporteCategoria_pdf?'+datos;
        window.open(url, '_blank');
    });

		/*button limpiar*/	    
		$(document).on("click","#btn_limpiar",function(){	    	        
	        $("#formReporte")[0].reset();	      
	        $("#producto_id").val('');
	        $("#categoria_id").val('');
	        $("#linea_id").val('');
	        $("#marca_id").val('');
	        $("#marca_id").val('');
	        buscarReporte();
	    }); 
});
</script>