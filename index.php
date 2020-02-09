<?php
// function array_to_xml($array, &$xml) {        
// 	foreach($array as $key => $value) {               
// 		if(is_array($value)) {            
// 			if(!is_numeric($key)){
// 				$subnode = $xml->addChild($key);
// 				array_to_xml($value, $subnode);
// 			} else {
// 				array_to_xml($value, $subnode);
// 			}
// 		} else {
// 			$xml->addChild($key, $value);
// 		}
// 	}        
// }
function array_to_xml( $data, &$xml_data ) {
    foreach( $data as $key => $value ) {
        if( is_array($value) ) {
            if( is_numeric($key) ){
                $key = 'item'.$key; //dealing with <0/>..<n/> issues
            }
            $subnode = $xml_data->addChild($key);
            array_to_xml($value, $subnode);
        } else {
            $xml_data->addChild("$key",htmlspecialchars("$value"));
        }
     }
}
function array_to_xml1($array, &$xml) {
    foreach($array as $key => $value) {
        if(is_array($value)) {
            if(!is_numeric($key)){
                $subnode = $xml->addChild("$key");
                array_to_xml($value, $subnode);
            } else {
                array_to_xml($value, $xml);
            }
        } else {
            $xml->addChild("$key","$value");
        }
    }
}
require 'flight/Flight.php';
require 'jsonindent.php';
Flight::register('db', 'Database', array('elektronsa_prodavnica'));
$json_podaci = file_get_contents("php://input");
Flight::set('json_podaci', $json_podaci );
Flight::route('/', function(){
    echo 'hello world!';
});

Flight::route('GET /paket.json', function(){
	header ("Content-Type: application/json; charset=utf-8");
	$db = Flight::db();
	$db->select();
	$niz=array();
	while ($red=$db->getResult()->fetch_object()){
		$niz[] = $red;
	}
	//JSON_UNESCAPED_UNICODE parametar je uveden u PHP verziji 5.4
	//Omogućava Unicode enkodiranje JSON fajla
	//Bez ovog parametra, vrši se escape Unicode karaktera
	//Na primer, slovo č će biti \u010
	//print_r($niz);
	$json_niz = json_encode ($niz,JSON_UNESCAPED_UNICODE);
	echo indent($json_niz);
	return false;
});
Flight::route('GET /paket.xml', function(){
	header ("Content-Type: application/xml; charset=utf-8");
	$db = Flight::db();
	$db->select();
	$niz=array();
	$xml = new SimpleXMLElement("<?xml version=\"1.0\"?><paketi></paketi>");
	while ($red=$db->getResult()->fetch_object()){
		$niz[] = $red;
		$node = $xml->addChild('paket');

// function call to convert array to xml
	array_to_xml1($red, $node);
	}
	//JSON_UNESCAPED_UNICODE parametar je uveden u PHP verziji 5.4
	//Omogućava Unicode enkodiranje JSON fajla
	//Bez ovog parametra, vrši se escape Unicode karaktera
	//Na primer, slovo č će biti \u010
	// CREATING XML OBJECT

	

// display XML to screen
	echo $xml->asXML();
	

// function to convert an array to XML using SimpleXML

	
});
Flight::route('GET /paket/@id.json', function($id){
	header ("Content-Type: application/json; charset=utf-8");
	$db = Flight::db();
	$db->select("paket", "*", null,null,null,null,null,null,null,null,null,null,null,null,"paket_id = ".$id, null);
	$red=$db->getResult()->fetch_object();
	//JSON_UNESCAPED_UNICODE parametar je uveden u PHP verziji 5.4
	//Omogućava Unicode enkodiranje JSON fajla
	//Bez ovog parametra, vrši se escape Unicode karaktera
	//Na primer, slovo č će biti \u010
	$json_niz = json_encode ($red,JSON_UNESCAPED_UNICODE);
	echo indent($json_niz);
	return false;
});
Flight::route('GET /paket/@id.xml', function($id){
	header ("Content-Type: application/xml; charset=utf-8");
	$db = Flight::db();
	$db->select("paket", "*", null,null,null,null,null,null,null,null,null,null,null,null,"paket_id = ".$id, null);
	$red=$db->getResult()->fetch_object();
	//JSON_UNESCAPED_UNICODE parametar je uveden u PHP verziji 5.4
	//Omogućava Unicode enkodiranje JSON fajla
	//Bez ovog parametra, vrši se escape Unicode karaktera
	//Na primer, slovo č će biti \u010
	
	
	// EXAMPLE ARRAY
	
	
	// CREATING XML OBJECT
	$xml = new SimpleXMLElement('<?xml version="1.0"?><paket></paket>'); 
	array_to_xml($red, $xml);
	
	// TO PRETTY PRINT OUTPUT
	$domxml = new DOMDocument('1.0');
	$domxml->preserveWhiteSpace = false;
	$domxml->formatOutput = true;
	$domxml->loadXML($xml->asXML());
	
	echo $domxml->saveXML();
});
Flight::route('GET /korisnik.json', function(){
	header ("Content-Type: application/json; charset=utf-8");
	$db = Flight::db();
	$db->select("korsinik", "*", null,null,null,null,null,null,null,null,null,null, null);
	$niz=array();
	while ($red=$db->getResult()->fetch_object()){
		$niz[] = $red;
	}
	//JSON_UNESCAPED_UNICODE parametar je uveden u PHP verziji 5.4
	//Omogućava Unicode enkodiranje JSON fajla
	//Bez ovog parametra, vrši se escape Unicode karaktera
	//Na primer, slovo č će biti \u010
	$json_niz = json_encode ($niz,JSON_UNESCAPED_UNICODE);
	echo indent($json_niz);
	return false;
});
Flight::route('GET /korisnik.xml', function(){
	header ("Content-Type: application/xml; charset=utf-8");
	$db = Flight::db();
	$db->select("korsinik", "*", null,null,null,null,null,null,null,null,null,null, null);
	$niz=array();
	$xml = new SimpleXMLElement("<?xml version=\"1.0\"?><korisnici></korisnici>");
	while ($red=$db->getResult()->fetch_object()){
	$niz[] = $red;
	$node = $xml->addChild('korisnik');

// function call to convert array to xml
array_to_xml1($red, $node);
//JSON_UNESCAPED_UNICODE parametar je uveden u PHP verziji 5.4
//Omogućava Unicode enkodiranje JSON fajla
//Bez ovog parametra, vrši se escape Unicode karaktera
//Na primer, slovo č će biti \u010
// CREATING XML OBJECT
	}


// display XML to screen
echo $xml->asXML();

});



