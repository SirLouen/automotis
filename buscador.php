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
      <td><? echo $lang_find_precio; ?></td>
      <td><input name="preciomenor" type="text" id="preciomenor" value="">
      <input name="preciomayor" type="text" id="preciomayor" value=""></td>
    </tr>
    <tr> 
      <td><? echo $lang_find_combustible; ?></td>
      <td><select name="combustible">
      <option selected value="TODOS">Cualquiera</option>
      <option value="DIESEL">Diesel</option>
      <option value="GASOLINA">Gasolina</option>
      </select></td>
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
		
	if ($_POST['preciomenor'] > 0)
		$preciomenor = $_POST['preciomenor'];
	else
		$preciomenor = "0";
	if ($_POST['preciomayor'] > 0)
		$preciomayor = $_POST['preciomayor'];
	else
		$preciomayor = "100000";
		
	if ($_SESSION['concesionario'] == "10")
		$nombreprecio = 'pvd';
	else
		$nombreprecio = 'pvp';	
	
	$combustible = $_POST['combustible'];
		
	if ($combustible == "TODOS")
		$combustible = "";	
	
	
	$sql = mysql_query("SELECT vehiculos.* FROM vehiculos_disponibles, vehiculos
			WHERE (vehiculos_disponibles.matricula = vehiculos.matricula) AND
		    (vehiculos.matricula LIKE '%$matricula%') AND (vehiculos.$nombreprecio >= '$preciomenor')
		    AND (vehiculos.$nombreprecio <= '$preciomayor') AND (combustible LIKE '%$combustible%')
			AND (marca LIKE '%$marca%') AND (modelo LIKE '%$modelo%') ORDER BY now()-fechaentrada DESC");
		
	$numrows = mysql_num_rows($sql);
	
	if($numrows > 0)
	{
		echo "<table border='1' align='center' cellpadding='4' cellspacing='0''>";
		echo "<tr>";
		echo "<td></td><td>".$lang_find_matricula."</td><td>".$lang_find_marca."</td><td>".$lang_find_modelo."</td>
			<td>".$lang_find_fechamatric."</td><td>".$lang_find_kilometros."</td>";
			
		if ($_SESSION['nivelusuario'] >= 3) 
			 echo "<td>".$lang_find_diasstock."</td>";

		echo "<td>".$lang_find_importe."</td>";

		echo "</tr>";
		
		for($i=0;$i<$numrows;$i++)
		{
			echo "<tr>";
			$arrayvehiculos = mysql_fetch_array($sql);
			$today = juliantojd(date("n"),date("j"),date("Y"));
			$fechaentry = $arrayvehiculos['fechaentrada'];
			$fechadiv = split("-",$fechaentry);
			$fechaentrada = juliantojd($fechadiv[1],$fechadiv[2],$fechadiv[0]);
			$diasstock = $today - $fechaentrada;
			$fechamatricula = fecha_normal($arrayvehiculos['fechamatric']);
			
			echo "<td style='vertical-align: top; width: 80px; height: 60px''>
			<a href='index.php?matricula=".$arrayvehiculos['matricula']."'>
			<img style='width: 80px; height: 60px;' alt='' src='./imagenes/".$arrayvehiculos['matricula']."/tn/1.jpg'>
			</a></td><td><a href='index.php?matricula=".$arrayvehiculos['matricula']."'>".$arrayvehiculos['matricula']."</a></td>
			<td>".$arrayvehiculos['marca']."</td><td>".$arrayvehiculos['modelo']."</td>
			<td>".$fechamatricula."</td><td>".$arrayvehiculos['kilometros']."</td>";
			
			if ($_SESSION['nivelusuario'] >= 3) 
			{
			 	echo "<td>".$diasstock."</td>";
			}
			if ($_SESSION['concesionario'] == 10)
				echo "<td>".$arrayvehiculos['pvd']."</td>";
			else
				echo "<td>".$arrayvehiculos['pvp']."</td>";		
			
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