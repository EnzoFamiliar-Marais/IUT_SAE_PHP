<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IUTables’O - Resto</title>
    <link rel="stylesheet" href="../static/css/login.css">
    <link rel="stylesheet" href="../static/css/header.css">
    

    
</head>
<body>
    <header>
        <h1>IUTables’O</h1>
    </header>
  

    <?php


    if (isset($_SESSION['errorAdd'])) {
        echo '<p class="error-message">' . $_SESSION['errorAdd'] . '</p>';
    } else {
        unset($_SESSION['errorAdd']);
    }

    if (isset($_SESSION['errorConnexion'])) {
        echo '<p class="error-message">' . $_SESSION['errorConnexion'] . '</p>';
    }

    if (isset($_SESSION['errorLogin'])) {
        echo '<p class="error-message">' . $_SESSION['errorLogin'] . '</p>';
    }


    echo '<div class="container">';
    echo '<h2 class="title">Se connecter</h2>';
    echo '<div class="form-box">';
    echo $form;
    echo '<p class="signup-link"><a href="/?controller=ControlleurRegister&action=view">Créer un compte</a></p>';
    echo '</div>';

    echo '<h2 class="back" ><a href="/?controller=ControlleurHome&action=view">Retour à l\'accueil</a></h2>';
    echo '</div>';

    require_once 'footer.php';
    
    ?>


</body>
</html>