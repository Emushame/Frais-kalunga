<?php
session_start();
// Connexion à la base de données
$host = 'localhost'; $dbname = 'kalunga_bd'; $user_db = 'root'; $pass_db = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user_db, $pass_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) { die("Erreur : " . $e->getMessage()); }

// Gestion de la recherche (Correction de l'erreur syntaxe ligne 62)
$search_value = isset($_GET['search']) ? $_GET['search'] : '';
$query_param = "%" . $search_value . "%";

$stmt = $pdo->prepare("SELECT * FROM eleves WHERE nom LIKE ? OR prenom LIKE ? ORDER BY classe ASC");
$stmt->execute([$query_param, $query_param]);
$eleves = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Élèves - ITI KALUNGA</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --bg: #0f172a; --card: #1e293b; --primary: #3b82f6; --text: #ffffff; --dim: #94a3b8; --danger: #ef4444; }
        body { background: var(--bg); color: var(--text); font-family: 'Segoe UI', sans-serif; display: flex; margin: 0; min-height: 100vh; }
        .sidebar { width: 260px; background: #111827; padding: 30px 20px; position: fixed; height: 100vh; border-right: 1px solid rgba(255,255,255,0.05); }
        .content { margin-left: 260px; padding: 40px; width: 100%; }
        .action-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; gap: 20px; }
        .search-box { background: var(--card); border: 1px solid rgba(255,255,255,0.1); padding: 12px 20px; border-radius: 10px; display: flex; align-items: center; flex: 1; }
        .search-box input { background: transparent; border: none; color: white; margin-left: 10px; width: 100%; outline: none; }
        .btn-add { background: var(--primary); color: white; padding: 12px 25px; border-radius: 10px; text-decoration: none; font-weight: bold; }
        .table-container { background: var(--card); border-radius: 16px; border: 1px solid rgba(255,255,255,0.05); overflow: hidden; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th { background: rgba(0,0,0,0.2); padding: 20px; color: var(--dim); font-size: 0.85rem; text-transform: uppercase; }
        td { padding: 18px 20px; border-top: 1px solid rgba(255,255,255,0.05); }
        .avatar { width: 35px; height: 35px; background: var(--primary); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-weight: bold; margin-right: 10px; }
        .btn-action { padding: 8px; border-radius: 6px; text-decoration: none; transition: 0.2s; display: inline-block; }
        .edit { color: var(--primary); } .delete { color: var(--danger); }
        .edit:hover { background: rgba(59, 130, 246, 0.1); }
        .delete:hover { background: rgba(239, 68, 68, 0.1); }
    </style>
</head>
<body>

<aside class="sidebar">
    <h2 style="color:var(--primary);"><i class="fas fa-user-shield"></i> Admin Panel</h2>
    <nav style="margin-top:40px;">
        <a href="gestion_eleves.php" style="color:white; text-decoration:none; display:block; padding:15px 0;"><i class="fas fa-users"></i> Liste des Élèves</a>
    </nav>
</aside>

<main class="content">
    <h1>Gestion des Élèves</h1>
    
    <div class="action-bar">
        <form class="search-box" method="GET">
            <i class="fas fa-search" style="color:var(--dim)"></i>
            <input type="text" name="search" placeholder="Rechercher par nom ou prénom..." value="<?php echo htmlspecialchars($search_value); ?>">
        </form>
        <a href="ajouter_eleve.php" class="btn-add"><i class="fas fa-plus"></i> Inscrire un élève</a>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Élève</th>
                    <th>Classe</th>
                    <th>Matricule</th>
                    <th>Solde Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($eleves as $e): ?>
                <tr>
                    <td>
                        <div class="avatar"><?php echo strtoupper(substr($e['prenom'],0,1)); ?></div>
                        <?php echo htmlspecialchars($e['nom'].' '.$e['prenom']); ?>
                    </td>
                    <td><?php echo htmlspecialchars($e['classe']); ?></td>
                    <td style="color:var(--primary); font-family:monospace;"><?php echo $e['username']; ?></td>
                    <td style="font-weight:bold;"><?php echo number_format($e['solde_total'], 0, ',', ' '); ?> FC</td>
                    <td>
                        <a href="modifier_eleve.php?id=<?php echo $e['id']; ?>" class="btn-action edit" title="Modifier"><i class="fas fa-edit"></i></a>
                        <a href="supprimer_eleve.php?id=<?php echo $e['id']; ?>" class="btn-action delete" title="Supprimer" onclick="return confirm('Voulez-vous vraiment supprimer cet élève ?')"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>
</body>
</html>