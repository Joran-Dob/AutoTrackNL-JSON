<?php

// Dit script kijkt of er een verandering is tussen het huidige json bestand en de oude!

$base = "https://test1234.feesie.club/";

function sendMessage()
{
    $content = array(
    "en" => 'New car in the assortment', "nl" => 'Nieuwe auto in het assortiment'

    );
    $title = array(
    "en" => 'New Cars  🚗', "nl" => 'Nieuwe Auto  🚗'

    );

    $fields = array(
    'app_id' => "2a031ce1-b3e2-4ba9-8482-e0875383eb50",
    'included_segments' => array('All'),
    'data' => array("foo" => "bar"),
    'contents' => $content,
    'headings' => $title,
    'large_icon' => 'http://test1234.feesie.club/data/icon.png'
  );

    $fields = json_encode($fields);


    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
                         'Authorization: Basic ZDYwODczYTctODE4OC00MjZmLThlODktZTU5NzY0NTk5NmFm'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}

$curl = curl_init();
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($curl, CURLOPT_URL, $base);
curl_setopt($curl, CURLOPT_REFERER, $base);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$str = curl_exec($curl);
$str_array = json_decode($str, true);
$c_str = count($str_array);
curl_close($curl);

$filename = 'libs/data/autos.json';
$index_F = fopen($filename, "r") or die("Unable to open file!");
$index_F_read = fread($index_F, filesize($filename));
$index_F_read_array = json_decode($index_F_read, true);
$c_index_F_read = count($index_F_read_array);


if (is_readable("libs/data/autos.json")) {
    if (!strcmp($c_index_F_read, $c_str) == 0) {
      echo "notifcation send";
        fclose($index_F);

        $response = sendMessage();
        $return["allresponses"] = $response;
        $return = json_encode($return);
        //echo $return;


        $index_F = fopen("libs/data/autos.json", "w") or die("Unable to open file!");


        fwrite($index_F, $str);


        fclose($index_F);
    } else {
    }
}
