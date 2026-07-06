<?php
$amount = isset($_GET['amount']) ? intval($_GET['amount']) : 0;
$motif = isset($_GET['motif']) ? urldecode($_GET['motif']) : 'Frais';
$reference = isset($_GET['reference']) ? urldecode($_GET['reference']) : 'KAL-000000';
$operator = isset($_GET['operator']) ? strtolower(urldecode($_GET['operator'])) : 'mpesa';
$phone = isset($_GET['phone']) ? urldecode($_GET['phone']) : '';
$operatorLabel = ($operator === 'airtel') ? 'Airtel Money' : 'M-Pesa RDC';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sécurisé | Passerelle de Paiement ITI KALUNGA</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary: #2563eb; --secondary: #1e40af; --success: #22c55e; --danger: #ef4444; --bg: #f8fafc; --text-dark: #0f172a; --text-light: #64748b; }
        body { background: var(--bg); font-family: 'Inter', 'Segoe UI', sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; padding: 20px; }
        .payment-card { background: white; width: 100%; max-width: 520px; border-radius: 24px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04); overflow: hidden; animation: slideUp 0.5s ease-out; }
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .header { background: linear-gradient(135deg, var(--primary), var(--secondary)); color: white; padding: 32px 20px; text-align: center; }
        .header i.main-lock { font-size: 2.6rem; margin-bottom: 8px; }
        .body { padding: 28px 24px; }
        .badge-verified { background: #dcfce7; color: #166534; padding: 5px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; display: inline-block; margin-bottom: 16px; }
        .amount-container { background: #f1f5f9; padding: 18px; border-radius: 16px; margin: 16px 0; }
        .amount-label { font-size: 0.8rem; color: var(--text-light); text-transform: uppercase; letter-spacing: 1px; }
        .amount-value { font-size: 2rem; font-weight: 800; color: var(--text-dark); margin-top: 5px; }
        .info-box { background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 12px; padding: 12px; margin: 14px 0; color: #1d4ed8; font-size: 0.95rem; }
        .steps { text-align: left; padding: 0 0 0 18px; color: var(--text-dark); line-height: 1.6; }
        .btn { display: flex; align-items: center; justify-content: center; gap: 10px; width: 100%; padding: 14px; margin-top: 12px; border: none; border-radius: 12px; font-weight: 700; font-size: 1rem; cursor: pointer; text-decoration: none; transition: all 0.2s; }
        .btn-pay { background: var(--success); color: white; box-shadow: 0 4px 14px 0 rgba(34, 197, 94, 0.39); }
        .btn-pay:hover { background: #16a34a; transform: translateY(-2px); }
        .btn-cancel { background: transparent; color: var(--danger); border: 2px solid #fee2e2; }
        .btn-cancel:hover { background: #fef2f2; border-color: #fecaca; }
        .security-footer { display: flex; align-items: center; justify-content: center; gap: 8px; font-size: 0.8rem; color: #94a3b8; margin-top: 20px; padding-top: 16px; border-top: 1px solid #f1f5f9; }
    </style>
</head>
<body>
    <div class="payment-card">
        <div class="header">
            <i class="fas fa-shield-halved main-lock"></i>
            <div style="font-weight: 700; font-size: 1.2rem;">ITI KALUNGA</div>
            <div style="font-size: 0.95rem; opacity: 0.9;">Paiement Mobile Money</div>
        </div>
        <div class="body">
            <span class="badge-verified"><i class="fas fa-check-circle"></i> Transaction sécurisée</span>
            <div class="amount-container">
                <div class="amount-label">Montant à régler</div>
                <div class="amount-value"><?php echo number_format($amount, 0, ',', ' '); ?> <span style="font-size: 1rem;">FC</span></div>
            </div>
            <div class="info-box">
                <strong>Référence :</strong> <?php echo htmlspecialchars($reference); ?><br>
                <strong>Opérateur :</strong> <?php echo htmlspecialchars($operatorLabel); ?><br>
                <strong>Motif :</strong> <?php echo htmlspecialchars($motif); ?>
            </div>
            <p style="color: var(--text-light); line-height: 1.6;">Veuillez suivre le processus réel ci-dessous depuis votre téléphone afin de finaliser le règlement.</p>
            <ol class="steps">
                <li>Ouvrez votre application ou le menu USSD de <?php echo htmlspecialchars($operatorLabel); ?>.</li>
                <li>Sélectionnez l’option de paiement marchand ou de paiement de facture.</li>
                <li>Saisissez le montant exact de <strong><?php echo number_format($amount, 0, ',', ' '); ?> FC</strong>.</li>
                <li>Utilisez la référence <strong><?php echo htmlspecialchars($reference); ?></strong> comme justificatif ou motif de paiement.</li>
                <li>Confirmez l’opération sur votre mobile puis appuyez sur le bouton ci-dessous.</li>
            </ol>
            <a href="callback.php?status=approved" class="btn btn-pay"><i class="fas fa-fingerprint"></i> J’AI ENVOYÉ LE PAIEMENT</a>
            <a href="Dashboard.php?error=cancel" class="btn btn-cancel">ANNULER</a>
            <div class="security-footer"><i class="fas fa-lock"></i><span>Validation manuelle de la transaction</span></div>
        </div>
    </div>
</body>
</html>