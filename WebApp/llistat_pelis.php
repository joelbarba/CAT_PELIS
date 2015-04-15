<?php // Modificar la pelicula

/*	Format del XML a retornar :

	<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
    <resposta>
		<resultat> 		ok/ko 	</resultat>
		<desc_error>	desc 	</desc_error>
		<num_pelis>		999		</num_pelis>
		<llista_pelis>
			<id_peli>		</id_peli>
			<titol_peli>	</titol_peli>
			<url_imdb>      </url_imdb>
			<url_film>      </url_film>
		</llista_pelis>
    </resposta>

*/

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
	
	
	
/*	
	
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
*/

	$resultat = "ok";


	pg_close ($dbconn);

	$xml = new xmlResponse();
	$xml->ini_xml($resultat);
	$xml->registre(array(
			"desc_error" 	=> $desc_error,
			"num_pelis"		=> 10
			));
	$xml->obrir_tag('llista_pelis');
	echo "eee";
	$xml->tancar_tag('llista_pelis');
			
	$xml->fi_xml();	

/*	Format del XML a retornar :

	<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
    <resposta>
		<resultat> 		ok/ko 	</resultat>
		<desc_error>	desc 	</desc_error>
		<num_pelis>		999		</num_pelis>
		<llista_pelis>
			<id_peli>		</id_peli>
			<titol_peli>	</titol_peli>
			<url_imdb>      </url_imdb>
			<url_film>      </url_film>
		</llista_pelis>
    </resposta>

*/	
	

?>