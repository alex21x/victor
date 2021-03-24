 <!--  modal nuevo cliente -->
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closeModalNuevoCliente"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Nuevo Cliente</h4>
      </div>
      <div class="modal-body" style="height:600px;">
        <div class="container">
    <!-- Example row of columns -->
    <div class="row">       
        <div class="col-md-6">           
            <div align="center"><h2>Ingresar Cliente</h2></div>
            <form class="form-horizontal" role="form"  method="POST" id="formNuevoCliente">
                <div class="form-group">
                    <label for="tipo_cliente" class="col-xs-4 col-md-5 col-lg-5 text-right">Tipo Cliente :</label>
                    <div class="col-xs-8 col-md-7 col-lg-7">
                        <select class="form-control" name="tipo_cliente" id="tipo_cliente" required="">
                            <option>Seleccionar</option>
                            <?PHP foreach ($tipo_clientes as $value_tipo_clientes) { ?>
                                <option value="<?PHP echo $value_tipo_clientes['id'].'xx-xx-xx'.$value_tipo_clientes['tipo_cliente']; ?>"><?PHP echo $value_tipo_clientes['tipo_cliente']; ?></option>
                                <?PHP }?>                            
                        </select>
                    </div>
                </div>
                <div id="datos">    
                    <div class="form-group">
                        <label id="lbl_DNI_RUC" for="ruc" class="col-xs-4 col-md-5 col-lg-5 control-label text-right">Ruc :</label>
                        <div class="col-xs-5 col-md-5 col-lg-5">
                            <input type="number" class="form-control" name="ruc" id="ruc" placeholder="RUC" oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
                        </div>
                        <div class="col-xs-1 col-md-1 col-lg-1 dni_auto">           
                          <label class="checkbox-inline "><input type="checkbox" name="dni_auto" id="dni_auto" value="">auto</label>
                        </div>                        
                        <div class="col-xs-1 col-md-1 col-lg-1">
                          <label class="checkbox-inline"><span class="glyphicon glyphicon-search searchCustomer">buscar</span></label>      
                        </div> 
                    </div>
                    <div class="form-group">
                        <label id="lbl_RAZ_APE" for="razon_social" class="col-xs-4 col-md-5 col-lg-5 control-label text-right">Razón Social :</label>
                        <div class="col-xs-8 col-md-7 col-lg-7">
                            <input type="text" class="form-control" name="razon_social" id="razon_social" placeholder="razon_social" required="">
                        </div>
                    </div>                                
                    <div class="form-group">
                        <label for="domicilio1" class="col-xs-4 col-md-5 col-lg-5 control-label text-right">Domicilio <label style="color: red;">(*)</label></label>
                        <div class="col-xs-8 col-md-7 col-lg-7">
                            <input type="text" class="form-control" name="domicilio1" id="domicilio1" placeholder="domicilio">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email" class="col-xs-4 col-md-5 col-lg-5 control-label text-right">Email :</label>
                        <div class="col-xs-8 col-md-7 col-lg-7">
                            <input type="text" class="form-control" name="email" id="email" placeholder="email">
                        </div>
                    </div >                 
                    <div class="form-group">
                        <label for="telefono_movil_1" class="col-xs-4 col-md-5 col-lg-5 control-label text-right">Telefono movil :</label>
                        <div class="col-xs-8 col-md-7 col-lg-7">
                            <input type="number" class="form-control" name="telefono_movil_1" id="telefono_movil_1" placeholder="telefono movil">
                        </div>
                    </div>
                     <div class="form-group">
                        <label for="telefono_movil_1" class="col-xs-4 col-md-5 col-lg-5 control-label"  style="text-align: center;"><label style="color: red;">(*) Campos obligatorios</label></label>
                       
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-6 col-sm-8">
                            <input class="btn btn-primary" id="guardarNuevoCliente" value="Guardar" >
                        </div>
                    </div>
                </div>
            </form>

        </div>
        <div class="col-md-3">
        </div>
    </div>
</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>            
    </div>  
</div>

