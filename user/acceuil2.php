<?php
session_start(); // Démarrage de la session - doit être avant toute sortie HTML

include 'connexion.php'; // Inclusion du fichier de connexion à la base de données

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('location:../login.php'); // Redirection vers la page de connexion
    exit;
}

$user_id = $_SESSION['user_id']; // Récupération de l'ID de l'utilisateur depuis la session

if (isset($_GET['logout'])) { // Vérification si la requête GET contient 'logout'
    session_destroy(); // Destruction de la session
    header('location:../acceuil.php'); // Redirection vers la page d'accueil
    exit;
}
;

if (isset($_POST['add_panier'])) { // Vérification si le formulaire d'ajout au panier a été soumis
    $product_id = $_POST['product_id']; // Récupération de l'ID du produit depuis le formulaire
    $product_name = $_POST['product_name']; // Récupération du nom du produit depuis le formulaire
    $product_price = $_POST['product_price']; // Récupération du prix du produit depuis le formulaire
    $product_image = $_POST['product_image']; // Récupération de l'image du produit depuis le formulaire
    $product_quantity = $_POST['product_quantity']; // Récupération de la quantité du produit depuis le formulaire

    $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('Erreur de requête'); // Requête pour vérifier si le produit est déjà dans le panier

    // Vérification du stock avec une requête préparée
    $stmt_stock = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
    $stmt_stock->bind_param("i", $product_id);
    $stmt_stock->execute();
    $select_stock = $stmt_stock->get_result();
    $fetch_stock = mysqli_fetch_assoc($select_stock);

    if ($fetch_stock["quantity"] <= 0) { // Vérification si la quantité en stock
        $message[] = 'Vous ne pouvez pas ajouter un produit en rupture de stock !';
    } else {
        // Vérification si le produit est déjà dans le panier avec une requête préparée
        $stmt_cart = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
        $stmt_cart->bind_param("si", $product_name, $user_id);
        $stmt_cart->execute();
        $select_cart = $stmt_cart->get_result();

        if (mysqli_num_rows($select_cart) > 0) { // Vérification si le produit est déjà dans le panier
            $message[] = 'Le produit a déjà été ajouté dans le panier !'; // Message d'erreur
        } else {
            // Insertion du produit dans le panier avec une requête préparée
            $stmt_insert = $conn->prepare("INSERT INTO `cart`(user_id, product_id, name, price, image, quantity) VALUES(?, ?, ?, ?, ?, ?)");
            $stmt_insert->bind_param("iisdsi", $user_id, $product_id, $product_name, $product_price, $product_image, $product_quantity);
            $stmt_insert->execute();

            $message[] = 'Le produit a été ajouté dans le panier'; // Message de succès
        }
    }


}

?>

<?php

if (isset($message)) { // Vérification si des messages sont présents
    foreach ($message as $message) { // Parcours de chaque message
        echo '<div class="message" onclick="this.remove();">' . $message . '</div>'; // Affichage des messages
    }
}

?>

