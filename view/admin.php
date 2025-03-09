<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="../static/css/adminAcceuil.css">
    <link rel="stylesheet" href="../static/css/header.css">
    <link rel="stylesheet" href="../static/css/footer.css">
</head>
<body>
<header>
    <h1>IUTables’O</h1>
    <nav>
        <?php
            echo $formDeconnexion;
        ?>
    </nav>
</header>

<?php
    echo "<table border='1'>
        <thead> 
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Nombre de critique</th>
                <th>Actions</th>
            </tr>
        </thead>";
    echo "<tbody>";
    foreach ($utilisateurs as $personne) {
        $formDelete = $this->getFormDeleteAdmin($personne['id']);
        $formModifier = $this->getFormLink($personne['id']);
        echo "<tr>
                <td>{$personne['nom']}</td>
                <td>{$personne['prenom']}</td>";
                $userCritiques = array_filter($critiques, function($critique) use ($personne) {
                    return $critique['idU'] == $personne['id'];
                });
                echo "<td>".count($userCritiques)."</td>";
        echo "<td class='actions' data-id='{$personne['id']}' data-nom='{$personne['nom']}' data-prenom='{$personne['prenom']}' data-nbCritique='".count($userCritiques)."'>
                    $formModifier
                    $formDelete
                </td>
              </tr>";
    }
    echo "</tbody>";
    echo "</table>";
require_once "footer.php";

?>


</body>
</html>