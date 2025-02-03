<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau avec Actions</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        button {
            padding: 5px 10px;
            margin: 2px;
            cursor: pointer;
        }
        .visualiser {
            background-color: #4CAF50;
            color: white;
            border: none;
        }
        .supprimerCritique {
            background-color: #f44336;
            color: white;
            border: none;
        }
    </style>
</head>
<body>

    <h2>Tableau des Utilisateurs</h2>
    
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Nb de critiques</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr id="row-1">
                <td>Dupont</td>
                <td>Jean</td>
                <td>12</td>
                <td>
                    <button class="visualiser" onclick="visualiserCritiques('Jean')">Visualiser</button>
                </td>
            </tr>
            <tr id="row-2">
                <td>Martin</td>
                <td>Marie</td>
                <td>8</td>
                <td>
                    <button class="visualiser" onclick="visualiserCritiques('Marie')">Visualiser</button>
                </td>
            </tr>
            <tr id="row-3">
                <td>Bernard</td>
                <td>Paul</td>
                <td>20</td>
                <td>
                    <button class="visualiser" onclick="visualiserCritiques('Paul')">Visualiser</button>
                </td>
            </tr>
        </tbody>
    </table>

    <div id="critiqueModal" style="display:none;">
        <h3>Liste des critiques pour <span id="nomCritique"></span></h3>
        <ul id="critiqueList"></ul>
        <button onclick="fermerModal()">Fermer</button>
    </div>

    <script>
        // Liste de critiques simulée pour chaque personne
        const critiquesData = {
            "Jean": ["Critique 1", "Critique 2", "Critique 3"],
            "Marie": ["Critique A", "Critique B"],
            "Paul": ["Critique X", "Critique Y", "Critique Z", "Critique W"]
        };

        // Fonction pour afficher la liste des critiques
        function visualiserCritiques(nom) {
            const critiqueList = critiquesData[nom] || [];
            const critiqueListElement = document.getElementById('critiqueList');
            const nomCritiqueElement = document.getElementById('nomCritique');
            
            // Afficher le nom de la personne
            nomCritiqueElement.textContent = nom;

            // Vider la liste précédente
            critiqueListElement.innerHTML = '';
            
            // Ajouter les critiques à la liste
            critiqueList.forEach((critique, index) => {
                const li = document.createElement('li');
                li.textContent = critique;

                // Ajouter un bouton de suppression pour chaque critique
                const supprimerButton = document.createElement('button');
                supprimerButton.textContent = "Supprimer";
                supprimerButton.classList.add("supprimerCritique");
                supprimerButton.onclick = () => supprimerCritique(nom, index);

                li.appendChild(supprimerButton);
                critiqueListElement.appendChild(li);
            });

            // Afficher le modal
            document.getElementById('critiqueModal').style.display = 'block';
        }

        // Fonction pour supprimer une critique
        function supprimerCritique(nom, index) {
            critiquesData[nom].splice(index, 1);  // Supprimer la critique du tableau
            visualiserCritiques(nom);  // Réafficher la liste des critiques après suppression
        }

        // Fonction pour fermer le modal
        function fermerModal() {
            document.getElementById('critiqueModal').style.display = 'none';
        }
    </script>

</body>
</html>
