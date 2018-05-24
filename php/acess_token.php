<?php
function consume_api($url) {
	$curlSession = curl_init();
	curl_setopt($curlSession, CURLOPT_URL, $url);
	curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
	curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);
	$getcontents = curl_exec($curlSession);
	curl_close($curlSession);
	$getcontentss = json_decode($getcontents, true);
	# Caso o ESB retorne vazio, a funcao retorna erro
	if ($getcontentss==null){
	    return array('error' => $getcontents );
	} else {
	    return $getcontentss;
	}
}
?>