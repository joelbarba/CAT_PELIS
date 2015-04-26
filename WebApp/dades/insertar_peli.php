<?php // Modificar la pelicula

	include("class.respostaXML.php");
	$resultat = "";
	$desc_error = "";
	
	$titol 				= $_REQUEST['titol']; 
	$titol_original		= $_REQUEST['titol_original']; 
	$idioma_audio       = $_REQUEST['idioma_audio']; 
	$idioma_subtitols   = $_REQUEST['idioma_subtitols'];
	$url_imdb           = $_REQUEST['url_imdb']; 
	$url_filmaffinity   = $_REQUEST['url_filmaffinity'];
	$qualitat_video     = $_REQUEST['qualitat_video']; 
	$qualitat_audio     = $_REQUEST['qualitat_audio']; 
	$any_estrena        = $_REQUEST['any_estrena']; 
	$director           = $_REQUEST['director']; 
	$nom_imatge         = $_REQUEST['nom_imatge']; 	
	
	if ($titol 				== '') $titol 				= null;
	if ($titol_original		== '') $titol_original		= null;
	if ($idioma_audio       == '') $idioma_audio        = null;
	if ($idioma_subtitols   == '') $idioma_subtitols    = null;
	if ($url_imdb           == '') $url_imdb            = null;
	if ($url_filmaffinity   == '') $url_filmaffinity    = null;
	if ($qualitat_video     == '') $qualitat_video      = null;
	if ($qualitat_audio     == '') $qualitat_audio      = null;
	if ($any_estrena        == '') $any_estrena         = null;
	if ($director           == '') $director            = null;
	if ($nom_imatge         == '') $nom_imatge          = null;
	

	$dbconn = pg_connect("host=localhost dbname=Cat_Pelis user=barba password=barba0001")
	or die('No s\'ha pogut connectar : ' . pg_last_error());

	// Calcular nou ID_PELI
	$consulta = "select coalesce((select max(id_peli) from pelis_down), 0) + 1 as nou_id_peli";
	$resultat = pg_query($dbconn, $consulta);
	$id_peli = pg_fetch_result($resultat, 0, 'nou_id_peli');
	
	
	
	
	// $sentencia = 'update pelis_down set url_imdb = \''. $_REQUEST['url_imdb'] .'\' where id_peli = ' .$_REQUEST['id_peli']. ';';
	$sentencia = "insert into pelis_down values (
		$1,	-- id_peli
		1, 	-- id_versio
		$2,	-- titol
		$3,	-- titol_original
		$4,	-- idioma_audio
		$5,	-- idioma_subtitols
		$6,	-- url_imdb
		$7,	-- url_filmaffinity
		$8,	-- qualitat_video
		$9,	-- qualitat_audio
		$10,-- any_estrena
		$11,-- director
		$12 -- nom_imatge
		);";

	  
	pg_prepare($dbconn, "sent1", $sentencia);
	$result = pg_execute($dbconn, "sent1", array(
											$id_peli,			// 1
											$titol,				// 2
											$titol_original,    // 3
											$idioma_audio,      // 4
											$idioma_subtitols,  // 5
											$url_imdb,          // 6 
											$url_filmaffinity,  // 7 
											$qualitat_video,    // 8
											$qualitat_audio,    // 9
											$any_estrena,       // 10
											$director,          // 11
											$nom_imatge         // 12
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
			"id_peli" 		=> $id_peli
			));
	$xml->fi_xml();		

/*	Format del XML a retornar :

	<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
    <resposta>
		<resultat> 		ok/ko 	</resultat>
		<desc_error>	desc 	</desc_error>
		<id_peli>
    </resposta>
*/

?>