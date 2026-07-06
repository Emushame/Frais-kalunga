<?php 
session_start(); 

// Sécurité : si on accède à cette page sans inscription récente, on redirige vers l'accueil
if (!isset($_SESSION['succes_inscription'])) {
    header("Location: inscription.php");
    exit();
}

$infos = $_SESSION['succes_inscription'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accès Confirmé - ITI KALUNGA</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            max-width: 450px;
            width: 90%;
        }
        .icon-success {
            font-size: 4rem;
            color: #22c55e;
            margin-bottom: 20px;
        }
        .info-box {
            background: rgba(0, 0, 0, 0.3);
            padding: 20px;
            border-radius: 15px;
            margin: 25px 0;
            border-left: 4px solid #60a5fa;
            text-align: left;
        }
        .label { color: #94a3b8; font-size: 0.9rem; }
        .value { color: #60a5fa; font-family: 'Courier New', Courier, monospace; font-size: 1.2rem; font-weight: bold; }
        
        .btn-print {
            background: #2563eb;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        .btn-print:hover { background: #1d4ed8; transform: translateY(-2px); }
        
        @media print {
            .btn-print, .btn-home { display: none; }
            body { background: white; color: black; }
            .glass-card { border: none; box-shadow: none; }
        }
    </style>
</head>
<body>

<div class="glass-card">
    <i class="fas fa-check-circle icon-success"></i>
    <h2>Inscription Réussie !</h2>
    <p>L'élève <strong><?php echo htmlspecialchars($infos['nom_complet']); ?></strong> a été ajouté avec succès.</p>

    <div class="info-box">
        <div style="margin-bottom: 15px;">
            <span class="label">MATRICULE (Identifiant)</span><br>
            <span class="value"><?php echo $infos['matricule']; ?></span>
        </div>
        <div>
            <span class="label">CODE D'ACCÈS PERMANENT</span><br>
            <span class="value"><?php echo $infos['code']; ?></span>
        </div>
    </div>

    <p style="font-size: 0.85rem; color: #94a3b8; margin-bottom: 20px;">
        <i class="fas fa-exclamation-triangle"></i> Notez bien ces informations, elles sont nécessaires pour la connexion et les paiements.
    </p>

    <button onclick="window.print()" class="btn-print">
        <i class="fas fa-print"></i> Imprimer le reçu
    </button>
    
    <a href="Dashboard.php" style="margin-top: 15px; display: block; color: #94a3b8; text-decoration: none; font-size: 0.9rem;">
        Retour au Dashboard <i class="fas fa-arrow-right"></i>
    </a>
</div>

</body>
</html>