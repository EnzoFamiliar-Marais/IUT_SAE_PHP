<header>
        <h1>IUTables'O</h1>
        <nav>
            <a href="?controller=ControlleurHome&action=view">Accueil</a>
            <a href="?controller=ControlleurResto&action=view">Les Restos</a>
            <div class="auth-buttons">
                <?php if (isset($_SESSION['auth'])): ?>
                    <a href="?controller=ControlleurCompte&action=view">Mon Compte</a>
                    <?php echo $formDeconnexion; ?>
                <?php else: ?>
                    <a href="?controller=ControlleurLogin&action=view">Connexion</a>
                    <a href="?controller=ControlleurRegister&action=view">S'inscrire</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>