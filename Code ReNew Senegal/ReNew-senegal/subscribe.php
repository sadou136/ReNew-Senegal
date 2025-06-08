<?php
// Inclure la configuration de la base de données
require_once 'config.php';

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer et sécuriser les données du formulaire
    $email = $mysqli->real_escape_string(trim($_POST['email']));
    
    // Préparer et exécuter la requête d'insertion
    $sql = "INSERT INTO newsletter (email) VALUES (?)";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("s", $email);
        
        if ($stmt->execute()) {
            header("Location: abouts.php?newsletter_success=1");
            exit();
        } else {
            echo "Erreur : Impossible d'inscrire l'email à la newsletter. Veuillez réessayer.";
        }
        $stmt->close();
    } else {
        echo "Erreur : Impossible de préparer la requête.";
    }
}

// Fermer la connexion
$mysqli->close();
?>
