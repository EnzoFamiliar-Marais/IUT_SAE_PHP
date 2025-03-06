<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Critique</title>
    <link rel="stylesheet" href="../static/css/adminAcceuil.css">
    <link rel="stylesheet" href="../static/css/header.css">
    <script>
        function confirmDelete() {
            return confirm("Êtes-vous sûr de vouloir supprimer cet utilisateur et tous ses avis ?");
        }
</script>
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
    $nomPersonne = $utilisateur['nom'] . ' ' . $utilisateur['prenom'];
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
        if ($critique['idU'] == $utilisateur['id']) {
            $restaurant = array_filter($restaurants, function($resto) use ($critique) {
                return $resto['id'] == $critique['idR'];
            });
            $restaurant = reset($restaurant); 
            $formDelete = $this->postFormDeleteAdmin($critique['id']);
            echo "<tr>
                <td>{$restaurant['nom']}</td>
                <td>{$critique['note']}/5</td>
                <td>{$critique['commentaire']}</td>
                <td class='actions' data-id='{$critique['id']}' data-restaurant='{$restaurant['nom']}'>
                    $formDelete
                </td>
              </tr>";
        }
    }

    echo "</tbody>";
    echo "</table>";
    ?>


</body>

</html>