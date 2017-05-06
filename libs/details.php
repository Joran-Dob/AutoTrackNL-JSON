<?php
header('Content-type:application/json;charset=utf-8');
$d_url = $_GET["url"];
$req = $_GET["req"];

$curl3 = curl_init();
curl_setopt($curl3, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl3, CURLOPT_HEADER, false);
curl_setopt($curl3, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($curl3, CURLOPT_URL, $d_url);
curl_setopt($curl3, CURLOPT_REFERER, $d_url);
curl_setopt($curl3, CURLOPT_RETURNTRANSFER, true);
$d_c_d = curl_exec($curl3);
curl_close($curl3);

// Create a DOM object
$html_details_d = new simple_html_dom();
// Load HTML from a string
$html_details_d->load($d_c_d);


echo "[";
if ($req == "overig") {
    $p_overig = $html_details_d->find('p', 0);
    echo '{';
    echo '"overig":"' . $p_overig->plaintext . '"';
    echo '}';
} elseif ($req == "description") {
    foreach ($html_details_d->find('p[itemprop=description]') as $p_des) {
        echo '{';
        echo '"description":"' . preg_replace( "/\r|\n/", "", $p_des->plaintext ) . '"';
        echo '}';
    }
} elseif ($req == "opties") {
    foreach ($html_details_d->find('div[class=grid-5 alpha]') as $info_div1) {
        foreach ($info_div1->find('li') as $li) {
            echo '{';
            echo '"opties":"' . $li->plaintext . '"';
            echo '},';
        }
    }
    foreach ($html_details_d->find('div[class=grid-5 alpha omega]') as $info_div2) {
        $li_n = 0;


        $last = count($info_div2->find('li'));
        foreach ($info_div2->find('li') as $li) {
            $li_n = $li_n+1;

            echo '{';
            echo '"opties":"' . $li->plaintext . '"';
            if ($li_n==$last) {
                echo '}';
            } else {
                echo '},';
            }
        }
    }
}
  echo "]";
