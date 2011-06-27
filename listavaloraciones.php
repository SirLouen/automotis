<?php
/*
 * Created on 27/06/2011
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

$maxvaloraciones = 10;

if ($_SESSION['nivelusuario'] >= 4 )
{
 
 	$sql = mysql_query("SELECT * FROM valoraciones ORDER BY id DESC ");
 	
 	$lineas = mysql_num_rows($sql);
 	if ($lineas)
 	{
 		echo "<table border='1'>";
 		echo "<tr><th colspan='5'>Lista de Valoraciones</th><tr>";
 		echo "<tr><td>Matricula</td><td>Usuario</td><td>Creacion</td><td>Enlace</td><td>Nueva Tasacion</td>";
 		for($i=0;$i<$lineas && $i<$maxvaloraciones;$i++)
 		{
 			$arrayval = mysql_fetch_array($sql);
 			$usuario = $arrayval['usuario'];
 			$sql2 = mysql_query("SELECT nombre FROM usuarios WHERE userid = '$usuario'");
 			$arrayusuarios = mysql_fetch_array($sql2);
 			$matricula = $arrayval['matricula'];
 			
 			
 			echo "<tr>";
 			echo "<td>".$matricula."</td>";
 			echo "<td>".$arrayusuarios['nombre']."</td>";
 			echo "<td>".$arrayval['fechacreacion']."</td>";
 			echo "<td><a target='_blank' href='tasaciones/".$arrayval['nombrefichero']."'>Descargar Fichero</a></td>";
 			echo "<td>";
 			echo "<form method='post' action='consultatasacion.php'>
				 <input type='hidden' name='idvaloracion' value='".$arrayval['id']."'>
				 <input type='hidden' name='matricula' value='".$matricula."'>
				 <input type=submit name='tasacionsubmit' value='Crear'>";
			echo "</form>";
			echo "</td>";
 			echo "</tr>";
 		}
 		echo "</table>";
 	}
 	
echo "<br>";
include("footer.php");
 
}
?>
