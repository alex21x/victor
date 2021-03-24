<style type="text/css">
    .serNum{
        margin-top: 20px;
    }

</style>
<div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <div class="modal-title"><h3>Registrar Nueva Serie</h3></div>
                    </div>
                    <div class="modal-body">                        
                        <form id="formSerNum">
                        <input type="hidden" name="id" value="<?= $serNum->id?>">
                        <input type="hidden" name="empresa" id="empresa" value="<?php echo $empresa['id']?>"/>
                        <div class="row serNum">
                            <div class="col-xs-4 col-md-4 col-lg-4">
                                Tipo de Documento
                            </div>
                            <div class="col-xs-6 col-md-6 col-lg-6">
                                <select  class="form-control" name="tipo_documento" id="tipo_documento" required>
                                <option value="">Seleccione tipo de documento</option>
                                <?php foreach($tipo_documentos as $value){ 
                                        $SELECTED =  ($value['id'] == $serNum->tipo_documento_id)? 'SELECTED' : '';?>
                                    <?php if($value['id']!=11){ ?>
                                       <option value="<?php echo $value['id']?>" <?= $SELECTED;?>><?php echo $value['tipo_documento']?></option>
                                    <?php } ?>   
                                <?php } ?>                                
                            </select>
                            </div>
                        </div>
                        <div class="row serNum">
                            <div class="col-xs-4 col-md-4 col-lg-4">
                                Almacén
                            </div>
                            <div class="col-xs-6 col-md-6 col-lg-6">
                                <select  class="form-control" name="almacen" id="almacen" required>
                                <option value="">Seleccione Almacén</option> 
                                <?php foreach($almacenes as $value){
                                        $SELECTED = ($value->alm_id == $serNum->almacen_id)? 'SELECTED' : '';?>
                                       <option value="<?php echo $value->alm_id?>" <?= $SELECTED;?>><?php echo $value->alm_nombre?></option>
                                <?php } ?>                                    
                                </select>
                            </div>
                        </div>                            
                        <div class="row serNum">
                            <div class="col-xs-4 col-md-4 col-lg-4">
                                Serie
                            </div>
                            <div class="col-xs-6 col-md-6 col-lg-6">
                                <input type="text"  class="form-control" name="serie" id="serie" value="<?= $serNum->serie;?>" required>
                            </div>
                        </div>                         
                        <div class="row serNum">
                            <div class="col-xs-4 col-md-4 col-lg-4">
                                Número
                            </div>
                            <div class="col-xs-6 col-md-6 col-lg-6">
                                <input type="number"  class="form-control" name="numero" id="numero" value="<?= $serNum->numero;?>" required>
                            </div>
                        </div>   
                    </div>  
                    <div class="modal-footer">                                  
                        <div class="row serNum"> 
                            <div class="col-xs-6 col-md-6 col-lg-6 col-md-offset-6">                                                           
                                <button type="button" id="btn_guardar_serNum" class="btn btn-primary">Guardar Nueva Serie</button>
                            </div>    
                        </div> 
                    </div>
                </div> 
                </div>                                                           
        </form>
<script>
    $(document).ready(function(e){
        //guardar
        $("#btn_guardar_serNum").click(function(e){
            e.preventDefault();
            $(".has-error").removeClass('has-error');            
            $.ajax({
                url:'<?php echo base_url()?>index.php/serNums/guardarSerNum',
                dataType:'json',
                data:$("#formSerNum").serialize(),
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
                        toast('success', 1500, 'se registro la Serie');
                        dataSource.read();
                        $("#myModal").modal('hide');
                    }
                }
            });
                    
        });
    });
  </script>