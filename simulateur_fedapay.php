<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sécurisé | Passerelle de Paiement ITI KALUNGA</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #2563eb;
            --secondary: #1e40af;
            --success: #22c55e;
            --danger: #ef4444;
            --bg: #f8fafc;
            --text-dark: #0f172a;
            --text-light: #64748b;
        }

        body { 
            background: var(--bg); 
            font-family: 'Inter', 'Segoe UI', sans-serif; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            min-height: 100vh; 
            margin: 0;
        }

        .payment-card { 
            background: white; 
            width: 100%;
            max-width: 420px; 
            border-radius: 24px; 
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04); 
            overflow: hidden;
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .header { 
            background: linear-gradient(135deg, var(--primary), var(--secondary)); 
            color: white; 
            padding: 40px 20px; 
            text-align: center;
            position: relative;
        }

        .header i.main-lock {
            font-size: 3rem;
            margin-bottom: 10px;
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.2));
        }

        .body { padding: 40px 30px; text-align: center; }

        .merchant-name { font-weight: 700; font-size: 1.2rem; margin-bottom: 5px; color: white; }
        .payment-label { font-size: 0.9rem; opacity: 0.9; }

        .amount-container {
            background: #f1f5f9;
            padding: 20px;
            border-radius: 16px;
            margin: 20px 0;
        }

        .amount-label { font-size: 0.8rem; color: var(--text-light); text-transform: uppercase; letter-spacing: 1px; }
        .amount-value { font-size: 2.2rem; font-weight: 800; color: var(--text-dark); margin-top: 5px; }

        .info-text { color: var(--text-light); font-size: 0.95rem; line-height: 1.5; margin-bottom: 30px; }

        .btn { 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            gap: 10px;
            width: 100%; 
            padding: 16px; 
            margin-top: 12px; 
            border: none; 
            border-radius: 12px; 
            font-weight: 700; 
            font-size: 1rem;
            cursor: pointer; 
            text-decoration: none; 
            transition: all 0.2s; 
        }

        .btn-pay { 
            background: var(--success); 
            color: white; 
            box-shadow: 0 4px 14px 0 rgba(34, 197, 94, 0.39);
        }

        .btn-pay:hover { 
            background: #16a34a; 
            transform: translateY(-2px);
            box-shadow: 0 6px 20px 0 rgba(34, 197, 94, 0.23);
        }

        .btn-cancel { 
            background: transparent; 
            color: var(--danger); 
            border: 2px solid #fee2e2;
        }

        .btn-cancel:hover { background: #fef2f2; border-color: #fecaca; }

        .security-footer { 
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 0.8rem; 
            color: #94a3b8; 
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #f1f5f9;
        }

        .badge-verified {
            background: #dcfce7;
            color: #166534;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
    </style>
</head>
<body>

    <div class="payment-card">
        <div class="header">
            <i class="fas fa-shield-halved main-lock"></i>
            <div class="merchant-name">ITI KALUNGA</div>
            <div class="payment-label">ScolarPay Gateway</div>
        </div>

        <div class="body">
            <span class="badge-verified"><i class="fas fa-check-circle"></i> Transaction Sécurisée</span>
            
            <div class="amount-container">
                <div class="amount-label">Montant à régler</div>
                <div class="amount-value">
                    <?php echo number_format($_GET['amount'], 0, ',', ' '); ?> <span style="font-size: 1.2rem;">FC</span>
                </div>
            </div>

            <p class="info-text">
                Veuillez confirmer l'opération sur votre mobile après avoir validé cette étape. Un SMS de confirmation vous sera envoyé.
            </p>
            
            <a href="callback.php?status=approved" class="btn btn-pay">
                <i class="fas fa-fingerprint"></i> CONFIRMER LE PAIEMENT
            </a>

            <a href="Dashboard.php?error=cancel" class="btn btn-cancel">
                ANNULER
            </a>
            
            <div class="security-footer">
                <i class="fas fa-lock"></i>
                <span>Cryptage de bout en bout AES-256</span>
            </div>
        </div>
    </div>

</body>
</html>