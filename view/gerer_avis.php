<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer mes avis</title>
    <link rel="stylesheet" href="../static/css/header.css">
    <link rel="stylesheet" href="../static/css/compte.css">
    <link rel="stylesheet" href="../static/css/gerer_avis.css">
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
        <h2>Gérer mes avis</h2>
        <ul>
            <?php foreach ($critiques as $critique): ?>
                <li>
                    <p><strong>Restaurant :</strong> <?php echo htmlspecialchars($critique['restaurant']); ?></p>
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