<?php // Modificar la pelicula

	
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
		echo "ko"; // . chr(13); 
		echo pg_last_error($dbconn);
	} else {
		$num_files = pg_affected_rows($result);
		if ($num_files == 1) {	echo "ok"; } 
		else { 					echo "ko"; }
		echo $num_files . " registres actualitzats";
	}
	



	pg_close ($dbconn);

?>