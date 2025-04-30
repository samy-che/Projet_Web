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

// Traitement des actions
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'update':
            if (isset($_POST['client_id'], $_POST['nom'], $_POST['email'])) {
                $client_id = mysqli_real_escape_string($conn, $_POST['client_id']);
                $nom = mysqli_real_escape_string($conn, $_POST['nom']);
                $email = mysqli_real_escape_string($conn, $_POST['email']);

                $update_query = "UPDATE `user_form` SET 
                    name = '$nom',
                    email = '$email'
                    WHERE id = '$client_id'";

                if (mysqli_query($conn, $update_query)) {
                    $message[] = "Information client mise à jour avec succès !";
                } else {
                    $message[] = "Erreur lors de la mise à jour : " . mysqli_error($conn);
                }
            }
            break;

        case 'delete':
            if (isset($_POST['client_id'])) {
                $client_id = mysqli_real_escape_string($conn, $_POST['client_id']);

                // Supprimer d'abord les commandes associées
                mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$client_id'");

                // Puis supprimer le client
                if (mysqli_query($conn, "DELETE FROM `user_form` WHERE id = '$client_id'")) {
                    $message[] = "Client supprimé avec succès !";
                } else {
                    $message[] = "Erreur lors de la suppression : " . mysqli_error($conn);
                }
            }
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Clients - Time Us</title>
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
                        <span>Gestion des Clients</span>
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

        <section class="clients-section">
            <div class="container">
                <div class="table-container">
                    <h2><i class='bx bx-group'></i> Liste des Clients</h2>
                    <table class="product-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $select_clients = mysqli_query($conn, "SELECT * FROM `user_form` ORDER BY id DESC");
                            if (mysqli_num_rows($select_clients) > 0) {
                                while ($client = mysqli_fetch_assoc($select_clients)) {
                                    ?>
                                    <tr id="client-<?php echo $client['id']; ?>">
                                        <td><?php echo $client['id']; ?></td>
                                        <td>
                                            <span class="view-mode"><?php echo htmlspecialchars($client['name']); ?></span>
                                            <input type="text" class="edit-mode box2"
                                                value="<?php echo htmlspecialchars($client['name']); ?>" style="display: none;">
                                        </td>
                                        <td>
                                            <span class="view-mode"><?php echo htmlspecialchars($client['email']); ?></span>
                                            <input type="email" class="edit-mode box2"
                                                value="<?php echo htmlspecialchars($client['email']); ?>"
                                                style="display: none;">
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-edit" onclick="toggleEdit(<?php echo $client['id']; ?>)">
                                                    <i class='bx bx-edit-alt'></i>
                                                </button>
                                                <button class="btn btn-save" onclick="saveChanges(<?php echo $client['id']; ?>)"
                                                    style="display: none;">
                                                    <i class='bx bx-check'></i>
                                                </button>
                                                <button class="btn btn-delete"
                                                    onclick="deleteClient(<?php echo $client['id']; ?>)">
                                                    <i class='bx bx-trash'></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo '<tr><td colspan="4" class="empty">Aucun client trouvé</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>

    <script>
        function toggleEdit(clientId) {
            const row = document.getElementById(`client-${clientId}`);
            const viewModes = row.querySelectorAll('.view-mode');
            const editModes = row.querySelectorAll('.edit-mode');
            const editBtn = row.querySelector('.btn-edit');
            const saveBtn = row.querySelector('.btn-save');

            viewModes.forEach(el => el.style.display = 'none');
            editModes.forEach(el => el.style.display = 'block');
            editBtn.style.display = 'none';
            saveBtn.style.display = 'inline-block';
        }

        function saveChanges(clientId) {
            const row = document.getElementById(`client-${clientId}`);
            const inputs = row.querySelectorAll('.edit-mode');
            const formData = new FormData();

            formData.append('action', 'update');
            formData.append('client_id', clientId);
            formData.append('nom', inputs[0].value);
            formData.append('email', inputs[1].value);

            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
                .then(response => response.text())
                .then(() => window.location.reload())
                .catch(error => console.error('Erreur:', error));
        }

        function deleteClient(clientId) {
            if (confirm('Êtes-vous sûr de vouloir supprimer ce client ?')) {
                const formData = new FormData();
                formData.append('action', 'delete');
                formData.append('client_id', clientId);

                fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.text())
                    .then(() => window.location.reload())
                    .catch(error => console.error('Erreur:', error));
            }
        }

        // Animation des messages
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