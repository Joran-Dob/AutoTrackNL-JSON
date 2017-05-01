<?php
header('Content-type:application/json;charset=utf-8');
$d_url = $_GET["url"];

$curl2 = curl_init();
curl_setopt($curl2, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl2, CURLOPT_HEADER, false);
curl_setopt($curl2, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($curl2, CURLOPT_URL, $d_url);
curl_setopt($curl2, CURLOPT_REFERER, $d_url);
curl_setopt($curl2, CURLOPT_RETURNTRANSFER, true);
$d_c = curl_exec($curl2);
curl_close($curl2);

// Create a DOM object
$html_details = new simple_html_dom();
// Load HTML from a string
$html_details->load($d_c);
echo "[";
foreach ($html_details->find('div[class=royalSlider rsDefault]') as $p_div) {
    $photo_n = 0;
    $last = count($p_div->find('a'));

    foreach ($p_div->find('a') as $photo_a) {
        echo '{';
        $photo_n = $photo_n+1;
        echo '"photo":"' . $photo_a->href . '"';

        if ($photo_n==$last) {
            echo '}';
        } else {
            echo '},';
        }
    }
}
  echo "]";
?>
