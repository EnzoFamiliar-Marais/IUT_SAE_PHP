<?php 

use Classes\Autoloader;
use Controlleur\ControlleurHome;
use Controlleur\ControlleurResto;



if (!isset($_SESSION)) {
    session_start();
}

require 'Classes/autoloader.php';
require './DATA/convert_data.php';

Autoloader::register();


if(isset($_GET['controller']) && isset($_GET['action'])){
    $controllerName = $_GET["controller"];

    switch($controllerName){
        case "ControlleurHome":
            $controller = new ControlleurHome($_REQUEST);
            break;
        case "ControlleurResto":
            $controller = new ControlleurResto($_REQUEST);
            break;
        default:
            $controller = null;
    }
    if(!is_null($controller)){
        $actionName = $_GET["action"];
            echo $controller->$actionName();
    }
}else{
    $controllerName = 'ControlleurHome';
    $controller = new ControlleurHome();
    $controller->view();
}

