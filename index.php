<?php
header('Content-Type:text/xml;charset=UTF-8');
ini_set("display_errors",0);
date_default_timezone_set("America/Sao_Paulo");
error_reporting(E_ERROR);

$endereco = "http://www.cetsp.com.br/";
$content = file_get_contents($endereco);
$doc = new DOMDocument();
$doc->loadHTML($content);
$doc = $doc->getElementsByTagName("div");
$total_km = $total_centro = $total_zona_norte = $total_zona_sul = $total_zona_leste = $total_zona_oeste = 0;
$unidade = [];

function getStringValueFromHTML($el) {
    return $el->getElementsByTagName("h4")->item(0)->nodeValue;
}

function getIntValue($stringValue) {
    return intval(preg_replace("/[^0-9]/", "", ($stringValue)));
}

function getNonIntValue($stringValue) {
    return preg_replace("/[0-9]+/", "", $stringValue);
}

foreach($doc as $div):
    if (is_numeric(strpos($div->getAttribute("class"),"centro"))):
        $val = getStringValueFromHTML($div);
        array_push($unidade, getNonIntValue($val));

        $total_centro += getIntValue($val);
    endif;
    if (is_numeric(strpos($div->getAttribute("class"),"norte"))):
        $val = getStringValueFromHTML($div);
        array_push($unidade, getNonIntValue($val));

        $total_zona_norte += getIntValue($val);
    endif;
    if (is_numeric(strpos($div->getAttribute("class"),"sul"))):
        $val = getStringValueFromHTML($div);
        array_push($unidade, getNonIntValue($val));

        $total_zona_sul += getIntValue($val);
    endif;
    if (is_numeric(strpos($div->getAttribute("class"),"leste"))):
        $val = getStringValueFromHTML($div);
        array_push($unidade, getNonIntValue($val));

        $total_zona_leste += getIntValue($val);
    endif;
    if (is_numeric(strpos($div->getAttribute("class"),"oeste"))):
        $val = getStringValueFromHTML($div);
        array_push($unidade, getNonIntValue($val));

        $total_zona_oeste += getIntValue($val);
    endif;
    if (is_numeric(strpos($div->getAttribute("class"),"boxZona"))):
        $val = getStringValueFromHTML($div);
        array_push($unidade, getNonIntValue($val));

        $total_km += getIntValue($val);
    endif;
endforeach;

$unidade = trim(join(', ',array_unique($unidade)));



die("<?xml version=\"1.0\" encoding=\"UTF-8\"?>
			<transito total=\"$total_km\">
				<fonte>$endereco</fonte>
				<unidade>$unidade</unidade>
				<centro>$total_centro</centro>
				<zona_norte>$total_zona_norte</zona_norte>
				<zona_sul>$total_zona_sul</zona_sul>
				<zona_leste>$total_zona_leste</zona_leste>
				<zona_oeste>$total_zona_oeste</zona_oeste>
			</transito>
");
?>