


<div class="container">  
    <a href="<?PHP echo base_url()?>index.php/comp_cli_per/index" class="btn btn-success btn-xs" role="button">&nbsp;&nbsp;Atras&nbsp;&nbsp;</a>
    <h1>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Modificar</h1>
    
    <form method="POST" action="<?= base_url()?>index.php/comp_cli_per/modificar_g" >
    <div class="row" style="padding-top: 20px;">
        <div class="col-xs-2 col-md-2 col-lg-2 text-right">
            <label class="control-label">Cliente:</label>            
        </div>                        
        <div class="col-xs-6 col-md-6 col-lg-6">
            <label class="control-label"><?= $comp_cli_per['cliente_razon_social']?></label>
        </div>
    </div>
    
    
    <div class="row" style="padding-top: 20px;">
        <div class="col-xs-2 col-md-2 col-lg-2 text-right">
            <label class="control-label">Ruc:</label>
        </div>                        
        <div class="col-xs-6 col-md-6 col-lg-6">
            <label class="control-label"><?= $comp_cli_per['cliente_ruc']?></label>
        </div>        
    </div>
    
    <div class="row" style="padding-top: 20px;">
        <div class="col-xs-2 col-md-2 col-lg-2 text-right">
            <label class="control-label">Empresa:</label>
        </div>                        
        <div class="col-xs-6 col-md-6 col-lg-6">
            <label class="control-label"><?= $comp_cli_per['empresa_razon_social']?></label>
        </div>        
    </div>
    
    <div class="row" style="padding-top: 20px;">
    <div class="col-xs-2 col-md-2 col-lg-2 text-right">
            <label class="control-label">Descripcion:</label>            
        </div>                        
        <div class="col-xs-6 col-md-6 col-lg-6">
            <input class="form-control" name="descripcion" id="descripcion" value="<?= $comp_cli_per['descripcion']?>" />
        </div>    
    </div>
    
    
    <div class="row" style="padding-top: 20px;">
    <div class="col-xs-2 col-md-2 col-lg-2 text-right">
            <label class="control-label">Tipo:</label>
        </div>                        
        <div class="col-xs-6 col-md-6 col-lg-6">
            <select class="form-control" name="desTipo" id="desTipo">
                <option value="0">Constante</option>
                <option value="1">Variable</option>
            </select>
        </div>    
    </div>
    
    <div class="row" style="padding-top: 20px;">       
        <input type="hidden" name="compCliPer_id" id="compCliPer_id" value="<?= $comp_cli_per['compCliPer_id']?>"/>
        <input class="btn btn-primary" type="submit" value="Modificar">        
    </div>    
        </form>
</div>





