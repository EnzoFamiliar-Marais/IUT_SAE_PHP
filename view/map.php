<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carte des Restaurants</title>
    <link rel="stylesheet" href="../static/css/index.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
</head>
<body>
    <header>
        <h1>IUTables’O</h1>
        <nav>
            <a href="/?controller=ControlleurHome&action=view">Accueil</a>
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
    <div id="map" style="height: 400px; margin: 20px;"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var map = L.map('map').setView([47.902964, 1.909251], 13); // Coordonnées de la région Orléanaise

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            var restaurants = <?php echo json_encode($restaurants); ?>;

            restaurants.forEach(function(restaurant) {
                var marker = L.marker([restaurant.latitude, restaurant.longitude]).addTo(map);
                marker.bindPopup('<b>' + restaurant.nom + '</b><br>' + restaurant.adresse);
            });
        });
    </script>
</body>
</html>