  <div class="modal-dialog modal-md" role="document">
  	<div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Agregar Anticipo</h4>
      </div> 
      <div class="modal-body">
      	<form>
	       	<div class="row">
	       		<div class="col-md-12">
              <?php if(count($anticipos)>0):?>
              <table class="table table-bordered table-xs">
                <thead>
                  <tr>
                    <th>Documento</th>
                    <th>Importe</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach($anticipos as $anticipo):?>
                  <tr>
                    <td><?php echo $anticipo->serie?>-<?php echo $anticipo->numero?></td>
                    <td><?php echo $anticipo->total_a_pagar?></td>
                    <td>
                      <button class="btn btn-warning btn_ant_agregar_anticipo btn-xs" data-documento="<?php echo $anticipo->id?>"><i class="glyphicon glyphicon-plus"></i></button>
                    </td>
                  </tr>
                  <?php endforeach?>
                </tbody>
              </table>
              <?php else:?>
              <h3>El cliente no cuenta con anticipos</h3>
              <?php endif?>
	       			<!--<button type="button" class="btn btn-primary" id="btn_ant_agregar_anticipo">Agregar</button>-->
	       		</div>

	       	</div>      		
      	</form>
      </div> 		
  	</div>	
  </div>
  <script>
  	$(".btn_ant_agregar_anticipo").click(function(e){
  		e.preventDefault();
  		var datos = {
  						      anticipo_id:$(this).data("documento"),
                    cliente:$("#cliente_id").val()
  					      };

  		$.ajax({
  			url:'<?php echo base_url()?>index.php/comprobantes/guardarAnticipo',
  			method:'post',
  			data:datos,
  			dataType:'json',
  			success:function(response)
  			{
  				if(response.status == STATUS_OK)
  				{
  					toast("success", 1500, "Anticipo agregado");
  					var _total_anticipo = parseFloat(response.totalAnticipo);
  					$("#total_anticipos").val(_total_anticipo.toFixed(2));
  					obtenerAnticipos();
  					calcular();
            $("#myModal").modal('hide');
  				}
  				if(response.status == STATUS_FAIL)
  				{
  					if(response.tipo == '1')
  					{
  						toast("error", 1500, "Debe ingresar numero de factura");
  					}else if(response.tipo == '2')
  					{
  						toast("error", 1500, "Esta factura ya ha sido usada.");
  					}else if(response.tipo == '3')
  					{
  						toast('error', 1500, "La factura no le pertenece al cliente");
  					}else{
  						toast('error', 1500, "La factura no fue encontrada");
  					}
  					
  				}
  			}
  		});			
  	});
  </script>