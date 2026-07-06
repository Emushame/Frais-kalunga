<?php
session_start();

// 1. Paramètres de connexion
$host = 'localhost'; 
$dbname = 'kalunga_bd'; 
$user_db = 'root'; 
$pass_db = '';

// 2. Vérification de la présence de l'ID dans l'URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    
    try {
        // 3. Connexion à la base de données
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user_db, $pass_db);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // 4. Préparation et exécution de la suppression
        $id_a_supprimer = $_GET['id'];
        $stmt = $pdo->prepare("DELETE FROM eleves WHERE id = ?");
        $stmt->execute([$id_a_supprimer]);

        // 5. Redirection vers la liste avec un message de succès (optionnel)
        header("Location: gestion_eleves.php?status=deleted");
        exit();

    } catch (PDOException $e) {
        // En cas d'erreur (ex: clé étrangère liée), on affiche l'erreur
        die("Erreur lors de la suppression : " . $e->getMessage());
    }

} else {
    // Si aucun ID n'est fourni, on retourne à la liste
    header("Location: gestion_eleves.php");
    exit();
}
?>