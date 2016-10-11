<?php
 //busqueda por host

$myUrl = $_SERVER['HTTP_HOST'];
//print $myUrl;
if (strpos($_SERVER['HTTP_HOST'], 'mundospe.org')) {
 include("index_es.php");
}elseif (strpos($_SERVER['HTTP_HOST'], 'mondedesspe.org')) { 
 include("index_fr.php");
}else{
 include("index_en.php");

}

/*
$supportedLangs = array('en', 'fr', 'es');
 
$languages = explode(',',substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2));

foreach($languages as $lang)
{
    if(in_array($lang, $supportedLangs))
    {
        // Set the page locale to the first supported language found
        //$page->setLocale($lang);
	include("index_$lang.php");
        break;
    }
}
*/
?>