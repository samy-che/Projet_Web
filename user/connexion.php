<?php
// Paramètres de connexion à la base de données
$host = 'localhost'; // Hôte de la base de données
$username = 'root'; // Nom d'utilisateur de la base de données
$password = ''; // Mot de passe de la base de données
$database = 'db_shop'; // Nom de la base de données

// Création de la connexion
$conn = mysqli_connect($host, $username, $password, $database);

// Vérification de la connexion
if (!$conn) {
    die("Échec de la connexion à la base de données: " . mysqli_connect_error());
}

// Configuration du jeu de caractères
mysqli_set_charset($conn, "utf8");
?>