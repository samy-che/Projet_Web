<?php

include 'connexion.php'; // Inclut le fichier de connexion à la base de données
session_start(); // Démarre la session PHP

$user_id = $_SESSION['user_id']; // Récupère l'ID de l'utilisateur à partir de la session

if (isset($_GET['logout'])) { // Vérifie si le paramètre 'logout' est présent dans l'URL
    unset($user_id); // Supprime la variable 'user_id'
    session_destroy(); // Détruit toutes les données de session
    header('location:acceuil.php'); // Redirige vers la page d'accueil
}

$select_user = mysqli_query($conn, "SELECT * FROM `user_form` WHERE ID = '$user_id'") or die("Erreur de requête"); // Sélectionne les informations de l'utilisateur actuel
if (mysqli_num_rows($select_user) > 0) { // Vérifie s'il y a des résultats retournés
    $fetch_user = mysqli_fetch_assoc($select_user); // Récupère les données de l'utilisateur actuel
}

// MODIFIER QUANTITY
if (isset($_POST['update_cart'])) { // Vérifie si le formulaire de mise à jour du panier a été soumis
    $update_quantity = $_POST['cart_quantity']; // Récupère la nouvelle quantité du produit à partir du formulaire
    $update_id = $_POST['cart_id']; // Récupère l'ID du produit à mettre à jour à partir du formulaire

    mysqli_query($conn, "UPDATE `cart` SET quantity = '$update_quantity' WHERE id = '$update_id'") or die("Erreur de requête"); // Met à jour la quantité du produit dans le panier

    // Réinitialiser les variables de session liées au code promo pour forcer un recalcul
    if (isset($_SESSION['code_promo_applique']) && $_SESSION['code_promo_applique'] === true) {
        // On garde l'information que le code promo est appliqué mais on force le recalcul du montant
        unset($_SESSION['montant_promo']);
    }

    $message[] = 'La quantité a bien été correctement modifiée !'; // Ajoute un message de confirmation de modification de la quantité
}

