<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.php");
    exit;
}

require_once 'config.php';

function calculate_total($cart) {
    $total = 0;
    foreach ($cart as $product) {
        $total += $product['prix'] * $product['quantite'];
    }
    return $total;
}

$total_price = calculate_total($_SESSION['panier']);
$user_id = $_SESSION['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Insérer la commande
    $stmt = $mysqli->prepare("INSERT INTO orders (user_id, total_price) VALUES (?, ?)");
    $stmt->bind_param("id", $user_id, $total_price);
    $stmt->execute();
    $order_id = $stmt->insert_id;
    $stmt->close();

    // Insérer les articles
    $stmt = $mysqli->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    foreach ($_SESSION['panier'] as $product) {
        $stmt->bind_param("iiid", $order_id, $product['id'], $product['quantite'], $product['prix']);
        $stmt->execute();
    }
    $stmt->close();

    // Vider le panier et rediriger
    unset($_SESSION['panier']);
    header("location: confirmation.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalisation de commande - ReNew-Sénégal</title>
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

        /* Main Content */
        .checkout-container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 30px;
        }

        @media (max-width: 768px) {
            .checkout-container {
                grid-template-columns: 1fr;
            }
        }

        .checkout-title {
            font-size: 2.2rem;
            color: var(--dark);
            margin-bottom: 30px;
            grid-column: 1 / -1;
            position: relative;
            padding-bottom: 15px;
        }

        .checkout-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 80px;
            height: 4px;
            background: linear-gradient(to right, var(--primary), var(--accent));
        }

        /* Section Livraison/Paiement */
        .checkout-form {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: var(--shadow-sm);
        }

        .section-title {
            font-size: 1.3rem;
            color: var(--dark);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title i {
            color: var(--primary);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark);
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: var(--transition);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        /* Section Récapitulatif */
        .order-summary {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: var(--shadow-sm);
            align-self: start;
            position: sticky;
            top: 100px;
        }

        .order-items {
            max-height: 300px;
            overflow-y: auto;
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 20px;
        }

        .order-item {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
        }

        .order-item-image {
            width: 60px;
            height: 60px;
            object-fit: contain;
            border-radius: 8px;
            background: #f8f9fa;
            padding: 5px;
        }

        .order-item-details {
            flex: 1;
        }

        .order-item-name {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .order-item-price {
            color: var(--text-light);
            font-size: 0.9rem;
        }

        .order-item-quantity {
            color: var(--text-light);
            font-size: 0.9rem;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .summary-total {
            font-size: 1.2rem;
            font-weight: 700;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #eee;
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
            width: 100%;
            margin-top: 20px;
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

        .payment-methods {
            margin-top: 30px;
        }

        .payment-method {
            display: flex;
            align-items: center;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: var(--transition);
        }

        .payment-method:hover {
            border-color: var(--primary);
        }

        .payment-method i {
            font-size: 1.5rem;
            margin-right: 15px;
            color: var(--primary);
        }

        .payment-method.active {
            border-color: var(--primary);
            background-color: #f0f4ff;
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
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .order-summary {
                position: static;
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

<!-- Main Content -->
<main class="checkout-container">
    <h1 class="checkout-title">Finalisation de commande</h1>
    
    <div class="checkout-form">
        <form action="checkout.php" method="post">
            <div class="section-title">
                <i class="fas fa-truck"></i>
                <h2>Informations de livraison</h2>
            </div>
            
            <div class="form-group">
                <label for="fullname">Nom complet</label>
                <input type="text" id="fullname" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="address">Adresse</label>
                <input type="text" id="address" class="form-control" required>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="city">Ville</label>
                    <input type="text" id="city" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="zipcode">Code postal</label>
                    <input type="text" id="zipcode" class="form-control" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="phone">Téléphone</label>
                <input type="tel" id="phone" class="form-control" required>
            </div>
            
            <div class="section-title">
                <i class="fas fa-credit-card"></i>
                <h2>Méthode de paiement</h2>
            </div>
            
            <div class="payment-methods">
                <div class="payment-method active">
                    <i class="fab fa-cc-visa"></i>
                    <span>Carte bancaire</span>
                </div>
                
                <div class="payment-method">
                    <i class="fas fa-mobile-alt"></i>
                    <span>Paiement mobile (Orange Money, Wave...)</span>
                </div>
                
                <div class="payment-method">
                    <i class="fas fa-money-bill-wave"></i>
                    <span>Paiement à la livraison</span>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-check-circle"></i> Confirmer la commande
            </button>
        </form>
    </div>
    
    <div class="order-summary">
        <h2>Récapitulatif de commande</h2>
        
        <div class="order-items">
            <?php if (isset($_SESSION['panier']) && count($_SESSION['panier']) > 0): ?>
                <?php foreach ($_SESSION['panier'] as $product): ?>
                    <div class="order-item">
                        <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['nom']); ?>" class="order-item-image">
                        
                        <div class="order-item-details">
                            <div class="order-item-name"><?php echo htmlspecialchars($product['nom']); ?></div>
                            <div class="order-item-price"><?php echo number_format($product['prix'], 0, ',', ' '); ?> FCFA</div>
                            <div class="order-item-quantity">Quantité: <?php echo $product['quantite']; ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Votre panier est vide</p>
            <?php endif; ?>
        </div>
        
        <div class="summary-row">
            <span>Sous-total</span>
            <span><?php echo number_format($total_price, 0, ',', ' '); ?> FCFA</span>
        </div>
        
        <div class="summary-row">
            <span>Livraison</span>
            <span>Gratuite</span>
        </div>
        
        <div class="summary-row summary-total">
            <span>Total</span>
            <span><?php echo number_format($total_price, 0, ',', ' '); ?> FCFA</span>
        </div>
    </div>
</main>

<!-- Footer -->
<footer>
    <div class="container">
        <p>&copy; <?php echo date('Y'); ?> ReNew-Sénégal. Tous droits réservés.</p>
    </div>
</footer>

<script>
    // Sélection de la méthode de paiement
    document.querySelectorAll('.payment-method').forEach(method => {
        method.addEventListener('click', function() {
            document.querySelectorAll('.payment-method').forEach(m => {
                m.classList.remove('active');
            });
            this.classList.add('active');
        });
    });
</script>

</body>
</html>