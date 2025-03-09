<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer mes avis</title>
    <link rel="stylesheet" href="../static/css/header.css">
    <link rel="stylesheet" href="../static/css/compte.css">
    <link rel="stylesheet" href="../static/css/footer.css">

    <link rel="stylesheet" href="../static/css/gerer_avis.css">
</head>
<body>
<?php require_once 'header.php'; ?>
    <div class="content">
        <h2>Gérer mes avis</h2>
        <ul>
            <?php foreach ($critiques as $critique): ?>
                <li>
                    <p><strong>Restaurant :</strong> <?php echo htmlspecialchars($critique['restaurant_nom']); ?></p>
                    <p><strong>Note :</strong> <?php echo htmlspecialchars($critique['note']); ?></p>
                    <p><strong>Commentaire :</strong> <?php echo htmlspecialchars($critique['commentaire']); ?></p>
                    <div class="actions">
                        <form action="/?controller=ControlleurCritique&action=delete" method="post" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $critique['id']; ?>">
                            <input type="submit" value="Supprimer">
                        </form>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php require_once 'footer.php'; ?>
</body>
</html>