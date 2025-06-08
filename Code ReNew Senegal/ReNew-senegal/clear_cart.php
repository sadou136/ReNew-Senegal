<?php
// Démarrer la session
session_start();

// Vérifier si le panier existe dans la session
if (isset($_SESSION['panier'])) {
    // Vider le panier
    unset($_SESSION['panier']);
}

// Rediriger vers la page du panier
header("location: panier.php");
exit;
?>
