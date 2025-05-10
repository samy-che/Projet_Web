<?php
session_start(); 

include 'connexion.php'; 

if (!isset($_SESSION['user_id'])) {
    header('location:../login.php'); 
    exit;
}

$user_id = $_SESSION['user_id']; 

if (isset($_GET['logout'])) { 
    session_destroy(); 
    header('location:../acceuil.php'); 
    exit;
}
;

if (isset($_POST['add_panier'])) { 
    $product_id = $_POST['product_id']; 
    $product_name = $_POST['product_name']; 
    $product_price = $_POST['product_price']; 
    $product_image = $_POST['product_image']; 
    $product_quantity = $_POST['product_quantity']; 

    $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('Erreur de requête'); 

    $stmt_stock = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
    $stmt_stock->bind_param("i", $product_id);
    $stmt_stock->execute();
    $select_stock = $stmt_stock->get_result();
    $fetch_stock = mysqli_fetch_assoc($select_stock);

    if ($fetch_stock["quantity"] <= 0) { 
        $message[] = 'Vous ne pouvez pas ajouter un produit en rupture de stock !';
    } else {
        
        $stmt_cart = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
        $stmt_cart->bind_param("si", $product_name, $user_id);
        $stmt_cart->execute();
        $select_cart = $stmt_cart->get_result();

        if (mysqli_num_rows($select_cart) > 0) { 
            $message[] = 'Le produit a déjà été ajouté dans le panier !'; 
        } else {
            
            $stmt_insert = $conn->prepare("INSERT INTO `cart`(user_id, product_id, name, price, image, quantity) VALUES(?, ?, ?, ?, ?, ?)");
            $stmt_insert->bind_param("iisdsi", $user_id, $product_id, $product_name, $product_price, $product_image, $product_quantity);
            $stmt_insert->execute();

            $message[] = 'Le produit a été ajouté dans le panier'; 
        }
    }


}

?>

<?php

if (isset($message)) { 
    foreach ($message as $message) { 
        echo '<div class="message" onclick="this.remove();">' . $message . '</div>'; 
    }
}

?>

<?php
$stmt = $conn->prepare("SELECT * FROM `user_form` WHERE ID = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$select_user = $stmt->get_result();

if (mysqli_num_rows($select_user) > 0) {
    $fetch_user = mysqli_fetch_assoc($select_user);
} else {
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
    <!-- Ajout du style spécifique à la page d'accueil -->
    <link rel="stylesheet" type="text/css" href="../styles/acceuil2-style.css?v=<?php echo time(); ?>">
    <link rel="icon" href="../img/logo.png" type="image/x-icon">
    <title>Time us - Votre Boutique Tech</title>
</head>

<body>

    <div class="promo animate__animated animate__fadeIn">
        <span><i class='bx bx-gift'></i> Offre Spéciale : 15% de réduction avec le code DAUPHINE15 <i class='bx bx-gift'></i></span>
    </div>

    <header class="header">
        <nav class="nav container">
            <div class="navigation flex-container">
                <div class="logo-container">
                    <a href="#" class="brand-logo"><span class="highlight">Time</span>us</a>
                </div>
                <div class="main-menu">
                    <ul class="menu-items">
                        <li class="menu-link">
                            <a href="#" class="active"><i class='bx bx-home'></i> Accueil</a>
                        </li>
                        <li class="menu-link">
                            <a href="#products"><i class='bx bx-store'></i> Boutique</a>
                        </li>
                        <li class="menu-link">
                            <a href="apropos.php"><i class='bx bx-info-circle'></i> À propos</a>
                        </li>
                        <li class="menu-link">
                            <a href="contact.php"><i class='bx bx-envelope'></i> Contact</a>
                        </li>
                    </ul>
                </div>
                <div class="user-actions">
                    <div class="action-icon">
                        <a href="profil.php" title="Mon profil">
                            <i class='bx bx-user'></i>
                            <span class="icon-label"><?php echo $fetch_user['name']; ?></span>
                        </a>
                    </div>
                    <div class="action-icon">
                        <a href="panier.php" title="Mon panier">
                            <i class='bx bx-cart'></i>
                            <?php
                            $select_rows = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('Erreur de requête');
                            $row_count = mysqli_num_rows($select_rows);
                            echo "<span class='cart-count'>$row_count</span>";
                            ?>
                        </a>
                    </div>
                    <div class="action-icon">
                        <a href="historique.php" title="Historique des commandes">
                            <i class='bx bx-history'></i>
                        </a>
                    </div>
                    <div class="action-icon">
                        <a class="logout-btn" href="../acceuil.php?logout=<?php echo $user_id; ?>"
                            onclick="return confirm('Es-tu sûr de te déconnecter ?');">
                            <i class='bx bx-log-out'></i>
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <div class="hero-section">
            <div class="hero-content container">
                <div class="hero-text animate__animated animate__fadeInLeft">
                    <span class="tagline">Technologie Premium à Prix Imbattables</span>
                    <h1 class="headline">
                        Transformez votre <span class="accent">expérience</span> tech
                        <span class="discount-badge">15% OFF</span>
                    </h1>
                    <p class="hero-description">Découvrez notre sélection exclusive d'équipements high-tech soigneusement sélectionnés pour les passionnés.</p>
                    <p class="promo-period">Offre spéciale valable jusqu'au 21 juin 2025</p>
                    <a href="#products" class="cta-button animate__animated animate__pulse">Découvrir la Collection <i class='bx bx-right-arrow-alt'></i></a>
                </div>
                <div class="hero-visual animate__animated animate__fadeInRight">
                    <div class="image-wrapper">
                        <img src="../img/setup.png" alt="Setup gaming premium" class="featured-image">
                        <div class="image-overlay"></div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section id="products" class="product-showcase">
        <h1 class="section-title animate__animated animate__fadeIn">Découvrez Notre Collection Elite</h1>
        <div class="product-grid">

            <?php
            $stmt_product = $conn->prepare("SELECT * FROM `products`");
            $stmt_product->execute();
            $select_product = $stmt_product->get_result();

            if (mysqli_num_rows($select_product) > 0) {
                while ($fetch_product = mysqli_fetch_assoc($select_product)) {
                    ?>
                    <div class="product-card animate__animated animate__fadeInUp">
                        <form class="product-form" action="" method="POST">
                            <a href="page.php?id=<?php echo $fetch_product['id']; ?>" class="product-link">
                                <div class="product-image">
                                    <img src="../img/products/<?php echo $fetch_product['image']; ?>"
                                        alt="<?php echo $fetch_product['name']; ?>">
                                </div>
                                <div class="product-info">
                                    <h3 class="name"><?php echo $fetch_product['name']; ?></h3>
                                    <div class="price"><?php echo $fetch_product['price']; ?> €</div>
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
                            <button type="submit" name="add_panier" class="add-to-cart-btn">
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
mysqli_close($conn);
?>