<?php
session_start();

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
    <link rel="stylesheet" type="text/css" href="styles/accueil-style.css">
    <link rel="icon" href="img/logo.png" type="image/x-icon">
    <title>Time us - Votre Boutique Tech</title>
</head>

<body>
    <div class="promo animate__animated animate__fadeIn">
        <span><i class='bx bx-gift'></i> Offre Spéciale : 15% de réduction avec le code DAUPHINE15 <i
                class='bx bx-gift'></i></span>
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
                    <div class="action-icon"><a href="login.php"><i class='bx bx-user'
                                aria-label="Connexion utilisateur"></i></a></div>
                    <div class="action-icon">
                        <i class='bx bx-shopping-bag' aria-label="Panier d'achat"></i>
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
                    <p class="hero-description">Découvrez notre sélection exclusive d'équipements high-tech
                        soigneusement sélectionnés pour les passionnés.</p>
                    <p class="promo-period">Offre spéciale valable jusqu'au 21 juin 2025</p>
                    <a href="#products" class="cta-button animate__animated animate__pulse">Découvrir la Collection <i
                            class='bx bx-right-arrow-alt'></i></a>
                </div>
                <div class="hero-visual animate__animated animate__fadeInRight">
                    <div class="image-wrapper">
                        <img src="img/setup.png" alt="Setup gaming premium" class="featured-image">
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
            $stmt = mysqli_prepare($conn, "SELECT * FROM `products`");
            mysqli_stmt_execute($stmt);
            $select_product = mysqli_stmt_get_result($stmt);
            if (mysqli_num_rows($select_product) > 0) {
                while ($fetch_product = mysqli_fetch_assoc($select_product)) {
                    ?>
                    <div class="product-card animate__animated animate__fadeInUp">
                        <form class="product-form" action="" method="POST">
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
                            <button type="submit" name="add_panier" class="add-to-cart-btn">
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Vérification de la page actuelle
            if (window.location.pathname.includes('acceuil.php')) {
                // Message personnalisé avec notification stylée
                const notifierUtilisateur = function () {
                    // Création d'une notification stylée au lieu d'une simple alerte
                    const notification = document.createElement('div');
                    notification.className = 'notification';
                    notification.innerHTML = '<i class="bx bx-lock"></i> Veuillez vous connecter pour accéder à cette fonctionnalité.';
                    document.body.appendChild(notification);

                    // Animation de la notification
                    setTimeout(() => {
                        notification.classList.add('show');
                    }, 10);

                    // Suppression après 3 secondes
                    setTimeout(() => {
                        notification.classList.remove('show');
                        setTimeout(() => {
                            document.body.removeChild(notification);
                        }, 300);
                    }, 3000);
                };

                // Attachement des événements
                const boutonPanier = document.querySelector(".bx-shopping-bag");
                if (boutonPanier) {
                    boutonPanier.addEventListener('click', notifierUtilisateur);
                }

                const boutonsAjout = document.querySelectorAll(".add-to-cart-btn");
                boutonsAjout.forEach(function (bouton) {
                    bouton.addEventListener('click', function (e) {
                        e.preventDefault(); // Empêche la soumission du formulaire
                        notifierUtilisateur();
                    });
                });
            }
        });
    </script>
</body>

</html>