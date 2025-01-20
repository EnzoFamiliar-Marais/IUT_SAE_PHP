# IUT_SAE_PHP

https://drive.google.com/file/d/1yWPntWR6Tiu30LsabS40O0C57CepPf1v/view?usp=sharing

Entités principales identifiées

    Restaurant
        id (PK) : Identifiant unique du restaurant (ex. osm_id ou un identifiant interne).
        nom : Nom du restaurant (ex. "Le Key West").
        type : Type d'établissement (ex. "bar").
        siret : Numéro SIRET (ex. 42160807600020).
        horaire_ouverture : Horaires d'ouverture (ex. "Tu-Sa 17:00-01:00, Su,Mo 17:00-23:00").
        accessibilite_handicapes : Accessibilité aux fauteuils roulants (ex. "no").
        internet : Types d'accès internet (ex. ["wlan"]).
        adresse_geo : Géolocalisation du restaurant (relation avec AdresseGeo).

    AdresseGeo
        id (PK) : Identifiant unique de l'adresse.
        longitude : Longitude (ex. 1.9100312).
        latitude : Latitude (ex. 47.90025169996137).
        commune_nom : Nom de la commune (ex. "Orléans").
        commune_code : Code de la commune (ex. 45234).
        departement_nom : Nom du département (ex. "Loiret").
        departement_code : Code du département (ex. 45).
        region_nom : Nom de la région (ex. "Centre-Val de Loire").
        region_code : Code de la région (ex. 24).

    Critique
        id (PK) : Identifiant unique de la critique.
        note : Note sur 5.
        commentaire : Texte de la critique.
        date : Date de la critique.
        utilisateur_id (FK) : Lien vers l'entité Utilisateur.
        restaurant_id (FK) : Lien vers l'entité Restaurant.

    Utilisateur
        id (PK) : Identifiant unique de l'utilisateur.
        nom : Nom de l'utilisateur.
        prenom : Prénom de l'utilisateur.
        email : Adresse email (nullable pour les visiteurs).
        mot_de_passe : Mot de passe (nullable pour les visiteurs).
        statut : Enumération pour différencier un utilisateur authentifié d’un visiteur.

Relations et cardinalités

    Restaurant ↔ AdresseGeo
        Un restaurant possède une seule adresse géographique.
        Une adresse géographique peut être liée à un seul restaurant.
        (1,1) ↔ (1,1).

    Restaurant ↔ Critique
        Un restaurant peut recevoir plusieurs critiques.
        Une critique concerne un seul restaurant.
        (1,N) ↔ (N,1).

    Utilisateur ↔ Critique
        Un utilisateur peut effectuer plusieurs critiques.
        Une critique est liée à un seul utilisateur.
        (1,N) ↔ (N,1).
