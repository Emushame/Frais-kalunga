<?php
session_start();

// Connexion à la base de données (Ajuste les paramètres si nécessaire)
$host = 'localhost';
$dbname = 'kalunga_bd';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 1. Récupération des données du formulaire
    $montant = isset($_POST['montant']) ? intval($_POST['montant']) : 0;
    $motif = isset($_POST['type_frais']) ? htmlspecialchars($_POST['type_frais']) : 'Frais';
    $eleve_id = isset($_POST['eleve_id']) ? intval($_POST['eleve_id']) : 0;
    $mois = isset($_POST['mois_concerne']) ? htmlspecialchars($_POST['mois_concerne']) : 'N/A';
    $methode = isset($_POST['method']) ? htmlspecialchars($_POST['method']) : 'Mobile Money';

    // 2. IMPORTANT : On définit l'email pour la notification SMTP
    // On utilise l'email que tu as fourni pour les tests
    $email_notification = "yannickmarionga75@gmail.com";

    // 3. Stockage en session pour que le simulateur et le callback y accèdent
    $_SESSION['payment_data'] = [
        'eleve_id' => $eleve_id,
        'montant'  => $montant,
        'motif'    => $motif,
        'mois'     => $mois,
        'methode'  => $methode,
        'email'    => $email_notification // L'email sera récupéré ici pour l'envoi
    ];

    // 4. Redirection vers ton simulateur local
    // On passe le montant et le nom de l'élève en paramètre pour l'affichage
    header("Location: simulateur_fedapay.php?amount=" . $montant . "&motif=" . urlencode($motif));
    exit();
}