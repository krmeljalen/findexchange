<?php
// Top how many you wish to check
$limit = 100;

// Exchanges you wish to match
$exchanges = array("binance");

// Exclude certain coins - uppercase
$exclusions = array();
// $exclusions = array("BTC", "MIOTA", "LTC", "ETH", "XRP", "BCH", "ADA", "XLM", "TRX", "DASH", "EOS", "ETC", "ICX", "LSK", "XVG", "VEN", "BNB", "FUN", "SALT", "POWR", "AION", "REQ", "ELF", "POE", "DGD", "ICN", "SUB", "QSP", "STORJ", "RDN", "LINK", "TRIG", "BNT", "TNB");

function get_markets($url,$url_market,$id) {
        global $exchanges;
        global $exclusions;

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
        if (in_array(strtoupper($matches[1][0]),$exclusions) === false)
                echo $color."".$id."\033[0m - ".$matches[1][0]." - ".$xchange."\n";
}

$data = json_decode(file_get_contents("https://api.coinmarketcap.com/v1/ticker/?limit=".$limit));

foreach ($data as $market) {
        $url = "https://coinmarketcap.com/currencies/".$market->id."/";
        $url_market = "https://coinmarketcap.com/currencies/".$market->id."/#markets";
        get_markets($url,$url_market,$market->id);
}

