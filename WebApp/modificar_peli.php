<?php // Modificar la pelicula
	
	$dbconn = pg_connect("host=localhost dbname=CAT_PELIS user=barba password=barba0001")
	or die('No s\'ha pogut connectar : ' . pg_last_error());

	// $sentencia = 'update pelis_down set url_imdb = \''. $_REQUEST['url_imdb'] .'\' where id_peli = ' .$_REQUEST['id_peli']. ';';
	$sentencia = "update pelis_down set 
		titol 				= $1,
		url_imdb 			= $2,
		url_filmaffinity 	= $3,
		nom_imatge          = $4
	  where id_peli = $5;";
	

	pg_prepare($dbconn, "sent1", $sentencia);
	$result = pg_execute($dbconn, "sent1", array(
											$_REQUEST['titol'],
											$_REQUEST['url_imdb'],
											$_REQUEST['url_filmaffinity'],
											$_REQUEST['nom_imatge'],
											$_REQUEST['id_peli']
											));

	$num_files = pg_affected_rows($result);
	
	if ($num_files) echo "ok";
	else			echo "ko";
	
	echo $_REQUEST['nom_imatge'];
	
	// pg_execute($dbconn, $sentencia , array($_REQUEST['url_imdb'], $_REQUEST['id_peli']) );
	// pg_execute($dbconn, $sentencia);
	// pg_exec($sentencia);
	
	
	pg_close ($dbconn);

?>