Flight::route('GET /korisnik/@id.json', function($id){
	header ("Content-Type: application/json; charset=utf-8");
	$db = Flight::db();
	$db->select("korsinik", "*", null,null,null,null,null,null,null,null,null,null,null,null,"korisnik_id = ".$id, null);
	$red=$db->getResult()->fetch_object();
	//JSON_UNESCAPED_UNICODE parametar je uveden u PHP verziji 5.4
	//Omogućava Unicode enkodiranje JSON fajla
	//Bez ovog parametra, vrši se escape Unicode karaktera
	//Na primer, slovo č će biti \u010
	$json_niz = json_encode ($red,JSON_UNESCAPED_UNICODE);
	echo indent($json_niz);
	return false;
});
Flight::route('GET /korisnik/@id.xml', function($id){
	header ("Content-Type: application/xml; charset=utf-8");
	$db = Flight::db();
	$db->select("korsinik", "*", null,null,null,null,null,null,null,null,null,null,null,null,"korisnik_id = ".$id, null);
	$red=$db->getResult()->fetch_object();
	//JSON_UNESCAPED_UNICODE parametar je uveden u PHP verziji 5.4
	//Omogućava Unicode enkodiranje JSON fajla
	//Bez ovog parametra, vrši se escape Unicode karaktera
	//Na primer, slovo č će biti \u010
	
	
	// EXAMPLE ARRAY
	
	
	// CREATING XML OBJECT
	$xml = new SimpleXMLElement('<?xml version="1.0"?><korisnik></korisnik>'); 
	array_to_xml($red, $xml);
	
	// TO PRETTY PRINT OUTPUT
	$domxml = new DOMDocument('1.0');
	$domxml->preserveWhiteSpace = false;
	$domxml->formatOutput = true;
	$domxml->loadXML($xml->asXML());
	
	echo $domxml->saveXML();
});
Flight::route('GET /model_telefona.json', function(){
	header ("Content-Type: application/json; charset=utf-8");
	$db = Flight::db();
	$db->select("model_telefona", "*", null,null,null,null,null,null,null,null,null,null, null);
	$niz=array();
	while ($red=$db->getResult()->fetch_object()){
		$niz[] = $red;
	}
	//JSON_UNESCAPED_UNICODE parametar je uveden u PHP verziji 5.4
	//Omogućava Unicode enkodiranje JSON fajla
	//Bez ovog parametra, vrši se escape Unicode karaktera
	//Na primer, slovo č će biti \u010
	$json_niz = json_encode ($niz,JSON_UNESCAPED_UNICODE);
	echo indent($json_niz);
	return false;
});
Flight::route('GET /model_telefona.xml', function(){
	header ("Content-Type: application/xml; charset=utf-8");
	$db = Flight::db();
	$db->select("model_telefona", "*", null,null,null,null,null,null,null,null,null,null, null);
	$niz=array();
	$xml = new SimpleXMLElement("<?xml version=\"1.0\"?><modeli></modeli>");
	while ($red=$db->getResult()->fetch_object()){
	$niz[] = $red;
	$node = $xml->addChild('model');
	
	// function call to convert array to xml
	array_to_xml1($red, $node);
	//JSON_UNESCAPED_UNICODE parametar je uveden u PHP verziji 5.4
	//Omogućava Unicode enkodiranje JSON fajla
	//Bez ovog parametra, vrši se escape Unicode karaktera
	//Na primer, slovo č će biti \u010
	// CREATING XML OBJECT
	}
	
	
	// display XML to screen
	echo $xml->asXML();
});


Flight::route('GET /model_telefona/@id.json', function($id){
	header ("Content-Type: application/json; charset=utf-8");
	$db = Flight::db();
	$db->select("model_telefona", "*", null,null,null,null,null,null,null,null,null,null,null,null,"model_id =".$id, null);
	$red=$db->getResult()->fetch_object();
	//JSON_UNESCAPED_UNICODE parametar je uveden u PHP verziji 5.4
	//Omogućava Unicode enkodiranje JSON fajla
	//Bez ovog parametra, vrši se escape Unicode karaktera
	//Na primer, slovo č će biti \u010
	$json_niz = json_encode ($red,JSON_UNESCAPED_UNICODE);
	echo indent($json_niz);
	return false;
});
Flight::route('GET /model_telefona/@id.xml', function($id){
	header ("Content-Type: application/xml; charset=utf-8");
	$db = Flight::db();
	$db->select("model_telefona", "*", null,null,null,null,null,null,null,null,null,null,null,null,"model_id =".$id, null);
	$red=$db->getResult()->fetch_object();
	//JSON_UNESCAPED_UNICODE parametar je uveden u PHP verziji 5.4
	//Omogućava Unicode enkodiranje JSON fajla
	//Bez ovog parametra, vrši se escape Unicode karaktera
	//Na primer, slovo č će biti \u010
	$xml = new SimpleXMLElement('<?xml version="1.0"?><model></model>'); 
	array_to_xml($red, $xml);
	
	// TO PRETTY PRINT OUTPUT
	$domxml = new DOMDocument('1.0');
	$domxml->preserveWhiteSpace = false;
	$domxml->formatOutput = true;
	$domxml->loadXML($xml->asXML());
	
	echo $domxml->saveXML();
});

