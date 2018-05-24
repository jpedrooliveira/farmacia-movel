<?php
	$url = "http://farmacia-movel2.herokuapp.com/api/verifyOrder/Default/ECPARAFA/2017/" . $_POST['nencomenda'] . "/" . $_POST['code'];
	$curlSession = curl_init();
	curl_setopt($curlSession, CURLOPT_URL, $url);
	curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
	curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);
	$getcontents = curl_exec($curlSession);
	curl_close($curlSession);
	echo $getcontents;
?>