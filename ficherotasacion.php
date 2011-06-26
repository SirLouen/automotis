<?php
/*
 * Created on 26/06/2011
 *
 * Automotis DMS  Copyright (C) <2011>  Manuel Camargo
 * This program comes with ABSOLUTELY NO WARRANTY; 
 * This is free software, and you are welcome to redistribute it
 * under certain conditions; Read README file for more information

 */
 
 include("config.php");
 include("lang/$lang.php");
 include_once("include/gen_functions.php");
 
 session_start();
 
 if ($_SESSION['nivelusuario'] >= 3)
 {


	if (isset ($_POST['ficherosubmit']))
	{
		
	
		//datos del arhivo 
		$nombre_archivo = $_FILES['userfile']['name']; 
		$tipo_archivo = $_FILES['userfile']['type']; 
		$tamano_archivo = $_FILES['userfile']['size']; 
		$matricula = $_POST['matricula'];
		$identificador = date("YnjHis");
		
		$upload_dir="tasaciones/";
		$nombre_fichero = $identificador.'_'.$matricula.".pdf";
		$img_path = $upload_dir.$nombre_fichero;
	 
		if (!((strpos($tipo_archivo, "pdf") && ($tamano_archivo < 5000000)))) 
		{ 
	   		echo "La extension o el tama&ntildeo de los archivos no es correcta. <br><br>
	   		 <table><tr><td><li>Se permiten archivos PDF<br>
	   		 <li>Se permiten archivos de 5Mb maximo.</td></tr></table>"; 
		}
		else
		{ 
			if(copy( $_FILES['userfile']['tmp_name'], $img_path ) )
			{
	        	echo "El archivo ha sido cargado correctamente."; 
	   		}
	   		else
	   		{ 
	      	    echo "Ocurrio algun error al subir el fichero. No pudo guardarse."; 
	   		} 
		} 
		echo "<br><br>";
		include("footer.php");
		
	} 
	
	else
	{
		?>
	
		<center>
		<form action="ficherotasacion.php" method="post" enctype="multipart/form-data"> 
	   	 	Subir Archivo PDF de Tasacion: 
	   	 <br> 
	   	 Matricula: <input type="text" size="8" maxlength="8" name="matricula" value="" /><br>
	   	 <input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
	   	 <input name="userfile" type="file"> 
	   	 <br> 
	   	 <input name="ficherosubmit" type="submit" value="Enviar"> 
		</form> 
		</center>
		
		<?
	}

 }
?>