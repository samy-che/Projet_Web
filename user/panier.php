<?php

include 'connexion.php'; 
session_start(); 


if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id']; 
} else {
    // Redirection vers la page de connexion si l'utilisateur n'est pas connecté
    header('location:../login.php');
    exit();
}

if (isset($_GET['logout'])) {
    unset($user_id);
    session_destroy();
    header('location:acceuil.php');
}

$select_user = mysqli_query($conn, "SELECT * FROM `user_form` WHERE ID = '$user_id'") or die("Erreur de requête");
if (mysqli_num_rows($select_user) > 0) {
    $fetch_user = mysqli_fetch_assoc($select_user);
}

// MODIFIER QUANTITY
if (isset($_POST['update_cart'])) { 
    $update_quantity = $_POST['cart_quantity']; 
    $update_id = $_POST['cart_id']; 

    mysqli_query($conn, "UPDATE `cart` SET quantity = '$update_quantity' WHERE id = '$update_id'") or die("Erreur de requête"); 

    // Réinitialiser les variables de session liées au code promo pour forcer un recalcul
    if (isset($_SESSION['code_promo_applique']) && $_SESSION['code_promo_applique'] === true) {
        // On garde l'information que le code promo est appliqué mais on force le recalcul du montant
        unset($_SESSION['montant_promo']);
    }

    $message[] = 'La quantité a été modifiee';
}

//SUPPRIMER
if (isset($_GET['remove'])) {
    $remove_id = $_GET['remove'];
    mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$remove_id'") or die('Erreur de requête');

    // Vérifier s'il reste des articles dans le panier
    $check_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'");
    if (mysqli_num_rows($check_cart) == 0) {
        // Si le panier est vide, réinitialiser les variables de session liées au code promo
        unset($_SESSION['code_promo_applique']);
        unset($_SESSION['montant_promo']);
        mysqli_query($conn, "UPDATE `cart` SET codepromo = 0 WHERE user_id = '$user_id'");
    }

    header('location:panier.php'); // Redirige vers la page du panier
}

