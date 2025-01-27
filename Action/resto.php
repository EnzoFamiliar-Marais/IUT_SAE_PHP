<?php 
session_start();

if (!(isset($_SESSION['name']))){
    header('Location: ../index.php');
    echo "<script>alert('Vous n\'êtes pas inscrit');</script>";
    exit();
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>IUTables’O  - Liste Restaurant</title>
    <link rel="stylesheet" href="../Css/index.css">
    <link rel="stylesheet" href="../Css/resto.css">

</head>
<body>

<?php include '../Templates/header.php'; ?>

<table>
    <thead>
        <tr>
            <th>Nom</th>
            <th>Avis</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Restaurant A</td>
            <td>4/5</td>
            <td>Un excellent restaurant avec une ambiance chaleureuse.</td>
        </tr>
    </tbody>
    <tbody>
                <?php 
                require '../DATA/database.php';
                echo "ca marche bien";
                
               
                ?>
            </tbody>
        </table>
        <a class="back-home" href="../index.php">Retour à l'accueil</a>

</body>
</html>