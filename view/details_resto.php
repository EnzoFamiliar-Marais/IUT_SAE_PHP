<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IUTables’O - Détails du Resto</title>
    <link rel="stylesheet" href="../static/css/index.css">
    <link rel="stylesheet" href="../static/css/resto.css">
    <link rel="stylesheet" href="../static/css/detail_resto.css">
</head>
<body>
    <header>
        <h1>IUTables’O</h1>
        <nav>
            <a href="?controller=ControlleurHome&action=view">Accueil</a>
            <a href="/?controller=ControlleurResto&action=view">Les Restos</a>
            
            <?php 
            if(isset($_SESSION['email'])){
                echo $formRetour;                
            }
            else{
                echo '<a href="/?controller=ControlleurLogin&action=view">Connexion</a>';
            }
            ?>
        </nav>
    </header>
  
    <main>
        <h1>Détails du Restaurant</h1>
        <div class="restaurant-details">
            <div class="restaurant-image">
                <img src="../static/img/restobase.jpeg" alt="photo" id="imageResto"/>
            </div>
            <div class="restaurant-info">
                <p><strong>Nom :</strong> <?php echo $restaurant["nom"];  ?></p>
                <p><strong>Type :</strong> <?php echo $restaurant["type"];  ?></p>
                <?php if($restaurant["opening_hours"] != null): ?>
                    <p><strong>Heures d'ouverture :</strong><?php echo $restaurant["opening_hours"];  ?></p>
                <?php else: ?>
                    <p><strong>Heures d'ouverture :</strong>Non renseigné</p>
                <?php endif; ?>


                <?php if($restaurant["wheelchair"] == true): ?>
                    <p><strong>Accessibilité en fauteuil roulant :</strong> <?php echo "OUI" ?></p>
                <?php else: ?>
                    <p><strong>Accessibilité en fauteuil roulant :</strong> Non</p>
                <?php endif; ?>

                <?php if($restaurant["siret"] != null): ?>
                    <p><strong>Siret :</strong> <?php echo $restaurant["siret"] ?></p>
                <?php else: ?>
                    <p><strong>Siret :</strong>Non renseigné</p>
                <?php endif; ?>


                <?php if($restaurant["telephone"] != null): ?>
                    <p><strong>Téléphone :</strong> <?php echo $restaurant["telephone"] ?></p>
                <?php else: ?>
                    <p><strong>Téléphone :</strong>Non renseigné</p>
                <?php endif; ?>

                <?php if($restaurant["website"] != null): ?>
                    <p><strong>Site web :</strong> <a href="<?php echo $restaurant["website"]; ?>" target="_blank"><?php echo $restaurant["website"]; ?></a></p>
                <?php else: ?>
                    <p><strong>Site web :</strong>Non renseigné</p>
                <?php endif; ?>
                

                <?php if($restaurant["idCommune"] != null): ?>
                    <p><strong>Commune :</strong> <?php echo $restaurant["idCommune"]; ?></p>
                <?php else: ?>
                    <p><strong>Commune :</strong>Non renseigné</p>
                <?php endif; ?>
                
                <p><strong>Département :</strong> Loiret</p>
                <p><strong>Région :</strong> Centre-Val de Loire</p>
                <p><strong>Modifier sur OSM :</strong> <a href="https://www.openstreetmap.org/edit?node=3422189698" target="_blank">Lien OSM</a></p>
            </div>
        </div>
    </main>
</body>
</html>