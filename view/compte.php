<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Compte</title>
    <link rel="stylesheet" href="../static/css/header.css">
    <link rel="stylesheet" href="../static/css/footer.css">

    <link rel="stylesheet" href="../static/css/compte.css">
    <script>
        function toggleEditForm() {
            var form = document.getElementById('editForm');
            var displayStyle = form.style.display;
            form.style.display = displayStyle === 'none' ? 'block' : 'none';

            var info = document.getElementById('info');
            info.style.display = displayStyle === 'none' ? 'none' : 'block';
        }
    </script>
</head>
<body>
<?php require_once 'header.php'; ?>
    <div class="content">
        <h2>Mon Compte</h2>
        <div id="info">
            <p><strong>Nom d'utilisateur :</strong> <?php echo htmlspecialchars($user['pseudo']); ?></p>
            <p><strong>Nom :</strong> <?php echo htmlspecialchars($user['nom']); ?></p>
            <p><strong>Prénom :</strong> <?php echo htmlspecialchars($user['prenom']); ?></p>
            <p><strong>Email :</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Nombre de critiques :</strong> <?php echo count($critiques); ?></p>
            <div class="button-container">
                <a href="?controller=ControlleurCompte&action=gererAvis" class="button">Gérer mes avis</a>
            </div>
            <div class="button-container">
                <a href="?controller=ControlleurFavoris&action=gererFavoris" class="button">Gérer mes favoris</a>
            </div>
            <div class="button-container">
                <a onclick="toggleEditForm()" class="button">Modifier les informations</a>
            </div>
        </div>
        <form id="editForm" action="/?controller=ControlleurCompte&action=update" method="post" style="display:none;">
            <p><strong>Nom d'utilisateur :</strong> <input type="text" name="pseudo" value="<?php echo htmlspecialchars($user['pseudo']); ?>"></p>
            <p><strong>Nom :</strong> <input type="text" name="nom" value="<?php echo htmlspecialchars($user['nom']); ?>"></p>
            <p><strong>Prénom :</strong> <input type="text" name="prenom" value="<?php echo htmlspecialchars($user['prenom']); ?>"></p>
            <p><strong>Email :</strong> <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>"></p>
            <input type="submit" value="Valider">
        </form>
    </div>
    <?php require_once 'footer.php'; ?>
</body>
</html>