<?php
header('Content-type:application/json;charset=utf-8');
ini_set('error_reporting', E_ALL);
ini_set('display_errors', true);
//ob_start(); // Start output buffering


//base url
$base = $URL;

$curl = curl_init();
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($curl, CURLOPT_URL, $base);
curl_setopt($curl, CURLOPT_REFERER, $base);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$str = curl_exec($curl);
curl_close($curl);

// Create a DOM object
$html = new simple_html_dom();
// Load HTML from a string
$html->load($str);
$count = 0;
$autos = 0;
$merk_naam = "";
// foreach($html->find('img') as $element) echo $element->src . '<br />';

echo "[";
$arrayt = $html->find('tr[class=item]');
$last = count($arrayt);



foreach ($html->find('tr[class=item]') as $ts) {
    echo '{';
    $numItems = count($ts);
    $autos++;

    foreach ($ts->find('a[class=deeplink]') as $a) {
        $d_url= 'https://www.autowereld.nl'.$a->href;
        echo '"details":"' . $d_url . '", ';
        foreach ($a->find('img') as $photo) {
            $photo_url = $photo->getAttribute('src');
           $photo_url = str_replace("100/", "320/", $photo_url);
            echo '"icon":"https://www.autowereld.nl' . $photo_url . '", ';
        }
    }
    foreach ($ts->find('td[class=omschrijving]') as $tf) {
        foreach ($tf->find('h3') as $title) {
            echo '"title":"' . $title->plaintext . '", ';







            $merk_naam = explode(' ', trim($title->plaintext))[0];
            echo '"merk":"' . $merk_naam . '", ';
        }

        foreach ($tf->find('span[class=kenmerken]') as $span) {
            $count++;

            $dataArray = explode(',', $span->plaintext);
            echo '"versnel":"' . $dataArray[1] . '", ';

            echo '"brandstof":"' . $dataArray[0] . '", ';
            echo '"inrichting":"' . $dataArray[2] . '", ';
            echo '"kleur":"' . $dataArray[3] . '", ';

          /*  if ($count == 1) {
                $td = preg_replace('/[ ]{2,}/', '', $td->plaintext);
                echo '"versnel":"' . $td . '", ';
            } elseif ($count == 2) {
                $td = preg_replace('/[ ]{2,}/', '', $td->plaintext);
                echo '"brandstof":"' . $td . '", ';
            } elseif ($count == 3) {
                $td = preg_replace('/[ ]{2,}/', '', $td->plaintext);
                echo '"inrichting":"' . $td . '", ';
            } elseif ($count == 4) {
                $td = preg_replace('/[ ]{2,}/', '', $td->plaintext);
                echo '"kilometer":"' . $td . '", ';
            } elseif ($count == 5) {
                $td = preg_replace('/[ ]{2,}/', '', $td->plaintext);
                echo '"kleur":"' . $td . '", ';
            }*/
        }
    }

    foreach ($ts->find('td[class=prijs]') as $prijs) {

            $prijs = preg_replace('/[^0-9\.]/', '', $prijs->plaintext);
            echo '"price":"' . $prijs . '", ';
        }

        foreach ($ts->find('td[class=bouwjaar]') as $bouwjaar) {

    $bouwjaar = preg_replace('/[ ]{1,}/', '', $bouwjaar->plaintext);

   echo '"jaar":"' . $bouwjaar . ' "';

}

    $count = 0;

    if ($autos == $last) {
        echo '}';
    } else {
        echo '},';
    }
}

echo "]";


//$list = ob_get_contents(); // Store buffer in variable

//ob_end_clean(); // End buffering and clean up

//echo $list; // will contain the contents
