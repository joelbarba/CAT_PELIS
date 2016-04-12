<?php // Modificar la pelicula

	$resultat = "";
	$desc_error = "";
    $data_input = file_get_contents("php://input");
    $postdata = json_decode($data_input);

    $any_est = $postdata->info_peli->any_estrena;
	if ($any_est == '') { $any_est = null; }


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
											$postdata->info_peli->titol,
											$postdata->info_peli->titol_original,
											$postdata->info_peli->idioma_audio,
											$postdata->info_peli->idioma_subtitols,
											$postdata->info_peli->qualitat_video,
											$postdata->info_peli->qualitat_audio,
											$any_est,
											$postdata->info_peli->director,
											$postdata->info_peli->url_imdb,
											$postdata->info_peli->url_filmaffinity,
											$postdata->info_peli->nom_imatge,
											$postdata->info_peli->id_peli,
											));

	if ($result == false) {
		$resultat = "ko";
		$desc_error = pg_last_error($dbconn);
	} else {
		$resultat = "ok";
	}

	pg_close ($dbconn);

// ---------------------------------------------------------------------


	echo '{ "result_code"  : "' . $resultat   . '",
            "desc_error"   : "' . $desc_error . '",';

    echo '  "input_params" : ' . $data_input . ', ';

    echo '  "output_data"  : {
                "id_peli"    : "'. $postdata->info_peli->id_peli    .'"
            }';

	echo '}';



	/*	Format del JSON a retornar :
        { "result_code"  : "ok/ko",
          "desc_error"   : "description of the error (in case of)",
          "input_params" : { 
                  "info_peli"          :  { obj peli }
          },
          "output_data"  : {
			      "id_peli"            :   "" (el mateix id que al input)
          }        
        }
	*/

?>