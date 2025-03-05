<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IUTables’O - Acceuil</title>
    <link rel="stylesheet" href="../static/css/index.css">
</head>
<body>
    <header>
        <h1>IUTables’O</h1>
        <nav>
            <a href="/?controller=ControlleurHome&action=view">Accueil</a>
            <a href="/?controller=ControlleurResto&action=view">Les Restos</a>
            <?php 
            if(isset($_SESSION['email'])){
                echo $formRetour;                
            }
            else{
                echo '<a href="/?controller=ControlleurLogin&action=view">Connexion</a>';
            }
            ?>
        </nav>
    </header>
    <div class="content">
        <h2>Bienvenue sur la page d'accueil</h2>
        <p>Bienvenue sur notre plateforme de comparateur de Restaurant en ligne vous pouvez comparer les restaurant de la région Orléanaises</p>
        <a href="/?controller=ControlleurHome&action=map" class="button">Voir la carte des restaurants</a>
    </div>

<?php 


if (!isset($_SESSION)) {
    session_start();
}


if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit();
}




echo '<h2 id="bestrestaurants">Les Meilleurs restaurants</h2>';
echo '<div class="bestrestaurants">';
foreach ($bestrestaurants as $bestrestaurant) {
    echo '<section class="bestrestaurant">';
    echo '<h3>' . htmlspecialchars($bestrestaurant['nom']) . '</h3>';
    echo '<p>Adresse: ' . htmlspecialchars($bestrestaurant['adresse']) . '</p>';
    echo '</section>';
}

echo '</div>';


require_once 'footer.php';
?>
</body>


</html>
