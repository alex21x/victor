<style>
  .ui-autocomplete{ z-index:2147483647; }
</style>
  <div class="modal-dialog " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Nuevo movimiento</h4>
      </div>
      <div class="modal-body">
       <form>
       	<input type="hidden" id="mov_id" value="<?php echo $movimiento->mov_id?>">
       	<div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="origen">Almacén Origen</label>
              <select id="origen" class="form-control input-sm" <?php if($movimiento->mov_id>0):?> disabled <?php endif?> >
                <option value="">Seleccione Almacén</option>
                <?php foreach($almacenes as $almacen):?>
                  <option value="<?php echo $almacen->alm_id?>" <?php if($almacen->alm_id==$movimiento->mov_origen_id):?> selected <?php endif?> ><?php echo $almacen->alm_nombre?></option>
                <?php endforeach?>
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="destino">Almacén Destino</label>
              <select id="destino" class="form-control input-sm" <?php if($movimiento->mov_id>0):?> disabled <?php endif?>>
                <option value="">Seleccione Almacén</option>
                <?php foreach($almacenes as $almacen):?>
                  <option value="<?php echo $almacen->alm_id?>" <?php if($almacen->alm_id==$movimiento->mov_destino_id):?> selected <?php endif?> ><?php echo $almacen->alm_nombre?></option>
                <?php endforeach?>
              </select>
            </div>
          </div>
       		<div class="col-md-4">
       			<div class="form-group">
       				<label for="fecha_movimiento">Fecha</label>
       				<input type="date" id="fecha_movimiento" class="form-control input-sm" value="<?php echo $movimiento->mov_fecha?>">
       			</div>
       		</div>
       	</div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="observacion">Observaciòn</label>
              <textarea id="observacion" rows="3" class="form-control"><?php echo $movimiento->mov_observacion?></textarea>
            </div>
          </div> 
        </div>
        <div id="movimiento_detalle" <?php if($movimiento->mov_id==''):?> style="display: none" <?php endif?> >
          <div class="row">
            <div class="col-md-7">
              <div class="form-group">
                <label for="producto">Producto</label>
                <input type="text" name="producto" id="producto" class="descripcion-item input-sm form-control">
                <div id="data_producto"><input type="hidden" name="prod_id[]" id="prod_id"></div>
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
          <div id="grid_movimientos"></div>          
        </div>

       </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" id="btn_guardar">Guardar</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
  <script>
  	$(document).ready(function(e){
      textoBoton();
      //buscar producto
        $('.descripcion-item').autocomplete({
            source : '<?PHP echo base_url();?>index.php/movimientos/buscador_item',
            minLength : 2,
            select : function (event,ui){                
                var data_producto = '<input class="val-descrip"  type="hidden" value="'+ ui.item.id + '" name = "prod_id" id = "prod_id" >';
                $('#data_producto').html(data_producto);
                
            },
            change : function(event,ui){
                if(!ui.item){
                    //si es nota no se pondrá vacio
                    var tipoDocumento = $("#tipo_documento").val();
                    if(tipoDocumento=='1' || tipoDocumento=='3')
                    {
                        this.value = '';
                        $('#item_id').val(''); 
                        $('#importe').val('');                        
                    }

                }
            }                
        });
      //guardar movimiento
      $("#btn_guardar").click(function(e){
        e.preventDefault();
        $(".has-error.has-feedback").removeClass('has-error has-feedback');
        var datos = {
                mov_id:$("#mov_id").val(),
                fecha_movimiento:$("#fecha_movimiento").val(),
                origen:$("#origen").val(),
                destino:$("#destino").val(),
                observacion:$("#observacion").val()
              }; 

        $.ajax({
          url:'<?php echo base_url()?>index.php/movimientos/guardarMovimiento',
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
              toast('success', 1500, 'movimiento agregado');
              $("#mov_id").val(response.idMovimiento);
              $("#movimiento_detalle").css("display","block");
              dataSourceMovimiento.read();
              dataSource.read();
              textoBoton();
            }
          }
        });                     
      });
  		//guardar prodcuto ingreso
  		$("#btn_agregar_producto").click(function(e){
  			e.preventDefault();
  			$(".has-error.has-feedback").removeClass('has-error has-feedback');
  			var datos = {
  							mov_id:$("#mov_id").val(),
  							producto:$("#prod_id").val(),
  							cantidad:$("#cantidad").val(),
  							origen:$("#origen").val(),
  							destino:$("#destino").val()
  						};

  			$.ajax({
  				url:'<?php echo base_url()?>index.php/movimientos/guardarProductoMovimiento',
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
  						if(response.tipo == '2')
  						{
  							toast("error", 3000, "El producto no se escuentra en almacén origen o la cantidad solicitada no hay en almacén");
  						}
  					}
  					if(response.status == STATUS_OK)
  					{
  						toast('success', 1500, 'Producto agregado');
              			$("#cantidad").val("");
                    $("#producto").val("");
                    $("#prod_id").val("")
              			//$('#producto').prop('selectedIndex',0);
  						      dataSourceMovimiento.read();
              			dataSource.read();
  					}
  				}
  			});
  					
  		});

      var dataSourceMovimiento = new kendo.data.DataSource({
               transport: {
                  read: {
                      url:"<?php echo base_url()?>index.php/movimientos/getMainListDetail/",
                      dataType:"json",
                      method:'post',
                      data:function(){
                          return {
                              mov_id:function(){
                                  return $("#mov_id").val();
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
    $("#grid_movimientos").kendoGrid({
        dataSource: dataSourceMovimiento,
        height: 250,
        sortable: true,
        pageable: true,
        columns: [
                { field: "prod_codigo", title:"CODIGO", width: "70px" },
                { field: "prod_nombre", title:"PRODUCTO", width: "120px" },
                { field: "movd_cantidad", title:"CANTIDAD",width:"70px" },
                { field: "movd_eliminar", title:"&nbsp;",width:"70px",template:"#= movd_eliminar #"}
        ],
        dataBound:function(e){
            //editar producto
            $(".btn_eliminar_detalle").click(function(e){
                e.preventDefault();
                var idDetalle = $(this).data('id');
                var msg = $(this).data('msg');
                var url = '<?php echo base_url()?>index.php/movimientos/eliminarDetalleMovimiento/'+idDetalle
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
                                            toast('success', 1500, 'Movimiento eliminado');
                                            dataSourceMovimiento.read();
                                            dataSource.read();
                                        }
                                        if(response.status == STATUS_FAIL)
                                        {
                                            toast('error', 1500 ,'No se puedo eliminar Movimiento.');
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
      var mov_id = $("#mov_id").val();
      var texto = (mov_id=='')?'Siguiente':'Guardar';
      $("#btn_guardar").text(texto);
    } 
  </script>
