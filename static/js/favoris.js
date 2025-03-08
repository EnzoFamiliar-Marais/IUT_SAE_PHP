function toggleFavoris(event) {
    event.preventDefault();
    const btn = event.currentTarget;
    const restaurantId = btn.dataset.restaurantId;
    const icon = document.getElementById('favorisIcon');
    
    fetch(`/?controller=ControlleurDetailResto&action=toggleFavoris&id=${restaurantId}`)
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Réponse reçue:', data);  // Ajouté pour déboguer
        if (data.success) {
            icon.textContent = data.isFavoris ? '♥' : '♡';
            icon.classList.toggle('favoris-icon-active', data.isFavoris);
        } else {
            console.error('Erreur lors de la mise à jour des favoris');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        if (error.status === 401) {
            window.location.href = `/?controller=ControlleurLogin&action=view&id=${restaurantId}`;
        }
    });

}