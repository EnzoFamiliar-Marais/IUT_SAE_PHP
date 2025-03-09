<?php 
// Activer l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Favoris</title>
    <link rel="stylesheet" href="../static/css/index.css">
    <link rel="stylesheet" href="../static/css/footer.css">
    <link rel="stylesheet" href="../static/css/compte.css">
</head>
<body>
<?php require_once 'header.php'; ?>
    <div class="content">
        <h2>Mes Favoris</h2>
        <?php if (empty($favoris)): ?>
            <p>Vous n'avez pas encore de restaurant favori.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($favoris as $favori): ?>
                    <li>
                        <?php 
                        // Trouver le restaurant correspondant à l'ID
                        $restaurantNom = "Restaurant non trouvé";
                        foreach ($restaurants as $restaurant) {
                            if ($restaurant['id'] == $favori['idR']) {
                                $restaurantNom = $restaurant['nom'];
                                break;
                            }
                        }
                        ?>
                        <p><strong>Restaurant :</strong> <?php echo htmlspecialchars($restaurantNom); ?></p>
                        <div class="actions">
                            <a href="?controller=ControlleurDetailResto&action=view&id=<?php echo $favori['idR']; ?>" class="btn-view">Voir le restaurant</a>
                            <form action="/?controller=ControlleurFavoris&action=delete" method="post" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $favori['id']; ?>">
                                <input type="submit" value="Supprimer">
                            </form>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <div class="back-link">
            <a href="?controller=ControlleurCompte&action=view">Retour à mon compte</a>
        </div>
    </div>
    <?php require_once 'footer.php'; ?>
</body>
</html>