<script type="text/javascript">
 $("#formNuevoCliente")[0].reset();

 $("#datos").hide();
 $("#tipo_cliente").change(function () {
            var op = $("#tipo_cliente option:selected").val();
            var array = op.split('xx-xx-xx');
            $("#datos").show();
            if (array[0] == 1) {
                $("#lbl_DNI_RUC").html('DNI <label style="color: red;">(*)</label>');
                $("#ruc").attr("placeholder","DNI");
                $("#ruc").attr("maxlength","8");

                $("#lbl_RAZ_APE").html('Nombres <label style="color: red;">(*)</label>');
                $("#razon_social").attr("placeholder","Nombres");
                $("#nombres").show();
                $(".dni_auto").show();
            }else{
                $("#lbl_DNI_RUC").html('RUC <label style="color: red;">(*)</label>');
                $("#ruc").attr("placeholder","RUC");
                $("#ruc").attr("maxlength","11");
                
                $("#lbl_RAZ_APE").html('Razon Social <label style="color: red;">(*)</label>');
                $("#razon_social").attr("placeholder","razon social");
                $("#nombres").hide();
                $(".dni_auto").hide();
            }

        });


 //SearchCustomer        
 $('.searchCustomer').on('click',function(){           
           var op = $("#tipo_cliente option:selected").val();
           var array = op.split('xx-xx-xx');
           var tipoCliente =  array[0];
                      
           var ruc = $('#ruc').val();           
           var url = '<?= base_url();?>index.php/clientes/searchCustomer';
           
           $.ajax({
               type: 'POST',
               url : url,
               dataType:'json',
               data : {tipoCliente : tipoCliente, ruc: ruc},
               success : function(datosCliente){                   
                if(datosCliente.status == STATUS_OK){                    
                    var datos = eval(datosCliente);                             
                    if(datos.typeCustomer == 1){                    
                    $("input[name*='razon_social']").val(datos.paterno+' '+datos.materno+' '+datos.nombres);
                    $("input[name*='domicilio1']").val('LIMA');}
                    if(datos.typeCustomer == 2){
                    $("input[name*='razon_social']").val(datos.razonSocial);    
                    $("input[name*='domicilio1']").val(datos.direccionFiscal);}
                }                      
                if(datosCliente.status == STATUS_FAIL){                                    
                    toast("error",1500, datosCliente.msg);
                }
               }                              
           });
           return false;
       });

 $('#guardarNuevoCliente').click(function(e){
            e.preventDefault();
        
        var url = "<?PHP echo base_url() ?>index.php/clientes/grabar_para_comprobante";
        $.ajax({                        
           type: "POST",                 
           url: url,                     
           data: $("#formNuevoCliente").serialize(), 
           success: function(data)             
           {
              var cliente = JSON.parse(data);
              if(cliente['success']==4){
                 toast("success", 1500, 'Cliente ingresado con exito');
                 $("#formNuevoCliente")[0].reset(); 
                 $("#closeModalNuevoCliente").click(); 
                 $("#myModalNuevoCliente").modal('hide');
                 $('#cliente').val(cliente['nombre']);
                 $('#direccion').val(cliente['direccion']);      
                 $('#cliente_id').val(cliente['id']); 
                 $("#datos").hide();
                 $("#limitado_detalle").hide();

              }else{
                 if(cliente['success']==1){
                    toast("error",3000, "Ingrese número de documento");
                 }else if(cliente['success']==2){
                    toast("error",3000, "Ingrese nombre o Razón Social");
                 }else{
                    toast("error",3000, "Ingrese domicilio");
                 }
                  
              }
                     
           }
       });
    });

    //DNI AUTOMATICO
    $(document).on("click",'#dni_auto',function(){            
        if($('#dni_auto').prop('checked')){           
            $.ajax({
                url: '<?= base_url()?>index.php/clientes/dni_auto',
                dataType : 'JSON',
                method: 'POST',
                success: function(response){                  
                  if(response.status == STATUS_OK){                    
                      $("#ruc").val(response.dni_auto);
                  }
                }
            })
        } else{
          $("#ruc").val('');
        }             
    });

 </script>