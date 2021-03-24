<style type="text/css" media="screen">

#tbl td{
        border:1px solid #95A5A6;
        font-weight: 600;
        padding: 10px 10px;
        font-size: 13px;
    }
    
</style>

<h2 align="center">Kárdex Físico por producto</h2>
<br>
<div class="container">
    <div class="row">
   
                  <div class="col-md-6"  style="padding:10px;">
                  
                      <select id="almacen" class="form-control input-sm" name="almacen">
                        <?php foreach($almacenes as $almacen):?>
                       
                               <option value="<?php echo $almacen->alm_id?>" <?php if($this->session->userdata("almacen_id")==$almacen->alm_id):?> selected <?php endif?> ><?php echo $almacen->alm_nombre?></option>
                            
                        <?php endforeach?>
                      </select>
                </div> 

        <div class="col-md-6 form-inline"  style="padding:10px;">
     
                    <input class="form-control input-sm" type="text" name="fechai" id="fechai" placeholder="Desde">
                    
                    <input class="form-control input-sm" type="text" name="fechaf" id="fechaf" placeholder="Hasta">
        </div>     
        <div class="col-md-11" style="padding:10px;">
              <input type="text" class="form-control" name="producto" id="producto" placeholder="Buscar por código o descripción">
              <div id="data_prod"><input type="hidden" name="producto_id" id="producto_id"></div>
          </div>   
        <div class="col-md-1"  style="padding:10px;">
          <button class="btn btn-default" type="button" id="btn_buscar_producto"><span class="glyphicon glyphicon-search"></span></button>   
        </div>
           
        

    </div>
    <br>

    <div class="row">
        <div class="col-md-12" id="kardex">
        </div>    
    </div>
</div>
<!-- <?php echo base_url()?>index.php/productos/modificar/<?php echo $producto->prod_id?>-->
<script type="text/javascript">
    $(function(){

      // FECHA JAVASCRIPT
            $("#fechai").datepicker();
            $("#fechaf").datepicker();

            $("#fechai").change(function(){
              var fi = $(this).val();
              $("#fechaf").val(fi);
            });



        $("#btn_buscar_producto").click(function(){
            //var opcion = $("#producto").val();
            //var guion = opcion.search("-");
            //var producto_id = opcion.substr(0,guion-1);
            var producto_id = $("#producto_id").val();
            var almacen = $("#almacen").val();
            var fechai = $("#fechai").val();
            var fechaf = $("#fechaf").val();

            $.getJSON("<?php echo base_url()?>index.php/productos/kardex_fisico_producto",{producto_id,almacen,fechai,fechaf})
             .done(function(json){
                
                  
                  var table = '';
                      table+= '<table width="100%;" id="tbl">';
                      table+= '<tr><td style="background:#BFC9CA;">FECHA</td>';
                      table+= '<td style="background:#BFC9CA;">ALMACEN</td>';
                      table+= '<td style="background:#BFC9CA;">PRODUCTO</td>';
                      table+= '<td style="background:#BFC9CA;">DOCUMENTO</td>';
                      table+= '<td style="background:#BFC9CA;">CONCEPTO</td>';
                      table+= '<td style="background:#2980B9;color:#fff;">ENTRADAS</td>';
                      table+= '<td style="background:#E74C3C;color:#fff;">SALIDAS</td>';
                      table+= '<td style="background:#F4D03F;color:#fff;">EXISTENCIAS</td></tr>';
                  $.each(json,function(index,value){
                      table+= '<tr><td style="font-weight:normal;">'+ value.k_fecha +'</td>';
                      table+= '<td style="font-weight:normal;">'+ value.alm_nombre +'</td>';
                      table+= '<td style="font-weight:normal;">'+ value.prod_nombre +'</td>';

                      if(value.k_serie!=null){
                        table+= '<td style="font-weight:normal;">'+ value.k_serie +'</td>';
                      }else{
                        table+= '<td style="font-weight:normal;">-</td>';
                      }
                      
                      table+= '<td style="font-weight:normal;">'+ value.k_concepto +'</td>';

                      if(parseFloat(value.k_ecantidad)>0){
                        table+= '<td>'+ value.k_ecantidad +'</td>';
                      }else{
                        table+= '<td style="font-weight:normal;">'+ value.k_ecantidad +'</td>';
                      }
                      
                      if(parseFloat(value.k_scantidad)>0){
                        table+= '<td>'+ value.k_scantidad +'</td>';
                      }else{
                        table+= '<td style="font-weight:normal;">'+ value.k_scantidad +'</td>';
                      }

                      if(parseFloat(value.k_excantidad)>0){
                        table+= '<td>'+ value.k_excantidad +'</td>';
                      }else{
                        table+= '<td style="font-weight:normal;">'+ value.k_excantidad +'</td>';
                      }

                  })

                  table+= '</table>';

                  $("#kardex").html(table);

             })

        })
    })

    //BUSCADOR PRODUCTO
    $('#producto').autocomplete({
            source : '<?PHP echo base_url();?>index.php/productos/buscador_item',
            minLength : 2,
            select : function (event,ui){                                        
                var data_prod = '<input type="hidden" value="'+ ui.item.id + '" name = "producto_id" id = "producto_id" >';
                $('#data_prod').html(data_prod);                
            }
    });    
    
</script>