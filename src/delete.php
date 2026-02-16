<?php
require_once 'config.php';

// Vérifier que l'utilisateur est connecté
requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /index.php');
    exit;
}

$id = $_POST['id'] ?? null;

if (!$id || !is_numeric($id)) {
    header('Location: /index.php');
    exit;
}

try {
    $pdo = getDbConnection();
    
    // Vérifier que le véhicule existe
    $stmt = $pdo->prepare('SELECT id, brand, model FROM vehicles WHERE id = ?');
    $stmt->execute([$id]);
    $vehicle = $stmt->fetch();
    
    if (!$vehicle) {
        header('Location: /index.php');
        exit;
    }
    
    // Supprimer le véhicule
    $stmt = $pdo->prepare('DELETE FROM vehicles WHERE id = ?');
    $stmt->execute([$id]);
    
    // Rediriger vers la page d'accueil avec un message de succès
    startSession();
    $_SESSION['success_message'] = 'Le véhicule ' . $vehicle['brand'] . ' ' . $vehicle['model'] . ' a été supprimé avec succès.';
    header('Location: /index.php');
    exit;
    
} catch (PDOException $e) {
    // En cas d'erreur, rediriger avec un message d'erreur
    startSession();
    $_SESSION['error_message'] = 'Erreur lors de la suppression du véhicule.';
    header('Location: /detail.php?id=' . $id);
    exit;
}
