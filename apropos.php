<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" type="text/css" href="styles/style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="img/logo.png" type="image/x-icon">
    <title>À propos - Time Us</title>
    <style>
        .about-section {
            padding: 4rem 0;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        .about-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            align-items: center;
        }

        .about-image {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .about-image img {
            width: 100%;
            height: auto;
            transition: transform 0.3s ease;
        }

        .about-image img:hover {
            transform: scale(1.05);
        }

        .about-text {
            padding: 2rem;
        }

        .about-text h2 {
            color: #2c3e50;
            margin-bottom: 1.5rem;
            font-size: 2.5rem;
        }

        .about-text p {
            color: #666;
            line-height: 1.8;
            margin-bottom: 1rem;
        }

        .values-section {
            padding: 4rem 0;
            background: #fff;
        }

        .values-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .value {
            text-align: center;
            padding: 2rem;
            border-radius: 10px;
            background: #fff;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }

        .value:hover {
            transform: translateY(-10px);
        }

        .value i {
            font-size: 3rem;
            color: #3498db;
            margin-bottom: 1rem;
        }

        .value h3 {
            color: #2c3e50;
            margin-bottom: 1rem;
        }

        .value p {
            color: #666;
        }

        .title {
            text-align: center;
            margin-bottom: 3rem;
        }

        .title h1,
        .title h2 {
            color: #2c3e50;
            font-size: 2.5rem;
            position: relative;
            display: inline-block;
        }

        .title h1::after,
        .title h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 3px;
            background: #3498db;
        }
    </style>
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
                <h4>Livraison offerte</h4>
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
                <span class="text">Satisfait ou rembourser</span>
            </div>

            <div class="service">
                <span class="icon">
                    <div><i class='bx bx-headphone'></i></div>
                </span>
                <h4>24/7 Support</h4>
                <span class="text">Support Client</span>
            </div>
        </div>
    </section>

    <main>
        <section class="about-section container">
            <div class="title">
                <h1>Notre Histoire</h1>
            </div>

            <div class="about-content">
                <div class="about-image">
                    <img src="img/products/image_1.png" alt="Montre Time Us">
                </div>
                <div class="about-text">
                    <h2>Bienvenue chez Time Us</h2>
                    <p>Fondée en 2025, <strong>Time Us</strong> est née d'une passion pour l'horlogerie et d'une vision
                        : rendre accessible l'excellence horlogère à tous les amateurs d'élégance et de précision.</p>

                    <p>Notre mission est de révolutionner l'expérience d'achat de montres en proposant une sélection
                        rigoureuse des plus belles pièces, alliant design contemporain, qualité exceptionnelle et
                        innovation technologique.</p>

                    <p>Chaque montre que nous proposons est minutieusement sélectionnée par nos experts horlogers,
                        garantissant ainsi à nos clients des produits d'exception qui traverseront le temps avec
                        élégance.</p>
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
                    <p>Nous ne proposons que des produits de la plus haute qualité, sélectionnés avec le plus grand soin
                    </p>
                </div>

                <div class="value">
                    <i class='bx bx-check-shield'></i>
                    <h3>Authenticité</h3>
                    <p>Chaque montre est garantie authentique et accompagnée de sa certification officielle</p>
                </div>

                <div class="value">
                    <i class='bx bx-heart'></i>
                    <h3>Service Premium</h3>
                    <p>Une équipe dédiée à votre service pour une expérience d'achat exceptionnelle</p>
                </div>

                <div class="value">
                    <i class='bx bx-bulb'></i>
                    <h3>Innovation</h3>
                    <p>Nous sommes constamment à la pointe des dernières innovations horlogères</p>
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
                    <li><a href="apropos.php">À propos</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Nous Contacter</h3>
                <ul class="footer-links">
                    <li><i class="fas fa-envelope"></i> contact@timeus.com</li>
                    <li><i class="fas fa-phone"></i> +33 1 99 11 22 33</li>
                    <li><i class="fas fa-map-marker-alt"></i> 25 Rue Dauphine, Paris</li>
                </ul>
            </div>
        </div>
        <div class="copyright">
            &copy; <?php echo date('Y'); ?> TIME us. Tous droits réservés. | Réalisé par CHERIEF Yacine-Samy
        </div>
    </footer>
</body>

</html>