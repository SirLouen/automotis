<?php 

/*
 * Created on 18/04/2011
 *
 * automotis  Copyright (C) <2011>  Manuel Camargo
 * This program comes with ABSOLUTELY NO WARRANTY; 
 * This is free software, and you are welcome to redistribute it
 * under certain conditions; Read README file for more information

 */


session_start();

//include("topheader.php");
include("config.php");
include("include/gen_functions.php");
include("lang/$lang.php");


if(isset($_REQUEST['exit']))
{
	
	session_destroy();
	
	if(!session_is_registered('nombre'))
	{
		echo "<center><font color=red><strong>$lang_index_logout</strong></font></center><br />";
		echo ("<center><a href='index.php'>$lang_index_back</a></center>");
	}
}
else
{
	if($_SESSION['nivelusuario'] < 1)
	{
		include("login.php");  
	} 
	else 
	{
		if ($_GET['matricula'])
		{
			include("fichavehiculo.php");
		}
		
		else
		{
			echo ("$lang_index_welcome<br><br>");
			echo "<hr>";
			
			if ($_SESSION['nivelusuario'] >= 3)
			{
				include("ficherotasacion.php");
				echo "<hr>";
			}
			
			if ($_SESSION['nivelusuario'] >= 4)
			{
				include("consultatasacion.php");
				echo "<hr>";
			}
			
			if ($_SESSION['concesionario'] == 10 || $_SESSION['nivelusuario'] >= 5 )
			{
				echo("<center><a href='subastas.php'>Acceso a Subastas</a></center>");
				echo "<hr>";
			}
			
			
			echo ("<center>$lang_index_buscador</center><br>");
			include("buscador.php");
			echo "<hr>";
	
			if($_SESSION['nivelusuario'] >= 5)
			{
				echo("<center>$lang_index_admin_menu:<br>
					<a href='administracion.php'>Administracion</a>
					<a href='estadisticas.php'>Estadisticas</a></center>");
				echo "<hr>";

			}
		}

		include("footer.php");
	}
}



?>