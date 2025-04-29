<?php
session_start();
include 'connexion.php';

if (!isset($_SESSION['admin_email'])) {
    header("Location:connexion.php");
    exit;
}

$admin_id = $_SESSION['admin_email'];
$select_admin = mysqli_query($conn, "SELECT * FROM `admin` WHERE email = '$admin_id'") or die("Erreur de requête");
if (mysqli_num_rows($select_admin) > 0) {
    $fetch_admin = mysqli_fetch_assoc($select_admin);
}

$select_products = mysqli_query($conn, "SELECT * FROM `products`");
$produits = mysqli_fetch_all($select_products, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="styles/admin.css">
    <link rel="icon" href="../img/logo.png" type="image/x-icon">
    <title>Time us - Gérer les Produits</title>
</head>

<body>
    <?php
    if (isset($message)) {
        foreach ($message as $message) {
            echo '<div class="message">' . htmlspecialchars($message) . '</div>';
        }
    }
    ?>

    <?php include 'includes/sidebar.php'; ?>

    <div class="main-content">
        <header class="header">
            <nav class="nav container">
                <div class="navigation">
                    <div class="logo">
                        <span>Gérer les Produits</span>
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

        <section class="products">
            <div class="container">
                <div class="table-container">
                    <h2><i class='bx bx-package'></i> Liste des Produits</h2>
                    <table class="product-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Nom</th>
                                <th>Quantité</th>
                                <th>Prix</th>
                                <th>Description</th>
                                <th>Modifier</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($produits as $product): ?>
                                <tr>
                                    <td><?php echo $product['id']; ?></td>
                                    <td><img src="../img/products/<?= $product['image'] ?>" style="width:60px;"></td>
                                    <td><?php echo $product['name']; ?></td>
                                    <td><?php echo $product['quantity']; ?></td>
                                    <td><?php echo $product['price']; ?>€</td>
                                    <td><?php echo $product['description']; ?></td>
                                    <td><a class="option-btn" href="modifier.php?pdt=<?= $product['id'] ?>">Modifier</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const messages = document.querySelectorAll('.message');
            messages.forEach(message => {
                setTimeout(() => {
                    message.style.opacity = '0';
                    setTimeout(() => message.remove(), 300);
                }, 3000);
            });
        });
    </script>
</body>

</html>