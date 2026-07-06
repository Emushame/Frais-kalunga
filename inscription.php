<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Élève - ITI KALUNGA</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* --- Configuration & Variables --- */
        :root {
            --primary-blue: #2563eb;
            --accent-blue: #60a5fa;
            --dark-bg: #0f172a;
            --glass: rgba(255, 255, 255, 0.07);
            --border: rgba(255, 255, 255, 0.15);
            --text-muted: #94a3b8;
        }

        * {
            margin: 0; padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', 'Segoe UI', sans-serif;
        }

        body {
            background: var(--dark-bg);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            position: relative;
            color: #ffffff;
        }

        /* --- Cercles d'ambiance --- */
        .background-circles .circle {
            position: absolute;
            border-radius: 50%;
            filter: blur(70px);
            z-index: 0;
        }
        .circle:nth-child(1) { width: 350px; height: 350px; background: var(--primary-blue); top: -80px; left: -60px; opacity: 0.5; }
        .circle:nth-child(2) { width: 400px; height: 400px; background: #4f46e5; bottom: -100px; right: -40px; opacity: 0.3; }

        /* --- Conteneur de la Carte --- */
        .login-container {
            position: relative;
            z-index: 10;
            background: var(--glass);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid var(--border);
            padding: 30px 40px;
            border-radius: 25px;
            width: 95%;
            max-width: 500px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            text-align: center;
            animation: slideUp 0.6s ease-out;
        }

        .login-header h1 { font-size: 1.6rem; margin-top: 10px; letter-spacing: 1px; }
        .subtitle { color: var(--text-muted); font-size: 0.85rem; margin-bottom: 20px; }
        
        /* Note explicative pour le mot de passe automatique */
        .password-notice {
            background: rgba(96, 165, 250, 0.1);
            border: 1px solid rgba(96, 165, 250, 0.2);
            color: var(--accent-blue);
            font-size: 0.78rem;
            padding: 8px 12px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: left;
            line-height: 1.3;
        }

        /* --- Formulaires --- */
        .input-group { position: relative; margin-bottom: 15px; }
        .input-group i {
            position: absolute; left: 15px; top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            transition: 0.3s; z-index: 2;
        }

        .input-group input, .input-group select {
            width: 100%;
            padding: 12px 15px 12px 45px;
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid var(--border);
            border-radius: 12px;
            color: white;
            font-size: 0.95rem;
            outline: none;
            transition: 0.3s ease;
        }

        .input-group input:focus, .input-group select:focus {
            border-color: var(--accent-blue);
            background: rgba(0, 0, 0, 0.5);
            box-shadow: 0 0 15px rgba(37, 99, 235, 0.2);
        }

        select {
            cursor: pointer; appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='white'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 15px;
        }

        optgroup { background: var(--dark-bg); color: var(--accent-blue); font-weight: 600; }
        option { background: var(--dark-bg); color: white; }

        button {
            width: 100%; padding: 14px; border: none; border-radius: 12px;
            background: linear-gradient(135deg, var(--primary-blue), #4f46e5);
            color: white; font-size: 1rem; font-weight: 600;
            cursor: pointer; transition: 0.3s ease; margin-top: 10px;
        }

        button:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(37, 99, 235, 0.4); }
        .footer-links { margin-top: 20px; }
        .footer-links a { color: var(--text-muted); text-decoration: none; font-size: 0.8rem; }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <div class="background-circles">
        <div class="circle"></div>
        <div class="circle"></div>
    </div>

    <div class="login-container">
        <div class="login-header">
            <i class="fas fa-user-plus" style="font-size: 2.2rem; color: #60a5fa;"></i>
            <h1>Inscription Élève</h1>
            <p class="subtitle">Espace d'administration ITI KALUNGA</p>
        </div>

        <div class="password-notice">
            <i class="fas fa-info-circle"></i> <strong>Sécurité :</strong> Le système générera automatiquement un mot de passe robuste combinant le nom de l'élève et un code numérique unique.
        </div>

        <form action="generer_acces.php" method="POST">
            
            <div class="input-group">
                <i class="fas fa-id-card"></i>
                <input type="text" name="nom" placeholder="Nom de l'élève" required>
            </div>
            
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="prenom" placeholder="Prénom de l'élève" required>
            </div>

            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email_parent" placeholder="Email du Responsable (pour notifications)" required>
            </div>

            <div class="input-group">
                <i class="fas fa-phone-alt"></i>
                <input type="tel" name="tel_responsable" placeholder="Téléphone du Responsable" required>
            </div>

            <div class="input-group">
                <i class="fas fa-school"></i>
                <select name="classe" required>
                    <option value="" disabled selected>Sélectionner la classe et l'option</option>
                    
                    <optgroup label="Cycle de Base">
                        <option value="7eme">7ème Année (EB)</option>
                        <option value="8eme">8ème Année (EB)</option>
                    </optgroup>

                    <optgroup label="Électricité Industrielle">
                        <option value="1ere_Elec">1ère Électricité</option>
                        <option value="2eme_Elec">2ème Électricité</option>
                        <option value="3eme_Elec">3ème Électricité</option>
                        <option value="4eme_Elec">4ème Électricité</option>
                    </optgroup>

                    <optgroup label="Mécanique Automobile">
                        <option value="1ere_MA">1ère Méc. Auto</option>
                        <option value="2eme_MA">2ème Méc. Auto</option>
                        <option value="3eme_MA">3ème Méc. Auto</option>
                        <option value="4eme_MA">4ème Méc. Auto</option>
                    </optgroup>

                    <optgroup label="Mécanique Générale">
                        <option value="1ere_MG">1ère Méc. Générale</option>
                        <option value="2eme_MG">2ème Méc. Générale</option>
                        <option value="3eme_MG">3ème Méc. Générale</option>
                        <option value="4eme_MG">4ème Méc. Générale</option>
                    </optgroup>

                    <optgroup label="Commerciale et Gestion">
                        <option value="1ere_CG">1ère Comm. & Gestion</option>
                        <option value="2eme_CG">2ème Comm. & Gestion</option>
                        <option value="3eme_CG">3ème Comm. & Gestion</option>
                        <option value="4eme_CG">4ème Comm. & Gestion</option>
                    </optgroup>

                    <optgroup label="Pédagogie Générale">
                        <option value="1ere_HP">1ère Pédagogie</option>
                        <option value="2eme_HP">2ème Pédagogie</option>
                        <option value="3eme_HP">3ème Pédagogie</option>
                        <option value="4eme_HP">4ème Pédagogie</option>
                    </optgroup>
                </select>
            </div>

            <button type="submit" name="register_student">Générer les identifiants sécurisés</button>
            
            <div class="footer-links">
                <a href="Dashboard.php"><i class="fas fa-arrow-left"></i> Retour au tableau de bord</a>
            </div>
        </form>
    </div>

</body>
</html>