<script src="<?PHP echo base_url(); ?>assets/js/funciones.js"></script>
<script>

    $(document).ready(function(){        
        $('#cliente').autocomplete({
                source : '<?= base_url()?>index.php/comp_cli_per/buscador_cliente',
                minLength : 2,
                select : function(event, ui){
                    var data_cli = '<input type="hidden" value="'+ ui.item.id + '" name=" cliente_id" id = "cliente_id">';
                    $('#data_cli').html(data_cli);
                }                       
        });                        
                
        $('#tipo_documento').on('change',function(){                
            seleTipoDocumento();
        });                
        
        
        $('#desTipo').on('change',function(){                
            seleDesTipo();
        });
        
        
        function seleTipoDocumento(){
            selec = $('#tipo_documento option:selected').val();
            
           if(selec  == '1'){
                $('#desTip').css('display','block');
                $('#dMes').css('display','block');
                seleDesTipo();
            } else{
                $('#desTip').css('display','none');
                $('#dMes').css('display','none');
           }            
        }
        
        function seleDesTipo(){            
           selec = $('#desTipo option:selected').val();            
           if(selec  == '0')           
                $('#dMes').css('display','block');//dMes --> Display Mes
           else
                $('#dMes').css('display','none');
        }
                
        seleTipoDocumento();
      
      
      //Seleccionando mes            
      $('#mes').on('change',function(){
          
          mesSelec = $('#mes option:selected').val();
          concepto = $('#concepto').val();
          
          //Obteniendo Anio
          var y = new Date();                    
          concepto =  concepto + ' ' + mesSelec + ' ' +y.getFullYear();          
          $('.descripcion').val(concepto);
      })
      
      //Modificando el Concepto
      $('#concepto').on('keyup',function(){
         concepto = $('#concepto').val();
         $('.descripcion').val(concepto);
      });      
      
      
      //Submit Form2
      
      $('#form2').on('click',function(){          
            serie = $('#serie option:selected').val();
            $('#series').val(serie);                                
            $('#form22').submit();
      })           
    });

