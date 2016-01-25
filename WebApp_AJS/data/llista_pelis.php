<?php // Retorna un JSON amb la llista de pelis

	// include("class.respostaXML.php");
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

	echo '{ "result_code"  : "ok",
            "desc_error"   : "eoeoeo",
            "input_params" : { },
            "output_data"  : {
                "llista_pelis" : [';

	for ($t = 0; $t < $num_pelis; $t++) {
		if ($t > 0) echo ',';
		echo '{
			"id"	   :  '.pg_fetch_result($result, $t, "id_peli").',
			"titol"	   : "'.pg_fetch_result($result, $t, "titol_peli").'",
			"url_imdb" : "'.pg_fetch_result($result, $t, "url_imdb").'",
			"url_film" : "'.pg_fetch_result($result, $t, "url_filmaffinity").'"
		}';
	}
	echo ']}}';

	/*	Format del JSON a retornar :
    
        { "result_code"  : "ok/ko",
          "desc_error"   : "description of the error (in case of)",
          "input_params" : { 
                  "id_peli" : "0"
          },
          "output_data"  : {
            "llista_pelis" :
                [{
                    "id"	   : 1,
                    "titol"	   : "primera peli",
                    "url_imdb" : "www.google.com",
                    "url_film" : "www.google.com"
                },{
                    "id"	   : 2,
                    "titol"	   : "segona peli",
                    "url_imdb" : "www.google.com",
                    "url_film" : "www.google.com"
                },{ ...
                }]
          }        
        }

	*/


	/*	Format del JSON a retornar :



	*/


?>