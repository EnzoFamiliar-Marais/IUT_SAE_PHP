<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../static/css/login.css">
    <link rel="stylesheet" href="../static/css/header.css">
    <link rel="stylesheet" href="../static/css/footer.css">
</head>

<body>
    <header>
        <h1>IUTables’O</h1>
    </header>

    <?php

    if (isset($_SESSION['errorAdd'])) {
        echo $_SESSION['errorAdd'];
    } else {
        unset($_SESSION['errorAdd']);
    }

    echo '<div class="container">';
        echo '<h2 class="title">S\'inscrire</h2>';
        echo '<div class="form-box">';
            echo $form;
            echo '<p class="login-link">Déjà un compte ? <a href="/?controller=ControlleurLogin&action=view">Se connecter</a></p>';
        echo '</div>';
    echo '</div>';
    echo '<h2 class="back" ><a href="/?controller=ControlleurHome&action=view">Retour à l\'accueil</a></h2>';
    echo '</div>';
    require_once 'footer.php';


    ?>

</body>

</html>