</script>
        <p class="bg-info">        
            <?= $this->session->flashdata('mensaje');?>
        </p>
        <div class="container">
            <form id="form1" method="POST" action="<?= base_url()?>index.php/comp_cli_per/index">
            <h2 class="text-center">Facturacion del Mes - Clientes Permanentes</h2><br>
            <div class="row">
                <div class="col-xs-5 col-md-5 col-lg-5">
                    <input class="form-control" type="text" name="cliente" id="cliente" placeholder="Cliente" value="<?= $cliente_selec;?>"/>
                        <?PHP $cliente_selec = (isset($cliente_selec_id)) ? '<input type="hidden" name="cliente_id" id="cliente_id" value ="'.$cliente_selec_id.'"/>' : '';?>
                    <div id="data_cli"><?= $cliente_selec;?></div>
                </div>                                 
            <div class="col-xs-2 col-md-2 col-lg-2">
                <select class="form-control" name="empresa" id="empresa">
                    <option>Empresa</option>
                    <?PHP foreach($empresas as $value){ 
                        $selected = ($value['id'] == $empresa_selec) ? 'SELECTED': '';?>
                        <option <?= $selected;?> value="<?= $value['id']?>"><?= $value['empresa']?></option>
                    <?PHP }?>
                </select>
            </div> 
                                            
            <div class="col-xs-2 col-md-2 col-lg-2">
                <select class="form-control" name="tipo_documento" id="tipo_documento">
                    <option>TDocumento.</option>
                    <?PHP foreach($tipo_documentos as $value){ 
                        $selected = ($value['id'] == $tipo_documento_selec) ? 'SELECTED': '';?>
                        <option <?= $selected;?> value="<?= $value['id']?>"><?= $value['tipo_documento']?></option>
                    <?PHP }?>
                </select>
            </div>
                <div id="desTip">                    
            <div class="col-xs-2 col-md-2 col-lg-2">
                <select class="form-control" name="desTipo" id="desTipo">
                    <?PHP if ($desTipo_selec == 0){?>
                        <option value="0" selected>Constante</option>
                        <option value="1">Variable</option>
                    <?PHP } else { ?>
                        <option value="0">Constante</option>
                        <option value="1" selected>Variable</option>
                    <?PHP }?>
                </select>       
            </div>                    
                </div>
                
                <div class="col-xs-1 col-md-1 col-lg-1">                    
                    <input id="rr" type="submit" class="btn btn-primary" value="Buscar">
                </div>                
            </div></form>
            <br>
            
            <!----------------------------------------------------------------------------------- -->                                    
                <div class="row">                                                                                      
                    <div id="dMes">
           <div class="col-xs-2 col-md-2 col-lg-2">               
               <select class="form-control" name="mes" id="mes">
                    <option value="">Mes</option>
                    <option value="Enero">Enero</option>
                    <option value="Febrero">Febrero</option>
                    <option value="Marzo">Marzo</option>
                    <option value="Abril">Abril</option>
                    <option value="Mayo">Mayo</option>
                    <option value="Junio">Junio</option>
                    <option value="Julio">Julio</option>
                    <option value="Agosto">Agosto</option>
                    <option value="Septiembre">Septiembre</option>
                    <option value="Octubre">Octubre</option>
                    <option value="Noviembre">Noviembre</option>
                    <option value="Diciembre">Diciembre</option>                                
                    </select>                
            </div>  
            <div class="col-xs-5 col-md-5 col-lg-5">               
                <input class="form-control" type="text" name="concepto" id="concepto" placeholder="Concepto">
            </div>
                        
                        
                        </div>
            <div class="col-xs-2 col-md-2 col-lg-2">                
                <select class="form-control" name="serie" id="serie">
                    <option>Serie</option>
                    <?PHP foreach ($ser_nums as $value) { ?>                                            
                    <option value="<?= $value['serie']?>"><?= $value['serie']?></option>
                    <?PHP }?>
                </select>
            </div>
                    <div class="col-xs-2 col-md-2 col-lg-2">                        
                        <a id="form2" href="#" class="btn btn-success">Facturar</a>
                    </div>                                                        
        </div>                                                                                
            <br><br>                                                
        </div> 
        <div class="container-fluid">
            <form id="form22" method="POST" action="<?= base_url()?>index.php/comp_cli_per/facMensualPermanentes" >
            <div class="text-right">
                <a href="<?= base_url()?>index.php/comp_cli_per/nuevo" class="btn btn-success btn-sm">Agregar</a>
            </div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Dni/ruc</th>
                        <th>Razon Social</th>
                        <th>Empresa</th>
                        <th>Moneda</th>
                        <th>Monto</th>
                        <th>Descripcion</th>                        
                        <th>Tipo Contrato</th>
                        <th>Facturar</th>
                        <th>Modificar</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>
                <tbody>
                    <?PHP $i=0;foreach ($clientes as $value) {$i++;?>
                    <tr>
                        <td><?= $i;?></td>
                        <td class="col-sm-1"><?= $value['cliente_ruc'];?></td>
                        <td class="col-sm-3"><?= $value['cliente_razon_social'];?></td>
                        <td><?= $value['empresa_razon_social'];?></td>
                        <td><?= $value['contrato_moneda'];?></td>
                        <td><input type="text" class="form-control" name="monto[]" value="<?= $value['contrato_monto'];?>"></td>
                        <td class="col-sm-4"><input class="form-control descripcion" type="text" name="descripcion[]" id="descripcion" value="<?= $value['descripcion']?>"/>
                        <td><?= $value['cliente_ruc'];?></td>
                        <td><a href="#">Facturar</a></td>
                        <td class="text-center"><a href="<?= base_url()?>index.php/comp_cli_per/modificar/<?= $value['compCliPer_id']?>"><span class="glyphicon glyphicon-edit"></span></a></td>                        
                        <td class="text-center"><a href="#" onclick="eliminar('<?= base_url()?>','comp_cli_per','eliminar','<?= $value['compCliPer_id']?>')"><span class="glyphicon glyphicon-remove"></span></a></td>
                        <input type="hidden" name="cliente_id[]" value="<?= $value['cliente_id']?>"/>
                        <input type="hidden" name="empresa_id[]" value="<?= $value['empresa_id']?>"/>
                        <input type="hidden" name="tipo_documento_id[]" value="<?= $value['tipo_documento_id']?>"/>
                        <input type="hidden" name="moneda_id[]" value="<?= $value['contrato_moneda_id']?>"/>
                    </tr>
                    <?PHP }?>
                </tbody>
                
            </table>
                <input type="hidden" name="series" id="series">
            </form>
        </div>        
        