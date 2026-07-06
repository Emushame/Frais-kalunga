<?php
session_start();
session_destroy(); // Détruit toutes les données de connexion
header("Location: login.php"); // Retour à l'accueil
exit();
?>