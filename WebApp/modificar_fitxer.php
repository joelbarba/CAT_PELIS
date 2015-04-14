<?php // Modificar la pelicula

	include("class.respostaXML.php");
	$resultat = "";
	$desc_error = "";
	
	if ($_REQUEST['any_estrena'] == '') {	$any_est = null;
	} else {								$any_est = $_REQUEST['any_estrena']; }
	


	$dbconn = pg_connect("host=localhost dbname=Cat_Pelis user=barba password=barba0001")
	or die('No s\'ha pogut connectar : ' . pg_last_error());

	// $sentencia = 'update pelis_down set url_imdb = \''. $_REQUEST['url_imdb'] .'\' where id_peli = ' .$_REQUEST['id_peli']. ';';
	$sentencia = "update arxius_pelis
				   set nom_arxiu = $1
				 where id_peli   = $2
				   and id_versio = 1
				   and num_arxiu = $3;";
	  
	pg_prepare($dbconn, "sent1", $sentencia);
	$result = pg_execute($dbconn, "sent1", array(
											$_REQUEST['nom_arxiu'],
											$_REQUEST['id_peli'],
											$_REQUEST['num_arxiu']
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
			"desc_error" 	=> $desc_error
			));
	$xml->fi_xml();	


?>