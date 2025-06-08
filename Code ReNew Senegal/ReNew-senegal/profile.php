<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.php");
    exit;
}

require_once 'config.php';

// Récupérer les informations de l'utilisateur
$user_id = $_SESSION["id"];
$sql_user = "SELECT username, email, created_at FROM users WHERE id = ?";
if ($stmt = $mysqli->prepare($sql_user)) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($username, $email, $created_at);
    $stmt->fetch();
    $stmt->close();
}

// Récupérer les commandes de l'utilisateur avec plus de détails
$sql_orders = "SELECT o.id, o.total_price, o.created_at, 
               COUNT(oi.id) as items_count 
               FROM orders o
               LEFT JOIN order_items oi ON o.id = oi.order_id
               WHERE o.user_id = ? 
               GROUP BY o.id
               ORDER BY o.created_at DESC";
$orders = [];
if ($stmt = $mysqli->prepare($sql_orders)) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - ReNew-Sénégal</title>
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

        /* Profile Container */
        .profile-container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 0 20px;
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 30px;
            margin-bottom: 40px;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid white;
            box-shadow: var(--shadow-md);
        }

        .profile-info h1 {
            font-size: 2.2rem;
            color: var(--dark);
            margin-bottom: 10px;
        }

        .profile-meta {
            display: flex;
            gap: 20px;
            color: var(--text-light);
            margin-bottom: 15px;
        }

        .profile-meta-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .profile-stats {
            display: flex;
            gap: 30px;
            margin-top: 20px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            text-align: center;
            min-width: 150px;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 5px;
        }

        .stat-label {
            color: var(--text-light);
            font-size: 0.9rem;
        }

        /* Tabs */
        .profile-tabs {
            display: flex;
            border-bottom: 1px solid #eee;
            margin-bottom: 30px;
        }

        .tab-btn {
            padding: 15px 25px;
            background: none;
            border: none;
            cursor: pointer;
            font-weight: 600;
            color: var(--text-light);
            position: relative;
            transition: var(--transition);
        }

        .tab-btn.active {
            color: var(--primary);
        }

        .tab-btn.active::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 100%;
            height: 3px;
            background: var(--primary);
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* Orders Table */
        .orders-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }

        .orders-table thead {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
        }

        .orders-table th {
            padding: 15px;
            text-align: left;
            font-weight: 500;
        }

        .orders-table td {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }

        .orders-table tr:last-child td {
            border-bottom: none;
        }

        .orders-table tr:hover {
            background-color: #f9f9f9;
        }

        .order-id {
            font-weight: 600;
            color: var(--primary);
        }

        .order-status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
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

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 15px;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
            transition: var(--transition);
            border: none;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 0.8rem;
        }

        .btn-outline {
            background: white;
            color: var(--primary);
            border: 1px solid var(--primary);
        }

        .btn-outline:hover {
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
            .profile-header {
                flex-direction: column;
                text-align: center;
            }
            
            .profile-stats {
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .orders-table {
                display: block;
                overflow-x: auto;
            }
            
            .profile-tabs {
                overflow-x: auto;
                white-space: nowrap;
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

<!-- Profile Content -->
<main class="profile-container">
    <div class="profile-header">
        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($username); ?>&background=4361ee&color=fff&size=120" alt="Avatar" class="profile-avatar">
        
        <div class="profile-info">
            <h1><?php echo htmlspecialchars($username); ?></h1>
            
            <div class="profile-meta">
                <div class="profile-meta-item">
                    <i class="fas fa-envelope"></i>
                    <span><?php echo htmlspecialchars($email); ?></span>
                </div>
                <div class="profile-meta-item">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Membre depuis <?php echo date('d/m/Y', strtotime($created_at)); ?></span>
                </div>
            </div>
            
            <div class="profile-stats">
                <div class="stat-card">
                    <div class="stat-value"><?php echo count($orders); ?></div>
                    <div class="stat-label">Commandes</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">
                        <?php 
                        $total_spent = array_reduce($orders, function($carry, $order) {
                            return $carry + $order['total_price'];
                        }, 0);
                        echo number_format($total_spent, 0, ',', ' '); 
                        ?> FCFA
                    </div>
                    <div class="stat-label">Total dépensé</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="profile-tabs">
        <button class="tab-btn active" data-tab="orders">Mes Commandes</button>
        <button class="tab-btn" data-tab="settings">Paramètres</button>
        <button class="tab-btn" data-tab="addresses">Adresses</button>
    </div>
    
    <div class="tab-content active" id="orders">
        <h2>Historique des Commandes</h2>
        
        <?php if (count($orders) > 0): ?>
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Commande</th>
                        <th>Date</th>
                        <th>Articles</th>
                        <th>Total</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td class="order-id">#<?php echo $order['id']; ?></td>
                            <td><?php echo date('d/m/Y', strtotime($order['created_at'])); ?></td>
                            <td><?php echo $order['items_count']; ?> article(s)</td>
                            <td><?php echo number_format($order['total_price'], 0, ',', ' '); ?> FCFA</td>
                            <td>
                                <?php 
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
                            </td>
                            <td>
                                <a href="order_details.php?id=<?php echo $order['id']; ?>" class="btn btn-outline btn-sm">
                                    <i class="fas fa-eye"></i> Détails
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div style="background: white; padding: 30px; border-radius: 12px; text-align: center;">
                <i class="fas fa-box-open" style="font-size: 3rem; color: #ddd; margin-bottom: 20px;"></i>
                <h3 style="margin-bottom: 10px;">Aucune commande passée</h3>
                <p style="color: var(--text-light); margin-bottom: 20px;">Vous n'avez pas encore passé de commande sur ReNew-Sénégal</p>
                <a href="all-produits.php" class="btn" style="background: var(--primary); color: white;">
                    <i class="fas fa-shopping-bag"></i> Découvrir nos produits
                </a>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="tab-content" id="settings">
        <h2>Paramètres du Compte</h2>
        <div style="background: white; padding: 30px; border-radius: 12px;">
            <form>
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Nom d'utilisateur</label>
                    <input type="text" value="<?php echo htmlspecialchars($username); ?>" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Adresse Email</label>
                    <input type="email" value="<?php echo htmlspecialchars($email); ?>" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Mot de passe</label>
                    <input type="password" placeholder="••••••••" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                </div>
                
                <button type="submit" style="background: var(--primary); color: white; padding: 12px 25px; border: none; border-radius: 8px; cursor: pointer;">
                    <i class="fas fa-save"></i> Enregistrer les modifications
                </button>
            </form>
        </div>
    </div>
    
    <div class="tab-content" id="addresses">
        <h2>Mes Adresses</h2>
        <div style="background: white; padding: 30px; border-radius: 12px; text-align: center;">
            <i class="fas fa-map-marker-alt" style="font-size: 3rem; color: #ddd; margin-bottom: 20px;"></i>
            <h3 style="margin-bottom: 10px;">Aucune adresse enregistrée</h3>
            <p style="color: var(--text-light); margin-bottom: 20px;">Ajoutez une adresse pour faciliter vos futurs achats</p>
            <button style="background: var(--primary); color: white; padding: 12px 25px; border: none; border-radius: 8px; cursor: pointer;">
                <i class="fas fa-plus"></i> Ajouter une adresse
            </button>
        </div>
    </div>
</main>

<!-- Footer -->
<footer>
    <div class="header-container">
        <p>&copy; <?php echo date('Y'); ?> ReNew-Sénégal. Tous droits réservés.</p>
    </div>
</footer>

<script>
    // Gestion des onglets
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            // Désactiver tous les onglets
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            
            // Activer l'onglet cliqué
            this.classList.add('active');
            const tabId = this.getAttribute('data-tab');
            document.getElementById(tabId).classList.add('active');
        });
    });
</script>

</body>
</html>