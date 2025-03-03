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
        echo $_SESSION['errorAdd'];
    } else {
        unset($_SESSION['errorAdd']);
    }
    echo  $_SESSION['errorConnexion'];
    echo  $_POST['email'];
    if(isset($_SESSION['errorLogin'])){
        echo $_SESSION['errorLogin'];
    }
    echo '<div class="container">';
    echo '<h2 class="title">Se connecter</h2>';
    echo '<div class="form-box">';
    echo $form;
    echo '<p class="signup-link"><a href="/?controller=ControlleurRegister&action=view">Créer un compte</a></p>';
    echo '</div>';
    ?>
</body>
</html>