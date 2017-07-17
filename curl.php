<?php

function executeCurl($url, $data = []) {
    $ch = curl_init();
    $postvars = '';
    foreach ($data as $key => $value) {
        $postvars .= $key . "=" . $value . "&";
    }
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1); //0 for a get request
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    $curlResponse = curl_exec($ch);
	
    // echo "<pre>";
    // print_r($curlResponse);
    // die("Test");

    return json_decode($curlResponse, true);
}
