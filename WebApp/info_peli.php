<?php // Recuperar tots els arxius de la pelicula
		
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




<div style="width: 100%; height: 30px;
		display: flex; flex-flow: column wrap; 
		align-items: center; 
		align-content: space-between; 
		">

	<?php echo "<h2> " . pg_fetch_result($result_2, 0, 'id_peli') . " : " . pg_fetch_result($result_2, 0, "titol") . "</h2>"; ?>
	
	

	<div>
	
		<img src="./Img/icons/add.png" style="width:22px; height:22px; margin-left:15px;"
		 onmouseenter="	if ($(this).attr('data_href') != '') this.style.cursor = 'pointer';  "
		 onmouseleave="	this.style.cursor = 'initial';  "
		 onclick="prepara_nova_peli();"
		 >

		<img src="./Img/icons/del.png" style="width:22px; height:22px; margin-left:15px;"
		 onmouseenter="	if ($(this).attr('data_href') != '') this.style.cursor = 'pointer';  "
		 onmouseleave="	this.style.cursor = 'initial';  "
		 onclick="eliminar_peli();"
		 >

		<img src="./Img/icons/edit.png" style="width:22px; height:22px; margin-left:15px;"
		 onmouseenter="	if ($(this).attr('data_href') != '') this.style.cursor = 'pointer';  "
		 onmouseleave="	this.style.cursor = 'initial';  "
		 onclick="editar_peli();"
		 >
		 
	</div>

</div>	

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
			
			if (pg_fetch_result($result_2, 0, "url_imdb") == "")	$imatge1 = './Img/imdb_logo2.png';
			else													$imatge1 = './Img/imdb_logo.png';

			if (pg_fetch_result($result_2, 0, "url_film") == "")	$imatge2 = './Img/filmaffinity_logo2.png';
			else													$imatge2 = './Img/filmaffinity_logo.png';
			
			$llista_idiomes = array(' ', 'ESP', 'CAT', 'ENG', 'LAT');
			$llista_q_video = array(' ', 'BR-Rip', 'DVD-Rip', 'VHS-Rip', 'TS-Screener');
			$llista_q_audio = array(' ', 'BR-Rip', 'DVD-Rip', 'VHS-Rip', 'TS-Screener');
		
			?>

			<input id="info_peli_1" value="<?php echo pg_fetch_result($result_2, 0, 'id_peli'); ?>" 
				style="display:none; width:28px;  text-align: center; margin-right:10px;" /> 



			<p> Títol :  </br>
			    <input class='info_peli info_peli_input' 
						id="info_peli_2" 
						value="<?php echo pg_fetch_result($result_2, 0, 'titol'); ?>" 
						style="width:250px; margin-top: 2px;" /> 
			</p>

			<p> Títol original : <br/>
				<input class='info_peli info_peli_input' 
						id="info_peli_3" 
						value="<?php echo pg_fetch_result($result_2, 0, 'titol_original');?>" 
						style="width:250px; margin-top: 2px;" /> 
			</p>

			<p> Idioma audio :
				<select class='info_peli info_peli_sel' id="info_peli_4" name="info_peli_4" disabled style="margin-right: 15px;">
					<?php
					for($x = 0; $x < count($llista_idiomes); $x++) {
						echo '<option value="'.$llista_idiomes[$x].'"';
						if (pg_fetch_result($result_2, 0, 'idioma_audio') == $llista_idiomes[$x]) { echo " selected"; }
						echo '>'.$llista_idiomes[$x].'</option>';
					} ?>
				</select>
			</p>
			
			<p> Idioma subtitols :
				<select class='info_peli info_peli_sel' id="info_peli_5" name="info_peli_5" disabled>
					<?php
					for($x = 0; $x < count($llista_idiomes); $x++) {
						echo '<option value="'.$llista_idiomes[$x].'"';
						if (pg_fetch_result($result_2, 0, 'idioma_subtitols') == $llista_idiomes[$x]) { echo " selected"; }
						echo '>'.$llista_idiomes[$x].'</option>';
					} ?>
				</select>
			</p>

			<p> Vídeo :  	
				<select class='info_peli info_peli_sel' id="info_peli_6" name="info_peli_6" disabled style="margin-right: 15px;">
					<?php
					for($x = 0; $x < count($llista_q_video); $x++) {
						echo '<option value="'.$llista_q_video[$x].'"';
						if (pg_fetch_result($result_2, 0, 'qualitat_video') == $llista_q_video[$x]) { echo " selected"; }
						echo '>'.$llista_q_video[$x].'</option>';
					} ?>
				</select>
			</p>

			<p> Audio :  	
				<select class='info_peli info_peli_sel' id="info_peli_7" name="info_peli_7" disabled>
					<?php
					for($x = 0; $x < count($llista_q_audio); $x++) {
						echo '<option value="'.$llista_q_audio[$x].'"';
						if (pg_fetch_result($result_2, 0, 'qualitat_audio') == $llista_q_audio[$x]) { echo " selected"; }
						echo '>'.$llista_q_audio[$x].'</option>';
					} ?>
				</select>
			</p>
			
			<p> Any estrena : 		<input class='info_peli info_peli_input' id="info_peli_8" value="<?php echo pg_fetch_result($result_2, 0, 'any_estrena');	?>" style="width:50px;  text-align: center; margin-right:15px;"/> </p>
			<p> Director : 			<input class='info_peli info_peli_input' id="info_peli_9" value="<?php echo pg_fetch_result($result_2, 0, 'director'); 		?>" style="width:140px; text-align: center;"/> </p>

			<img src="<?php echo $imatge1; ?>"
				 id="id_link_imdb" style="width:80px;"
				 onmouseenter="	if ($(this).attr('data_href') != '') this.style.cursor = 'pointer';  "
				 onmouseleave="	this.style.cursor = 'initial';  "
				 onclick="		tractar_link('id_link_imdb', 'Link IMDB'); "
				 data_href="<?php echo pg_fetch_result($result_2, 0, 'url_imdb'); ?>"
				 img1="./Img/imdb_logo.png"
				 img2="./Img/imdb_logo2.png"
				 >
				 

				 
			<img src="<?php echo $imatge2; ?>"
				 id="id_link_film" style="width:150px;"
				 onmouseenter="	if ($(this).attr('data_href') != '') this.style.cursor = 'pointer';  "
				 onmouseleave="	this.style.cursor = 'initial';  "
				 onclick="		tractar_link('id_link_film', 'Link FilmAffinity'); "
				 data_href="<?php echo pg_fetch_result($result_2, 0, 'url_film') ?>"
				 img1="./Img/filmaffinity_logo.png"
				 img2="./Img/filmaffinity_logo2.png"					 
				 >

			
			<br/> </br> </br>

		</div>
	</div>

