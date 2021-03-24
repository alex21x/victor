<script src="<?PHP echo base_url(); ?>assets/js/funciones.js"></script>
<div class="container">
    <h4 align="center">Cargar facturas permanente</h4>
    <br>
    <form action="<?= base_url()?>index.php/comp_cli_per/seleccionar" method="post" enctype="multipart/form-data">
        <table class="table table-striped">
            <tr>
                <td>Año
                    <select class="form-control" name="anio">
                        <?php
                        $anio = date("Y");
                        for($i = ($anio - 5); $i <= ($anio + 5); $i++){
                            $selected = (isset($envio_anio)) ? ($envio_anio == $i) ? 'selected' : '' : '';
                            ?>
                        <option <?php echo $selected;?> value="<?php echo $i;?>"><?php echo $i;?></option>
                        <?php
                        }
                        ?>
                    </select>
                </td>
                <td>Mes
                    <select class="form-control" name="mes">
                        <?php
                        for($i = 1; $i <= 12; $i++){
                            $selected = (isset($envio_mes)) ? ($envio_mes == $i) ? 'selected' : '' : '';
                            
                            $mostrar = $i;
                            if($i<10) $mostrar = "0".$i;
                            ?>
                        <option <?php echo $selected;?> value="<?php echo $mostrar;?>"><?php echo $mostrar;?></option>
                        <?php
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <input  dir="ltr" class="btn btn-primary" type="submit" value="Seleccionar" name="submit">
                </td>
                <td><a class="btn btn-success" href="<?php echo base_url()?>/files/xlsx/descargar_formato/formato_clientes_permanentes_2.xlsx">Descargar Formato</a></td>
            </tr>
        </table>
    </form>    
    Seleccionar Excel a cargar:
    <form action="<?= base_url()?>index.php/comp_cli_per/carga_g" method="post" enctype="multipart/form-data">
        <table class="table table-striped">
            <tr>
                <td><input required="" class="btn btn-default" type="file" name="fileToUpload" id="fileToUpload"></td>
                <td><input  dir="ltr" class="btn btn-primary" type="submit" value="Cargar Formato" name="submit"></td>
            </tr>
        </table>
    </form>
    <br>
    <p class="bg-info">
    <?PHP echo $this->session->flashdata('mensaje'); ?>
    </p>
    <table class="table tab-content">
        <tr>
            <td>N.</td>
            <td>Periodo</td>
            <td>Empresa</td>
            <td>Tipo</td>
            <td>RUC</td>
            <td>Razon</td>
            <td>Mon.</td>
            <td>Monto</td>
            <td>Descripción</td>
            <td>Modificar</td>
            <td>Facturar</td>            
            <td>Eliminar</td>
        </tr>
        <?php
        $i = 1;
        foreach ($datos as $value) {
            $color = (($value['enviado_sunat'] == "1") && ($value['estado_sunat'] == "0") ? "bgcolor='#D0F5A9'" : "");
            ?>
       <tr <?php echo $color; ?>>
            <td><?php echo $i; $i++;?></td>
            <td><?php echo $value['anio'].'-'.$value['mes']?></td>
            <td><?php echo $value['empresa']?></td>
            <td><?php echo $value['tipo_documento']?></td>
            <td><?php echo $value['ruc_cliente']?></td>
            <td><?php echo $value['razon_social']?></td>
            <td><?php echo $value['moneda']?></td>
            <td dir="rtl"><?php echo number_format($value['monto'],2);?></td>
            <td><?php echo $value['descripcion']?></td>
            <?php
            if($value['comprobante_id'] == NULL){?>
            <td><a href="<?php echo base_url()."index.php/comp_cli_per/modificarComprobante/" . $value['comp_cli_per_id']. "/" . $value['anio'] . "/" . $value['mes']; ?>" class="btn btn-default"><div align="center">Modificar</div></a></td>
            <td><a href="<?php echo base_url()."index.php/comp_cli_per/insertarComprobante/" . $value['empresa_id'] . "/" . $value['tipo_documento_id'] . "/" . $value['comp_cli_per_id'] . "/" . $value['anio_de_permanente'] . "/" . $value['mes_de_permanente']; ?>"><div align="center">Facturar<br>Sunat</div></a></td>
            <?php
            }else{?>
            <td>---</td>
            <td>Facturado<br><?php echo $value['serie_comprobante']." ".$value['numero_comprobante']?></td>
            <?php
            }            
            if($value['comprobante_id'] == NULL){?>                        
            <td><li><a class="btn btn-danger" href="#" onclick="eliminar('<?PHP echo base_url()?>','comp_cli_per','eliminar_comp_cli_per','<?php echo $value['comp_cli_per_id']?>','<?php echo $value['anio']?>','<?php echo $value['mes']?>')">Eliminar</a></li></td>
            <?php
            }else{?>
            <td>---</td>
            <?php
            }
            ?>
        </tr>
        <?php
        }
        ?>
    </table>
</div>