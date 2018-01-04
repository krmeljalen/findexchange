<?php
// Top how many you wish to check
$limit = 100;
// Exchanges you wish to match
$exchanges = array("binance","cryptopia");

function get_markets($url,$url_market,$id) {
        global $exchanges;

        $xchange = "";
        $color = "\033[0;31m";

        $markets = file_get_contents($url);
        $data_markets = file_get_contents($url_market);

        preg_match_all("|<small class=\"bold hidden-sm hidden-md hidden-lg\">\((.*)\)<\/small>|", $markets, $matches);

        foreach ($exchanges as $exchange) {
                $pos = strpos($data_markets, $exchange);
                if ($pos !== false) {
                        $xchange .= $exchange." ";
                }
        }

        if ($xchange != "")
                $color = "\033[0;32m";

        echo $color."".$id." - ".$matches[1][0]." - ".$xchange."\n";
}

$data = json_decode(file_get_contents("https://api.coinmarketcap.com/v1/ticker/?limit=".$limit));

foreach ($data as $market) {
        $url = "https://coinmarketcap.com/currencies/".$market->id."/";
        $url_market = "https://coinmarketcap.com/currencies/".$market->id."/#markets";
        get_markets($url,$url_market,$market->id);
        echo "\033[0m";
}
