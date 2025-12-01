<?php
session_start();
// include 'conexao.php'; // Descomente quando quiser puxar dados reais do banco

// Verifica se é professor mesmo (Segurança básica)
// Se a sessão não tiver 'usuario_tipo' como 'professor', você pode redirecionar
if (!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] !== 'professor') {
    // header("Location: login.html"); 
    // exit;
}

$nome_prof = isset($_SESSION['nome_usuario']) ? $_SESSION['nome_usuario'] : 'Professor Giordano Clepf';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal do Docente - Autocademy</title>
    <!-- Usando o mesmo CSS padronizado -->
    <link rel="stylesheet" href="../css/Dasprof.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="">

    <!-- SIDEBAR DO PROFESSOR -->
    <aside class="sidebar">
        <div class="logo-area">
            <img src="../imagens/logo.png" alt="Logo">
            <span class="logo-text">Autocademy <small style="font-size: 0.6rem; color: var(--accent-color);">DOCENTE</small></span>
        </div>

        <a href="dashboard_professor.php" class="nav-link active">
            <i class="fa-solid fa-chalkboard-user"></i>
            <span class="nav-text">Painel</span>
        </a>

        <a href="../html/turmas.php" class="nav-link">
            <i class="fa-solid fa-users"></i> <!-- Ícone de Turmas -->
            <span class="nav-text">Minhas Turmas</span>
        </a>

        <a href="../html/notas.php" class="nav-link">
            <i class="fa-solid fa-file-pen"></i> <!-- Ícone de Notas -->
            <span class="nav-text">Lançar Notas</span>
        </a>

        <a href="./chatprof.html" class="nav-link">
            <i class="fa-regular fa-comments"></i>
            <span class="nav-text">Mensagens</span>
        </a>

        <div class="spacer"></div>

        <a href="config.html" class="nav-link">
            <img src="../imagens/icons8-configurações-150.png" alt="Config">
            <span class="nav-text">Configurações</span>
        </a>
    </aside>

    <!-- CONTEÚDO PRINCIPAL -->
    <main class="main-wrapper">
        
        <header class="top-header">
            <div class="search-box">
                <input type="text" placeholder="Buscar aluno ou turma..." class="search-input">
                <i class="fa-solid fa-magnifying-glass" style="color: var(--text-secondary)"></i>
            </div>
            <div class="user-profile">
                <span style="text-align: right;">
                    <?php echo $nome_prof; ?><br>
                    <small style="color: var(--text-secondary); font-size: 0.75rem;">Instrutor Técnico</small>
                </span>
                <div class="avatar-circle" style="background-color: var(--accent-color);">
                    <i class="fa-solid fa-chalkboard-teacher"></i>
                </div>
            </div>
        </header>

        <div class="scroll-content">
            <div class="container">
                
                <h1 class="section-title">Visão Geral</h1>

                <!-- CARDS DE ESTATÍSTICAS -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fa-solid fa-users"></i></div>
                        <div class="stat-info">
                            <h3>124</h3>
                            <p>Alunos Ativos</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fa-solid fa-book-open"></i></div>
                        <div class="stat-info">
                            <h3>4</h3>
                            <p>Turmas em Andamento</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fa-solid fa-clipboard-check"></i></div>
                        <div class="stat-info">
                            <h3>12</h3>
                            <p>Trabalhos para Corrigir</p>
                        </div>
                    </div>
                </div>

                <div class="section-divider">
                    <i class="fa-solid fa-layer-group"></i> Turmas Sob Sua Responsabilidade
                </div>

                <!-- LISTA DE TURMAS (Reaproveitando o estilo de Cards, mas adaptado) -->
                <div class="materias-grid">
                    
                    <!-- Turma 1 -->
                    <div class="materia-card" style="height: auto; min-height: 200px;">
                        <div class="card-bg" style="background: linear-gradient(45deg, #1a1a1a, #2c2c2c); opacity: 1;"></div>
                        <div class="card-overlay" style="background: none; padding: 20px; flex-direction: column; align-items: flex-start; justify-content: space-between; height: 100%;">
                            
                            <div style="width: 100%;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                    <span class="badge-pending" style="background: rgba(0, 120, 212, 0.2); color: #55cffa; border-color: #0078d4;">Manhã</span>
                                    <i class="fa-solid fa-ellipsis" style="color: var(--text-secondary); cursor: pointer;"></i>
                                </div>
                                <h2 class="card-title" style="font-size: 1.3rem; margin-bottom: 5px;">Mecânica Básica</h2>
                                <p style="color: var(--text-secondary); font-size: 0.9rem;">Turma: <strong>MEC-2024-A</strong></p>
                            </div>

                            <div style="width: 100%; margin-top: 20px;">
                                <div style="display: flex; justify-content: space-between; font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 5px;">
                                    <span>Progresso do Semestre</span>
                                    <span>75%</span>
                                </div>
                                <div style="width: 100%; height: 6px; background: rgba(231, 214, 214, 0.1); border-radius: 3px;">
                                    <div style="width: 75%; height: 100%; background: var(--accent-color); border-radius: 3px;"></div>
                                </div>
                                <div style="margin-top: 15px; display: flex; gap: 10px;">
                                    <a href="#" class="action-btn-small">Diário</a>
                                    <a href="#" class="action-btn-small">Notas</a>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Turma 2 -->
                    <div class="materia-card" style="height: auto; min-height: 200px;">
                        <div class="card-bg" style="background: linear-gradient(45deg, #1a1a1a, #2c2c2c); opacity: 1;"></div>
                        <div class="card-overlay" style="background: none; padding: 20px; flex-direction: column; align-items: flex-start; justify-content: space-between; height: 100%;">
                            
                            <div style="width: 100%;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                    <span class="badge-pending" style="background: rgba(255, 185, 0, 0.2); color: #ffb900; border-color: #ffb900;">Noite</span>
                                    <i class="fa-solid fa-ellipsis" style="color: var(--text-secondary); cursor: pointer;"></i>
                                </div>
                                <h2 class="card-title" style="font-size: 1.3rem; margin-bottom: 5px;">Injeção Eletrônica</h2>
                                <p style="color: var(--text-secondary); font-size: 0.9rem;">Turma: <strong>INJ-2024-N</strong></p>
                            </div>

                            <div style="width: 100%; margin-top: 20px;">
                                <div style="display: flex; justify-content: space-between; font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 5px;">
                                    <span>Progresso do Semestre</span>
                                    <span>30%</span>
                                </div>
                                <div style="width: 100%; height: 6px; background: rgba(255,255,255,0.1); border-radius: 3px;">
                                    <div style="width: 30%; height: 100%; background: var(--accent-color); border-radius: 3px;"></div>
                                </div>
                                <div style="margin-top: 15px; display: flex; gap: 10px;">
                                    <a href="#" class="action-btn-small">Diário</a>
                                    <a href="#" class="action-btn-small">Notas</a>
                                </div>
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