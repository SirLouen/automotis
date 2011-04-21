<?php
/*
 * Created on 18/04/2011
 *
 * webdms  Copyright (C) <2011>  mcamargo
 * This program comes with ABSOLUTELY NO WARRANTY; 
 * This is free software, and you are welcome to redistribute it
 * under certain conditions; Read README file for more information

 */
 
 // Extensiones validas
 $extensions = array('jpg','jpeg','gif','png','bmp');
 $matricula = $_GET['matricula'];

 $folder_image_name = "/webdms/imagenes/$matricula/";
 $images_folder_path = $_SERVER['DOCUMENT_ROOT'].$folder_image_name;
 $url_to_folder = 'http://'.$_SERVER["SERVER_NAME"].$folder_image_name;

 $images = array();

 // Introducir las imagenes en un array
 if ($handle = opendir($images_folder_path)) {
    while (false !== ($file = readdir($handle))) {
        if ($file != "." && $file != "..") {

          $ext = strtolower(substr(strrchr($file, "."), 1));
                
          if(in_array($ext, $extensions)){
            $images[] = $file;
          }
        }
    }
	closedir($handle);
 }
 
 
 $matricula = $_GET['matricula'];
 
 $sql = mysql_query("SELECT * FROM vehiculos WHERE (matricula = '$matricula')");
		
 $login_check = mysql_num_rows($sql);
 
 if($login_check > 0)
	{
		while($row = mysql_fetch_array($sql))
		{
			foreach( $row AS $key => $val )
			{
				$$key = stripslashes( $val );
			}
			
			
		?>
		
		
			<table style="text-align: left; width: 800px;" border="1" cellpadding="2" cellspacing="2">
				<tr>
					<td colspan="2" rowspan="6" style="vertical-align: top; width: 320px;"><a target="_blank" href="imagenes.php?i=0&amp;matricula=<?php echo $matricula;?>"><img style="width: 330px; height: 240px;" alt="" src="./imagenes/<? echo $matricula; ?>/<? echo $images['0'];?>"></a><br></td>
					<td style="vertical-align: top;"><?php echo $lang_file_matricula.': '.$matricula; ?><br></td>
				</tr>
				<tr>
					<td style="vertical-align: top;"><?php echo $lang_file_marca.': '.$marca; ?><br></td>
				</tr>
				<tr>
					<td style="vertical-align: top;"><?php echo $lang_file_modelo.': '.$modelo; ?><br></td>
				</tr>
				<tr>
					<td style="vertical-align: top;"><?php echo $lang_file_kilometros.': '.$kilometros; ?><br></td>
				</tr>
				<tr>
					<td style="vertical-align: top;"><?php echo $lang_file_fechamatric.': '.$fechamatric; ?><br></td>
				</tr>
				<tr>
					<td style="vertical-align: top;"><?php
					if ($_SESSION['concesionario']=="10")
						echo $lang_file_pvd.': '.$pvd; 	
					else
					 	echo $lang_file_pvp.': '.$pvp;						
					?><br></td>
				</tr>
				<tr>
					<td colspan="1" rowspan="3" style="vertical-align: top; width: 160px;"><a target="_blank" href="imagenes.php?i=1&amp;matricula=<?php echo $matricula;?>"><img style="width: 160px; height: 120px;" alt="" src="./imagenes/<? echo $matricula; ?>/<? echo $images['1'];?>"></a><br></td>
					<td colspan="1" rowspan="3" style="vertical-align: top; width: 160px;"><a target="_blank" href="imagenes.php?i=2&amp;matricula=<?php echo $matricula;?>"><img style="width: 160px; height: 120px;" alt="" src="./imagenes/<? echo $matricula; ?>/<? echo $images['2'];?>"></a><br></td>
					<td style="vertical-align: top;"><?php echo $lang_file_combustible.': '.$combustible; ?><br></td>
				</tr>
				<tr>
					<td style="vertical-align: top;"><?php echo $lang_file_potencia.': '.$potencia.' CV'; ?><br></td>
				</tr>
				<tr>
					<td style="vertical-align: top;"><?php echo $lang_file_cilindrada.': '.$cilindrada; ?><br></td>
				</tr>
				<tr>
					<td colspan="2" rowspan="1" style="vertical-align: middle; width: 320px; height: 40px;"><?php echo $lang_file_extras; ?><br></td>
					<td style="vertical-align: top; height: 40px;"><?php echo $lang_file_carroceria.': '.$carroceria; ?><br></td>
				</tr>
				<tr>
					<td colspan="2" rowspan="4" style="vertical-align: middle; width: 320px; height: 160px;"><?php echo $extras; ?><br></td>
					<td style="vertical-align: top; height: 40px;"><?php echo $lang_file_color.': '.$color; ?><br></td>
				</tr>
				<tr>
					<td style="vertical-align: top; height: 40px;"><?php echo $lang_file_ubicacion.': '.$ubicacion; ?><br></td>
				</tr>
				<tr>
					<td style="vertical-align: top; height: 40px;"><?php echo $lang_file_categoria.': '.$categoria; ?><br></td>
				</tr>
				<tr>
					<td style="vertical-align: top; height: 40px;"><?php echo $lang_file_usoanterior.': '.$usoanterior; ?><br></td>
				</tr>
			</table>
		
		<?php
				
		}
	}
	else 
	{
		echo "$lang_file_fail<br />";
	}
?>
