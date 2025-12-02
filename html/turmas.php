<?php
session_start();
$nome_prof = isset($_SESSION['nome_usuario']) ? $_SESSION['nome_usuario'] : 'Professor Roberto';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Turmas - Autocademy Docente</title>
    <link rel="stylesheet" href="../css/Dasprof.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="">

    <aside class="sidebar">
        <div class="logo-area">
            <img src="../imagens/logo.png" alt="Logo">
            <span class="logo-text">Autocademy <small style="font-size: 0.6rem; color: var(--accent-color);">DOCENTE</small></span>
        </div>

        <a href="dashboard_professor.php" class="nav-link">
            <i class="fa-solid fa-chalkboard-user"></i>
            <span class="nav-text">Painel</span>
        </a>

        <!-- ATIVO -->
        <a href="turmas_professor.php" class="nav-link active">
            <i class="fa-solid fa-users"></i>
            <span class="nav-text">Minhas Turmas</span>
        </a>

        <a href="lancar_notas.php" class="nav-link">
            <i class="fa-solid fa-file-pen"></i>
            <span class="nav-text">Lançar Notas</span>
        </a>

        <a href="chat.html" class="nav-link">
            <i class="fa-regular fa-comments"></i>
            <span class="nav-text">Mensagens</span>
        </a>

   <a href="../html/diario_classe.php" class="nav-link">
            <img src="../imagens/iconagenda.png" alt="Config">
            <span class="nav-text">Diário das Turmas</span>
        </a>

        <div class="spacer"></div>
        <a href="configpro.php" class="nav-link"><img src="../imagens/icons8-configurações-150.png"><span class="nav-text">Configurações</span></a>
    </aside>

    <main class="main-wrapper">
        <header class="top-header">
            <div class="search-box">
                <input type="text" placeholder="Pesquisar turma..." class="search-input">
                <i class="fa-solid fa-magnifying-glass" style="color: var(--text-secondary)"></i>
            </div>
            <div class="user-profile">
                <span><?php echo $nome_prof; ?></span>
                <div class="avatar-circle" style="background-color: var(--accent-color);"><i class="fa-solid fa-chalkboard-teacher"></i></div>
            </div>
        </header>

        <div class="scroll-content">
            <div class="container">
                <h1 class="page-title">Gerenciamento de Turmas</h1>

                <div class="materias-grid">
                    
                    <!-- Turma 1 -->
                    <div class="materia-card" style="height: auto; min-height: 220px;">
                        <div class="card-bg" style="background: linear-gradient(45deg, #1a1a1a, #2c2c2c); opacity: 1;"></div>
                        <div class="card-overlay" style="background: none; padding: 25px; flex-direction: column; align-items: flex-start; height: 100%; justify-content: space-between;">
                            
                            <div style="width: 100%;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                    <span class="badge-pending" style="color: #55cffa; border-color: #0078d4; background: rgba(0, 120, 212, 0.1);">35 Alunos</span>
                                    <i class="fa-solid fa-gear" style="color: var(--text-secondary); cursor: pointer;"></i>
                                </div>
                                <h2 class="card-title" style="font-size: 1.4rem;">Mecânica Básica</h2>
                                <p style="color: var(--text-secondary); margin-top: 5px;">Turma: MEC-2024-A</p>
                            </div>

                            <div style="width: 100%; display: flex; gap: 10px; margin-top: 20px;">
                                <a href="lancar_notas.php?turma=mec" class="action-btn-small" style="flex: 1; text-align: center;"><i class="fa-solid fa-table"></i> Notas</a>
                                <a href="#" class="action-btn-small" style="flex: 1; text-align: center;"><i class="fa-solid fa-list"></i> Chamada</a>
                            </div>
                        </div>
                    </div>

                    <!-- Turma 2 -->
                    <div class="materia-card" style="height: auto; min-height: 220px;">
                        <div class="card-bg" style="background: linear-gradient(45deg, #1a1a1a, #2c2c2c); opacity: 1;"></div>
                        <div class="card-overlay" style="background: none; padding: 25px; flex-direction: column; align-items: flex-start; height: 100%; justify-content: space-between;">
                            
                            <div style="width: 100%;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                    <span class="badge-pending" style="color: #ffb900; border-color: #ffb900; background: rgba(255, 185, 0, 0.1);">28 Alunos</span>
                                    <i class="fa-solid fa-gear" style="color: var(--text-secondary); cursor: pointer;"></i>
                                </div>
                                <h2 class="card-title" style="font-size: 1.4rem;">Injeção Eletrônica</h2>
                                <p style="color: var(--text-secondary); margin-top: 5px;">Turma: INJ-2024-N</p>
                            </div>

                            <div style="width: 100%; display: flex; gap: 10px; margin-top: 20px;">
                                <a href="lancar_notas.php?turma=inj" class="action-btn-small" style="flex: 1; text-align: center;"><i class="fa-solid fa-table"></i> Notas</a>
                                <a href="#" class="action-btn-small" style="flex: 1; text-align: center;"><i class="fa-solid fa-list"></i> Chamada</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>
    <script src="tema.js"></script>
</body>
</html>