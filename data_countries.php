<?
//this file re-creates the .js file with the data of how many services provide every country
// the .js file is used to colour the map


	$datos = convertCSVtoAssocMArray("PESsurvey2015.csv", ","); 
	$output_file ='./maps/jquery.datos_paises.js';
	
foreach($datos as $lineas) {
//print_r($lineas);
	$pais =$lineas['CountryCode'];
	$num_serv =0 ; // contador de servicios por pais
	//$serv_parciales =Array() ; // contador de servicios por bloques
	$last_block= '';
		foreach($lineas as $nombre_servicio => $SiNo) {
			$bloque = substr($nombre_servicio,0,-1); //los nombre de servicio son tipo Duties1, Duties2, Duties3.... EB1, EB2...
			if ($bloque != $last_block) {
				//crear arrya a 0 para evitar errores
				$last_block =$bloque ;
				$serv_parciales[$bloque][$pais]=0;
			}
			if ($SiNo == 'Yes') $num_serv++ ;
			if ($SiNo == 'Yes') $serv_parciales[$bloque][$pais]++ ;//= $serv_parciales[$bloque][$pais]+1 ;
			if ($bloque == 'Countr' || $bloque == 'CountryCod' || $bloque == 'Continen') unset($serv_parciales[$bloque]);
		}
		// guardo los resultados por pais	
	  if (strlen($pais) >0) $recuento_paises .= "'$pais' : '$num_serv',";
	 /* foreach($serv_parciales as $nombre_servicio => $subtotal) {
			if (strlen($pais) >0) ${"recuento_" . $nombre_servicio} .= "'$pais' : '$subtotal',";
			
		}*/
    }
print $recuento_paises;
//print_r($serv_parciales);

$fp = fopen("$output_file",'w+');
fwrite ($fp,'var countries_data = {'.strtolower($recuento_paises).'};'. PHP_EOL);
foreach($serv_parciales as $nombre_serv =>$servicio) {
			foreach($servicio as $pais => $subtotal) {
				if (strlen($pais) >0) ${"recuento_" . $nombre_serv} .= "'$pais' : '$subtotal',";
				 
			}
		fwrite ($fp,'var countries_'.$nombre_serv.' = {'.strtolower(${"recuento_" . $nombre_serv}).'};'. PHP_EOL);
		print $nombre_serv. " tiene " .${"recuento_" . $nombre_serv} . "<BR>";
		//print_r($servicio);
		}
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