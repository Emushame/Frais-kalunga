<?php
// --- 1. SÉCURITÉ DE TEMPS ---
set_time_limit(60); 
session_start();

require 'phpmailer/Exception.php';
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$host = 'localhost';
$dbname = 'kalunga_bd';
$user_db = 'root';
$pass_db = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user_db, $pass_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['status']) && $_GET['status'] == 'approved' && isset($_SESSION['payment_data'])) {
        
        $data = $_SESSION['payment_data'];
        $eleve_id = $data['eleve_id'];
        $montant = $data['montant'];

        // --- 2. MISE À JOUR BDD (Priorité n°1) ---
        $sql = "UPDATE eleves SET montant_paye = montant_paye + :montant WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['montant' => $montant, 'id' => $eleve_id]);

        // --- 2.5 RÉCUPÉRATION DU NOM ET DE LA CLASSE (Ce qui manquait) ---
        $query = $pdo->prepare("SELECT nom, prenom, classe FROM eleves WHERE id = :id");
        $query->execute(['id' => $eleve_id]);
        $eleve = $query->fetch(PDO::FETCH_ASSOC);
        
        // On prépare une variable pour le nom complet
        $nom_complet = strtoupper($eleve['nom']) . " " . $eleve['prenom'];
        $classe_eleve = $eleve['classe'];

        // --- 3. TENTATIVE D'ENVOI D'EMAIL ---
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; 
            $mail->SMTPAuth   = true;
            $mail->Username   = 'yannickmarionga75@gmail.com'; 
            $mail->Password   = 'zcfwbyupvtvcudtx'; 
            $mail->SMTPSecure = 'tls'; 
            $mail->Port       = 587;
            $mail->Timeout    = 20; 
            $mail->CharSet    = 'UTF-8';

            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            $mail->setFrom('yannickmarionga75@gmail.com', 'ITI KALUNGA - Finance');
            $mail->addAddress('yannickmarionga75@gmail.com'); 

            $mail->isHTML(true);
            $mail->Subject = "Confirmation de paiement - ITI KALUNGA";
            
            // --- MODIFICATION DE LA NOTIFICATION (Ajout du Nom et de la Classe) ---
            $mail->Body = "
                <h2>Paiement Reçu !</h2>
                <p><strong>Élève :</strong> {$nom_complet}</p>
                <p><strong>Classe :</strong> {$classe_eleve}</p>
                <p><strong>ID Système :</strong> {$eleve_id}</p>
                <p><strong>Montant :</strong> " . number_format($montant, 0, ',', ' ') . " FC</p>
            ";

            $mail->send();

        } catch (Exception $e) {
            error_log("Email non envoyé : " . $mail->ErrorInfo);
        }

        // --- 4. REDIRECTION (Toujours effectuée) ---
        unset($_SESSION['payment_data']);
        header("Location: Dashboard.php?payment=success");
        exit();

    } else {
        header("Location: Dashboard.php");
        exit();
    }

} catch (PDOException $e) {
    die("Erreur critique (BDD) : " . $e->getMessage());
}