// Flight::route('GET /telefon.json', function(){
// 	header ("Content-Type: application/json; charset=utf-8");
// 	$db = Flight::db();
// 	$db->select("telefon", "*", "model_telefona",null,null,null,"model_id",null,null,"model_id",null,null,null,null,null, null);
// 	$niz=array();
// 	while ($red=$db->getResult()->fetch_object()){
// 		$niz[] = $red;
// 	}
// 	//JSON_UNESCAPED_UNICODE parametar je uveden u PHP verziji 5.4
// 	//Omogućava Unicode enkodiranje JSON fajla
// 	//Bez ovog parametra, vrši se escape Unicode karaktera
// 	//Na primer, slovo č će biti \u010
// 	$json_niz = json_encode ($niz,JSON_UNESCAPED_UNICODE);
// 	echo indent($json_niz);
// 	return false;
// });
// Flight::route('GET /telefon/@id.json', function($id){
// 	header ("Content-Type: application/json; charset=utf-8");
// 	$db = Flight::db();
// 	$db->select("telefon", "*", "model_telefona",null,null,null,"model_id",null,null,"model_id",null,null,null,null,"telefon_id = ".$id, null);
// 	$red=$db->getResult()->fetch_object();
// 	//JSON_UNESCAPED_UNICODE parametar je uveden u PHP verziji 5.4
// 	//Omogućava Unicode enkodiranje JSON fajla
// 	//Bez ovog parametra, vrši se escape Unicode karaktera
// 	//Na primer, slovo č će biti \u010
// 	$json_niz = json_encode ($red,JSON_UNESCAPED_UNICODE);
// 	echo indent($json_niz);
// 	return false;
// });
Flight::route('GET /ugovor.json', function(){
	header ("Content-Type: application/json; charset=utf-8");
	$db = Flight::db();
	$db->select("ugovor", "*", "korsinik","paket","model_telefona",null,"korisnik_id","paket_id","model_id","korisnik_id","paket_id","model_id","model_id","model_id",null, null);
	$niz=array();
	while ($red=$db->getResult()->fetch_object()){
		$niz[] = $red;
	}
	//JSON_UNESCAPED_UNICODE parametar je uveden u PHP verziji 5.4
	//Omogućava Unicode enkodiranje JSON fajla
	//Bez ovog parametra, vrši se escape Unicode karaktera
	//Na primer, slovo č će biti \u010
	$json_niz = json_encode ($niz,JSON_UNESCAPED_UNICODE);
	echo indent($json_niz);
	return false;
});
Flight::route('GET /ugovor.xml', function(){
	header ("Content-Type: application/xml; charset=utf-8");
	$db = Flight::db();
	$db->select("ugovor", "*", "korsinik","paket","model_telefona",null,"korisnik_id","paket_id","model_id","korisnik_id","paket_id","model_id","model_id","model_id",null, null);
	$niz=array();
	$xml = new SimpleXMLElement("<?xml version=\"1.0\"?><ugovori></ugovori>");
	while ($red=$db->getResult()->fetch_object()){
	$niz[] = $red;
	$node = $xml->addChild('ugovor');
	
	// function call to convert array to xml
	array_to_xml1($red, $node);
	//JSON_UNESCAPED_UNICODE parametar je uveden u PHP verziji 5.4
	//Omogućava Unicode enkodiranje JSON fajla
	//Bez ovog parametra, vrši se escape Unicode karaktera
	//Na primer, slovo č će biti \u010
	// CREATING XML OBJECT
	}
	
	
	// display XML to screen
	echo $xml->asXML();
});
Flight::route('GET /ugovor/@id.json', function($id){
	header ("Content-Type: application/json; charset=utf-8");
	$db = Flight::db();
	$db->select("ugovor", "*", "korsinik","paket","model_telefona",null,"korisnik_id","paket_id","model_id","korisnik_id","paket_id","model_id",null,null,"ugovor_id = ".$id, null);
	$red=$db->getResult()->fetch_object();
	//JSON_UNESCAPED_UNICODE parametar je uveden u PHP verziji 5.4
	//Omogućava Unicode enkodiranje JSON fajla
	//Bez ovog parametra, vrši se escape Unicode karaktera
	//Na primer, slovo č će biti \u010
	$json_niz = json_encode ($red,JSON_UNESCAPED_UNICODE);
	echo indent($json_niz);
	return false;
});
Flight::route('GET /ugovor/@id.xml', function($id){
	header ("Content-Type: application/xml; charset=utf-8");
	$db = Flight::db();
	$db->select("ugovor", "*", "korsinik","paket","model_telefona",null,"korisnik_id","paket_id","model_id","korisnik_id","paket_id","model_id",null,null,"ugovor_id = ".$id, null);
	$red=$db->getResult()->fetch_object();
	//JSON_UNESCAPED_UNICODE parametar je uveden u PHP verziji 5.4
	//Omogućava Unicode enkodiranje JSON fajla
	//Bez ovog parametra, vrši se escape Unicode karaktera
	//Na primer, slovo č će biti \u010
	$xml = new SimpleXMLElement('<?xml version="1.0"?><ugovor></ugovor>'); 
	array_to_xml($red, $xml);
	
	// TO PRETTY PRINT OUTPUT
	$domxml = new DOMDocument('1.0');
	$domxml->preserveWhiteSpace = false;
	$domxml->formatOutput = true;
	$domxml->loadXML($xml->asXML());
	
	echo $domxml->saveXML();
});

