<div align="center"><h2>Imagen del PDF</h2></div>
<br>
<table class="table table-hover">
    <tr>
        <td>Razon social</td>
        <td><?php echo $empresa['nombre_comercial']?></td>
    </tr>
    <tr>
        <td>Nombre Corto</td>
        <td><?php echo $empresa['empresa']?></td>
    </tr>     
</table>
<br>
<div class="container">
        <div class="col-md-6 col-md-offset-3">
            <form class="form-horizontal" enctype="multipart/form-data" role="form" action="<?PHP echo base_url() ?>index.php/empresas/imagen_pdf" method="POST">
                <table class="table table-striped">                
                    <tr>
                        <td><label for="foto" class="col-sm-5 control-label">Fotografía:</label></td>
                        <td><input type="file" name="foto" id="foto">
                            <input type="hidden" name="empresa_id" id="empleado_id" value="<?PHP echo $empresa['id']?>"/>                            
                        </td>
                    </tr>
                    <tr>                        
                        <td colspan="2" align="center"><input type="submit" value="Guardar"/></td>
                    </tr>                    
                </table>
            </form>
        </div>

</div>

<div class="col-xs-3">
    <div align="right" style="border-style: solid; border-color: #000; border-width: 1px"><img src="<?PHP echo base_url() . "images/empresa/" . $empresa['imagen_pdf']; ?>" height="150" width="150"></div>    
</div>
<div><?php echo $empresa['imagen_pdf']?></div>