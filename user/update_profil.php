<?php

require_once 'connexion.php';
session_start();


if (!isset($_SESSION['user_id'])) {

    header('Location: login.php');
    exit();
}


$user_id = $_SESSION['user_id'];
$messages = [];
$userData = null;


if (isset($_GET['logout'])) {

    $_SESSION = [];
    session_destroy();
    
    header('Location: acceuil2.php');
    exit();
}

function getUserData($connection, $userId) {
    $stmt = $connection->prepare("SELECT * FROM user_form WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    
    return null;
}

$userData = getUserData($conn, $user_id);

if (isset($_POST['update_profil'])) {
    $formData = [
        'name' => filter_input(INPUT_POST, 'update_name', FILTER_SANITIZE_STRING),
        'email' => filter_input(INPUT_POST, 'update_email', FILTER_SANITIZE_EMAIL),
        'numrue' => filter_input(INPUT_POST, 'update_numrue', FILTER_SANITIZE_NUMBER_INT),
        'nomrue' => filter_input(INPUT_POST, 'update_nomrue', FILTER_SANITIZE_STRING),
        'ville' => filter_input(INPUT_POST, 'update_ville', FILTER_SANITIZE_STRING),
        'codepostal' => filter_input(INPUT_POST, 'update_codepostal', FILTER_SANITIZE_STRING)
    ];
    
    $updateQuery = $conn->prepare("UPDATE user_form SET 
                                name = ?, 
                                email = ?, 
                                numrue = ?, 
                                nomrue = ?, 
                                ville = ?, 
                                codepostal = ? 
                                WHERE id = ?");
    
    if ($updateQuery) {
        $updateQuery->bind_param("ssisssi", 
            $formData['name'], 
            $formData['email'], 
            $formData['numrue'], 
            $formData['nomrue'], 
            $formData['ville'], 
            $formData['codepostal'], 
            $user_id
        );
        
        if ($updateQuery->execute()) {
            $messages[] = "Vos informations personnelles ont été mises à jour avec succès.";
        } else {
            $messages[] = "Erreur lors de la mise à jour des informations: " . $conn->error;
        }
    }
    
    $currentPassword = filter_input(INPUT_POST, 'update_pass', FILTER_SANITIZE_STRING);
    $newPassword = filter_input(INPUT_POST, 'new_pass', FILTER_SANITIZE_STRING);
    $confirmPassword = filter_input(INPUT_POST, 'cnew_pass', FILTER_SANITIZE_STRING);
    
    if (!empty($currentPassword) || !empty($newPassword) || !empty($confirmPassword)) {
        if (!password_verify($currentPassword, $userData['password'])) {
            $messages[] = "Erreur: Le mot de passe actuel est incorrect.";
        } 
        elseif ($newPassword !== $confirmPassword) {
            $messages[] = "Erreur: Les nouveaux mots de passe ne correspondent pas.";
        } 
        else {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            
            $passwordUpdate = $conn->prepare("UPDATE user_form SET password = ? WHERE id = ?");
            if ($passwordUpdate) {
                $passwordUpdate->bind_param("si", $hashedPassword, $user_id);
                
                if ($passwordUpdate->execute()) {
                    $messages[] = "Votre mot de passe a été modifié avec succès.";
                } else {
                    $messages[] = "Erreur lors de la mise à jour du mot de passe: " . $conn->error;
                }
            }
        }
    }
    
    $userData = getUserData($conn, $user_id);
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
    <title>Time Us - Modifier Votre Profil</title>
    <style>
        .update-profil {
            min-height: 100vh;
            background-color: #eee;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .update-profil form {
            width: 700px;
            background-color: var(--blanche);
            border-radius: 5px;
            box-shadow: var(--box-shadow);
            padding: 20px;
        }
        
        .update-profil form .flex {
            display: flex;
            gap: 15px;
            justify-content: space-between;
        }
        
        .update-profil form .inputBox {
            width: 49%;
        }
        
        .update-profil form .box {
            width: 100%;
            padding: 12px 14px;
            border-radius: 5px;
            border: var(--border);
            margin: 10px 0;
            background-color: #eee;
        }
        
        .update-profil form label {
            display: block;
            font-size: 1.8rem;
            margin-top: 10px;
            color: var(--noir);
        }
        
        .btn3 {
            width: 100%;
            background-color: var(--primaire);
            color: var(--blanche);
            padding: 12px;
            font-size: 1.8rem;
            margin-top: 10px;
            border-radius: 5px;
            cursor: pointer;
            border: none;
            transition: all 0.3s ease;
        }
        
        .btn3:hover {
            background-color: var(--noir);
        }
        

        .message {
            position: sticky;
            top: 0;
            margin: 0 auto;
            max-width: 1200px;
            background-color: var(--blanche);
            padding: 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 10000;
            gap: 1.5rem;
            border-radius: 5px;
            box-shadow: var(--box-shadow);
            margin-bottom: 10px;
            animation: fadeIn 0.5s ease;
        }
        
        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(-10px); }
            100% { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body>

    <div class="promo">
        <span>Promo de 15% avec le code : DAUPHINE15</span>
    </div>

    <?php

    if (!empty($messages)) {
        foreach ($messages as $msg) {
            echo '<div class="message" onclick="this.remove();">'. $msg .'</div>';
        }
    }
    ?>

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
    
    <script>

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

    <div class="update-profil">
        <form action="" method="POST">
            <h3>Modifier votre profil</h3>
            <div class="flex">
                <div class="inputBox">
                    <label for="update_name">Nom</label>
                    <input class="box" type="text" name="update_name" value="<?php echo htmlspecialchars($userData['name'] ?? ''); ?>">
                    
                    <label for="update_email">Email</label>
                    <input class="box" type="text" name="update_email" value="<?php echo htmlspecialchars($userData['email'] ?? ''); ?>">
                    
                    <label for="update_numrue">Numéro de rue</label>
                    <input class="box" type="number" name="update_numrue" value="<?php echo htmlspecialchars($userData['numrue'] ?? ''); ?>">
                    
                    <label for="update_nomrue">Nom de la rue</label>
                    <input class="box" type="text" name="update_nomrue" value="<?php echo htmlspecialchars($userData['nomrue'] ?? ''); ?>">
                    
                    <label for="update_ville">Ville</label>
                    <input class="box" type="text" name="update_ville" value="<?php echo htmlspecialchars($userData['ville'] ?? ''); ?>">
                    
                    <label for="update_codepostal">Code Postal</label>
                    <input class="box" type="text" name="update_codepostal" value="<?php echo htmlspecialchars($userData['codepostal'] ?? ''); ?>">
                </div>
                
                <div class="inputBox">
                    <input type="hidden" name="old_pass" value="<?php echo htmlspecialchars($userData['password'] ?? ''); ?>">
                    
                    <label for="update_pass">Ancien Mot De Passe</label>
                    <input class="box" type="password" name="update_pass" placeholder="Entrez votre ancien mot de passe">
                    
                    <label for="new_pass">Nouveau Mot De Passe</label>
                    <input class="box" type="password" name="new_pass" placeholder="Entrez votre nouveau mot de passe">
                    
                    <label for="cnew_pass">Confirmer le Nouveau Mot De Passe</label>
                    <input class="box" type="password" name="cnew_pass" placeholder="Confirmez votre nouveau mot de passe">
                </div>
            </div>
            
            <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                <a href="profil.php" class="delete-btn" style="display: inline-block; padding: 10px 20px;">
                    <i class="fas fa-arrow-left"></i> Retour au profil
                </a>
                <input class="btn3" type="submit" value="Modifier votre compte" name="update_profil">
            </div>
        </form>
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
</body>

</html>

<?php

mysqli_close($conn);
?>