Flight::route('POST /korisnik', function(){
	header ("Content-Type: application/json; charset=utf-8");
	$db = Flight::db();
	$podaci_json = Flight::get("json_podaci");
	$podaci = json_decode ($podaci_json);
	if ($podaci == null){
	$odgovor["poruka"] = "Niste prosledili podatke";
	$json_odgovor = json_encode ($odgovor);
	echo $json_odgovor;
	return false;
	} else {
	if (!property_exists($podaci,'ime')||!property_exists($podaci,'prezime')||!property_exists($podaci,'korisnicko_ime')||!property_exists($podaci,'sifra')||!property_exists($podaci,'email')||!property_exists($podaci,'kontakt_telefon')||!property_exists($podaci,'adresa')){
			$odgovor["poruka"] = "Niste prosledili korektne podatke";
			$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
			echo $json_odgovor;
			return false;
	
	} else {
			$podaci_query = array();
			foreach ($podaci as $k=>$v){
				$v = "'".$v."'";
				$podaci_query[$k] = $v;
			}
			if ($db->insert("korsinik", "ime, prezime, korisnicko_ime, sifra, email, kontakt_telefon, adresa", array($podaci_query["ime"], $podaci_query["prezime"], $podaci_query["korisnicko_ime"],$podaci_query["sifra"], $podaci_query["email"], $podaci_query["kontakt_telefon"],$podaci_query["adresa"]))){
				$odgovor["poruka"] = "Novost je uspešno ubačena";
				$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
				echo $json_odgovor;
				return false;
			} else {
				$odgovor["poruka"] = "Došlo je do greške pri ubacivanju novosti";
				$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
				echo $json_odgovor;
				return false;
			}
	}
	}	
	}
);

Flight::route('POST /paket', function(){
	header ("Content-Type: application/json; charset=utf-8");
	$db = Flight::db();
	$podaci_json = Flight::get("json_podaci");
	$podaci = json_decode ($podaci_json);
	if ($podaci == null){
	$odgovor["poruka"] = "Niste prosledili podatke";
	$json_odgovor = json_encode ($odgovor);
	echo $json_odgovor;
	return false;
	} else {
	if (!property_exists($podaci,'naziv_paketa')||!property_exists($podaci,'broj_minuta')||!property_exists($podaci,'broj_sms')||!property_exists($podaci,'broj_mb')||!property_exists($podaci,'cena')||!property_exists($podaci,'url')){
			$odgovor["poruka"] = "Niste prosledili korektne podatke";
			$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
			echo $json_odgovor;
			return false;
	
	} else {
			$podaci_query = array();
			foreach ($podaci as $k=>$v){
				$v = "'".$v."'";
				$podaci_query[$k] = $v;
			}
			if ($db->insert("paket", "naziv_paketa, broj_minuta, broj_sms, broj_mb, cena, url", array($podaci_query["naziv_paketa"], $podaci_query["broj_minuta"], $podaci_query["broj_sms"],$podaci_query["broj_mb"], $podaci_query["cena"], $podaci_query["url"]))){
				$odgovor["poruka"] = "Novost je uspešno ubačena";
				$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
				echo $json_odgovor;
				return false;
			} else {
				$odgovor["poruka"] = "Došlo je do greške pri ubacivanju novosti";
				$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
				echo $json_odgovor;
				return false;
			}
	}
	}	
	}
);

Flight::route('POST /model_telefona', function(){
	header ("Content-Type: application/json; charset=utf-8");
	$db = Flight::db();
	$podaci_json = Flight::get("json_podaci");
	$podaci = json_decode ($podaci_json);
	if ($podaci == null){
	$odgovor["poruka"] = "Niste prosledili podatke";
	$json_odgovor = json_encode ($odgovor);
	echo $json_odgovor;
	return false;
	} else {
	if (!property_exists($podaci,'proizvodjac')||!property_exists($podaci,'naziv')||!property_exists($podaci,'masa')||!property_exists($podaci,'dimenzije')||!property_exists($podaci,'kamera')||!property_exists($podaci,'procesor')||!property_exists($podaci,'baterija')||!property_exists($podaci,'oparativni_sistem')||!property_exists($podaci,'memorija')||!property_exists($podaci,'slika')){
			$odgovor["poruka"] = "Niste prosledili korektne podatke";
			$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
			echo $json_odgovor;
			return false;
	
	} else {
			$podaci_query = array();
			foreach ($podaci as $k=>$v){
				$v = "'".$v."'";
				$podaci_query[$k] = $v;
			}
			if ($db->insert("model_telefona", "proizvodjac, naziv, masa, dimenzije, kamera, procesor, baterija,oparativni_sistem,memorija, slika", array($podaci_query["model_telefona"], $podaci_query["proizvodjac"], $podaci_query["naziv"],$podaci_query["masa"], $podaci_query["dimenzije"], $podaci_query["kamera"],$podaci_query["procesor"], $podaci_query["baterija"], $podaci_query["oparativni_sistem"],$podaci_query["memorija"],$podaci_query["cena"]))){
				$odgovor["poruka"] = "Novost je uspešno ubačena";
				$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
				echo $json_odgovor;
				return false;
			} else {
				$odgovor["poruka"] = "Došlo je do greške pri ubacivanju novosti";
				$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
				echo $json_odgovor;
				return false;
			}
	}
	}	
	}
);

