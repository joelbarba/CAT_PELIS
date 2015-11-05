<?php // Retorna un XML amb la llista de pelis

	include("class.respostaXML.php");
	$resultat = "";
	$desc_error = "";


	$dbconn = pg_connect("host=localhost dbname=Cat_Pelis user=barba password=barba0001")
	or die('No s\'ha pogut connectar : ' . pg_last_error());

	$consulta = "
		select id_peli,
			   titol 							as titol_peli,
			   coalesce(url_imdb, '')			as url_imdb,
			   coalesce(url_filmaffinity, '')	as url_filmaffinity
		  from Pelis_Down
		 where id_peli > 0
		 order by id_peli desc";
		 
	$result = pg_query($dbconn, $consulta);

	if ($result == false) {
		$resultat = "ko";
		$desc_error = pg_last_error($dbconn);
		$num_pelis = 0;
	} else {
		$resultat = "ok";
		$num_pelis = pg_numrows($result);
		// if ($num_pelis > 100) { $num_pelis = 100; }
	}
	

	pg_close ($dbconn);

	// Generació del XML :
	
	$xml = new xmlResponse();
	$xml->ini_xml($resultat);
	$xml->registre(array(
			"desc_error" 	=> $desc_error,
			"num_pelis"		=> $num_pelis
			));
	
	$xml->obrir_tag("llista_pelis");

	for ($t = 0; $t < $num_pelis; $t++) { 
	
		$xml->obrir_tag('peli');
		
		$xml->registre(array(
				"id_peli" 		=> pg_fetch_result($result, $t, "id_peli"),
				"titol_peli"	=> pg_fetch_result($result, $t, "titol_peli"),
				"url_imdb"		=> pg_fetch_result($result, $t, "url_imdb"),
				"url_film"		=> pg_fetch_result($result, $t, "url_filmaffinity")
				));	

		$xml->tancar_tag('peli');
	} 


	$xml->tancar_tag("llista_pelis");
	$xml->fi_xml();	

	
	
/*	Format del XML a retornar :

	<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
    <resposta>
		<resultat> 		ok/ko 	</resultat>
		<desc_error>	desc 	</desc_error>
		<num_pelis>		999		</num_pelis>
		<llista_pelis>
			<peli>
				<id_peli>		</id_peli>
				<titol_peli>	</titol_peli>
				<url_imdb>      </url_imdb>
				<url_film>      </url_film>
			</peli>
			<peli></peli>
			...
		</llista_pelis>
    </resposta>

*/	
	

?>