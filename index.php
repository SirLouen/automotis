<?php 

/*
 * Created on 18/04/2011
 *
 * webdms  Copyright (C) <2011>  Manuel Camargo
 * This program comes with ABSOLUTELY NO WARRANTY; 
 * This is free software, and you are welcome to redistribute it
 * under certain conditions; Read README file for more information

 */

session_start();

include("config.php");
include("lang/$lang.php");
include("header.php");

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
		echo("<center>$lang_index_ident:</center><br>");
		include("login.php"); 
		return; 
	} 
	else 
	{
		echo ("$lang_index_welcome<br>");
		echo ("$lang_index_buscador");

		include("buscador.php");
	
		if($_SESSION['nivelusuario'] >= 2)
		{
			echo("
			<p>
				$lang_index_admin_menu:<br>
				En construccion: <a href='estadisticas.php'>Estadisticas</a>
			</p>	
			");
		}
		
		include("footer.php");
	}
}



?>