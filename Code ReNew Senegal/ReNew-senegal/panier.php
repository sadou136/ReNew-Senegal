<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.php");
    exit;
}

function calculate_total($cart) {
    $total = 0;
    foreach ($cart as $product) {
        $total += $product['prix'] * $product['quantite'];
    }
    return $total;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && isset($_POST['index'])) {
        $index = $_POST['index'];

        if ($_POST['action'] == 'increment') {
            $_SESSION['panier'][$index]['quantite'] += 1;
        } elseif ($_POST['action'] == 'decrement' && $_SESSION['panier'][$index]['quantite'] > 1) {
            $_SESSION['panier'][$index]['quantite'] -= 1;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre Panier - ReNew-Sénégal</title>
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
        .cart-container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 0 20px;
        }

        .cart-title {
            font-size: 2.5rem;
            color: var(--dark);
            margin-bottom: 30px;
            text-align: center;
            position: relative;
        }

        .cart-title::after {
            content: '';
            display: block;
            width: 100px;
            height: 4px;
            background: linear-gradient(to right, var(--primary), var(--accent));
            margin: 15px auto;
            border-radius: 2px;
        }

        .cart-empty {
            text-align: center;
            padding: 50px;
            font-size: 1.2rem;
            color: var(--text-light);
        }

        .cart-empty i {
            font-size: 3rem;
            color: var(--text-light);
            margin-bottom: 20px;
            display: block;
        }

        .cart-empty a {
            display: inline-block;
            margin-top: 20px;
            background: var(--primary);
            color: white;
            padding: 12px 30px;
            border-radius: 30px;
            text-decoration: none;
            transition: var(--transition);
        }

        .cart-empty a:hover {
            background: var(--secondary);
            transform: translateY(-3px);
        }

        .cart-items {
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .cart-item {
            display: flex;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid #eee;
            transition: var(--transition);
        }

        .cart-item:hover {
            background: #f9f9f9;
        }

        .cart-item-image {
            width: 100px;
            height: 100px;
            object-fit: contain;
            border-radius: 8px;
            margin-right: 20px;
            background: #f8f9fa;
            padding: 10px;
        }

        .cart-item-details {
            flex: 1;
        }

        .cart-item-name {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 5px;
        }

        .cart-item-price {
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 10px;
        }

        .cart-item-quantity {
            display: flex;
            align-items: center;
        }

        .quantity-btn {
            width: 30px;
            height: 30px;
            background: var(--light);
            border: none;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
        }

        .quantity-btn:hover {
            background: var(--primary);
            color: white;
        }

        .quantity-value {
            min-width: 40px;
            text-align: center;
            font-weight: 600;
        }

        .cart-item-remove {
            margin-left: 20px;
        }

        .remove-btn {
            background: none;
            border: none;
            color: var(--accent);
            cursor: pointer;
            font-size: 1.2rem;
            transition: var(--transition);
        }

        .remove-btn:hover {
            transform: scale(1.2);
        }

        .cart-summary {
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            padding: 30px;
            margin-top: 30px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .summary-total {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--dark);
        }

        .summary-price {
            font-weight: 700;
            color: var(--primary);
        }

        .cart-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .btn {
            padding: 15px 30px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(67, 97, 238, 0.4);
        }

        .btn-outline {
            background: white;
            color: var(--primary);
            border: 2px solid var(--primary);
        }

        .btn-outline:hover {
            background: #f0f4ff;
        }

        .btn-danger {
            background: var(--accent);
            color: white;
        }

        .btn-danger:hover {
            background: #e5177b;
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
            .cart-item {
                flex-direction: column;
                text-align: center;
                padding: 30px 20px;
            }
            
            .cart-item-image {
                margin-right: 0;
                margin-bottom: 20px;
            }
            
            .cart-item-details {
                margin-bottom: 20px;
            }
            
            .cart-item-remove {
                margin-left: 0;
                margin-top: 20px;
            }
            
            .cart-actions {
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

<!-- Main Content -->
<main class="cart-container">
    <h1 class="cart-title">Votre Panier</h1>
    
    <?php if (isset($_SESSION['panier']) && count($_SESSION['panier']) > 0): ?>
        <div class="cart-items">
            <?php foreach ($_SESSION['panier'] as $index => $product): ?>
                <div class="cart-item">
                    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['nom']); ?>" class="cart-item-image">
                    
                    <div class="cart-item-details">
                        <div class="cart-item-name"><?php echo htmlspecialchars($product['nom']); ?></div>
                        <div class="cart-item-price"><?php echo number_format($product['prix'], 0, ',', ' '); ?> FCFA</div>
                    </div>
                    
                    <div class="cart-item-quantity">
                        <form method="post" action="panier.php" style="display: inline;">
                            <input type="hidden" name="index" value="<?php echo $index; ?>">
                            <input type="hidden" name="action" value="decrement">
                            <button type="submit" class="quantity-btn">-</button>
                        </form>
                        
                        <span class="quantity-value"><?php echo $product['quantite']; ?></span>
                        
                        <form method="post" action="panier.php" style="display: inline;">
                            <input type="hidden" name="index" value="<?php echo $index; ?>">
                            <input type="hidden" name="action" value="increment">
                            <button type="submit" class="quantity-btn">+</button>
                        </form>
                    </div>
                    
                    <div class="cart-item-remove">
                        <a href="remove_from_cart.php?index=<?php echo $index; ?>" class="remove-btn" title="Retirer du panier">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="cart-summary">
            <div class="summary-row">
                <span>Sous-total</span>
                <span class="summary-price"><?php echo number_format(calculate_total($_SESSION['panier']), 0, ',', ' '); ?> FCFA</span>
            </div>
            <div class="summary-row">
                <span>Livraison</span>
                <span>Gratuite</span>
            </div>
            <div class="summary-row summary-total">
                <span>Total</span>
                <span class="summary-price"><?php echo number_format(calculate_total($_SESSION['panier']), 0, ',', ' '); ?> FCFA</span>
            </div>
        </div>
        
        <div class="cart-actions">
            <a href="all-produits.php" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i> Continuer mes achats
            </a>
            <a href="clear_cart.php" class="btn btn-danger">
                <i class="fas fa-trash-alt"></i> Vider le panier
            </a>
            <a href="checkout.php" class="btn btn-primary">
                <i class="fas fa-credit-card"></i> Passer la commande
            </a>
        </div>
    <?php else: ?>
        <div class="cart-empty">
            <i class="fas fa-shopping-cart"></i>
            <p>Votre panier est vide</p>
            <a href="all-produits.php">
                <i class="fas fa-arrow-left"></i> Commencez vos achats
            </a>
        </div>
    <?php endif; ?>
</main>

<!-- Footer -->
<footer>
    <div class="container">
        <p>&copy; <?php echo date('Y'); ?> ReNew-Sénégal. Tous droits réservés.</p>
    </div>
</footer>

</body>
</html>