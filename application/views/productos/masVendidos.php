<style type="text/css">
    label{
        width: 100%;
    }
</style>
<script type="text/javascript">
    $(document).ready(function(){
        $("#fecha_desde").datepicker();
        $("#fecha_hasta").datepicker(); 
    });
</script>
<div class="container">
    <h3>PRODUCTOS MAS VENDIDOS</h3><br>
    <form id="formProductos">
    <div class="row">
        <div class="col-xs-4 col-md-4 col-lg-4">
            <label>Fecha Desde
                <input class="form-control" name="fecha_desde" id="fecha_desde">
            </label>
        </div>  
        <div class="col-xs-4 col-md-4 col-lg-4">
            <label>Fecha Hasta
                <input class="form-control" name="fecha_hasta" id="fecha_hasta">
            </label>
        </div>  
        <div class="col-xs-2 col-md-2 col-lg-2">
            <label>&nbsp;
                <button id="buscarProductos" type="button" class="btn btn-primary btn-block">BUSCAR</button>
            </label>
        </div>  
        <div class="col-xs-2 col-md-2 col-lg-2">
            <label>&nbsp;<br>
                <a id="exportar_repo" href="#" class="btn btn-success btn-sm colbg">Eportar excel</a>
            </label>
        </div>
    </div>      
    </form>
</div>

<br><br>
<div class="container">       
    <div id="contenido"></div>
</div>


<script type="text/javascript">
    
    $(document).ready(function(){
        function buscarProductos(){
            $.ajax({
                url: '<?= base_url()?>index.php/productos/masVendidos_g',
                dataType: 'HTML',
                method: 'POST',
                data: $("#formProductos").serialize(),
                success: function(response){
                    $("#contenido").html(response);
                }
            });
        }
        buscarProductos();
        
        $(document).on("click","#buscarProductos",function(){
            buscarProductos();
        });

        $('#exportar_repo').click(function() {
        datos = $("#formProductos").serialize();                

        var url ='<?PHP echo base_url() ?>index.php/productos/exportarProductosmasVendidos?'+datos;
        window.open(url, '_blank');

    });

});
</script>