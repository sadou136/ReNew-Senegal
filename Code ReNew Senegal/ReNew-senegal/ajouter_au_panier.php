<?php
// Démarrer la session
session_start();

// Vérifier si un ID de produit est fourni
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $product_id = $_GET['id'];

    // Connexion à la base de données
    require_once 'config.php';

    // Préparer la requête SQL pour obtenir les détails du produit
    $sql = "SELECT * FROM produits WHERE id = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        $stmt->close();

        if ($product) {
            // Vérifier si le panier existe dans la session
            if (!isset($_SESSION['panier'])) {
                $_SESSION['panier'] = [];
            }

            // Ajouter le produit au panier avec une quantité initiale de 1
            $product['quantite'] = 1;
            $_SESSION['panier'][] = $product;

            // Rediriger vers la page du panier
            header("location: panier.php");
            exit;
        } else {
            die("Produit non trouvé.");
        }
    } else {
        die("Erreur lors de la préparation de la requête.");
    }
} else {
    die("ID de produit non valide.");
}
?>
