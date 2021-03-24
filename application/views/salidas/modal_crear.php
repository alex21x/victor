 <style>
  .ui-autocomplete{ z-index:2147483647; }
</style> 
  <div class="modal-dialog " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Nuevo salida</h4>
      </div>
      <div class="modal-body">
       <form>
       	<input type="hidden" id="sal_id" value="<?php echo $salida->sal_id?>">
       	<div class="row">
       		<div class="col-md-4">
       			<div class="form-group">
       				<label for="fecha_salida">Fecha</label>
       				<input type="date" id="fecha_salida" class="form-control input-sm" value="<?php echo $salida->sal_fecha?>">
       			</div>
       		</div>
          <div class="col-md-8">
            <div class="form-group">
              <label for="almacen">Almacén</label>
              <select id="almacen" class="form-control input-sm" <?php if($salida->sal_id>0):?> disabled <?php endif?> >
                <option value="">Seleccione Almacén</option>
                <?php foreach($almacenes as $almacen):?>
                  <option value="<?php echo $almacen->alm_id?>" <?php if($almacen->alm_id==$salida->sal_almacen_id):?> selected <?php endif?> ><?php echo $almacen->alm_nombre?></option>
                <?php endforeach?>
              </select>
            </div>
          </div>

       	</div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="observacion">Observaciòn</label>
              <textarea id="observacion" rows="3" class="form-control"><?php echo $salida->sal_observacion?></textarea>
            </div>
          </div> 
        </div>
        <div id="salida_detalle" <?php if($salida->sal_id==''):?> style="display: none" <?php endif?> >
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
          <div id="grid_salidas"></div>          
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
            source : '<?PHP echo base_url();?>index.php/salidas/buscador_item',
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


      //guardar ingreso
      $("#btn_guardar").click(function(e){
        e.preventDefault();
        $(".has-error.has-feedback").removeClass('has-error has-feedback');
        var datos = {
                sal_id:$("#sal_id").val(),
                fecha_salida:$("#fecha_salida").val(),
                observacion:$("#observacion").val(),
                almacen:$("#almacen").val()
              }; 
        $.ajax({
          url:'<?php echo base_url()?>index.php/salidas/guardarSalida',
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
              toast('success', 1500, 'salida agregado');
              $("#sal_id").val(response.idSalida);
              $("#salida_detalle").css("display","block");
              dataSourceSalida.read();
              dataSource.read();
              textoBoton();
            }
          }
        });                     
      });
  		//guardar prodcuto salida
  		$("#btn_agregar_producto").click(function(e){
  			e.preventDefault();
  			$(".has-error.has-feedback").removeClass('has-error has-feedback');
  			var datos = {
  							sal_id:$("#sal_id").val(),
  							producto:$("#prod_id").val(),
                fecha_salida:$("#fecha_salida").val(),
  							cantidad:$("#cantidad").val(),
                almacen:$("#almacen").val()
  						};

  			$.ajax({
  				url:'<?php echo base_url()?>index.php/salidas/guardarProductoSalida',
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
              $("#prod_id").val()
  						dataSourceSalida.read();
              dataSource.read();
  					}
  				}
  			});
  					
  		});

      var dataSourceSalida = new kendo.data.DataSource({
               transport: {
                  read: {
                      url:"<?php echo base_url()?>index.php/salidas/getMainListDetail/",
                      dataType:"json",
                      method:'post',
                      data:function(){
                          return {
                              sal_id:function(){
                                  return $("#sal_id").val();
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
    $("#grid_salidas").kendoGrid({
        dataSource: dataSourceSalida,
        height: 250,
        sortable: true,
        pageable: true,
        columns: [
                { field: "prod_codigo", title:"CODIGO", width: "70px" },
                { field: "prod_nombre", title:"PRODUCTO", width: "120px" },
                { field: "sald_cantidad", title:"CANTIDAD",width:"70px" },
                { field: "sald_eliminar", title:"&nbsp;",width:"70px",template:"#= sald_eliminar #"}
        ],
        dataBound:function(e){
            //editar producto
            $(".btn_eliminar_detalle").click(function(e){
                e.preventDefault();
                var idDetalle = $(this).data('id');
                var msg = $(this).data('msg');
                var url = '<?php echo base_url()?>index.php/salidas/eliminarDetalleSalida/'+idDetalle
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
      var sal_id = $("#sal_id").val();
      var texto = (sal_id=='')?'Siguiente':'Guardar';
      $("#btn_guardar").text(texto);
    } 
  </script>
