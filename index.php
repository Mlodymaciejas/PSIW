<?php
require_once 'AuthController.php';
AuthController::check(); 

require_once 'ProductController.php';
$app = new ProductController();

if (isset($_GET['logout'])) {
    (new AuthController())->logout();
}

$produkty = $app->handle();
$kategorie = $app->getCategories(); // TEGO BRAKOWAŁO
$summary = $app->getSummary($produkty);

include 'view.php';