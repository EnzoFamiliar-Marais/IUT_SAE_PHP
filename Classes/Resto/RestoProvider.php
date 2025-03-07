<?php
namespace Classes\Resto;

use Auth\DBRestaurant;
use Auth\DBCommune;
use Auth\DBDepartement;
use Auth\DBRegion;
use Auth\DBTypeCuisine;
use Auth\DBPropose;

class RestoProvider {
    private $file;

    public function __construct($file) {
        $this->file = $file;
    }

    public function loadRestaurants() {
        try {
            $contenu = file_get_contents($this->file);
            $json = json_decode($contenu, true);
            $commune = new DBCommune();
            $region = new DBRegion();
            $restaurant = new DBRestaurant();
            $TypeCuisine = new DBTypeCuisine();
            $propose = new DBPropose();
            $departement = new DBDepartement();

            foreach ($json as $key => $value) {
                $name = $value['name'] ?? "";
                $osm_edit = $value['osm_edit'] ?? "";
                $phone = $value['phone'] ?? "";
                $siret = $value['siret'] ?? "";
                $opening_hours = $value['opening_hours'] ?? "";
                $internet_access = isset($value['internet_access']) ? (is_array($value['internet_access']) ? ($value['internet_access'][0] != 'no') : ($value['internet_access'] != 'no')) : false;
                $wheelchair = isset($value['wheelchair']) && $value['wheelchair'] != "no";
                $type = $value['type'] ?? "";
                $brand = $value['brand'] ?? "";
                $capacity = is_numeric($value['capacity']) ? (int)$value['capacity'] : null;
                $stars = is_numeric($value['stars']) ? (int)$value['stars'] : null;
                $website = $value['website'] ?? "";
                $longitude = $value['geo_point_2d']['lon'] ?? 0;
                $latitude = $value['geo_point_2d']['lat'] ?? 0;

                $operator = isset($value['operator']) ? $value['operator'] : "";
                $vegetarian = isset($value['vegetarian']) ? $value['vegetarian'] : false;
                $vegan = isset($value['vegan']) ? $value['vegan'] : false;
                $delivery = isset($value['delivery']) ? $value['delivery'] : false;
                $takeaway = isset($value['takeaway']) ? $value['takeaway'] : false;
                $drive_through = isset($value['drive_through']) ? $value['drive_through'] : false;
                $wikidata = isset($value['wikidata']) ? $value['wikidata'] : "";
                $brand_wikidata = isset($value['brand_wikidata']) ? $value['brand_wikidata'] : "";
                $facebook = isset($value['facebook']) ? $value['facebook'] : "";
                $smoking = isset($value['smoking']) ? $value['smoking'] : false;

                $code_departement = isset($value['code_departement']) ? $value['code_departement'] : "";
                $departement_name = isset($value['departement']) ? $value['departement'] : "";

                $code_commune = isset($value['code_commune']) ? $value['code_commune'] : "";
                $commune_name = isset($value['commune']) ? $value['commune'] : "";

                $code_region = isset($value['code_region']) ? $value['code_region'] : "";
                $region_name = isset($value['region']) ? $value['region'] : "";

                $typecuisine = isset($value['cuisine']) ? $value['cuisine'] : [];

                $internet_accessStr = $internet_access ? 'true' : 'false';
                $wheelchairStr = $wheelchair ? 'true' : 'false';
                $vegetarianStr = $vegetarian ? 'true' : 'false';
                $veganStr = $vegan ? 'true' : 'false';
                $deliveryStr = $delivery ? 'true' : 'false';
                $takeawayStr = $takeaway ? 'true' : 'false';
                $drive_throughStr = $drive_through ? 'true' : 'false';
                $smokingStr = $smoking ? 'true' : 'false';

                if (!$region->exists($code_region)) {
                    $region->addRegion($code_region, $region_name);
                }

                if (!$departement->exists($code_departement)) {
                    $departement->addDepartement($code_departement, $departement_name, $code_region);
                }

                if (!$commune->exists($code_commune)) {
                    $commune->addCommune($code_commune, $commune_name, $code_departement);
                }

                $restaurantId = $restaurant->addRestaurant($name, "", $phone, "", $siret, $opening_hours, $internet_accessStr, $wheelchairStr, $type, $longitude, $latitude, $brand, $capacity, $stars, $website, $osm_edit, $operator, $vegetarianStr, $veganStr, $deliveryStr, $takeawayStr, $drive_throughStr, $wikidata, $brand_wikidata, $facebook, $smokingStr, $code_commune);
                $restaurantId = $restaurant->getLastInsertId();

                foreach ($typecuisine as $cuisine) {
                    $typeCuisineId = $TypeCuisine->addTypeCuisine($cuisine);
                    $typeCuisineInfo = $TypeCuisine->getTypeCuisineById($typeCuisineId);
                    echo "Restaurant ID : " . $restaurantId . " Type Cuisine ID : " . $typeCuisineId . "\n";
                    echo "Type Cuisine Info : " . json_encode($typeCuisineInfo) . "\n";
                    $propose->addPropose($typeCuisineId, $restaurantId);
                }
            }
        } catch (\Throwable $th) {
            echo "Erreur : " . $th->getMessage();
            echo "Erreur : " . $th->getTraceAsString();
        }
    }
}
?>