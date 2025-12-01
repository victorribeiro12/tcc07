<?php
// Arquivo de diagnóstico: backend/test_db.php
// Acesse via navegador: http://localhost/tcc4/backend/test_db.php

// Mostra erros (apenas para desenvolvimento local)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/db.php';

try {
    // Verifica se $pdo existe e é um PDO
    if (!isset($pdo) || !($pdo instanceof PDO)) {
        throw new Exception('Variável $pdo não definida ou não é uma instância de PDO. Verifique config/db.php');
    }

    // Tenta executar uma consulta simples
    $stmt = $pdo->query('SELECT 1 AS ok');
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    echo "Conexão com o banco OK. Test query retornou: ";
    var_dump($row);

    // Mostra algumas informações úteis
    echo "<br>DSN e usuário de conexão verificados no arquivo `config/db.php`.<br>`config/db.php` usa PDO; verifique credenciais e nome do banco.<br>`usuarios` deve existir com colunas: id, nome, email, senha, tipo_usuario.<br>";
} catch (Exception $e) {
    // Exibe detalhes do erro (apenas em ambiente de desenvolvimento)
    echo "Erro de diagnóstico: " . htmlspecialchars($e->getMessage());
    if ($e instanceof PDOException) {
        echo "<br>PDOException: " . htmlspecialchars($e->getMessage());
    }
}

?>