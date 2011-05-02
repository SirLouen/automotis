<?php
/*
 * Created on 22/04/2011
 *
 * automotis  Copyright (C) <2011>  Manuel Camargo
 * This program comes with ABSOLUTELY NO WARRANTY; 
 * This is free software, and you are welcome to redistribute it
 * under certain conditions; Read README file for more information

 */
 

//Convierte fecha de mysql a normal

function fecha_normal($fecha)
{
    ereg( "([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})", $fecha, $mifecha);
    $lafecha=$mifecha[3]."/".$mifecha[2]."/".$mifecha[1];
    return $lafecha;
}


//Convierte fecha de normal a mysql

function fecha_mysql($fecha)
{
    ereg( "([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})", $fecha, $mifecha);
    $lafecha=$mifecha[3]."-".$mifecha[1]."-".$mifecha[2];
    return $lafecha;
} 

//Esta función convierte la fecha del formato DATETIME de SQL
//a formato DD-MM-YYYY HH:mm:ss

// Saca fecha de DATETIME

function convertir_fecha_normal($fecha_datetime)
{	
	$date_array = explode("-", $fecha_datetime);
	$day = $date_array[2];
	$day = $day[0].$day[1];
	return $day.'/'.$date_array[1].'/'.$date_array[0];
}
 
?>
