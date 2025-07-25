<?php


include 'connexion.php';
session_start();

$user_id = $_SESSION['user_id'];

if (isset($_GET['logout'])) {
    unset($user_id);
    session_destroy();
    header('location:acceuil.php');
}


$product_id = $_GET["id"];


if (isset($_POST['add_panier'])) { 
    $product_name = $_POST['product_name']; 
    $product_price = $_POST['product_price']; 
    $product_image = $_POST['product_image']; 
    $product_quantity = $_POST['product_quantity']; 

    $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('Erreur de requête'); 

    $select_stock = mysqli_query($conn, "SELECT * FROM `products` WHERE id = $product_id") or die('Erreur de requête');
    $fetch_stock = mysqli_fetch_assoc($select_stock);

    if ($fetch_stock["quantity"] <= 0) { 
        $message[] = 'Vous ne pouvez pas ajouter un produit en rupture de stock !';
    } else {
        if (mysqli_num_rows($select_cart) > 0) { 
            $message[] = 'Le produit a déjà été ajouté dans le panier !'; 
        } else {
            mysqli_query($conn, "INSERT INTO `cart`(user_id, product_id, name, price, image, quantity) VALUES('$user_id','$product_id', '$product_name', '$product_price', '$product_image','$product_quantity')") or die('Erreur de requête'); 
            $message[] = 'Le produit a été ajouté dans le panier'; 
        }
    }
}

$select_user = mysqli_query($conn, "SELECT * FROM `user_form` WHERE ID = '$user_id'") or die("Erreur de requête");
if (mysqli_num_rows($select_user) > 0) {
    $fetch_user = mysqli_fetch_assoc($select_user);
}

date_default_timezone_set('Europe/Paris');

if (isset($_POST['submit'])) {
    $nom = $_POST['nom'];
    $commentaire = mysqli_real_escape_string($conn, $_POST['commentaire']);
    $date_heure = date("Y-m-d H:i:s");

    mysqli_query($conn, "INSERT INTO commentaires(product_id, user_id, name, commentaire, date_heure) VALUES('$product_id', '$user_id','$nom','$commentaire', '$date_heure')");
    $message[] = "Votre commentaire a été posté !";
}

if (isset($_GET['commentaireid'])) {
    $delete_comment_id = $_GET['commentaireid'];
    mysqli_query($conn, "DELETE FROM commentaires WHERE id = $delete_comment_id AND user_id = $user_id");
    header("Location: page.php?id=$product_id");
    exit();
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="../styles/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" type="text/css" href="../styles/page.css?v=<?php echo time(); ?>">
    <link rel="icon" href="../img/logo.png" type="image/x-icon">
    <title>TIME us - <?php
    $select_product = mysqli_query($conn, "SELECT name FROM `products` WHERE id = $product_id ") or die("Erreur de requête");
    if (mysqli_num_rows($select_product) > 0) {
        $fetch_name = mysqli_fetch_assoc($select_product);
        echo $fetch_name['name'];
    }
    ?></title>
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
                    <a href="acceuil2.php"><span>Time</span> us</a>
                </div>
                <div class="menu">
                    <ul class="nav-list d-flex">
                        <li class="nav-item"></li>
                        <li class="nav-item">
                            <a href="acceuil2.php" class="nav-link"><i class='bx bx-arrow-back'></i> Retour à
                                l'accueil</a>
                        </li>
                    </ul>
                </div>
                <div class="icons d-flex">
                    <div class="username"><a href="profil.php"><?php echo $fetch_user['name']; ?></a></div>
                    <div>
                        <a href="panier.php" title="Panier"><i class='bx bx-shopping-bag'></i></a>
                    </div>
                    <div>
                        <a class="delete-btn" href="../acceuil.php?logout=<?php echo $user_id; ?>"
                            onclick="return confirm('Êtes-vous sûr de vouloir vous déconnecter ?');">Déconnexion</a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <div class="breadcrumb">
        <div class="container">
            <a href="acceuil2.php">Accueil</a> >
            <span class="current-page">
                <?php
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
                            <img class="product-image" src="../img/products/<?php echo $fetch_product['image']; ?>"
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

            <?php if (isset($user_id)) { ?>
                <div class="review-form">
                    <h3>Ajouter un commentaire</h3>
                    <form action="" method="POST">
                        <input type="hidden" name="nom" value="<?php echo $fetch_user['name']; ?>">
                        <textarea name="commentaire" placeholder="Saisissez votre commentaire" required></textarea>
                        <button type="submit" name="submit" class="submit-btn">Envoyer</button>
                    </form>
                </div>
            <?php } ?>

            <div class="reviews-container">
                <?php
                $select_commentaire = mysqli_query($conn, "SELECT * FROM commentaires WHERE product_id = $product_id ORDER BY date_heure DESC");
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
                                <?php if ($ligne['user_id'] == $user_id) { ?>
                                    <a href="page.php?id=<?php echo $product_id; ?>&commentaireid=<?php echo $ligne['id']; ?>"
                                        class="delete-review"
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?');">
                                        <i class='bx bx-trash'></i>
                                    </a>
                                <?php } ?>
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
                    </div>
                    <?php
                }
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
                    <li><a href="acceuil2.php">Accueil</a></li>
                    <li><a href="profil.php">Profil</a></li>
                    <li><a href="#">À propos</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Nous Contacter</h3>
                <ul class="footer-links">
                    <li><i class='bx bx-envelope'></i> contact@timeus.com</li>
                    <li><i class='bx bx-phone'></i> +33 1 99 11 22 33</li>
                    <li><i class='bx bx-map'></i> 123 Rue du Commerce, Paris</li>
                </ul>
            </div>
        </div>
        <div class="copyright">
            &copy; <?php echo date('Y'); ?> TIME us. Tous droits réservés. | Réalisé par CHERIEF Yacine-Samy
        </div>
    </footer>

    <script src="../script/user/page.js"></script>
</body>

</html>