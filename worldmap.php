<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />

  <head>
  
  
  <?php 
$definiciones = convertCSVtoAssocMArray("PEScategories.csv", ",");
// Determinamos idioma
$mylang="en"; //default
$campo_seccion ='Section';
$NoInfo="There is no information available for ";
$NotCovered="is not included in the Survey. <BR>There is no information available.";
$Title = "The World of Public Employment Services";
$Help = "Click on any country to review the services provided by their PES.";
$LblServices=" services offered";
$TitleModel = "Services offered by the PES";
$LblMapMe="Click here to <a class='btn animated fadeInUpBig' href='#' onClick='CambiaData(countries_data)'>map all the services</a> or filter by Sections";
if (strpos($_SERVER['HTTP_HOST'], 'mundospe.org')) {
	 $mylang="es";
	$campo_seccion ='Section_es';
	$NoInfo="No hay informacion disponible sobre ";
	$NotCovered=" no participo en la Encuesta.<BR>No hay datos disponibles.";
	$Title = "El mundo de los Servicios Publicos de Empleo";
	$Help = "Seleccion cualquier pais para ver los servicios que ofrece su SPE.";
	$LblServices=" servicios cubiertos";
	$LblMapMe="Haga click aqui para ver <a class='btn animated fadeInUpBig' href='#' onClick='CambiaData(countries_data)'>el mapa de todos los servicios</a> o filtre por secciones";
	$TitleModel = "Servicios ofrecidos por el SPE";
}elseif (strpos($_SERVER['HTTP_HOST'], 'mondedesspe.org')) { 
	$campo_seccion ='Section_fr';
	 $mylang="fr";
	$NoInfo="Il n'y a pas d'information disponible pour ";
	$NotCovered=" ne figure pas dans l'enquête"; 
	$Title = "Le Monde des Services Publics d'Emploi";
		$Help = "Cliquez sur un pays pour examiner les services offerts par leurs SPE.";
	$LblServices=" services offerts";
	$LblMapMe="Cliquez ici pour <a class='btn animated fadeInUpBig' href='#' onClick='CambiaData(countries_data)'>la carte de tous les services</a> ou filtre en sections";
	$TitleModel = "Services offerts par le SPE";
}
 ?>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <title><? echo $Title ; ?> - Survey</title>
    <link href="./css/animate.min.css" rel="stylesheet"> 
    <link href="./css/font-awesome.min.css" rel="stylesheet">
    <link href="./css/map.css" rel="stylesheet">
    <link rel="shortcut icon" href="images/favicon.ico">
    <link href="./css/animate.min.css" rel="stylesheet"> 
    <link href="./css/font-awesome.min.css" rel="stylesheet">
    <link href="./css/lightbox.css" rel="stylesheet">
    <link id="./css-preset" href="css/presets/preset1.css" rel="stylesheet">
    <link href="./css/responsive.css" rel="stylesheet">
    <link href="./maps/jqvmap.css" media="screen" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="./maps/jquery.vmap.js"></script>
    <script type="text/javascript" src="./maps/jquery.vmap.world.js" charset="utf-8"></script>
    <script type="text/javascript" src="./maps/jquery.datos_paises.js"></script>
    <script type="text/javascript">
      var paises = 'BJ,BF,CM,CF,TD,KM,CD,CG,GA,GN,CI,NE,TG,AR,BS,BB,BO,BR,CA,CL,CO,CR,DO,EC,SV,GT,GY,HN,JM,MX,PA,PY,PE,SR,TT,UY,VE,AL,AM,AT,BE,HR,CZ,DK,FI,FR,DE,HU,LT,MK,MD,NL,RU,RS,SI,SE,CH,TR,UA,GB,AU,KH,CN,JP,NZ,PH,TH,VN,DZ,MR,MA';     
      //pruebas cambio dataset
	  var FuenteDatos = countries_data; //almaceno el array para colorear en una variable global
	  function CambiaData(id) {
          jQuery('#vmap').vectorMap('set', 'values', id); // forzar cambio fuente de datos del mapa
		  FuenteDatos = id; //recuperar fuente de datos desde la variable global.
		    }
		  //final de  pruebas
		    function touch_detect() {
			return 'ontouchstart' in window || 'onmsgesturechange' in window || navigator.msMaxTouchPoints > 0;
        }
        jQuery(document).ready(function () {
        	/* Evento para cerrar el modal. */
        	$('.layer, .close-modal').on('click', function(event) {
        		event.preventDefault();
        		$(this).closest('.layer').fadeOut();
        	})
        	/* Evento para evitar que el modal se cierra si dan click sobre modal-body. */
        	$('.modal').on('click', function(event) {
        		event.stopPropagation();
        	})
          jQuery('#vmap').vectorMap({
            map: 'world_en',
            enableZoom: false,
            showTooltip: true,
            values: countries_data,
            backgroundColor: '#D8D8D8',
            scaleColors: ['#01DFD7', '#088A85'],
            normalizeFunction: 'polynomial',
  			//showLabels: true,
            onRegionClick: function (element, code, region) {
              if (paises.toLowerCase().indexOf(code) <= -1) {
                event.preventDefault();
                alert("<? echo $NoInfo; ?>" + region);
  				        } else {
                      if (!touch_detect()) {
  						        // we're not on a mobile device, handle the click
  						        var message = 'You clicked "' + region + '" which has the code: ' + code.toUpperCase();
  						        //console.log(message);
  						          $('.layer').fadeIn()
  						          /* El ajax va a hacer la solicitud hasta el archivo php y todo lo que retorna
  								      se queda guardado en la variable data */
                        $.ajax({
  								      url: "./countries.php?region=" + code.toUpperCase(),
  								      }).done(function(data) {
                        /* Aca imprimo la información que retornó el .php en modal-body. */
  								      $('.modal .modal-body p').html(data)
  								      /* Aquí hago que el modal sea ahora visible. */ 
  								      $('.layer').fadeIn()
  								    });
  						      }
                  }
                },
  		  onLabelShow: function (event, label, code) {
  			if (paises.toLowerCase().indexOf(code) <= -1) {
  					//event.preventDefault();
  				   label.append("<? echo $NotCovered ;?>");
  				} else {
					label.append(': '+ FuenteDatos[code] + ' <? echo $LblServices;?>'); 
				}
  			},
            onRegionOver: function (element, code, region) {
	   // if it's not in the approved list, do nothing, 
		  // else allow normal behavior
		  if (paises.toLowerCase().indexOf(code) <= -1) {
			  event.preventDefault();
		  } else {
				  if (touch_detect()) {
					/// we're not on a mobile device, handle the click
					var message = 'You clicked "' + region + '" which has the code: ' + code.toUpperCase();
					///alert(message);
				   window.open("./countries.php?region=" + code.toUpperCase());
				  }
				}
            }
          });
        });
    </script>
  </head>

  <body>
