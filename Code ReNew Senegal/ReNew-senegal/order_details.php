<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.php");
    exit;
}

require_once 'config.php';

// Vérifier si l'ID de commande est présent dans l'URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("location: profile.php");
    exit;
}

$order_id = $_GET['id'];
$user_id = $_SESSION["id"];

// Récupérer les informations de base de la commande
$sql_order = "SELECT o.id, o.total_price, o.created_at, 
              COUNT(oi.id) as items_count,
              o.payment_method, o.shipping_address
              FROM orders o
              LEFT JOIN order_items oi ON o.id = oi.order_id
              WHERE o.id = ? AND o.user_id = ?
              GROUP BY o.id";
$order = null;

if ($stmt = $mysqli->prepare($sql_order)) {
    $stmt->bind_param("ii", $order_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();
    $stmt->close();
}

// Si la commande n'existe pas ou n'appartient pas à l'utilisateur
if (!$order) {
    header("location: profile.php");
    exit;
}

// Récupérer les articles de la commande
$sql_items = "SELECT oi.id, oi.product_id, oi.quantity, oi.price, 
              p.nom as name, p.image
              FROM order_items oi
              JOIN produits p ON oi.product_id = p.id
              WHERE oi.order_id = ?";
$items = [];

if ($stmt = $mysqli->prepare($sql_items)) {
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
    $stmt->close();
}

// Récupérer les informations de l'utilisateur
$sql_user = "SELECT username FROM users WHERE id = ?";
$username = '';
if ($stmt = $mysqli->prepare($sql_user)) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($username);
    $stmt->fetch();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la Commande #<?php echo $order['id']; ?> - ReNew-Sénégal</title>
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

        /* Order Container */
        .order-container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 0 20px;
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .order-title {
            font-size: 2rem;
            color: var(--dark);
        }

        .order-meta {
            display: flex;
            gap: 20px;
            color: var(--text-light);
        }

        .order-meta-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .order-status {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 50px;
            font-weight: 600;
            margin-top: 10px;
        }

        .status-processing {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-shipped {
            background-color: #c3e6cb;
            color: #155724;
        }

        .status-delivered {
            background-color: #d4edda;
            color: #155724;
        }

        /* Order Grid */
        .order-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
        }

        @media (max-width: 768px) {
            .order-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Order Items */
        .order-items {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: var(--shadow-sm);
        }

        .items-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .section-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--dark);
        }

        .item-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .order-item {
            display: flex;
            gap: 15px;
            padding: 15px 0;
            border-bottom: 1px solid #f5f5f5;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .item-image {
            width: 80px;
            height: 80px;
            border-radius: 8px;
            object-fit: cover;
        }

        .item-details {
            flex: 1;
        }

        .item-name {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .item-meta {
            display: flex;
            gap: 15px;
            color: var(--text-light);
            font-size: 0.9rem;
        }

        .item-price {
            font-weight: 600;
            color: var(--text);
        }

        .item-total {
            font-weight: 700;
            color: var(--primary);
        }

        /* Order Summary */
        .order-summary {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: var(--shadow-sm);
            align-self: flex-start;
            position: sticky;
            top: 20px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .summary-label {
            color: var(--text-light);
        }

        .summary-value {
            font-weight: 500;
        }

        .summary-total {
            font-weight: 700;
            font-size: 1.2rem;
            color: var(--primary);
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }

        /* Shipping Info */
        .shipping-info {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: var(--shadow-sm);
            margin-top: 30px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 15px;
        }

        @media (max-width: 768px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
        }

        .info-block h4 {
            font-size: 1rem;
            margin-bottom: 10px;
            color: var(--text-light);
        }

        .info-content {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            line-height: 1.6;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
            transition: var(--transition);
            border: none;
            cursor: pointer;
        }

        .btn-outline {
            background: white;
            color: var(--primary);
            border: 1px solid var(--primary);
        }

        .btn-outline:hover {
            background: #f0f4ff;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--secondary);
        }

        .btn-back {
            margin-top: 30px;
        }

        /* Footer */
        footer {
            background: linear-gradient(135deg, var(--dark), var(--primary));
            color: white;
            padding: 30px 0;
            margin-top: 80px;
            text-align: center;
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
            <a href="profile.php" class="nav-link"><i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($username); ?></a>
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

<!-- Order Details -->
<main class="order-container">
    <div class="order-header">
        <div>
            <h1 class="order-title">Commande #<?php echo $order['id']; ?></h1>
            <div class="order-meta">
                <div class="order-meta-item">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Passée le <?php echo date('d/m/Y à H:i', strtotime($order['created_at'])); ?></span>
                </div>
                <div class="order-meta-item">
                    <i class="fas fa-box"></i>
                    <span><?php echo $order['items_count']; ?> article(s)</span>
                </div>
            </div>
            <?php 
            // Générer un statut aléatoire pour l'exemple
            $statuses = ['processing', 'shipped', 'delivered'];
            $random_status = $statuses[array_rand($statuses)];
            ?>
            <span class="order-status status-<?php echo $random_status; ?>">
                <?php 
                echo ucfirst($random_status);
                if ($random_status == 'processing') echo ' <i class="fas fa-spinner fa-pulse"></i>';
                if ($random_status == 'shipped') echo ' <i class="fas fa-truck"></i>';
                if ($random_status == 'delivered') echo ' <i class="fas fa-check"></i>';
                ?>
            </span>
        </div>
    </div>
    
    <div class="order-grid">
        <div>
            <div class="order-items">
                <div class="items-header">
                    <h2 class="section-title">Articles commandés</h2>
                </div>
                
                <div class="item-list">
                    <?php foreach ($items as $item): ?>
                        <div class="order-item">
                            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="item-image">
                            <div class="item-details">
                                <h3 class="item-name"><?php echo htmlspecialchars($item['name']); ?></h3>
                                <div class="item-meta">
                                    <span class="item-price"><?php echo number_format($item['price'], 0, ',', ' '); ?> FCFA</span>
                                    <span>× <?php echo $item['quantity']; ?></span>
                                </div>
                            </div>
                            <div class="item-total">
                                <?php echo number_format($item['price'] * $item['quantity'], 0, ',', ' '); ?> FCFA
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="shipping-info">
                <h2 class="section-title">Informations de livraison</h2>
                
                <div class="info-grid">
                    <div class="info-block">
                        <h4>Adresse de livraison</h4>
                        <div class="info-content">
                            <?php if (!empty($order['shipping_address'])): ?>
                                <?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?>
                            <?php else: ?>
                                <p>Information non disponible</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="info-block">
                        <h4>Méthode de paiement</h4>
                        <div class="info-content">
                            <?php if (!empty($order['payment_method'])): ?>
                                <?php echo htmlspecialchars($order['payment_method']); ?>
                            <?php else: ?>
                                <p>Non spécifiée</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="order-summary">
            <h2 class="section-title">Récapitulatif</h2>
            
            <div class="summary-row">
                <span class="summary-label">Sous-total</span>
                <span class="summary-value"><?php echo number_format($order['total_price'], 0, ',', ' '); ?> FCFA</span>
            </div>
            
            <div class="summary-row">
                <span class="summary-label">Livraison</span>
                <span class="summary-value">Gratuite</span>
            </div>
            
            <div class="summary-row">
                <span class="summary-label">Réduction</span>
                <span class="summary-value">0 FCFA</span>
            </div>
            
            <div class="summary-row summary-total">
                <span>Total</span>
                <span><?php echo number_format($order['total_price'], 0, ',', ' '); ?> FCFA</span>
            </div>
            
            <button class="btn btn-primary" style="width: 100%; margin-top: 20px;">
                <i class="fas fa-print"></i> Imprimer la facture
            </button>
            
            <button class="btn btn-outline" style="width: 100%; margin-top: 10px;">
                <i class="fas fa-question-circle"></i> Aide & Support
            </button>
        </div>
    </div>
    
    <a href="profile.php" class="btn btn-outline btn-back">
        <i class="fas fa-arrow-left"></i> Retour à mon compte
    </a>
</main>

<!-- Footer -->
<footer>
    <div class="header-container">
        <p>&copy; <?php echo date('Y'); ?> ReNew-Sénégal. Tous droits réservés.</p>
    </div>
</footer>

</body>
</html>