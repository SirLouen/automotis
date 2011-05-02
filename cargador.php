<?php
/*
 * Created on 21/04/2011
 *
 * automotis  Copyright (C) <2011>  Manuel Camargo
 * This program comes with ABSOLUTELY NO WARRANTY; 
 * This is free software, and you are welcome to redistribute it
 * under certain conditions; Read README file for more information

 */

include ("config.php");
include ("lang/$lang.php");
include ("include/gen_functions.php");

mysql_query("TRUNCATE TABLE `vehiculos_disponibles`"); 

 
	$row = 1;
	if (($handle = fopen("stockvo.csv", "r"))) 
	{
	    while (($data = fgetcsv($handle, 2000, ","))) 
	    {
	        $column = count($data);
	        $row++;
	        $i = 0;
	        
	        $matricula = $data[$i]; $i++;
	        $vin = $data[$i]; $i++;
	        $fechamatric = $data[$i]; $i++;
	        $fechamatric = fecha_mysql($fechamatric);
	        $marca = $data[$i]; $i++;
	        $modelo = $data[$i]; $i++;
	        $combustible = $data[$i]; $i++;
	        $potencia = $data[$i]; $i++;
	        $cilindrada = $data[$i]; $i++; 
	        $carroceria = $data[$i]; $i++;
	        $color = $data[$i]; $i++;
	        $kilometros = $data[$i]; $i++;
	        $pvp = $data[$i]; $i++;
	        $pvd = $data[$i]; $i++;
	        $tipovehiculo = $data[$i]; $i++;
	        $ubicacion = $data[$i]; $i++;
	        $categoria = $data[$i]; $i++;
	        $usoanterior = $data[$i]; $i++;
	        $fechaentrada = $data[$i]; $i++;
	        $fechaentrada = fecha_mysql($fechaentrada);
	        $garantia =  $data[$i]; $i++;
	        $extras = $data[$i]; $i++;
	        $idv = $data[$i]; $i++;

	       $sql = mysql_query("SELECT * FROM vehiculos WHERE (matricula = '$matricula')");
	       $login_check = mysql_num_rows($sql);
		
		   if($login_check > 0)
		   {
		   		//echo "Existen<br>";
		    	$sql = "UPDATE vehiculos
		    	SET vin = '$vin', fechamatric = '$fechamatric', marca = '$marca', modelo = '$modelo',
		    	combustible = '$combustible', potencia = '$potencia', cilindrada = '$cilindrada',
		    	carroceria = '$carroceria', color = '$color', kilometros = '$kilometros', pvp = '$pvp',
		    	pvd = '$pvd', tipovehiculo = '$tipovehiculo', ubicacion = '$ubicacion',
		    	categoria = '$categoria', usoanterior = '$usoanterior', fechaentrada = '$fechaentrada',
		    	garantia = '$garantia', extras = '$extras', idv = '$idv', fechainsercion = now()
		    	WHERE (matricula = '$matricula')";	
		    	if (!(mysql_query($sql,$conexion)))
		    	{
		    		die('Error: '.mysql_error());
		    	}
		    	
		    	    	
		    	$sqldisponibles = "INSERT INTO vehiculos_disponibles (matricula) VALUES ('$matricula')";
		    	
		    	if (!(mysql_query($sqldisponibles,$conexion)))
		    	{
		    		die('Error: '.mysql_error());
		    	}

		    			
		   }
		   else
		   {
		   		//echo "No Existen<br>";
		   		$sql = "INSERT INTO vehiculos (matricula, vin, fechamatric, marca, modelo, 
		    	combustible, potencia, cilindrada, carroceria, color, kilometros, pvp, pvd,
		    	tipovehiculo, ubicacion, categoria, usoanterior, fechaentrada, garantia, extras,
		    	idv, fechainsercion)
		    	VALUES ('$matricula', '$vin', '$fechamatric', '$marca', '$modelo', '$combustible',
		    	'$potencia', '$cilindrada', '$carroceria', '$color', '$kilometros', '$pvp', '$pvd',
		    	'$tipovehiculo', '$ubicacion', '$categoria', '$usoanterior', '$fechaentrada',
		    	'$garantia', '$extras','$idv',now())";
		    	if (!(mysql_query($sql,$conexion)))
		    	{
		    		die('Error: '.mysql_error());
		    	}
		    	
		    	$sqldisponibles = "INSERT INTO vehiculos_disponibles (matricula) VALUES ('$matricula')";
		    	
		    	if (!(mysql_query($sqldisponibles,$conexion)))
		    	{
		    		die('Error: '.mysql_error());
		    	}
		   }
	       
	    }
	    fclose($handle);
	}
	
	$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
	$cabeceras .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
	$cabeceras .= 'From: Peugeot Ibericar' . "\r\n";
	$mensaje = $lang_loader_mensajeemail;
	$asunto = $lang_loader_asuntoemail;
	
    mail ($adminemail,$asunto,$mensaje,$cabeceras);
	
//}
//else
//{
//	echo "$lang_loader_fail";
//}
 
?>