// Flight::route('POST /telefon', function(){
// 	header ("Content-Type: application/json; charset=utf-8");
// 	$db = Flight::db();
// 	$podaci_json = Flight::get("json_podaci");
// 	$podaci = json_decode ($podaci_json);
// 	if ($podaci == null){
// 	$odgovor["poruka"] = "Niste prosledili podatke";
// 	$json_odgovor = json_encode ($odgovor);
// 	echo $json_odgovor;
// 	return false;
// 	} else {
// 	if (!property_exists($podaci,'model_id')){
// 			$odgovor["poruka"] = "Niste prosledili korektne podatke";
// 			$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
// 			echo $json_odgovor;
// 			return false;
	
// 	} else {
// 			$podaci_query = array();
// 			foreach ($podaci as $k=>$v){
// 				$v = "'".$v."'";
// 				$podaci_query[$k] = $v;
// 			}
// 			if ($db->insert("telefon", "model_id", array($podaci_query["model_id"]))){
// 				$odgovor["poruka"] = "Novost je uspešno ubačena";
// 				$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
// 				echo $json_odgovor;
// 				return false;
// 			} else {
// 				$odgovor["poruka"] = "Došlo je do greške pri ubacivanju novosti";
// 				$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
// 				echo $json_odgovor;
// 				return false;
// 			}
// 	}
// 	}	
// 	}
// );

Flight::route('POST /ugovor', function(){
	header ("Content-Type: application/json; charset=utf-8");
	$db = Flight::db();
	$podaci_json = Flight::get("json_podaci");
	$podaci = json_decode ($podaci_json);
	if ($podaci == null){
	$odgovor["poruka"] = "Niste prosledili podatke";
	$json_odgovor = json_encode ($odgovor);
	echo $json_odgovor;
	return false;
	} else {
	if (!property_exists($podaci,'datum')||!property_exists($podaci,'trajanje_ugovora')||!property_exists($podaci,'model_id')||!property_exists($podaci,'paket_id')||!property_exists($podaci,'korisnik_id')){
			$odgovor["poruka"] = "Niste prosledili korektne podatke";
			$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
			echo $json_odgovor;
			return false;
	
	} else {
			$podaci_query = array();
			foreach ($podaci as $k=>$v){
				$v = "'".$v."'";
				$podaci_query[$k] = $v;
			}
			if ($db->insert("ugovor", "datum, trajanje_ugovora, model_id, paket_id, korisnik_id", array($podaci_query["datum"], $podaci_query["trajanje_ugovora"], $podaci_query["model_id"],$podaci_query["paket_id"], $podaci_query["korisnik_id"]))){
				$odgovor["poruka"] = "Novost je uspešno ubačena";
				$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
				echo $json_odgovor;
				return false;
			} else {
				$odgovor["poruka"] = "Došlo je do greške pri ubacivanju novosti";
				$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
				echo $json_odgovor;
				return false;
			}
	}
	}	
	}
);
Flight::route('PUT /korisnik/@id', function($id){
	header ("Content-Type: application/json; charset=utf-8");
	$db = Flight::db();
	$podaci_json = Flight::get("json_podaci");
	$podaci = json_decode ($podaci_json);
	if ($podaci == null){
	$odgovor["poruka"] = "Niste prosledili podatke";
	$json_odgovor = json_encode ($odgovor);
	echo $json_odgovor;
	} else {
	if (!property_exists($podaci,'ime')||!property_exists($podaci,'prezime')||!property_exists($podaci,'korisnicko_ime') ||!property_exists($podaci,'sifra')||!property_exists($podaci,'email') ||!property_exists($podaci,'kontakt_telefon')||!property_exists($podaci,'adresa')||!property_exists($podaci,'admin')){
			$odgovor["poruka"] = "Niste prosledili korektne podatke";
			$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
			echo $json_odgovor;
			return false;
	
	} else {
			$podaci_query = array();
			foreach ($podaci as $k=>$v){
				$v = "'".$v."'";
				$podaci_query[$k] = $v;
			}
			if ($db->update("korsinik", "korisnik_id",$id, array('ime','prezime','korisnicko_ime','sifra','email','kontakt_telefon','adresa','admin'),array($podaci->ime, $podaci->prezime,$podaci->korisnicko_ime, $podaci->sifra, $podaci->email, $podaci->kontakt_telefon,$podaci->adresa,$podaci->admin))){
				$odgovor["poruka"] = "Novost je uspešno izmenjena";
				$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
				echo $json_odgovor;
				return false;
			} else {
				$odgovor["poruka"] = "Došlo je do greške pri izmeni novosti";
				$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
				echo $json_odgovor;
				return false;
			}
	}
	}	




});
Flight::route('PUT /paket/@id', function($id){
	header ("Content-Type: application/json; charset=utf-8");
	$db = Flight::db();
	$podaci_json = Flight::get("json_podaci");
	$podaci = json_decode ($podaci_json);
	if ($podaci == null){
	$odgovor["poruka"] = "Niste prosledili podatke";
	$json_odgovor = json_encode ($odgovor);
	echo $json_odgovor;
	} else {
	if (!property_exists($podaci,'naziv_paketa')||!property_exists($podaci,'broj_minuta')||!property_exists($podaci,'broj_sms')||!property_exists($podaci,'broj_mb')||!property_exists($podaci,'cena')||!property_exists($podaci,'url')){
			$odgovor["poruka"] = "Niste prosledili korektne podatke";
			$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
			echo $json_odgovor;
			return false;
	
	} else {
			$podaci_query = array();
			foreach ($podaci as $k=>$v){
				$v = "'".$v."'";
				$podaci_query[$k] = $v;
			}
			if ($db->update("paket", "paket_id",$id, array('naziv_paketa','broj_minuta','broj_sms','sifra','broj_mb','cena','url'),array($podaci->naziv_paketa, $podaci->broj_minuta,$podaci->korisnicko_ime, $podaci->broj_sms, $podaci->broj_mb, $podaci->cena,$podaci->url))){
				$odgovor["poruka"] = "Novost je uspešno izmenjena";
				$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
				echo $json_odgovor;
				return false;
			} else {
				$odgovor["poruka"] = "Došlo je do greške pri izmeni novosti";
				$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
				echo $json_odgovor;
				return false;
			}
	}
	}	




});
Flight::route('PUT /model_telefona/@id', function($id){
	header ("Content-Type: application/json; charset=utf-8");
	$db = Flight::db();
	$podaci_json = Flight::get("json_podaci");
	$podaci = json_decode ($podaci_json);
	if ($podaci == null){
	$odgovor["poruka"] = "Niste prosledili podatke";
	$json_odgovor = json_encode ($odgovor);
	echo $json_odgovor;
	} else {
	if (!property_exists($podaci,'proizvodjac')||!property_exists($podaci,'naziv')||!property_exists($podaci,'masa')||!property_exists($podaci,'dimenzije')||!property_exists($podaci,'kamera')||!property_exists($podaci,'procesor')||!property_exists($podaci,'baterija')||!property_exists($podaci,'oprativni_sistem')||!property_exists($podaci,'memorija')||!property_exists($podaci,'slika')){
			$odgovor["poruka"] = "Niste prosledili korektne podatke";
			//$odgovor["poruka"] =$podaci;
			$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
			echo $json_odgovor;
			return false;
	
	} else {
			$podaci_query = array();
			foreach ($podaci as $k=>$v){
				$v = "'".$v."'";
				$podaci_query[$k] = $v;
			}
			if ($db->update("model_telefona", "model_id",$id, array('proizvodjac','naziv','masa','dimenzije','kamera','procesor','baterija','oprativni_sistem','memorija','slika'),array($podaci->proizvodjac, $podaci->naziv,$podaci->masa, $podaci->dimenzije, $podaci->kamera, $podaci->procesor,$podaci->baterija, $podaci->oprativni_sistem, $podaci->memorija, $podaci->slika))){
				$odgovor["poruka"] = "Novost je uspešno izmenjena";
				$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
				echo $json_odgovor;
				return false;
			} else {
				$odgovor["poruka"] = "Došlo je do greške pri izmeni novosti";
				$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
				echo $json_odgovor;
				return false;
			}
	}
	}	




});
// Flight::route('PUT /telefon/@id', function($id){
// 	header ("Content-Type: application/json; charset=utf-8");
// 	$db = Flight::db();
// 	$podaci_json = Flight::get("json_podaci");
// 	$podaci = json_decode ($podaci_json);
// 	if ($podaci == null){
// 	$odgovor["poruka"] = "Niste prosledili podatke";
// 	$json_odgovor = json_encode ($odgovor);
// 	echo $json_odgovor;
// 	} else {
// 	if (!property_exists($podaci,'model_id')){
// 			$odgovor["poruka"] = "Niste prosledili korektne podatke";
// 			$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
// 			echo $json_odgovor;
// 			return false;
	
