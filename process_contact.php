<?php
// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération et nettoyage des données du formulaire
    $nom = filter_input(INPUT_POST, 'Nom', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'E-mail', FILTER_SANITIZE_EMAIL);
    $objet = filter_input(INPUT_POST, 'Objet', FILTER_SANITIZE_STRING);
    $message = filter_input(INPUT_POST, 'Message', FILTER_SANITIZE_STRING);

    // Validation des données
    if (!$nom || !$email || !$objet || !$message) {
        echo "<script>alert('Veuillez remplir tous les champs du formulaire.'); window.location.href='contact.php';</script>";
        exit;
    }

    // Validation de l'email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Adresse email invalide.'); window.location.href='contact.php';</script>";
        exit;
    }

    // Préparation du message
    $to = "samycherief21@outlook.fr";
    $subject = "[Time Us] $objet";

    // Corps du message
    $email_message = "<html><body>";
    $email_message .= "<h2>Nouveau message de contact depuis le site Time Us</h2>";
    $email_message .= "<p><strong>Nom:</strong> $nom</p>";
    $email_message .= "<p><strong>Email:</strong> $email</p>";
    $email_message .= "<p><strong>Objet:</strong> $objet</p>";
    $email_message .= "<p><strong>Message:</strong><br>" . nl2br($message) . "</p>";
    $email_message .= "</body></html>";

    // En-tu00eates pour le format HTML
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n";
    $headers .= "From: $nom <$email>\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    // Tentative d'envoi avec la fonction mail() de PHP
    $mail_sent = mail($to, $subject, $email_message, $headers);

    // Enregistrement de la tentative dans un fichier de log
    $log_message = date('Y-m-d H:i:s') . " - Tentative d'envoi d'email - ";
    $log_message .= "De: $nom ($email) - Objet: $objet - ";
    $log_message .= "Résultat: " . ($mail_sent ? "Succès" : "Échec") . "\n";

    // Créer le dossier logs s'il n'existe pas
    if (!file_exists('logs')) {
        mkdir('logs', 0755, true);
    }

    file_put_contents('logs/email_log.txt', $log_message, FILE_APPEND);

    // Réponse à l'utilisateur
    if ($mail_sent) {
        // Afficher un message de succès et rediriger vers la page d'accueil
        echo "<script>alert('Message envoyé!'); window.location.href='acceuil.php';</script>";
    } else {
        // Enregistrer l'erreur dans un fichier de log
        error_log("Échec de l'envoi d'email à $to depuis $email", 0);

        // Afficher un message d'erreur et rediriger vers la page d'accueil
        echo "<script>alert('Votre message n\'a pas pu être envoyé. Nous avons enregistré votre demande et vous contacterons dès que possible.'); window.location.href='acceuil.php';</script>";
    }
} else {
    // Redirection si accès direct au fichier
    header("Location: contact.php");
    exit;
}
?>