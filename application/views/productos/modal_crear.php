  <?php

    $cat_id=0;
    $med_id=0;
    $tipoitemid=0;
    $estilo ='';
    if (isset($producto)) {
      $cat_id = $producto->prod_categoria_id;
      $med_id = $producto->prod_medida_id;
      $estilo ='readonly';
    } 
   ?>
   <style type="text/css">
     img{
        margin-top: 10px;
        width: 120px;
        height: 130px;        
    }     
   </style>
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>        
        <div class="text-center">
          <h4 class="modal-title">Registro Producto</h4>
          <div id="images_gallery">
          <img  src="<?= base_url().'images/productos/'.$producto->prod_imagen;?>"></div></div>
      </div>
      <div class="modal-body">
       <form id="formProducto">
       	<input type="hidden" id="prod_id" name="prod_id" value="<?php echo $producto->prod_id;?>">
       	<div class="row">
                        
          <div class="col-md-6">
            <div class="form-group">
              <label for="prod_codigo_sunat">Código Producto Sunat</label>
              <input type="text" id="prod_codigo_sunat" name="prod_codigo_sunat"  list="lista_productos_sunat" class="form-control input-sm" value="<?php echo $producto->prod_codigo_sunat;?>" <?php echo ($producto->prod_id==1)?"readonly":"";?> > 
               <datalist id="lista_productos_sunat" >
                                  
               </datalist>
            </div>
          </div>
       		<div class="col-md-6">
       			<div class="form-group">
       				<label for="prod_codigo">Código</label>
       				<input type="text" id="prod_codigo" name="prod_codigo" class="form-control input-sm" value="<?php echo $producto->prod_codigo;?>" <?php echo ($producto->prod_id==1)?"readonly":"";?>>
       			</div>
       		</div>
       		<div class="col-md-6">
       			<div class="form-group">
       				<label for="prod_nombre">Nombre/Descripción</label>
       				<input type="text" id="prod_nombre" name="prod_nombre" class="form-control input-sm" value="<?php echo $producto->prod_nombre;?>" <?php echo ($producto->prod_id==1)?"readonly":"";?>>
       			</div>
       		</div>
           <div class="col-md-3">
            <div class="form-group">
              <label for="prod_imagen">Imagen</label>
              <input type="file" id="prod_imagen" name="prod_imagen" class="form-control input-sm" value="<?php echo $producto->prod_imagen;?>">
            </div>
          </div> 
          <div class="col-md-3">
            <div class="form-group">
              <label for="prod_codigo_barra">Codigo Barra </label>
              <input type="text" id="prod_codigo_barra" name="prod_codigo_barra" class="form-control input-sm" value="<?php echo $producto->prod_codigo_barra;?>">
            </div>
          </div>  
        </div>  
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="prod_categoria">Categoria</label>
              <select class="form-control" id="prod_categoria" name="prod_categoria">
                <option value="">Seleccione</option>
                <?php foreach ($categoria as $value): ?>
                  <option value="<?php echo $value->cat_id;?>" <?php if($value->cat_id == $producto->prod_categoria_id):?> selected <?php endif?> > <?php echo $value->cat_nombre;?></option>  
                <?php endforeach ?>                              
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="prod_medida"> Unidad/Medida </label>
              <select class="form-control" id="prod_medida" name="prod_medida" <?php echo ($producto->prod_id==1)?"readonly":"";?>>
                <option value="">Seleccione</option>
                <?php foreach ($medida as $valor):?>
                    <option value="<?php echo $valor->medida_id;?>" <?php if($valor->medida_id == $producto->prod_medida_id):?> selected <?php endif?>  > <?php echo $valor->medida_nombre;?></option>  
                <?php endforeach ?>                            
              </select>
            </div>
          </div> 
        </div>
        <div class="row">

  <input type="hidden" value="<?php echo $producto->prod_tipo?>" name="prod_tipo" id="prod_tipo">
        <div class="panel panel-default">
           <div class="panel-heading">PRECIO</div>
          <div class="panel-body">
            <div class="col-md-2">
            <div class="form-group">
              <label for="prod_precio_publico">Precio 01</label>
              <input type="number" id="prod_precio_publico" name="prod_precio_publico" class="form-control input-sm" value="<?php echo $producto->prod_precio_publico;?>">
            </div>
          </div>  
          <div class="col-md-2">
            <div class="form-group">
              <label for="prod_precio_2">Precio 02</label>
              <input type="number" id="prod_precio_2" name="prod_precio_2" class="form-control input-sm" value="<?php echo $producto->prod_precio_2;?>">
            </div>
          </div>  
          <div class="col-md-2">
            <div class="form-group">
              <label for="prod_precio_3">Precio 03</label>
              <input type="number" id="prod_precio_3" name="prod_precio_3" class="form-control input-sm" value="<?php echo $producto->prod_precio_3;?>">
            </div>
          </div>  
          <div class="col-md-2">
            <div class="form-group">
              <label for="prod_precio_4">Precio 04</label>
              <input type="number" id="prod_precio_4" name="prod_precio_4" class="form-control input-sm" value="<?php echo $producto->prod_precio_4;?>">
            </div>
          </div>  
          <div class="col-md-2">
            <div class="form-group">
              <label for="prod_precio_5">Precio 05</label>
              <input type="number" id="prod_precio_5" name="prod_precio_5" class="form-control input-sm" value="<?php echo $producto->prod_precio_5;?>">
            </div>
          </div>  
          <div class="col-md-2">
            <div class="form-group">
              <label for="prod_precio_compra">P.Compra</label>
              <input type="number" id="prod_precio_compra" name="prod_precio_compra" class="form-control input-sm" value="<?php echo $producto->prod_precio_compra;?>">
            </div>
          </div>
          </div>


        </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="comision_vendedor">Comis. Vendedor(%)</label>
              <input type="text" id="prod_comision_vendedor" name="prod_comision_vendedor" class="form-control input-sm" value="<?php echo $producto->prod_comision_vendedor;?>">
            </div>
          </div> 

          <div class="col-md-4">
            <div class="form-group">
              <label for="prod_garantia">Garantia</label>
              <input type="text" id="prod_garantia" name="prod_garantia" class="form-control input-sm" value="<?php echo $producto->prod_garantia;?>">
            </div>
          </div> 

          <div class="col-md-4">
            <div class="form-group">
              <label for="prod_descuento">Descuento</label>
              <input type="number" id="prod_descuento" name="prod_descuento" class="form-control input-sm" value="<?php echo $producto->prod_descuento;?>">
            </div>
          </div> 


          <div class="col-md-4">
            <div class="form-group">
              <label for="prod_caducidad">F.Caducidad</label>
              <input type="date" id="prod_caducidad" name="prod_caducidad" class="form-control input-sm" value="<?php echo $producto->prod_caducidad;?>">
            </div>
          </div> 

          <div id="div_stock" >
          <div class="col-md-4">
            <div class="form-group">
              <?php if($producto->prod_id!=''){ ?>
                <label for="stock_inicial">Stock</label>
              <?php }else{ ?>
                <label for="stock_inicial">Stock Inicial</label>
              <?php } ?>
              
              <input type="number" id="stock_inicial" name="stock_inicial" class="form-control input-sm is-invalid has-danger" value="<?php echo $producto->prod_stock;?>">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="prod_cantidad_minima">Cantidad Mínima </label>
              <input type="number" id="prod_cantidad_minima" name="prod_cantidad_minima" class="form-control input-sm is-invalid has-danger" value="<?php echo $producto->prod_cantidad_minima;?>">
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
               <label for="linea">Linea</label>
              <select class="form-control" id="linea" name="linea">
                <option value="">Seleccione</option>
                <?php foreach ($lineas as $value): ?>
                  <option value="<?php echo $value->lin_id;?>" <?php if($value->lin_id == $producto->prod_linea_id):?> selected <?php endif?> > <?php echo $value->lin_nombre;?></option>
                <?php endforeach ?>                              
              </select>              
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="marca">Marca</label>
              <select class="form-control" id="marca" name="marca">
                <option value="">Seleccione</option>
                <?php foreach ($marcas as $value): ?>
                  <option value="<?php echo $value->mar_id;?>" <?php if($value->mar_id == $producto->prod_marca_id):?> selected <?php endif?> > <?php echo $value->mar_nombre;?></option>
                <?php endforeach ?>
              </select>              
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
               <label for="ubicacion">Ubicación</label>
               <input type="text" class="form-control" name="ubicacion" id="ubicacion" value="<?= $producto->prod_ubicacion;?>">              
            </div>
          </div>          
         </div>       
          <div class="col-md-12">
            <div class="form-group">
              <label for="prod_observaciones">Observaciones</label>
              <input type="text" id="prod_observaciones" name="prod_observaciones" class="form-control input-sm" value="<?php echo $producto->prod_observaciones;?>">
            </div>
          </div> 

          <div class="col-md-4" style="display: block;">
            <div class="form-group">
              <label for="almacen">Almacén</label>
              <select id="almacen" class="form-control input-sm" name="almacen" disabled="">
                <option value="">Seleccione Almacén</option>
                <?php foreach($almacenes as $almacen):?>
                  <option value="<?php echo $almacen->alm_id?>" <?php if($this->session->userdata('almacen_id')==$almacen->alm_id):?> selected <?php endif?> ><?php echo $almacen->alm_nombre?></option>
                <?php endforeach?>
              </select>
            </div>
          </div>         
          <!--<div class="col-md-4" style="display: block;">
            <div class="form-group">
              <label for="empresa">Empresa</label>
              <select id="empresa" class="form-control input-sm" name="empresa">
                <option value="">Seleccione Empresa</option>
                <?php foreach($empresas as $empresa):?>
                  <option value="<?php echo $empresa->id?>" <?php if($this->session->userdata('almacen_id')==$empresa->alm_id):?> selected <?php endif?> ><?php echo $empresa->empresa?></option>
                <?php endforeach?>
              </select>
            </div>
          </div> -->
          <div class="col-md-2">
            <div class="form-group">
              <label for="prod_precio_compra">Producto</label>
              <input type="checkbox" id="prod_tipo_insumo" name="prod_tipo_insumo" class="form-control input-sm" value="<?php echo $producto->prod_precio_compra;?>">
            </div>
          </div>

          <div class="col-md-2">
            <div class="form-group">
              <label for="prod_precio_compra">Insumo</label>
              <input type="checkbox" id="prod_tipo_insumo" name="prod_tipo_insumo" class="form-control input-sm" value="<?php echo $producto->prod_precio_compra;?>">
            </div>
          </div>        
        <input type="hidden" id="stock_value" value="<?php echo $producto->prod_stock;?>">
          <!--<div class="col-md-4">
            <div class="form-group">
              <label for="stock">Stock</label>
              <input type="text" id="stock" class="form-control input-sm" value="<?php echo $producto->prod_stock;?>" readonly> 
            </div>
          </div>-->            
        </div>
       	<div class="row">

          
       	</div>    

        <input type="hidden" name="codigo_auto_num_m" id="codigo_auto_num_m">
       </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btn_guardar_producto">Guardar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
  <script>    

    $(function(){

            if( $("#codigo_auto_num").val() == 1 ) {                      
                $("#prod_codigo").attr("readonly",true);
                            
            }else{
          
              if($("#prod_id").val()!=1){                
                $("#prod_codigo").attr("readonly",false);                
              }
            }

            $("#prod_codigo_sunat").keyup(function(){
                  var texto = $(this).val();
                  $.getJSON("<?PHP echo base_url() ?>index.php/productos/buscar_producto_sunat",{texto})
                   .done(function(json){
                      
                       var html = '';
                       $.each(json,function(index,value){
                         
                           html+= '<option value="'+ value.ps_cod+ ' - ' + value.ps_nom + '">';
                       });

                       $("#lista_productos_sunat").html(html);
                   })
            });

           

          $("#prod_codigo_sunat").change(function(){
              var opcion = $(this).val();
              var guion = opcion.search("-");
              var cod = opcion.substr(0,guion-1);

              $.getJSON("<?PHP echo base_url() ?>index.php/productos/seleccionar_producto_sunat",{cod})
               .done(function(json){
             
                  //$("#prod_nombre").val(json.ps_nom);
                  $("#prod_nombre").val('');
                                  
               })              
          });
    })

  	$(document).ready(function(e){
      $("#codigo_auto_num_m").val($("#codigo_auto_num").val());

      <?php if($producto->prod_tipo==2){
         echo "$('#div_stock').hide();";  
      }
      ?>

     $("#prod_medida").change(function(){
        var tipo = $(this).val();
        if(tipo!=59){
          $("#prod_tipo").val(1);
          $("#div_stock").show();
        }else{
          $("#prod_tipo").val(2);
          $("#div_stock").hide();
        }
     });


  		//guardar
      $("#btn_guardar_producto").on('click',function(){
        $("#formProducto").submit();
      });

  		$("#formProducto").on('submit',function(e){
  			e.preventDefault();
  			$(".has-error.has-feedback").removeClass('has-error has-feedback');

  		        
              var time = parseInt($("#stock_inicial").val())*30;
              toast('success',time , 'Espere mientras se guarda el producto ... ');
  			$.ajax({
  				url:'<?php echo base_url()?>index.php/productos/guardarProducto',
  				dataType:'json',            				
          method : "POST",
          data:new FormData(this),
          contentType:false,
          processData:false,  				
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
  							toast('error', 1500, 'El còdigo ya està en uso');
  						}
  					}
  					if(response.status == STATUS_OK)
  					{              
  						toast('success', 1500, 'Producto ingresado');
  						dataSource.read();
  						$("#myModal").modal('hide');
  					}
  				}
  			});
  					
  		});
      });
  	    
    //CAMBIO DE IMAGEN
    $(document).on('change','#prod_imagen',function(){      
        $.ajax({
            url : "<?= base_url()?>index.php/productos/galeria_g",
            method : "POST",
            data: new FormData(document.getElementById("formProducto")),
            contentType:false,
            processData:false,
            success: function(data){            
            $('#images_gallery').html(data); 
              }
            })
      });    
  </script>
