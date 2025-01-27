<?php
// Connexion à Supabase
$host = "aws-0-eu-west-3.pooler.supabase.com"; // Remplace par ton hôte Supabase
$port = "5432"; // Port par défaut
$dbname = "postgres"; // Remplace par le nom de ta base
$user = "postgres"; // Nom d'utilisateur (par défaut, postgres)
$password = "ENZOAMINEROMAINJEAN-MARC"; // Remplace par ton mot de passe




// Connexion PDO
try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;";
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    echo "Connexion réussie !<br>";
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Nom de la table à exporter
$table = "nom_de_ta_table"; // Remplace par le nom de ta table
$outputFile = "export_table.csv"; // Nom du fichier CSV à générer

// Requête SQL pour récupérer les données
$query = "SELECT * FROM $table";
$stmt = $pdo->prepare($query);
$stmt->execute();

// Vérification des résultats
if ($stmt->rowCount() > 0) {
    // Ouverture du fichier en mode écriture
    $file = fopen($outputFile, 'w');

    // Ajouter l'en-tête (colonnes)
    $columns = array_keys($stmt->fetch(PDO::FETCH_ASSOC));
    fputcsv($file, $columns);

    // Réexécuter la requête pour parcourir les lignes
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        fputcsv($file, $row);
    }

    fclose($file);
    echo "Exportation réussie ! Fichier généré : $outputFile";
} else {
    echo "Aucune donnée trouvée dans la table.";
}