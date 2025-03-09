<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer mes Favoris</title>
    <link rel="stylesheet" href="../static/css/index.css">
    <link rel="stylesheet" href="../static/css/footer.css">

    <link rel="stylesheet" href="../static/css/compte.css">
</head>
<body>
<?php require_once 'header.php'; ?>
    <div class="content">
        <h2>Gérer mes Favoris</h2>
        <?php if (empty($favoris)): ?>
            <p>Vous n'avez pas encore de restaurant favori.</p>
        <?php else: ?>
            <ul class="favoris-list">
                <?php foreach ($favoris as $favori): ?>
                    <li class="favori-item">
                        <div class="favori-info">
                            <h3><?php echo htmlspecialchars($favori['restaurant']); ?></h3>
                            <p>Restaurant ID: <?php echo htmlspecialchars($favori['id_restaurant']); ?></p>
                        </div>
                        <div class="favori-actions">
                            <a href="?controller=ControlleurDetailResto&action=view&id=<?php echo $favori['id_restaurant']; ?>" class="btn-view">Voir</a>
                            <form action="/?controller=ControlleurFavoris&action=delete" method="post">
                                <input type="hidden" name="id" value="<?php echo $favori['id']; ?>">
                                <button type="submit" class="btn-delete">Supprimer</button>
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
