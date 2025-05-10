<?php

session_start(); 
if (!isset($_SESSION['admin_email'])) { 
    header("Location:connexion.php"); 
    exit;
}
if (empty($_SESSION['admin_email'])) { 
    header("Location:connexion.php"); 
    exit;
}
if (!isset($_GET['pdt']) || empty($_GET['pdt']) || !is_numeric($_GET['pdt'])) { 
    header("Location:admin.php"); 
    exit;
}
$id = $_GET['pdt']; 

include 'connexion.php'; 
$admin_id = $_SESSION['admin_email'];
$select_admin = mysqli_query($conn, "SELECT * FROM `admin` WHERE email = '$admin_id'") or die("Erreur de requête"); 
if (mysqli_num_rows($select_admin) > 0) { 
    $fetch_admin = mysqli_fetch_assoc($select_admin); 
}
;

if (isset($_GET['logout'])) { 
    unset($fetch_admin['id']); 
    session_destroy(); 
    header('Location:acceuil.php'); 
}

require("commande.php"); 
$produits = produit($id); 

if ($produits) { 
    $idpdt = $produits[0]['id']; 
    $nom = $produits[0]['name']; 
    $prix = $produits[0]['price']; 
    $image = $produits[0]['image']; 
    $description = $produits[0]['description']; 
    $stock = $produits[0]['quantity']; 
}
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="../styles/style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="../img/logo.png" type="image/x-icon">
    <title>Time Us - Ajouter un produit</title>
</head>

<body>

    <?php

    if (isset($message)) {
        foreach ($message as $message) {
            echo '<div class="message" onclick="this.remove();">' . $message . '</div>';
        }
    }

    ?>


    <header class="header">
        <nav class="nav container">

            <div class="navigation d-flex">
                <div class="icon1">
                    <i class='bx bx-menu'></i>
                </div>
                <div class="logo">
                    <a href="#"><span>Time</span> Us</a>
                </div>
                <div class="menu">
                    <ul class="nav-list d-flex">
                        <li class="nav-item">

                        </li>
                        <li class="nav-item">
                            <a href="afficher.php" class="nav-link">Retour</a>
                            <!-- Lien pour revenir à la page d'affichage -->
                        </li>
                    </ul>
                </div>
                <div class="icons d-flex">
                    <div class="username"><?php echo $fetch_admin['email']; ?></a></div>
                    <div>
                        <a class="delete-btn" href="../acceuil.php?logout=<?php echo $fetch_admin['id']; ?>"
                            onclick="return confirm('Es-tu sûr de te déconnecter ?');">Déconnexion</a>
                    </div>
                </div>
            </div>

        </nav>
        <div class="form-container">
            <form method="POST">
                <input type="hidden" name="id" value="<?php echo $idpdt; ?>">
                <!-- Champ caché pour stocker l'ID du produit -->
                <label for="nom">Nom du produit :</label>
                <input class="box2" type="text" name="nom" value="<?php echo $nom; ?>" required>
                <!-- Champ pour modifier le nom du produit -->
                <label for="description">Description :</label>
                <textarea class="box3" type="text" name="description" required><?php echo $description; ?></textarea>
                <!-- Champ pour modifier la description du produit -->
                <label for="prix">Prix :</label>
                <input class="box2" type="text" name="prix" value="<?php echo $prix; ?>" required>
                <!-- Champ pour modifier le prix du produit -->
                <label for="image">Image :</label>
                <input class="box2" type="text" name="image" value="<?php echo $image; ?>" required>
                <!-- Champ pour modifier le nom de l'image du produit -->
                <img class="imgproduct" src="../img/products/<?php echo $image; ?>"> <!-- Affiche l'image du produit -->
                <br>
                <label for="stock">Stock :</label>
                <input class="box2" type="number" name="stock" value="<?php echo $stock; ?>" required>
                <!-- Champ pour modifier la quantité en stock du produit -->
                <input class="btn2" type="submit" name="Modifier" value="Enregistrer">
                <!-- Bouton pour enregistrer les modifications -->
            </form>
        </div>
</body>

</html>
<?php
if (isset($_POST["Modifier"])) { 

    if (isset($_POST["image"]) && isset($_POST["prix"]) && isset($_POST["description"]) && isset($_POST["nom"]) && isset($_POST["stock"])) { 

        if (!empty($_POST["image"]) && !empty($_POST["prix"]) && !empty($_POST["description"]) && !empty($_POST["nom"]) && !empty($_POST["stock"])) { 

            $id = htmlspecialchars(strip_tags($_POST["id"])); 
            $image = htmlspecialchars(strip_tags($_POST["image"])); 
            $prix = htmlspecialchars(strip_tags($_POST["prix"])); 
            $description = htmlspecialchars(strip_tags($_POST["description"])); 
            $nom = htmlspecialchars(strip_tags($_POST["nom"])); 
            $stock = htmlspecialchars(strip_tags($_POST["stock"])); 

            modifier($nom, $image, $description, $prix, $stock, $id); 
            header("Location: afficher.php"); 

            exit; 
        }
    }
}
?>

<?php
mysqli_close($conn);
?>