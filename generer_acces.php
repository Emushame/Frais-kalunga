<?php
session_start();
// Importation de la connexion à la base de données
require_once 'config.php'; 

if (isset($_POST['register_student'])) {
    try {
        // 1. Nettoyage des données reçues du formulaire
        $nom = strtoupper(trim($_POST['nom']));
        $prenom = ucfirst(trim($_POST['prenom']));
        $email = trim($_POST['email_parent']);
        $tel = trim($_POST['tel_responsable']);
        $classe = $_POST['classe'];

        // 2. Génération du MATRICULE PERMANENT (Ex: 2026-ITIK-001)
        $annee = date('Y');
        // On compte les élèves pour générer le numéro suivant
        $checkCount = $pdo->query("SELECT COUNT(*) FROM eleves")->fetchColumn();
        $prochainNumero = $checkCount + 1;
        
        // Formatage du matricule : KAL-2026-001
        $matricule = "KAL-" . $annee . "-" . str_pad($prochainNumero, 3, '0', STR_PAD_LEFT);

        // 3. Génération du CODE D'ACCÈS PERMANENT (4 chiffres)
        $code_clair = rand(1000, 9999);
        
        // Hachage compatible EasyPHP
        $password_crypte = sha1($code_clair); 

        // 4. Insertion dans la base de données (Colonnes mises à jour selon ton SQL)
        $sql = "INSERT INTO eleves (
                    nom, 
                    prenom, 
                    tel_responsable,
                    email_parent, 
                    classe, 
                    username, 
                    password_hash, 
                    solde_total, 
                    montant_paye
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        
        // Valeurs par défaut selon ton SQL (450 000 FC)
        $solde_fixe = 450000;
        $paye_initial = 0;

        $stmt->execute([
            $nom, 
            $prenom, 
            $tel,
            $email, 
            $classe, 
            $matricule, 
            $password_crypte, 
            $solde_fixe, 
            $paye_initial
        ]);

        // 5. Stockage en session pour l'affichage sur la page de confirmation
        $_SESSION['succes_inscription'] = [
            'nom_complet' => $prenom . " " . $nom,
            'matricule'   => $matricule,
            'code'        => $code_clair
        ];

        // Redirection
        header("Location: confirmation.php");
        exit();

    } catch (PDOException $e) {
        // En cas d'erreur, on affiche un message clair
        die("Erreur lors de l'enregistrement : " . $e->getMessage());
    }
} else {
    header("Location: inscription.php");
    exit();
}
?>