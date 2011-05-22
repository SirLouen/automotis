<?php
/*
 * Created on 22/05/2011
 *
 * Automotis DMS  Copyright (C) <2011>  Manuel Camargo
 * This program comes with ABSOLUTELY NO WARRANTY; 
 * This is free software, and you are welcome to redistribute it
 * under certain conditions; Read README file for more information

 */
 
include("config.php");
include("include/gen_functions.php");
include("lang/$lang.php");
session_start();

if (isset ($_POST['ofertassubmit']))
{
	$diaini = $_POST['diaini'];
	$mesini = $_POST['mesini'];
	$anyoini = $_POST['anyoini'];
	$diafin = $_POST['diafin'];
	$mesfin = $_POST['mesfin'];
	$anyofin = $_POST['anyofin'];
	
	$horaini = "00:00:00";
	$horafin = "23:59:59";
	$fechaini = $anyoini."-".$mesini."-".$diaini." ".$horaini;
	$fechafin = $anyofin."-".$mesfin."-".$diafin." ".$horafin;
	
	
	$sql = mysql_query("SELECT * FROM ofertas WHERE fechacreacion >= '$fechaini' AND fechacreacion <= '$fechafin' ORDER BY id DESC");
	$lineas = mysql_num_rows($sql);
	
	echo "<table border='2'>";
	echo "<tr align='center'><td colspan = '10'> $lang_stats_listofertas </td></tr>";
	echo "<tr><td>ID Of</td><td>Nombre</td><td>Apellidos</td><td>Matricula</td><td>PVP</td><td>Tasacion</td><td>Desc Real</td>
		 <td>Precio Oferta</td><td>Usuario</td><td>Fecha Creacion</td></tr>";
	
	
	for($i=0;$i<$lineas;$i++)
	{
		$arrayofertas = mysql_fetch_array($sql);
		
		$vehiculo = $arrayofertas['vehiculo'];
		$sql2 = mysql_query("SELECT * FROM vehiculos WHERE id = '$vehiculo'");
		$arrayvehiculos = mysql_fetch_array($sql2);
		$usuario = $arrayofertas['usuario'];
		$sql3 = mysql_query("SELECT * FROM usuarios WHERE userid = '$usuario'");
		$arrayusuarios = mysql_fetch_array($sql3);
		$tasacion = $arrayofertas['tasacion'];
		$sql4 = mysql_query("SELECT * FROM tasaciones WHERE id = '$tasacion'");
		$arraytasacion = mysql_fetch_array($sql4);
		$cliente = $arrayofertas['cliente'];
		$sql5 = mysql_query("SELECT * FROM clientes WHERE id = '$cliente'");
		$arrayclientes = mysql_fetch_array($sql5);
		
		
		if ($arraytasacion['esfuerzocomercial3'])
			$esfuerzocomercial = $arraytasacion['esfuerzocomercial3'];
		elseif ($arraytasacion['esfuerzocomercial2'])
			$esfuerzocomercial = $arraytasacion['esfuerzocomercial2'];
		else
			$esfuerzocomercial = $arraytasacion['esfuerzocomercial1'];
		
		$valortasacion = $esfuerzocomercial - $arraytasacion['totalreacondicionamiento'] + $arraytasacion['valormercado'];
		$totaldescuentos = $arrayofertas['descuento'] + $valortasacion;
		
		$costevehiculo = $arrayvehiculos['pvp'] + $arrayofertas['sobreprecio'];
		$preciofinal = $costevehiculo - $totaldescuentos;
		
		$descuentoreal = + $arrayofertas['sobreprecio'] - $arrayofertas['descuento'];
		
		$fechacreacion = convertir_fecha_normal($arrayofertas['fechacreacion']);
		
		echo "<tr>";
		echo "<td>".$arrayofertas['id']."</td>";
		echo "<td>".$arrayclientes['nombre']."</td>";
		echo "<td>".$arrayclientes['apellidos']."</td>";
		echo "<td>".$arrayvehiculos['matricula']."</td>";
		echo "<td>".$arrayvehiculos['pvp']."</td>";
		echo "<td>".$valortasacion."</td>";
		echo "<td>".$descuentoreal."</td>";
		echo "<td>".$preciofinal."</td>";
		echo "<td>".$arrayusuarios['nombre']."</td>";
		echo "<td>".$fechacreacion."</td>";
		echo "</tr>";
	}
	
	echo "</table>";
	include ("footer.php");
	
}
 
else
{
?>

<form method='post' action='?'>
	<table border="0" align="center">
	
		<tr>
			<td colspan="2" align="center"> <? echo $lang_stats_ofertas; ?> </td>
		</tr>
		<tr>
			<td><? echo $lang_stats_fechaini; ?></td>
			<td>
				<input type='text' name='diaini' maxlength='2' size='2'>
				<input type='text' name='mesini' maxlength='2' size='2'>
				<input type='text' name='anyoini' maxlength='4' size='4'>
			</td>
		</tr>
		<tr>
			<td><? echo $lang_stats_fechafin; ?></td>
			<td>
				<input type='text' name='diafin' maxlength='2' size='2'>
				<input type='text' name='mesfin' maxlength='2' size='2'>
				<input type='text' name='anyofin' maxlength='4' size='4'>
			</td>	
		</tr>
		<tr>
			<td colspan="2" align="center"><input type=submit name='ofertassubmit' value="Buscar"></td> 
		</tr>
		
	</table>
</form>

<?

	include ("footer.php");

}

?>