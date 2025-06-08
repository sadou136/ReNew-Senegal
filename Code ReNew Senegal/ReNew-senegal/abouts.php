<?php
session_start();
require_once 'config.php';

// Gestion de la newsletter
$newsletter_success = isset($_GET['newsletter_success']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>À Propos - ReNew-Sénégal</title>
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
            background-color: #f5f7fa;
            color: var(--text);
            line-height: 1.6;
        }

        /* Header - Identique à all-produits.php */
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

        .nav-link.active {
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

        .nav-link:hover::after, .nav-link.active::after {
            width: 100%;
        }

        .search-cart {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        /* Main Content */
        .about-hero {
            background: linear-gradient(rgba(27, 38, 59, 0.85), rgba(27, 38, 59, 0.85)), url('https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 20px;
            text-align: center;
        }

        .about-hero h1 {
            font-size: 3rem;
            margin-bottom: 20px;
        }

        .about-hero p {
            font-size: 1.2rem;
            max-width: 800px;
            margin: 0 auto;
        }

        .about-container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 0 20px;
        }

        .section-title {
            text-align: center;
            margin-bottom: 50px;
            position: relative;
        }

        .section-title h2 {
            font-size: 2.2rem;
            color: var(--dark);
            margin-bottom: 15px;
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

        .mission-vision {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 50px;
        }

        .mission-card, .vision-card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            text-align: center;
        }

        .mission-card:hover, .vision-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }

        .mission-card h3, .vision-card h3 {
            font-size: 1.5rem;
            margin-bottom: 20px;
            color: var(--primary);
        }

        .mission-card i, .vision-card i {
            font-size: 2.5rem;
            color: var(--accent);
            margin-bottom: 20px;
            display: inline-block;
        }

        /* Team Section */
        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-bottom: 50px;
        }

        .team-member {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            text-align: center;
        }

        .team-member:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-lg);
        }

        .member-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }

        .member-info {
            padding: 20px;
        }

        .member-info h3 {
            font-size: 1.3rem;
            margin-bottom: 5px;
            color: var(--dark);
        }

        .member-position {
            color: var(--accent);
            font-weight: 600;
            margin-bottom: 15px;
            display: block;
        }

        .member-social {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 15px;
        }

        .member-social a {
            color: var(--text-light);
            transition: var(--transition);
        }

        .member-social a:hover {
            color: var(--primary);
            transform: translateY(-3px);
        }

        /* Newsletter */
        .newsletter {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            padding: 50px 20px;
            border-radius: 12px;
            color: white;
            text-align: center;
            margin-top: 50px;
        }

        .newsletter h2 {
            font-size: 1.8rem;
            margin-bottom: 20px;
        }

        .newsletter p {
            max-width: 600px;
            margin: 0 auto 30px;
            opacity: 0.9;
        }

        .newsletter-form {
            display: flex;
            max-width: 500px;
            margin: 0 auto;
        }

        .newsletter-form input {
            flex: 1;
            padding: 15px;
            border: none;
            border-radius: 30px 0 0 30px;
            font-size: 1rem;
        }

        .newsletter-form button {
            background: var(--accent);
            color: white;
            border: none;
            padding: 0 30px;
            border-radius: 0 30px 30px 0;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
        }

        .newsletter-form button:hover {
            background: #e5177b;
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

        /* Footer */
        footer {
            background: linear-gradient(135deg, var(--dark), var(--primary));
            color: white;
            padding: 30px 0;
            margin-top: 80px;
            text-align: center;
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
            
            .about-hero h1 {
                font-size: 2.2rem;
            }
        }

        @media (max-width: 768px) {
            .newsletter-form {
                flex-direction: column;
            }
            
            .newsletter-form input,
            .newsletter-form button {
                border-radius: 30px;
                width: 100%;
            }
            
            .newsletter-form button {
                margin-top: 10px;
                padding: 15px;
            }
        }
    </style>
</head>
<body>

<!-- Header - Identique à all-produits.php -->
<header>
    <div class="header-container">
        <div class="brand">
            <img src="logo/logo-renew-senegal.jpg" alt="Logo" class="logo">
            <span class="brand-name">ReNew-Sénégal</span>
        </div>
        
        <nav>
            <a href="welcome.php" class="nav-link"><i class="fas fa-home"></i> Accueil</a>
            <a href="all-produits.php" class="nav-link"><i class="fas fa-shopping-bag"></i> Produits</a>
            <a href="abouts.php" class="nav-link active"><i class="fas fa-info-circle"></i> À propos</a>
        </nav>
        
        <div class="search-cart">
            <a href="profile.php" class="nav-link"><i class="fas fa-user-circle"></i></a>
            <a href="panier.php" class="nav-link"><i class="fas fa-shopping-cart"></i></a>
            <a href="logout.php" class="nav-link"><i class="fas fa-sign-out-alt"></i></a>
        </div>
    </div>
</header>

<!-- Hero Section -->
<section class="about-hero">
    <h1>Notre Histoire</h1>
    <p>ReNew-Sénégal est né de la passion pour la technologie et le développement durable. Nous croyons en un avenir où la technologie de qualité est accessible à tous.</p>
</section>

<!-- Main Content -->
<main class="about-container">
    <!-- Mission & Vision -->
    <section>
        <div class="mission-vision">
            <div class="mission-card">
                <i class="fas fa-bullseye"></i>
                <h3>Notre Mission</h3>
                <p>Offrir des smartphones reconditionnés de qualité supérieure à des prix accessibles, tout en réduisant l'impact environnemental des déchets électroniques.</p>
            </div>
            
            <div class="vision-card">
                <i class="fas fa-eye"></i>
                <h3>Notre Vision</h3>
                <p>Devenir le leader du marché des smartphones reconditionnés en Afrique de l'Ouest, en combinant innovation technologique et responsabilité écologique.</p>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section>
        <div class="section-title">
            <h2>Notre Équipe</h2>
            <p>Des professionnels passionnés à votre service</p>
        </div>
        
        <div class="team-grid">
            <!-- Membre 1 -->
            <div class="team-member">
                <img src="https://img.freepik.com/photos-gratuite/portrait-homme-elegant-portant-joli-chapeau_23-2148634028.jpg" alt="Amadou Tidiane Diop" class="member-image">
                <div class="member-info">
                    <h3>Amadou Tidiane Diop</h3>
                    <span class="member-position">Fondateur & CEO</span>
                    <p>Expert en technologie avec 10 ans d'expérience dans le reconditionnement de smartphones.</p>
                    <div class="member-social">
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="mailto:papa@renew-senegal.com"><i class="fas fa-envelope"></i></a>
                    </div>
                </div>
            </div>
            
            <!-- Membre 2 -->
            <div class="team-member">
                <img src="https://img.freepik.com/photos-gratuite/belle-femme-africaine-manteau-floral-exterieur_23-2148747916.jpg" alt="Aminata Ndiaye" class="member-image">
                <div class="member-info">
                    <h3>Aminata Ndiaye</h3>
                    <span class="member-position">Responsable Commercial</span>
                    <p>Spécialiste des relations clients et de la gestion des partenariats.</p>
                    <div class="member-social">
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="mailto:aminata@renew-senegal.com"><i class="fas fa-envelope"></i></a>
                    </div>
                </div>
            </div>
            
            <!-- Membre 3 -->
            <div class="team-member">
                <img src="https://img.freepik.com/photos-gratuite/homme-regardant-camera_23-2148638717.jpg" alt="Thierno Bah" class="member-image">
                <div class="member-info">
                    <h3>Thierno Sadou Bah</h3>
                    <span class="member-position">Technicien Expert</span>
                    <p>Certifié Apple et Samsung, il assure la qualité de tous nos appareils reconditionnés.</p>
                    <div class="member-social">
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="mailto:ibrahima@renew-senegal.com"><i class="fas fa-envelope"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter -->
    <section class="newsletter">
        <h2>Restez Informés</h2>
        <p>Abonnez-vous à notre newsletter pour recevoir nos dernières offres et actualités.</p>
        
        <form class="newsletter-form" action="subscribe.php" method="post">
            <input type="email" name="email" placeholder="Votre adresse email" required>
            <button type="submit">S'abonner <i class="fas fa-paper-plane"></i></button>
        </form>
    </section>
</main>

<!-- Toast Notification -->
<div id="toast" class="toast">Merci pour votre inscription à notre newsletter !</div>

<!-- Footer -->
<footer>
    <div class="container">
        <p>&copy; <?php echo date('Y'); ?> ReNew-Sénégal. Tous droits réservés.</p>
    </div>
</footer>

<script>
    // Afficher le toast si l'inscription à la newsletter a réussi
    <?php if ($newsletter_success): ?>
        document.addEventListener('DOMContentLoaded', function() {
            const toast = document.getElementById('toast');
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 3000);
        });
    <?php endif; ?>

    // Animation au défilement
    document.addEventListener('DOMContentLoaded', function() {
        const animateOnScroll = function() {
            const elements = document.querySelectorAll('.mission-card, .vision-card, .team-member');
            
            elements.forEach(element => {
                const elementPosition = element.getBoundingClientRect().top;
                const screenPosition = window.innerHeight / 1.2;
                
                if (elementPosition < screenPosition) {
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }
            });
        };
        
        // Initial state
        const cards = document.querySelectorAll('.mission-card, .vision-card, .team-member');
        cards.forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        });
        
        window.addEventListener('scroll', animateOnScroll);
        animateOnScroll(); // Trigger on load
    });
</script>

</body>
</html>