<?php
//Ce fichier permet d'afficher une page dédiée pour chaque produit à l'aide de la récupération du product_id

// Inclusion du fichier de connexion à la base de données et démarrage de la session PHP
include 'connexion.php';
session_start();

// Récupération de l'ID du produit depuis la requête GET
$product_id = $_GET["id"];
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="styles/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" type="text/css" href="styles/page.css?v=<?php echo time(); ?>">
    <link rel="icon" href="img/logo.png" type="image/x-icon">
    <title>TIME us - <?php
    // Récupération du nom du produit à partir de la base de données en fonction de son ID
    $select_product = mysqli_query($conn, "SELECT name FROM `products` WHERE id = $product_id ") or die("Erreur de requête");
    if (mysqli_num_rows($select_product) > 0) {
        $fetch_name = mysqli_fetch_assoc($select_product);
        echo $fetch_name['name'];
    }
    ?></title>
</head>

<body>

    <?php
    // Affichage des messages s'il y en a
    if (isset($message)) {
        foreach ($message as $message) {
            echo '<div class="message" onclick="this.remove();">' . $message . '</div>'; // Affichage des messages dans une boîte de message avec possibilité de suppression
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
                    <a href="acceuil.php"><span>Time</span> us</a>
                </div>
                <div class="menu">
                    <ul class="nav-list d-flex">
                        <li class="nav-item"></li>
                        <li class="nav-item">
                            <a href="acceuil.php" class="nav-link"><i class='bx bx-arrow-back'></i> Retour à
                                l'accueil</a>
                        </li>
                    </ul>
                </div>
                <div class="icons d-flex">
                    <div><a href="login.php" title="Se connecter"><i class='bx bx-user'></i></a></div>
                    <div>
                        <a href="#" title="Panier"><i class='bx bx-shopping-bag'></i></a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <div class="breadcrumb">
        <div class="container">
            <a href="acceuil.php">Accueil</a> >
            <span class="current-page">
                <?php
                // Récupération du nom du produit pour le fil d'Ariane
                $select_product = mysqli_query($conn, "SELECT name FROM `products` WHERE id = $product_id ") or die("Erreur de requête");
                if (mysqli_num_rows($select_product) > 0) {
                    $fetch_name = mysqli_fetch_assoc($select_product);
                    echo $fetch_name['name'];
                }
                ?>
            </span>
        </div>
    </div>

    <section class="products2">
        <div class="box-container2">
            <?php
            // Récupération et affichage des détails du produit à partir de la base de données en fonction de son ID
            $select_product = mysqli_query($conn, "SELECT * FROM `products` WHERE id = $product_id ") or die("Erreur de requête");
            if (mysqli_num_rows($select_product) > 0) {
                while ($fetch_product = mysqli_fetch_assoc($select_product)) {
                    ?>
                    <div class="product-card">
                        <div class="product-details">
                            <h1 class="product-title"><?php echo $fetch_product['name']; ?></h1>
                            <div class="product-price">
                                <span class="price-label">Prix :</span>
                                <span class="price-value"><?php echo $fetch_product['price']; ?>€</span>
                            </div>
                            <div
                                class="product-stock <?php echo ($fetch_product['quantity'] > 0) ? 'in-stock' : 'out-of-stock'; ?>">
                                <?php
                                // Vérification et affichage du stock du produit
                                if ($fetch_product['quantity'] > 0) {
                                    echo "<i class='bx bx-check-circle'></i> En stock : " . $fetch_product['quantity'] . " unités";
                                } else {
                                    echo "<i class='bx bx-x-circle'></i> Rupture de Stock";
                                }
                                ?>
                            </div>

                            <div class="product-description">
                                <h3>Caractéristiques :</h3>
                                <ul class="feature-list">
                                    <?php
                                    // Affichage de la description du produit
                                    $liste = explode(";", $fetch_product['description']);
                                    foreach ($liste as $element) {
                                        if (!empty(trim($element))) {
                                            echo "<li><i class='bx bx-chevron-right'></i> " . trim($element) . "</li>";
                                        }
                                    }
                                    ?>
                                </ul>
                            </div>

                            <div class="product-actions">
                                <form action="" method="POST" class="add-to-cart-form">
                                    <div class="quantity-selector">
                                        <label for="quantity">Quantité :</label>
                                        <input class="quantity-input" id="quantity" type="number" name="product_quantity"
                                            min="1" value="1">
                                    </div>
                                    <input type="hidden" name="product_image" value="<?php echo $fetch_product['image']; ?>">
                                    <input type="hidden" name="product_name" value="<?php echo $fetch_product['name']; ?>">
                                    <input type="hidden" name="product_price" value="<?php echo $fetch_product['price']; ?>">
                                    <button type="submit" class="add-to-cart-btn" name="add_panier">
                                        <i class='bx bx-cart-add'></i> Ajouter au Panier
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="product-image-container">
                            <img class="product-image" src="img/products/<?php echo $fetch_product['image']; ?>"
                                alt="<?php echo $fetch_product['name']; ?>">
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </section>

    <section class="reviews-section">
        <div class="container">
            <h2 class="section-title"><i class='bx bx-comment-detail'></i> Avis des Utilisateurs</h2>
            <div class="reviews-container">
                <?php
                // Récupération et affichage des commentaires sur le produit
                $select_commentaire = mysqli_query($conn, "SELECT * FROM commentaires WHERE product_id = $product_id");
                // Si le nombre total de commentaire est supérieur à 0 : on affiche tous les commentaires
                if (mysqli_num_rows($select_commentaire) > 0) {
                    while ($ligne = mysqli_fetch_assoc($select_commentaire)) {
                        ?>
                        <div class="review-card">
                            <div class="review-header">
                                <div class="reviewer-info">
                                    <div class="reviewer-avatar">
                                        <i class='bx bx-user-circle'></i>
                                    </div>
                                    <div class="reviewer-details">
                                        <h4 class="reviewer-name"><?php echo $ligne["name"]; ?></h4>
                                        <span class="review-date">Posté le
                                            <?php echo date("d/m/Y à H:i", strtotime($ligne["date_heure"])); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="review-content">
                                <?php echo $ligne["commentaire"]; ?>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <div class="no-reviews">
                        <i class='bx bx-message-x'></i>
                        <p>Aucun utilisateur n'a posté de commentaire.</p>
                        <a href="login.php" class="login-link">Connectez-vous pour ajouter votre avis !</a>
                    </div>
                    <?php
                }
                mysqli_close($conn);
                ?>
            </div>
        </div>
    </section>

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
                    <li><a href="acceuil.php">Accueil</a></li>
                    <li><a href="login.php">Connexion</a></li>
                    <li><a href="#">À propos</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Nous Contacter</h3>
                <ul class="footer-links">
                    <li><i class='bx bx-envelope'></i> contact@timeus.com</li>
                    <li><i class='bx bx-phone'></i> +33 1 23 45 67 89</li>
                    <li><i class='bx bx-map'></i> 123 Rue du Commerce, Paris</li>
                </ul>
            </div>
        </div>
        <div class="copyright">
            &copy; <?php echo date('Y'); ?> TIME us. Tous droits réservés. | Réalisé par CHERIEF Yacine-Samy
        </div>
    </footer>

    <script src="script/page.js"></script>
</body>

</html>