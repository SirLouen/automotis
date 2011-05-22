<?php
/*
 * Created on 22/05/2011
 *
 * Automotis DMS  Copyright (C) <2011>  Manuel Camargo
 * This program comes with ABSOLUTELY NO WARRANTY; 
 * This is free software, and you are welcome to redistribute it
 * under certain conditions; Read README file for more information

 */

 
 if ($_SESSION['nivelusuario'] >= 4)
 {
 	
 $idsubasta = $_GET['idsubasta'];
 $sql = mysql_query("SELECT * FROM subastas_pujas WHERE subasta = '$idsubasta' ORDER BY puja DESC");
 $lineas = mysql_num_rows($sql);
 
 echo "<table border='1' align ='center'>
 		<tr><td colspan ='4' align='center'>Lista de Pujas Subasta</td></tr>
		<tr><td>Id Puja</td><td>Fecha Insercion</td><td>Nombre Pujador</td><td>Importe</td></tr>";
 		
 for($i=0;$i<$lineas;$i++)
 {
 	$arraypujas = mysql_fetch_array($sql);
 	
 	echo "<tr>";
 	echo "<td>".$arraypujas['id']."</td>";
	echo "<td>".$arraypujas['fechainsercion']."</td>";
	$usuario = $arraypujas['usuario'];
	$sql2 = mysql_query("SELECT * FROM usuarios WHERE userid = '$usuario'");
	$arrayusuario = mysql_fetch_array($sql2);
	echo "<td>".$arrayusuario['nombre']."</td>";
	echo "<td>".$arraypujas['puja']."</td>";
	echo "</tr>";
 		
 }
 
 echo "</table>";
 
 }
 
?>
