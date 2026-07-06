-- 1. Création de la base de données
CREATE DATABASE IF NOT EXISTS kalunga_bd CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE kalunga_bd;

-- 2. Table des élèves (stocke les infos, les accès et le solde)
CREATE TABLE IF NOT EXISTS eleves (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    tel_responsable VARCHAR(20) NOT NULL,
    email_parent VARCHAR(100), -- Utile pour les notifications SMTP
    classe VARCHAR(50) NOT NULL,
    username VARCHAR(20) UNIQUE NOT NULL, -- Identifiant généré (ex: KAL-...)
    password_hash VARCHAR(255) NOT NULL,  -- Mot de passe haché
    solde_total INT DEFAULT 450000,       -- Frais totaux fixés à 450.000 FC par défaut
    montant_paye INT DEFAULT 0,           -- Somme déjà versée
    date_inscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 3. Table des paiements (Historique des transactions)
CREATE TABLE IF NOT EXISTS paiements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    eleve_id INT NOT NULL,
    montant INT NOT NULL,
    motif VARCHAR(100) NOT NULL,          -- Scolarité, Pratique, etc.
    mois_concerne VARCHAR(20),
    reference VARCHAR(50) UNIQUE,         -- Numéro de transaction (ex: PAY-...)
    methode VARCHAR(20),                  -- M-Pesa, Orange Money
    date_paiement DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (eleve_id) REFERENCES eleves(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 4. Table des notifications (Optionnel : pour garder une trace des messages envoyés)
CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    eleve_id INT NOT NULL,
    type_notif VARCHAR(20),               -- SMTP ou SMS
    message TEXT NOT NULL,
    date_envoi TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (eleve_id) REFERENCES eleves(id) ON DELETE CASCADE
) ENGINE=InnoDB;