<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


// helpers functions
require_once "../helpers/functions.php";

// database
require_once "../database/Database.php";

// countries iso
$countriesIso = require "../helpers/countries.php";

// get all cities

$db = new Database;
$q = "SELECT id, name, country, lon, lat FROM cities";
if (isset($_GET["city"])) {
  $q .= " WHERE name LIKE :city";
}

// $q .= " ODER BY name DESC";

if (isset($_GET["limit"])) {
  $q .= " LIMIT " . $_GET["limit"];
} else {
  $q .= " LIMIT 20";
}

$db->query($q);

if (isset($_GET["city"])) {
  $db->bind(':city', $_GET["city"] . '%');
}


$result = $db->fetchResults();
$cities = [];
foreach($result as $city) {
  array_push($cities, [
    'id' => strval($city->id),
    'name' => utf8_encode($city->name),
    'lon' => strval($city->lon),
    'lat' => strval($city->lat),
    'countryISO' => utf8_encode($city->country),
    'countryName' => utf8_encode($countriesIso[$city->country]),
  ]);
}

// print_r($cities);

echo json_encode([
  'number' => count($cities),
  'data' => $cities,
]);
