
        <p class="bg-info">        
            <?= $this->session->flashdata('mensaje');?>
        </p>
        <div class="container">
            <form method="POST" action="<?= base_url()?>index.php/comprobantes/facMensualPermanentes">
            <h2 class="text-center">Facturacion del Mes - Clientes Permanentes</h2><br>
            
            <div class="form-group">                
            <div class="col-xs-1 col-md-1 col-lg-1">
                <label class="control-label">Mes:</label>
            </div>    
           <div class="col-xs-3 col-md-3 col-lg-3">
               <select class="form-control" name="mes" id="mes">
                <option value="1">Enero</option>
                <option value="2">Febrero</option>
                <option value="3">Marzo</option>
                <option value="4">Abril</option>
                <option value="5">Mayo</option>
                <option value="6">Junio</option>
                <option value="7">Julio</option>
                <option value="8">Agosto</option>
                <option value="9">Septiembre</option>
                <option value="10">Octubre</option>
                <option value="11">Noviembre</option>
                <option value="12">Diciembre</option>                                
            </select>               
            </div>                   
            <div class="col-xs-1 col-md-1 col-lg-1">
                <label class="control-label">Serie:</label>
            </div>                
            <div class="col-xs-2 col-md-2 col-lg-2">
                <select class="form-control" name="serie" id="serie">
                    <?PHP foreach ($ser_nums as $value) { ?>                                            
                    <option value="<?= $value['serie']?>"><?= $value['serie']?></option>
                    <?PHP }?>
                </select>
            </div>                            
                
                <div class="col-xs-3 col-md-3 col-lg-3">
                    <input type="submit" class="btn btn-success" value="Facturar">
                </div>                
            </div>                                                                    
            </form>
            <br><br>           
            
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Razon Social</th>
                        <th>Dni/ruc</th>
                        <th>Tipo Contrato</th>
                        <th>Facturar</th>
                    </tr>
                </thead>
                <tbody>
                    <?PHP $i=0;foreach ($clientes as $value) {$i++;?>
                    <tr>
                        <td><?= $i;?></td>
                        <td><?= $value['razon_social'];?></td>
                        <td><?= $value['ruc'];?></td>
                        <td><?= $value['tipo_contrato'];?></td>
                        <td><a href="#">Facturar</a></td>
                    </tr>
                    <?PHP }?>
                </tbody>
                
            </table>                                    
        </div>      
    
    