<?php
// Utilisation d'une requête préparée pour récupérer les informations de l'utilisateur
$stmt = $conn->prepare("SELECT * FROM `user_form` WHERE ID = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$select_user = $stmt->get_result();

if (mysqli_num_rows($select_user) > 0) { // Vérification si l'utilisateur existe
    $fetch_user = mysqli_fetch_assoc($select_user); // Récupération des données de l'utilisateur
} else {
    // Si l'utilisateur n'existe pas dans la base de données malgré la session
    session_destroy();
    header('location:../login.php');
    exit;
}
?>



<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" type="text/css" href="../styles/style.css?v=<?php echo time(); ?>">
    <link rel="icon" href="../img/logo.png" type="image/x-icon">
    <title>Time us - Votre Boutique Tech</title>
</head>

<body>

    <div class="promo animate__animated animate__fadeIn">
        <span>🎉 Offre Spéciale : 15% de réduction avec le code DAUPHINE15 🎉</span>
    </div>

    <header class="header">
        <nav class="nav container">
            <div class="navigation d-flex">
                <div class="logo">
                    <a href="#"><span>Time</span> us</a>
                </div>
                <div class="menu">
                    <ul class="nav-list d-flex">
                        <li class="nav-item">
                            <a href="#" class="nav-link active">Accueil</a>
                        </li>
                        <li class="nav-item">
                            <a href="#products" class="nav-link">Boutique</a>
                        </li>
                        <li class="nav-item">
                            <a href="apropos.php" class="nav-link">À propos</a>
                        </li>
                        <li class="nav-item">
                            <a href="contact.php" class="nav-link" target='_BLANK'>Contact</a>
                        </li>
                    </ul>
                </div>
                <div class="icons d-flex">
                    <div class="username"><a href="profil.php"><?php echo $fetch_user['name']; ?></a></div>
                    <div>
                        <a href="panier.php"><i class='bx bx-shopping-bag' aria-label="Panier d'achat"></i></a>
                    </div>
                    <div>
                        <a href="historique.php"><i class='bx bxs-book-content' aria-label="Historique des commandes"></i></a>
                    </div>
                    <div>
                        <a class="delete-btn" href="../acceuil.php?logout=<?php echo $user_id; ?>"
                            onclick="return confirm('Es-tu sûr de te déconnecter ?');">Déconnexion</a>
                    </div>
                </div>
            </div>
        </nav>

        <div class="banniere">
            <div class="banniere-contenu d-flex container">
                <div class="gauche animate__animated animate__fadeInLeft">
                    <span class="Sous-titre">Découvrez notre Nouvelle Collection</span>
                    <h1 class="titre">
                        Équipez-vous avec
                        <span class="couleur">15%<br>
                            de réduction</span>
                        sur notre sélection premium
                    </h1>
                    <h5>Offre valable jusqu'au 21 juin 2025</h5>
                    <a href="#products" class="btn animate__animated animate__pulse">Explorer la
                        Collection</a>
                </div>
                <div class="droite animate__animated animate__fadeInRight">
                    <img src="../img/setup.png" alt="Setup gaming premium" class="hero-image">
                </div>
            </div>
        </div>
    </header>

    <section id="products" class="products">
        <h1 class="title animate__animated animate__fadeIn">Notre Sélection Premium</h1>
        <div class="box-container">

            <?php
            // Utilisation d'une requête préparée pour récupérer tous les produits
            $stmt_product = $conn->prepare("SELECT * FROM `products`");
            $stmt_product->execute();
            $select_product = $stmt_product->get_result();

            if (mysqli_num_rows($select_product) > 0) { // Vérification si des produits sont présents
                while ($fetch_product = mysqli_fetch_assoc($select_product)) { // Parcours des produits
                    ?>
                    <div class="boite animate__animated animate__fadeInUp">
                        <form class="box" action="" method="POST">
                            <a href="page.php?id=<?php echo $fetch_product['id']; ?>" class="product-link">
                                <div class="product-image">
                                    <img src="../img/products/<?php echo $fetch_product['image']; ?>"
                                        alt="<?php echo $fetch_product['name']; ?>">
                                </div>
                                <div class="product-info">
                                    <div class="name"><?php echo $fetch_product['name']; ?></div>
                                    <div class="price"><?php echo $fetch_product['price']; ?>€</div>
                                    <div class="description">
                                        <ul>
                                            <?php
                                            $liste = explode(";", $fetch_product['description']);
                                            foreach ($liste as $element) {
                                                echo "<li>" . $element . "</li>";
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                </div>
                            </a>
                            <div class="stock <?php echo $fetch_product['quantity'] > 0 ? 'in-stock' : 'out-of-stock'; ?>">
                                <?php
                                if ($fetch_product['quantity'] > 0) {
                                    echo "<i class='bx bx-check-circle'></i> En stock : " . $fetch_product['quantity'];
                                } else {
                                    echo "<i class='bx bx-x-circle'></i> Rupture de Stock";
                                }
                                ?>
                            </div>
                            <div class="quantity-control">
                                <input type="number" name="product_quantity" min="1"
                                    max="<?php echo $fetch_product['quantity']; ?>" value="1" required>
                            </div>
                            <input type="hidden" name="product_image" value="<?php echo $fetch_product['image']; ?>">
                            <input type="hidden" name="product_id" value="<?php echo $fetch_product['id']; ?>">
                            <input type="hidden" name="product_name" value="<?php echo $fetch_product['name']; ?>">
                            <input type="hidden" name="product_price" value="<?php echo $fetch_product['price']; ?>">
                            <button type="submit" name="add_panier" class="btn2">
                                <i class='bx bx-cart-add'></i> Ajouter au Panier
                            </button>
                        </form>
                    </div>
                    <?php
                }
                ;
            }
            ;
            ?>


        </div>
    </section>

    <footer class="footer">
        <div class="footer-content">
            <div class="footer-column">
                <h3><span>Time</span> us</h3>
                <p>Votre destination premium pour l'équipement tech. Nous sélectionnons avec soin les meilleurs produits
                    pour vous offrir une expérience d'achat exceptionnelle.</p>
                <div class="social-links">
                    <a href="#"><i class='bx bxl-facebook'></i></a>
                    <a href="#"><i class='bx bxl-instagram'></i></a>
                    <a href="#"><i class='bx bxl-twitter'></i></a>
                </div>
            </div>
            <div class="footer-column">
                <h3>Navigation</h3>
                <ul class="footer-links">
                    <li><a href="acceuil2.php"><i class='bx bx-home'></i> Accueil</a></li>
                    <li><a href="profil.php"><i class='bx bx-user'></i> Profil</a></li>
                    <li><a href="apropos.php"><i class='bx bx-info-circle'></i> À propos</a></li>
                    <li><a href="contact.php"><i class='bx bx-envelope'></i> Contact</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Contactez-nous</h3>
                <ul class="footer-links">
                    <li><i class='bx bx-envelope'></i> contact@timeus.com</li>
                    <li><i class='bx bx-phone'></i> +33 1 99 11 22 33</li>
                    <li><i class='bx bx-map'></i> 25 Rue Dauphine, Paris</li>
                </ul>
            </div>
        </div>
        <div class="copyright">
            &copy; <?php echo date('Y'); ?> <span>Time</span> us. Tous droits réservés. | Réalisé par CHERIEF
            Yacine-Samy
        </div>
    </footer>
</body>

</html>

<?php
// Fermeture de la connexion à la base de données
mysqli_close($conn);
?>