<?php
header('Content-type:application/json;charset=utf-8');
//ini_set( 'error_reporting', E_ALL );
//ini_set( 'display_errors', true );
ob_start(); // Start output buffering


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
$arrayt = $html->find('tr[class=normalRowResult search-result-item] ');
$last = count($arrayt);


foreach ($html->find('tr[class=normalRowResult search-result-item] ') as $ts) {
    echo '{';
    $numItems = count($ts);
    $autos++;
    foreach ($ts->find('td') as $photol) {
        foreach ($photol->find('div[class=search-result-item-photo] img') as $photo) {
            $photo_url = $photo->getAttribute('src');
            $new_url = str_replace("-thumbnail.jpg", "-medium.jpg", $photo_url);
            echo '"icon":"' . $new_url . '", ';
        }
    }

    foreach ($ts->find('td') as $tf) {
        foreach ($tf->find('a') as $title) {
            echo '"title":"' . $title->plaintext . '", ';


            $d_url= 'https://api.autotrader.nl/'.$title->href;
            echo '"details":"' . $d_url . '", ';




            $merk_naam = explode(' ', trim($title->plaintext))[0];
            echo '"merk":"' . $merk_naam . '", ';
        }

        foreach ($tf->find('ul') as $tr) {
            foreach ($tr->find('li') as $tf) {
                $count++;
                foreach ($tf->find('span[style=white-space:nowrap;]') as $td) {
                    if ($count == 1) {
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
                    }
                }
            }
        }
    }

    foreach ($ts->find('td') as $rinfo) {
        foreach ($rinfo->find('div[class=search-result-item-price]') as $prijs) {
            $prijs = preg_replace('/[^0-9\.]/', '', $prijs);
            echo '"price":"' . $prijs . '", ';
        }
    }

    $bouwjaar = preg_replace('/[ ]{1,}/', '', $ts->find('td', 3)->plaintext);

    echo '"jaar":' . $bouwjaar . ' ';



    $count = 0;

    if ($autos == $last) {
        echo '}';
    } else {
        echo '},';
    }
}

echo "]";


$list = ob_get_contents(); // Store buffer in variable

ob_end_clean(); // End buffering and clean up

echo $list; // will contain the contents
?>