// 	} else {
// 			$podaci_query = array();
// 			foreach ($podaci as $k=>$v){
// 				$v = "'".$v."'";
// 				$podaci_query[$k] = $v;
// 			}
// 			if ($db->update("telefon", "telefon_id",$id, array('model_id'),array($podaci->model_id))){
// 				$odgovor["poruka"] = "Novost je uspešno izmenjena";
// 				$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
// 				echo $json_odgovor;
// 				return false;
// 			} else {
// 				$odgovor["poruka"] = "Došlo je do greške pri izmeni novosti";
// 				$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
// 				echo $json_odgovor;
// 				return false;
// 			}
// 	}
// 	}	




// });
Flight::route('PUT /ugovor/@id', function($id){
	header ("Content-Type: application/json; charset=utf-8");
	$db = Flight::db();
	$podaci_json = Flight::get("json_podaci");
	$podaci = json_decode ($podaci_json);
	if ($podaci == null){
	$odgovor["poruka"] = "Niste prosledili podatke";
	$json_odgovor = json_encode ($odgovor);
	echo $json_odgovor;
	} else {
	if (!property_exists($podaci,'datum')||!property_exists($podaci,'trajanje_ugovora')||!property_exists($podaci,'model_id')||!property_exists($podaci,'paket_id')||!property_exists($podaci,'korisnik_id')){
			$odgovor["poruka"] = "Niste prosledili korektne podatke";
			$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
			echo $json_odgovor;
			return false;
	
	} else {
			$podaci_query = array();
			foreach ($podaci as $k=>$v){
				$v = "'".$v."'";
				$podaci_query[$k] = $v;
			}
			if ($db->update("ugovor", "ugovor_id",$id, array('datum','trajanje_ugovora','model_id','paket_id','korisnik_id'),array($podaci->datum, $podaci->trajanje_ugovora,$podaci->model_id, $podaci->paket_id, $podaci->korisnik_id))){
				$odgovor["poruka"] = "Novost je uspešno izmenjena";
				$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
				echo $json_odgovor;
				return false;
			} else {
				$odgovor["poruka"] = "Došlo je do greške pri izmeni novosti";
				$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
				echo $json_odgovor;
				return false;
			}
	}
	}	




});
Flight::route('DELETE /korisnik/@id', function($id){
		header ("Content-Type: application/json; charset=utf-8");
		$db = Flight::db();
		if ($db->delete("korsinik","korisnik_id",$id)){
				$odgovor["poruka"] = "Novost je uspešno izbrisana";
				$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
				echo $json_odgovor;
				return false;
		} else {
				$odgovor["poruka"] = "Došlo je do greške prilikom brisanja novosti";
				$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
				echo $json_odgovor;
				return false;
		
		}		
				
});
Flight::route('DELETE /paket/@id', function($id){
	header ("Content-Type: application/json; charset=utf-8");
	$db = Flight::db();
	if ($db->delete("paket","paket_id",$id)){
			$odgovor["poruka"] = "Novost je uspešno izbrisana";
			$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
			echo $json_odgovor;
			return false;
	} else {
			$odgovor["poruka"] = "Došlo je do greške prilikom brisanja novosti";
			$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
			echo $json_odgovor;
			return false;
	
	}		
			
});
Flight::route('DELETE /model_telefona/@id', function($id){
	header ("Content-Type: application/json; charset=utf-8");
	$db = Flight::db();
	if ($db->delete("model_telefona","model_id",$id)){
			$odgovor["poruka"] = "Novost je uspešno izbrisana";
			$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
			echo $json_odgovor;
			return false;
	} else {
			$odgovor["poruka"] = "Došlo je do greške prilikom brisanja novosti";
			$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
			echo $json_odgovor;
			return false;
	
	}		
			
});
// Flight::route('DELETE /telefon/@id', function($id){
// 	header ("Content-Type: application/json; charset=utf-8");
// 	$db = Flight::db();
// 	if ($db->delete("telefon","telefon_id",$id)){
// 			$odgovor["poruka"] = "Novost je uspešno izbrisana";
// 			$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
// 			echo $json_odgovor;
// 			return false;
// 	} else {
// 			$odgovor["poruka"] = "Došlo je do greške prilikom brisanja novosti";
// 			$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
// 			echo $json_odgovor;
// 			return false;
	
