<style type="text/css">
    .pseToken{
        margin-top: 20px;
    }

</style>
<div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <div class="modal-title"><h3>Registrar Nueva PSE/TOKEN</h3></div>
                    </div>
                    <div class="modal-body">                        
                        <form id="formPseToken">
                        <input type="hidden" name="id" value="<?= $pseToken->id?>">                                        
                        <div class="row pseToken">
                            <div class="col-xs-4 col-md-4 col-lg-4">
                                Almacén
                            </div>
                            <div class="col-xs-6 col-md-6 col-lg-6">
                                <select  class="form-control" name="almacen" id="almacen" required>
                                <option value="">Seleccione Almacén</option> 
                                <?php foreach($almacenes as $value){
                                        $SELECTED = ($value->alm_id == $pseToken->almacen_id)? 'SELECTED' : '';?>
                                       <option value="<?php echo $value->alm_id?>" <?= $SELECTED;?>><?php echo $value->alm_nombre?></option>
                                <?php } ?>                                    
                                </select>
                            </div>
                        </div>                            
                        <div class="row pseToken">
                            <div class="col-xs-4 col-md-4 col-lg-4">
                                Ruta
                            </div>
                            <div class="col-xs-6 col-md-6 col-lg-6">
                                <input type="text"  class="form-control" name="ruta" id="ruta" value="<?= $pseToken->ruta;?>" required>
                            </div>
                        </div>                         
                        <div class="row pseToken">
                            <div class="col-xs-4 col-md-4 col-lg-4">
                                Token
                            </div>
                            <div class="col-xs-6 col-md-6 col-lg-6">
                                <input type="text"  class="form-control" name="token" id="token" value="<?= $pseToken->token;?>" required>
                            </div>
                        </div>
                    </div>  
                    <div class="modal-footer">                                  
                        <div class="row serNum"> 
                            <div class="col-xs-6 col-md-6 col-lg-6 col-md-offset-6">                                                           
                                <button type="button" id="btn_guardar_pseToken" class="btn btn-primary">Guardar PSE/TOKEN</button>
                            </div>    
                        </div> 
                    </div>
                </div> 
                </div>                                                           
        </form>         


<script>
    $(document).ready(function(e){
        //guardar
        $("#btn_guardar_pseToken").click(function(e){
            e.preventDefault();
            $(".has-error").removeClass('has-error');            
            $.ajax({
                url:'<?php echo base_url()?>index.php/pse_token/guardarPseToken',
                dataType:'json',
                data:$("#formPseToken").serialize(),
                method:'post',
                success:function(response){
                    if(response.status == STATUS_FAIL)
                    {
                        if(response.tipo == '1')
                        {
                            var errores = response.errores;
                            toast('error', 1500, 'Faltan ingresar datos.');
                            $.each(errores, function(index, value){
                                $("#"+index).parent().addClass('has-error');
                            });
                        }
                    }
                    if(response.status == STATUS_OK)
                    {
                        toast('success', 1500, 'se registro el PSE/TOKEN');
                        dataSource.read();
                        $("#myModal").modal('hide');
                    }
                }
            });                    
        });
    });
  </script>