if (isset($_GET['delete_all'])) {
    mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('Erreur de requête');

    // Réinitialiser les variables de session liées au code promo
    unset($_SESSION['code_promo_applique']);
    unset($_SESSION['montant_promo']);

    header('location:panier.php'); // Redirige vers la page du panier
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="../styles/style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="../img/logo.png" type="image/x-icon">
    <title>Time Us - Panier</title>
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
                    <a href="#"><span>Time</span> Us</a>
                </div>
                <div class="menu">
                    <div class="top">
                        <span class="fermer">Fermer <i class='bx bx-x'></i></span>
                    </div>
                    <ul class="nav-list d-flex">
                        <li class="nav-item"></li>
                        <li class="nav-item">
                            <a href="acceuil2.php" class="nav-link" style="font-size: 1.6rem;">Retour</a>
                        </li>
                    </ul>
                </div>
                <div class="icons d-flex">
                    <div class="username">
                        <a href="profil.php" target='_BLANK'
                            style="font-size: 1.6rem;"><?php echo $fetch_user['name']; ?></a>
                    </div>
                    <div>
                        <a href="panier.php" style="font-size: 1.6rem;"><i class='bx bx-shopping-bag'></i></a>
                    </div>
                    <div>
                        <a class="delete-btn" href="../acceuil.php?logout=<?php echo $user_id; ?>"
                            onclick="return confirm('Es-tu sûr de te déconnecter ?');">Déconnexion</a>
                    </div>
                </div>
            </div>
        </nav>
        <div class="shopping-cart">
            <h1 class="title">Panier</h1>

            <table>
                <tbody>
                    <thead>
                        <th>Image</th>
                        <th>Nom</th>
                        <th>Prix</th>
                        <th>Quantité</th>
                        <th>Total</th>
                        <th>Action</th>
                    </thead>


                    <?php
                    $total = 0;
                    $cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die("Erreur de requête");
                    if (mysqli_num_rows($cart) > 0) {
                        while ($fetch_cart = mysqli_fetch_assoc($cart)) {
                            ?>
                            <tr>
                                <td><img src="../img/products/<?php echo $fetch_cart['image'] ?>" height="100" alt=""></td>
                                <td><?php echo $fetch_cart['name'] ?></td>
                                <td><?php echo $fetch_cart['price'] ?>€</td>
                                <td>
                                    <form action="" method="POST">
                                        <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']; ?>">
                                        <input type="number" min="1" name="cart_quantity"
                                            value="<?php echo $fetch_cart['quantity']; ?>">
                                        <input type="submit" name="update_cart" value="Modifier" class="option-btn">
                                    </form>
                                </td>
                                <td><?php echo $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']) ?>€</td>
                                <td><a class="delete-btn" href="panier.php?remove=<?php echo $fetch_cart['id']; ?>"
                                        onclick="return confirm('Veux-tu retirer du panier ?')">Supprimer</a></td>
                            </tr>
                            <?php
                            $total += $sub_total;
                        }
                        ;
                    } else {
                        echo '<tr><td style="padding:20px; text-transform:capitalize;" colspan="6">Votre panier est vide</td></tr>';
                        // Si le panier est vide, réinitialiser les variables de session liées au code promo
                        unset($_SESSION['code_promo_applique']);
                        unset($_SESSION['montant_promo']);
                    }
                    ?>
                    <tr class="table-bottom">
                        <td colspan="4">Prix Total</td>
                        <td><?php
                        $total_avant_promo = $total; // Sauvegarde du total avant application du code promo
                        $code_promo_applique = false;

                        if (isset($_POST["CodePromo"])) {
                            $CodePromo = strtoupper($_POST["CodePromo"]);
                            if ($CodePromo == "DAUPHINE15") {
                                $code_promo_applique = true;
                                $total = $total * 0.85;
                                $montant_promo = $total_avant_promo - $total;
                                $message[] = "Le code $CodePromo a été appliqué";
                                mysqli_query($conn, "UPDATE `cart` SET codepromo = 1 WHERE user_id = '$user_id'") or die('Erreur de requête');
                        
                                // Stockage du montant de la promotion dans une variable de session pour l'afficher plus tard
                                $_SESSION['montant_promo'] = $montant_promo;
                                $_SESSION['code_promo_applique'] = true;
                            } else {
                                $message[] = "/!\ Le code $CodePromo ne fonctionne pas !";
                            }
                        } else {
                            // Vérification si un code promo a déjà été appliqué (en vérifiant la base de données)
                            $check_promo = mysqli_query($conn, "SELECT codepromo FROM `cart` WHERE user_id = '$user_id' AND codepromo = 1 LIMIT 1");
                            if (mysqli_num_rows($check_promo) > 0) {
                                $code_promo_applique = true;
                                $total = $total * 0.85;
                                $montant_promo = $total_avant_promo - $total;
                        
                                // Stockage du montant de la promotion dans une variable de session
                                $_SESSION['montant_promo'] = $montant_promo;
                                $_SESSION['code_promo_applique'] = true;
                            }
                        }

                        // Si un code promo est appliqué, on affiche d'abord le prix sans la promotion
                        if ($code_promo_applique && $total_avant_promo > 0):
                            echo number_format($total_avant_promo, 2, '.', '');
                            ?>€</td>
                            <td></td>
                        </tr>
                        <tr class="table-promo">
                            <td colspan="4">Promotion (-15%)</td>
                            <td style="color: #e74c3c;">-<?php echo number_format($montant_promo, 2, '.', ''); ?>€</td>
                            <td></td>
                        </tr>
                        <tr class="table-bottom">
                            <td colspan="4">Prix Total après promotion</td>
                            <td><?php
                        endif;

                        echo number_format($total, 2, '.', '');
                        ?>€</td>
                        <td><a class="delete-btn" href="panier.php?delete_all"
                                onclick="return confirm('Veux-tu tout supprimer ?')">Tout supprimer</a></td>
                    </tr>
                </tbody>
            </table>
            <form class="container-promo" action="" method="POST">
                <label for="CodePromo">Code Promo</label>
                <input class="CodePromo" type="text" id="CodePromo" name="CodePromo">
                <button class="btn2" type="submit">Appliquer</button>
            </form>
            <div class="cart-btn">
                <a href="paiement.php" class="btn <?php echo ($total > 1) ? '' : 'disabled'; ?>">Confirmer le
                    Paiement</a>
            </div>
        </div>




</body>

</html>

<?php

mysqli_close($conn);
?>