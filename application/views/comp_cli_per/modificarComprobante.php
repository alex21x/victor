<div class="container">
    <div align="center">
        <h3>Modificar Comprobante</h3>
    </div>    
    <form method="post" action="<?php echo base_url()."index.php/comp_cli_per/modificarComprobante_g"?>">
        <div class="form-group">
          <label for="descripcion">Descripción</label>
          <textarea class="form-control" rows="3" name="descripcion" required=""><?php echo $datos[0]['descripcion']?></textarea>
        </div>
        <div class="form-group">
          <label for="exampleInputPassword1">Moneda</label>
          <select name="moneda" class="form-control">
            <?php
            foreach ($monedas as $value){
                $selected = ($value['id'] == $datos[0]['moneda_id']) ? 'selected' : '';
            ?>
            <option <?php echo $selected;?> value="<?php echo $value['id']?>"><?php echo $value['moneda']?></option>
            <?php
            }              
            ?>              
          </select>
        </div>
        <div class="form-group">
          <label for="monto">Monto</label>
          <input class="form-control" type="text" id="monto" name="monto" value="<?php echo number_format(($datos[0]['monto']),2);?>" required="">
        </div>  
        <div align="center">
            <button type="submit" class="btn btn-default">Modificar</button>
        </div>        
        <input type="hidden" name="comp_cli_per_id" value="<?php echo $comp_cli_per_id?>"/>
        <input type="hidden" name="anio" value="<?php echo $anio?>"/>
        <input type="hidden" name="mes" value="<?php echo $mes?>"/>
      </form>
</div>