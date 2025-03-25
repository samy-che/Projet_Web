<?php
// Inclusion du fichier de connexion à la base de données et démarrage de la session PHP
include 'connexion.php';
session_start();

// Vérification si le formulaire de connexion a été soumis
if (isset($_POST['submit'])) {
    // Récupération et échappement des valeurs saisies dans le formulaire
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Vérification dans la table admin
    $stmt_admin = $conn->prepare("SELECT * FROM admin WHERE email = ?");
    $stmt_admin->bind_param('s', $email);
    $stmt_admin->execute();
    $result_admin = $stmt_admin->get_result();

    if ($result_admin->num_rows > 0) {
        // L'utilisateur est un administrateur
        $row = $result_admin->fetch_assoc();
        if (password_verify($password, $row['mdp'])) {
            $_SESSION['admin_email'] = $email;
            // Redirection vers la page d'administration
            header('location: admin/admin.php');
            exit;
        } else {
            $message[] = "Mot de passe ou email incorrect !";
        }
    } else {
        // Vérification dans la table user_form
        $stmt_user = $conn->prepare("SELECT * FROM user_form WHERE email = ?");
        $stmt_user->bind_param('s', $email);
        $stmt_user->execute();
        $result_user = $stmt_user->get_result();

        if ($result_user->num_rows > 0) {
            // L'utilisateur est un utilisateur normal
            $row = $result_user->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['id'];
                // Redirection vers la page utilisateur
                header('location: user/acceuil2.php');
                exit;
            } else {
                $message[] = "Mot de passe ou email incorrect !";
            }
        } else {
            $message[] = "Mot de passe ou email incorrect !";
        }
    }
}

// Fermeture des statements
if (isset($stmt_admin))
    $stmt_admin->close();
if (isset($stmt_user))
    $stmt_user->close();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Connexion - Time Us</title>
    <link rel="stylesheet" type="text/css" href="styles/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" href="img/logo.png" type="image/x-icon">
</head>

<body>
    <?php
    // Affichage des messages d'erreur
    if (isset($message)) {
        foreach ($message as $message) {
            echo '<div class="message" onclick="this.remove();">' . $message . '</div>';
        }
    }
    ?>

    <div class="logo">
        <a href="acceuil.php"><span>Time</span> us</a>
    </div>

    <div class="form-container">
        <form action="" method="POST">
            <h3>Connexion</h3>
            <input class="box" type="email" name="email" placeholder="Entrez votre e-mail" required>
            <input class="box" type="password" name="password" placeholder="Entrez votre mot de passe" required>
            <input class="btn" type="submit" name="submit" value="Se connecter">
            <p>Vous n'avez pas de compte ? <a href="register.php">Inscrivez-vous</a></p>
        </form>
    </div>

</body>

</html>
<?php
// Fermeture de la connexion à la base de données
if (isset($conn))
    mysqli_close($conn);
?>