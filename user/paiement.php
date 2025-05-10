<?php


include 'connexion.php';
session_start();

$user_id = $_SESSION['user_id'];


if (isset($_GET['logout'])) {
    session_destroy();
    header('location:../acceuil.php');
    exit;
}


$stmt = $conn->prepare("SELECT * FROM `user_form` WHERE ID = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$select_user = $stmt->get_result();

if (mysqli_num_rows($select_user) > 0) {

    $fetch_user = mysqli_fetch_assoc($select_user);
}

?>




<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="../styles/stylepaiement.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="../img/logo.png" type="image/x-icon">
    <title>Time Us - Paiement</title>
</head>

<body>

    <?php

    if (isset($message)) {
        foreach ($message as $message) {
            echo '<div class="message" onclick="this.remove();">' . $message . '</div>';
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

                        </li>
                        <li class="nav-item">
                            <a href="panier.php" class="nav-link">Retour</a>
                        </li>
                    </ul>
                </div>
                <div class="icons d-flex">
                    <div class="username"><a href="profil.php" target='_BLANK'><?php echo $fetch_user['name']; ?></a>
                    </div>
                    <div>
                        <a href="panier.php"><i class='bx bx-shopping-bag'></i></a>

                    </div>
                    <div>
                        <a class="delete-btn" href="../acceuil.php?logout=<?php echo $user_id; ?>"
                            onclick="return confirm('Es-tu sûr de te déconnecter ?');">Déconnexion</a>
                    </div>
                </div>
            </div>
    </header>

    <div class="form-container">
        <form action="confirmation.php" method="POST">
            <h3>Paiement</h3>
            <div class="paiement">
                <h4>Carte Bancaire</h4>
                <label for="numCarte">Numéro de Carte</label>
                <input class="box" type="text" name="numCarte" placeholder="XXXX XXXX XXXX XXXX" minlength="16"
                    maxlength="16" required>
                <label for="dateexpiration">Date d'expiration</label>
                <input class="box" type="month" name="dateexpiration" placeholder="XX/XX" required>
                <label for="nomCarte">Nom sur la Carte</label>
                <input class="box" type="text" name="nomCarte" placeholder="Nom Carte" required>
                <label for="codeSecret">Code Secret</label>
                <input class="box" type="password" name="codeSecret" placeholder="Code" minlength="3" maxlength="3"
                    required>
            </div>
            </br>
            <div class="facturation">
                <h4>Adresse de Livraison</h4>
                <label for="numrue">Numéro de rue</label>
                <input class="box" type="number" name="numrue" placeholder="Numéro de rue" min=1 required>
                <label for="nomrue">Nom de la rue</label>
                <input class="box" type="text" name="nomrue" placeholder="Nom de rue">
                <label for="ville">Ville</label>
                <input class="box" type="text" name="ville" placeholder="Ville" required>
                <label for="codepostal">Code Postal</label>
                <input class="box" type="text" name="codepostal" placeholder="Code Postal" required>
                <input type="submit" name="submit" class="btn2" value="Confirmer l'achat">
            </div>
        </form>
    </div>

</body>

</html>

<?php

mysqli_close($conn);
?>