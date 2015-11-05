<?php 	// Classe per generar XML de respostes AJAX

/* Preparat per ser cridat com :

  include "class.xmlresponse.php";
  $xml = new xmlResponse();
  $xml->ini_xml();
  $xml->registre(array("resultat" => "ok", "desc_error" => "tot bé", "camp3" => "valor3"));
  $xml->obrir_tag("clau1");
	$xml->registre(array("camp4" => "valor4", "camp5" => "valor5"));
  $xml->tancar_tag();
  $xml->fi_xml();
  
  I generant un XML :
  
	<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
    <resposta>
		<resultat>		ok		</resultat>
		<desc_error>	tot bé	</desc_error>
		<camp3>			valor3	</camp3>
		<clau1>
			<camp4>	valor4	</camp4>
			<camp5>	valor5	</camp5>
		</clau1>
    </resposta>
  
*/


class xmlResponse {
	
	public $arbre_nodes = array("primer", "segon");
	
	// Crea el inici del document XML, obrint el tag gereral <resposta>
	function ini_xml($tipus_res) {
		header('Content-Type: text/xml');
		echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
		
		$this->obrir_tag("resposta");
		
		if ($tipus_res == 'ok') {	$this->crear_tag("resultat", "ok"); }
		else 					{	$this->crear_tag("resultat", "ko"); }
		
	}
	
	function crear_tag($nom, $valor) {
		echo $this->tab() . "<$nom>";
		echo $valor;
		echo "</$nom>\n";
	}

	function registre($params = array()) {

		if ($params) {
			foreach($params as $key => $val) {
				echo $this->tab() . "<$key>" . htmlspecialchars($val) . "</$key>\n";
			}
		}
	}
  
	function obrir_tag($nom) {
		echo $this->tab() . "<$nom>\n";		
		$this->arbre_nodes[] = $nom;
	}
  
	// Es deixa el paràmetre $nom, però no cal (s'utilitza el array de nodes oberts)
	function tancar_tag($nom) {
		$ultim_node = count($this->arbre_nodes) - 1;
		$nom_node = $this->arbre_nodes[$ultim_node];
		
		unset($this->arbre_nodes[$ultim_node]); // Elimina l'últim element de l'array
		$this->arbre_nodes = array_values($this->arbre_nodes);
		
		
		// echo $this->tab() . "</$nom>\n";
		echo $this->tab() . "</" . $nom_node . ">\n";
		
	}
	
	function tab() {
		$tab = "";
		for ($t = 0; $t <= count($this->arbre_nodes); $t++) { $tab = $tab . "    "; } 
		return $tab;
	}

	// Tanca el document XML
	function fi_xml() {
		$this->tancar_tag("resposta");
	}

}
?>