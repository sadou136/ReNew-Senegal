<?php
// Inclure la configuration de la base de données
require_once 'config.php';

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer et sécuriser les données du formulaire
    $name = $mysqli->real_escape_string(trim($_POST['name']));
    $email = $mysqli->real_escape_string(trim($_POST['email']));
    $message = $mysqli->real_escape_string(trim($_POST['message']));
    
    // Préparer et exécuter la requête d'insertion
    $sql = "INSERT INTO contact (name, email, message) VALUES (?, ?, ?)";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("sss", $name, $email, $message);
        
        if ($stmt->execute()) {
            header("Location: welcome.php?contact_success=1");
            exit();
        } else {
            echo "Erreur : Impossible d'envoyer votre message. Veuillez réessayer.";
        }
        $stmt->close();
    } else {
        echo "Erreur : Impossible de préparer la requête.";
    }
}

// Fermer la connexion
$mysqli->close();
?>
