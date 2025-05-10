<?php

// Description: Affiche l'historique des commandes d'un utilisateur


session_start();
require_once 'connexion.php';


if (!isset($_SESSION['user_id'])) {
    header('location: acceuil.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['logout'])) {
    session_destroy();
    header('location: acceuil.php');
    exit();
}

$query = "SELECT * FROM user_form WHERE ID = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result && mysqli_num_rows($result) > 0) {
    $fetch_user = mysqli_fetch_assoc($result);
} else {
    $message[] = "Erreur lors de la récupération des données utilisateur";
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="../styles/stylehistorique.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="../img/logo.png" type="image/x-icon">
    <title>Time Us - Historique</title>
</head>

<body>

    <?php
    if (isset($message)) {
        foreach ($message as $msg) {
            echo '<div class="message" onclick="this.remove();">' . $msg . '</div>'; 
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
                    <a href="#"><span>Time</span> Us</a>
                </div>
                <div class="menu">
                    <div class="top">
                        <span class="fermer">Fermer <i class='bx bx-x'></i></span>
                    </div>
                    <ul class="nav-list d-flex">
                        <li class="nav-item">
                        </li>
                        <li class="nav-item">
                            <a href="acceuil2.php" class="nav-link">Retour à l'accueil</a>
                        </li>
                    </ul>
                </div>
                <div class="icons d-flex">
                    <div class="username"><a href="profil.php" target='_BLANK'><?php echo htmlspecialchars($fetch_user['name']); ?></a>
                    </div> <!-- Affichage du nom de l'utilisateur avec un lien vers son profil -->
                    <div>
                        <a href="panier.php"><i class='bx bx-shopping-bag'></i></a>
                        <!-- <span class = "align-center">0</span> -->
                    </div>
                    <div>
                        <a class="delete-btn" href="../acceuil.php?logout=<?php echo $user_id; ?>"
                            onclick="return confirm('Es-tu sûr de te déconnecter ?');">Déconnexion</a>
                        <!-- Bouton de déconnexion avec confirmation -->
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <div>
        <div class="container3">
            <div class="center-div">
                <h3>Historique Commande</h3>
                <?php
                $total = 0;

                $stmt = mysqli_prepare($conn, "SELECT * FROM orders WHERE user_id = ?");
                mysqli_stmt_bind_param($stmt, "i", $user_id);
                mysqli_stmt_execute($stmt);
                $select_order2 = mysqli_stmt_get_result($stmt);
                
                if (mysqli_num_rows($select_order2) > 0) {
                    while ($row2 = mysqli_fetch_assoc($select_order2)) {
                        $product_id = $row2['product_id'];
                        
                        // Utilisation d'une requête préparée pour plus de sécurité
                        $stmt_product = mysqli_prepare($conn, "SELECT * FROM products WHERE id = ?");
                        mysqli_stmt_bind_param($stmt_product, "i", $product_id);
                        mysqli_stmt_execute($stmt_product);
                        $select_product = mysqli_stmt_get_result($stmt_product);
                        
                        while ($row = mysqli_fetch_assoc($select_product)) {
                            echo htmlspecialchars($row['name']); // Affichage du nom du produit avec protection XSS
                            echo "<br>";
                            echo "<img class='img' src='../img/products/" . htmlspecialchars($row['image']) . "'>"; // Affichage de l'image du produit avec protection XSS
                
                            echo "<br>";
                            echo "Prix : " . htmlspecialchars($row['price']); // Affichage du prix du produit avec protection XSS
                            echo "<br>";
                            $total += $row['price'] * $row2['quantity']; // Calcul du total
                        }
                        echo "Quantité : " . htmlspecialchars($row2['quantity']) . "<br>"; // Affichage de la quantité commandée avec protection XSS
                        echo "Date : " . htmlspecialchars($row2['date']); // Affichage de la date de la commande avec protection XSS
                        echo "<br><br>";
                    }
                    
                    // Affichage du total des achats
                    echo "<div class='order-summary'><p class='total-amount'>Montant total de vos achats: <span>" . $total . " €</span></p></div>";
                    
                } else {
                    echo "<div class='no-orders'>Vous n'avez effectué aucune commande !</div>"; // Message si aucune commande n'a été effectuée
                    echo "<div class='empty-history'><a href='acceuil2.php' class='shop-now-btn'>Commencer vos achats</a></div>";
                }
                ?>
            </div>
        </div>
    </div>

    <!-- Pied de page -->
    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Time Us. Tous droits réservés.</p>
        </div>
    </footer>

</body>
</html>

<?php

mysqli_close($conn);
?>