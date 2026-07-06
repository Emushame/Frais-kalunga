<?php
session_start(); // Permet de garder la session ouverte sur le Dashboard

// Connexion à la base de données
$host = 'sql212.infinityfree.com';
$dbname = 'if0_41957335_kalunga_bd';
$user_db = 'if0_41957335';
$pass_db = 'Kalunga2026';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user_db, $pass_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de base de données : " . $e->getMessage());
}

$error_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_input = htmlspecialchars($_POST['username']);
    $pass_input = $_POST['password'];

    // On hache le mot de passe saisi avec sha1 comme dans generer_acces.php
    $pass_hashed = sha1($pass_input);

    // Vérification dans la table eleves
    $stmt = $pdo->prepare("SELECT * FROM eleves WHERE username = :u AND password_hash = :p");
    $stmt->execute([
        'u' => $user_input, 
        'p' => $pass_hashed
    ]);
    $user = $stmt->fetch();

    if ($user) {
        // Succès : On stocke les infos en session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nom'] = $user['nom'];
        $_SESSION['prenom'] = $user['prenom'];
        $_SESSION['classe'] = $user['classe'];

        header("Location: Dashboard.php"); // Redirection
        exit();
    } else {
        $error_msg = "Identifiant ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="log.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Connexion - ITI KALUNGA</title>
    <style>
        .error-banner {
            background: rgba(239, 68, 68, 0.2);
            color: #f87171;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 0.85rem;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }
    </style>
</head>
<body>
    <div class="background-circles">
        <div class="circle"></div>
        <div class="circle"></div>
    </div>
    <div class="login-container">
        <form action="" method="POST"> 
            <h1>Bienvenue</h1>
            <p class="subtitle">Accédez à votre espace ITI KALUNGA</p>
            
            <?php if($error_msg): ?>
                <div class="error-banner"><?php echo $error_msg; ?></div>
            <?php endif; ?>

            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="username" placeholder="Identifiant (ex: KAL-NOM-123)" required>
            </div>
            
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Mot de passe" required>
            </div>
            
            <button type="submit">Se connecter</button>
            
            <div class="footer-links">
                <a href="#">Mot de passe oublié ?</a>
                </div>
        </form>
    </div>
</body>
</html>