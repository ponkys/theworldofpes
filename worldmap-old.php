<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <title>The World of Public Employment Services Survey</title>
    <link href="/css/animate.min.css" rel="stylesheet"> 
    <link href="/css/font-awesome.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="images/favicon.ico">
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <link href="./maps/jqvmap.css" media="screen" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="./maps/jquery.vmap.js"></script>
    <script type="text/javascript" src="./maps/jquery.vmap.world.js" charset="utf-8"></script>
    <style>
      html, body {
        font-family: 'Open Sans', sans-serif;
        font-size: 14px;
        line-height: 0.6rem;
        color: #666;
        background-color: #fff;
        padding: 0;
        margin: 0;
        width: 100%;
        height: 100%;
      }

      h1 {
        color: #46ddda;
        text-transform: uppercase;
      }
      
      #header {
        text-align: center;
        -webkit-box-shadow: 0px 4px 17px -6px rgba(0,0,0,0.73);
        -moz-box-shadow: 0px 4px 17px -6px rgba(0,0,0,0.73);
        box-shadow: 0px 4px 17px -6px rgba(0,0,0,0.73); 
      }

      #vmap {
        width: 100%;
        height: 100%;
        max-height: 650px;
        background-color: red;
      }

      .left-control {  
        height: 100%;
        width: 100%;
        line-height: 1.5rem;
        border-radius: 50%;
        font-size: 0.7rem;
        color: #20928F;
        text-align: center;
        -webkit-transition: all 0.5s ease;
        -moz-transition: all 0.5s ease;
        -ms-transition: all 0.5s ease;
        -o-transition: all 0.5s ease;
        transition: all 0.5s ease;
      }

      .left-control:hover {
        color: turquoise;
      }

      /* Estilos para el modal, feos pero para guiarte. */
    	.layer {
    		background-color: rgba(0, 0, 0, .8);
    		display: none;
    		height: 100%;
    		left: 0;
    		position: absolute;
    		text-align: center;
    		top: 0;
    		width: 100%;
    	}
    	.layer .modal {
    		background-color: rgba(250, 250, 250, 1);
    		margin: 20px auto;
    		position: relative;
    		width: 50%;
        height: 100%;
        
    	}
    	.layer .modal .modal-header {
    		padding: 1em;
        text-transform: uppercase;
        background-color: #20928F;
        font-weight: 700;
        color: #fff;
    	}
    	.layer .modal .modal-header .close-modal {
    		position: absolute;
    		right: 0;
    		top: 0;
        font-size: 1.2em;
        color: black;
        padding-right: 1rem;
        padding-top: 0.5rem;
    	}
    	.layer .modal .modal-body {
    		height: 800px;
    		overflow: scroll;
    		padding: 15px;
    		text-align: left;
    	}
    	.layer .modal .modal-footer {
    		background-color: #eee;
    	}
    </style>
    <script type="text/javascript" src="./maps/jquery.datos_paises.js"></script>
    <script type="text/javascript">
      var paises = 'BJ,BF,CM,CF,TD,KM,CD,CG,GA,GN,CI,NE,TG,AR,BS,BB,BO,BR,CA,CL,CO,CR,DO,EC,SV,GT,GY,HN,JM,MX,PA,PY,PE,SR,TT,UY,VE,AL,AM,AT,BE,HR,CZ,DK,FI,FR,DE,HU,LT,MK,MD,NL,RU,RS,SI,SE,CH,TR,UA,GB,AU,KH,CN,JP,NZ,PH,TH,VN,DZ,MR,MA';     

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
            scaleColors: ['#C8EEFF', '#006400'],
            normalizeFunction: 'polynomial',
  			//showLabels: true,
            onRegionClick: function (element, code, region) {
  			 if (paises.toLowerCase().indexOf(code) <= -1) {
  					event.preventDefault();
  				   alert('There is no information available for ' + region);
  				} else {
  						if (!touch_detect()) {
  						  // we're not on a mobile device, handle the click
  						  var message = 'You clicked "' + region + '" which has the code: ' + code.toUpperCase();
  						  //console.log(message);
  						 
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

  						  // window.open("./countries.php?region=" + code.toUpperCase());
  						  }
                     }
            },
  		  onLabelShow: function (event, label, code) {
  			if (paises.toLowerCase().indexOf(code) <= -1) {
  					//event.preventDefault();
  				   label.append(' is not included in the Survey. <BR>There is no information available.');
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
    
    <div id="header" class="navbar-header">
      <a class="navbar-brand" href="index_en.php">
        <h1 ><img class="img-responsive" src="images/marca_retocada_enana.png" alt="logo"></h1>
      </a>
    	<h1 class="animated fadeInLeftBig">Number of services provided by Public Employment Services</h1>
    	<h2 class="animated fadeInUpBig">Based on the Survey conducted by WAPES, IDB and OECD</h2>
    	<p class="animated wow fadeInUp" data-wow-duration="30000ms" data-wow-delay="10000ms">Click on any country to review the services provided by their PES</p>
      <a class="left-control" href="index_en.php"><i class="fa fa-angle-left">  Go to main site</i></a>
    </div>

    <div id="vmap"></div>

    <!-- Este es el modal aquí puedes ordenar todo como necesitas. -->
		<div class="layer">
			<div class="modal">
				<!-- Aquí dejas titulos y el botón para cerrar el modal. -->
				<div class="modal-header">
					<p>Information of the services provided by the PES</p>
					<a href="#close" class="close-modal"><i class="fa fa-close"></i></a>
				</div>
				<!-- Aquí se imprime el contenido que da como resultado el ajax. -->
				<div class="modal-body">
					<p>Body</p>
				</div>
				<!-- Aquí puedes poner botones o acciones, es opcional. -->
				<div class="modal-footer">
					<p>theworldofpes.org</p>
				</div>
			</div>
		</div>
  </body>
</html>