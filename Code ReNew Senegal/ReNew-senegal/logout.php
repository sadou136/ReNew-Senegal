<?php
// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté, puis le déconnecter
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    // Détruire toutes les variables de session
    session_unset();

    // Détruire la session
    session_destroy();

    // Rediriger l'utilisateur vers la page de connexion
    header("location: index.php");
    exit;
} else {
    // Si l'utilisateur n'est pas connecté, le rediriger également vers la page de connexion
    header("location: index.php");
    exit;
}
?>
