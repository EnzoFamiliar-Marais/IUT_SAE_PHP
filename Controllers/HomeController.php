<?php

namespace Controllers;

class HomeController {
    public function index() {
        // Appel de la vue
        require_once 'Views/home.php';
    }
}
