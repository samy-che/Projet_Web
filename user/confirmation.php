<?php


include 'connexion.php';

session_start();

$user_id = $_SESSION['user_id'];

if (isset($_GET['logout'])){
    unset($user_id);
    session_destroy();
    unset($user_id);
    session_destroy();
    header('location:acceuil.php');
};


if (isset($_POST['submit'])){
    

    $numCarte = mysqli_real_escape_string($conn, $_POST['numCarte']);
    $dateexpiration = mysqli_real_escape_string($conn, $_POST['dateexpiration']);
    $nomCarte = mysqli_real_escape_string($conn, $_POST['nomCarte']);
    $codeSecret_Hash = password_hash($_POST['codeSecret'], PASSWORD_DEFAULT);

    mysqli_query($conn, "INSERT INTO `credit_card` (user_id, numcarte, dateexpiration, nomCarte, codeSecret) VALUES('$user_id','$numCarte','$dateexpiration','$nomCarte','$codeSecret_Hash') ") or die('Erreur de requête');

    $numrue = mysqli_real_escape_string($conn, $_POST['numrue']);
    $nomrue = mysqli_real_escape_string($conn, $_POST['nomrue']);
    $ville = mysqli_real_escape_string($conn, $_POST['ville']);
    $codepostal = mysqli_real_escape_string($conn, $_POST['codepostal']);
    

    $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('Erreur de requête');
    
    while ($row = mysqli_fetch_assoc($cart_query)) {
        $product_id = $row['product_id'];
        $price = $row['price'];
        $image = $row['image'];
        $quantity = $row['quantity'];
        $codepromo = $row['codepromo'];


        mysqli_query($conn, "INSERT INTO `orders` (user_id, product_id, image, price, quantity, date, heure, codepromo, numrue, nomrue, ville, codepostal) VALUES('$user_id','$product_id', '$image', '$price', '$quantity', CURDATE(), CURTIME(), '$codepromo', '$numrue','$nomrue','$ville','$codepostal') ") or die('Erreur de requête');
    }
}


if (isset($_POST['submit'])) {

    mysqli_begin_transaction($conn);

    try {

        $cart_query = mysqli_query($conn, "SELECT product_id, quantity FROM `cart` WHERE user_id = '$user_id' FOR UPDATE") or die('Erreur de requête');

        while ($row = mysqli_fetch_assoc($cart_query)) {
            $product_id = $row['product_id'];
            $quantity = $row['quantity'];

            $check_stock_query = mysqli_query($conn, "SELECT quantity FROM `products` WHERE id = '$product_id' FOR UPDATE");
            $stock_row = mysqli_fetch_assoc($check_stock_query);
            $stock_quantity = $stock_row['quantity'];
            if ($stock_quantity < $quantity) {
                $message[] = 'La quantité commandée est supérieure à la quantité stockée';
                mysqli_rollback($conn);
                header('location:acceuil2.php');
                exit();
            }

            $update_stock_query = mysqli_query($conn, "UPDATE `products` SET quantity = quantity - $quantity  WHERE id = '$product_id'");

            if (!$update_stock_query) {
                throw new Exception('Erreur lors de la mise à jour du stock');
            }
        }

        $delete_cart_query = mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'");

        if (!$delete_cart_query) {
            throw new Exception('Erreur lors de la suppression des produits du panier');
        }

        mysqli_commit($conn);

    } catch (Exception $e) {
        mysqli_rollback($conn);
        $message[] = $e->getMessage();
    }
}

$select_user = mysqli_query($conn, "SELECT * FROM `user_form` WHERE ID = '$user_id'") or die("Erreur de requête");
if (mysqli_num_rows($select_user) > 0){
    $fetch_user = mysqli_fetch_assoc($select_user);
}
$message[]= "Merci d'avoir effectué une commande sur notre site !";

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href = "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" type = "text/css" href = "../styles/stylepaiement.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="../img/logo.png" type="image/x-icon">
    <title>Time Us - Paiement</title>
</head>
<body>

<?php

if(isset($message)){
   foreach($message as $message){
      echo '<div class="message" onclick="this.remove();">'.$message.'</div>';
   }
}

