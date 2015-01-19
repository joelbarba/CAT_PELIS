<html>
<head>
	<meta charset="UTF-8">
	<title> Llista de Pelis </title>
	<style>
	
		html,body,input,select{
			font:normal normal normal 10px/10px Verdana;
		}
		
		td { 
			border: solid 0px #DDDDDD; 
			background-color: #DDDDDD;
			padding: 3px;
		}
	</style>

	<script language="Javascript">
		
		function seleccionar_peli(id_peli) {
			alert ("Seleccionant peli amb id = " + id_peli);
			return false;
		}
		
	</script>
	
	<?php // Recuperar totes les pelicules
			
		$dbconn = pg_connect("host=localhost dbname=Cataleg_Pelis user=barba password=barba0001")
			or die('No s\'ha pogut connectar : ' . pg_last_error());
			
		$consulta = "
			select id_peli,
				   titol 			as titol_peli
			  from Pelis_Down
			 where id_peli > 0
			 order by id_peli";
			 
		$result = pg_query($dbconn, $consulta);	
	?>
	
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
						echo '<tr> <td style="width:30px"> '. ($x + 1) . ' </td> ';
						echo '<td id="peli_' .  pg_fetch_result($result, $x, "id_peli") . '" ';
						echo 'style="width:500px"> <a href="#" onclick="seleccionar_peli('.pg_fetch_result($result, $x, "id_peli").')" >';
						echo pg_fetch_result($result, $x, "titol_peli");
						echo '</a> </td> </tr>';
						$x++;
					}
				?>
			
			</tbody>		
		</table>
	
	</div>


	
	
	<div style="
		position: fixed; left: 750px; top: 30px;
		width: 650px; height: 700px;
		overflow-x: hidden; overflow-y: scroll;
		border: solid 1px #AAAAAA; 
		padding:10px;
		background-color: #FFFFEE;
		">

		<h2> La Venganza de los novatos </h2>
		
		<img src="./Img/id_0001.jpg" alt="porta" height="350" width="250">

	</div>		
	
</body>
</html>
