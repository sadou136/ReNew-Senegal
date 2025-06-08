<?php 
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.php");
    exit;
}

require_once 'config.php';

$sql_populaires = "SELECT id, nom, description, prix, image FROM produits ORDER BY RAND() LIMIT 5";
$result_populaires = $mysqli->query($sql_populaires);

$sql_vedettes = "SELECT id, nom, description, prix, image FROM produits WHERE vedette = 0 ORDER BY RAND() LIMIT 5";
$result_vedettes = $mysqli->query($sql_vedettes);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReNew-Sénégal - Smartphones Reconditionnés</title>
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
            background-color: var(--light);
            color: var(--text);
            line-height: 1.6;
        }

        /* Layout */
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

        /* Hero Section */
        .hero {
            background: linear-gradient(rgba(27, 38, 59, 0.8), rgba(27, 38, 59, 0.8)), url('https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            text-align: center;
            position: relative;
        }

        .hero-content {
            max-width: 800px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .hero h1 {
            font-size: 3rem;
            margin-bottom: 20px;
            font-weight: 700;
            line-height: 1.2;
        }

        .hero p {
            font-size: 1.2rem;
            margin-bottom: 30px;
            opacity: 0.9;
        }

        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: var(--accent);
            color: white;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(247, 37, 133, 0.4);
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(247, 37, 133, 0.6);
        }

        .btn-outline {
            background: transparent;
            border: 2px solid white;
            margin-left: 15px;
        }

        .btn-outline:hover {
            background: white;
            color: var(--dark);
        }

        /* Welcome Message */
        .welcome-banner {
            background: linear-gradient(to right, var(--primary), var(--secondary));
            color: white;
            padding: 15px 0;
            text-align: center;
            margin-bottom: 50px;
            box-shadow: var(--shadow-md);
        }

        .welcome-banner p {
            font-size: 1.1rem;
        }

        .username {
            font-weight: 600;
            text-transform: capitalize;
        }

        /* Products Section */
        .section-title {
            text-align: center;
            margin-bottom: 50px;
            position: relative;
        }

        .section-title h2 {
            font-size: 2.2rem;
            color: var(--dark);
            margin-bottom: 15px;
            font-weight: 700;
        }

        .section-title::after {
            content: '';
            display: block;
            width: 80px;
            height: 4px;
            background: linear-gradient(to right, var(--primary), var(--accent));
            margin: 0 auto;
            border-radius: 2px;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 30px;
            margin-bottom: 80px;
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

        .product-description {
            color: var(--text-light);
            font-size: 0.9rem;
            margin-bottom: 15px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-price {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 15px;
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
            background: var(--success);
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
            background: #3aa8d8;
        }

        /* Contact Section */
        .contact-section {
            background: white;
            padding: 80px 0;
            margin-top: 50px;
        }

        .contact-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .contact-form {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: var(--shadow-md);
        }

        .form-group {
            margin-bottom: 25px;
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
            border-radius: 6px;
            font-size: 1rem;
            transition: var(--transition);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
        }

        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }

        /* Footer */
        footer {
            background: linear-gradient(135deg, var(--dark), var(--primary));
            color: white;
            padding: 50px 0 20px;
            margin-top: 80px;
        }

        .footer-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
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

        /* Toast */
        .toast {
            visibility: hidden;
            min-width: 300px;
            background: var(--dark);
            color: white;
            text-align: center;
            border-radius: 8px;
            padding: 16px;
            position: fixed;
            z-index: 1000;
            left: 50%;
            bottom: 30px;
            transform: translateX(-50%);
            box-shadow: var(--shadow-lg);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .toast.show {
            visibility: visible;
            opacity: 1;
            animation: slideIn 0.5s, fadeOut 0.5s 2.5s forwards;
        }

        @keyframes slideIn {
            from {bottom: 0; opacity: 0;}
            to {bottom: 30px; opacity: 1;}
        }

        @keyframes fadeOut {
            from {bottom: 30px; opacity: 1;}
            to {bottom: 0; opacity: 0;}
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .hero h1 {
                font-size: 2.5rem;
            }
        }

        @media (max-width: 768px) {
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

            .hero {
                padding: 80px 0;
            }

            .hero h1 {
                font-size: 2rem;
            }

            .hero p {
                font-size: 1rem;
            }

            .btn {
                padding: 10px 20px;
            }

            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            }
        }

        @media (max-width: 576px) {
            .hero h1 {
                font-size: 1.8rem;
            }

            .section-title h2 {
                font-size: 1.8rem;
            }

            .products-grid {
                grid-template-columns: 1fr;
            }

            .contact-form {
                padding: 25px;
            }

            .footer-container {
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
            <a href="all-produits.php" class="nav-link"><i class="fas fa-shopping-bag"></i> Produits</a>
            <a href="abouts.php" class="nav-link"><i class="fas fa-info-circle"></i> À propos</a>
            <a href="#contact" class="nav-link"><i class="fas fa-envelope"></i> Contact</a>
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

<!-- Hero Section -->
<section class="hero">
    <div class="hero-content">
        <h1>Smartphones Reconditionnés de Qualité</h1>
        <p>Économisez jusqu'à 70% sur des téléphones haut de gamme testés et garantis. Une solution écologique et économique.</p>
        <div class="hero-buttons">
            <a href="all-produits.php" class="btn">Découvrir nos produits</a>
            <a href="#contact" class="btn btn-outline">Nous contacter</a>
        </div>
    </div>
</section>

<!-- Welcome Message -->
<div class="welcome-banner">
    <div class="container">
        <p>Bienvenue <span class="username"><?php echo htmlspecialchars($_SESSION["username"]); ?></span>, profitez de nos offres exceptionnelles !</p>
    </div>
</div>

<!-- Main Content -->
<main class="container">
    <!-- Produits Populaires -->
    <section>
        <div class="section-title">
            <h2>Nos Produits Populaires</h2>
            <p>Découvrez les smartphones les plus appréciés par nos clients</p>
        </div>
        
        <div class="products-grid">
            <?php
            if ($result_populaires && $result_populaires->num_rows > 0) {
                while ($row = $result_populaires->fetch_assoc()) {
                    echo '<div class="product-card">';
                    echo '<div class="product-image">';
                    echo '<img src="' . htmlspecialchars($row["image"]) . '" alt="' . htmlspecialchars($row["nom"]) . '">';
                    echo '</div>';
                    echo '<div class="product-info">';
                    echo '<h3 class="product-name">' . htmlspecialchars($row["nom"]) . '</h3>';
                    echo '<p class="product-description">' . htmlspecialchars(substr($row["description"], 0, 100)) . '...</p>';
                    echo '<div class="product-price">' . number_format($row["prix"], 0, ',', ' ') . ' FCFA</div>';
                    echo '<div class="product-actions">';
                    echo '<a href="product_detail.php?id=' . $row["id"] . '" class="btn-details">Voir détails</a>';
                    echo '<button class="btn-cart"><i class="fas fa-shopping-cart"></i></button>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p style="grid-column:1/-1;text-align:center;">Aucun produit populaire disponible pour le moment.</p>';
            }
            ?>
        </div>
    </section>

    <!-- Produits Vedettes -->
    <section>
        <div class="section-title">
            <h2>Nos Produits Vedettes</h2>
            <p>Nos sélections coup de cœur à ne pas manquer</p>
        </div>
        
        <div class="products-grid">
            <?php
            if ($result_vedettes && $result_vedettes->num_rows > 0) {
                while ($row = $result_vedettes->fetch_assoc()) {
                    echo '<div class="product-card">';
                    echo '<div class="product-badge">Vedette</div>';
                    echo '<div class="product-image">';
                    echo '<img src="' . htmlspecialchars($row["image"]) . '" alt="' . htmlspecialchars($row["nom"]) . '">';
                    echo '</div>';
                    echo '<div class="product-info">';
                    echo '<h3 class="product-name">' . htmlspecialchars($row["nom"]) . '</h3>';
                    echo '<p class="product-description">' . htmlspecialchars(substr($row["description"], 0, 100)) . '...</p>';
                    echo '<div class="product-price">' . number_format($row["prix"], 0, ',', ' ') . ' FCFA</div>';
                    echo '<div class="product-actions">';
                    echo '<a href="product_detail.php?id=' . $row["id"] . '" class="btn-details">Voir détails</a>';
                    echo '<button class="btn-cart"><i class="fas fa-shopping-cart"></i></button>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p style="grid-column:1/-1;text-align:center;">Aucun produit vedette disponible pour le moment.</p>';
            }
            ?>
        </div>
    </section>
</main>

<!-- Contact Section -->
<section class="contact-section" id="contact">
    <div class="contact-container">
        <div class="section-title">
            <h2 style="color:white;">Contactez-Nous</h2>
            <p style="color:rgba(255,255,255,0.8);">Une question ? Nous sommes à votre écoute</p>
        </div>
        
        <div class="contact-form">
            <form action="contact.php" method="post">
                <div class="form-group">
                    <label for="name">Votre nom</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Votre email</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="message">Votre message</label>
                    <textarea id="message" name="message" class="form-control" required></textarea>
                </div>
                
                <button type="submit" class="btn" style="width:100%;">Envoyer le message</button>
            </form>
        </div>
    </div>
</section>

<!-- Footer -->
<footer>
    <div class="container">
        <div class="footer-container">
            <div class="footer-about">
                <div class="footer-logo">ReNew-Sénégal</div>
                <p>Votre destination pour des smartphones reconditionnés de qualité à des prix imbattables.</p>
                <div class="social-links">
                    <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            
            <div>
                <h3 class="footer-title">Liens rapides</h3>
                <ul class="footer-links">
                    <li class="footer-link"><a href="welcome.php">Accueil</a></li>
                    <li class="footer-link"><a href="all-produits.php">Produits</a></li>
                    <li class="footer-link"><a href="abouts.php">À propos</a></li>
                    <li class="footer-link"><a href="#contact">Contact</a></li>
                </ul>
            </div>
            
            <div>
                <h3 class="footer-title">Informations</h3>
                <ul class="footer-links">
                    <li class="footer-link"><a href="#">Politique de confidentialité</a></li>
                    <li class="footer-link"><a href="#">Conditions générales</a></li>
                    <li class="footer-link"><a href="#">FAQ</a></li>
                    <li class="footer-link"><a href="#">Livraison & Retours</a></li>
                </ul>
            </div>
            
            <div>
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

<!-- Toast Notification -->
<div id="toast" class="toast">Votre message a été envoyé avec succès !</div>

<script>
    // Toast notification
    window.onload = function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('contact_success') === '1') {
            const toast = document.getElementById('toast');
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 3000);
        }
    };
</script>

</body>
</html>