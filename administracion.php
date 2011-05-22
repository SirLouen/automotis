<?php
/*
 * Created on 08/05/2011
 *
 * Automotis DMS  Copyright (C) <2011>  mcamargo
 * This program comes with ABSOLUTELY NO WARRANTY; 
 * This is free software, and you are welcome to redistribute it
 * under certain conditions; Read README file for more information

 */

include("config.php");
include("include/gen_functions.php");
include("lang/$lang.php");
session_start();

if ($_SESSION['nivelusuario'] >= 4)
{
	
	if (isset ($_POST['listatasacionsubmit']))
	{
		$matricula = $_POST['matricula'];
		$sql = mysql_query("SELECT * FROM tasaciones WHERE matricula = '$matricula'");
		$lineas = mysql_num_rows($sql);
		
		echo "<table border='1'>";
		echo "<tr><td>Numero</td><td>Nombre Tasador</td><td>Nombre Cliente</td><td>Acceso</td></tr>";
		
		for($i=0;$i<$lineas;$i++)
		{
			echo "<tr>";
			$arraytasacion = mysql_fetch_array($sql);
			echo "<td>".$arraytasacion['id']."</td>";
			$usuario = $arraytasacion['usuario'];
			$sql2 = mysql_query("SELECT nombre FROM usuarios WHERE userid = '$usuario'");
			$rowcliente = mysql_fetch_row($sql2);
			echo "<td>".$rowcliente[0]."</td>";
			echo "<td>".$arraytasacion['nombrecliente']."</td>";
			echo "<td>";	 
			echo "<form method='post' action='?'>
				<input type='hidden' name='idtasacion' value='".$arraytasacion['id']."'>
				<input type='hidden' name='matricula' value='".$matricula."'>
				<input type=submit name='infotasacionsubmit' value='Tasacion'>";
			echo "</td>";
		}

		echo "</table>";	
		include("footer.php");	
		
	}
	
	elseif (isset ($_POST['infotasacionsubmit'])) 
	{

		include("infotasacion.php");
		include("footer.php");

	}
	elseif ($_GET['idsubasta'])
	{
		include("infosubastas.php");
		include("footer.php");
	}

	else
	{
	
	?>
	
		<form method='post' action='?'>
		<table border="0" align="center">
		
			<tr>
			<td colspan="2" align="center"> <? echo $lang_admin_tasacion; ?> </td>
			</tr>
			<tr>
			<td>Matricula:</td><td><input type='text' id='matricula' name='matricula' size=30></td>	
			</tr>
			<tr>
				<td colspan="2" align="center"><input type=submit name='listatasacionsubmit' value="Buscar"></td> 
			</tr>
		
			</table>
		</form>
		<hr>
		<table border="1" align="center">
		<tr><td colspan ="4" align="center">Buscador de Subastas</td></tr>
		<tr><td>Num Id</td><td>Fecha Creacion</td><td>Marca</td><td>Modelo</td></tr>
		
		<?
		$maxsubastas = 7;
		
		$sql = mysql_query("SELECT * FROM subastas ORDER BY id DESC");
		$filas = mysql_num_rows($sql);
		for ($i=0;$i<$maxsubastas&&$i<$filas;$i++)
		{
			$arraysubastas = mysql_fetch_array($sql);
			$idsubasta = $arraysubastas['id'];
			echo "<tr>";
			echo "<td><a href='administracion.php?idsubasta=".$idsubasta."'>".$idsubasta."</td>";
			echo "<td>".$arraysubastas['fechacreacion']."</td>";
			$vehiculo = $arraysubastas['vehiculo'];
			$sql2 = mysql_query("SELECT * FROM vehiculos WHERE id = '$vehiculo'");
			$arrayvehiculo = mysql_fetch_array($sql2);
			echo "<td>".$arrayvehiculo['marca']."</td>";
			echo "<td>".$arrayvehiculo['modelo']."</td>";			
			
			echo "</tr>";
		 }
		 ?>
		</table>
		
	<?php
		include("footer.php");
	}
}	

?>