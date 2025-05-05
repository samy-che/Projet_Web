<?php
// Inclusion du fichier de connexion et démarrage de la session
include 'connexion.php';
session_start();

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('location:../login.php');
    exit();
}

// Récupération de l'identifiant de l'utilisateur depuis la session
$user_id = $_SESSION['user_id'];

// Vérification si l'utilisateur demande à se déconnecter
if (isset($_GET['logout'])) {
    // Suppression de l'identifiant de l'utilisateur et destruction de la session
    unset($user_id);
    session_destroy();
    // Redirection vers la page d'accueil
    header('location:../acceuil.php');
    exit();
}

// Sécurisation de l'ID utilisateur pour éviter les injections SQL
$user_id = mysqli_real_escape_string($conn, $user_id);

// Sélection des informations de l'utilisateur connecté
$select = mysqli_query($conn, "SELECT * FROM `user_form` WHERE id = '$user_id'");
if (!$select) {
    die("Erreur lors de la récupération des données utilisateur: " . mysqli_error($conn));
}

// Vérification si l'utilisateur existe
if (mysqli_num_rows($select) == 0) {
    // L'utilisateur n'existe pas dans la base de données
    session_destroy();
    header('location:../login.php');
    exit();
}

// Récupération des données de l'utilisateur
$fetch = mysqli_fetch_assoc($select);

// Traitement de la demande de suppression de compte
if (isset($_GET['delete_account'])) {
    // Suppression du compte de l'utilisateur
    $delete = mysqli_query($conn, "DELETE FROM `user_form` WHERE id = '$user_id'");
    if (!$delete) {
        die("Erreur lors de la suppression du compte: " . mysqli_error($conn));
    }

    // Destruction de la session et redirection
    session_destroy();
    header('location:../acceuil.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="../styles/style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="../img/logo.png" type="image/x-icon">
    <title>Time Us - Profil</title>
    <style>
        .container-profil {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .profil {
            width: 100%;
            padding: 0;
        }

        .profil h3 {
            font-size: 24px;
            margin-bottom: 20px;
            color: var(--primaire);
        }

        .profil .btn,
        .profil .delete-btn {
            display: inline-block;
            margin: 15px;
            padding: 15px 30px;
            font-size: 18px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .profil .btn:hover,
        .profil .delete-btn:hover {
            transform: scale(1.05);
        }

        .user-info {
            margin: 20px 0 30px;
            text-align: left;
            padding: 0 20px;
        }

        .user-info p {
            margin: 10px 0;
            font-size: 18px;
        }

        .user-info strong {
            color: var(--noir);
            font-weight: 600;
        }

        /* Style pour les boutons d'action */
        .profil .btn {
            background-color: var(--primaire);
            color: white;
            text-decoration: none;
            font-weight: bold;
            min-width: 200px;
        }

        .profil .delete-btn {
            background-color: #ff3333;
            color: white;
            text-decoration: none;
            font-weight: bold;
            min-width: 200px;
        }
    </style>
</head>

<body>
    <div class="promo">
        <span>Promo de 15% avec le code : DAUPHINE15</span>
    </div>

    <header class="header">
        <nav class="nav container">
            <div class="navigation d-flex">
                <div class="icon1">
                    <i class='bx bx-menu'></i>
                </div>
                <div class="logo">
                    <a href="acceuil2.php"><span>Time</span> Us</a>
                </div>
                <div class="menu">
                    <div class="top">
                        <span class="fermer">Fermer <i class='bx bx-x'></i></span>
                    </div>
                    <ul class="nav-list d-flex">
                        <li class="nav-item">
                            <a href="acceuil2.php" class="nav-link">Accueil</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">Boutique</a>
                        </li>
                        <li class="nav-item">
                            <a href="apropos.php" class="nav-link">À propos</a>
                        </li>
                        <li class="nav-item">
                            <a href="contact.php" class="nav-link">Contact</a>
                        </li>
                    </ul>
                </div>
                <div class="icons d-flex">
                    <div>
                        <a href="profil.php"><i class='bx bx-user' style="color: var(--primaire);"></i></a>
                    </div>
                    <div>
                        <a href="panier.php"><i class='bx bx-shopping-bag'></i></a>
                    </div>
                    <div>
                        <a class="delete-btn" href="acceuil2.php?logout=<?php echo $user_id; ?>"
                            onclick="return confirm('Êtes-vous sûr de vouloir vous déconnecter ?');">Déconnexion</a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <div class="container-profil">
        <div class="profil">
            <h3>Profil de <?php echo htmlspecialchars($fetch['name']); ?></h3>

            <div class="user-info">
                <p><strong>Nom :</strong> <?php echo htmlspecialchars($fetch['name']); ?></p>
                <p><strong>Email :</strong> <?php echo htmlspecialchars($fetch['email']); ?></p>
            </div>

            <a href="update_profil.php" class="btn">Modifier mon compte</a>
            <a class="delete-btn" href="profil.php?delete_account"
                onclick="return confirm('Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.')">
                Supprimer mon compte
            </a>
        </div>
    </div>

    <footer>
        <div class="footer-content">
            <div class="footer-column">
                <h3>TIME us</h3>
                <p>Votre boutique en ligne pour tous vos besoins technologiques. Nous proposons une large gamme de
                    produits de qualité à des prix compétitifs.</p>
            </div>
            <div class="footer-column">
                <h3>Liens Rapides</h3>
                <ul class="footer-links">
                    <li><a href="acceuil2.php">Accueil</a></li>
                    <li><a href="profil.php">Mon profil</a></li>
                    <li><a href="apropos.php">À propos</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Nous Contacter</h3>
                <ul class="footer-links">
                    <li><i class="fas fa-envelope"></i> contact@timeus.com</li>
                    <li><i class="fas fa-phone"></i> +33 1 99 11 22 33</li>
                    <li><i class="fas fa-map-marker-alt"></i> 25 Rue Dauphine, Paris</li>
                </ul>
            </div>
        </div>
        <div class="copyright">
            &copy; <?php echo date('Y'); ?> TIME us. Tous droits réservés. | Réalisé par CHERIEF Yacine-Samy
        </div>
    </footer>

    <script>
        // Gestion du menu mobile
        document.addEventListener('DOMContentLoaded', function () {
            const menu = document.querySelector('.menu');
            const navOpen = document.querySelector('.bx-menu');
            const navClose = document.querySelector('.bx-x');

            if (navOpen) {
                navOpen.addEventListener('click', () => {
                    menu.classList.add('montrer');
                });
            }

            if (navClose) {
                navClose.addEventListener('click', () => {
                    menu.classList.remove('montrer');
                });
            }
        });
    </script>
</body>

</html>

<?php
// Fermeture de la connexion à la base de données
mysqli_close($conn);
?>