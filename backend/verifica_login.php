<?php
// backend/verifica_login.php

// Se a sessão não estiver iniciada, inicie-a
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário NÃO está logado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    
    // Redireciona para a página de login
    // O 'http_build_query' é um truque para levar o usuário de volta para onde ele tentou ir
    $redirect_url = 'login.php?erro=Voce precisa estar logado para acessar esta pagina.';
    header("Location: " . $redirect_url);
    exit;
}

// Se chegou até aqui, o usuário está logado.
?>