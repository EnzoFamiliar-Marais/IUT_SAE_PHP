<?php
session_start(); // Placer en tout début du fichier, avant toute sortie HTML

if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['name'])) {
    $_SESSION['name'] = htmlspecialchars($_POST['name']);
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IUTables’O - Accueil</title>
    <link rel="stylesheet" href="Css/index.css">
</head>
<body>
    <header>
        <h1>IUTables’O</h1>
        <nav>
            <a href="index.php">Accueil</a>
            <a href="Action/resto.php">Les Restos</a>
        </nav>
    </header>
    <div class="content">
        <h2>Bienvenue sur la page d'accueil</h2>
        <p>Bienvenue sur notre plateforme de comparateur de restaurants en ligne. Vous pouvez comparer les restaurants de la région orléanaise.</p>
    </div>

    <?php 
    if (isset($_SESSION['name'])) {
        echo '<p>Vous êtes déjà inscrit !</p>';
        echo '<p>Vous pouvez soit aller voir vos avis ou regarder les différents restaurants.</p>';
    } else {
        echo '<p class="no-signin">Vous n\'êtes pas inscrit</p>';
        echo '<form action="" method="post">';
        echo '<label for="name">Entrez votre nom :</label>';
        echo '<input type="text" id="name" name="name" required>';
        echo '<button type="submit">Soumettre</button>';
        echo '</form>';
    }
    ?>

    <footer>
        Vous pouvez vous désinscrire juste ici <a href="./index.php?action=logout">cliquez ici</a>
    </footer>
</body>
</html>
