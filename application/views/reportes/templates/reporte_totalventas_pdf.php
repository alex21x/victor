<style type="text/css">
	table{
		font-size: 8px;
		text-align: left;
		border: 0px solid #dddddd;
		width: 100%;
	}

	table tr th,td{
		border: 0px solid #dddddd;
 		text-align: left;
 		line-height: 8px;
		letter-spacing: 1px;
		padding: 2px;

	}
	table tr td.rowProducto_xs{
		width: 50px;
	}
	table tr td.rowProducto{
		width: 100px;
		
	}
	table tr td.rowProducto_des{
		width: 60px;
	}

	table tr td.rowCliente{
		font-size: 8px;
	}
	table tr.rowCabecera{
		font-size: 8px;
		background-color: #dddddd;
	}

	
.cabecera{
	text-align: center;
}
.detalle{
	text-align: right;

}
p{
	font-size: 8px;
	line-height: 2px;
	letter-spacing: 2px;
}

.desc{
	width:200px;
	background-color: #dddddd;
}

</style>

<table>
	<tr>
		<th></th>
		<th  width="300" style="text-align: center; background-color:#dddddd; font-size: 12px;padding: 6px">Reporte Total Ventas</th>
		<th></th>
	</tr>
</table><br>

<table>
  <tr>
    <th width="80" rowspan="5" style="text-align: center;"><img src="<?PHP echo FCPATH;?>images/<?php echo $empresa['foto'];?>" width="70" ></th>
    <th width="80" >RUC</th>
    <th>:<?php echo $empresa['ruc'];?></th>
  </tr>
  <tr>
  	<td>EMPRESA</td>
  	<td>:<?php echo $empresa['empresa'];?></td>
  </tr>
<tr>
  	<td>DIRECCION</td>
  	<td>:<?php echo $empresa['domicilio_fiscal'];?></td>
  </tr>
  <tr>
  	<td>EMPLEADO</td>
  	<td>:<?php echo $caja->empleado;?></td>
  </tr>
  <tr>
  	<td>LOCAL</td>
  	<td>:<?php echo $this->session->userdata('almacen_nom');?></td>
  </tr>

</table>

<table>
	<tr>
		<td><strong>FECHA DESDE:</strong></td>
		<td><?php echo $fecha_desde?></td>
		<td><strong>FECHA HASTA:</strong></td>
		<td><?php echo $fecha_hasta?></td>
		<td><strong>TRANSPORTISTA:</strong></td>
		<td><?php echo $transportista?></td>
		<td><strong>VENDEDOR:</strong></td>
		<td><?php echo $vendedor?></td>
	</tr>
</table>
 <br>
<table border=1 >
	<tr class="rowCabecera">
		<th class="rowCodigo">FECHA DE EMISIÓN</th>							
		<th class="rowProducto">NUMSER</th>		
		<th width="200" class="rowProducto_">CLIENTE</th>
		<th class="rowProducto_des">VENDEDOR</th>
		<th class="rowProducto_des">TIPO_PAGO</th>
		<th class="rowProducto_des">SUBTOTAL</th>
		<th class="rowProducto_des">TOTAL_IGV</th>	
		<th class="rowProducto_des">TOTAL VENTA</th>
		<th class="rowProducto_des">TOTAL COSTO</th>	
		<th class="rowProducto_des">UTILIDAD</th>										
	</tr>


	<?PHP		
        $sumTotal_subTotal = 0;
		$sumTotal_igv = 0;
		$sumTotal_total_a_pagar = 0;
		$sumTotal_total_costo = 0;
		$sumTotal_utilidad = 0;		

		
     foreach($reporteTotal  as $key => $value) { 	

        	$subtotal = 0;
			$total_igv = 0;
			$total_a_pagar = 0;			
			$total_costo = 0;
			$utilidad = 0;

			$rowKey = implode(array_keys($value));	

	foreach ($value[$rowKey] as $value_1) {?>			
			<tr>
				<td><?= $value_1['fecha_de_emision']?></td>
                <td><?= $value_1['numser'] ?></td>
                <td><?= $value_1['cliente_razon_social'] ?></td>
                <td><?= $value_1['vendedor'] ?></td>
                <td><?= $value_1['tipo_pago'] ?></td>
                <td><?= $value_1['subtotal'] ?></td>
                <td><?= $value_1['total_igv'] ?></td>
                <td><?= $value_1['total_a_pagar'] ?></td>
                <td><?= $value_1['total_costo'] ?></td>
                <td><?= $value_1['utilidad'] ?></td>
             </tr>

        <?php   			
		        $subtotal += $value_1['subtotal'];
				$total_igv += $value_1['total_igv'];
				$total_a_pagar += $value_1['total_a_pagar'];
				$total_costo += $value_1['total_costo'];
				$utilidad += $value_1['utilidad'];
		
		} ?>
		 			<tr class="rowCabecera"> 
		 				<td>&nbsp;</td>
		 				<td>&nbsp;</td>
		 				<td>&nbsp;</td>
		 				<td>&nbsp;</td>
		 				<td>TOTAL +"<?=strtoupper($key)?>"+ SERIE + "<?=strtoupper($key)?>"</td>					 <td><?=$subtotal ?></td>
		 				<td><?=$total_igv ?></td>
		 				<td><?=$total_a_pagar?></td>
		 				<td><?=$total_costo?></td>
		 				<td><?=$utilidad?></td>

		 			</tr>
		  <?php 
          			$sumTotal_subTotal += $subtotal;		 	
					$sumTotal_igv += $total_igv;
					$sumTotal_total_a_pagar += $total_a_pagar;
					$sumTotal_total_costo += $total_costo;
					$sumTotal_utilidad += $utilidad;
		   } ?>
		           <tr class="rowCabecera">
		           		<td>&nbsp;</td>
		 				<td>&nbsp;</td>
		 				<td>&nbsp;</td>
		 				<td>&nbsp;</td>
		 				<td>TOTAL VENTAS</td>
		 			<td><?=$sumTotal_subTotal?></td>
		 			<td><?=$sumTotal_igv?></td>
		 			<td><?=$sumTotal_total_a_pagar?></td>
		 			<td><?=$sumTotal_total_costo?></td>
		 			<td><?=$sumTotal_utilidad?></td>

	
		 	
									
		</table>
</body>
</html>