<?php include_once("./analyticstracking.php");
 ?>
    <header id="header">
     
      <div class="header_info">
        <h1 class="main_title animated fadeInLeftBig"><a class="img_to_home" href="/">
<img src="images/marca_retocada_enana.png" alt="Home Page"" alt="Home" style="float:left;width:43px;height:44px;padding-left:12px;"></a><? echo $Title ;?></h1>
        <h3 class="h3_title animated wow fadeInUp" data-wow-duration="1000ms" data-wow-delay="100ms"><? echo $Help ;?></h3>                    
      </div>
      
    </header>
    <div id="vmap"></div>
    <div id="footer_map">
    	 <h3>Source: WAPES-IDB 2014 Survey</h3>
   	</div>	

	 <header id="header">
<div class="header_links wow FadeInUp"> 
        <p class="h3_title" style="font-size:1.5rem;" ><? echo $LblMapMe;?>:</p> 

<?
$last_serv =''; //inicializacion recordador de servicio
foreach($definiciones as $servicio) {
			//pasamos por todas las lineas. Cuando cambie la categoria de servicio, anyadimos boton
			
		if ($servicio['Section'] != $last_serv) { 
			$last_serv = $servicio['Section'];
			print "<a class='btn-section' style='padding:5px;line-height: 200%;' id='tohash' href='#' onClick='CambiaData(countries_".substr($servicio['Code'],0,-1).")'> $servicio[$campo_seccion]</a>";
		}
	}

?>
	  </div>	
</header>
	  <!-- Modal con datos -->
		<div class="layer">
			<div class="modal">
				<!-- Aquí dejas titulos y el botón para cerrar el modal. -->
				<div class="modal-header">
					<p><? echo $TitleModel ; ?></p>
					<a href="#close" class="close-modal"><i class="fa fa-close"></i></a>
				</div>
				<!-- Aquí se imprime el contenido que da como resultado el ajax. -->
				<div class="modal-body">
					<p>Body</p>
				</div>
				<!-- Aquí puedes poner botones o acciones, es opcional. -->
				<div class="modal-footer">
					<p>Source: WAPES-IDB 2014 Survey</p>
				</div>
			</div>
		</div>
  
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
    <script type="text/javascript" src="js/jquery.inview.min.js"></script>
    <script type="text/javascript" src="js/wow.min.js"></script>
    <script type="text/javascript" src="js/mousescroll.js"></script>
    <script type="text/javascript" src="js/smoothscroll.js"></script>
    <script type="text/javascript" src="js/jquery.countTo.js"></script>
    <script type="text/javascript" src="js/lightbox.min.js"></script>
    <script type="text/javascript" src="js/main.js"></script>
  
  </body>
</html>
<? 
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