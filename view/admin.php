<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>


<?php

    echo "<h1>Liste des critiques récentes</h1>";

    $reviews = [
        ["restaurant" => "Le Gourmet", "comments" => 5, "average_rating" => 4.2],
        ["restaurant" => "Chez Pierre", "comments" => 3, "average_rating" => 3.8],
        ["restaurant" => "La Bonne Table", "comments" => 8, "average_rating" => 4.5],
    ];

    echo "<table border='1'>
            <tr>
                <th>Nom du restaurant</th>
                <th>Nombre de commentaires</th>
                <th>Moyenne des notes</th>
                <th>Actions</th>
            </tr>";

    foreach ($reviews as $review) {
        echo "<tr>
                <td>{$review['restaurant']}</td>
                <td>{$review['comments']}</td>
                <td>{$review['average_rating']}</td>
                <td>
                    <button onclick=\"alert('Afficher les détails de {$review['restaurant']}')\">Afficher les détails</button>
                    <button onclick=\"alert('Valider la critique de {$review['restaurant']}')\">Valider</button>
                    <button onclick=\"alert('Supprimer la critique de {$review['restaurant']}')\">Supprimer</button>
                </td>
              </tr>";
    }

    echo "</table>";
?>
    
</body>
</html>