<?php
/*
 * Created on 21/04/2011
 *
 * webdms  Copyright (C) <2011>  mcamargo
 * This program comes with ABSOLUTELY NO WARRANTY; 
 * This is free software, and you are welcome to redistribute it
 * under certain conditions; Read README file for more information

 */
include ("config.php");
if ($_GET['pass'] == $pass)
{
 
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
	        $garantia =  $data[$i]; $i++;
	        $extras = $data[$i]; $i++;
	       
	       $sql = mysql_query("SELECT * FROM vehiculos WHERE (matricula = '$matricula')");
	       $login_check = mysql_num_rows($sql);
		
		   if($login_check > 0)
		   {
		   		echo "Existen<br>";
		    	mysql_query("UPDATE vehiculos
		    	SET (vin = '$vin', fechamatric = '$fechamatric', marca = '$marca', modelo = '$modelo',
		    	combustible = '$combustible', potencia = '$potencia', cilindrada = '$cilindrada',
		    	carroceria = '$carroceria', color = '$color', kilometros = '$kilometros', pvp = '$pvp',
		    	pvd = '$pvd', tipovehiculo = '$tipovehiculo', ubicacion = '$ubicacion',
		    	categoria = '$categoria', usoanterior = '$usoanterior', fechaentrada = '$fechaentrada',
		    	garantia = '$garantia', extras = '$extras')
		    	WHERE (matricula = '$matricula')");		
		    			
		   }
		   else
		   {
		   		echo "No Existen<br>";
		   		$sql = "INSERT INTO vehiculos (matricula, vin, fechamatric, marca, modelo, 
		    	combustible, potencia, cilindrada, carroceria, color, kilometros, pvp, pvd,
		    	tipovehiculo, ubicacion, categoria, usoanterior, fechaentrada, garantia, extras)
		    	VALUES ('$matricula', '$vin', '$fechamatric', '$marca', '$modelo', '$combustible',
		    	'$potencia', '$cilindrada', '$carroceria', '$color', '$kilometros', '$pvp', '$pvd',
		    	'$tipovehiculo', '$ubicacion', '$categoria', '$usoanterior', '$fechaentrada',
		    	'$garantia', '$extras')";
		    	if (mysql_query($sql,$conexion))
		    	{
		    		die('Error: '.mysql_error());
		    	}
		   }
	       
	    }
	    fclose($handle);
	}
}
else
{
	echo "No tiene acceso a esta pagina";
}
 
?>
