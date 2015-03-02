<?php 	// Classe per generar XML de respostes AJAX

/* Preparat per ser cridat com :

  include "class.xmlresponse.php";
  $xml = new xmlResponse();
  $xml->ini_xml();
  $xml->registre(
    array("camp1" => "valor1", "camp2" => "valor2", "camp3" => "valor3")
  );
  $xml->fi_xml();
  
  I generant un XML :
  
	<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
    <resposta>
		<camp1>valor1</camp1>
		<camp2>valor2</camp2>
		<camp3>valor3</camp3>
    </resposta>
  
*/


class xmlResponse {

  function ini_xml($tipus_res) {
    header('Content-Type: text/xml');
    echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
    echo "<resposta>\n";
	if ($tipus_res == 'ok') {	echo "<resultat>ok</resultat>\n"; }
	else 					{	echo "<resultat>ko</resultat>\n"; }
  }

  function registre($params = array()) {

    if ($params) {
		foreach($params as $key => $val) {
			echo "    <$key>" . htmlspecialchars($val) . "</$key>\n";
		}
    }
  }

  function fi_xml() {
    echo "</resposta>\n";
    exit;
  }

}
?>