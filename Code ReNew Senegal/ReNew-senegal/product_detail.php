<?php 
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.php");
    exit;
}

require_once 'config.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $product_id = $_GET['id'];
    $sql = "SELECT * FROM produits WHERE id = ?";
    
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        $stmt->close();
        
        if (!$product) {
            header("location: all-produits.php");
            exit;
        }
        
        // Récupérer les produits similaires (même marque)
        $marque = $product['marque'];
        $sql_similaires = "SELECT id, nom, image, prix FROM produits WHERE marque = ? AND id != ? LIMIT 4";
        $stmt_similaires = $mysqli->prepare($sql_similaires);
        $stmt_similaires->bind_param("si", $marque, $product_id);
        $stmt_similaires->execute();
        $result_similaires = $stmt_similaires->get_result();
        $produits_similaires = $result_similaires->fetch_all(MYSQLI_ASSOC);
        $stmt_similaires->close();
    } else {
        die("Erreur lors de la préparation de la requête.");
    }
} else {
    header("location: all-produits.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['nom']); ?> - ReNew-Sénégal</title>
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

        .container {
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
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

        /* Product Detail */
        .product-detail-container {
            padding: 50px 0;
        }

        .product-detail {
            display: flex;
            flex-wrap: wrap;
            gap: 40px;
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--shadow-md);
        }

        .product-gallery {
            flex: 1;
            min-width: 300px;
            padding: 30px;
            background: #f8f9fa;
        }

        .main-image {
            width: 100%;
            height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: var(--shadow-sm);
        }

        .main-image img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .thumbnail-container {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .thumbnail {
            width: 70px;
            height: 70px;
            border-radius: 8px;
            overflow: hidden;
            cursor: pointer;
            border: 2px solid transparent;
            transition: var(--transition);
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 5px;
        }

        .thumbnail:hover, .thumbnail.active {
            border-color: var(--primary);
        }

        .thumbnail img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .product-info {
            flex: 1;
            min-width: 300px;
            padding: 40px;
            position: relative;
        }

        .product-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: var(--accent);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .product-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 10px;
        }

        .product-meta {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .product-rating {
            color: #ffc107;
            font-size: 0.9rem;
        }

        .product-review-count {
            color: var(--text-light);
            font-size: 0.9rem;
        }

        .product-price {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--accent);
            margin: 20px 0;
        }

        .product-old-price {
            text-decoration: line-through;
            color: var(--text-light);
            font-size: 1.2rem;
            margin-left: 10px;
        }

        .product-description {
            color: var(--text);
            margin-bottom: 25px;
            line-height: 1.7;
        }

        .product-specs {
            margin: 30px 0;
        }

        .spec-item {
            display: flex;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .spec-label {
            width: 150px;
            font-weight: 600;
            color: var(--dark);
        }

        .spec-value {
            flex: 1;
            color: var(--text);
        }

        .product-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 30px;
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
            border: 1px solid var(--primary);
        }

        .btn-secondary:hover {
            background: #f0f4ff;
        }

        .btn-icon {
            margin-right: 8px;
        }

        .stock-status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .in-stock {
            background: #e3f9e5;
            color: #28a745;
        }

        .low-stock {
            background: #fff3cd;
            color: #856404;
        }

        /* Related Products */
        .related-products {
            margin: 80px 0 40px;
        }

        .section-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 30px;
            position: relative;
            padding-bottom: 15px;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background: linear-gradient(to right, var(--primary), var(--accent));
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 25px;
        }
        /* Styles pour les produits similaires */
        .similar-product {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }

        .similar-product:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }

        .similar-product-image {
            height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: #f8f9fa;
        }

        .similar-product-image img {
            max-height: 100%;
            max-width: 100%;
            object-fit: contain;
        }

        .similar-product-info {
            padding: 20px;
        }

        .similar-product-name {
            font-weight: 600;
            margin-bottom: 10px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .similar-product-price {
            color: var(--accent);
            font-weight: 700;
            margin-bottom: 15px;
        }

        .btn-view-more {
            display: inline-block;
            width: 100%;
            padding: 8px;
            background: var(--dark);
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 6px;
            font-size: 0.9rem;
            transition: var(--transition);
        }

        .btn-view-more:hover {
            background: var(--primary);
        }


        /* Footer */
        footer {
            background: linear-gradient(135deg, var(--dark), var(--primary));
            color: white;
            padding: 50px 0 20px;
            margin-top: 80px;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .product-detail {
                flex-direction: column;
            }
            
            .product-gallery, .product-info {
                flex: none;
                width: 100%;
            }
        }

        @media (max-width: 768px) {
            .product-actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
            
            .product-title {
                font-size: 1.6rem;
            }
            
            .product-price {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>

<!-- Header -->
<header>
    <div class="container header-container">
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
            <a href="panier.php" class="nav-link"><i class="fas fa-shopping-cart"></i></a>
            <a href="logout.php" class="nav-link"><i class="fas fa-sign-out-alt"></i></a>
        </div>
    </div>
</header>

<!-- Product Detail -->
<div class="product-detail-container">
    <div class="container">
        <div class="product-detail">
            <div class="product-gallery">
                <div class="main-image">
                    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['nom']); ?>">
                </div>
                <div class="thumbnail-container">
                    <!-- Vous pouvez ajouter des miniatures ici si vous avez plusieurs images -->
                    <div class="thumbnail active">
                        <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="Miniature 1">
                    </div>
                </div>
            </div>
            
            <div class="product-info">
                <?php if ($product['vedette'] == 1): ?>
                    <div class="product-badge">Vedette</div>
                <?php endif; ?>
                
                <h1 class="product-title"><?php echo htmlspecialchars($product['nom']); ?></h1>
                
                <div class="product-meta">
                    <div class="product-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <span class="product-review-count">(12 avis)</span>
                </div>
                
                <?php if ($product['stock'] > 5): ?>
                    <span class="stock-status in-stock"><i class="fas fa-check-circle"></i> En stock</span>
                <?php elseif ($product['stock'] > 0): ?>
                    <span class="stock-status low-stock"><i class="fas fa-exclamation-circle"></i> Stock limité</span>
                <?php else: ?>
                    <span class="stock-status out-of-stock"><i class="fas fa-times-circle"></i> Rupture de stock</span>
                <?php endif; ?>
                
                <div class="product-price">
                    <?php echo number_format($product['prix'], 0, ',', ' '); ?> FCFA
                    <?php if (!empty($product['ancien_prix'])): ?>
                        <span class="product-old-price"><?php echo number_format($product['ancien_prix'], 0, ',', ' '); ?> FCFA</span>
                    <?php endif; ?>
                </div>
                
                <p class="product-description">
                    <?php echo htmlspecialchars($product['description']); ?>
                </p>
                
                <div class="product-specs">
                    <div class="spec-item">
                        <div class="spec-label">Marque</div>
                        <div class="spec-value"><?php echo htmlspecialchars($product['marque']); ?></div>
                    </div>
                    <div class="spec-item">
                        <div class="spec-label">Modèle</div>
                        <div class="spec-value"><?php echo htmlspecialchars($product['modele'] ?? 'Non spécifié'); ?></div>
                    </div>
                    <div class="spec-item">
                        <div class="spec-label">Stockage</div>
                        <div class="spec-value"><?php echo htmlspecialchars($product['stockage']); ?></div>
                    </div>
                    <div class="spec-item">
                        <div class="spec-label">État</div>
                        <div class="spec-value"><?php echo htmlspecialchars($product['etat']); ?></div>
                    </div>
                    <div class="spec-item">
                        <div class="spec-label">Garantie</div>
                        <div class="spec-value"><?php echo htmlspecialchars($product['garantie']); ?></div>
                    </div>
                    <div class="spec-item">
                        <div class="spec-label">Disponibilité</div>
                        <div class="spec-value"><?php echo htmlspecialchars($product['stock']); ?> unité(s)</div>
                    </div>
                </div>
                
                <div class="product-actions">
                    <a href="ajouter_au_panier.php?id=<?php echo $product['id']; ?>" class="btn btn-primary">
                        <i class="fas fa-shopping-cart btn-icon"></i> Ajouter au panier
                    </a>
                    <button class="btn btn-secondary">
                        <i class="far fa-heart btn-icon"></i> Favoris
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Produits similaires -->
        <?php if (!empty($produits_similaires)): ?>
        <div class="related-products">
            <h2 class="section-title">Produits similaires</h2>
            <div class="products-grid">
                <?php foreach ($produits_similaires as $produit): ?>
                <div class="similar-product">
                    <div class="similar-product-image">
                        <img src="<?php echo htmlspecialchars($produit['image']); ?>" alt="<?php echo htmlspecialchars($produit['nom']); ?>">
                    </div>
                    <div class="similar-product-info">
                        <h3 class="similar-product-name"><?php echo htmlspecialchars($produit['nom']); ?></h3>
                        <div class="similar-product-price"><?php echo number_format($produit['prix'], 0, ',', ' '); ?> FCFA</div>
                        <a href="product_detail.php?id=<?php echo $produit['id']; ?>" class="btn-view-more">
                            Voir plus <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
    </div>
</div>

<!-- Footer -->
<footer>
    <div class="container">
        <p>&copy; <?php echo date('Y'); ?> ReNew-Sénégal. Tous droits réservés.</p>
    </div>
</footer>

<script>
    // Script pour changer l'image principale lorsqu'on clique sur une miniature
    document.querySelectorAll('.thumbnail').forEach(thumb => {
        thumb.addEventListener('click', function() {
            const mainImg = document.querySelector('.main-image img');
            const thumbImg = this.querySelector('img').src;
            mainImg.src = thumbImg;
            
            document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
        });
    });
</script>

</body>
</html>