<?php // Recuperar totes les pelicules
		
	$dbconn = pg_connect("host=localhost dbname=Cat_Pelis user=barba password=barba0001")
		or die('No s\'ha pogut connectar : ' . pg_last_error());
		
	$consulta = "
		select id_peli,
			   titol 							as titol_peli,
			   coalesce(url_imdb, '')			as url_imdb,
			   coalesce(url_filmaffinity, '')	as url_filmaffinity
		  from Pelis_Down
		 where id_peli > 0
		 order by id_peli";
		 
	$result = pg_query($dbconn, $consulta);	
?>

<html>
<head>
	<meta charset="UTF-8">
	<title> Llista de Pelis </title>
	
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	
	<style>
	
		html,body,input,select{
			font:normal normal normal 10px/10px Verdana;
		}
		
		td { 
			border: solid 0px #DDDDDD; 
			background-color: #DDDDDD;
			padding: 3px;
		}
		
		input {
			border: solid 1px #888888; 
		}
		
		
	</style>

	
	
	
	<script language="Javascript">
	
		var mode_editar = false;
		var id_peli_sel;
	
		window.onload = function() {
			seleccionar_peli(<?php echo pg_fetch_result($result, 0, "id_peli") ?>);	// Carregar la primera peli de la llista inicialment
		}
		
		// Carregar peli al div lateral (petició AJAX)
		function seleccionar_peli(id_peli) {
			
			id_peli_sel = id_peli;
			
			var xmlhttp;
			if (window.XMLHttpRequest) {	xmlhttp = new XMLHttpRequest();						// code for IE7+, Firefox, Chrome, Opera, Safari
	  		} else {						xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");	// code for IE6, IE5
			}
	
			xmlhttp.onreadystatechange = function() {
	  			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					document.getElementById("id_div_peli").innerHTML = xmlhttp.responseText;
					mode_editar = true;
					editar_peli();
				}
			}
			
			xmlhttp.open("GET", "info_peli.php?id_peli=" + id_peli_sel, true);
			xmlhttp.send();
			
			return false;
		}

		// Modificar pelicula (petició AJAX)
		function modificar_peli() {
			
			// alert ("Modificant peli");
			
			var xmlhttp;
			if (window.XMLHttpRequest) {	xmlhttp = new XMLHttpRequest();						// code for IE7+, Firefox, Chrome, Opera, Safari
	  		} else {						xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");	// code for IE6, IE5
			}
	
			xmlhttp.onreadystatechange = function() {
	  			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					// alert ('resultat tx = ' + xmlhttp.responseText);
					if (xmlhttp.responseText.substring(0, 2) != 'ok')  alert(xmlhttp.responseText.substring(2));
				}
			}
			
			var ruta = $('#id_img_caratula').attr('src');
			var nom_imatge = ruta.slice(ruta.lastIndexOf('/') + 1);
			
			var urlMod = 'modificar_peli.php'
					+ '?id_peli=' 			+ id_peli_sel
					+ '&titol='				+ $('#info_peli_2').val()
					+ '&titol_original='	+ $('#info_peli_3').val()
					+ '&idioma_audio=' 		+ $('#info_peli_4').val()
					+ '&idioma_subtitols='	+ $('#info_peli_5').val()
					+ '&url_imdb=' 			+ $('#id_link_imdb').attr('data_href')
					+ '&url_filmaffinity=' 	+ $('#id_link_film').attr('data_href')
					+ '&qualitat_video=' 	+ $('#info_peli_6').val()
					+ '&qualitat_audio=' 	+ $('#info_peli_7').val()
					+ '&any_estrena=' 		+ $('#info_peli_8').val()
					+ '&director=' 			+ $('#info_peli_9').val()
					+ '&nom_imatge=' 		+ nom_imatge;

			// alert (urlMod);
			
			xmlhttp.open("GET", urlMod, true);
			xmlhttp.send();
			
			return false;
		}

		

		function editar_peli() { 

			mode_editar = !mode_editar;
			
			
			$("#id_boto_editar").text((mode_editar ? "OK" : "Editar"));
			
			$("#id_div_editar")
				.css('background-color', (mode_editar ? "#88DD88" : "#CCCCCC"))
				.css('border-color', 	 (mode_editar ? "#00CC00" : "#888888"));
				
			$("#id_div_peli").css('background-color', (mode_editar ? "#DDFFDD" : "#FFFFEE"));
			
			$("#id_div_info_peli, #id_div_arxius_peli")
				.css('background-color', (mode_editar ? "#AAEEAA" : "#FFFFCC"))
				.css('border-color', 	 (mode_editar ? "#55FF55" : "#AAAAAA"));
				
			$(".info_peli_input").css('background-color', (mode_editar ? "#DDFFDD" : "#EEEEEE"));
			
			if (mode_editar) {	$(".info_peli_input").removeAttr('readonly');	$(".info_peli_sel").removeAttr('disabled');	}
			else 			 {	$(".info_peli_input").attr('readonly', 'true'); $(".info_peli_sel").attr('disabled', ''); }
			
			// El campd de ID no es pot modificar mai
			$("#info_peli_1").attr('readonly', 'true');
			$("#info_peli_1").css('background-color', "#EEEEEE");
			
		} 
		
		function canviar_caratula() {
		
			var ruta = $('#id_img_caratula').attr('src');
			var nom_imatge = ruta.slice(ruta.lastIndexOf('/') + 1);
			var arrel = ruta.slice(0, ruta.lastIndexOf('/') + 1);
			
			// var llista = ruta.split('/'); nom_imatge = llista[llista.length - 1];


			var valor = prompt('Nom de la imatge (a /Img/cataleg/)', nom_imatge);
			if (valor != null) $('#id_img_caratula').attr('src', arrel + valor);
		}

		
		function tractar_link(id_elem, nom_prop) {
			
			var desti = $('#' + id_elem).attr('data_href');
			
			if (!mode_editar && desti != '')  { 
				window.open(desti, '_blank');	// Saltar a l'enllaç
				
			} else { // Editar l'enllaç
				
				var valor = prompt(nom_prop + ' :', desti);
				if (valor != null) {

					$('#' + id_elem).attr('data_href', valor);
				
					// Actualitzar la imatge
					if (valor == '') 	$('#' + id_elem).attr('src', $('#' + id_elem).attr('img2'));
					else 				$('#' + id_elem).attr('src', $('#' + id_elem).attr('img1'));
					
					modificar_peli();
				}
			}
			
		}		

		
		
		function Canvia_Color_Fons_1(elem) { elem.style.backgroundColor = '#AAFFAA'; elem.style.cursor = 'pointer'; }
		function Canvia_Color_Fons_2(elem) { elem.style.backgroundColor = '#DDDDDD'; elem.style.cursor = 'initial'; }

	</script>
	

