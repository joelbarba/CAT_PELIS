<?php // Retorna un JSON amb la info de la peli

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

	pg_close ($dbconn);

// ---------------------------------------------------------------------

	echo '{ "result_code"  : "' . $resultat   . '",
            "desc_error"   : "' . $desc_error . '",
            "input_params" : { 
                "id_peli" : "' . $_REQUEST['id_peli'] . '"
            }, 
            ';

    echo '  "output_data"  : {
                "id_peli" 			: "'. pg_fetch_result($result, 0, "id_peli")           .'",
			    "titol" 			: "'. pg_fetch_result($result, 0, "titol")             .'",
			    "titol_original" 	: "'. pg_fetch_result($result, 0, "titol_original")    .'",
			    "nom_imatge" 		: "'. pg_fetch_result($result, 0, "nom_imatge")        .'",
			    "idioma_audio" 		: "'. pg_fetch_result($result, 0, "idioma_audio")      .'",
			    "idioma_subtitols" 	: "'. pg_fetch_result($result, 0, "idioma_subtitols")  .'",
			    "qualitat_video" 	: "'. pg_fetch_result($result, 0, "qualitat_video")    .'",
			    "qualitat_audio" 	: "'. pg_fetch_result($result, 0, "qualitat_audio")    .'",
			    "any_estrena" 		: "'. pg_fetch_result($result, 0, "any_estrena")       .'",
			    "director" 			: "'. pg_fetch_result($result, 0, "director")          .'",
			    "url_imdb" 			: "'. pg_fetch_result($result, 0, "url_imdb")          .'",
			    "url_film" 			: "'. pg_fetch_result($result, 0, "url_film")          .'"
            }';

	echo '}';



	/*	Format del JSON a retornar :
        { "result_code"  : "ok/ko",
          "desc_error"   : "description of the error (in case of)",
          "input_params" : { 
                  "id_peli" : "0"
          },
          "output_data"  : {
			      "id_peli"            :   "",
			      "titol"              :   "",
			      "titol_original"     :   "",
			      "nom_imatge"         :   "",
			      "idioma_audio"       :   "",
			      "idioma_subtitols"   :   "",
			      "qualitat_video"     :   "",
			      "qualitat_audio"     :   "",
			      "any_estrena"        :   "",
			      "director"           :   "",
			      "url_imdb"           :   "",
			      "url_film"           :   ""
          }        
        }

	*/



?>