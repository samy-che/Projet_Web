<?php
/**
 * Sidebar de l'administration
 * Ce fichier définit la barre latérale de navigation pour l'interface d'administration.
 * Il permet de naviguer entre les différentes fonctionnalités de gestion du site.
 */
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="sidebar">
    <div class="logo">
        <a href="admin.php"><span>Time</span> Us</a>
    </div>
    <!-- Liste des liens de navigation -->
    <ul class="nav-links">
        <!-- Gestion des produits -->
        <li>
            <a href="afficher.php" class="<?php echo $current_page === 'afficher.php' ? 'active' : ''; ?>">
                <i class='bx bx-package'></i>
                <span>Gérer les Produits</span>
            </a>
        </li>
        <!-- Suppression de produits -->
        <li>
            <a href="supp.php" class="<?php echo $current_page === 'supp.php' ? 'active' : ''; ?>">
                <i class='bx bx-trash'></i>
                <span>Supprimer un Produit</span>
            </a>
        </li>
        <!-- Ajout de nouveaux produits -->
        <li>
            <a href="ajouter.php" class="<?php echo $current_page === 'ajouter.php' ? 'active' : ''; ?>">
                <i class='bx bx-plus-circle'></i>
                <span>Ajouter un Produit</span>
            </a>
        </li>
        <!-- Gestion des clients -->
        <li>
            <a href="client.php" class="<?php echo $current_page === 'client.php' ? 'active' : ''; ?>">
                <i class='bx bx-group'></i>
                <span>Gestion des Clients</span>
            </a>
        </li>
    </ul>
</div>