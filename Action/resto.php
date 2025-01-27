<?php 
session_start();

if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>RestoAdvisor - Liste Restaurant</title>
    <link rel="stylesheet" href="Css/resto.css">
</head>
<body>



</body>
</html>