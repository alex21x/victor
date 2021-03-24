  <style>
    .ui-autocomplete { z-index:2147483647; }

  </style>
  <div class="modal-dialog " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Nuevo ingreso</h4>
      </div>
      <div class="modal-body">
       <form>
       	<input type="hidden" id="ing_id" value="<?php echo $ingreso->ing_id?>">
       	<div class="row">
       		<div class="col-md-4">
       			<div class="form-group">
       				<label for="fecha_ingreso">Fecha</label>
       				<input type="date" id="fecha_ingreso" class="form-control input-sm" value="<?php echo $ingreso->ing_fecha?>">
       			</div>
       		</div>
          <div class="col-md-8">
            <div class="form-group">
              <label for="almacen">Almacén</label>
              <select id="almacen" class="form-control input-sm">
                <option value="">Seleccione Almacén</option>
                <?php foreach($almacenes as $almacen):?>
                  <option value="<?php echo $almacen->alm_id?>" <?php if($almacen->alm_id==$ingreso->ing_almacen_id):?> selected <?php endif?> ><?php echo $almacen->alm_nombre?></option>
                <?php endforeach?>
              </select>
            </div>
          </div>

       	</div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="proveedor">Proveedor</label>
              <select id="proveedor" class="form-control">
                <option value="">Seleccione</option>
                <?php foreach($proveedores as $proveedor):?>
                <option value="<?php echo $proveedor->prov_id?>" <?php if($proveedor->prov_id==$ingreso->ing_proveedor_id):?> selected <?php endif?> > <?php echo $proveedor->prov_razon_social?></option>
                <?php endforeach?>
              </select>
              <!--<input type="text" id="proveedor" class="form-control input-sm" value="<?php echo $ingreso->ing_proveedor?>">-->
            </div>
          </div>          
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="observacion">Observaciòn</label>
              <textarea id="observacion" rows="3" class="form-control"><?php echo $ingreso->ing_observaciones?></textarea>
            </div>
          </div> 
        </div>
        <div id="ingreso_detalle" <?php if($ingreso->ing_id==''):?> style="display: none" <?php endif?> >
          <div class="row">
            <div class="col-md-7">
              <div class="form-group">
                <label for="producto"> Producto </label>
                <input type="text" class="form-control" name="producto" id="producto">
                <div id="data_producto"></div>              
                
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="cantidad">Cantidad</label>
                <input type="text" id="cantidad" class="form-control input-sm">
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label for="stock">&nbsp;</label>
                <br>
                <button id="btn_agregar_producto" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"></span></button>
              </div>
            </div>
          </div>
          <div id="grid_ingresos"></div>          
        </div>

       </form>
      </div>
      <div class="modal-footer">  
        <button type="button" class="btn btn-default" id="btn_guardar">Guardar</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
  <script type="text/javascript">
    textoBoton();
    $('#producto').autocomplete({
            source : '<?PHP echo base_url();?>index.php/comprobantes/buscador_item',
            minLength : 2,
            select : function (event,ui){                
                var data_prod = '<input type="hidden" value="'+ ui.item.id + '" name = "prod_id" id = "prod_id" >';
                $('#data_producto').html(data_prod);      
                
            },
            change : function(event,ui){
                if(!ui.item){
                  this.value = '';
                  $('#prod_id').val(''); 
                }
            }                
        });
  	$(document).ready(function(e){
      /*buscar producto*/
      
      //guardar ingreso
      $("#btn_guardar").click(function(e){
        e.preventDefault();
        $(".has-error.has-feedback").removeClass('has-error has-feedback');
        var datos = {
                ing_id:$("#ing_id").val(),
                fecha_ingreso:$("#fecha_ingreso").val(),
                proveedor:$("#proveedor").val(),
                observacion:$("#observacion").val(),
                almacen:$("#almacen").val()
              }; 
        $.ajax({
          url:'<?php echo base_url()?>index.php/ingresos/guardarIngreso',
          dataType:'json',
          data:datos,
          method:'post',
          success:function(response){
            if(response.status == STATUS_FAIL)
            {
              if(response.tipo == '1')
              {
                var errores = response.errores;
                toast('error', 1500, 'Faltan ingresar datos.');
                $.each(errores, function(index, value){
                  $("#"+index).parent().addClass('has-error has-feedback');
                });
              }
            }
            if(response.status == STATUS_OK)
            {
              toast('success', 1500, 'ingreso agregado');
              $("#ing_id").val(response.idIngreso);
              $("#ingreso_detalle").css("display","block");
              dataSourceIngreso.read();
              dataSource.read();
              textoBoton()
            }
          }
        });                     
      });
  		//guardar prodcuto ingreso
  		$("#btn_agregar_producto").click(function(e){
  			e.preventDefault();
  			$(".has-error.has-feedback").removeClass('has-error has-feedback');
  			var datos = {
  							ing_id:$("#ing_id").val(),
  							producto:$("#prod_id").val(),
                fecha_ingreso:$("#fecha_ingreso").val(),
  							cantidad:$("#cantidad").val(),
                almacen:$("#almacen").val()
  						};

  			$.ajax({
  				url:'<?php echo base_url()?>index.php/ingresos/guardarProductoIngreso',
  				dataType:'json',
  				data:datos,
  				method:'post',
  				success:function(response){
  					if(response.status == STATUS_FAIL)
  					{
  						if(response.tipo == '1')
  						{
  							var errores = response.errores;
  							toast('error', 1500, 'Faltan ingresar datos.');
  							$.each(errores, function(index, value){
  								$("#"+index).parent().addClass('has-error has-feedback');
  							});
  						}
  					}
  					if(response.status == STATUS_OK)
  					{
  						toast('success', 1500, 'Producto agregado');
              $("#cantidad").val("");
              $('#producto').val("");
              $('#prod_id').val("");
  						dataSourceIngreso.read();
              dataSource.read();
  					}
  				}
  			});
  					
  		});

      var dataSourceIngreso = new kendo.data.DataSource({
               transport: {
                  read: {
                      url:"<?php echo base_url()?>index.php/ingresos/getMainListDetail/",
                      dataType:"json",
                      method:'post',
                      data:function(){
                          return {
                              ing_id:function(){
                                  return $("#ing_id").val();
                              }
                          }
                      }
                  }
              },
              schema:{
                  data:'data',
                  total:'rows'
              },
              pageSize: 7,
              serverPaging: true,
              serverFiltering: true,
              serverSorting: true
                               
      });    
      $("#grid_ingresos").kendoGrid({
          dataSource: dataSourceIngreso,
          height: 250,
          sortable: true,
          pageable: true,
          columns: [
                  { field: "prod_codigo", title:"CODIGO", width: "70px" },
                  { field: "prod_nombre", title:"PRODUCTO", width: "120px" },
                  { field: "ingd_cantidad", title:"CANTIDAD",width:"70px" },
                 // { field: "ingd_eliminar", title:"&nbsp;",width:"70px",template:"#= ingd_eliminar #"}
          ],
          dataBound:function(e){
              //editar producto
              $(".btn_eliminar_detalle").click(function(e){
                  e.preventDefault();
                  var idDetalle = $(this).data('id');
                  var msg = $(this).data('msg');
                  var url = '<?php echo base_url()?>index.php/ingresos/eliminarDetalleIngreso/'+idDetalle
                  $.confirm({
                      title: 'Confirmar',
                      content: msg,
                      buttons: {
                          confirm:{
                              text:'aceptar',
                              btnClass: 'btn-blue',
                              action:function(){
                                  $.ajax({
                                      url:url,
                                      dataType:'json',
                                      method:'get',
                                      success:function(response){
                                          if(response.status == STATUS_OK)
                                          {
                                              toast('success', 1500, 'Ingreso eliminado');
                                              dataSourceIngreso.read();
                                              dataSource.read();
                                          }
                                          if(response.status == STATUS_FAIL)
                                          {
                                              toast('error', 1500 ,'No se puedo eliminar Ingreso.');
                                          }
                                      }
                                  });
                              }
                          },
                          cancel: function () {
                              
                          }
                      }
                  });
              });                            
          }
      });         
  	});

    function textoBoton()
    {
      var ing_id = $("#ing_id").val();
      var texto = (ing_id=='')?'Siguiente':'Guardar';
      $("#btn_guardar").text(texto);
    }
  </script>
