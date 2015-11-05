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









		.fila_seleccionada {
			background-color: #AAFFAA;
			cursor 			: pointer;
		}

		.fila_no_seleccionada {
			background-color: #DDDDDD;
			cursor 			: initial;
		}

		.fila_fixada_seleccio {
			background-color: #33FF33;
			cursor 			: pointer;
		}

	</style>




	<script language="Javascript">


		// off / con / mod / add
		var mode_act = 'off';
		var id_peli_sel;







		window.onload = function() {

			canviar_mode('off');
			carregar_llista_pelis();

		}

		function carregar_llista_pelis() {

			$.post("dades/llistat_pelis.php",
				{ 	id_peli			:	0
				},
				function(data, status) {

					// alert("Status: " + status + "\n Data: " + $(data).find("*")[0].textContent);
					// alert('DOCUMENT XML : ' + data.children[0].innerHTML);

					if (status == 'success') {

						var resposta = data.children[0];
						var resultat   = resposta.children[0].textContent;
						var desc_error = resposta.children[1].textContent;
						// alert ('resultat = ' + resultat + ', desc_error = ' + desc_error);

						// if ($(data).find("resultat")[0].textContent != 'ok') {
						if (resultat != 'ok') {
							alert ('No s\'ha pogut carregar la llista de pelicules : ' + desc_error);
						} else {
							muntar_llista_pelis(resposta);
						}
					}
				}
			);

		}



		/* Esctuctura llista:
		<tbody id="id_taula_llista_pelis">
			<tr>
				<td> [num] </td>
				<td id="peli_9999"> titol pelicula </td>
				<td> <img> </td>
			</tr>
			...
		</tbody> */

		function muntar_llista_pelis(resposta_xml) {

			/*	Format del XML a retornar :
			<resposta>
				0	<resultat> 		ok/ko 	</resultat>
				1	<desc_error>	desc 	</desc_error>
				2	<num_pelis>		999		</num_pelis>
				3	<llista_pelis>
					0	<peli>
						0	<id_peli>		</id_peli>
						1	<titol_peli>	</titol_peli>
						2	<url_imdb>      </url_imdb>
						3	<url_film>      </url_film>
					</peli>
				</llista_pelis>
			</resposta>	*/

			$('#id_taula_llista_pelis').empty();

			var num_pelis = resposta_xml.children[2].textContent;
			var llista = resposta_xml.children[3];

			for (t = 1; t <= num_pelis; ++t) {

				$('#id_taula_llista_pelis').append(
					generar_element_llista(
						llista.children[t - 1].children[0].textContent, // id_peli,
						llista.children[t - 1].children[1].textContent,	// titol
						llista.children[t - 1].children[2].textContent	// url_imdb
					));
			}

			/* Es descarta l'opció de utilitzar el HOVER perque no permet vincular l'event de forma delegada a tots els fills
			$('.peli_de_la_llista').hover(
				function() { $(this).addClass('fila_seleccionada').prev().addClass('fila_seleccionada'); },			// mouseenter
				function() { $(this).removeClass('fila_seleccionada').prev().removeClass('fila_seleccionada'); } 	// mouseleave
			); */

			$('#id_taula_llista_pelis')
				.on('mouseenter', '.peli_de_la_llista', function() {
					$(this).addClass('fila_seleccionada').prev().addClass('fila_seleccionada');
			 	})
				.on('mouseleave', '.peli_de_la_llista', function() {
					$(this).removeClass('fila_seleccionada').prev().removeClass('fila_seleccionada');
			 	})
				.on('click', '.peli_de_la_llista', function() {
					if (mode_act == 'mod') { if (confirm('Vols guardar els canvis de la modificació en curs?')) modificar_peli(); }
					if (mode_act == 'add') { if (confirm('Vols guardar els canvis de la nova peli?')) insertar_peli(); }
					seleccionar_peli($(this).attr('id_peli'))
				});

			// Si no hi ha cap id_peli seleccionat, apuntar a la primera de la llista
			if (typeof id_peli_sel === 'undefined') id_peli_sel = llista.children[0].children[0].textContent;
			seleccionar_peli(id_peli_sel);

		}

		function generar_element_llista(id_peli, titol, url_imdb) {
			var fila = '';
			fila += '<tr> ';
			fila += '	<td style="width:30px"> ' + id_peli + ' </td> ';
			fila += '	<td style="width:500px" ';
			fila += '		id_peli="' + id_peli + '"';
			fila += '		class="peli_de_la_llista"';
			fila += '		> ' + titol;
			fila += '	</td> ';
			fila += '	<td style="padding: 0px;"> ';
							if (url_imdb != '') fila += '<img src="./Img/imdb_petit.png">';
			fila += '	</td>';
			fila += '</tr>';
			return fila;
		}



		// Carregar peli al div lateral (petició AJAX)
		function seleccionar_peli(id_peli) {

			// alert("seleccionant " + id_peli);

			$('#id_taula_llista_pelis [id_peli=' + id_peli_sel + ']')
				.removeClass('fila_fixada_seleccio')
				.prev().removeClass('fila_fixada_seleccio');

			id_peli_sel = id_peli;

			$('#id_taula_llista_pelis [id_peli=' + id_peli + ']')
				.addClass('fila_fixada_seleccio')
				.prev().addClass('fila_fixada_seleccio');


			$.post("dades/consulta_peli.php",
				{ 	id_peli	: id_peli
				},
				function(data, status) {

					// alert("Status: " + status + "\n Data: " + $(data).find("*")[0].textContent);
					// alert('DOCUMENT XML : ' + data.children[0].innerHTML);
					if (status == 'success') {

						var resposta = data.children[0];
						var resultat   = resposta.children[0].textContent;
						var desc_error = resposta.children[1].textContent;

						if (resultat != 'ok') {
							alert ('No s\'ha pogut carregar la informació de la pelicula : ' + desc_error);
						} else {
							muntar_info_peli(resposta);
						}
					}
				}
			);
		}

		function muntar_info_peli(resposta_xml) {

			/*	Format del XML a retornar :

				<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
				<resposta>
					0	<resultat> 		ok/ko 	</resultat>
					1	<desc_error>	desc 	</desc_error>
					2	<info_peli>
						0	<id_peli>
						1	<titol
						2	<titol_original>
						3	<nom_imatge>
						4	<idioma_audio>
						5	<idioma_subtitols>
						6	<qualitat_video>
						7	<qualitat_audio>
						8	<any_estrena>
						9	<director>
						10	<url_imdb>
						11	<url_film>
					</info_peli>
				</resposta>
			*/


			var info_peli = resposta_xml.children[2];

			var id_peli 			= info_peli.children[0].textContent;
			var titol 				= info_peli.children[1].textContent;
			var titol_original 		= info_peli.children[2].textContent;
			var nom_imatge 			= info_peli.children[3].textContent;
			var idioma_audio 		= info_peli.children[4].textContent;
			var idioma_subtitols 	= info_peli.children[5].textContent;
			var qualitat_video 		= info_peli.children[6].textContent;
			var qualitat_audio 		= info_peli.children[7].textContent;
			var any_estrena 		= info_peli.children[8].textContent;
			var director 			= info_peli.children[9].textContent;
			var url_imdb 			= info_peli.children[10].textContent;
			var url_film 			= info_peli.children[11].textContent;

			$('#info_peli_titol').text(id_peli + ' : ' + titol);
			$('#info_peli_imatge_caratula').attr('src', './Img/cataleg/' + nom_imatge);

			$('#info_peli_1').val(id_peli);
			$('#info_peli_2').val(titol);
			$('#info_peli_3').val(titol_original);
			$('#info_peli_4').val(idioma_audio);
			$('#info_peli_5').val(idioma_subtitols);
			$('#info_peli_6').val(qualitat_video);
			$('#info_peli_7').val(qualitat_audio);
			$('#info_peli_8').val(any_estrena);
			$('#info_peli_9').val(director);

			$('#id_link_imdb').attr('data_href', url_imdb);
			$('#id_link_film').attr('data_href', url_film);

			canviar_mode('con');

		}


		// Modificar pelicula (petició AJAX)
		function modificar_peli() {

			// alert ("Modificant peli");
			var ruta = $('#info_peli_imatge_caratula').attr('src');
			var nom_imatge = ruta.slice(ruta.lastIndexOf('/') + 1);

			$.post("dades/modificar_peli.php",
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
					// alert('DOCUMENT XML : ' + data.children[0].innerHTML);
					if (status != 'success') {
						alert('Error de transacció');
					} else {

						var resposta = data.children[0];
						var resultat    = resposta.children[0].textContent;
						var desc_error  = resposta.children[1].textContent;
						var id_peli_mod = resposta.children[2].textContent;
						var titol_mod   = resposta.children[3].textContent;

						if (resultat != 'ok') {
							alert ('No s\'ha pogut modificar correctament la peli: ' + $(data).find("resultat")[0].textContent);
						}

						$('#id_taula_llista_pelis [id_peli=' + id_peli_mod + ']').text(titol_mod);
						seleccionar_peli(id_peli_sel);
					}
				}
			);
			return false;
		}

		// Insertar pelicula (petició AJAX)
		function insertar_peli() {

			var ruta = $('#info_peli_imatge_caratula').attr('src');
			var nom_imatge = ruta.slice(ruta.lastIndexOf('/') + 1);

			$.post("dades/insertar_peli.php",
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
						// alert('DOCUMENT XML : ' + data.children[0].innerHTML);

						var resposta = data.children[0];
						var resultat   = resposta.children[0].textContent;
						var desc_error = resposta.children[1].textContent;

						if (resultat != 'ok') {
							alert ('No s\'ha pogut insertar correctament la peli');
						} else {

							/*	Format del XML a retornar :

								<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
								<resposta>
									0	<resultat> 		ok/ko 	</resultat>
									1	<desc_error>	desc 	</desc_error>
									2	<id_peli>		9999	</id_peli>
									3	<titol>			xxxx	</titol>
									4	<url_imdb>		xxxx	</url_imdb>
								</resposta> */

							// Afegir element a la llista i seleccionar-lo
							$('#id_taula_llista_pelis').prepend(
								generar_element_llista(
									resposta.children[2].textContent, 	// id_peli
									resposta.children[3].textContent,	// titol
									resposta.children[4].textContent	// url_imdb
								));

							seleccionar_peli(resposta.children[2].textContent);
							// carregar_llista_pelis();

						}

					}
				}
			);
		}


		function eliminar_peli() {

			if (!confirm('Estàs segur que vols eliminar la pel·lícula "' + $('#info_peli_2').val() + '"')) return;

			$.post("dades/eliminar_peli.php",
				{ 	id_peli	: id_peli_sel },
				function(data, status) {

					if (status == 'success') {
						// alert('DOCUMENT XML : ' + data.children[0].innerHTML);

						var resposta = data.children[0];
						var resultat   = resposta.children[0].textContent;
						var desc_error = resposta.children[1].textContent;

						if (resultat != 'ok') {
							alert ('No s\'ha pogut eliminar correctament la peli : ' + desc_error);
						} else {

							/*	Format del XML a retornar :

								<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
								<resposta>
									0 <resultat> 		ok/ko 	</resultat>
									1 <desc_error>		desc 	</desc_error>
									2 <id_peli_del>		9999	</id_peli_del>
									3 <id_peli_seg>		9999	</id_peli_seg>
									4 <count_arxius>	999		</count_arxius>
									5 <count_peli>		999		</count_peli>
							    </resposta> */

							var id_peli_del = resposta.children[2].textContent;
							var id_peli     = resposta.children[3].textContent;
							var count_peli  = resposta.children[5].textContent;

							if (count_peli == '0') alert ('No s\'ha eliminat cap pelicula');

							$('#id_taula_llista_pelis [id_peli=' + id_peli_del + ']').parent().remove();
							seleccionar_peli(id_peli);
						}
					}
				}
			);

		}







		/*
				<img id="id_img_add_peli" src="./Img/icons/add.png" style="width:22px; height:22px; margin-left:15px;"
				 onmouseenter="	if ($(this).attr('data_href') != '') this.style.cursor = 'pointer';  "
				 onmouseleave="	this.style.cursor = 'initial';  "
				 onclick="prepara_nova_peli();"
				 >

				<img id="id_img_del_peli" src="./Img/icons/del.png" style="width:22px; height:22px; margin-left:15px;"
				 onmouseenter="	if ($(this).attr('data_href') != '') this.style.cursor = 'pointer';  "
				 onmouseleave="	this.style.cursor = 'initial';  "
				 onclick="eliminar_peli();"
				 >

				<img id="id_img_mod_peli" src="./Img/icons/edit.png" style="width:22px; height:22px; margin-left:15px;"
				 onmouseenter="	if ($(this).attr('data_href') != '') this.style.cursor = 'pointer';  "
				 onmouseleave="	this.style.cursor = 'initial';  "
				 onclick="editar_peli();"
				 >

			<div id="id_div_ok" class="boto_accio_on" style="margin-left:10px;"
				onclick="if (mode_editar) { modificar_peli(); } editar_peli();"
				onmouseenter="this.style.cursor = 'pointer';"
				onmouseleave="this.style.cursor = 'initial'"
				>
				<p id="id_boto_ok"> Ok </p>
			</div>

			<div id="id_div_cancelar" class="boto_accio_on"
				onclick="seleccionar_peli(id_peli_sel);"
				onmouseenter="this.style.cursor = 'pointer';"
				onmouseleave="this.style.cursor = 'initial'"
				>
				<p id="id_boto_cancelar"> Cancelar </p>
			</div>


			<div id="id_div_cancelar" class="boto_accio_on" style="margin-left:10px;"
				onclick="insertar_peli();"
				>
				<p id="id_boto_ok"> Ok </p>
			</div>

			<div id="id_div_ok" class="boto_accio_on"
				onclick="seleccionar_peli(id_peli_sel);"
				>
				<p id="id_boto_cancelar"> Cancelar </p>
			</div>



		*/



		function canviar_mode(mode_nou) {

			// off / con / mod / add
			mode_act = mode_nou;

			// Desactivar events
			$('#id_link_imdb').unbind();
			$('#id_link_film').unbind();
			$('#info_peli_imatge_caratula').unbind();
			$('#id_img_add_peli, #id_img_del_peli, #id_img_mod_peli').unbind();
			$('#id_div_ok, #id_div_cancelar').unbind();
			$('.info_peli_sel, .info_peli_input').unbind();


			// Botons add/del/mod peli
			if (mode_act == 'con') {

				$('#id_img_add_peli, #id_img_del_peli, #id_img_mod_peli')
					.css('opacity', '1')
					.mouseenter(function() { this.style.cursor = 'pointer'; })
					.mouseleave(function() { this.style.cursor = 'initial'; });

				$('#id_img_mod_peli').click(function() { canviar_mode('mod'); });
				$('#id_img_del_peli').click(function() { eliminar_peli(); });


				$('#id_img_add_peli').click(function() {
					// Deixar tots els camps buits per introduir info de peli nova
					$('#info_peli_titol').text('');
					$('#info_peli_imatge_caratula').attr('src', '');
					$('.info_peli_sel, .info_peli_input').val('');
					$('#id_link_imdb').attr('data_href', '');
					$('#id_link_film').attr('data_href', '');
					canviar_mode('add');
				});


			} else {
				$('#id_img_add_peli, #id_img_del_peli, #id_img_mod_peli').css('opacity', '0.1');
			}

			// Botons ok/cancel
			if (mode_act == 'mod' || mode_act == 'add') {
				$('#id_div_ok, #id_div_cancelar')
					.css('display', 'flex')
					.mouseenter(function() { this.style.cursor = 'pointer'; })
					.mouseleave(function() { this.style.cursor = 'initial'; });

				$('#id_div_cancelar').click(function() { seleccionar_peli(id_peli_sel); });

				if (mode_act == 'mod') $('#id_div_ok').click(function() { modificar_peli(); });
				if (mode_act == 'add') $('#id_div_ok').click(function() { insertar_peli(); });

			} else {
				$('#id_div_ok, #id_div_cancelar').css('display', 'none');
			}


			// Detectar quan es modifica info, i passar a mode modificar
			if (mode_act == 'con') {
				$('.info_peli_sel').change(function() { canviar_mode('mod'); });
				$('.info_peli_input').change(function() { canviar_mode('mod'); });
				$('.info_peli_input').keydown(function() { canviar_mode('mod'); });
			}


			// Estil de info peli
			if (mode_act == 'off') {	// gris
				$('#id_div_peli').css('background-color', '#EEEEEE');
				$('#id_div_info_peli, #id_div_arxius_peli').css('background-color', '#DDDDDD')
				$('.info_peli_input').css('border-color', '#888888');
			}
			if (mode_act == 'con') {	// groc
				$('#id_div_peli').css('background-color', '#FFFFEE');
				$('#id_div_info_peli, #id_div_arxius_peli').css('background-color', '#FFFFCC')
				$('.info_peli_input').css('border-color', '#888888');
			}
			if (mode_act == 'add') {	// verd
				$('#id_div_peli').css('background-color', '#DDFFFF');
				$("#id_div_info_peli, #id_div_arxius_peli").css('background-color', '#AAEEEE');
				$('.info_peli_input').css('border-color', '#008888');
			}
			if (mode_act == 'mod') {	// blau
				$('#id_div_peli').css('background-color', '#DDFFDD');
				$("#id_div_info_peli, #id_div_arxius_peli").css('background-color', '#AAEEAA');
				$('.info_peli_input').css('border-color', '#008800');
			}




			if (mode_act == 'off') {
				// Desactivar els input select
				$('#info_peli_4, #info_peli_5, #info_peli_6, #info_peli_7').attr('disabled', '');

				// Desactivar enllaços IMDB
				$('#id_link_imdb').attr('src', $('#id_link_imdb').attr('img2'));
				$('#id_link_film').attr('src', $('#id_link_film').attr('img2'));

				$('#info_peli_imatge_caratula').attr('src', '');	// Imatge caratula

			} else {

				// Activar els input select
				$('#info_peli_4, #info_peli_5, #info_peli_6, #info_peli_7').removeAttr('disabled');

				// Assignar imatge als enllaços del IMDB i Film
				if ($('#id_link_imdb').attr('data_href') == '') $('#id_link_imdb').attr('src', $('#id_link_imdb').attr('img2'));
				else											$('#id_link_imdb').attr('src', $('#id_link_imdb').attr('img1'));

				if ($('#id_link_film').attr('data_href') == '') $('#id_link_film').attr('src', $('#id_link_film').attr('img2'));
				else											$('#id_link_film').attr('src', $('#id_link_film').attr('img1'));

				// Assignar events enllaços IMDB i Film
				$('#id_link_imdb').mouseenter(function() { if ($(this).attr('data_href') != '') this.style.cursor = 'pointer'; });
				$('#id_link_imdb').mouseleave(function() { this.style.cursor = 'initial'; });
				$('#id_link_imdb').click(function() { tractar_link($(this), 'Link IMDB'); });

				$('#id_link_film').mouseenter(function() { if ($(this).attr('data_href') != '') this.style.cursor = 'pointer'; });
				$('#id_link_film').mouseleave(function() { this.style.cursor = 'initial'; });
				$('#id_link_film').click(function() { tractar_link($(this), 'Link FilmAffinity'); });


				// Canviar imatge caratula
				$('#info_peli_imatge_caratula').click(function() {
					var ruta = $('#info_peli_imatge_caratula').attr('src');
					var nom_imatge = ruta.slice(ruta.lastIndexOf('/') + 1);
					var arrel = ruta.slice(0, ruta.lastIndexOf('/') + 1);
					if (arrel == '') arrel = './Img/cataleg/';

					var valor = prompt('Nom de la imatge (a ' + arrel + ')', nom_imatge);
					if (valor != null) {
						$('#info_peli_imatge_caratula').attr('src', arrel + valor);
						if (mode_act == 'con') canviar_mode('mod');	// canviar a mode modificar
						// modificar_peli();	// Fer efectiva la modificació sense haver de clicar el boto ok
					}
				});

				// Per nova peli, posar el focus al títol
				if (mode_act == 'add') $('#info_peli_2').focus();


			}

		}


		function tractar_link(elem, nom_prop) {

			var desti = elem.attr('data_href');

			if (mode_act == 'con' && desti != '') {
				window.open(desti, '_blank');	// Saltar a l'enllaç

			} else { // Editar l'enllaç

				var valor = prompt(nom_prop + ' :', desti);
				if (valor != null) {

					if (mode_act == 'con') canviar_mode('mod');	// Si s'està editan en mode consulta, canviar a mode modificar

					elem.attr('data_href', valor);

					// Actualitzar la imatge
					if (valor == '') 	elem.attr('src', elem.attr('img2'));
					else 				elem.attr('src', elem.attr('img1'));

					// modificar_peli();	// Fer efectiva la modificació sense haver de clicar el boto ok
				}
			}
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
					<td id="peli_9999" style="width:500px">
							titol pelicula
					</td>
					<td style="padding: 0px;">
						<img src="./Img/imdb_petit.png">
					</td>
				</tr>

				<tr>
					<td style="width:30px"> 9999 </td>
					<td id="peli_9999"
						onmouseenter="$(this).toggleClass('fila_seleccionada');"
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




		<div style="width: 100%; height: 30px;
				display: flex; flex-flow: column wrap;
				align-items: center;
				align-content: space-between;
				">

			<h2 id="info_peli_titol">ID : Titol peli</h2>



			<div>

				<img id="id_img_add_peli" src="./Img/icons/add.png"  style="width:22px; height:22px; margin-left:15px;" title="Afegir">

				<img id="id_img_del_peli" src="./Img/icons/del.png"  style="width:22px; height:22px; margin-left:15px;" title="Eliminar">

				<img id="id_img_mod_peli" src="./Img/icons/edit.png" style="width:22px; height:22px; margin-left:15px;" title="Modificar">

			</div>

		</div>

		<div id="id_div_info_peli" style="
			height: 50%; padding:10px;
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

				<img id="info_peli_imatge_caratula" src=""
					height="350" width="250"
					style="border: solid 1px #333333; " >


				<div style="margin-left:10px; ">

					<input id="info_peli_1" value="¿?" style="display:none; width:28px;  text-align: center; margin-right:10px;" />


					<p> Títol :  </br>
						<input class='info_peli info_peli_input'
								id="info_peli_2" value=""
								style="width:250px; margin-top: 2px;" />
					</p>

					<p> Títol original : <br/>
						<input class='info_peli info_peli_input'
								id="info_peli_3" value=""
								style="width:250px; margin-top: 2px;" />
					</p>

					<p> Idioma audio :
						<select class='info_peli info_peli_sel' id="info_peli_4" name="info_peli_4" style="margin-right: 15px;" disabled>
							<option value="" selected></option>
							<option value="ESP">ESP</option>
							<option value="CAT">CAT</option>
							<option value="ENG">ENG</option>
							<option value="LAT">LAT</option>
						</select>
					</p>

					<p> Idioma subtitols :
						<select class='info_peli info_peli_sel' id="info_peli_5" name="info_peli_5" disabled>
							<option value="" selected></option>
							<option value="ESP">ESP</option>
							<option value="CAT">CAT</option>
							<option value="ENG">ENG</option>
							<option value="LAT">LAT</option>
						</select>
					</p>

					<p> Vídeo :
						<select class='info_peli info_peli_sel' id="info_peli_6" name="info_peli_6" style="margin-right: 15px;" disabled>
							<option value="" selected></option>
							<option value="BR-Rip">BR-Rip</option>
							<option value="DVD-Rip">DVD-Rip</option>
							<option value="VHS-Rip">VHS-Rip</option>
							<option value="TS-Screener">TS-Screener</option>
						</select>
					</p>

					<p> Audio :
						<select class='info_peli info_peli_sel' id="info_peli_7" name="info_peli_7" disabled>
							<option value="" selected></option>
							<option value="BR-Rip">BR-Rip</option>
							<option value="DVD-Rip">DVD-Rip</option>
							<option value="VHS-Rip">VHS-Rip</option>
							<option value="TS-Screener">TS-Screener</option>
						</select>
					</p>

					<p> Any estrena : 		<input class='info_peli info_peli_input' id="info_peli_8" value="any_estrena" 	style="width:50px;  text-align: center; margin-right:15px;"/> </p>
					<p> Director : 			<input class='info_peli info_peli_input' id="info_peli_9" value="director" 		style="width:140px; text-align: center;"/> </p>

					<img src="" id="id_link_imdb" style="width:80px;"
						 data_href=""
						 img1="./Img/imdb_logo.png"
						 img2="./Img/imdb_logo2.png">

					<img src="" id="id_link_film" style="width:150px;"
						 data_href=""
						 img1="./Img/filmaffinity_logo.png"
						 img2="./Img/filmaffinity_logo2.png">


					<br/> </br> </br>

				</div>
			</div>

		</div>



		<div id="id_div_arxius_peli" style="
			position: relative; left: 0px; top: 10px;
			height: 25%;
			overflow-x: hidden; overflow-y: auto;
			border: solid 1px #AAAAAA;
			padding:10px;
			background-color: #FFFFCC;
			">

			<table style="width: 570px; font-size: 10px;" >
				<thead> <tr> <th style="width: 500px; text-align: left;" > Fitxer </th> <th> MB </th> </tr> </thead>

				<tbody>

					<tr>
						<td id="nom_fitxer_0"
							onclick="editar_fitxer(this, 'id_peli', 'num_arxiu');"
							onmouseenter="this.style.cursor = 'pointer';"
							onmouseleave="this.style.cursor = 'initial'"
							>
							nom_arxiu
						</td>
						<td> 700 MB </td>
					</tr>
				</tbody>
			</table>


		</div>

		<div style="
			position: relative; left: 0px; top: 10px;
			overflow: hidden;
			margin-top: 10px;
			display: flex; flex-flow: row-reverse wrap; align-items: center;
			">

			<div id="id_div_ok" class="boto_accio_on" style="margin-left:10px;">
				<p id="id_boto_ok"> Ok </p>
			</div>

			<div id="id_div_cancelar" class="boto_accio_on">
				<p id="id_boto_cancelar"> Cancelar </p>
			</div>

		</div>

	</div>

</body>
</html>
