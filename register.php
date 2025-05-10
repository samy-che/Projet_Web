<?php
include 'connexion.php';
session_start();
$product_id = $_GET["id"];

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $cpassword = mysqli_real_escape_string($conn, $_POST['cpassword']);
    $numrue = mysqli_real_escape_string($conn, $_POST['numrue']);
    $nomrue = mysqli_real_escape_string($conn, $_POST['nomrue']);
    $ville = mysqli_real_escape_string($conn, $_POST['ville']);
    $codepostal = mysqli_real_escape_string($conn, $_POST['codepostal']);

    if ($password != $cpassword) {
        $message[] = "Le mot de passe de confirmation ne correspond pas !";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("SELECT * FROM `user_form` WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $select = $stmt->get_result();

        if (mysqli_num_rows($select) > 0) {
            $message[] = "L'utilisateur existe déjà !"; 
        } else {
            $stmt = $conn->prepare("INSERT INTO `user_form` (name, email, password, numrue, nomrue, ville, codepostal) VALUES(?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $name, $email, $hashed_password, $numrue, $nomrue, $ville, $codepostal);
            $stmt->execute();

            $message[] = "Inscription réussie !"; 
            header('location:login.php?success=1');
        }
    }
}

?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Time us - Inscription</title>
    <link rel="stylesheet" type="text/css" href="styles/register.css">
    <link rel="icon" href="img/logo.png" type="image/x-icon">
</head>

<body>

    <?php

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
            <h3>Inscription</h3>
            <input class="box" type="text" name="name" placeholder="Entrez votre nom" required>
            <input class="box" type="email" name="email" placeholder="Entrez votre e-mail" required>
            <input class="box" type="password" name="password" placeholder="Entrez votre mot de passe" required
                minlength="5">
            <input class="box" type="password" name="cpassword" placeholder="Confirmez votre mot de passe" required>
            <input class="box" type="number" name="numrue" placeholder="Numéro de rue" required min=1>
            <input class="box" type="text" name="nomrue" placeholder="Nom de rue" required>
            <input class="box" type="text" name="ville" placeholder="Ville" required>
            <input class="box" type="text" name="codepostal" placeholder="Code Postal" pattern="[0-9]{5}"
                title="Le code postal doit contenir 5 chiffres" required>
            <input class="btn" type="submit" name="submit" value="S'inscrire">
            <p>Vous avez déjà un compte ? <a href="login.php">Connectez vous</a>
            <p>
        </form>
    </div>

</body>

</html>     

<?php
mysqli_close($conn);
?>