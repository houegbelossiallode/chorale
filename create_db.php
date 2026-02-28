<?php
$host = '127.0.0.1';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS chorale_db");
    echo "Base de données 'chorale_db' créée avec succès ou déjà existante.\n";
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
