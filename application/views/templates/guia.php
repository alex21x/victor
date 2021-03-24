<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <style>

    .bold,b,strong{font-weight:700}
    body{background-repeat:no-repeat;background-position:center center;text-align:center;margin:0;font-family: Verdana, monospace}  
    .tabla_borde{border:1px solid #666;border-radius:4px}  
    tr.border_bottom td{border-bottom:1px solid #000}  
    tr.border_top td{border-top:1px solid #666}
    td.border_right{border-right:1px solid #666}
    .table-valores-totales tbody>tr>td{border:0}  
    .table-valores-totales>tbody>tr>td:first-child{text-align:right}  
    .table-valores-totales>tbody>tr>td:last-child{border-bottom:1px solid #666;text-align:right;width:30%}  
    hr,img{border:0}  
    table td{font-size:10px}  
    html{font-family:sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;font-size:10px;-webkit-tap-highlight-color:transparent}
    a{background-color:transparent}  
    a:active,a:hover{outline:0}  
    img{vertical-align:middle}  
    hr{height:0;-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box;margin-top:20px;margin-bottom:20px;border-top:1px solid #eee}  
    table{border-spacing:0;border-collapse:collapse}
    @media print{blockquote,img,tr{page-break-inside:avoid}*,:after,:before{color:#000!important;text-shadow:none!important;background:0 0!important;-webkit-box-shadow:none!important;box-shadow:none!important}a,a:visited{text-decoration:underline}a[href]:after{content:" (" attr(href) ")"}blockquote{border:1px solid #999}img{max-width:100%!important}p{orphans:3;widows:3}.table{border-collapse:collapse!important}.table td{background-color:#fff!important}}  
    a,a:focus,a:hover{text-decoration:none}  
    *,:after,:before{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}  
    a{color:#428bca;cursor:pointer}  
    a:focus,a:hover{color:#2a6496}  
    a:focus{outline:dotted thin;outline:-webkit-focus-ring-color auto 5px;outline-offset:-2px}  
    h6{font-family:inherit;line-height:1.1;color:inherit;margin-top:10px;margin-bottom:10px}  
    p{margin:0 0 10px}  
    blockquote{padding:5px 10px;margin:0 0 20px;border-left:5px solid #eee}  
    table{background-color:transparent}  .table{width:100%;max-width:100%;margin-bottom:20px}  h6{font-weight:100;font-size:10px}  
    body{line-height:1.42857143;font-family:"open sans","Helvetica Neue",Helvetica,Arial,sans-serif;background-color:#2f4050;font-size:13px;color:#676a6c;overflow-x:hidden}  
    .table>tbody>tr>td{vertical-align:top;border-top:1px solid #e7eaec;line-height:1.42857;padding:8px}  
    .white-bg{background-color:#fff}  
   
    .table-valores-totales tbody>tr>td{border-top:0 none!important}


    </style>
</head>
<body class="white-bg">

<table width="100%">
    <tbody><tr>
        <td>
            <table width="100%" height="220px" border="0" aling="center" cellpadding="0" cellspacing="0">
                <tbody>
                    <tr>
                    <td width="53%"  align="left">

                        <!--<span><img src="<?PHP echo base_url();?>images/pelota-semilla-jd-thuds.jpg" height="80" style="text-align:center;" border="0"></span>-->
                        <span><img src="<?PHP echo FCPATH;?>images/<?php echo $empresa->foto;?>" height="160" width="380" style="text-align:center;" border="0"></span><br>
                        <div style="height: 2px"></div>
                        <span><strong><?php echo $empresa->empresa?></strong></span><br>
                        <span><strong>Dirección: </strong><?php echo $empresa->domicilio_fiscal?></span><br>
                        <span><strong>Telf: </strong><?php echo $empresa->telefono_fijo?> / <?php echo $empresa->telefono_movil?></span>
                    </td>
                    <td width="2%" height="40" align="center"></td>
                    <td width="45%"valign="bottom" style="padding-left:0">
                        <div  style="border:1px solid #aaa;border-radius:10px;height: 180px;">
                            <table width="100%" border="0" cellpadding="6" cellspacing="0">
                                <tbody>
                                <tr>
                                    <td align="center">
                                        <span style="font-size:25px" text-align="center">R.U.C.: <?php echo $empresa->ruc?></span>
                                    </td>
                                </tr>                                    
                                <tr>
                                    <td align="center">
                                        <span style="font-family:Tahoma, Geneva, sans-serif; font-size:20px" text-align="center"><?php echo strtoupper("GUÍA DE REMISIÓN") ?></span>
                                        <br>
                                       <span style="font-family:Tahoma, Geneva, sans-serif; font-size:20px" text-align="center">REMITENTE</span>
                                    </td>
                                </tr>

                                <tr>
                                    <td align="center">
                                         <span style="font-family:Tahoma, Geneva, sans-serif; font-size:20px" text-align="center">No.: <?php echo $guia->guia_serie?>-<?php echo $guia->guia_numero?></span>
                                    </td>
                                </tr>
                                <!--<tr>
                                    <td align="center">
                                        Nro. R.I. Emisor: <span></span>
                                    </td>
                                </tr>-->
                                </tbody></table>
                        </div>
                    </td>
                </tr>

                </tbody></table>
            <br>
            <div class="tabla_borde">
                <table width="100%" border="0" cellpadding="5" cellspacing="0">
                    <tbody>
                    <tr>
                        <td colspan="2"><strong>DESTINATARIO</strong></td>
                    </tr>
                    <tr class="border_top">
                        <td width="60%" align="left"><strong>Razón Social:</strong>  <?php echo $guia->destinatario_razon_social?></td>
                        <td width="40%" align="left"><strong>RUC:</strong>  <?php echo $guia->destinatario_ruc?></td>
                    </tr>
                    <tr>
                        <td width="60%" align="left"><strong>Dirección:</strong>  <?php echo $guia->llegada_direccion?></td>
                        <td width="40%" align="left"><strong>Factura:</strong>  <?php echo $guia->numero_factura?></td>
                    </tr>
                    </tbody></table>
            </div><br>
            <div class="tabla_borde">
                <table width="100%" border="0" cellpadding="5" cellspacing="0">
                    <tbody>
                    <tr>
                        <td colspan="2"><strong>ENVIO</strong></td>
                    </tr>
                    <tr class="border_top">
                        <td width="60%" align="left">
                            <strong>Fecha Emisión:</strong>  <?php echo $guia->fecha_inicio_traslado?>
                        </td>
                        <td width="40%" align="left"><strong>Fecha Inicio de Traslado:</strong>  <?php echo $guia->fecha_inicio_traslado?> </td>
                    </tr>
                    <tr>
                        <td width="60%" align="left"><strong>Motivo Traslado:</strong>  <?php echo $guia->descripcion?> </td>
                        <td width="40%" align="left"><strong>Modalidad de Transporte:</strong>   
                                  <?php echo $guia->modalidad ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="60%" align="left"><strong>Peso Bruto Total (KG):</strong> <?php echo $guia->peso_total?> </td>
                        <td width="40%"><strong>Número de Bultos:</strong> <?php echo $guia->numero_bultos?> </td>
                    </tr>
                    <tr>
                        <td width="60%" align="left"><strong>P. Partida:</strong>   <?php echo $guia->partida_direccion?></td>
                        <td width="40%" align="left"><strong>P. Llegada: </strong>  <?php echo $guia->llegada_direccion?></td>
                    </tr>
                    </tbody></table>
            </div><br>

            <div class="tabla_borde">
                <table width="100%" border="0" cellpadding="5" cellspacing="0">
                    <tbody>
                    <tr>
                        <td colspan="2"><strong>TRANSPORTE</strong></td>
                    </tr>
                    <tr class="border_top">
                        <td width="60%" align="left"><strong>Razón Social:</strong>  <?php echo $guia->transporte_razon_social?></td>
                        <td width="40%" align="left"><strong>RUC:</strong>  <?php echo $guia->transporte_ruc?></td>
                    </tr>
                    <tr>
                        <td width="60%" align="left"><strong>Placa:</strong>  <?php echo $guia->vehiculo_placa?></td>
                        <td width="40%" align="left"><strong>Licencia:</strong>  <?php echo $guia->vehiculo_licencia?></td>
                        <!--<td width="40%" align="left"><strong>Conductor:</strong>  </td> -->
                    </tr>
                    <tr>
                        <td width="60%" align="left"><strong>Marca:</strong> <?php echo $guia->vehiculo_marca?></td>
                    </tr>
                    </tbody></table>
            </div><br>
            <div class="tabla_borde">
                <table width="100%" border="0" cellpadding="5" cellspacing="0">
                    <tbody>
                    <tr>
                        <td align="center" class="bold">Item</td>
                        <!--<td align="center" class="bold">Código</td>-->
                        <td align="center" class="bold" width="300px">Descripción</td>
                        <td align="center" class="bold">Unidad</td>
                        <td align="center" class="bold">Cantidad</td>
                    </tr>
                        <?php foreach($guia->detalles as $index => $item):?>
                        <tr class="border_top">
                            <td align="center"><?php echo (++$index)?></td>
                            <!--<td align="center"><?php echo $item->codigo?></td>-->
                            <td align="center"><?php echo $item->descripcion?></td>
                            <td align="center"><?php echo $item->medida_nombre?></td>
                            <td align="center"><?php echo $item->cantidad?></td>
                        </tr>
                        <?php endforeach?>
                    </tbody>
                </table></div>

            <div><br><br><br><hr>
                <div style="text-align: center;">
                    <?= $guia->firma_sunat;?>
                </div>    
            </div>
        </td>
    </tr>
    </tbody></table>
</body></html>