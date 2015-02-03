<?php // Recuperar tots els arxius de la pelicula
		
	$dbconn = pg_connect("host=localhost dbname=CAT_PELIS user=barba password=barba0001")
	or die('No s\'ha pogut connectar : ' . pg_last_error());

	$consulta = "
	select id_peli, titol,
		   coalesce(titol_original, '?')	as titol_original,
		   coalesce(nom_imatge, '#')		as nom_imatge,
		   coalesce(idioma_audio, '?')		as idioma_audio,
		   coalesce(idioma_subtitols, '?')	as idioma_subtitols,
		   coalesce(qualitat_video, '?')	as qualitat_video,
		   coalesce(qualitat_audio, '?')	as qualitat_audio,
		   coalesce(to_char(any_estrena, '9999D'), '?')
											as any_estrena,
		   coalesce(director, '?')			as director,
		   coalesce(url_imdb, '#')			as url_imdb,
		   coalesce(url_filmaffinity, '#')	as url_film
	  from pelis_down
	 where id_peli = ". $_REQUEST['id_peli'] .";";
		 
	$result_2 = pg_query($dbconn, $consulta);	


	
	$consulta = "
		select num_arxiu, nom_arxiu, 
			   round(tamany / (1024 * 1024), 2) tam,
			   url
		  from arxius_pelis t1
		 where id_peli = ". $_REQUEST['id_peli'] ."
		 order by id_versio, id_peli;";
		 
	$result_1 = pg_query($dbconn, $consulta);	
	
?>



<?php echo "<h2> " . pg_fetch_result($result_2, 0, "titol") . "</h2>"; ?>

<div id="id_div_info_peli" style="
	width: 600px; height: 350px; padding:10px;
	overflow: hidden; font:normal normal normal 10px/10px Verdana;
	border: solid 1px #AAAAAA; background-color: #FFFFCC;
	display: flex; flex-flow: column-reverse wrap; 
	align-items: stretch; 
	align-content: space-between;
	">		
	
	<div style="
		display: flex; flex-flow: row wrap; 
		align-items: flex-start; 
		align-content: flex-start; 
		">
		
		<?php 
			echo '<img src="./Img/cataleg/'. pg_fetch_result($result_2, 0, 'nom_imatge') .'" 
					height="350" width="250" 
					id="id_img_caratula" onclick="canviar_caratula();"
					style="border: solid 1px #333333; " >';
		?>
		
		<div style="margin-left:10px; ">
			<?php
			
				if (pg_fetch_result($result_2, 0, "url_imdb") == "#")	$imatge1 = './Img/imdb_logo2.png';
				else													$imatge1 = './Img/imdb_logo.png';

				if (pg_fetch_result($result_2, 0, "url_film") == "#")	$imatge2 = './Img/filmaffinity_logo2.png';
				else													$imatge2 = './Img/filmaffinity_logo.png';

				
				echo '
				<p> ID : 				<input id="info_peli_1" value="'. pg_fetch_result($result_2, 0, 'id_peli') 			.'"/> </p>
				<p> Títol : 			<input id="info_peli_2" value="'. pg_fetch_result($result_2, 0, 'titol') 			.'"/> </p>
				<p> Títol original : 	<input id="info_peli_3" value="'. pg_fetch_result($result_2, 0, 'titol_original') 	.'"/> </p>
				<p> Idioma audio : 		<input id="info_peli_4" value="'. pg_fetch_result($result_2, 0, 'idioma_audio') 	.'"/> </p>
				<p> Idioma subtitols : 	<input id="info_peli_5" value="'. pg_fetch_result($result_2, 0, 'idioma_subtitols') .'"/> </p>
				<p> Qualitat vídeo : 	<input id="info_peli_6" value="'. pg_fetch_result($result_2, 0, 'qualitat_video') 	.'"/> </p>
				<p> Qualitat audio : 	<input id="info_peli_7" value="'. pg_fetch_result($result_2, 0, 'qualitat_audio') 	.'"/> </p>
				<p> Any estrena : 		<input id="info_peli_8" value="'. pg_fetch_result($result_2, 0, 'any_estrena') 		.'"/> </p>
				<p> Director : 			<input id="info_peli_9" value="'. pg_fetch_result($result_2, 0, 'director') 		.'"/> </p>

				<img src="'.$imatge1.'"
					 id="id_link_imdb"
					 onmouseenter="	if ($(this).attr(\'data_href\') != \'#\') this.style.cursor = \'pointer\';  "
					 onmouseleave="	this.style.cursor = \'initial\';  "
					 onclick="		tractar_link(\'id_link_imdb\', \'Link IMDB\');  "
					 data_href="'. pg_fetch_result($result_2, 0, 'url_imdb') .'"
					 img1="./Img/imdb_logo.png"
					 img2="./Img/imdb_logo2.png"
					 >
					 
				<br/> 
					 
				<img src="'.$imatge2.'"
					 id="id_link_film"
					 onmouseenter="	if ($(this).attr(\'data_href\') != \'#\') this.style.cursor = \'pointer\';  "
					 onmouseleave="	this.style.cursor = \'initial\';  "
					 onclick="		tractar_link(\'id_link_film\', \'Link FilmAffinity\');  "
					 data_href="'. pg_fetch_result($result_2, 0, 'url_film') .'"
					 img1="./Img/filmaffinity_logo.png"
					 img2="./Img/filmaffinity_logo2.png"					 
					 >';					 

				
				echo " <br/> ";

			?>
			
			</br> </br>

		</div>
	</div>

	<div id="id_div_editar" 
		 style="background-color: #CCCCCC; border: solid 1px #888888; align-self: flex-end; 
			width: 70px; height: 30px;
			display: flex; flex-flow: column wrap; 
			align-items: center; 
			align-content: center; 
			"
		onclick="if (mode_editar) { modificar_peli(); } editar_peli();"
		onmouseenter="this.style.cursor = 'pointer';" 
		onmouseleave="this.style.cursor = 'initial'"		
		>
		<p id="id_boto_editar"> Editar </p>

	</div>
	
	
</div>



<div id="id_div_arxius_peli" style="
	position: relative; left: 0px; top: 10px;
	width: 600px; height: 240px;
	overflow-x: hidden; overflow-y: auto;
	border: solid 1px #AAAAAA; 
	padding:10px;
	background-color: #FFFFCC;
	">		



	<table style="width: 570px; font-size: 10px;" >
		<thead> <tr> <th style="width: 500px; text-align: left;" > Fitxer </th> <th> MB </th> </tr> </thead>

		<tbody>

			<?php						
				$x = 0;						
				while ($x < pg_numrows($result_1) and $x < 200) {							
					echo '<tr> 
							<td id="nom_fitxer_'. pg_fetch_result($result_1, $x, "num_arxiu"). '" > '
								. pg_fetch_result($result_1, $x, "nom_arxiu"). '
							</td>
							<td> 700 MB </td> 
						  </tr>';
					$x++;
				}
			?>

		</tbody>				
	</table>
	

</div>

<?php pg_close ($dbconn); ?>