<?php
// config/db.php

$host = 'localhost'; // ou 'localhost'
$db_name = 'autocademy'; // Mude para o nome do seu DB
$username = 'root'; // Seu usuário do MySQL
$password = ''; // Sua senha do MySQL

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);
    // Configura o PDO para lançar exceções em caso de erro
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}
?>