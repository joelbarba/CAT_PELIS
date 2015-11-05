<?php // Modificar la pelicula

	include("class.respostaXML.php");
	$resultat = "";
	$desc_error = "";

	if ($_REQUEST['any_estrena'] == '') {	$any_est = null;
	} else {								$any_est = $_REQUEST['any_estrena']; }



	$dbconn = pg_connect("host=localhost dbname=Cat_Pelis user=barba password=barba0001")
	or die('No s\'ha pogut connectar : ' . pg_last_error());

	// $sentencia = 'update pelis_down set url_imdb = \''. $_REQUEST['url_imdb'] .'\' where id_peli = ' .$_REQUEST['id_peli']. ';';
	$sentencia = "update pelis_down set
		titol 				= $1,
		titol_original		= $2,
		idioma_audio		= $3,
		idioma_subtitols	= $4,
		qualitat_video      = $5,
		qualitat_audio      = $6,
		any_estrena         = $7,
		director            = $8,
		url_imdb 			= $9,
		url_filmaffinity 	= $10,
		nom_imatge          = $11
	  where id_peli = $12;";


	pg_prepare($dbconn, "sent1", $sentencia);
	$result = pg_execute($dbconn, "sent1", array(
											$_REQUEST['titol'],
											$_REQUEST['titol_original'],
											$_REQUEST['idioma_audio'],
											$_REQUEST['idioma_subtitols'],
											$_REQUEST['qualitat_video'],
											$_REQUEST['qualitat_audio'],
											$any_est,
											$_REQUEST['director'],
											$_REQUEST['url_imdb'],
											$_REQUEST['url_filmaffinity'],
											$_REQUEST['nom_imatge'],
											$_REQUEST['id_peli']
											));

	if ($result == false) {
		$resultat = "ko";
		$desc_error = pg_last_error($dbconn);
	} else {
		$resultat = "ok";
	}

	pg_close ($dbconn);


	$xml = new xmlResponse();
	$xml->ini_xml($resultat);
	$xml->registre(array(
			"desc_error" 	=> $desc_error,
			"id_peli_mod" 	=> $_REQUEST['id_peli'],
			"titol_mod" 	=> $_REQUEST['titol'],
			"url_peli_mod" 	=> $_REQUEST['url_imdb']
			));
	$xml->fi_xml();

/*	Format del XML a retornar :

	<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
    <resposta>
		<resultat> 		ok/ko 	</resultat>
		<desc_error>	desc 	</desc_error>
		<id_peli_mod>	9999 	</id_peli_mod>
		<titol_mod>		xxxx 	</titol_mod>
		<url_peli_mod>	xxxx 	</url_peli_mod>
    </resposta>
*/


?>
