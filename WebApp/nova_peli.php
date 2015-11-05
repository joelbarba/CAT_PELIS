


<div style="width: 100%; height: 30px;
		display: flex; flex-flow: column wrap; 
		align-items: center; 
		align-content: space-between; 
		">
		
	<h2>  </h2>

	<div>
	
		<img src="./Img/icons/add.png" style="width:22px; height:22px; margin-left:15px;"
		 onmouseenter="	if ($(this).attr('data_href') != '') this.style.cursor = 'pointer';  "
		 onmouseleave="	this.style.cursor = 'initial';  "
		 onclick="prepara_nova_peli();"
		 >

		<img src="./Img/icons/del.png" style="width:22px; height:22px; margin-left:15px;"
		 onmouseenter="	if ($(this).attr('data_href') != '') this.style.cursor = 'pointer';  "
		 onmouseleave="	this.style.cursor = 'initial';  "
		 onclick="		tractar_link('id_link_film', 'Link FilmAffinity'); "
		 >

		<img src="./Img/icons/edit.png" style="width:22px; height:22px; margin-left:15px;"
		 onmouseenter="	if ($(this).attr('data_href') != '') this.style.cursor = 'pointer';  "
		 onmouseleave="	this.style.cursor = 'initial';  "
		 onclick=""
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
		
		<img src=""
			height="350" width="250" 
			id="id_img_caratula" onclick="canviar_caratula();"
			style="border: solid 1px #333333; " >
		
		<div style="margin-left:10px; ">
			<?php
			
			$imatge1 = './Img/imdb_logo2.png';
			$imatge2 = './Img/filmaffinity_logo2.png';
			
			$llista_idiomes = array(' ', 'ESP', 'CAT', 'ENG', 'LAT');
			$llista_q_video = array(' ', 'BR-Rip', 'DVD-Rip', 'VHS-Rip', 'TS-Screener');
			$llista_q_audio = array(' ', 'BR-Rip', 'DVD-Rip', 'VHS-Rip', 'TS-Screener');
		
			?>


			<p> Títol :  </br>
			    <input class='info_peli info_peli_input' 
						id="info_peli_2" 
						value="" 
						style="width:250px; margin-top: 2px;" /> 
			</p>

			<p> Títol original : <br/>
				<input class='info_peli info_peli_input' 
						id="info_peli_3" 
						value="" 
						style="width:250px; margin-top: 2px;" /> 
			</p>

			<p> Idioma audio :
				<select class='info_peli info_peli_sel' id="info_peli_4" name="info_peli_4" style="margin-right: 15px;">
					<?php
					for($x = 0; $x < count($llista_idiomes); $x++) {
						echo '<option value="'.$llista_idiomes[$x].'"';
						if ($x == 0) { echo " selected"; }
						echo '>'.$llista_idiomes[$x].'</option>';
					} ?>
				</select>
			</p>
			
			<p> Idioma subtitols :
				<select class='info_peli info_peli_sel' id="info_peli_5" name="info_peli_5" >
					<?php
					for($x = 0; $x < count($llista_idiomes); $x++) {
						echo '<option value="'.$llista_idiomes[$x].'"';
						if ($x == 0) { echo " selected"; }
						echo '>'.$llista_idiomes[$x].'</option>';
					} ?>
				</select>
			</p>

			<p> Vídeo :  	
				<select class='info_peli info_peli_sel' id="info_peli_6" name="info_peli_6" style="margin-right: 15px;">
					<?php
					for($x = 0; $x < count($llista_q_video); $x++) {
						echo '<option value="'.$llista_q_video[$x].'"';
						if ($x == 0) { echo " selected"; }
						echo '>'.$llista_q_video[$x].'</option>';
					} ?>
				</select>
			</p>

			<p> Audio :  	
				<select class='info_peli info_peli_sel' id="info_peli_7" name="info_peli_7" >
					<?php
					for($x = 0; $x < count($llista_q_audio); $x++) {
						echo '<option value="'.$llista_q_audio[$x].'"';
						if ($x == 0) { echo " selected"; }
						echo '>'.$llista_q_audio[$x].'</option>';
					} ?>
				</select>
			</p>
			
			<p> Any estrena : 		<input class='info_peli info_peli_input' id="info_peli_8" value="" style="width:50px;  text-align: center; margin-right:15px;"/> </p>
			<p> Director : 			<input class='info_peli info_peli_input' id="info_peli_9" value="" style="width:140px; text-align: center;"/> </p>

			<img src="<?php echo $imatge1; ?>"
				 id="id_link_imdb" style="width:80px;"
				 onmouseenter="	if ($(this).attr('data_href') != '') this.style.cursor = 'pointer';  "
				 onmouseleave="	this.style.cursor = 'initial';  "
				 onclick="		tractar_link('id_link_imdb', 'Link IMDB'); "
				 data_href=""
				 img1="./Img/imdb_logo.png"
				 img2="./Img/imdb_logo2.png"
				 >
				 

				 
			<img src="<?php echo $imatge2; ?>"
				 id="id_link_film" style="width:150px;"
				 onmouseenter="	if ($(this).attr('data_href') != '') this.style.cursor = 'pointer';  "
				 onmouseleave="	this.style.cursor = 'initial';  "
				 onclick="		tractar_link('id_link_film', 'Link FilmAffinity'); "
				 data_href=""
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
		onclick="insertar_peli();"
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


