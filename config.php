<?php
// Configuration de la base de données
$host = 'sql212.infinityfree.com';
$dbname = 'if0_41957335_kalunga_bd';
$user_db = 'if0_41957335';
$pass_db = 'Kalunga2026';

try {
    // Connexion via PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user_db, $pass_db);
    
    // Configuration des erreurs
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Si la connexion échoue, on arrête tout
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>