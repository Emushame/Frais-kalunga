# Frais-kalunga

Système de gestion des frais scolaires avec paiement mobile pour les élèves de l’ITI KALUNGA.

## Présentation

Cette application web permet de :
- gérer les élèves et leurs informations,
- suivre les frais scolaires,
- permettre un paiement via Mobile Money,
- enregistrer les paiements et mettre à jour le solde des élèves,
- envoyer une confirmation par email après validation.

## Fonctionnalités principales

- Inscription et connexion des élèves
- Tableau de bord élève avec suivi du paiement
- Paiement de frais scolaires
- Support des opérateurs :
  - M-Pesa RDC
  - Airtel Money
- Génération d’une référence de transaction unique
- Confirmation de paiement et mise à jour du solde
- Historique des paiements enregistré en base de données

## Technologies utilisées

- PHP 7+
- MySQL / MariaDB
- HTML / CSS / JavaScript
- PHPMailer pour les notifications email

## Structure du projet

- [index.php](index.php) : page d’accueil
- [login.php](login.php) : connexion des élèves
- [inscription.php](inscription.php) : inscription des élèves
- [Dashboard.php](Dashboard.php) : tableau de bord élève
- [paiement.php](paiement.php) : formulaire de paiement
- [process_paiement.php](process_paiement.php) : traitement du paiement
- [simulateur_fedapay.php](simulateur_fedapay.php) : écran de confirmation du paiement mobile
- [callback.php](callback.php) : validation du paiement et mise à jour du système
- [config.php](config.php) : configuration de la base de données
- [BDD.D](BDD.D) : script SQL de création de la base

## Base de données

Le projet attend une base MySQL nommée `kalunga_bd` avec au minimum les tables suivantes :

- `eleves`
- `paiements`
- `notifications`

Le script de création est disponible dans [BDD.D](BDD.D).

## Configuration requise

Avant de lancer l’application, assurez-vous d’avoir :
- un serveur web local (XAMPP, WAMP, Laragon, etc.)
- PHP installé
- MySQL installé
- l’extension PDO MySQL activée

## Installation

1. Placez le projet dans le dossier de votre serveur web.
2. Importez le script SQL depuis [BDD.D](BDD.D).
3. Modifiez les paramètres de connexion dans [config.php](config.php) si nécessaire.
4. Démarrez votre serveur Apache et MySQL.
5. Ouvrez l’application via votre navigateur.

## Processus de paiement mobile

Le flux de paiement suit ce processus :

1. L’élève remplit le formulaire de paiement.
2. Le système enregistre la transaction avec une référence unique.
3. L’utilisateur reçoit un écran de confirmation avec les instructions à suivre sur son téléphone.
4. Il exécute le paiement via :
   - M-Pesa RDC
   - Airtel Money
5. Après validation, le système met à jour le montant payé de l’élève.

## Notes importantes

- Le projet intègre actuellement un flux de paiement réaliste côté interface et logique applicative.
- Pour un usage production, il faudra connecter cette application à des API officielles de paiement mobile avec des credentials réels.
- Les identifiants SMTP utilisés dans le projet doivent être remplacés par des informations sécurisées en production.

## Sécurité

Quelques recommandations importantes :
- ne jamais exposer les mots de passe en clair,
- utiliser des variables d’environnement ou un fichier sécurisé pour les secrets,
- protéger les routes sensibles,
- utiliser HTTPS en production.

## Auteurs

Projet développé pour l’ITI KALUNGA.