// 	}		
			
// });
Flight::route('DELETE /ugovor/@id', function($id){
	header ("Content-Type: application/json; charset=utf-8");
	$db = Flight::db();
	if ($db->delete("ugovor","ugovor_id",$id)){
			$odgovor["poruka"] = "Novost je uspešno izbrisana";
			$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
			echo $json_odgovor;
			return false;
	} else {
			$odgovor["poruka"] = "Došlo je do greške prilikom brisanja novosti";
			$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
			echo $json_odgovor;
			return false;
	
	}		
			
});
// Flight::route('GET /kategorije.json', function(){
// 	header ("Content-Type: application/json; charset=utf-8");
// 	$db = Flight::db();
// 	$db->select("kategorije", "*", null, null, null, null, null);
// 	$niz=array();
// 	$i=0;
// 	while ($red=$db->getResult()->fetch_object()){
		
// 		$niz[$i]["id"] = $red->id;
// 		$niz[$i]["kategorija"] = $red->kategorija;
// 		$db_pomocna=new Database("rest");
// 		$db_pomocna->select("novosti", "*", null, null, null, "novosti.kategorija_id = ".$red->id, null);
// 		while ($red_pomocna=$db_pomocna->getResult()->fetch_object()){
// 			$niz[$i]["novosti"][]=$red_pomocna;
// 		}
// 		$i++;
// 	}
// 	//JSON_UNESCAPED_UNICODE parametar je uveden u PHP verziji 5.4
// 	//Omogućava Unicode enkodiranje JSON fajla
// 	//Bez ovog parametra, vrši se escape Unicode karaktera
// 	//Na primer, slovo č će biti \u010
// 	$json_niz = json_encode ($niz,JSON_UNESCAPED_UNICODE);
// 	echo indent($json_niz);
// 	return false;
// });
// Flight::route('GET /kategorije/@id.json', function($id){
// 	header ("Content-Type: application/json; charset=utf-8");
// 	$db = Flight::db();
// 	$db->select("kategorije", "*", null, null, null, "kategorije.id = ".$id, null);
// 	$niz=array();
	
// 	$red=$db->getResult()->fetch_object();
		
// 		$niz["id"] = $red->id;
// 		$niz["kategorija"] = $red->kategorija;
// 		$db_pomocna=new Database("rest");
// 		$db_pomocna->select("novosti", "*", null, null, null, "novosti.kategorija_id = ".$red->id, null);
// 		while ($red_pomocna=$db_pomocna->getResult()->fetch_object()){
// 		$niz["novosti"][]=$red_pomocna;
// 		}

// 	//JSON_UNESCAPED_UNICODE parametar je uveden u PHP verziji 5.4
// 	//Omogućava Unicode enkodiranje JSON fajla
// 	//Bez ovog parametra, vrši se escape Unicode karaktera
// 	//Na primer, slovo č će biti \u010
// 	$json_niz = json_encode ($niz,JSON_UNESCAPED_UNICODE);
// 	echo indent($json_niz);
// 	return false;


// });
// Flight::route('POST /novosti', function(){
// 	header ("Content-Type: application/json; charset=utf-8");
// 	$db = Flight::db();
// 	$podaci_json = Flight::get("json_podaci");
// 	$podaci = json_decode ($podaci_json);
// 	if ($podaci == null){
// 	$odgovor["poruka"] = "Niste prosledili podatke";
// 	$json_odgovor = json_encode ($odgovor);
// 	echo $json_odgovor;
// 	return false;
// 	} else {
// 	if (!property_exists($podaci,'naslov')||!property_exists($podaci,'tekst')||!property_exists($podaci,'kategorija_id')){
// 			$odgovor["poruka"] = "Niste prosledili korektne podatke";
// 			$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
// 			echo $json_odgovor;
// 			return false;
	
