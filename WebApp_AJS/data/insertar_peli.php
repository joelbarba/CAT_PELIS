<?php // Insertar la pelicula

	$resultat = "";
	$desc_error = "";
    $data_input = file_get_contents("php://input");
    $postdata = json_decode($data_input);

	if ($postdata->info_peli->titol 	         == '') $postdata->info_peli->titol 			  = null;
	if ($postdata->info_peli->titol_original	 == '') $postdata->info_peli->titol_original	  = null;
	if ($postdata->info_peli->idioma_audio       == '') $postdata->info_peli->idioma_audio        = null;
	if ($postdata->info_peli->idioma_subtitols   == '') $postdata->info_peli->idioma_subtitols    = null;
	if ($postdata->info_peli->url_imdb           == '') $postdata->info_peli->url_imdb            = null;
	if ($postdata->info_peli->url_filmaffinity   == '') $postdata->info_peli->url_filmaffinity    = null;
	if ($postdata->info_peli->qualitat_video     == '') $postdata->info_peli->qualitat_video      = null;
	if ($postdata->info_peli->qualitat_audio     == '') $postdata->info_peli->qualitat_audio      = null;
	if ($postdata->info_peli->any_estrena        == '') $postdata->info_peli->any_estrena         = null;
	if ($postdata->info_peli->director           == '') $postdata->info_peli->director            = null;
	if ($postdata->info_peli->nom_imatge         == '') $postdata->info_peli->nom_imatge          = null;


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
											$id_peli,			                     // 1
											$postdata->info_peli->titol,			 // 2
											$postdata->info_peli->titol_original,    // 3
											$postdata->info_peli->idioma_audio,      // 4
											$postdata->info_peli->idioma_subtitols,  // 5
											$postdata->info_peli->url_imdb,          // 6
											$postdata->info_peli->url_filmaffinity,  // 7
											$postdata->info_peli->qualitat_video,    // 8
											$postdata->info_peli->qualitat_audio,    // 9
											$postdata->info_peli->any_estrena,       // 10
											$postdata->info_peli->director,          // 11
											$postdata->info_peli->nom_imatge         // 12
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
                "id_peli"    : "'. $id_peli    .'"
            }';

	echo '}';


	/*	Format del JSON a retornar :
        { "result_code"  : "ok/ko",
          "desc_error"   : "description of the error (in case of)",
          "input_params" : { 
                  "info_peli"          :  { obj peli }
          },
          "output_data"  : {
			      "id_peli"            :   "" (nou id calculat)
          }        
        }
	*/

?>