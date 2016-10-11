<?
//this file re-creates the .js file with the data of how many services provide every country
// the .js file is used to colour the map


	$datos = convertCSVtoAssocMArray("PESsurvey2015.csv", ","); 
	$output_file ='./maps/jquery.datos_paises.js';
	
foreach($datos as $lineas) {
//print_r($lineas);
	$pais =$lineas['CountryCode'];
	$num_serv =0 ;
		foreach($lineas as $servicio) {
			if ($servicio == 'Yes') $num_serv++ ;
	 }
	  if (strlen($pais) >0)$recuento_paises .= "'$pais' : '$num_serv',";
	  
    }
print $recuento_paises;

$fp = fopen("$output_file",'w+');
fwrite ($fp,'var countries_data = {'.lcase($recuento_paises).'};');
fclose ($fp);

 function convertCSVtoAssocMArray($file, $delimiter,$filter ='') { 
        $result = Array(); 
        $size = filesize($file) +1; 
        $file = fopen($file, 'r'); 
        $keys = fgetcsv($file, $size, $delimiter); 
        while ($row = fgetcsv($file, $size, $delimiter)) 
        { 
            for($i = 0; $i < count($row); $i++) 
            { 
                   # substituïm comes per punts
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