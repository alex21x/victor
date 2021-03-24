
<script type="text/javascript">
    $(document).ready(function() {
        $("#cliente").autocomplete( {
            source: '<?PHP echo base_url(); ?>index.php/comp_cli_per/buscador_cliente',
            minLength: 2,
            select: function(event, ui) {
                var data_cli ='<input type="hidden" value="' + ui.item.id + '" name = "cliente_id" id = "cliente_id" >';
                $('#data_cli').html(data_cli);
            }
        });
    });
</script>



<div class="container"> 
    <a href="<?PHP echo base_url()?>index.php/comp_cli_per/index" class="btn btn-success btn-xs" role="button">&nbsp;&nbsp;Atras&nbsp;&nbsp;</a>
    <form method="POST" action="<?= base_url()?>index.php/comp_cli_per/guardar">
        <h1>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Agregar Clientes</h1><br>
    
    <div class="row">
        <div class="col-xs-2 col-md-2 col-lg-2 text-right">
            <label>Cliente:</label>            
        </div>        
        <div class="col-xs-6 col-md-6 col-lg-6">            
            <input type="text"  class="form-control input-sm" id="cliente" name="cliente" placeholder="Cliente" required="">            
                <div id="data_cli"></div>
        </div>        
    </div>
    
    <div class="row" style="padding-top: 20px;">
        <div class="col-xs-2 col-md-2 col-lg-2 text-right">
            <label>Tipo Doc:</label>            
        </div>        
        <div class="col-xs-6 col-md-6 col-lg-6">
            <select class="form-control" name="tipo_documento" id="tipo_documento">
                    <option value="1">Factura</option>
                    <option value="3">Boleta</option>
                </select>
        </div>        
    </div>
    
    
    <div class="row" style="padding-top: 20px;">
        <div class="col-xs-2 col-md-2 col-lg-2 text-right">
            <label>Descricpcion:</label>            
        </div>        
        <div class="col-xs-6 col-md-6 col-lg-6">
            <textarea type="text" class="form-control" id="descripcion" name="descripcion" ></textarea>
        </div>        
    </div>
    
    
    <div class="row" style="padding-top: 20px;">
        <div class="col-xs-2 col-md-2 col-lg-2 text-right">
            <label>Tipo:</label>            
        </div>        
        <div class="col-xs-6 col-md-6 col-lg-6">
            <select class="form-control" name="desTipo" id="desTipo">
                    <option value="0">Constante</option>
                    <option value="1">Variable</option>
                </select>
        </div>        
    </div>
        
    <div class="row" style="padding-top: 20px;">
        <div class="col-xs-2 col-md-2 col-lg-2 text-right">
            <label>Monto:</label>
        </div>        
        <div class="col-xs-6 col-md-6 col-lg-6">
            <input type="text" class="form-control" id="monto" name="monto" >
        </div>        
    </div>    
        
    <div class="row" style="padding-top: 20px;">
        <input type="submit" class="btn btn-primary">
    </div>        
    </form>
</div>