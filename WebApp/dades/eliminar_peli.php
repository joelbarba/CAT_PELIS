<?php // Eliminar la pelicula

	include("class.respostaXML.php");
	$resultat = "";
	$id_peli = $_REQUEST['id_peli'];
	$desc_error = "";
	$count_arxius = 0;
	$count_peli = 0;

	$dbconn = pg_connect("host=localhost dbname=Cat_Pelis user=barba password=barba0001")
	or die('No s\'ha pogut connectar : ' . pg_last_error());


	// Eliminar primer tots els arxius de la pelicula
	$sentencia1 = "delete from arxius_pelis where id_peli = $1";
	pg_prepare($dbconn, "sent1", $sentencia1);
	$result = pg_execute($dbconn, "sent1", array($id_peli));

	if ($result == false) {
		$desc_error = pg_last_error($dbconn);
		$resultat = "ko";
	} else {
		$resultat = "ok";
		$count_arxius = pg_affected_rows($result);


		// Eliminar la pelicula
		$sentencia2 = "delete from pelis_down where id_peli = $1";
		pg_prepare($dbconn, "sent2", $sentencia2);
		$result = pg_execute($dbconn, "sent2", array($id_peli));

		if ($result == false) {
			$desc_error = $desc_error . pg_last_error($dbconn);
			$resultat = "ko";
		} else {
			$count_peli = pg_affected_rows($result);
		}


		// Recuperar el ID de la següent peli (o anterior si no existeix)
		$sentencia3 = "
			select coalesce(
				(select max(id_peli) from pelis_down where id_peli <= $1),
				(select min(id_peli) from pelis_down where id_peli >= $1),
				1
			) as id_peli_seg";
		// pg_prepare($dbconn, "sent3", $sentencia3);
		// $result = pg_execute($dbconn, "sent3", $id_peli);
		$result2 = pg_query_params($dbconn, $sentencia3, array($id_peli));

		$id_peli_seg = pg_fetch_result($result2, 0, 'id_peli_seg');

	}


	pg_close ($dbconn);

	$xml = new xmlResponse();
	$xml->ini_xml($resultat);
	$xml->registre(array(
			"desc_error" 	=> $desc_error,
			"id_peli_del" 	=> $id_peli,
			"id_peli_seg" 	=> $id_peli_seg,
			"count_arxius" 	=> $count_arxius,
			"count_peli" 	=> $count_peli
			));
	$xml->fi_xml();

/*	Format del XML a retornar :

	<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
    <resposta>
		<resultat> 		ok/ko 	</resultat>
		<desc_error>	desc 	</desc_error>
		<id_peli_del>	9999	</id_peli_del>
		<id_peli_seg>	9999	</id_peli_seg>
		<count_arxius>	999		</count_arxius>
		<count_peli>	999		</count_peli>
    </resposta>
*/


?>
