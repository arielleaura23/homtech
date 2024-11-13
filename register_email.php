<?php
session_start(); // Activer les sessions

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des informations du formulaire
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $school = htmlspecialchars($_POST['school']);
    $speciality = htmlspecialchars($_POST['speciality']);

    // Récupération des fichiers CV et lettre de motivation
    $cv = $_FILES['cv'];
    $coverLetter = $_FILES['cover_letter'];

    $mail = new PHPMailer(true);

    try {
        // Configuration de Mailpit en local
        $mail->isSMTP();
        $mail->Host = 'localhost';
        $mail->Port = 1025;
        $mail->SMTPAuth = false;

        // Informations de l'e-mail
        $mail->setFrom($email, $name);
        $mail->addAddress('homtech@gmail.com');

        // Sujet et contenu de l'e-mail
        $mail->Subject = "Nouvelle inscription";
        $mail->Body = "Nom : $name\nEmail : $email\nTéléphone : $phone\nÉcole : $school\nSpécialité : $speciality";

        // Ajout des fichiers en pièce jointe si présents
        if ($cv['error'] == 0) {
            $mail->addAttachment($cv['tmp_name'], $cv['name']);
        }
        if ($coverLetter['error'] == 0) {
            $mail->addAttachment($coverLetter['tmp_name'], $coverLetter['name']);
        }

        // Envoi de l'e-mail
        $mail->send();

        // Si l'e-mail est envoyé, définir une variable de session
        $_SESSION['email_sent'] = true;
        header('Location: index.php'); // Rediriger vers index.php
        exit;
    } catch (Exception $e) {
        echo "Erreur lors de l'envoi de l'e-mail : {$mail->ErrorInfo}";
    }
}
?>
