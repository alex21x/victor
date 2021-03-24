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
		padding: 0px;
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

	table tr td.espaciado{
		font-size: 8px;
		padding: 2px;
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
img{
	float: left;
	padding-left: 15px;
}

</style>


<img src="<?PHP echo FCPATH;?>images/<?php echo $empresa['foto'];?>" width="80" style="text-align:center;" border="0">
<table>
	<tr>
		<th style="text-align: center;" width="80">.</th>
		<th style="background-color:#dddddd; font-size: 12px;padding: 5px">Reporte stock minimo</th>
	</tr>
</table>

<table>
  <tr>
  	<th width="80" style="text-align: center;">.</th>
  	<th>EMPRESA</th>
  	<th>:<?php echo $empresa['empresa'];?></th>
  </tr>
	<tr>
	<td></td>
  	<td>DIRECCION</td>
  	<td>:<?php echo $empresa['domicilio_fiscal'];?></td>
  </tr>
  <tr>
  	<td></td>
  	<td>EMPLEADO</td>
  	<td>:<?php echo $caja->empleado;?></td>
  </tr>
  <tr>
  	<td></td>
  	<td>LOCAL</td>
  	<td>:<?php echo $this->session->userdata('almacen_nom');?></td>
  </tr>

</table>
 <br>
<table border=1 >
	<tr class="rowCabecera">
		<th class="rowCodigo">CATEGORIA</th>							
		<th class="rowProducto">CODIGO</th>		
		<th class="rowProducto_">DESCRIPCION</th>
		<th class="rowProducto_des">CANTIDAD</th>
		<th class="rowProducto_des">STOCK MINIMO</th>									
	</tr>


	<?PHP		
        $rsTotal_venta = 0;
		$rsTotal_compra = 0;
		$rsTotal_utilidad = 0;
		$rsTotal_comision = 0;
		$rsTotal_utilidadNeta = 0;	
		
     foreach($reporteTotal  as $value) { ?>

			  <tr>
				<td><?= $value['cat_nombre']?></td>
                <td><?= $value['prod_codigo'] ?></td>
                <td><?= $value['prod_nombre'] ?></td>
                <td><?= $value['prod_stock'] ?></td>
                <td><?= $value['prod_cantidad_minima'] ?></td>
             </tr>

        <?php   			     
		} 
		?>
		 		   
		 	
									
		</table>
</body>
</html>

