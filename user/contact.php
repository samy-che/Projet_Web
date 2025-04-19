<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="../styles/stylecontact.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="img/logo.png" type="image/x-icon">
    <title>Time Us - Contact</title>
</head>

<body>

    <header class="header">
        <nav class="nav container">

            <div class="navigation d-flex">
                <div class="icon1">
                    <i class='bx bx-menu'></i>
                </div>
                <div class="logo">
                    <a href="acceuil2.php"><span>Time</span> Us</a>
                </div>
                <div class="menu">
                    <div class="top">
                        <span class="fermer">Fermer <i class='bx bx-x'></i></span>
                    </div>
                    <ul class="nav-list d-flex">
                        <li class="nav-item">
                            <a href="acceuil.2php" class="nav-link">Accueil</a>
                        </li>
                        <li class="nav-item">
                            <a href="acceuil2.php#products" class="nav-link">Boutique</a>
                        </li>
                        <li class="nav-item">
                            <a href="apropos.php" class="nav-link" target='_BLANK'>A propos</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">Contact</a>
                        </li>
                    </ul>
                </div>
            </div>

        </nav>
    </header>

    <section id="Contact" class="section">
        <div class="container">
            <div class="contact-box">
                <div class="contact-left">
                    <h3>Besoin de plus d'informations ? Veuillez me contactez !</h3>

                    <form action="process_contact.php" method="POST">
                        <div class="input-row">
                            <div class="input_group">
                                <label>Nom*</label>
                                <input type="text" name="Nom" placeholder="Nom" required>
                            </div>
                            <div class="input_group">
                                <label>E-mail*</label>
                                <input type="email" name="E-mail" placeholder="nom@email.com" required>
                            </div>
                        </div>
                        <div class="input-row">
                            <div class="input_group">
                                <label>Objet*</label>
                                <input type="text" name="Objet" placeholder="Objet" required>
                            </div>
                        </div>

                        <label>Message*</label>
                        <textarea name="Message" rows="15" placeholder="Votre Message" required></textarea>

                        <button type="submit">ENVOYER</button>
                    </form>

                </div>


                <div class="contact-right">
                    <h3>Nos coordonnées</h3>
                    <table>
                        <tr>
                            <td>Email: </td>
                            <td>contact@timeus.com</td>
                        </tr>
                        <tr>
                            <td>Tel:</td>
                            <td>+33 1 23 45 67 89</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </section>

</body>
<script>
    // Script pour le menu mobile
    const menu = document.querySelector('.menu');
    const menuBtn = document.querySelector('.icon1');
    const closeBtn = document.querySelector('.fermer');

    menuBtn.addEventListener('click', () => {
        menu.classList.add('show');
    });

    closeBtn.addEventListener('click', () => {
        menu.classList.remove('show');
    });
</script>

</html>