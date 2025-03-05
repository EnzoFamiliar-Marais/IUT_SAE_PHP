<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Compte</title>
    <link rel="stylesheet" href="../static/css/index.css">
    <link rel="stylesheet" href="../static/css/compte.css">
</head>
<body>
    <header>
        <h1>IUTables’O</h1>
        <nav>
            <a href="?controller=ControlleurHome&action=view">Accueil</a>
            <a href="?controller=ControlleurResto&action=view">Les Restos</a>
            <?php echo $formDeconnexion; ?>
        </nav>
    </header>
    <div class="content">
        <h2>Mon Compte</h2>
        <p><strong>Nom d'utilisateur :</strong> <?php echo htmlspecialchars($user['pseudo']); ?></p>
        <p><strong>Nom :</strong> <?php echo htmlspecialchars($user['nom']); ?></p>
        <p><strong>Prénom :</strong> <?php echo htmlspecialchars($user['prenom']); ?></p>
        <p><strong>Email :</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <p><strong>Nombre de critiques :</strong> <?php echo count($critiques); ?></p>
        <p><strong>Nombre de restaurants en favoris :</strong> <?php echo count($favoris); ?></p>
    </div>
    <?php require_once 'footer.php'; ?>
</body>
</html>