<?php
error_reporting(0);
require __DIR__ . "/../../vendor/autoload.php";

use Illuminate\Http\JsonResponse;
use GuzzleHttp\Client;



$keys = ["start","keyword","count"];

foreach ($keys as $key) {
  if (isset($_GET[$key])) {
    $$key = $_GET[$key];
  } else {
    $$key = 0;
  }
}

$url = "https://connpass.com/api/v1/event/";
$client = new Client();
$res = $client->get($url,[
  "query" => [
      "keyword" => $keyword,
      "start" => $start,
	  "count" => $count,
  ]
]);

$data = json_decode($res->getBody(),true);

$response = JsonResponse::create($data,200,[]);
$response->header('Access-Control-Allow-Origin', '*');
$response->send();