<?php
session_start();

// 1. Protection de la page et contrôle des accès
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Redirection si c'est un admin qui tente d'accéder au dashboard élève
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    header("Location: Dashboard_admin.php");
    exit();
}

// 2. CONFIGURATION DE LA BASE DE DONNÉES - SPÉCIFIQUE INFINITYFREE
// Remplacer les valeurs ci-dessous par vos identifiants fournis dans votre panel InfinityFree
$host = 'sql212.infinityfree.com';
$dbname = 'if0_41957335_kalunga_bd';
$user_db = 'if0_41957335';
$pass_db = 'Kalunga2026';


try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user_db, $pass_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupération des informations de l'élève connecté
    $stmt = $pdo->prepare("SELECT * FROM eleves WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $eleve = $stmt->fetch();

    if (!$eleve) {
        session_destroy();
        header("Location: login.php");
        exit();
    }

    // Récupération des vraies notifications de l'élève depuis la BD (Triées par date récente)
    $stmt_notif = $pdo->prepare("SELECT * FROM notifications WHERE eleve_id = ? OR ciblage = 'tous' ORDER BY date_creation DESC LIMIT 3");
    $stmt_notif->execute([$eleve['id']]);
    $db_notifications = $stmt_notif->fetchAll();

    // Système de repli si aucune notification n'est trouvée en base de données
    $notifications = [];
    if (!empty($db_notifications)) {
        foreach ($db_notifications as $notif) {
            $notifications[] = [
                "msg" => $notif['message'],
                "icon" => ($notif['type'] === 'alerte') ? 'exclamation-circle' : 'info-circle'
            ];
        }
    } else {
        $notifications = [
            ["msg" => "Bienvenue sur ton portail, " . htmlspecialchars($eleve['prenom']) . " !", "icon" => "user-graduate"],
            ["msg" => "Le paiement en ligne via Mobile Money (M-Pesa, Airtel, Orange) est opérationnel.", "icon" => "mobile-alt"]
        ];
    }

} catch (PDOException $e) {
    // En production sur InfinityFree, évitez d'afficher les détails de l'erreur brute pour des raisons de sécurité
    die("Erreur de connexion au serveur de l'école. Veuillez réessayer ultérieurement.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ITI KALUNGA</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --bg-body: #0f172a;       /* Bleu nuit très sombre */
            --bg-card: #1e293b;       /* Gris-bleu foncé pour les cartes */
            --sidebar: #111827;       /* Noir bleuté pour la barre latérale */
            --primary: #3b82f6;       /* Bleu vif pour les actions */
            --text-main: #ffffff;     /* Blanc pur pour les titres */
            --text-dim: #94a3b8;      /* Gris clair pour les textes secondaires */
            --accent-green: #4ade80;  /* Vert pour les succès */
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        
        body { 
            background-color: var(--bg-body); 
            color: var(--text-main); 
            display: flex; 
            min-height: 100vh; 
        }

        /* BARRE LATÉRALE */
        .sidebar {
            width: 260px;
            background: var(--sidebar);
            padding: 30px 20px;
            display: flex;
            flex-direction: column;
            border-right: 1px solid rgba(255,255,255,0.05);
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--primary);
            margin-bottom: 40px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        nav a {
            color: var(--text-dim);
            text-decoration: none;
            padding: 12px 15px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: 0.3s;
            margin-bottom: 5px;
        }

        nav a:hover, nav a.active {
            background: rgba(59, 130, 246, 0.1);
            color: var(--text-main);
        }

        .logout-link { color: #f87171; margin-top: auto; }

        /* CONTENU PRINCIPAL */
        .content { flex: 1; padding: 40px; overflow-y: auto; }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }

        .welcome-text h1 { font-size: 1.8rem; margin-bottom: 5px; }
        .welcome-text p { color: var(--text-dim); font-size: 1rem; }

        .user-profile {
            width: 45px; height: 45px;
            background: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            box-shadow: 0 0 15px rgba(59, 130, 246, 0.3);
            text-transform: uppercase;
        }

        /* CARTES ET GRILLE */
        .main-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 25px; }

        @media (max-width: 900px) {
            .main-grid { grid-template-columns: 1fr; }
        }

        .card {
            background: var(--bg-card);
            padding: 25px;
            border-radius: 16px;
            border: 1px solid rgba(255,255,255,0.05);
        }

        h3 { color: var(--text-dim); font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 15px; }

        .total-amount {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--text-main);
            margin: 10px 0;
        }

        /* PROGRESS BAR */
        .progress-container {
            background: rgba(255,255,255,0.1);
            height: 10px;
            border-radius: 10px;
            margin: 20px 0;
            overflow: hidden;
        }

        .progress-fill {
            background: var(--primary);
            height: 100%;
            border-radius: 10px;
            box-shadow: 0 0 10px var(--primary);
            transition: width 0.5s ease-in-out;
        }

        /* BOUTON */
        .primary-btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 15px;
            border-radius: 10px;
            width: 100%;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
            font-size: 1rem;
        }

        .primary-btn:hover { transform: translateY(-2px); filter: brightness(1.1); }

        /* TABLEAU */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { text-align: left; color: var(--text-dim); padding-bottom: 15px; font-size: 0.85rem; }
        td { padding: 15px 0; border-top: 1px solid rgba(255,255,255,0.05); color: var(--text-main); }

        .badge {
            background: rgba(74, 222, 128, 0.1);
            color: var(--accent-green);
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: bold;
        }
        .badge.zero {
            background: rgba(248, 113, 113, 0.1);
            color: #f87171;
        }

        /* NOTIFICATIONS */
        .notif-item {
            display: flex;
            gap: 15px;
            padding: 15px 0;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .notif-item:last-child { border-bottom: none; }
        .notif-item i { color: var(--primary); font-size: 1.1rem; margin-top: 3px; }
        .notif-item p { font-size: 0.9rem; line-height: 1.5; color: var(--text-main); }
    </style>
</head>
<body>

<aside class="sidebar">
    <div class="logo"><i class="fas fa-graduation-cap"></i> ITI KALUNGA</div>
    <nav>
        <a href="Dashboard.php" class="active"><i class="fas fa-th-large"></i> Vue d'ensemble</a>
        <a href="paiement.php"><i class="fas fa-credit-card"></i> Paiement Mobile</a>
        <a href="#"><i class="fas fa-file-invoice"></i> Mes Reçus</a>
        <a href="logout.php" class="logout-link"><i class="fas fa-power-off"></i> Déconnexion</a>
    </nav>
</aside>

<main class="content">
    <header>
        <div class="welcome-text">
            <h1>Bonjour, <?php echo htmlspecialchars($eleve['prenom']); ?> !</h1>
            <p>Portail élève · <?php echo htmlspecialchars($eleve['classe']); ?></p>
        </div>
        <div class="user-profile">
            <?php echo htmlspecialchars(strtoupper(substr($eleve['prenom'], 0, 1) . substr($eleve['nom'], 0, 1))); ?>
        </div>
    </header>

    <div class="main-grid">
        <div class="left-column">
            <div class="card">
                <h3>Solde restant à payer</h3>
                <div class="total-amount">
                    <?php 
                        $reste = $eleve['solde_total'] - $eleve['montant_paye'];
                        echo number_format(max(0, $reste), 0, ',', ' ') . " FC";
                    ?>
                </div>
                <p style="color: var(--text-dim);">Sur un total de <?php echo number_format($eleve['solde_total'], 0, ',', ' '); ?> FC</p>
                
                <div class="progress-container">
                    <?php 
                        // Évite l'erreur Fatale de division par 0 si les frais généraux ne sont pas saisis
                        $pourcentage = ($eleve['solde_total'] > 0) ? ($eleve['montant_paye'] / $eleve['solde_total']) * 100 : 0; 
                        $pourcentage = min(100, max(0, $pourcentage));
                    ?>
                    <div class="progress-fill" style="width: <?php echo $pourcentage; ?>%"></div>
                </div>

                <button class="primary-btn" onclick="window.location.href='paiement.php'">
                    <i class="fas fa-plus-circle"></i> Effectuer un nouveau versement
                </button>
            </div>

            <div class="card" style="margin-top: 25px;">
                <h3>Dernier versement enregistré</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Référence</th>
                            <th>Montant</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <?php if ($eleve['montant_paye'] > 0): ?>
                                <td>#ITI-<?php echo htmlspecialchars(strtoupper(substr($eleve['username'], -3))); ?></td>
                                <td style="font-weight: bold; color: var(--accent-green);"><?php echo number_format($eleve['montant_paye'], 0, ',', ' '); ?> FC</td>
                                <td><span class="badge">PAYÉ</span></td>
                            <?php else: ?>
                                <td style="color: var(--text-dim);">Aucun versement</td>
                                <td style="font-weight: bold; color: #f87171;">0 FC</td>
                                <td><span class="badge zero">EN ATTENTE</span></td>
                            <?php endif; ?>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="right-column">
            <div class="card" style="margin-bottom: 25px;">
                <h3>Mes Informations</h3>
                <div style="margin-top: 15px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                        <span style="color: var(--text-dim);">Matricule :</span>
                        <span style="font-weight: bold;"><?php echo htmlspecialchars($eleve['username']); ?></span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: var(--text-dim);">Contact :</span>
                        <span style="font-weight: bold;"><?php echo htmlspecialchars($eleve['tel_responsable']); ?></span>
                    </div>
                </div>
            </div>

            <div class="card">
                <h3>Notifications</h3>
                <div style="margin-top: 10px;">
                    <?php foreach($notifications as $n): ?>
                    <div class="notif-item">
                        <i class="fas fa-<?php echo htmlspecialchars($n['icon']); ?>"></i>
                        <p><?php echo htmlspecialchars($n['msg']); ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</main>

</body>
</html>