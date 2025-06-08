<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de commande - ReNew-Sénégal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --accent: #f72585;
            --dark: #1b263b;
            --light: #f8f9fa;
            --success: #4cc9f0;
            --text: #2b2d42;
            --text-light: #8d99ae;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.12);
            --shadow-md: 0 4px 6px rgba(0,0,0,0.1);
            --shadow-lg: 0 10px 15px rgba(0,0,0,0.1);
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            color: var(--text);
            line-height: 1.6;
        }

        /* Header */
        header {
            background: linear-gradient(135deg, var(--dark), var(--primary));
            color: white;
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: var(--shadow-lg);
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid white;
        }

        .brand-name {
            font-size: 1.8rem;
            font-weight: 600;
            background: linear-gradient(to right, white, #f3f4f6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        nav {
            display: flex;
            align-items: center;
            gap: 30px;
        }

        .nav-link {
            color: white;
            text-decoration: none;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: var(--transition);
            position: relative;
            padding: 5px 0;
        }

        .nav-link:hover {
            color: var(--accent);
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--accent);
            transition: var(--transition);
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .search-cart {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .cart-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--accent);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: bold;
        }

        /* Confirmation Container */
        .confirmation-container {
            max-width: 800px;
            margin: 80px auto;
            padding: 40px;
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow-md);
            text-align: center;
        }

        .confirmation-icon {
            font-size: 5rem;
            color: var(--success);
            margin-bottom: 20px;
            animation: bounce 1s;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {transform: translateY(0);}
            40% {transform: translateY(-30px);}
            60% {transform: translateY(-15px);}
        }

        .confirmation-title {
            font-size: 2.5rem;
            color: var(--dark);
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 15px;
        }

        .confirmation-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(to right, var(--primary), var(--accent));
        }

        .confirmation-message {
            font-size: 1.2rem;
            margin-bottom: 30px;
            color: var(--text-light);
        }

        .confirmation-details {
            background: #f9f9f9;
            border-radius: 12px;
            padding: 20px;
            margin: 30px 0;
            text-align: left;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #eee;
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            color: var(--dark);
        }

        .detail-value {
            color: var(--text);
        }

        .btn-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 15px 25px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(67, 97, 238, 0.4);
        }

        .btn-secondary {
            background: white;
            color: var(--primary);
            border: 2px solid var(--primary);
        }

        .btn-secondary:hover {
            background: #f0f4ff;
        }

        /* Footer */
        footer {
            background: linear-gradient(135deg, var(--dark), var(--primary));
            color: white;
            padding: 30px 0;
            margin-top: 80px;
            text-align: center;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .confirmation-container {
                margin: 40px 20px;
                padding: 30px 20px;
            }
            
            .btn-group {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<!-- Header -->
<header>
    <div class="header-container">
        <div class="brand">
            <img src="logo/logo-renew-senegal.jpg" alt="Logo" class="logo">
            <span class="brand-name">ReNew-Sénégal</span>
        </div>
        
        <nav>
            <a href="welcome.php" class="nav-link"><i class="fas fa-home"></i> Accueil</a>
            <a href="all-produits.php" class="nav-link"><i class="fas fa-shopping-bag"></i> Produits</a>
            <a href="abouts.php" class="nav-link"><i class="fas fa-info-circle"></i> À propos</a>
        </nav>
        
        <div class="search-cart">
            <a href="profile.php" class="nav-link"><i class="fas fa-user-circle"></i></a>
            <a href="panier.php" class="nav-link">
                <i class="fas fa-shopping-cart"></i>
                <?php if (isset($_SESSION['panier']) && count($_SESSION['panier']) > 0): ?>
                    <span class="cart-count"><?php echo count($_SESSION['panier']); ?></span>
                <?php endif; ?>
            </a>
            <a href="logout.php" class="nav-link"><i class="fas fa-sign-out-alt"></i></a>
        </div>
    </div>
</header>

<!-- Confirmation Content -->
<main class="confirmation-container">
    <div class="confirmation-icon">
        <i class="fas fa-check-circle"></i>
    </div>
    
    <h1 class="confirmation-title">Commande confirmée !</h1>
    
    <p class="confirmation-message">
        Merci pour votre achat chez ReNew-Sénégal. Votre commande a été enregistrée avec succès.
    </p>
    
    <div class="confirmation-details">
        <div class="detail-item">
            <span class="detail-label">Numéro de commande:</span>
            <span class="detail-value">#<?php echo rand(100000, 999999); ?></span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Date:</span>
            <span class="detail-value"><?php echo date('d/m/Y'); ?></span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Statut:</span>
            <span class="detail-value">En préparation</span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Méthode de paiement:</span>
            <span class="detail-value">Carte bancaire</span>
        </div>
    </div>
    
    <p class="confirmation-message">
        Nous avons envoyé les détails de votre commande à votre adresse email.<br>
        Vous pouvez suivre l'état de votre commande dans votre espace client.
    </p>
    
    <div class="btn-group">
        <a href="all-produits.php" class="btn btn-primary">
            <i class="fas fa-shopping-bag"></i> Continuer vos achats
        </a>
        <a href="profile.php" class="btn btn-secondary">
            <i class="fas fa-clipboard-list"></i> Voir mes commandes
        </a>
    </div>
</main>

<!-- Footer -->
<footer>
    <div class="header-container">
        <p>&copy; <?php echo date('Y'); ?> ReNew-Sénégal. Tous droits réservés.</p>
    </div>
</footer>

</body>
</html>