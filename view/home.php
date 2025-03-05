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





?>
<?php if (count($bestrestaurants) > 0): ?>
    <h2>Les meilleurs restaurants</h2>
    <p>Voici les restaurants qui ont plus d'une étoile.</p>
    <ul>
        <?php foreach ($bestrestaurants as $restaurant): ?>
            <li>
                <a href="/?controller=ControlleurDetailResto&action=view&id=<?= htmlspecialchars($restaurant['id']) ?>">
                    <section class="restaurant">
                        <img src="../static/img/restobase.jpeg" alt="photo" />
                        <h3><?= htmlspecialchars($restaurant['nom']) ?></h3>
                        <p>Adresse: <?= htmlspecialchars($restaurant['adresse']) ?></p>

                        <div class="typeCuisine" style="display:none;">
                            <?php
                            if (is_array($propositions) && count($propositions) > 0) {
                                $propositionsAssociees = array_filter($propositions, function($propose) use ($restaurant) {
                                    return $propose['idResto'] == $restaurant['id'];
                                });
                            } else {
                                $propositionsAssociees = [];
                            }

                            foreach ($propositionsAssociees as $propose) {
                                if (is_array($typeCuisines) && count($typeCuisines) > 0) {
                                    $typeCuisine = array_filter($typeCuisines, function($typeCuisine) use ($propose) {
                                        return $typeCuisine['id'] == $propose['idCuisine'];
                                    });
                                    foreach ($typeCuisine as $cuisine) {
                                        echo '<div>' . htmlspecialchars($cuisine['nom']) . '</div>';
                                    }
                                }
                            }
                            ?>
                        </div>

                        <div class="typeRestaurant" style="display:none;">
                            <?= htmlspecialchars($restaurant['type']) ?>
                        </div>
                    </section>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Aucun restaurant trouvé avec plus d'une étoile.</p>
<?php endif; ?>

<?php require_once 'footer.php'; ?>




</body>
</html>