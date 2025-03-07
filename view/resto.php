<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IUTables’O - Resto</title>
    <link rel="stylesheet" href="../static/css/index.css">
    <link rel="stylesheet" href="../static/css/resto.css">
    <script src="../static/js/filtres.js" defer></script>
</head>
<body>
    <header>
        <h1>IUTables’O</h1>
        <nav>
            <a href="?controller=ControlleurHome&action=view">Accueil</a>
            <a href="?controller=ControlleurResto&action=view">Les Restos</a>
            <?php if (isset($_SESSION['auth'])): ?>
                <a href="?controller=ControlleurCompte&action=view">Mon Compte</a>
                <?php echo $formDeconnexion; ?>
            <?php else: ?>
                <a href="?controller=ControlleurLogin&action=view">Connexion</a>
            <?php endif; ?>
        </nav>
    </header>
  

<?php

echo $formRecherche;
echo "<section id='filtres'>";
echo $filtreCuisine;
echo $filtreTypeRestaurant;
?>


<div class="menu">
    <button>Accessibilité</button>
    <div class="menu-content">
    <label>
        <input type="checkbox" id="internet_access" name="internet_access">
        Accès Internet
    </label>

    <label>
        <input type="checkbox" id="wheelchair" name="wheelchair">
        Accès fauteuil roulant
    </label>

    <label>
        <input type="checkbox" id="vegetarian" name="vegetarian">
        Végétarien
    </label>

    <label>
        <input type="checkbox" id="vegan" name="vegan">
        Végan
    </label>

    <label>
        <input type="checkbox" id="delivery" name="delivery">
        Livraison
    </label>

    <label>
        <input type="checkbox" id="takeaway" name="takeaway">
        À emporter
    </label>

    <label>
        <input type="checkbox" id="drive_through" name="drive_through">
        Drive-through
    </label>

    <label>
        <input type="checkbox" id="smoking" name="smoking">
        Fumeur
    </label>
    </div>
</div>


<?php
echo "</section>";

echo '<h2>Les restaurants de la région</h2>';
echo '<div class="restaurants">';
foreach ($restaurants as $restaurant) {

    echo '<a href="/?controller=ControlleurDetailResto&action=view&id=' . htmlspecialchars($restaurant['id']) . '">';
    echo '<section class="restaurant">';
    echo '<img src="../static/img/restobase.jpeg" alt="photo" />';
    echo '<h3>' . htmlspecialchars($restaurant['nom']) . '</h3>';
    echo '<p>Adresse: ' . htmlspecialchars($restaurant['adresse']) . '</p>';

     echo '<div class="typeCuisine" style="display:none;">';
     $propositionsAssociees = array_filter($propositions, function($propose) use ($restaurant) {
         return $propose['idResto'] == $restaurant['id'];
     });
     $typesCuisineAssocies = [];
     foreach ($propositionsAssociees as $propose) {
         $typeCuisine = array_filter($typeCuisines, function($typeCuisine) use ($propose) {
             return $typeCuisine['id'] == $propose['idCuisine'];
         });
         foreach ($typeCuisine as $cuisine) {
             echo '<div>' . htmlspecialchars($cuisine['nom']) . '</div>';
         }
     }
     echo '</div>';



    echo '<div class="typeRestaurant" style="display:none;">' . htmlspecialchars($restaurant['type']) . '</div>';
    echo '<hidden class="typeRestaurant">' . htmlspecialchars($restaurant['type']) . '</hidden>';
    echo '<div class="capacite" style="display:none;">' . htmlspecialchars($restaurant['capacity']) . '</div>';
    echo '<div class="internet_access" style="display:none;">' . htmlspecialchars($restaurant['internet_access']) . '</div>';
    echo '<div class="wheelchair" style="display:none;">' . htmlspecialchars($restaurant['wheelchair']) . '</div>';
    echo '<div class="vegetarian" style="display:none;">' . htmlspecialchars($restaurant['vegetarian']) . '</div>';
    echo '<div class="vegan" style="display:none;">' . htmlspecialchars($restaurant['vegan']) . '</div>';
    echo '<div class="delivery" style="display:none;">' . htmlspecialchars($restaurant['delivery']) . '</div>';
    echo '<div class="takeaway" style="display:none;">' . htmlspecialchars($restaurant['takeaway']) . '</div>';
    echo '<div class="drive_through" style="display:none;">' . htmlspecialchars($restaurant['drive_through']) . '</div>';
    


    echo '</section>';
    echo '</a>';

}
echo '</div>';

require "footer.php";
?>
</body>
</html>