//SUPPRIMER
if (isset($_GET['remove'])) { // Vérifie si le paramètre 'remove' est présent dans l'URL
    $remove_id = $_GET['remove']; // Récupère l'ID du produit à supprimer à partir de l'URL
    mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$remove_id'") or die('Erreur de requête'); // Supprime le produit du panier

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

if (isset($_GET['delete_all'])) { // Vérifie si le paramètre 'delete_all' est présent dans l'URL
    mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('Erreur de requête'); // Supprime tous les produits du panier de l'utilisateur

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

    if (isset($message)) { // Vérifie s'il y a des messages à afficher
        foreach ($message as $message) { // Parcourt tous les messages
            echo '<div class="message" onclick="this.remove();">' . $message . '</div>'; // Affiche chaque message dans une boîte de message avec la possibilité de le supprimer en cliquant dessus
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
                            <a href="acceuil2.php" class="nav-link">Retour</a>
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
                        <a class="delete-btn" href="../index.php?logout=<?php echo $user_id; ?>"
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
                    $total = 0; // Initialise la variable pour le total du panier
                    $cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die("Erreur de requête"); // Sélectionne tous les produits dans le panier de l'utilisateur
                    if (mysqli_num_rows($cart) > 0) { // Vérifie s'il y a des produits dans le panier
                        while ($fetch_cart = mysqli_fetch_assoc($cart)) { // Parcourt tous les produits du panier
                            ?>
                            <tr>
                                <td><img src="../img/products/<?php echo $fetch_cart['image'] ?>" height="100" alt=""></td>
                                <!-- Affiche l'image du produit -->
                                <td><?php echo $fetch_cart['name'] ?></td> <!-- Affiche le nom du produit -->
                                <td><?php echo $fetch_cart['price'] ?>€</td> <!-- Affiche le prix du produit -->
                                <td>
                                    <form action="" method="POST"> <!-- Formulaire pour mettre à jour la quantité du produit -->
                                        <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']; ?>">
                                        <!-- Champ caché contenant l'ID du produit -->
                                        <input type="number" min="1" name="cart_quantity"
                                            value="<?php echo $fetch_cart['quantity']; ?>">
                                        <!-- Champ pour entrer la quantité du produit -->
                                        <input type="submit" name="update_cart" value="Modifier" class="option-btn">
                                        <!-- Bouton pour soumettre le formulaire de mise à jour de la quantité -->
                                    </form>
                                </td>
                                <td><?php echo $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']) ?>€</td>
                                <!-- Affiche le total pour ce produit -->
                                <td><a class="delete-btn" href="panier.php?remove=<?php echo $fetch_cart['id']; ?>"
                                        onclick="return confirm('Veux-tu retirer du panier ?')">Supprimer</a></td>
                                <!-- Lien pour supprimer ce produit du panier -->
                            </tr>
                            <?php
                            $total += $sub_total; // Ajoute le total de ce produit au total général du panier
                        }
                        ;
                    } else {
                        echo '<tr><td style="padding:20px; text-transform:capitalize;" colspan="6">Votre panier est vide</td></tr>'; // Affiche un message si le panier est vide
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

                        if (isset($_POST["CodePromo"])) { // Vérifie si un code promo a été soumis
                            $CodePromo = strtoupper($_POST["CodePromo"]); // Convertit le code promo en majuscules
                            if ($CodePromo == "PARIS1") { // Vérifie si le code promo est valide
                                $code_promo_applique = true;
                                $total = $total * 0.85; // Applique une réduction de 15% sur le total
                                $montant_promo = $total_avant_promo - $total; // Calcul du montant de la promotion
                                $message[] = "Le code $CodePromo a été appliqué"; // Ajoute un message de confirmation de l'application du code promo
                                mysqli_query($conn, "UPDATE `cart` SET codepromo = 1 WHERE user_id = '$user_id'") or die('Erreur de requête'); // Marque le panier comme ayant utilisé un code promo
                        
                                // Stockage du montant de la promotion dans une variable de session pour l'afficher plus tard
                                $_SESSION['montant_promo'] = $montant_promo;
                                $_SESSION['code_promo_applique'] = true;
                            } else {
                                $message[] = "/!\ Le code $CodePromo ne fonctionne pas !"; // Ajoute un message d'erreur si le code promo n'est pas valide
                            }
                        } else {
                            // Vérification si un code promo a déjà été appliqué (en vérifiant la base de données)
                            $check_promo = mysqli_query($conn, "SELECT codepromo FROM `cart` WHERE user_id = '$user_id' AND codepromo = 1 LIMIT 1");
                            if (mysqli_num_rows($check_promo) > 0) {
                                $code_promo_applique = true;
                                $total = $total * 0.85; // Application de la réduction de 15%
                                $montant_promo = $total_avant_promo - $total; // Calcul du montant de la promotion
                        
                                // Stockage du montant de la promotion dans une variable de session
                                $_SESSION['montant_promo'] = $montant_promo;
                                $_SESSION['code_promo_applique'] = true;
                            }
                        }

                        // Si un code promo est appliqué, on affiche d'abord le prix sans la promotion
                        if ($code_promo_applique && $total_avant_promo > 0):
                            echo number_format($total_avant_promo, 2, '.', ''); // Affiche le total avant promotion avec 2 décimales et un point comme séparateur
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

                        echo number_format($total, 2, '.', ''); // Affiche le total du panier avec 2 décimales et un point comme séparateur
                        ?>€</td>
                        <td><a class="delete-btn" href="panier.php?delete_all"
                                onclick="return confirm('Veux-tu tout supprimer ?')">Tout supprimer</a></td>
                        <!-- Lien pour supprimer tous les produits du panier -->
                    </tr>
                </tbody>
            </table>
            <form class="container-promo" action="" method="POST"> <!-- Formulaire pour soumettre un code promo -->
                <label for="CodePromo">Code Promo</label>
                <input class="CodePromo" type="text" id="CodePromo" name="CodePromo">
                <!-- Champ pour entrer le code promo -->
                <button class="btn2" type="submit">Appliquer</button> <!-- Bouton pour soumettre le code promo -->
            </form>
            <div class="cart-btn">
                <a href="paiement.php" class="btn <?php echo ($total > 1) ? '' : 'disabled'; ?>">Confirmer le
                    Paiement</a> <!-- Lien pour passer à la page de paiement si le total est supérieur à 1 -->
            </div>
        </div>




</body>

</html>

<?php
// Fermeture de la connexion à la base de données
mysqli_close($conn);
?>