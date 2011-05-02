<?php

/*
 * Created on 18/04/2011
 *
 * automotis  Copyright (C) <2011>  Manuel Camargo
 * This program comes with ABSOLUTELY NO WARRANTY; 
 * This is free software, and you are welcome to redistribute it
 * under certain conditions; Read README file for more information

 */

// Extensiones validas
$extensions = array('jpg','jpeg','gif','png','bmp','JPG');
$matricula = $_GET['matricula'];

$folder_image_name = "/automotis/imagenes/$matricula/";
$images_folder_path = $_SERVER['DOCUMENT_ROOT'].$folder_image_name;
$url_to_folder = 'http://'.$_SERVER["SERVER_NAME"].$folder_image_name;

$images = array();
$j=0;

// Introducir las imagenes en un array
if ($handle = opendir($images_folder_path)) {
    while (false !== ($file = readdir($handle))) {
        if ($file != "." && $file != "..") {

          $ext = strtolower(substr(strrchr($file, "."), 1));
                
          if(in_array($ext, $extensions)){
            $images[] = $url_to_folder.$file;
            $j++;
          }
        }
    }
    closedir($handle);
}

if ($_GET['i'])
	$i = $_GET['i'];
else
	$i = 0;

if(!empty($images)) 
{
	$src = $images[$i];
	if ($i == $j-1)
		$i=0;
	else
		$i++;
	echo ("<a href='imagenes.php?i=".$i."&matricula=".$matricula."'><img src=".$src." align='absmiddle'></a>");
}
else
	echo 'Atencion! Ha habido un error, o no hay imagenes para mostrar';

?>
