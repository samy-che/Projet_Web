<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" type="text/css" href="styles/style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="img/logo.png" type="image/x-icon">
    <title>A propos</title>
</head>

<body>

    <header class="header">
        <nav class="nav container">
            <div class="navigation d-flex">
                <div class="logo">
                    <a href="acceuil.php"><span>Time</span> us</a>
                </div>
                <div class="menu">
                    <ul class="nav-list d-flex">
                        <li class="nav-item">
                            <a href="acceuil.php" class="nav-link">Accueil</a>
                        </li>
                        <li class="nav-item">
                            <a href="acceuil.php#products" class="nav-link">Boutique</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">A propos</a>
                        </li>
                        <li class="nav-item">
                            <a href="contact.php" target="_BLANK" class="nav-link">Contact</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <section class="section service">
        <div class="service-center container">
            <div class="service">
                <span class="icon">
                    <div><i class='bx bx-purchase-tag'></i></div>
                </span>
                <h4>Livraison gratuite</h4>
                <span class="text">Commande supérieure à partir de 100€</span>
            </div>

            <div class="service">
                <span class="icon">
                    <div><i class='bx bx-lock'></i></div>
                </span>
                <h4>Paiement sécurisé</h4>
                <span class="text">Moyens de paiement divers</span>
            </div>

            <div class="service">
                <span class="icon">
                    <div><i class='bx bxs-left-arrow-circle'></i></div>
                </span>
                <h4>14 Jours pour retour</h4>
                <span class="text">Produits 100% garantis</span>
            </div>

            <div class="service">
                <span class="icon">
                    <div><i class='bx bx-headphone'></i></div>
                </span>
                <h4>24/7 Support</h4>
                <span class="text">Assistance Client</span>
            </div>
        </div>
    </section>

    <main>
        <section class="about-section container">
            <div class="title">
                <h1>Notre Histoire</h1>
            </div>

            <div class="about-content">
                <div class="about-text">
                    <h2>Bienvenue chez Time Us</h2>
                    <p>Fondée en 2025, <strong>Time Us</strong> est née dans le cadre d'un projet universitaire,
                        l'objectif est de
                        rendre accessible des montres de qualité à tous les amateurs d'élégance et de précision.</p>

                    <p>Notre mission est simple : proposer une sélection rigoureuse des plus belles montres, alliant
                        design, qualité et innovation, tout en offrant un service client irréprochable.</p>

                    <p>Chaque montre que nous proposons est sélectionnée avec soin par nos experts, garantissant ainsi à
                        nos clients des produits d'exception qui traverseront le temps.</p>
                </div>
            </div>
        </section>

        <section class="values-section container">
            <div class="title">
                <h2>Nos Valeurs</h2>
            </div>

            <div class="values-content">
                <div class="value">
                    <i class='bx bx-medal'></i>
                    <h3>Excellence</h3>
                    <p>Nous ne proposons que des produits de la plus haute qualité</p>
                </div>

                <div class="value">
                    <i class='bx bx-check-shield'></i>
                    <h3>Authenticité</h3>
                    <p>Toutes nos montres sont garanties authentiques</p>
                </div>

                <div class="value">
                    <i class='bx bx-heart'></i>
                    <h3>Service</h3>
                    <p>Nous plaçons la satisfaction client au cœur de notre démarche</p>
                </div>

                <div class="value">
                    <i class='bx bx-bulb'></i>
                    <h3>Innovation</h3>
                    <p>Nous sommes constamment à la recherche des dernières tendances</p>
                </div>
            </div>
        </section>
    </main>

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
                    <li><i class="fas fa-envelope"></i> contact@timeus.com</li>
                    <li><i class="fas fa-phone"></i> +33 1 23 45 67 89</li>
                    <li><i class="fas fa-map-marker-alt"></i> 123 Rue du Commerce, Paris</li>
                </ul>
            </div>
        </div>
        <div class="copyright">
            &copy; <?php echo date('Y'); ?> TIME us. Tous droits réservés. | Réalisé par CHERIEF Yacine-Samy
        </div>
    </footer>

    <script src="js/script.js"></script>
</body>

</html>