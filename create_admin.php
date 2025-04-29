<?php
// Script pour créer un administrateur avec un mot de passe haché correctement
include 'connexion.php';

// Paramètres de l'administrateur à créer
$admin_email = 'admin@gmail.com'; // Modifiez selon vos besoins
$admin_password = 'admin123'; // Modifiez selon vos besoins

// Hachage du mot de passe avec password_hash
$hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);

// Vérification si l'administrateur existe déjà
$check_query = mysqli_query($conn, "SELECT * FROM `admin` WHERE email = '$admin_email'");

if (mysqli_num_rows($check_query) > 0) {
    // Mise à jour du mot de passe si l'administrateur existe déjà
    mysqli_query($conn, "UPDATE `admin` SET mdp = '$hashed_password' WHERE email = '$admin_email'");
    echo "Administrateur existant mis à jour avec succès.<br>";
    echo "Email: $admin_email<br>";
    echo "Mot de passe haché: [PROTÉGÉ]";
} else {
    // Création d'un nouvel administrateur
    mysqli_query($conn, "INSERT INTO `admin` (email, mdp) VALUES ('$admin_email', '$hashed_password')");
    echo "Nouvel administrateur créé avec succès.<br>";
    echo "Email: $admin_email<br>";
    echo "Mot de passe haché: [PROTÉGÉ]";
}

// Fermeture de la connexion
mysqli_close($conn);
?>