</head>

<body>

	<h1> Llista de pelicules (<?php echo pg_numrows($result); ?>) </h1>

	<div style="
		position: fixed; left: 30px; top: 30px;
		width: 650px; height: 700px;
		overflow-x: hidden; overflow-y: scroll;
		border: solid 1px #AAAAAA; 
		padding:10px;
		background-color: #FFFFEE;
		">
		
		<table style="font:normal normal normal 10px/10px Verdana; border: 0px solid black;">
			<tbody>

				<?php
					
					$x = 0;
					
					while ($x < pg_numrows($result) and $x < 200) {
						echo '
							<tr> 
								<td style="width:30px"> '. ($x + 1) . ' </td> 
								<td id="peli_' .  pg_fetch_result($result, $x, "id_peli") . '" 
									onmouseenter="Canvia_Color_Fons_1(this);" 
									onmouseleave="Canvia_Color_Fons_2(this);"
									onclick="seleccionar_peli('.pg_fetch_result($result, $x, "id_peli").')"
									style="width:500px"> 											
										'. pg_fetch_result($result, $x, "titol_peli") .'											
								</td>
								<td style="padding: 0px;"> ';
									if (pg_fetch_result($result, $x, "url_imdb") != "") {
										echo '<img src="./Img/imdb_petit.png">';
									}
									echo '
								</td>
							</tr>';
						$x++;
					}
				?>
			
			</tbody>		
		</table>
	
	</div>


	
	
	<div id="id_div_peli"  style="
		position: fixed; left: 750px; top: 30px;
		width: 630px; height: 680px;
		overflow-x: hidden; overflow-y: hidden;
		border: solid 1px #AAAAAA; 
		padding:20px;
		background-color: #FFFFEE;
		">


	</div>		
	
</body>
</html>
