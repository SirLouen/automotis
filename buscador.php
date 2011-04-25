<?php
/*
 * Created on 18/04/2011
 *
 * webdms  Copyright (C) <2011>  mcamargo
 * This program comes with ABSOLUTELY NO WARRANTY; 
 * This is free software, and you are welcome to redistribute it
 * under certain conditions; Read README file for more information

 */
?>

<form method='post' action='?'>
  <table border="0" align="center" cellpadding="4" cellspacing="0">
  	<tr> 
      <td><? echo $lang_find_matricula; ?></td>
      <td><input name="matricula" type="text" id="matricula" value=""></td>
    </tr>
    <tr> 
      <td><? echo $lang_find_marca; ?></td>
      <td><input name="marca" type="text" id="marca" value=""></td>
    </tr>
    <tr> 
      <td><? echo $lang_find_modelo; ?></td>
      <td><input name="modelo" type="text" id="modelo" value=""></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td><input type="submit" name="submit" value="OK"></td>
    </tr>
  </table>
</form>

<?

if (isset ($_POST['submit'])) 
{
	$matricula = $_POST['matricula'];
	$marca = $_POST['marca'];
	$modelo = $_POST['modelo'];	
	
	$sql = mysql_query("SELECT vehiculos.* FROM vehiculos_disponibles, vehiculos
			WHERE (vehiculos_disponibles.matricula = vehiculos.matricula) AND
		    (vehiculos.matricula LIKE '%$matricula%') 
			AND (marca LIKE '%$marca%') AND (modelo LIKE '%$modelo%')");
		
	$login_check = mysql_num_rows($sql);
	
	if($login_check > 0)
	{
		echo "<table border='1' align='center' cellpadding='4' cellspacing='0''>";
		echo "<tr>";
		echo "<td></td><td>".$lang_find_matricula."</td><td>".$lang_find_marca."</td><td>".$lang_find_modelo."</td>";
		echo "</tr>";
				
		while($row = mysql_fetch_array($sql))
		{
			echo "<tr>";
			
			foreach( $row AS $key => $val )
			{
				$$key = stripslashes( $val );
			}	
			echo "<td style='vertical-align: top; width: 80px; height: 60px''>
			<a href='index.php?matricula=".$matricula."'>
			<img style='width: 80px; height: 60px;' alt='' src='./imagenes/".$matricula."/tn/1.jpg'>
			</a></td><td><a href='index.php?matricula=".$matricula."'>$matricula</a></td>
			<td>$marca</td><td>$modelo</td>";
			
			echo "</tr>";
		}
		echo "</table>";
	}
	else 
	{
		echo "$lang_find_fail<br />";
	}
	
}

?>