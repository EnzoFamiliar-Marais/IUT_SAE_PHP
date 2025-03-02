<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Critique</title>
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
    $critiques = [

        ["idUser" => 1, "id" => 1, "restaurant" => "KFC", "avis" => 2.5, "description" => "Un excellent restaurant avec une ambiance chaleureuse."],
        ["idUser" => 1, "id" => 2, "restaurant" => "McDonald's", "avis" => 3.5, "description" => "Excellent repas mais l’ambiance ne me plaisait pas."],
        ["idUser" => 1, "id" => 3, "restaurant" => "Burger King", "avis" => 4.5, "description" => "Un excellent restaurant"],
        ["idUser" => 1, "id" => 10, "restaurant" => "Five Guys", "avis" => 4.7, "description" => "Burgers délicieux et frites excellentes."],
        ["idUser" => 1, "id" => 11, "restaurant" => "Chipotle", "avis" => 4.3, "description" => "Burritos savoureux et ingrédients frais."],


        ["idUser" => 2, "id" => 4, "restaurant" => "Subway", "avis" => 4.0, "description" => "Des sandwichs frais et délicieux."],
        ["idUser" => 2, "id" => 5, "restaurant" => "Pizza Hut", "avis" => 3.0, "description" => "Bonne pizza mais service lent."],
        ["idUser" => 2, "id" => 6, "restaurant" => "Domino's Pizza", "avis" => 4.2, "description" => "Livraison rapide et pizza savoureuse."],

        ["idUser" => 3, "id" => 7, "restaurant" => "Starbucks", "avis" => 4.8, "description" => "Café excellent et ambiance agréable."],
        ["idUser" => 3, "id" => 8, "restaurant" => "Dunkin' Donuts", "avis" => 3.8, "description" => "Bonnes pâtisseries mais café moyen."],

    ];

    $personnes = [
        ["id" => 1, "nom" => "Dena", "prenom" => "Paul", "nbCritique" => 5],
        ["id" => 2, "nom" => "Doe", "prenom" => "John", "nbCritique" => 3],
        ["id" => 3, "nom" => "Doe", "prenom" => "Jane", "nbCritique" => 2],
        ["id" => 4, "nom" => "Smith", "prenom" => "Will", "nbCritique" => 0]
    ];

    $nomPersonne = '';
    foreach ($personnes as $personne) {
        if ($personne['id'] == $_GET['id']) {
            $nomPersonne = $personne['nom'] . ' ' . $personne['prenom'];
            break;
        }
    }

    echo '<h2 class="title">Critique de ' . $nomPersonne . '</h2>';

  
        echo "<table border='1'>
        <thead> 
            <tr>
                <th>Restaurant</th>
                <th>Avis</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>";
        echo "<tbody>";
        foreach ($critiques as $critique) {
            if ($critique['idUser'] == $_GET['id']) {
                $formDelete = $this->getFormDeleteAdmin($critique['id']);
                echo "<tr>
                <td>{$critique['restaurant']}</td>
                <td>{$critique['avis']}/5</td>
                <td>{$critique['description']}</td>
                <td class='actions' data-id='{$critique['id']}' data-restaurant='{$critique['restaurant']}'}'>
                    $formDelete
                </td>
              </tr>";
            }
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
        //
    </script>

</body>

</html>