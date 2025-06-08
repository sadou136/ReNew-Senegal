<?php
session_start();
$email = $password = "";
$email_err = $password_err = $login_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once 'config.php';

    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if (empty($email)) {
        $email_err = "Veuillez entrer un email.";
    }
    if (empty($password)) {
        $password_err = "Veuillez entrer un mot de passe.";
    }

    if (empty($email_err) && empty($password_err)) {
        $sql = "SELECT id, username, password FROM users WHERE email = ?";
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("s", $param_email);
            $param_email = $email;

            if ($stmt->execute()) {
                $stmt->store_result();
                if ($stmt->num_rows == 1) {
                    $stmt->bind_result($id, $username, $hashed_password);
                    if ($stmt->fetch()) {
                        if (password_verify($password, $hashed_password)) {
                            session_start();
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                            header("location: welcome.php");
                            exit;
                        } else {
                            $login_err = "Mot de passe invalide.";
                        }
                    }
                } else {
                    $login_err = "Aucun compte trouvé avec cet email.";
                }
            } else {
                echo "Oups ! Une erreur est survenue.";
            }
            $stmt->close();
        }
        $mysqli->close();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - ReNew Sénégal</title>
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
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            display: flex;
            max-width: 1000px;
            width: 100%;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
        }

        .login-left {
            flex: 1;
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-right {
            flex: 1;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .login-logo {
            width: 80px;
            height: 80px;
            margin-bottom: 20px;
            border-radius: 50%;
            border: 3px solid white;
        }

        .login-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: var(--dark);
        }

        .login-subtitle {
            color: var(--text-light);
            margin-bottom: 40px;
            font-size: 1.1rem;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark);
        }

        .form-control {
            width: 100%;
            padding: 15px 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            font-size: 1rem;
            transition: var(--transition);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
        }

        .input-icon {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 15px 30px;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
            border: none;
            cursor: pointer;
            width: 100%;
            font-size: 1rem;
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

        .login-links {
            margin-top: 20px;
            text-align: center;
        }

        .login-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }

        .login-link:hover {
            text-decoration: underline;
        }

        .welcome-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .welcome-text {
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 30px;
            max-width: 400px;
        }

        .feature-list {
            list-style: none;
            margin-top: 40px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .feature-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 1.2rem;
        }

        .invalid-feedback {
            color: var(--accent);
            font-size: 0.9rem;
            margin-top: 5px;
            display: block;
        }

        .is-invalid {
            border-color: var(--accent) !important;
        }

        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 25px;
            font-size: 0.9rem;
        }

        .alert-danger {
            background-color: #fdecea;
            color: var(--accent);
            border-left: 4px solid var(--accent);
        }

        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
            }
            
            .login-left, .login-right {
                padding: 40px 30px;
            }
            
            .login-right {
                display: none; /* Cacher la partie droite sur mobile */
            }
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-left">
        <h1 class="login-title">Bienvenue sur ReNew-Sénégal</h1>
        <p class="login-subtitle">Connectez-vous pour accéder à votre compte</p>

        <?php 
        if (!empty($login_err)) {
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" novalidate>
            <div class="form-group">
                <label for="email" class="form-label">Adresse Email</label>
                <div style="position: relative;">
                    <input type="email" name="email" id="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" placeholder="votre@email.com" value="<?php echo htmlspecialchars($email); ?>">
                    <i class="fas fa-envelope input-icon"></i>
                </div>
                <?php if (!empty($email_err)): ?>
                    <span class="invalid-feedback"><?php echo $email_err; ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Mot de passe</label>
                <div style="position: relative;">
                    <input type="password" name="password" id="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" placeholder="Votre mot de passe">
                    <i class="fas fa-lock input-icon"></i>
                </div>
                <?php if (!empty($password_err)): ?>
                    <span class="invalid-feedback"><?php echo $password_err; ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt" style="margin-right: 10px;"></i> Se connecter
                </button>
            </div>

            <div class="login-links">
                <a href="#" class="login-link">Mot de passe oublié ?</a>
                <span style="margin: 0 10px; color: #ddd;">|</span>
                <a href="inscription.php" class="login-link">Créer un compte</a>
            </div>
        </form>
    </div>
    
    <div class="login-right">
        <img src="logo/logo-renew-senegal.jpg" alt="Logo" class="login-logo">
        <h2 class="welcome-title">ReNew-Sénégal</h2>
        <p class="welcome-text">Découvrez des smartphones reconditionnés de qualité à prix réduits. Économique et écologique !</p>
        
        <ul class="feature-list">
            <li class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-check"></i>
                </div>
                <span>Produits testés et garantis</span>
            </li>
            <li class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-truck"></i>
                </div>
                <span>Livraison rapide</span>
            </li>
            <li class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <span>Paiement sécurisé</span>
            </li>
        </ul>
    </div>
</div>

</body>
</html>