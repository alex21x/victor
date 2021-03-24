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
		<th  width="300" style="text-align: center; background-color:#dddddd; font-size: 12px;padding: 6px">Reporte Reparto Transportista</th>
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
		<th class="rowCodigo">CODIGO</th>							
		<th class="rowProducto">DESCRIPCION</th>		
		<th class="rowProducto_">CANTIDAD</th>
		<th class="rowProducto_des">UNIDAD</th>										
	</tr>


	<?PHP		
	$rsTotal_cantidad = 0;			

	foreach ($reporteTotal as $value) {?>			
			<tr>
				<td class="rowCliente"><?= $value['prod_codigo']?></td>
                <td><?= $value['prod_nombre'] ?></td>
                <td><?= $value['cantidad'] ?></td>
                <td><?= $value['medida_nombre'] ?></td>
             </tr>
        <?php   			
		$rsTotal_cantidad += $value['cantidad'];
		
		} ?>
		 			<tr class="rowCabecera"> 
		 				<td>&nbsp;</td>
		 				<td>TOTAL</td>			 							 				
		 				<td><?= $rsTotal_cantidad ?></td>		 				
		 			</tr>
		  <?php  ?>
	
			
									
		</table>
</body>
</html>

