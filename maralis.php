<?php

$url = 'https://node-maralis.dev/chainalytics?account=0x7a5c4bb4930be5d3a056ef6228d93444da18041c';

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, false);
$json = curl_exec($ch);
curl_close($ch);

$json = json_decode($json, true);

if (count($json)) {
    foreach ($json as $item) {
        if (isset($item['data'])) {
            if (isset($item['data']['items']) && count($item['data']['items'])) {
                $quote_rate = [];
                $balance = [];

                foreach ($item['data']['items'] as $key => $row) {
                    $quote_rate[$key]  = $row['quote_rate'];
                    $balance[$key] = $row['balance'];
                }

                array_multisort($item['data']['items'], $quote_rate, SORT_DESC, $balance, SORT_DESC);
            }
        }
    }
}

$json = json_encode($json);

if (isset($_GET['debug'])) {
    echo '<pre>';
    print_r(json_decode($json, true));
    echo '</pre>';
} else {
    header('Content-Type: application/json; charset=utf-8');
    echo $json;
}
