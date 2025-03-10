<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IUTables'O - Détails du Resto</title>
    <link rel="stylesheet" href="../static/css/resto.css">
    <link rel="stylesheet" href="../static/css/detail_resto.css">
    <link rel="stylesheet" href="../static/css/header.css">
    <link rel="stylesheet" href="../static/css/modals.css">
    <link rel="stylesheet" href="../static/css/avis.css">
    <link rel="stylesheet" href="../static/css/footer.css">

    <script src="../static/js/modals.js" defer></script>
    <script src="../static/js/favoris.js" defer></script>
</head>

<body>
<?php require_once 'header.php'; ?>

    <main>
        <div class="title">
            <h1 id="titleResto">
                <?php echo $restaurant["nom"]; ?>
                <?php if ($restaurant["stars"] != null): ?>
                    <span class="stars">
                        <?php for ($i = 0; $i < $restaurant["stars"]; $i++): ?>
                            <span class="star" style="color: gold;">★</span>
                        <?php endfor; ?>
                    </span>
                <?php endif; ?>
                <?php if (isset($_SESSION['auth'])): ?>
                    <a href="#" onclick="toggleFavoris(event)" class="favorite-btn" data-restaurant-id="<?php echo $restaurant['id']; ?>">
                        <span id="favorisIcon" class="favoris-icon <?php echo $isFavoris ? 'favoris-icon-active' : ''; ?>">
                            <?php echo $isFavoris ? '♥' : '♡'; ?>
                        </span>
                    </a>
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
                <?php if (isset($restaurant["opening_hours"])): ?>
                    <?php if (isset($restaurant["is_open_now"])): ?>
                        <p class="<?php echo $restaurant["is_open_now"] ? 'open-now' : 'closed-now'; ?>">
                            <?php echo $restaurant["is_open_now"] ? 'Ouvert maintenant' : 'Fermé maintenant'; ?>
                        </p>
                    <?php endif; ?>
                    <ul>
                        <?php 
                        $daysOfWeek = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
                        foreach ($daysOfWeek as $day): ?>
                            <li>
                                <span class="day"><?php echo $day; ?></span>
                                <span class="time">
                                    <?php 
                                    if (isset($restaurant["opening_hours_processed"][$day])) {
                                        echo implode(', ', array_map('trim', $restaurant["opening_hours_processed"][$day]));
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

        <h3>Caractéristiques :</h3>
        <div class="restaurant-details">
            <div class="restaurant-info">
                <div id="detailsGauche">
                    <p class="label">Type de restaurant : <span class="value"><?php echo $restaurant["type"];  ?></span></p>
                   
                    <?php if ($restaurant["brand"] != null): ?>
                        <p class="label">Marque : <span class="value"><?php echo $restaurant["brand"] ?></span></p>
                    <?php else: ?>
                        <p class="label">Marque : <span class="value">Non renseigné</span></p>
                    <?php endif; ?>

                    <?php if ($restaurant["siret"] != null): ?>
                        <p class="label">Siret : <span class="value"><?php echo $restaurant["siret"] ?></span></p>
                    <?php else: ?>
                        <p class="label">Siret : <span class="value">Non renseigné</span></p>
                    <?php endif; ?>


                    <?php if ($restaurant["telephone"] != null): ?>
                        <p class="label">Téléphone : <span class="value"><?php echo $restaurant["telephone"] ?></span></p>
                    <?php else: ?>
                        <p class="label">Téléphone : <span class="value">Non renseigné</span></p>
                    <?php endif; ?>

                    <?php if ($restaurant["website"] != null): ?>
                        <p class="label">Site web : <span class="value"><a href="<?php echo $restaurant["website"]; ?>" target="_blank"><?php echo $restaurant["website"]; ?></a></span></p>
                    <?php else: ?>
                        <p class="label">Site web : <span class="value">Non renseigné</span></p>
                    <?php endif; ?>

                    <?php if ($restaurant["operator"] != null): ?>
                        <p class="label">Opérateur : <span class="value"><?php echo $restaurant["operator"] ?></span></p>
                    <?php endif; ?>

                    <?php if ($commune != null): ?>
                        <p class="label">Commune : <span class="value"><?php echo $commune; ?></span></p>
                    <?php else: ?>
                        <p class="label">Commune : <span class="value">Non renseigné</span></p>
                    <?php endif; ?>


                    <?php if ($departement != null): ?>
                        <p class="label">Département : <span class="value"><?php echo $departement ?></span></p>
                    <?php else: ?>
                        <p class="label">Département : <span class="value">Non renseigné</span></p>
                    <?php endif; ?>

                    <?php if ($region != null): ?>
                        <p class="label">Région : <span class="value"><?php echo $region ?></span></p>
                    <?php else: ?>
                        <p class="label">Région : <span class="value">Non renseigné</span></p>
                    <?php endif; ?>

                    <p class="label">Regarder sur OSM : <span class="value"><a href="https://www.openstreetmap.org/edit?node=3422189698" target="_blank">Lien OSM</a></span></p>
                </div>

                <div id="detailsDroite">
                    <?php if ($restaurant["wheelchair"] == true): ?>
                        <p class="label">Accessibilité en fauteuil roulant : <span class="value"><?php echo "OUI" ?></span></p>
                    <?php else: ?>
                        <p class="label">Accessibilité en fauteuil roulant : <span class="value">Non</span></p>
                    <?php endif; ?>

                    <?php if ($restaurant["takeaway"] == true): ?>
                        <p class="label">A Emporter : <span class="value"><?php echo "OUI" ?></span></p>
                    <?php else: ?>
                        <p class="label">A Emporter : <span class="value">Non</span></p>
                    <?php endif; ?>

                    <?php if ($restaurant["delivery"] == true): ?>
                        <p class="label">Livraison : <span class="value"><?php echo "OUI" ?></span></p>
                    <?php else: ?>
                        <p class="label">Livraison : <span class="value">Non</span></p>
                    <?php endif; ?>

                    <?php if ($restaurant["internet_access"] == true): ?>
                        <p class="label">Accès internet : <span class="value"><?php echo "OUI" ?></span></p>
                    <?php else: ?>
                        <p class="label">Accès internet : <span class="value">Non</span></p>
                    <?php endif; ?>

                    <?php if ($restaurant["vegetarian"] == true): ?>
                        <p class="label">Végétarien : <span class="value"><?php echo "OUI" ?></span></p>
                    <?php else: ?>
                        <p class="label">Végétarien : <span class="value">Non</span></p>
                    <?php endif; ?>

                    <?php if ($restaurant["vegan"] == true): ?>
                        <p class="label">Végan : <span class="value"><?php echo "OUI" ?></span></p>
                    <?php else: ?>
                        <p class="label">Végan : <span class="value">Non</span></p>
                    <?php endif; ?>

                    <?php if ($restaurant["smoking"] == true): ?>
                        <p class="label">Fumeur : <span class="value"><?php echo "OUI" ?></span></p>
                    <?php else: ?>
                        <p class="label">Fumeur : <span class="value">Non</span></p>
                    <?php endif; ?>

                    <?php if ($restaurant["drive_through"] == true): ?>
                        <p class="label">Service au volant : <span class="value"><?php echo "OUI" ?></span></p>
                    <?php else: ?>
                        <p class="label">Service au volant : <span class="value">Non</span></p>
                    <?php endif; ?>

                    <?php if ($restaurant["facebook"] != null): ?>
                        <p class="label">Facebook :
                            <span class="value">
                                <a href="<?php echo $restaurant["facebook"]; ?>" target="_blank">
                                    <img src="../static/img/facebook_logo.png" alt="Facebook" class="facebook-logo">
                                    <?php echo $restaurant["facebook"]; ?>
                                </a>
                            </span>
                        </p>
                    <?php else: ?>
                        <p class="label">Facebook : <span class="value">Non renseigné</span></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        </div>

        
    </main>
    <div class="avisContainer">
        <?php if (isset($_SESSION['auth'])): ?>
            <button type="button" id="openAvisModal">Donner votre Avis</button>
        <?php else: ?>
            <p>Vous devez être connecté pour laisser un avis.</p>
        <?php endif; ?>

        <h2>Avis</h2>
        <ul class="avis">
            <?php if (empty($critiques)): ?>
                <p>Aucun avis pour ce restaurant.</p>
            <?php else: ?>
                <?php foreach ($critiques as $critique): ?>
                    <li class="avis-item">
                        <p class="avis-author">Pseudo: <?php echo htmlspecialchars($critique['pseudo']); ?></p>
                        <p class="avis-note">
                            <?php for ($i = 0; $i < 5; $i++): ?>
                                <span class="avis-note-star"><?php echo $i < $critique['note'] ? '●' : '○'; ?></span>
                            <?php endfor; ?>
                        </p>
                        <p class="avis-content"><?php echo htmlspecialchars($critique['commentaire']); ?></p>
                        <p class="avis-date">Rédigé le <?php echo htmlspecialchars($critique['date_critique']); ?></p>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>

    <?php require_once 'modals.php'; ?>
    <?php require_once 'footer.php'; ?>
</body>
</html>