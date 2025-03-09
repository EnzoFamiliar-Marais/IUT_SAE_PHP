document.addEventListener('DOMContentLoaded', function() {
    const modals = {
        addAvisModal: document.getElementById("addAvisModal"), 
    };

    const buttons = {
        openAvisModal: document.getElementById("openAvisModal"),
    };

    const spans = document.getElementsByClassName("close");

    function openModal(modal) {
        modal.style.display = "block";
    }

    function closeModal(modal) {
        modal.style.display = "none";
    }


    buttons.openAvisModal.onclick = () => openModal(modals.addAvisModal);
   

    Array.from(spans).forEach(span => {
        span.onclick = function() {
            Object.values(modals).forEach(closeModal);
        }
    });

    window.onclick = function(event) {
        Object.values(modals).forEach(modal => {
            if (event.target == modal) {
                closeModal(modal);
            }
        });
    }

});