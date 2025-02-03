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
                
                
                $db = new \Classes\Form\Database('../Data/database.sqlite');
                $scores = $db->getScores();
                
                foreach ($scores as $row) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['name']) . "</td>
                            <td>" . htmlspecialchars($row['score']) . "</td>
                            <td>" . htmlspecialchars($row['date']) . "</td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
        <a class="back-home" href="../index.php">Retour à l'accueil</a>

</body>
</html>