<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IUTables’O - Resto</title>
    <link rel="stylesheet" href="Css/index.css">
    <link rel="stylesheet" href="Css/resto.css">
    <script src="js/openStreetMap.js" defer></script>
    <script src="js/filtres.js" defer></script>
</head>
<body>
    <header>
        <h1>IUTables’O</h1>
        <nav>
            <a href="?controller=ControlleurHome&action=view">Accueil</a>
            <a href="/?controller=ControlleurResto&action=view">Les Restos</a>
        </nav>
    </header>
  

<?php

echo $formRecherche;
echo "<section id='filtres'>";
echo $filtreCuisine;
echo $filtreTypeRestaurant;
echo "</section>";

echo '<h2>Les restaurants de la région</h2>';
echo '<div class="restaurants">';
foreach ($restaurants as $restaurant) {
    echo '<a href="/?controller=ControlleurResto&action=view&id=' . htmlspecialchars($restaurant['id']) . '">';
    echo '<section class="restaurant">';
    echo '<h3>' . htmlspecialchars($restaurant['nom']) . '</h3>';
    echo '<p>Adresse: ' . htmlspecialchars($restaurant['adresse']) . '</p>';

    echo '</section>';
    echo '</a>';

}
echo '</div>';

