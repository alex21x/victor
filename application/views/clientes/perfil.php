<style>
    .titulo_27{
        font-size: 27px;
        text-align: center;        
    }    
    
    .titulo_23{
        font-size: 23px;
    }

    .titulo_21{
        font-size: 23px;
    }
    img{
        margin-top: -90px;
        width: 200px;
        height: 230px;
    }
    .rowCliente{
        margin-top: 100px;
    }
    .rowClienteDatos{
        margin-top: 60px;
    }
</style>

<div class="container">    
    <div class="row rowClienteDatos">
        <div class="col-xs-4 col-md-4 col-lg-4"></div>
        <div class="col-xs-8 col-md-8 col-md-8">
            <span class="titulo_27">Cliente:&nbsp;<?PHP echo $cliente['razon_social'];?></span>    
        </div>        
    </div>
    <div class="row rowCliente">  
        <div class="col-xs-4 col-md-4 col-md-4"></div>
        <div class="col-xs-6 col-md-6 col-lg-6">
            <img src="<?= base_url().'images/'.$cliente['foto'];?>">
        </div>
    </div>
    <div class="row rowClienteDatos">
        <div class="col-md-1">
        </div>
        <div class="col-md-9">
            <table class="table table-striped">
                
                <?PHP if($cliente['tipo_cliente_id']== '1'){ ?>
                <tr><td>Dni:</td><?PHP } else {?>
                <tr><td>Ruc:</td>    
                <?PHP } ?>    
                <td><?PHP echo $cliente['ruc'];?></td></tr>                
                <?PHP if($cliente['tipo_cliente_id']== '1'){ ?>
                <tr><td>Apellidos:</td><?PHP } else {?>
                <tr><td>Razon Social:</td>    
                <?PHP } ?>                    
                <td><?PHP echo $cliente['razon_social'];?></td></tr>
                                                                                
                <?PHP if($cliente['tipo_cliente_id']== '1'){ ?>
                <tr><td>Nombres:</td><td><?PHP echo $cliente['nombres'];?></td></tr>                    
                <?PHP
                } else {?>
                <tr><td>Razon Social SUNAT:</td><td><?PHP echo $cliente['razon_social_sunat'];?></td></tr>
                <?PHP                 
                } ?>                
                
                <tr><td>Domicilio 1:</td><td><?PHP echo $cliente['domicilio1'];?></td></tr>
                <tr><td>Domicilio 2:</td><td><?PHP echo $cliente['domicilio2'];?></td></tr>
                <tr><td>Email:</td><td><?PHP echo $cliente['email'];?></td></tr>
                <tr><td>Email2:</td><td><?PHP echo $cliente['email2'];?></td></tr>
                <tr><td>Email3:</td><td><?PHP echo $cliente['email3'];?></td></tr>
                <tr><td>Página Web:</td><td><?PHP echo $cliente['pagina_web'];?></td></tr>
                <tr><td>Teléfono Fijo 1:</td><td><?PHP echo $cliente['telefono_fijo_1'];?></td></tr>
                <tr><td>Teléfono Fijo 2:</td><td><?PHP echo $cliente['telefono_fijo_2'];?></td></tr>
                <tr><td>Teléfono Movil 1:</td><td><?PHP echo $cliente['telefono_movil_1'];?></td></tr>
                <tr><td>Teléfono Movil 2:</td><td><?PHP echo $cliente['telefono_movil_2'];?></td></tr>
                <tr><td>Tipo cliente:</td><td><?PHP echo $cliente['tipo_cliente'];?></td></tr>
                <tr><td>Empresa:</td><td><?PHP if(isset($empresa['empresa'])){ echo $empresa['empresa'];}?></td></tr>
                <tr><td>Estado:</td><td><?PHP echo $cliente['activo'] ?></td></tr>
            </table>
        </div>
        
    </div>
</div><br><br>