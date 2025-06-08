<?php
// Démarrer la session
session_start();

// Vérifier si un index est fourni
if (isset($_GET['index']) && is_numeric($_GET['index'])) {
    $index = $_GET['index'];

    // Vérifier si le panier existe dans la session
    if (isset($_SESSION['panier']) && isset($_SESSION['panier'][$index])) {
        // Retirer l'élément du panier
        unset($_SESSION['panier'][$index]);
        // Ré-indexer le tableau pour éviter les trous dans les index
        $_SESSION['panier'] = array_values($_SESSION['panier']);
    }
}

// Rediriger vers la page du panier
header("location: panier.php");
exit;
?>
