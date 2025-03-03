<div id="addAvisModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Donnez votre avis</h2>
        <form action="/?controller=ControllerDetailResto&action=submitAvis" method="post">
            <input type="hidden" name="_method" value="POST">
            <input type="hidden" name="id" value="<?php $_GET["id"] ?>">
            <input type="hidden" name="form_type" value="add_avis">
            <label for="note">Note:</label>
            <select id="note" name="note" required>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            </select>
            
            <label for="content">Ajouter votre commentaire :</label>
            <textarea id="content" name="content" rows="4" required></textarea>
            <button type="submit" class="save-button">Ajouter votre Avis</button>
        </form>
    </div>
</div>