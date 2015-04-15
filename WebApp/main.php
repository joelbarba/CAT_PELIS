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
		
		.boto_accio_on {
			background-color: #CCCCCC; 
			border: solid 1px #888888;
			width: 70px; height: 30px;
			text-align: center;
			flex-flow: column wrap; align-items: center;
		}
		

	</style>

	
	
	
	<script language="Javascript">
	
		var mode_editar = false;
		var id_peli_sel;
	
		window.onload = function() {

			carregar_llista_pelis();
		}
		
		function carregar_llista_pelis() {

		
			$.post("llistat_pelis.php",
				{ 	id_peli			:	0
				},
				function(data, status) { 
				
					var xmlDoc = $.parseXML( xml );
					alert (xmlDoc);
				
					alert("Status: " + status + "\n Data: " + $(data).find("*")[0].textContent);
					if (status == 'success') {						
						if ($(data).find("resultat")[0].textContent != 'ok') {
							alert ('No s\'ha pogut carregar la llista de pelicules : ' + $(data).find("desc_error")[0].textContent);
						}
					}	
				}
			);			
		
		
		

			$('#id_taula_llista_pelis').empty();
			
			var fila = '<tr> ';
			fila += '	<td style="width:30px"> 9999 </td> ';
			fila += '	<td style="width:500px" id="peli_9999"> titol pinicullla </td> ';
			fila += '	<td style="padding: 0px;"> <img src="./Img/imdb_petit.png"> </td>';
			fila += '</tr>';

			
			$('#id_taula_llista_pelis').append(fila);
			$('#id_taula_llista_pelis').append(fila);
			$('#id_taula_llista_pelis').append(fila);
			$('#id_taula_llista_pelis').append(fila);
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

			var ruta = $('#id_img_caratula').attr('src');
			var nom_imatge = ruta.slice(ruta.lastIndexOf('/') + 1);

			$.post("modificar_peli.php",
				{ 	id_peli			:	id_peli_sel,
					titol			:	$('#info_peli_2').val(),
					titol_original	:	$('#info_peli_3').val(),
					idioma_audio	: 	$('#info_peli_4').val(),
					idioma_subtitols:	$('#info_peli_5').val(),
					url_imdb		: 	$('#id_link_imdb').attr('data_href'),
					url_filmaffinity: 	$('#id_link_film').attr('data_href'),
					qualitat_video	: 	$('#info_peli_6').val(),
					qualitat_audio	: 	$('#info_peli_7').val(),
					any_estrena		: 	$('#info_peli_8').val(),
					director		: 	$('#info_peli_9').val(),
					nom_imatge		: 	nom_imatge
				},
				function(data, status) { 
					// alert("Status: " + status + "\n Data: " + data);
					if (status == 'success') {						
						if ($(data).find("resultat")[0].textContent != 'ok') {
							alert ('No s\'ha pogut modificar correctament la peli: ' + $(data).find("resultat")[0].textContent);
						}
					}	
				}
			);	
			
			return false;
			
		}
		
		function prepara_nova_peli() {
		
			$.post("nova_peli.php",{},
				function(data, status) { 
					if (status == 'success') {
						$('#id_div_peli').html(data);
						// alert("Status: " + status + "\n Data: " + data);
					}	
				}
			);
		}

		function insertar_peli() {
		
			var ruta = $('#id_img_caratula').attr('src');
			var nom_imatge = ruta.slice(ruta.lastIndexOf('/') + 1);
		
			$.post("insertar_peli.php",
				{ 	titol				: $('#info_peli_2').val(),
					titol_original		: $('#info_peli_3').val(),
					idioma_audio		: $('#info_peli_4').val(),
					idioma_subtitols	: $('#info_peli_5').val(),
					url_imdb			: $('#id_link_imdb').attr('data_href'),
					url_filmaffinity	: $('#id_link_film').attr('data_href'),
					qualitat_video		: $('#info_peli_6').val(),
					qualitat_audio		: $('#info_peli_7').val(),
					any_estrena			: $('#info_peli_8').val(),
					director			: $('#info_peli_9').val(),
					nom_imatge			: nom_imatge
				},
				function(data, status) { 

					if (status == 'success') {
						// alert("Status: " + status + "\n Data: " + data);	
						if ($(data).find("resultat")[0].textContent == 'ok') {
							var id_peli = $(data).find("id_peli")[0].textContent;
							seleccionar_peli(id_peli);
						} else {
							alert ('No s\'ha pogut insertar correctament la peli');
						}
						
					}	
				}
			);		
			
		}
		
		function eliminar_peli() {

			if (!confirm('Estàs segur que vols eliminar la pel·lícula "' + $('#info_peli_2').val() + '"')) return;

			$.post("eliminar_peli.php",
				{ 	id_peli	: id_peli_sel },
				function(data, status) { 

					if (status == 'success') {
						// alert("Status: " + status + "\n Data: " + data);	
						if ($(data).find("resultat")[0].textContent == 'ok') {

							if ($(data).find("count_peli")[0].textContent == '0') alert ('No s\'ha eliminat cap pelicula');
							// if ($(data).find("count_arxius")[0].textContent == '0') alert ('No s\'ha eliminat cap arxiu');							
							
							var id_peli = $(data).find("id_peli_seg")[0].textContent;
							seleccionar_peli(id_peli);							
						} else {
							alert ('No s\'ha pogut eliminar correctament la peli : ' + $(data).find("desc_error")[0].textContent);
						}
					}	
				}
			);		
			
		}
		

		function editar_peli() { 

			mode_editar = !mode_editar;

			$("#id_div_ok, #id_div_cancelar")
				.css('display', mode_editar ? 'flex' : 'none')
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
		
		
		function editar_fitxer(obj, id_peli, num_arxiu) {
			
			var nom_nou = prompt('Canviar nom del arxiu :', obj.innerText);
			if (nom_nou == '') return;

			$.post("modificar_fitxer.php",
				{ 	id_peli			:	id_peli,
					num_arxiu		:	num_arxiu,
					nom_arxiu		:	nom_nou
				},
				function(data, status) { 
					alert("Status: " + status + "\n Data: " + data);
					if (status == 'success') {						
						if ($(data).find("resultat")[0].textContent != 'ok') {
							alert ('No s\'ha pogut modificar correctament el nom del arxiu: ' + $(data).find("resultat")[0].textContent);
						}
					}	
				}
			);	


			
			obj.innerText = nom_nou;
			
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

<body style="display: flex; flex-flow: column wrap;
			align-items: stretch; align-content: space-between;
			overflow: hidden; margin: 0px; padding: 10px;
			width: 100%; height: 98%;">

	<h1 style="position: fixed;"> CAT_PELIS </h1>

	<div style="
		position: relative;
		width: 43%; height: 100%;
		overflow-x: hidden; overflow-y: scroll;
		border: solid 1px #AAAAAA; 
		padding:10px; margin-right: 10px; margin-top: 40px;
		background-color: #FFFFEE;
		">
		
		
		<table style="font:normal normal normal 10px/10px Verdana; border: 0px solid black;">
			<tbody id="id_taula_llista_pelis">


							<tr> 
								<td style="width:30px"> 9999 </td> 
								<td id="peli_9999"
									onmouseenter="Canvia_Color_Fons_1(this);" 
									onmouseleave="Canvia_Color_Fons_2(this);"
									onclick="seleccionar_peli(9999);"
									style="width:500px"> 											
										titol pelicula
								</td>
								<td style="padding: 0px;">
									<img src="./Img/imdb_petit.png">
								</td>
							</tr>
			
			</tbody>		
		</table>
	
	</div>


	
	
	<div id="id_div_peli"  style="
		position: relative;
		width: 49%; height: 100%;
		overflow-x: hidden; overflow-y: hidden;
		border: solid 1px #AAAAAA; 
		padding:20px; margin-right: 20px;
		background-color: #FFFFEE;
		">


	</div>		
	
</body>
</html>