</div>



<div id="id_div_arxius_peli" style="
	position: relative; left: 0px; top: 10px;
	width: 600px; height: 200px;
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
					?>
					<tr> 
						<td id="nom_fitxer_<?php echo pg_fetch_result($result_1, $x, "num_arxiu"); ?>"
							onclick="editar_fitxer(this, <?php echo pg_fetch_result($result_1, $x, "num_arxiu"); ?>)"
							onmouseenter="this.style.cursor = 'pointer';" 
							onmouseleave="this.style.cursor = 'initial'"
							>
							<?php echo pg_fetch_result($result_1, $x, "nom_arxiu"); ?>
						</td>
						<td> 700 MB </td> 
					</tr>
					<?php $x++;
				}
			?>

		</tbody>				
	</table>
	

</div>

<div style="
	position: relative; left: 0px; top: 10px;
	overflow: hidden;
	margin-top: 10px;
	display: flex; flex-flow: row-reverse wrap; align-items: center; 
	">	

	<div id="id_div_cancelar" class="boto_accio_on" style="margin-left:10px;"
		onclick="if (mode_editar) { modificar_peli(); } editar_peli();"
		onmouseenter="this.style.cursor = 'pointer';" 
		onmouseleave="this.style.cursor = 'initial'"
		>
		<p id="id_boto_ok"> Ok </p>
	</div>

	<div id="id_div_ok" class="boto_accio_on"
		onclick="seleccionar_peli(id_peli_sel);" 
		onmouseenter="this.style.cursor = 'pointer';" 
		onmouseleave="this.style.cursor = 'initial'"		
		>
		<p id="id_boto_cancelar"> Cancelar </p>
	</div>	
	
</div>



<?php pg_close ($dbconn); ?>
