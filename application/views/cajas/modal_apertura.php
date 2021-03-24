
<style type="text/css">
	
	.row{
		margin-top: 40px;
	}

	.session{		
		text-align: left;
		font-size: 12px;
	}
</style>

<div class="modal-dialog">
	<div class="modal-content">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<div class="modal-title">
			<div class="col-xs-12 col-md-12 col-lg-6">
				<b>APERTURA DE CAJA<b>
			</div>
			<div class="col-xs-12 col-md-12 col-lg-6">
			<div class="session">	
			<?PHP
                $nombre = (strpos($this->session->userdata('usuario'), ' ') != '')?substr($this->session->userdata('usuario'), 0,  strpos($this->session->userdata('usuario'), ' ')):$this->session->userdata('usuario');
                                    ?>
                <li><?PHP echo $nombre.' '.$this->session->userdata('apellido_paterno'); ?><?PHP echo "<br>".$this->session->userdata('almacen_nom'); ?>&nbsp;&nbsp;&nbsp;&nbsp;</li>
        	</div>      
        	</div>
		</div>		  
	</div>
	<div class="modal-body">	
		<form id="formCajaMov">	
		<div class="row">
			<div class="col-md-3">
				<label>Monto Inicial</label>
			</div>
			<div class="col-md-9">
				<input type="number" class="form-control" name="saldo_inicial" id="saldo_inicial" value="0.00">
			</div>

		</div>

		<div class="row">
			<div class="col-md-12">			
				<button type="button" class="btn btn-success btn-block" id="guardarAperturaCaja">APERTURAR CAJA</button>
			</div>
		</div>
	</form>

	</div>

	<div class="modal-footer">
		
	</div>	
</div>
</div>

<script type="text/javascript">


//$(document).ready(function(){
function selectRowsCajamoviento(){
	    $.ajax({
	        url: '<?= base_url()?>index.php/cajas/selectRowsCajamoviento',
	        method: 'POST',
	        dataType: 'HTML',
	        success: function(response){
	            $("#rowCajaMov").html(response);
	            $("#tablaCaja").DataTable({
	            	rowReorder: false,
                	lengthMenu: [5, 10, 15, 20, 25],
           		 });
	        }
	    });
  	}	


  $("#guardarAperturaCaja").on("click",function(){
  	//alert("hoola");
   $.ajax({
    	url:'<?= base_url()?>index.php/cajas/guardar',
    	method:'POST',
    	dataType:'JSON',
    	data:$("#formCajaMov").serialize(),
    	success:function(response){
      	if (response.status==2){
       		selectRowsCajamoviento();
		$("#modalCajaMov").modal('hide');

		$(".btn_agregar_cajaMov").removeClass().addClass("btn_cerrar_cajaMov btn btn-danger");
		$("a.btn_cerrar_cajaMov").attr("data-target","#modalCajaMovCierre").attr("data-id", response.caja_id).text("Cierre de Caja");

      }
    }
   });
   });
//});




</script>
