<h2 align="center">Modificar Usuario</h2>
<br>
<div class="container">    
    <form method="post" action="<?php echo base_url()?>index.php/empleados/basic_modificar_g/<?php echo $empleados['id']?>">
        <table class="table table-hover">
            <tr>
                <td>Nombres</td>
                <td><input class="form-control" type="text" name="nombre" value="<?php echo $empleados['nombre']?>" ></td>
            </tr>
            <tr>
                <td>Apellido Paterno</td>
                <td><input class="form-control" type="text" name="apellido_paterno" value="<?php echo $empleados['apellido_paterno']?>" ></td>
            </tr>
            <tr>
                <td>Apellido Materno</td>
                <td><input class="form-control" type="text" name="apellido_materno" value="<?php echo $empleados['apellido_materno']?>" ></td>
            </tr>
            <tr>
                <td>Contraseña</td>
                <td><input class="form-control" type="text" name="dni" value="<?php echo $empleados['dni']?>" ></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><input class="form-control" type="text" name="email" value="<?php echo $empleados['email']?>" ></td>
            </tr>
            <tr>
                <td>Perfil</td>
                <td>
                    <select name="perfil" class="form-control">
                        <option value="">Seleccione perfil</option>
                        <?php foreach($perfiles as $perfil):?>
                            <option value="<?php echo $perfil->id?>" <?php if($perfil->id==$empleados['tipo_empleado_id']):?> selected <?php endif?>  ><?php echo $perfil->tipo_empleado?></option>
                        <?php endforeach?>
                    </select>
                </td>
            </tr>   
            <tr>
                <td>Almacén</td>
                <td>
                    <select name="almacen" class="form-control">
                        <option value="">Seleccione almacén</option>
                        <?php foreach($almacenes as $almacen):?>
                            <option value="<?php echo $almacen->alm_id?>" <?php if($almacen->alm_id==$empleados['almacen_id']):?> selected <?php endif?>  ><?php echo $almacen->alm_nombre?></option>
                        <?php endforeach?>
                    </select>
                </td>
            </tr>           
            <tr>
                <td align="center" colspan="2">
                    <input class="btn btn-info" type="submit" value="Modificar"/>
                </td>
            </tr>
        </table>
    </form>
</div>