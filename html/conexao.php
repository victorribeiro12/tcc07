<?php
// Configurações do Banco de Dados
$host = 'localhost';      // Geralmente é localhost
$usuario = 'root';        // Usuário padrão do XAMPP/WAMP é root
$senha = '';              // Senha padrão geralmente é vazia
$banco = 'autocademy';    // NOME DO BANCO (Edite aqui se for diferente)

$conexao = new mysqli($host, $usuario, $senha, $banco);
if ($conexao->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conexao->connect_error);
}

$conexao->set_charset("utf8mb4");
?>