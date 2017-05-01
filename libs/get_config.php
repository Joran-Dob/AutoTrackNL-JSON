<?php

$base = $URL;
$pages = $PAGES;
$str = '';
for ($p = 1; $p <= $pages; $p++) {
    $base = $URL;
    $base = $base . $p;
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_URL, $base);
    curl_setopt($curl, CURLOPT_REFERER, $base);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $str .= curl_exec($curl);
    curl_close($curl);
}

?>
