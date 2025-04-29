<?php
session_start();
if (!isset($_SESSION['admin_email']) || empty($_SESSION['admin_email'])) {
    header("Location:connexion.php");
    exit;
}

include 'connexion.php';
$admin_id = $_SESSION['admin_email'];
// Utilisation d'une requête préparée pour éviter les injections SQL
$stmt = $conn->prepare("SELECT * FROM `admin` WHERE email = ?");
$stmt->bind_param("s", $admin_id);
$stmt->execute();
$select_admin = $stmt->get_result();
if (mysqli_num_rows($select_admin) > 0) {
    $fetch_admin = mysqli_fetch_assoc($select_admin);
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location:../connexion.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Time Us - Administrateur</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="styles/admin.css">
    <link rel="icon" href="../img/logo.png" type="image/x-icon">
</head>

<body>
    <header class="header">
        <nav class="nav container">
            <div class="navigation">
                <div class="logo">
                    <a href="#"><span>Time</span> Us - Administrateur</a>
                </div>
                <div class="icons">
                    <div class="username">
                        <i class='bx bx-user'></i>
                        <?php echo htmlspecialchars($fetch_admin['email']); ?>
                    </div>
                    <a class="delete-btn" href="../acceuil.php?logout=<?php echo $fetch_admin['id']; ?>"
                        onclick="return confirm('Êtes-vous sûr de vouloir vous déconnecter ?');">
                        <i class='bx bx-log-out'></i> Déconnexion
                    </a>
                </div>
            </div>
        </nav>
    </header>

    <section class="dashboard">
        <div class="service-center">
            <a href="afficher.php" class="service">
                <span class="icon">
                    <i class='bx bx-package'></i>
                </span>
                <div class="service-content">
                    <h4>Gérer les Produits</h4>
                    <p>Modifier ou supprimer les produits existants</p>
                </div>
            </a>

            <a href="supp.php" class="service">
                <span class="icon">
                    <i class='bx bx-trash'></i>
                </span>
                <div class="service-content">
                    <h4>Supprimer un Produit</h4>
                    <p>Supprimer des produits du catalogue</p>
                </div>
            </a>

            <a href="client.php" class="service">
                <span class="icon">
                    <i class='bx bx-group'></i>
                </span>
                <div class="service-content">
                    <h4>Gestion des Clients</h4>
                    <p>Gérer les informations des clients</p>
                </div>
            </a>

            <a href="ajouter.php" class="service">
                <span class="icon">
                    <i class='bx bx-plus-circle'></i>
                </span>
                <div class="service-content">
                    <h4>Ajouter un Produit</h4>
                    <p>Ajouter un nouveau produit au catalogue</p>
                </div>
            </a>
        </div>
    </section>
</body>

</html>