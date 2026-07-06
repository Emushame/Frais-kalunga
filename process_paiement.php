<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: paiement.php');
    exit();
}

$montant = isset($_POST['montant']) ? max(0, intval($_POST['montant'])) : 0;
$motif = isset($_POST['type_frais']) ? trim($_POST['type_frais']) : 'Frais';
$eleve_id = isset($_POST['eleve_id']) ? intval($_POST['eleve_id']) : 0;
$mois = isset($_POST['mois_concerne']) ? trim($_POST['mois_concerne']) : 'N/A';
$methode = isset($_POST['method']) ? strtolower(trim($_POST['method'])) : 'mobile_money';
$telephone = isset($_POST['phone']) ? preg_replace('/[^0-9+]/', '', trim($_POST['phone'])) : '';

if ($eleve_id <= 0 || $montant <= 0 || $telephone === '') {
    $_SESSION['payment_error'] = 'Veuillez compléter correctement le formulaire de paiement.';
    header('Location: paiement.php');
    exit();
}

$operateur_label = ($methode === 'airtel') ? 'Airtel Money' : 'M-Pesa RDC';
$reference = 'KAL-' . date('YmdHis') . '-' . strtoupper(substr(md5($eleve_id . $montant . $telephone . time()), 0, 6));

try {
    $pdo->exec("ALTER TABLE paiements ADD COLUMN IF NOT EXISTS statut VARCHAR(20) NOT NULL DEFAULT 'en_attente'");
    $pdo->exec("ALTER TABLE paiements ADD COLUMN IF NOT EXISTS telephone VARCHAR(20) NULL");
    $pdo->exec("ALTER TABLE paiements ADD COLUMN IF NOT EXISTS operateur VARCHAR(30) NULL");
    $pdo->exec("ALTER TABLE paiements ADD COLUMN IF NOT EXISTS date_validation DATETIME NULL");

    $stmt = $pdo->prepare(
        "INSERT INTO paiements (eleve_id, montant, motif, mois_concerne, reference, methode, statut, telephone, operateur) VALUES (?, ?, ?, ?, ?, ?, 'en_attente', ?, ?)"
    );
    $stmt->execute([$eleve_id, $montant, $motif, $mois, $reference, $operateur_label, $telephone, $methode]);

    $_SESSION['payment_data'] = [
        'eleve_id' => $eleve_id,
        'montant' => $montant,
        'motif' => $motif,
        'mois' => $mois,
        'methode' => $operateur_label,
        'telephone' => $telephone,
        'reference' => $reference,
        'email' => 'yannickmarionga75@gmail.com'
    ];

    header('Location: simulateur_fedapay.php?amount=' . $montant . '&motif=' . urlencode($motif) . '&reference=' . urlencode($reference) . '&operator=' . urlencode($methode) . '&phone=' . urlencode($telephone));
    exit();
} catch (PDOException $e) {
    $_SESSION['payment_error'] = 'Impossible d’enregistrer la transaction pour le moment.';
    error_log($e->getMessage());
    header('Location: paiement.php');
    exit();
}
