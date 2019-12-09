<?php
function check2Empty($arg1, $arg2)
{
    if (empty($arg1) || empty($arg2)) {
        return true;
    } else {
        return false;
    }
}

function randomPicture()
{
    $randomGetal = rand(0, 0);
    $img = "https://picsum.photos/id/$randomGetal/1000/800";
    // $smallImg = imagejpeg($img, NULL , 20);
    return $img;
}
function convertCurrency($amount,$from_currency,$to_currency){
    $apikey = '4560a3f7760193469e10';

    $from_Currency = urlencode($from_currency);
    $to_Currency = urlencode($to_currency);
    $query =  "{$from_Currency}_{$to_Currency}";

    // change to the free URL if you're using the free version
    try {
        $json = file_get_contents("https://free.currconv.com/api/v7/convert?q={$query}&compact=ultra&apiKey={$apikey}");
        $obj = json_decode($json, true);
        $val = floatval($obj["$query"]);
        $total = $val * $amount;
        $result = number_format($total, 2, '.', '.');
        if($result == 0.00){
            $result = convertCurrency2($amount,$from_currency,$to_currency);
        }
        return $result;
    } catch (Exception $e) {
        echo "Fout bij het ophalen van de USD naar Euro. Foutcode: " . $e->getMessage();
    }

}
function convertCurrency2($amount,$from_currency,$to_currency){
    return 0.90;
}