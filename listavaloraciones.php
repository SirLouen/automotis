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
 
 	$sql = mysql_query("SELECT * FROM ficheros_valoraciones ORDER BY id DESC ");
 	
 	$lineas = mysql_num_rows($sql);
 	if ($lineas)
 	{
 		echo "<table>";
 		echo "<tr><th colspan='4'>Lista de Valoraciones</th><tr>";
 		echo "<tr><td>Matricula</td><td>Usuario</td><td>Creacion</td><td>Enlace</td>";
 		for($i=0;$i<$lineas && $i<$maxvaloraciones;$i++)
 		{
 			$arrayval = mysql_fetch_array($sql);
 			
 			echo "<tr>";
 			echo "<td>".$arrayval['matricula']."</td>";
 			echo "<td>".$arrayval['usuario']."</td>";
 			echo "<td>".$arrayval['fechacreacion']."</td>";
 			echo "<td><a href='tasaciones/".$arrayval['nombrefichero'].">Descargar Fichero</a></td>";
 			echo "</tr>";
 		}
 		echo "</table>";
 	}
 	
echo "<br>";
include("footer.php");
 
}
?>
