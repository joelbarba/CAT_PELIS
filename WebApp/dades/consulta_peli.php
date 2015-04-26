<?php // Retorna un XML amb la llista de pelis

	include("class.respostaXML.php");
	$resultat = "";
	$desc_error = "";


	$dbconn = pg_connect("host=localhost dbname=Cat_Pelis user=barba password=barba0001")
	or die('No s\'ha pogut connectar : ' . pg_last_error());

	$consulta = "
	select trim(to_char(id_peli, '000D')) as id_peli, titol,
		   coalesce(titol_original, '')		as titol_original,
		   coalesce(nom_imatge, '')			as nom_imatge,
		   coalesce(idioma_audio, '')		as idioma_audio,
		   coalesce(idioma_subtitols, '')	as idioma_subtitols,
		   coalesce(qualitat_video, '')		as qualitat_video,
		   coalesce(qualitat_audio, '')		as qualitat_audio,
		   coalesce(trim(to_char(any_estrena, '9999D')), '')
											as any_estrena,
		   coalesce(director, '')			as director,
		   coalesce(url_imdb, '')			as url_imdb,
		   coalesce(url_filmaffinity, '')	as url_film
	  from pelis_down
	 where id_peli = $1;";

	// pg_prepare($dbconn, "sent1", $sentencia);
	$result = pg_query_params($dbconn, $consulta, array($_REQUEST['id_peli']) );


	$titol = pg_fetch_result($result, 0, 'titol');


	if ($result == false) {
		$resultat = "ko";
		$desc_error = pg_last_error($dbconn);
	} else {
		$resultat = "ok";
	}


	// Generació del XML :

	$xml = new xmlResponse();
	$xml->ini_xml($resultat);
	$xml->registre(array(
			"desc_error" 	=> $desc_error
			));


	$xml->obrir_tag('info_peli');

	$xml->registre(array(
			"id_peli" 			=> pg_fetch_result($result, 0, "id_peli"),
			"titol" 			=> pg_fetch_result($result, 0, "titol"),
			"titol_original" 	=> pg_fetch_result($result, 0, "titol_original"),
			"nom_imatge" 		=> pg_fetch_result($result, 0, "nom_imatge"),
			"idioma_audio" 		=> pg_fetch_result($result, 0, "idioma_audio"),
			"idioma_subtitols" 	=> pg_fetch_result($result, 0, "idioma_subtitols"),
			"qualitat_video" 	=> pg_fetch_result($result, 0, "qualitat_video"),
			"qualitat_audio" 	=> pg_fetch_result($result, 0, "qualitat_audio"),
			"any_estrena" 		=> pg_fetch_result($result, 0, "any_estrena"),
			"director" 			=> pg_fetch_result($result, 0, "director"),
			"url_imdb" 			=> pg_fetch_result($result, 0, "url_imdb"),
			"url_film" 			=> pg_fetch_result($result, 0, "url_film")
			));

	$xml->tancar_tag('info_peli');

	$xml->fi_xml();

	
	pg_close ($dbconn);


/*	Format del XML a retornar :

	<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
    <resposta>
		<resultat> 		ok/ko 	</resultat>
		<desc_error>	desc 	</desc_error>
		<info_peli>
			<id_peli>
			<titol
			<titol_original>
			<nom_imatge>
			<idioma_audio>
			<idioma_subtitols>
			<qualitat_video>
			<qualitat_audio>
			<any_estrena>
			<director>
			<url_imdb>
			<url_film>
		</info_peli>
    </resposta>
*/


?>