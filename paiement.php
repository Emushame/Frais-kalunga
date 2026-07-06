<?php 
session_start();

// Protection de la page : On vérifie si l'élève est connecté
$id_eleve = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
$nom_eleve = isset($_SESSION['nom']) ? $_SESSION['nom'] : "Élève";
$prenom_eleve = isset($_SESSION['prenom']) ? $_SESSION['prenom'] : "";

if ($id_eleve == 0) {
    header("Location: login.php");
    exit();
}

// Tableau des mois pour le sélecteur
$mois = [
    "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", 
    "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"
];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ITI KALUNGA - Effectuer un Paiement</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* --- STYLE PERSONNALISÉ (SANS CHANGER LE DESIGN) --- */
        :root {
            --primary-blue: #2563eb;
            --accent-blue: #60a5fa;
            --glass: rgba(255, 255, 255, 0.05);
            --border: rgba(255, 255, 255, 0.1);
        }

        body {
            background-color: #0f172a;
            color: white;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
        }

        .payment-container { max-width: 900px; margin: 40px auto; padding: 0 20px; }

        .payment-card {
            background: var(--glass);
            backdrop-filter: blur(15px);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .student-info-header {
            display: flex; align-items: center; gap: 15px;
            background: rgba(37, 99, 235, 0.1);
            padding: 15px 25px; border-radius: 15px;
            margin-bottom: 30px; border-left: 5px solid var(--primary-blue);
        }

        .fee-selector {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px; margin-bottom: 25px;
        }
        .fee-selector input[type="radio"] { display: none; }
        .fee-item {
            background: rgba(255, 255, 255, 0.03); border: 1px solid var(--border);
            padding: 20px; border-radius: 15px; text-align: center; cursor: pointer; transition: 0.3s;
        }
        .fee-selector input[type="radio"]:checked + .fee-item {
            background: var(--primary-blue); border-color: var(--accent-blue);
            transform: translateY(-5px); box-shadow: 0 10px 20px rgba(37, 99, 235, 0.3);
        }

        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px; }
        .form-group label { display: block; margin-bottom: 10px; color: var(--accent-blue); font-size: 0.9rem; }
        
        .form-group input, .form-group select {
            width: 100%; padding: 12px 15px;
            background: rgba(0, 0, 0, 0.3); border: 1px solid var(--border);
            border-radius: 10px; color: white; outline: none; transition: 0.3s;
        }

        /* --- SÉLECTEUR OPÉRATEURS --- */
        .method-selector { display: flex; gap: 15px; margin-top: 10px; }
        .method-option { flex: 1; cursor: pointer; }
        .method-option input { display: none; }
        .op-box {
            padding: 12px; text-align: center; border-radius: 10px;
            font-weight: bold; border: 2px solid transparent; transition: 0.3s; filter: grayscale(0.7);
            font-size: 0.8rem;
        }
        .mpesa { background: #e61c27; } 
        .orange { background: #ff7900; }
        .airtel { background: #ff0000; } /* Rouge vif pour Airtel */

        .method-option input:checked + .op-box { 
            filter: grayscale(0); 
            border-color: white; 
            transform: scale(1.05); 
            box-shadow: 0 5px 15px rgba(255, 255, 255, 0.2);
        }

        .confirm-btn {
            width: 100%; padding: 18px; margin-top: 20px; border: none; border-radius: 12px;
            background: linear-gradient(135deg, var(--primary-blue), #7c3aed);
            color: white; font-weight: bold; font-size: 1.1rem; cursor: pointer; transition: 0.3s;
        }

        @media (max-width: 600px) { .form-row { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

<div class="app-wrapper">
    <aside class="sidebar">
        <div class="logo"><i class="fas fa-graduation-cap"></i> ScolarPay</div>
        <nav>
            <a href="Dashboard.php"><i class="fas fa-th-large"></i> Vue d'ensemble</a>
            <a href="paiement.php" class="active"><i class="fas fa-credit-card"></i> Effectuer un Paiement</a>
            <a href="logout.php"><i class="fas fa-power-off"></i> Déconnexion</a>
        </nav>
    </aside>

    <main class="content">
        <header class="top-bar">
            <h1>Règlement des frais</h1>
            <div class="user-profile">
                <?php echo strtoupper(substr($prenom_eleve, 0, 1) . substr($nom_eleve, 0, 1)); ?>
            </div>
        </header>

        <div class="payment-container">
            <div class="payment-card">
                
                <div class="student-info-header">
                    <i class="fas fa-user-circle" style="font-size: 1.5rem;"></i>
                    <span>Paiement pour : <strong><?php echo $prenom_eleve . " " . $nom_eleve; ?></strong></span>
                </div>

                <form action="process_paiement.php" method="POST">
                    <input type="hidden" name="eleve_id" value="<?php echo $id_eleve; ?>">

                    <div class="form-group">
                        <label><i class="fas fa-list"></i> Motif du paiement</label>
                        <div class="fee-selector">
                            <input type="radio" name="type_frais" value="Scolarité" id="frais_scol" checked>
                            <label for="frais_scol" class="fee-item">
                                <i class="fas fa-university"></i><br><span>Scolarité</span>
                            </label>

                            <input type="radio" name="type_frais" value="Pratique" id="frais_pratique">
                            <label for="frais_pratique" class="fee-item">
                                <i class="fas fa-tools"></i><br><span>Pratique</span>
                            </label>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="mois_concerne"><i class="fas fa-calendar-alt"></i> Mois concerné</label>
                            <select name="mois_concerne" id="mois_concerne" required>
                                <option value="" disabled selected>Choisir le mois...</option>
                                <?php foreach($mois as $m): ?>
                                    <option value="<?php echo $m; ?>"><?php echo $m; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="montant"><i class="fas fa-coins"></i> Montant (FC)</label>
                            <input type="number" name="montant" id="montant" placeholder="Ex: 50000" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone"><i class="fas fa-phone-alt"></i> N° Mobile Money</label>
                            <input type="tel" name="phone" id="phone" placeholder="08XXXXXXXX" required>
                        </div>
                        <div class="form-group">
                            <label>Opérateur</label>
                            <div class="method-selector">
                                <label class="method-option">
                                    <input type="radio" name="method" value="mpesa" required>
                                    <div class="op-box mpesa">M-PESA</div>
                                </label>
                                <label class="method-option">
                                    <input type="radio" name="method" value="orange">
                                    <div class="op-box orange">ORANGE</div>
                                </label>
                                <label class="method-option">
                                    <input type="radio" name="method" value="airtel">
                                    <div class="op-box airtel">AIRTEL</div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="confirm-btn">
                        <i class="fas fa-lock"></i> VALIDER LE PAIEMENT SÉCURISÉ
                    </button>
                </form>

            </div>
        </div>
    </main>
</div>

</body>
</html>