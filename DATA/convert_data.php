<?php

require_once __DIR__ . '/Classes/Resto/RestoProvider.php';

use Classes\Resto\RestoProvider;

$file = "Data/restaurants_orleans.json";
$restoProvider = new RestoProvider($file);
$restoProvider->loadRestaurants();

?>