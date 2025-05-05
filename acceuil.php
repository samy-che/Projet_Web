<?php
session_start(); // démarrage de la session

include 'connexion.php'; // 
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" type="text/css" href="styles/style.css?v=<?php echo time(); ?>">
    <link rel="icon" href="img/logo.png" type="image/x-icon">
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
                            <a href="contact.php" class="nav-link" target="_blank" rel="noopener noreferrer">Contact</a>
                        </li>
                    </ul>
                </div>
                <div class="icons d-flex">
                    <div><a href="login.php"><i class='bx bx-user' aria-label="Connexion utilisateur"></i></a></div>
                    <div>
                        <i class='bx bx-shopping-bag' aria-label="Panier d'achat"></i>
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
                    <img src="img/setup.png" alt="Setup gaming premium" class="hero-image">
                </div>
            </div>
        </div>
    </header>

    <section id="products" class="products">
        <h1 class="title animate__animated animate__fadeIn">Notre Sélection Premium</h1>
        <div class="box-container">
            <?php
            $stmt = mysqli_prepare($conn, "SELECT * FROM `products`");
            mysqli_stmt_execute($stmt);
            $select_product = mysqli_stmt_get_result($stmt);
            if (mysqli_num_rows($select_product) > 0) {
                while ($fetch_product = mysqli_fetch_assoc($select_product)) {
                    ?>
                    <div class="boite animate__animated animate__fadeInUp">
                        <form class="box" action="" method="POST">
                            <a href="page.php?id=<?php echo $fetch_product['id']; ?>" class="product-link">
                                <div class="product-image">
                                    <img src="img/products/<?php echo $fetch_product['image']; ?>"
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
                            <input type="hidden" name="product_name" value="<?php echo $fetch_product['name']; ?>">
                            <input type="hidden" name="product_price" value="<?php echo $fetch_product['price']; ?>">
                            <button type="submit" name="add_panier" class="btn2">
                                <i class='bx bx-cart-add'></i> Ajouter au Panier
                            </button>
                        </form>
                    </div>
                    <?php
                }
            }
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
                    <li><a href="acceuil.php"><i class='bx bx-home'></i> Accueil</a></li>
                    <li><a href="login.php"><i class='bx bx-user'></i> Connexion</a></li>
                    <li><a href="apropos.php"><i class='bx bx-info-circle'></i> À propos</a></li>
                    <li><a href="contact.php"><i class='bx bx-envelope'></i> Contact</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Contactez-nous</h3>
                <ul class="footer-links">
                    <li><i class='bx bx-envelope'></i> contact@timeus.com</li>
                    <li><i class='bx bx-phone'></i> +33 1 23 45 67 89</li>
                    <li><i class='bx bx-map'></i> 25 Rue Dauphine, Paris</li>
                </ul>
            </div>
        </div>
        <div class="copyright">
            &copy; <?php echo date('Y'); ?> <span>Time</span> us. Tous droits réservés. | Réalisé par CHERIEF
            Yacine-Samy
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.location.pathname.includes('acceuil.php')) {
                const messageConnexion = "Veuillez vous connecter pour accéder à cette fonctionnalité.";
                const afficherAlerte = function () {
                    alert(messageConnexion);
                };

                const boutonPanier = document.querySelector(".bx-shopping-bag");
                if (boutonPanier) {
                    boutonPanier.addEventListener('click', afficherAlerte);
                }

                const boutonsAjout = document.querySelectorAll(".btn2");
                boutonsAjout.forEach(function (bouton) {
                    bouton.addEventListener('click', afficherAlerte);
                });
            }
        });
    </script>
</body>

</html>