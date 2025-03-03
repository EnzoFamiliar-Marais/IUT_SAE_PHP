<?php 

use Classes\Autoloader;
use Controlleur\ControlleurDetailResto;
use Controlleur\ControlleurHome;
use Controlleur\ControlleurResto;
use Controlleur\ControlleurLogin;
use Controlleur\ControlleurRegister;
use Controlleur\ControlleurAdmin;
use Controlleur\ControlleurCritique;



if (!isset($_SESSION)) {
    session_start();
}

require 'Classes/autoloader.php';
require_once './Classes/data/db.php';

Autoloader::register();
//require './DATA/convert_data.php';


if(isset($_GET['controller']) && isset($_GET['action'])){
    $controllerName = $_GET["controller"];

    switch($controllerName){
        case "ControlleurHome":
            $controller = new ControlleurHome($_REQUEST);
            break;
        case "ControlleurResto":
            $controller = new ControlleurResto($_REQUEST);
            break;
        case "ControlleurLogin":
            $controller = new ControlleurLogin($_REQUEST);
            break;

        case "ControlleurRegister":
            $controller = new ControlleurRegister($_REQUEST);
            break;
        case "ControlleurAdmin":
            $controller = new ControlleurAdmin($_REQUEST);
            break;
        case "ControlleurCritique":
            $controller = new ControlleurCritique($_REQUEST);
            break;
        case "ControlleurDetailResto":
            $controller = new ControlleurDetailResto($_REQUEST);
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

