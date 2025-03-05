<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IUTables’O - Détails du Resto</title>
    <link rel="stylesheet" href="../static/css/index.css">
    <link rel="stylesheet" href="../static/css/resto.css">
    <link rel="stylesheet" href="../static/css/detail_resto.css">
    <link rel="stylesheet" href="../static/css/modals.css">
    <link rel="stylesheet" href="../static/css/avis.css">
    <script src="../static/js/modals.js" defer></script>
</head>

<body>
    <header>
        <h1>IUTables’O</h1>
        <nav>
            <a href="?controller=ControlleurHome&action=view">Accueil</a>
            <a href="/?controller=ControlleurResto&action=view">Les Restos</a>
            <?php
            if (isset($_SESSION['email'])) {
                echo $formDeconnexion;
            } else {
                echo '<a href="/?controller=ControlleurLogin&action=view">Connexion</a>';
            }
            ?>
        </nav>
    </header>

    <main>
        <div class="title">
            <h1 id="titleResto">
                <?php echo $restaurant["nom"]; ?>
                <?php if ($restaurant["stars"] != null): ?>
                    <span class="stars">
                        <?php for ($i = 0; $i < $restaurant["stars"]; $i++): ?>
                            <span style="color: gold;">★</span>
                        <?php endfor; ?>
                    </span>
                <?php endif; ?>
            </h1>

            <?php if ($restaurant["capacity"] != null): ?>
                <h2 class="capacity">Capacité : <span class="value"><?php echo $restaurant["capacity"] ?></span></h2>
            <?php endif; ?>
        </div>

        <div class="restaurant-description">
            <div class="restaurant-image">
                <img src="../static/img/restobase.jpeg" alt="photo" id="imageResto" />
            </div>
            <div class="opening-hours">
                <h2>Horaires</h2>
                <?php if (isset($restaurant["opening_hours_processed"])): ?>
                    <p style="color: <?php echo $restaurant["is_open_now"] ? 'green' : 'red'; ?>;">
                        <?php echo $restaurant["is_open_now"] ? 'Ouvert maintenant' : 'Fermé maintenant'; ?>
                    </p>
                    <ul>
                        <?php 
                        $daysOfWeek = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
                        foreach ($daysOfWeek as $day): ?>
                            <li>
                                <span class="day"><?php echo $day; ?></span>
                                <span class="time">
                                    <?php 
                                    if (isset($restaurant["opening_hours_processed"][$day])) {
                                        echo implode('<br>', array_map('trim', $restaurant["opening_hours_processed"][$day]));
                                    } else {
                                        echo 'Fermé';
                                    }
                                    ?>
                                </span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>Non renseigné</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="restaurant-caractéristique">
            <h3>Caractéristiques :</h3>
            <div class="restaurant-details">
                <div class="restaurant-info">
                    <p class="label">Type de restaurant : <span class="value"><?php echo $restaurant["type"]; ?></span></p>
                    <p class="label">Marque : <span class="value"><?php echo $restaurant["brand"] ?? 'Non renseigné'; ?></span></p>
                    <p class="label">Siret : <span class="value"><?php echo $restaurant["siret"] ?? 'Non renseigné'; ?></span></p>
                    <p class="label">Téléphone : <span class="value"><?php echo $restaurant["telephone"] ?? 'Non renseigné'; ?></span></p>
                    <p class="label">Site web : <span class="value">
                        <?php if ($restaurant["website"] != null): ?>
                            <a href="<?php echo $restaurant["website"]; ?>" target="_blank">Lien</a>
                        <?php else: ?>
                            Non renseigné
                        <?php endif; ?>
                    </span></p>
                    <p class="label">Commune : <span class="value"><?php echo $commune ?? 'Non renseigné'; ?></span></p>
                    <p class="label">Département : <span class="value"><?php echo $departement ?? 'Non renseigné'; ?></span></p>
                    <p class="label">Région : <span class="value"><?php echo $region ?? 'Non renseigné'; ?></span></p>
                    <p class="label">Regarder sur OSM : <span class="value"><a href="https://www.openstreetmap.org/edit?node=3422189698" target="_blank">Lien OSM</a></span></p>
                </div>
            </div>
        </div>

        <div class="avisContainer">
            <?php if (isset($_SESSION['email'])): ?>
                <button type="button" id="openAvisModal">Donner votre Avis</button>
            <?php else: ?>
                <p>Vous devez être connecté pour laisser un avis.</p>
            <?php endif; ?>

            <h2>Avis</h2>
            <ul class="avis">
                <li class="avis-item">
                    <p class="avis-author">Pseudo: clapclap17</p>
                    <p class="avis-note"><span style="color: green;">●●●●●</span></p>
                    <p class="avis-content">Très bon restaurant, service impeccable et plats délicieux.</p>
                    <p class="avis-date">Rédigé le 01/03/2025</p>
                </li>
                <li class="avis-item">
                    <p class="avis-author">Pseudo: Cécile L</p>
                    <p class="avis-note"><span style="color: green;">●●●●○</span></p>
                    <p class="avis-content">Ambiance agréable, mais les plats étaient un peu trop salés à mon goût.</p>
                    <p class="avis-date">Rédigé le 28/02/2025</p>
                </li>
            </ul>
        </div>
    </main>

    <?php require_once 'modals.php'; ?>
    <?php require_once 'footer.php'; ?>
</body>

</html>
