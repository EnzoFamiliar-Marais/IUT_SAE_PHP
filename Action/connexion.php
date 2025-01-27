<?

if (isset($_SESSION['name'])) {
    header('Location: ../index.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>IUTables’O - Inscription</title>
    <link rel="stylesheet" href="../Css/index.css">
    <link rel="stylesheet" href="../Css/connexion.css">
</head>
<body>
<?php include '../Templates/header.php'; ?>
</body>


</html>