<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Favoris</title>
    <link rel="stylesheet" href="../static/css/index.css">
    <link rel="stylesheet" href="../static/css/compte.css">
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
        <h2>Mes Favoris</h2>
        <ul>
            <?php foreach ($favoris as $favori): ?>
                <li>
                    <p><strong>Restaurant :</strong> <?php echo htmlspecialchars($restaurants[$favori['idR']]['nom']); ?></p>
                    <div class="actions">
                        <form action="/?controller=ControlleurFavoris&action=delete" method="post" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $favori['id']; ?>">
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
