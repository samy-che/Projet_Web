<?php
session_start();
if (!isset($_SESSION['admin_email']) || empty($_SESSION['admin_email'])) {
    header("Location:connexion.php");
    exit;
}

include 'connexion.php';
$admin_id = $_SESSION['admin_email'];
$select_admin = mysqli_query($conn, "SELECT * FROM `admin` WHERE email = '$admin_id'") or die("Erreur de requête");
if (mysqli_num_rows($select_admin) > 0) {
    $fetch_admin = mysqli_fetch_assoc($select_admin);
}

if (isset($_POST["Ajouter"])) {
    if (isset($_POST["nom"]) && isset($_POST["prix"]) && isset($_POST["description"]) && isset($_POST["image"]) && isset($_POST["quantity"])) {
        if (!empty($_POST["nom"]) && !empty($_POST["prix"]) && !empty($_POST["description"]) && !empty($_POST["image"]) && !empty($_POST["quantity"])) {
            $name = mysqli_real_escape_string($conn, $_POST["nom"]);
            $price = mysqli_real_escape_string($conn, $_POST["prix"]);
            $description = mysqli_real_escape_string($conn, $_POST["description"]);
            $image = mysqli_real_escape_string($conn, $_POST["image"]);
            $quantity = mysqli_real_escape_string($conn, $_POST["quantity"]);

            try {
                $insert_query = "INSERT INTO products (name, price, description, image, quantity) VALUES (?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($conn, $insert_query);
                mysqli_stmt_bind_param($stmt, "sdssi", $name, $price, $description, $image, $quantity);

                if (mysqli_stmt_execute($stmt)) {
                    $message[] = "Produit ajouté avec succès !";
                } else {
                    throw new Exception(mysqli_error($conn));
                }
            } catch (Exception $e) {
                $message[] = "Erreur lors de l'ajout du produit : " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Produit - Time us</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="styles/admin.css">
    <link rel="icon" href="../img/logo.png" type="image/x-icon">
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
                        <span>Ajouter un Produit</span>
                    </div>
                    <div class="icons">
                        <div class="username">
                            <i class='bx bx-user'></i>
                            <?php echo htmlspecialchars($fetch_admin['email']); ?>
                        </div>
                        <a class="delete-btn" href="../index.php?logout=<?php echo $fetch_admin['id']; ?>"
                            onclick="return confirm('Êtes-vous sûr de vouloir vous déconnecter ?');">
                            <i class='bx bx-log-out'></i> Déconnexion
                        </a>
                    </div>
                </div>
            </nav>
        </header>

        <section class="add-product">
            <div class="form-container">
                <h2>Ajouter un Nouveau Produit</h2>
                <form method="POST">
                    <div class="form-group">
                        <label for="nom">Nom du produit</label>
                        <input class="box2" type="text" id="nom" name="nom" required
                            placeholder="Entrez le nom du produit">
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="box3" id="description" name="description" required
                            placeholder="Entrez la description du produit"></textarea>
                        <small>Séparez les caractéristiques par des points-virgules (;)</small>
                    </div>

                    <div class="form-group">
                        <label for="prix">Prix</label>
                        <input class="box2" type="number" id="prix" name="prix" step="0.01" required
                            placeholder="Entrez le prix">
                    </div>

                    <div class="form-group">
                        <label for="quantity">Quantité en stock</label>
                        <input class="box2" type="number" id="quantity" name="quantity" required
                            placeholder="Entrez la quantité">
                    </div>

                    <div class="form-group">
                        <label for="image">Image du produit</label>
                        <input class="box2" type="text" id="image" name="image" required
                            placeholder="Entrez le nom du fichier image">
                        <small>L'image doit être placée dans le dossier img/products/</small>
                    </div>

                    <button type="submit" name="Ajouter" class="btn-primary">
                        <i class='bx bx-plus'></i> Ajouter le Produit
                    </button>
                </form>
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