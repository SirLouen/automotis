<?php
/*
 * Created on 24/04/2011
 *
 * webdms  Copyright (C) <2011>  mcamargo
 * This program comes with ABSOLUTELY NO WARRANTY; 
 * This is free software, and you are welcome to redistribute it
 * under certain conditions; Read README file for more information

 */
 $usuario = $_SESSION['userid'];
 $matricula = $_POST['matricula'];
 $idvehiculo = $_POST['idvehiculo'];
 $maxtasaciones = 5;
?>

<form method='post' action='?'>
	<b><center>Matricula :<?php echo "$matricula" ; ?></center></b><br>

	<input type="hidden" name="matricula" value="<?php echo "$matricula"; ?>">
	<input type='hidden' name='idvehiculo' value="<?php echo "$idvehiculo"; ?>">
	
<table border="0" align ="center">

	<tr>
		<th colspan='2'>Datos del Cliente</th>
	</tr>
	
	<tr>
		<td>Tipo Cliente:</td>
		<td><select name='tipo_cliente'> <option value="E">Empresa</option>
		 <option value="P" selected>Particular</option></select></td>
	</tr>
	
	<tr>
		<td>Codigo Cortesia:</td>
		<td><select name = 'codigocortersia_cliente'> 
		<option value="sr" selected>Sr.</option>
		<option value="sra" >Sra.</option>
		<option value="srta" >Srta.</option></select></td>
	</tr>
	
	<tr>
		<td>Nombre/Nombre Empresa:</td><td> <input type='text' name='nombre_cliente' value='' size=20 maxlength='20'></td>
	</tr>
	
	<tr>
		<td>Apellidos (Vacio si es Empresa):</td><td> <input type='text' name='apellidos_cliente' value='' size=30 maxlength='20'></td>
	</tr>
	
	<tr>
		<td>Movil:</td><td> <input type='text' name='movil_cliente' value='' size=20 maxlength='20'></td>
	</tr>
	
	<tr>
		<td>Telefono Fijo:</td><td> <input type='text' name='fijo_cliente' value='' size=20 maxlength='20'></td>
	</tr>
	
	<tr>
		<td>E-Mail Cliente:</td><td> <input type='text' name='email_cliente' value='' size=40 maxlength='40'></td>
	</tr>
	
	<tr>
		<td>Codigo Postal Cliente:</td><td> <input type='text' name='cp_cliente' value='' size=20 maxlength='20'></td>
	</tr>
	<tr>
		<th colspan='2'>Datos Tasacion</th>
	</tr>	
	<tr>
		<td>Tasacion:</td>
		<td><select name='tasacion'> 
		 <option value=''>Sin Tasacion</option>
		 <?php 
		 $sql = mysql_query("SELECT * FROM tasaciones WHERE usuario = '$usuario' ORDER BY id DESC");
		 $filas = mysql_num_rows($sql);
		 for ($i=0;$i<$maxtasaciones&&$i<$filas;$i++)
		 {
		 	$array = mysql_fetch_array($sql);
		 	echo "<option value=".$array['id'].">".$array['matricula']."</option>";
		 }
		 ?></select></td>
	</tr>	 
	<tr>
		<td>Sobre-Precio:</td><td> <input type='text' name='sobreprecio' value='' size=20 maxlength='20'></td>
	</tr>
	<tr>
		<td>Descuento Especial:</td><td> <input type='text' name='descuento' value='' size=20 maxlength='20'></td>
	</tr>
	
	<tr>
		<th colspan='2'>Datos Financiacion</th>
	</tr>
	
	<tr>
		<td colspan='2'>
			<table align='center'>
				<tr>
					<td></td><td>Entrada</td><td>Meses</td><td>Importe</td>
				</tr>
				<tr>
					<td>1</td>
					<td><input type='text' name='entrada1' value='' size=10 maxlength='10'></td>
					<td><input type='text' name='meses1' value='' size=3 maxlength='3'></td>
					<td><input type='text' name='importe1' value='' size=10 maxlength='10'></td>
				</tr>
				<tr>
					<td>2</td>
					<td><input type='text' name='entrada2' value='' size=10 maxlength='10'></td>
					<td><input type='text' name='meses2' value='' size=3 maxlength='3'></td>
					<td><input type='text' name='importe2' value='' size=10 maxlength='10'></td>
				</tr>
				<tr>
					<td>3</td>
					<td><input type='text' name='entrada3' value='' size=10 maxlength='10'></td>
					<td><input type='text' name='meses3' value='' size=3 maxlength='3'></td>
					<td><input type='text' name='importe3' value='' size=10 maxlength='10'></td>
				</tr>
			</table>
		</td>
	</tr>
	

	<tr>
		<td align=right><input type='submit' value='Enviar' name='inofertasubmit'></td>
	</tr>
	
</table>