// 	} else {
// 			$podaci_query = array();
// 			foreach ($podaci as $k=>$v){
// 				$v = "'".$v."'";
// 				$podaci_query[$k] = $v;
// 			}
// 			if ($db->insert("novosti", "naslov, tekst, kategorija_id, datumvreme", array($podaci_query["naslov"], $podaci_query["tekst"], $podaci_query["kategorija_id"], 'NOW()'))){
// 				$odgovor["poruka"] = "Novost je uspešno ubačena";
// 				$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
// 				echo $json_odgovor;
// 				return false;
// 			} else {
// 				$odgovor["poruka"] = "Došlo je do greške pri ubacivanju novosti";
// 				$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
// 				echo $json_odgovor;
// 				return false;
// 			}
// 	}
// 	}	
// 	}
// );
// Flight::route('POST /kategorije', function(){
// 	header ("Content-Type: application/json; charset=utf-8");
// 	$db = Flight::db();
// 	$podaci_json = Flight::get("json_podaci");
// 	$podaci = json_decode ($podaci_json);
// 	if ($podaci == null){
// 	$odgovor["poruka"] = "Niste prosledili podatke";
// 	$json_odgovor = json_encode ($odgovor);
// 	echo $json_odgovor;
// 	} else {
// 	if (!property_exists($podaci,'kategorija')){
// 			$odgovor["poruka"] = "Niste prosledili korektne podatke";
// 			$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
// 			echo $json_odgovor;
// 			return false;
	
// 	} else {
// 			$podaci_query = array();
// 			foreach ($podaci as $k=>$v){
// 				$v = "'".$v."'";
// 				$podaci_query[$k] = $v;
// 			}
// 			if ($db->insert("kategorije", "kategorija", array($podaci_query["kategorija"]))){
// 				$odgovor["poruka"] = "Kategorija je uspešno ubačena";
// 				$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
// 				echo $json_odgovor;
// 				return false;
// 			} else {
// 				$odgovor["poruka"] = "Došlo je do greške pri ubacivanju novosti";
// 				$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
// 				echo $json_odgovor;
// 				return false;
// 			}
// 	}
// 	}	


// });
// Flight::route('PUT /novosti/@id', function($id){
// 	header ("Content-Type: application/json; charset=utf-8");
// 	$db = Flight::db();
// 	$podaci_json = Flight::get("json_podaci");
// 	$podaci = json_decode ($podaci_json);
// 	if ($podaci == null){
// 	$odgovor["poruka"] = "Niste prosledili podatke";
// 	$json_odgovor = json_encode ($odgovor);
// 	echo $json_odgovor;
// 	} else {
// 	if (!property_exists($podaci,'naslov')||!property_exists($podaci,'tekst')||!property_exists($podaci,'kategorija_id')){
// 			$odgovor["poruka"] = "Niste prosledili korektne podatke";
// 			$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
// 			echo $json_odgovor;
// 			return false;
	
// 	} else {
// 			$podaci_query = array();
// 			foreach ($podaci as $k=>$v){
// 				$v = "'".$v."'";
// 				$podaci_query[$k] = $v;
// 			}
// 			if ($db->update("novosti", $id, array('naslov','tekst','kategorija_id'),array($podaci->naslov, $podaci->tekst,$podaci->kategorija_id))){
// 				$odgovor["poruka"] = "Novost je uspešno izmenjena";
// 				$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
// 				echo $json_odgovor;
// 				return false;
// 			} else {
// 				$odgovor["poruka"] = "Došlo je do greške pri izmeni novosti";
// 				$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
// 				echo $json_odgovor;
// 				return false;
// 			}
// 	}
// 	}	




// });
// Flight::route('PUT /kategorije/@id', function($id){
// 	header ("Content-Type: application/json; charset=utf-8");
// 	$db = Flight::db();
// 	$podaci_json = Flight::get("json_podaci");
// 	$podaci = json_decode ($podaci_json);
// 	if ($podaci == null){
// 	$odgovor["poruka"] = "Niste prosledili podatke";
// 	$json_odgovor = json_encode ($odgovor);
// 	echo $json_odgovor;
// 	} else {
// 	if (!property_exists($podaci,'kategorija')){
// 			$odgovor["poruka"] = "Niste prosledili korektne podatke";
// 			$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
// 			echo $json_odgovor;
// 			return false;
	
// 	} else {
// 			$podaci_query = array();
// 			foreach ($podaci as $k=>$v){
// 				$v = "'".$v."'";
// 				$podaci_query[$k] = $v;
// 			}
// 			if ($db->update("kategorije", $id, array('kategorija'),array($podaci->kategorija))){
// 				$odgovor["poruka"] = "Kategorija je uspešno izmenjena";
// 				$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
// 				echo $json_odgovor;
// 				return false;
// 			} else {
// 				$odgovor["poruka"] = "Došlo je do greške pri izmeni kategorije";
// 				$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
// 				echo $json_odgovor;
// 				return false;
// 			}
// 	}
// 	}	

// });
// Flight::route('DELETE /novosti/@id', function($id){
// 		header ("Content-Type: application/json; charset=utf-8");
// 		$db = Flight::db();
// 		if ($db->delete("novosti", array("id"),array($id))){
// 				$odgovor["poruka"] = "Novost je uspešno izbrisana";
// 				$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
// 				echo $json_odgovor;
// 				return false;
// 		} else {
// 				$odgovor["poruka"] = "Došlo je do greške prilikom brisanja novosti";
// 				$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
// 				echo $json_odgovor;
// 				return false;
		
// 		}		
				
// });
// Flight::route('DELETE /kategorije/@id', function($id){
// 		header ("Content-Type: application/json; charset=utf-8");
// 		$db = Flight::db();
// 		if ($db->delete("kategorije", array("id"),array($id))){
// 				$odgovor["poruka"] = "Kategorija je uspešno izbrisana";
// 				$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
// 				echo $json_odgovor;
// 				return false;
// 		} else {
// 				$odgovor["poruka"] = "Došlo je do greške prilikom brisanja kategorije";
// 				$json_odgovor = json_encode ($odgovor,JSON_UNESCAPED_UNICODE);
// 				echo $json_odgovor;
// 				return false;
		
// 		}		


// });


Flight::start();
?>
