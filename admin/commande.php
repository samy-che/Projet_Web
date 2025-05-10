<?php


function ajouter($nom, $image, $desc, $prix, $stock)
{
    include("connexion.php"); 
    if ($conn) { 
        $stmt = $conn->prepare("INSERT INTO products (name, price, description, image, quantity) VALUES (?, ?, ?, ?, ?)"); 
        $stmt->bind_param('sssss', $nom, $prix, $desc, $image, $stock); 
        $stmt->execute(); 
        $stmt->close(); 
        $conn->close(); 
    }
}

function afficher()
{
    include("connexion.php"); 
    $donnees = array(); 
    if ($conn) { 
        $result = $conn->query("SELECT * FROM products ORDER BY id DESC"); 
        if ($result->num_rows > 0) { 
            while ($row = $result->fetch_assoc()) { 
                $donnees[] = $row; 
            }
        }
        $conn->close(); 
    }
    return $donnees;
}

function supprimer($id)
{
    include("connexion.php"); 
    if ($conn) { 
        $stmt = $conn->prepare("DELETE FROM products WHERE id=?"); 
        $stmt->bind_param('i', $id); 
        $stmt->execute(); 
        $stmt->close(); 
        $conn->close(); 
    }
}

function Admin($email, $mdp)
{
    include("connexion.php"); 
    if ($conn) { 
        $stmt = $conn->prepare("SELECT * FROM admin WHERE email=?"); 
        $stmt->bind_param('s', $email); 
        $stmt->execute(); 
        $result = $stmt->get_result(); 
        if ($result->num_rows == 1) { 
            $donnee = $result->fetch_assoc(); 
            if (password_verify($mdp, $donnee['mdp'])) { 
                $stmt->close(); 
                $conn->close(); 
                return $donnee;
            } else {
                $stmt->close(); 
                $conn->close(); 
                return false; 
            }
        } else {
            $stmt->close(); 
            $conn->close(); 
            return false; 
        }
    }
}

function produit($id)
{
    include("connexion.php"); 
    if ($conn) { 
        $donnees = array(); 
        $stmt = $conn->prepare("SELECT * FROM `products` WHERE id=?"); 
        $stmt->bind_param('i', $id); 
        $stmt->execute(); 
        $result = $stmt->get_result(); 
        if ($result->num_rows == 1) { 
            while ($row = $result->fetch_assoc()) { 
                $donnees[] = $row; 
            }
        }
        $stmt->close(); 
        $conn->close(); 
        return $donnees; 
    }
}

function modifier($nom, $image, $desc, $prix, $stock, $id)
{
    include("connexion.php"); 
    if ($conn) { 
        $stmt = $conn->prepare("UPDATE products SET name=?, price=?, description=?, image=?, quantity=? WHERE id=?"); 
        $stmt->bind_param('sssssi', $nom, $prix, $desc, $image, $stock, $id); 
        $stmt->execute(); 
        $stmt->close(); 
        $conn->close(); 
    }
}

?>