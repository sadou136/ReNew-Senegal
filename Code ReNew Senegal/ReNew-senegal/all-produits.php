<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smartphones Reconditionnés - ReNew-Sénégal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --accent: #f72585;
            --dark: #1b263b;
            --light: #f8f9fa;
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
            background-color: var(--light);
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

        .search-bar {
            position: relative;
        }

        .search-bar input {
            padding: 10px 15px 10px 40px;
            border-radius: 30px;
            border: none;
            width: 200px;
            background: rgba(255,255,255,0.2);
            color: white;
            transition: var(--transition);
        }

        .search-bar input::placeholder {
            color: rgba(255,255,255,0.7);
        }

        .search-bar input:focus {
            outline: none;
            width: 250px;
            background: rgba(255,255,255,0.3);
        }

        .search-bar i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: white;
        }

        .cart-icon, .user-icon {
            color: white;
            font-size: 1.2rem;
            position: relative;
            transition: var(--transition);
        }

        .cart-icon:hover, .user-icon:hover {
            color: var(--accent);
            transform: translateY(-2px);
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
        .main-title {
            text-align: center;
            margin: 40px 0 30px;
            position: relative;
        }

        .main-title h1 {
            font-size: 2.5rem;
            color: var(--dark);
            margin-bottom: 15px;
            font-weight: 700;
        }

        .main-title::after {
            content: '';
            display: block;
            width: 80px;
            height: 4px;
            background: linear-gradient(to right, var(--primary), var(--accent));
            margin: 0 auto;
            border-radius: 2px;
        }

        /* Category Filter */
        .category-filter {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 10px;
            margin: 30px 0;
        }

        .filter-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 30px;
            background: white;
            color: var(--dark);
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: var(--shadow-sm);
            border: 1px solid #eee;
        }

        .filter-btn:hover, .filter-btn.active {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        /* Products Grid */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 30px;
            margin: 40px 0;
        }

        .product-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            position: relative;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-lg);
        }

        .product-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: var(--accent);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            z-index: 1;
        }

        .product-image {
            height: 220px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
            position: relative;
            overflow: hidden;
        }

        .product-image img {
            max-height: 80%;
            max-width: 80%;
            object-fit: contain;
            transition: var(--transition);
        }

        .product-card:hover .product-image img {
            transform: scale(1.1);
        }

        .product-info {
            padding: 20px;
        }

        .product-name {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--dark);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .product-price {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 10px;
        }

        .product-meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 0.9rem;
            color: var(--text-light);
        }

        .product-etat {
            background: #f0f0f0;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
        }

        .product-actions {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        .btn-details {
            flex: 1;
            background: var(--dark);
            color: white;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
            font-size: 0.9rem;
            transition: var(--transition);
        }

        .btn-details:hover {
            background: var(--secondary);
        }

        .btn-cart {
            width: 40px;
            height: 40px;
            background: var(--accent);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-cart:hover {
            background: #e5177b;
        }

        /* No Products Message */
        .no-products {
            grid-column: 1/-1;
            text-align: center;
            padding: 50px;
            color: var(--text-light);
            font-size: 1.1rem;
        }

        /* Footer */
        footer {
            background: linear-gradient(135deg, var(--dark), var(--primary));
            color: white;
            padding: 30px 0 20px;
            margin-top: 80px;
        }

        .footer-container {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 40px;
            margin-bottom: 30px;
        }

        .footer-col {
            flex: 1;
            min-width: 200px;
        }

        .footer-logo {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 20px;
            background: linear-gradient(to right, white, #f3f4f6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .footer-about p {
            opacity: 0.8;
            margin-bottom: 20px;
        }

        .social-links {
            display: flex;
            gap: 15px;
        }

        .social-link {
            color: white;
            font-size: 1.2rem;
            transition: var(--transition);
        }

        .social-link:hover {
            color: var(--accent);
            transform: translateY(-3px);
        }

        .footer-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
        }

        .footer-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 40px;
            height: 2px;
            background: var(--accent);
        }

        .footer-links {
            list-style: none;
        }

        .footer-link {
            margin-bottom: 10px;
        }

        .footer-link a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: var(--transition);
        }

        .footer-link a:hover {
            color: white;
            padding-left: 5px;
        }

        .footer-bottom {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
            font-size: 0.9rem;
            opacity: 0.8;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .header-container {
                flex-direction: column;
                gap: 15px;
            }

            nav {
                order: 2;
                width: 100%;
                justify-content: center;
                flex-wrap: wrap;
            }

            .search-cart {
                order: 3;
                width: 100%;
                justify-content: center;
            }

            .main-title h1 {
                font-size: 2rem;
            }
        }

        @media (max-width: 768px) {
            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            }

            .footer-container {
                flex-direction: column;
                gap: 30px;
            }
        }

        @media (max-width: 576px) {
            .main-title h1 {
                font-size: 1.8rem;
            }

            .products-grid {
                grid-template-columns: 1fr;
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
            <a href="all-produits.php" class="nav-link active"><i class="fas fa-shopping-bag"></i> Produits</a>
            <a href="abouts.php" class="nav-link"><i class="fas fa-info-circle"></i> À propos</a>
        </nav>
        
        <div class="search-cart">
            <div class="search-bar">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Rechercher...">
            </div>
            <a href="profile.php" class="user-icon"><i class="fas fa-user-circle"></i></a>
            <a href="panier.php" class="cart-icon">
                <i class="fas fa-shopping-cart"></i>
                <span class="cart-count">0</span>
            </a>
            <a href="logout.php" class="nav-link"><i class="fas fa-sign-out-alt"></i></a>
        </div>
    </div>
</header>

<!-- Main Content -->
<main class="container">
    <div class="main-title">
        <h1>Nos Smartphones Reconditionnés</h1>
        <p>Découvrez notre sélection de téléphones haut de gamme à prix réduits</p>
    </div>

    <!-- Category Filter -->
    <div class="category-filter">
        <button class="filter-btn active" onclick="filterProducts('all')">Tous les produits</button>
        <button class="filter-btn" onclick="filterProducts('apple')"><i class="fab fa-apple"></i> Apple</button>
        <button class="filter-btn" onclick="filterProducts('samsung')"><i class="fas fa-mobile-alt"></i> Samsung</button>
        <button class="filter-btn" onclick="filterProducts('xiaomi')"><i class="fas fa-mobile-alt"></i> Xiaomi</button>
        <button class="filter-btn" onclick="filterProducts('itel')"><i class="fas fa-mobile-alt"></i> Itel</button>
    </div>

    <!-- Products Grid -->
    <div id="product-container" class="products-grid">
        <?php
        include 'config.php';
        $sql = "SELECT * FROM produits ORDER BY RAND()";
        $result = $mysqli->query($sql);
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="product-card ' . $row["categories"] . '">';
                
                // Badge pour les produits vedettes
                if ($row["vedette"] == 1) {
                    echo '<div class="product-badge">Vedette</div>';
                }
                
                echo '<div class="product-image">';
                echo '<img src="' . htmlspecialchars($row["image"]) . '" alt="' . htmlspecialchars($row["nom"]) . '">';
                echo '</div>';
                
                echo '<div class="product-info">';
                echo '<h3 class="product-name">' . htmlspecialchars($row["nom"]) . '</h3>';
                echo '<div class="product-price">' . number_format($row["prix"], 0, ',', ' ') . ' FCFA</div>';
                
                echo '<div class="product-meta">';
                echo '<span class="product-etat">' . htmlspecialchars($row["etat"]) . '</span>';
                echo '<span><i class="fas fa-shield-alt"></i> ' . htmlspecialchars($row["garantie"]) . '</span>';
                echo '</div>';
                
                echo '<div class="product-actions">';
                echo '<a href="product_detail.php?id=' . $row["id"] . '" class="btn-details">Voir détails</a>';
                echo '<button class="btn-cart"><i class="fas fa-shopping-cart"></i></button>';
                echo '</div>';
                
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<div class="no-products">';
            echo '<p>Aucun smartphone disponible pour le moment.</p>';
            echo '<a href="welcome.php" class="btn-details" style="display:inline-block;margin-top:15px;">Retour à l\'accueil</a>';
            echo '</div>';
        }
        
        $mysqli->close();
        ?>
    </div>
</main>

<!-- Footer -->
<footer>
    <div class="container">
        <div class="footer-container">
            <div class="footer-col">
                <div class="footer-logo">ReNew-Sénégal</div>
                <p class="footer-about">Votre destination pour des smartphones reconditionnés de qualité à des prix imbattables.</p>
                <div class="social-links">
                    <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            
            <div class="footer-col">
                <h3 class="footer-title">Navigation</h3>
                <ul class="footer-links">
                    <li class="footer-link"><a href="welcome.php">Accueil</a></li>
                    <li class="footer-link"><a href="all-produits.php">Produits</a></li>
                    <li class="footer-link"><a href="abouts.php">À propos</a></li>
                    <li class="footer-link"><a href="#contact">Contact</a></li>
                </ul>
            </div>
            
            <div class="footer-col">
                <h3 class="footer-title">Informations</h3>
                <ul class="footer-links">
                    <li class="footer-link"><a href="#">Politique de retour</a></li>
                    <li class="footer-link"><a href="#">Conditions de vente</a></li>
                    <li class="footer-link"><a href="#">Livraison</a></li>
                    <li class="footer-link"><a href="#">FAQ</a></li>
                </ul>
            </div>
            
            <div class="footer-col">
                <h3 class="footer-title">Contact</h3>
                <ul class="footer-links">
                    <li class="footer-link"><i class="fas fa-map-marker-alt"></i> Dakar, Sénégal</li>
                    <li class="footer-link"><i class="fas fa-phone"></i> +221 77 123 45 67</li>
                    <li class="footer-link"><i class="fas fa-envelope"></i> contact@renew-senegal.com</li>
                </ul>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> ReNew-Sénégal. Tous droits réservés.</p>
        </div>
    </div>
</footer>

<script>
    function filterProducts(category) {
        // Update active button
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        event.target.classList.add('active');
        
        // Filter products
        let products = document.querySelectorAll('.product-card');
        let hasProducts = false;

        products.forEach(product => {
            if (category === 'all') {
                product.style.display = 'block';
                hasProducts = true;
            } else {
                if (product.classList.contains(category)) {
                    product.style.display = 'block';
                    hasProducts = true;
                } else {
                    product.style.display = 'none';
                }
            }
        });

        // Show message if no products in category
        if (!hasProducts) {
            const noProductsMsg = document.createElement('div');
            noProductsMsg.className = 'no-products';
            noProductsMsg.innerHTML = `<p>Aucun produit disponible dans cette catégorie.</p>`;
            
            const container = document.getElementById('product-container');
            container.appendChild(noProductsMsg);
        } else {
            const noProductsMsg = document.querySelector('.no-products');
            if (noProductsMsg) noProductsMsg.remove();
        }
    }
</script>

</body>
</html>