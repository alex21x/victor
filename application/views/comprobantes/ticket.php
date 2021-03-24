<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <style>

    .bold,b,strong{font-weight:700}
    body{background-repeat:no-repeat;background-position:center center;text-align:center;margin:0;font-family: Verdana, monospace}  
    .tabla_borde{border:1px solid #666;border-radius:10px}  
    tr.border_bottom td{border-bottom:1px solid #000}  
    tr.border_top td{border-top:1px solid #666}
    td.border_right{border-right:1px solid #666}
    .table-valores-totales tbody>tr>td{border:0}  
    .table-valores-totales>tbody>tr>td:first-child{text-align:right}  
    .table-valores-totales>tbody>tr>td:last-child{border-bottom:1px solid #666;text-align:right;width:30%}  
    hr,img{border:0}  
    table td{font-size:12px}  
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
    td{padding:6}  
    .table-valores-totales tbody>tr>td{border-top:0 none!important}


    </style>
</head>
<body class="white-bg" style="background-color: #fff">
<?php 

 switch ($comprobante->tipo_documento_id) {
    case 1:
        $tipo_documento = "FACTURA DE VENTA ELECTRONICA";
        break;
    case 3:
        $tipo_documento = "BOLETA DE VENTA ELECTRONICA";
        break;
    case 7:
        $tipo_documento = "NOTA DE CREDITO";
        $data['tipo_nota'] = $this->tipo_ncreditos_model->select($data['comprobante']['tipo_nota_id']);
        $data['comp_adjunto'] = $this->comprobantes_model->select($data['comprobante']['com_adjunto_id']);
        break;
    case 8:
        $tipo_documento = "NOTA DE DEBITO";
        $data['tipo_nota'] = $this->tipo_ndebitos_model->select($data['comprobante']['tipo_nota_id']);
        $data['comp_adjunto'] = $this->comprobantes_model->select($data['comprobante']['com_adjunto_id']);
        break;
    }
  $tipopago ="";
    $data['tipo_documento'] = $tipo_documento;   

 ?>
 <div style="padding:50px 220px;">
     <table width="100%" >
         <tr>
           <td colspan="2">
              <img src="<?php echo base_url();?>images/logo.jpg" height="160" width="100%" style="text-align:center;" border="0">   
           </td>  
         </tr>
         <tr><td colspan="2" style="padding-bottom: 5px;padding-top: 10px;font-size: 18px;"><?php echo $empresa->empresa?></td></tr>
         <tr><td colspan="2" style="padding-bottom: 5px;">RUC : <?php echo $empresa->ruc?></td></tr>
         <tr><td colspan="2" style="border-bottom: 1px dashed #000;padding-bottom: 8px;"><span><?php echo $empresa->domicilio_fiscal?></span></td></tr>
         <tr><td colspan="2" style="font-size: 17px;padding-bottom: 5px;padding-top: 10px;"><span><?php echo $data['tipo_documento']; ?></span></td></tr>
         <tr><td colspan="2" style="font-size: 15px;padding-bottom:5px;"><span><?php echo $comprobante->serie?>-<?php echo $comprobante->numero?></span></td></tr>
         
         <tr>
            <td style="text-align: left;padding-bottom:5px;">
                <span>Fecha de Emisión : <?php echo $comprobante->fecha_de_emision?></span>
            </td>
            <td style="text-align: left;padding-bottom:5px;">
                <?php $hora = date("h:j:s", strtotime($$comprobante->fecha_de_emision));?>
                <span>Hora de Emisión : <?php echo $hora; ?></span>
            </td>
        </tr>
          <tr><td colspan="2" style="border-bottom: 1px dashed #000;text-align: left;padding-bottom:5px;"><span>Responsable : <?php echo $this->session->userdata('usuario'). " ". $this->session->userdata('apellido_paterno'); ?></span></td></tr>
          <tr><td colspan="2" style="text-align: left;padding-bottom: 5px;padding-top: 10px;"><span>Cliente : <?php echo $comprobante->razon_social?> <?php echo $comprobante->nombres?></span></td></tr>
          <tr><td colspan="2" style="text-align: left;border-bottom: 1px dashed #000;padding-bottom: 5px;"><span>Tipo de Documento: <?php if($comprobante->tipo_cliente_id==1):?> D.N.I <?php else:?> R.U.C <?php endif?>  <?php echo "  ". $comprobante->ruc?></span></td></tr>
     </table>

     <table width="100%" style="border-bottom: 1px dashed #000;">
         <tr style="border-bottom: 1px dashed #000;">
             <td style="padding-bottom: 5px;padding-top: 10px;">CÓDIGO</td>
             <td style="padding-bottom: 5px;padding-top: 10px;">DESCRIPCIÓN</td>
             <td style="padding-bottom: 5px;padding-top: 10px;">CANT.</td>
             <td style="padding-bottom: 5px;padding-top: 10px;">P/U.</td>
             <td style="padding-bottom: 5px;padding-top: 10px;">IMPORTE</td>
         </tr>        
         <?php foreach($detalles as $item):?>
            <tr style="padding-bottom: 5px;">
                <td align="center">
                    <?php echo $item->cantidad?>
                </td>
               <!-- codigo
                <td align="center">
                    
                </td> -->
                <td align="center">
                    <span><?php echo $item->descripcion?></span><br>
                </td>
                <td align="center">
                    <span><?php echo $item->cantidad?></span><br>
                </td>
                <td align="center">

                    <?php echo $comprobante->simbolo?> <?php echo $item->importe?>
                </td>
                                          
                <td align="center">
                     <?php $total = ($comprobante->incluye_igv==1) ? $item->total : $item->subtotal ; ?>
                    <?php echo $comprobante->simbolo?> <?php echo $total?>
                </td>
            </tr>
        <?php 
         $tipopago = $item->tipo_pago;
         endforeach?>
     </table>

     <table width="100%;" style="border-bottom:1px dashed #000;">
         <tr>
             <td style="text-align: left;padding-bottom: 5px;padding-top: 10px;">Total Descuentos:</td>
             <td style="text-align: right;padding-bottom: 5px;">
                <?php echo $comprobante->simbolo?> 
                <?php 
                if ($comprobante->total_descuentos>0) {
                    echo "-".$comprobante->total_descuentos;   
                } else {                    
                    echo $comprobante->total_descuentos;
                }
                ?>                    
                </td>
         </tr>
         <tr >
             <td style="text-align: left;padding-bottom: 5px;">SubTotal:</td>
             <td style="text-align: right;padding-bottom: 5px;"><?php echo $comprobante->simbolo?> <?php echo number_format($comprobante->total_gravada,2)?></td>
         </tr>

     </table>

     <table width="100%;">
         <tr>
             <td style="text-align: left;padding-bottom: 5px;">Op. Gravadas:</td>
             <td style="text-align: right;padding-bottom: 5px;"><?php echo $comprobante->simbolo?> <?php echo $comprobante->total_gravada?></td>
         </tr>
         <tr>
             <td style="text-align: left;padding-bottom: 5px;">Op. Inafectas:</td>
             <td style="text-align: right;padding-bottom: 5px;">S/ 0.00</td>
         </tr>
         <tr>
             <td style="text-align: left;padding-bottom: 5px;">Op. Gratuitas:</td>
             <td style="text-align: right;padding-bottom: 5px;">S/ 0.00</td>
         </tr>

         <tr>
             <td style="text-align: left;padding-bottom: 5px;">IGV (18%):</td>
             <td style="text-align: right;padding-bottom: 5px;"><?php echo $comprobante->simbolo?> <?php echo $comprobante->total_igv?></td>
         </tr>
         <tr>
             <td style="text-align: left;padding-bottom: 5px;">IMPORTE TOTAL:</td>
             <td style="text-align: right;padding-bottom: 5px;"><?php echo $comprobante->simbolo?> <?php echo $comprobante->total_a_pagar?></td>
         </tr>
         <tr>
             <td colspan="2" style="text-align: left;">SON: <?php echo $comprobante->total_letras?></td>
         </tr>

     </table>
    
     <table width="100%">
         <tr style="display: flex;">
             <td width="80%" style="text-align: left;float: left;">Formas de Pago: <?php echo "  ". $Tipopago->tipo_pago;?></td>
             <td width="20%" style="text-align: right;float: right;">S/ <?php echo $comprobante->total_a_pagar?></td>
         </tr>        
         <tr>
            <td width="100%" align="center">
            <br>                             
                <img src="<?PHP echo $rutaqr?>" style="width:3cm;height: 3cm;">
                <br>                                
                <?php echo $certificado?>
            </td>
        </tr>
     </table>
      <br>
     <table width="100%;">
         <tr>
             <td>Representación imepresa de la Boleta de Venta Electrónica </br>obligado a ser emisor electrónico </br>mediante la Resolución de Superintendencia N° 155-2017/SUNAT-ANEXOIV</td>
             
         </tr>
         <tr><td style="padding:20px;font-size: 18px;">Gracias por su Visita!</td>
        
     </table>

 </div>




<!-------------------------------------------------------------->    

</body></html>