?>

    <header class="header">
        <nav class="nav container">

            <div class ="navigation d-flex">
                <div class="icon1">
                    <i class='bx bx-menu'></i>
                </div>
                <div class="logo">
                    <a href ="acceuil2.php"><span>Time</span> Us</a>
                </div>
                <div class="menu">
                    <div class="top">
                        <span class = "fermer">Fermer <i class='bx bx-x'></i></span>
                    </div>
                    <ul class ="nav-list d-flex">
                        <li class="nav-item">

                        </li>
                        <li class="nav-item">
                            <a href="acceuil2.php" class ="nav-link">Retour à l'accueil</a>
                        </li>
                    </ul>
                </div>
                <div class="icons d-flex">
                    <div class = "username"><a href="profil.php" target='_BLANK'><?php echo $fetch_user['name']; ?></a></div>
                    <div>
                            <a href="panier.php"><i class='bx bx-shopping-bag'></i></a>

                    </div>
                    <div>
                    <a class = "delete-btn" href ="../acceuil.php?logout=<?php echo $user_id; ?>" onclick="return confirm('Es-tu sûr de te déconnecter ?');">Déconnexion</a>
                    </div>
                </div>
            </div>
    </header>
    

    <div>
<?php 

    $select_credit_cart = mysqli_query($conn, "SELECT * FROM `credit_card` WHERE user_id = '$user_id'") or die("Erreur de requête");
    if (mysqli_num_rows($select_credit_cart) > 0){
        $fetch_credit_cart = mysqli_fetch_assoc($select_credit_cart);
    }

    $select_order = mysqli_query($conn, "SELECT * FROM `orders` WHERE user_id = '$user_id' ORDER BY date DESC, heure DESC LIMIT 1") or die("Erreur de requête");
    if (mysqli_num_rows($select_order) > 0){
        $fetch_order = mysqli_fetch_assoc($select_order);
    }
?>

<div class="container2">
    <div class="left-div">
       
        <div class = "infolivraison">
        <h3>Adresse de Livraison</h3>
        Adresse : <?php echo $fetch_order['numrue']. " " .$fetch_order['nomrue'].", ".$fetch_order['ville'].", ".$fetch_order['codepostal']; ?>
        </div>
        <br>
        <div class = "infocarte">
        <h3>Informations Bancaire</h3>
        <?php 
        if(isset($fetch_credit_cart) && !empty($fetch_credit_cart)) {
            $maskedCard = "XXXX XXXX XXXX " . substr($fetch_credit_cart['numcarte'], -4);
            echo "Numéro de carte : $maskedCard<br>";
            echo "Nom sur la carte : {$fetch_credit_cart['nomCarte']}";
        } else {
            echo "<em>Informations de carte non disponibles</em>";
        }
        ?>
        
        </div>
    </div>
    <div class="right-div">
        <h3>Information Commande</h3>   
        <?php

        $total  = 0;

            if(isset($fetch_order) && !empty($fetch_order)) {
                $last_date = $fetch_order['date'];
                $last_heure = $fetch_order['heure'];
                

                $select_order2 = mysqli_query($conn, "SELECT * FROM `orders` WHERE user_id = '$user_id' AND date = '$last_date' AND heure = '$last_heure'") or die("Erreur de requête");
            } else {
                echo "<em>Aucune commande trouvée</em>";
                $select_order2 = false;
            }
            while ($row2 = mysqli_fetch_assoc($select_order2)) {
                $product_id = $row2['product_id'];   
                $select_product = mysqli_query($conn, "SELECT * FROM `products` WHERE id = '$product_id'") or die('Erreur de requête');
                while ($row = mysqli_fetch_assoc($select_product)){
                    echo $row['name'];
                    echo "<br>";
                    echo "<img class = 'img' src='../img/products/".$row['image']."'>";

                    echo "<br>";
                    echo "Prix : " .$row['price'];
                    echo "<br>";
                    $total += $row['price'] * $row2['quantity'];
                }
                echo "Quantité : ".$row2['quantity']. "<br>";
                echo "<br><br>";
            }
            

            $select_promo = mysqli_query($conn, "SELECT codepromo FROM `orders` WHERE user_id = '$user_id' ORDER BY date DESC, heure DESC LIMIT 1");
            if (mysqli_num_rows($select_promo) > 0){
                $fetch_promo = mysqli_fetch_assoc($select_promo);
                if($fetch_promo['codepromo']){
                    echo "Code Promo appliqué ! <br>";
                    echo "Sous-total : ".$total."<br>";
                    echo "Total : ". $total*0.85;
                }
                else{
                    echo "Pas de Code Promo ! <br>";
                    echo "Total : ".$total;
                }
            }


        ?>
    </div>
</div>

<div class = "text">Les conditions et délais de livraison sont sur notre page <a href = "apropos.php" target = "_BLANK">A propos</a></div>

</body>
</html>

<?php
    mysqli_close($conn);
?>
