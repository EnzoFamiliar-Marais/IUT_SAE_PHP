<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="../static/css/adminAcceuil.css">
    <link rel="stylesheet" href="../static/css/header.css">
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
    $personnes = [
        ["id" => 1, "nom" => "Dena", "prenom" => "Paul", "nbCritique" => 5],
        ["id" => 2, "nom" => "Doe", "prenom" => "John" , "nbCritique" => 3],
        ["id" => 3, "nom" => "Doe", "prenom" => "Jane" , "nbCritique" => 2],
        ["id" => 4, "nom" => "Smith", "prenom" => "Will", "nbCritique" => 0]
    ];

    

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
    foreach ($personnes as $personne) {
        $formDelete = $this->getFormDeleteAdmin($personne['id']);
        $formModifier = $this->getFormLink($personne['id']);
        echo "<tr>
                <td>{$personne['nom']}</td>
                <td>{$personne['prenom']}</td>
                <td>{$personne['nbCritique']}</td>
                <td class='actions' data-id='{$personne['id']}' data-nom='{$personne['nom']}' data-prenom='{$personne['prenom']}' data-nbCritique='{$personne['nbCritique']}'>
                    $formModifier
                    $formDelete
                </td>
              </tr>";
    }
    echo "</tbody>";
    echo "</table>";
?>

<div id="details" style="display:none;">
    <h2>Détails de la personne</h2>
    <p id="detailNom"></p>
    <p id="detailPrenom"></p>
    <p id="detailNbCritique"></p>
</div>

<script>
//    document.querySelectorAll('.btn.view').forEach(button => {
//        button.addEventListener('click', function() {
//            const row = this.closest('td.actions');
//            const nom = row.getAttribute('data-nom');
//            const prenom = row.getAttribute('data-prenom');
//            const nbCritique = row.getAttribute('data-nbCritique');
//
//            document.getElementById('detailNom').innerText = 'Nom: ' + nom;
//            document.getElementById('detailPrenom').innerText = 'Prénom: ' + prenom;
//            document.getElementById('detailNbCritique').innerText = 'Nombre de critique: ' + nbCritique;
//
//            document.getElementById('details').style.display = 'block';
//        });
//    });
//</script>

</body>
</html>