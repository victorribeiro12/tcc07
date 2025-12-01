<?php
session_start();
// Removi o include 'conexao.php' pois não vamos usar banco agora
$nome_a_exibir = isset($_SESSION['nome_usuario']) ? $_SESSION['nome_usuario'] : 'Convidado';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matérias - Autocademy</title>
    <link rel="stylesheet" href="../css/materias.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="">

    <aside class="sidebar">
        <div class="logo-area">
            <img src="../imagens/logo.png" alt="Logo">
            <span class="logo-text">Autocademy</span>
        </div>

        <a href="dashboard.php" class="nav-link">
            <img src="../imagens/iconhome.png" alt="Início">
            <span class="nav-text">Início</span>
        </a>

        <a href="materias.php" class="nav-link active">
            <img src="../imagens/icons8-livros-96.png" alt="Matérias">
            <span class="nav-text">Matérias</span>
        </a>

    <a href="../html/chat.html" class="nav-link">
            <img src="../imagens/chat.png" alt="Chat">
            <span class="nav-text">Chat</span>
        </a>

        <a href="historico.html" class="nav-link">
            <img src="../imagens/iconhistorico.png" alt="Histórico">
            <span class="nav-text">Histórico</span>
        </a>

        <div class="spacer"></div>

        <a href="config.html" class="nav-link">
            <img src="../imagens/icons8-configurações-150.png" alt="Config">
            <span class="nav-text">Configurações</span>
        </a>
    </aside>

    <main class="main-wrapper">
        
        <header class="top-header">
            <div class="search-box">
                <input type="text" placeholder="Pesquisar matéria..." class="search-input">
                <i class="fa-solid fa-magnifying-glass" style="color: var(--text-secondary)"></i>
            </div>
            <div class="user-profile">
                <span><?php echo $nome_a_exibir; ?></span>
                <div class="avatar-circle"><i class="fa-solid fa-user"></i></div>
            </div>
        </header>

        <div class="scroll-content">
            <div class="container">
                
                <h1 class="section-title">Catálogo de Cursos</h1>

                <div class="materias-grid">
                    
                    <a href="materia_detalhes.php?id=1" class="materia-card" style="text-decoration: none;">
                        <img src="../img/cursos/mecanica.jpg" class="card-bg" 
                             onerror="this.src='https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?auto=format&fit=crop&q=80'">
                        <div class="card-overlay">
                            <h2 class="card-title">Mecânica de Precisão</h2>
                        </div>
                    </a>

                    <a href="materia_detalhes.php?id=2" class="materia-card" style="text-decoration: none;">
                        <img src="../img/cursos/eletrica.jpg" class="card-bg" 
                             onerror="this.src='https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&q=80'">
                        <div class="card-overlay">
                            <h2 class="card-title">Elétrica Automotiva</h2>
                        </div>
                    </a>

                    <a href="materia_detalhes.php?id=3" class="materia-card" style="text-decoration: none;">
                        <img src="../img/cursos/pintura.jpg" class="card-bg" 
                             onerror="this.src='https://images.unsplash.com/photo-1625047509168-a7026f36de04?auto=format&fit=crop&q=80'">
                        <div class="card-overlay">
                            <h2 class="card-title">Pintura e Funilaria</h2>
                        </div>
                    </a>

                    <a href="materia_detalhes.php?id=4" class="materia-card" style="text-decoration: none;">
                        <img src="../img/cursos/injecao.jpg" class="card-bg" 
                             onerror="this.src='https://images.unsplash.com/photo-1517524008697-84bbe3c3fd98?auto=format&fit=crop&q=80'">
                        <div class="card-overlay">
                            <h2 class="card-title">Injeção Eletrônica</h2>
                        </div>
                    </a>

                    <a href="materia_detalhes.php?id=5" class="materia-card" style="text-decoration: none;">
                        <img src="../img/cursos/performance.jpg" class="card-bg" 
                             onerror="this.src='https://images.unsplash.com/photo-1503376763036-066120622c74?auto=format&fit=crop&q=80'">
                        <div class="card-overlay">
                            <h2 class="card-title">Preparação de Motores</h2>
                        </div>
                    </a>

                    <a href="materia_detalhes.php?id=6" class="materia-card" style="text-decoration: none;">
                        <img src="../img/cursos/detailing.jpg" class="card-bg" 
                             onerror="this.src='https://images.unsplash.com/photo-1601362840469-51e4d8d58785?auto=format&fit=crop&q=80'">
                        <div class="card-overlay">
                            <h2 class="card-title">Estética Automotiva</h2>
                        </div>
                    </a>

                    <a href="materia_detalhes.php?id=7" class="materia-card" style="text-decoration: none;">
                        <img src="../img/cursos/obd2.jpg" class="card-bg" 
                             onerror="this.src='https://images.unsplash.com/photo-1580273916550-e323be2ae537?auto=format&fit=crop&q=80'">
                        <div class="card-overlay">
                            <h2 class="card-title">Diagnóstico OBD2</h2>
                        </div>
                    </a>

                    <a href="materia_detalhes.php?id=8" class="materia-card" style="text-decoration: none;">
                        <img src="../img/cursos/arcondicionado.jpg" class="card-bg" 
                             onerror="this.src='https://images.unsplash.com/photo-1626806787461-102c1bfaaea1?auto=format&fit=crop&q=80'">
                        <div class="card-overlay">
                            <h2 class="card-title">Ar Condicionado</h2>
                        </div>
                    </a>

                </div> </div> </div> 
            </main>
    

    <script src="../html/tema.js"></script>
</body>
</html>