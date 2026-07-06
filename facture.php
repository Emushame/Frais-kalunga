<?php
session_start();

// 1. Vérification que l'élève est bien connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// 2. Inclusion de la connexion globale (InfinityFree)
include 'config.php';

// 3. Récupération des données de la session
$eleve_id = $_SESSION['user_id'];
$nom = isset($_SESSION['nom']) ? $_SESSION['nom'] : 'Élève';
$prenom = isset($_SESSION['prenom']) ? $_SESSION['prenom'] : '';
$classe = isset($_SESSION['classe']) ? $_SESSION['classe'] : 'Non définie';

// 4. Simulation ou récupération des infos du paiement (FedaPay / M-Pesa)
// Dans un cas réel, ces données proviennent de votre script de paiement (callback)
$montant_paye = 50; // Exemple : 50 USD ou FC
$devise = "USD";
$reference_transaction = "TXN-" . strtoupper(uniqid());
$date_paiement = date('d/m/Y H:i:s');

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture Numérique - Institut Kalunga</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .invoice-box {
            max-width: 600px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            background: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            border-radius: 8px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #0056b3;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #0056b3;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0 0 0;
            font-size: 14px;
            color: #666;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .details-table td {
            padding: 10px;
            border-bottom: 1px solid #f2f2f2;
        }
        .details-table td.label {
            font-weight: bold;
            color: #555;
            width: 40%;
        }
        .status-success {
            display: inline-block;
            background-color: #28a745;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 14px;
        }
        .footer-actions {
            margin-top: 30px;
            text-align: center;
        }
        .btn {
            text-decoration: none;
            background-color: #0056b3;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
            margin: 5px;
            display: inline-block;
        }
        .btn-secondary {
            background-color: #6c757d;
        }
        @media print {
            .footer-actions { display: none; }
            body { background: white; padding: 0; }
            .invoice-box { box-shadow: none; border: none; }
        }
    </style>
</head>
<body>

<div class="invoice-box">
    <div class="header">
        <h1>INSTITUT KALUNGA</h1>
        <p>Application de Paiement Numérique des Frais Scolaires</p>
        <p>Généré le : <?php echo $date_paiement; ?></p>
    </div>

    <h3>REÇU DE PAIEMENT OFFICIEL</h3>
    
    <table class="details-table">
        <tr>
            <td class="label">Référence :</td>
            <td style="font-family: monospace; font-weight: bold;"><?php echo $reference_transaction; ?></td>
        </tr>
        <tr>
            <td class="label">Statut du Paiement :</td>
            <td><span class="status-success">SUCCÈS / VALIDÉ</span></td>
        </tr>
        <tr>
            <td class="label">Nom de l'Élève :</td>
            <td><?php echo htmlspecialchars($nom . ' ' . $prenom); ?></td>
        </tr>
        <tr>
            <td class="label">Classe :</td>
            <td><?php echo htmlspecialchars($classe); ?></td>
        </tr>
        <tr>
            <td class="label">Montant Payé :</td>
            <td style="font-size: 18px; font-weight: bold; color: #28a745;"><?php echo $montant_paye . ' ' . $devise; ?></td>
        </tr>
    </table>

    <div class="footer-actions">
        <a href="#" onclick="window.print();" class="btn">Imprimer le reçu</a>
        <a href="Dashboard.php" class="btn btn-secondary">Retour au Tableau de bord</a>
    </div>
</div>

</body>
</html>