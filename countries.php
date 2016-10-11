<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>Public Employment Services Survey 2015, by WAPES and IDB</title>
<link href="css/countries.css" rel="stylesheet"></head>
<body><?php include_once("./analyticstracking.php");

// Determinamos idioma
if (strpos($_SERVER['HTTP_HOST'], 'mundospe.org')) {
 $mylang="es";
 $campo_seccion ='Section_es';
}elseif (strpos($_SERVER['HTTP_HOST'], 'mondedesspe.org')) { 
 $mylang="fr";
 $campo_seccion ='Section_fr';
}else{
 $mylang="en"; //default
 $campo_seccion ='Section';
}
 ?>
   <?

if (isset($_GET['region'])) {
    //echo $_GET['region']."</BR>";
	$datos = convertCSVtoAssocMArray("PESsurvey2015.csv", ",",$_GET['region']); //,$_GET['region']
	$definiciones = convertCSVtoAssocMArray("PEScategories.csv", ","); //,$_GET['region']
	//print_r($definiciones);
	?>   
	
<table id="hor-zebra" summary="<? print $datos[1]['Country'] ;?>">
    
<?	
}else{
    // Fallback behaviour goes here
	//$datos = convertCSVtoAssocMArray("PESsurvey2015.csv", ","); 
	//print_r($datos);
	
}
if (!empty($datos)) {
	$descriptions = $datos[0];
	array_shift($datos);
	
	//cabecera de los paises
	print ' <thead>
   <tr><th></th>';
	
     for ($i = 0; $i < count($datos); ++$i) {
		//print $datos[$i][$item];
        /*    if(array_key_exists($item,$datos[$i]) && $datos[$i][$item]=='1') 
		{print "YES".$datos[$i][$item];
		}else{print "NO";
		} */
		print "<th scope='col'>";
		print ( count($datos)>1) ? "<a href='/countries.php?region=".$datos[$i]['CountryCode']."'>".$datos[$i]['Country']."</a>" : $datos[$i]['Country'];//si hay mas de un pais, la cabecera debe ser clickable
		print "</th>";
		      
    } 
	print '</tr></thead>';
		$cuantosPaises = count($datos) +1 ;
	//print_r($datos);
	$count=0;
	$last_serv =''; //inicializacion recordador de servicio
	$last_code =''; //inicializacion recordador de servicio
	//print "<BR>Aqui continua</BR>";
	foreach($definiciones as $servicio) {
		
	//mirar si toca encabezado
		if ($last_code != substr($servicio['Code'],0,-1)) { 
		$last_serv = $servicio[$campo_seccion];
		$last_code = substr($servicio['Code'],0,-1);
		?>
		<tr>
        	<td colspan='<? echo $cuantosPaises ;?>' bgcolor="#20928F" style="color: white;"><strong><? echo $last_serv; ?></strong></td>
            
        </tr>
		<? }
		
		print '<tr '.($count % 2 ? 'class="odd"' : ''). '>
		';
		print "<td>".htmlspecialchars ($servicio[$mylang])."</td>";
		$item = $servicio['Code'];
		//hacemos bucle para cada uno de los paises
	for ($i = 0; $i < count($datos); ++$i) {
		//print $datos[$i][$item];
        /*    if(array_key_exists($item,$datos[$i]) && $datos[$i][$item]=='1') 
		{print "YES".$datos[$i][$item];
		}else{print "NO";
		} */
		print "<td>".($datos[$i][$item] == 'Yes' ? '<img src="/images/Yes.svg.png" alt="Yes">' : '<img src="/images/No.icon.png" alt="No">')."</td>";
         
    }
		print ' </tr>
		';
		$count++;
	 }
		
	

 }
/*foreach($datos as $lines) {
		print_r($lines);
				if(array_key_exists($item,$lines)) print "YES";
			
			 }
    */
    function convertCSVtoAssocMArray($file, $delimiter,$filter ='') { 
        $result = Array(); 
        $size = filesize($file) +1; 
        $file = fopen($file, 'r'); 
        $keys = fgetcsv($file, $size, $delimiter); 
        while ($row = fgetcsv($file, $size, $delimiter)) 
        { 
            for($i = 0; $i < count($row); $i++) 
            { 
                   # substituim comes per punts
                    $row[$i] = str_replace(',','.',$row[$i]);
					if (isset($row) && !empty($row[$i])) $row2[$keys[$i]] = $row[$i];
            }
            if ($filter == '') {
                    $result[] = $row2;
                    } else {
					//print_r($row2);
                      if ($row2['Country'] == "Description")  $result[] = $row2;
                      if ($row2['CountryCode'] == $filter)  $result[] = $row2;
                      if ($row2['Continent'] == $filter)  $result[] = $row2;
                    }

        } 
        fclose($file); 
        return $result; 
    } 
  
 
?>      
    		</tbody>
		</table>
	</body>
</html>