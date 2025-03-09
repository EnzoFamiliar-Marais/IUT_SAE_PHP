<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IUTables'O - Accueil</title>
    <link rel="stylesheet" href="../static/css/index.css">
    <link rel="stylesheet" href="../static/css/footer.css">

</head>
<body>
    <?php require_once 'header.php'; ?>
    <div class="content">
        <h2>Bienvenue sur la page d'accueil</h2>
        <p>Bienvenue sur notre plateforme de comparateur de Restaurant en ligne vous pouvez comparer les restaurant de la région Orléanaise</p>
        <a href="/?controller=ControlleurHome&action=map" class="button">Voir la carte des restaurants</a>
    </div>

    <?php if (count($bestrestaurants) > 0): ?>
        <div class="best-restaurants">
            <h2>Les meilleurs restaurants</h2>
            <p>Voici les restaurants qui ont plus d'une étoile.</p>
            <ul>
                <?php foreach ($bestrestaurants as $restaurant): ?>
                    <li>
                        <a href="/?controller=ControlleurDetailResto&action=view&id=<?= htmlspecialchars($restaurant['id']) ?>">
                            <section class="restaurant">
                                <img src="../static/img/restobase.jpeg" alt="photo" />
                                <h3><?= htmlspecialchars($restaurant['nom']) ?></h3>
                                <p>Type: <?= htmlspecialchars($restaurant['type'] ?? 'Non spécifié') ?></p>
                            </section>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php else: ?>
        <p>Aucun restaurant trouvé avec plus d'une étoile.</p>
    <?php endif; ?>

    <?php require_once 'footer.php'; ?>
</body>
</html>