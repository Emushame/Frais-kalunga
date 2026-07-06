<?php
set_time_limit(60);
session_start();
require_once 'config.php';

$useMail = file_exists(__DIR__ . '/phpmailer/PHPMailer.php') && file_exists(__DIR__ . '/phpmailer/Exception.php') && file_exists(__DIR__ . '/phpmailer/SMTP.php');

if ($useMail) {
    require_once __DIR__ . '/phpmailer/Exception.php';
    require_once __DIR__ . '/phpmailer/PHPMailer.php';
    require_once __DIR__ . '/phpmailer/SMTP.php';
}

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['status']) && isset($_SESSION['payment_data'])) {
        $data = $_SESSION['payment_data'];
        $eleve_id = $data['eleve_id'];
        $montant = $data['montant'];
        $reference = isset($data['reference']) ? $data['reference'] : null;
        $status = ($_GET['status'] === 'approved') ? 'paye' : 'annule';

        $stmt = $pdo->prepare("UPDATE eleves SET montant_paye = montant_paye + :montant WHERE id = :id");
        $stmt->execute(['montant' => $montant, 'id' => $eleve_id]);

        if ($reference) {
            $stmtPayment = $pdo->prepare("UPDATE paiements SET statut = ?, date_validation = NOW() WHERE reference = ?");
            $stmtPayment->execute([$status, $reference]);
        }

        $query = $pdo->prepare("SELECT nom, prenom, classe FROM eleves WHERE id = :id");
        $query->execute(['id' => $eleve_id]);
        $eleve = $query->fetch(PDO::FETCH_ASSOC);

        if ($useMail && $eleve) {
            $nom_complet = strtoupper($eleve['nom']) . ' ' . $eleve['prenom'];
            $classe_eleve = $eleve['classe'];
            $mail = new PHPMailer\PHPMailer\PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'yannickmarionga75@gmail.com';
                $mail->Password = 'zcfwbyupvtvcudtx';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;
                $mail->Timeout = 20;
                $mail->CharSet = 'UTF-8';
                $mail->SMTPOptions = ['ssl' => ['verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true]];
                $mail->setFrom('yannickmarionga75@gmail.com', 'ITI KALUNGA - Finance');
                $mail->addAddress('yannickmarionga75@gmail.com');
                $mail->isHTML(true);
                $mail->Subject = 'Confirmation de paiement - ITI KALUNGA';
                $mail->Body = "<h2>Paiement reçu !</h2><p><strong>Élève :</strong> {$nom_complet}</p><p><strong>Classe :</strong> {$classe_eleve}</p><p><strong>Montant :</strong> " . number_format($montant, 0, ',', ' ') . ' FC</p>';
                $mail->send();
            } catch (Exception $e) {
                error_log('Email non envoyé : ' . $mail->ErrorInfo);
            }
        }

        unset($_SESSION['payment_data']);
        header('Location: Dashboard.php?payment=success');
        exit();
    }

    header('Location: Dashboard.php');
    exit();
} catch (PDOException $e) {
    die('Erreur critique (BDD) : ' . $e->getMessage());
}