<?php
session_start();

// --- CORREÇÃO DA CONEXÃO (BLOCO ROBUSTO) ---
// 1. Tenta incluir o arquivo de conexão
if (file_exists('conexao.php')) {
    include 'conexao.php';
} elseif (file_exists('../config/db.php')) {
    include '../config/db.php';
} else {
    // Se não achar arquivo nenhum, tenta conectar manualmente
    $conn = new mysqli('localhost', 'root', '', 'autocademy');
}

// 2. Garante que $conn exista, mesmo se o seu arquivo usar $conexao
if (isset($conexao) && !isset($conn)) {
    $conn = $conexao;
}

// 3. Verifica se a conexão funcionou antes de continuar
if (!isset($conn) || $conn->connect_error) {
    die("Erro Crítico: Não foi possível conectar ao banco de dados. Verifique o arquivo conexao.php.");
}
// --- FIM DA CORREÇÃO ---

$nome_prof = $_SESSION['nome_usuario'] ?? 'Professor';

// 1. Filtros (Turma e Data)
// Pega a turma da URL ou usa a primeira que encontrar
$id_curso_selecionado = isset($_GET['turma']) ? intval($_GET['turma']) : 1;
$data_selecionada = isset($_GET['data']) ? $_GET['data'] : date('Y-m-d'); // Padrão: Hoje

// 2. Busca Cursos (Para o Select)
$sql_cursos = "SELECT * FROM cursos";
$result_cursos = $conn->query($sql_cursos);

// 3. Busca Alunos + Presença (LEFT JOIN com tabela frequencia na data específica)
$sql_chamada = "
    SELECT u.id, u.nome, f.presente 
    FROM usuarios u
    JOIN inscricoes i ON u.id = i.id_usuario
    LEFT JOIN frequencia f ON u.id = f.id_usuario 
         AND f.id_curso = $id_curso_selecionado 
         AND f.data_aula = '$data_selecionada'
    WHERE i.id_curso = $id_curso_selecionado AND u.email LIKE '%@senaimgaluno%'
    ORDER BY u.nome ASC
";
$result_alunos = $conn->query($sql_chamada);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diário de Classe - Autocademy</title>
    <link rel="stylesheet" href="../css/style4.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Estilos específicos para a Chamada */
        .toolbar {
            display: flex; gap: 15px; align-items: center; margin-bottom: 20px;
            background: #fff; padding: 15px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .date-input {
            padding: 8px; border: 1px solid #ddd; border-radius: 5px; font-family: inherit;
        }
        .switch-container {
            display: flex; align-items: center; justify-content: center;
        }
        /* Toggle Switch Bonito */
        .switch {
            position: relative; display: inline-block; width: 50px; height: 26px;
        }
        .switch input { opacity: 0; width: 0; height: 0; }
        .slider {
            position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0;
            background-color: #ff4d4d; /* Vermelho (Falta) */
            transition: .4s; border-radius: 34px;
        }
        .slider:before {
            position: absolute; content: ""; height: 20px; width: 20px; left: 3px; bottom: 3px;
            background-color: white; transition: .4s; border-radius: 50%;
        }
        input:checked + .slider {
            background-color: #10b981; /* Verde (Presença) */
        }
        input:checked + .slider:before {
            transform: translateX(24px);
        }
        .status-label { font-size: 0.9rem; font-weight: bold; margin-left: 10px; width: 80px;}
   
        .toolbar {
            display: flex;
            gap: 20px;
            align-items: flex-end; /* Alinha labels e inputs na base */
            margin-bottom: 30px;
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            padding: 25px 30px;
            border-radius: 16px;
            box-shadow: var(--shadow);
            flex-wrap: wrap; /* Responsivo */
            animation: fadeIn 0.4s ease-out;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
            flex: 1;
            min-width: 200px;
        }

        .filter-group label {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Estilo dos Inputs e Selects */
        .class-selector, .date-input {
            width: 100%;
            background-color: var(--bg-input);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            padding: 12px 16px;
            border-radius: 10px;
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            outline: none;
            transition: var(--transition-border), var(--transition-shadow);
            cursor: pointer;
        }

        .class-selector:focus, .date-input:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(229, 57, 53, 0.15); /* Glow sutil */
        }
        
        /* Ajuste do ícone de calendário no input date (apenas Chrome/Edge) */
        ::-webkit-calendar-picker-indicator {
            filter: invert(1);
            opacity: 0.6;
            cursor: pointer;
        }

        /* ==========================================================================
           TABELA DE CHAMADA CUSTOMIZADA
           ========================================================================== */
        .settings-card {
            overflow: visible !important; /* Permite sombras vazarem se necessário */
        }

        .data-table {
            width: 100%;
            border-collapse: separate; 
            border-spacing: 0;
        }

        .data-table th {
            text-align: left;
            padding: 20px 24px;
            font-weight: 600;
            color: var(--text-secondary);
            font-size: 0.9rem;
            text-transform: uppercase;
            border-bottom: 2px solid var(--border-color);
            background-color: rgba(0,0,0,0.15); /* Cabeçalho levemente mais escuro */
        }

        .data-table td {
            padding: 18px 24px;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-primary);
            vertical-align: middle;
            transition: background-color 0.2s;
        }

        /* Hover na linha inteira */
        .data-table tbody tr:hover td {
            background-color: var(--bg-hover);
        }

        .data-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Avatar do Aluno na Tabela */
        .student-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .student-avatar-small {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--border-color), var(--bg-hover));
            color: var(--text-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
            border: 2px solid var(--bg-card);
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        /* ==========================================================================
           SWITCH (BOTÃO DE PRESENÇA) - Estilo Premium
           ========================================================================== */
        .switch-container {
            display: flex;
            align-items: center;
            gap: 15px;
            justify-content: center; /* Centraliza na célula */
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 56px;
            height: 30px;
            flex-shrink: 0;
        }

        .switch input { opacity: 0; width: 0; height: 0; }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: #ef4444; /* Vermelho (Falta) por padrão */
            transition: .4s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 34px;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.3); /* Profundidade */
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 22px;
            width: 22px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 50%;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        /* Ícones dentro do slider (Opcional, visual avançado) */
        .slider:after {
            content: '✕'; /* X de falta */
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255,255,255,0.7);
            font-size: 14px;
            font-weight: bold;
        }

        /* ESTADO: MARCADO (PRESENTE) */
        input:checked + .slider {
            background-color: #10b981; /* Verde (Presente) */
        }

        input:checked + .slider:before {
            transform: translateX(26px);
        }

        input:checked + .slider:after {
            content: '✓'; /* Check de presença */
            left: 10px;
            right: auto;
            color: white;
        }

        /* LABEL DE TEXTO (Presente / Falta) */
        .status-label {
            font-size: 0.9rem;
            font-weight: 700;
            min-width: 70px;
            transition: color 0.3s;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* ==========================================================================
           BOTÃO SALVAR FLUTUANTE OU FIXO
           ========================================================================== */
        .btn-save {
            background: linear-gradient(135deg, var(--accent-color), #c62828);
            border: none;
            padding: 14px 35px;
            color: white;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(229, 57, 53, 0.4);
            transition: transform 0.2s, box-shadow 0.2s;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .btn-save:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(229, 57, 53, 0.5);
        }
        
        .btn-save:active {
            transform: scale(0.98);
        }

        /* Feedback de Mensagens (Sucesso/Erro) */
        .status-message {
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
            animation: slideDown 0.4s ease;
        }
        .status-success {
            background-color: rgba(16, 185, 129, 0.15);
            border: 1px solid #10b981;
            color: #10b981;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    
   
   </style>

    <script>
        // Atualiza a página quando muda a turma ou a data
        function atualizarFiltros() {
            const turma = document.getElementById('select-turma').value;
            const data = document.getElementById('input-data').value;
            window.location.href = `diario_classe.php?turma=${turma}&data=${data}`;
        }

        // Muda o texto de "Presente" para "Falta" visualmente
        function toggleLabel(checkbox, id) {
            const label = document.getElementById('label-' + id);
            if (checkbox.checked) {
                label.innerText = "Presente";
                label.style.color = "#10b981";
            } else {
                label.innerText = "Falta";
                label.style.color = "#ff4d4d";
            }
        }
    </script>
</head>

<body>
    <aside class="sidebar">
        <div class="logo-area">
            <img src="../imagens/logo.png" alt="Logo">
            <span class="logo-text">Autocademy <small style="font-size: 0.6rem; color: var(--accent-color);">DOCENTE</small></span>
        </div>
        <a href="dashboard_professor.php" class="nav-link"><i class="fa-solid fa-chalkboard-user"></i><span class="nav-text">Painel</span></a>

        <a href="turmas_professor.php" class="nav-link"><i class="fa-solid fa-users"></i><span class="nav-text">Minhas Turmas</span></a>

        <a href="lancar_notas.php" class="nav-link"><i class="fa-solid fa-file-pen"></i><span class="nav-text">Lançar Notas</span></a>
        
                <a href="chatprof.html" class="nav-link"><i class="fa-regular fa-comments"></i><span class="nav-text">Mensagens</span></a>

        <a href="diario_classe.php" class="nav-link active"><img src="../imagens/iconagenda.png"><span class="nav-text">Diário das turmas</span></a>
        <div class="spacer"></div>
        <a href="configpro.php" class="nav-link"><img src="../imagens/icons8-configurações-150.png"><span class="nav-text">Configurações</span></a>
    </aside>

    <main class="main-wrapper">
        <header class="top-header">
            <div class="search-box">
                <input type="text" placeholder="Buscar..." class="search-input" style="opacity: 0;"> </div>
            <div class="user-profile">
                <span><?php echo $nome_prof; ?></span>
                <div class="avatar-circle" style="background-color: var(--accent-color);"><i class="fa-solid fa-chalkboard-teacher"></i></div>
            </div>
        </header>

        <div class="scroll-content">
            <div class="container">
                
                <form action="../backend/salvar_chamada.php" method="POST">
                    <h1 class="page-title">Diário de Classe</h1>
                    
                    <div class="toolbar">
                        
                        <div style="flex: 1;">
                            <label style="display:block; font-size: 0.8rem; color: #666;">Selecione a Turma:</label>
                            <select id="select-turma" name="id_curso" class="class-selector" style="margin:0; width: 100%;" onchange="atualizarFiltros()">
                                <?php 
                                if ($result_cursos) {
                                    while($curso = $result_cursos->fetch_assoc()) {
                                        $sel = ($curso['id'] == $id_curso_selecionado) ? 'selected' : '';
                                        echo "<option value='{$curso['id']}' $sel>{$curso['nome']}</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <div>
                            <label style="display:block; font-size: 0.8rem; color: #666;">Data da Aula:</label>
                            <input type="date" id="input-data" name="data_aula" class="date-input" 
                                   value="<?php echo $data_selecionada; ?>" onchange="atualizarFiltros()">
                        </div>

                        <button type="submit" class="btn-save" style="margin-left: auto;">
                            <i class="fa-solid fa-check"></i> Salvar Chamada
                        </button>
                    </div>

                    <?php if (isset($_GET['status']) && $_GET['status'] == 'sucesso'): ?>
                        <div style="background: rgba(16, 185, 129, 0.2); color: #10b981; padding: 10px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #10b981;">
                            <i class="fa-solid fa-check-circle"></i> Chamada do dia <?php echo date('d/m/Y', strtotime($data_selecionada)); ?> salva!
                        </div>
                    <?php endif; ?>

                    <div class="settings-card" style="padding: 0; border: none;">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Aluno</th>
                                    <th style="width: 150px; text-align: center;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result_alunos && $result_alunos->num_rows > 0) {
                                    while($aluno = $result_alunos->fetch_assoc()) {
                                        
                                        // Lógica do Checkbox:
                                        // 1. Se veio do banco '1', está presente.
                                        // 2. Se veio do banco '0', está ausente.
                                        // 3. Se veio NULL (nunca fez chamada nesse dia), sugerimos 'checked' (Presente) como padrão.
                                        $isPresente = ($aluno['presente'] === null || $aluno['presente'] == 1) ? true : false;
                                        
                                        $checkState = $isPresente ? 'checked' : '';
                                        $labelState = $isPresente ? 'Presente' : 'Falta';
                                        $colorState = $isPresente ? '#10b981' : '#ff4d4d';
                                        ?>
                                        <tr>
                                            <td>
                                                <div style="display: flex; align-items: center; gap: 10px;">
                                                    <div class="avatar-circle" style="width: 35px; height: 35px; font-size: 0.8rem; background: #eee; color: #555;">
                                                        <?php echo strtoupper(substr($aluno['nome'], 0, 2)); ?>
                                                    </div>
                                                    <strong><?php echo $aluno['nome']; ?></strong>
                                                </div>
                                            </td>
                                            <td class="switch-container">
                                                <label class="switch">
                                                    <input type="checkbox" name="presenca[]" value="<?php echo $aluno['id']; ?>" 
                                                           <?php echo $checkState; ?> 
                                                           onchange="toggleLabel(this, <?php echo $aluno['id']; ?>)">
                                                    <span class="slider"></span>
                                                </label>
                                                <span id="label-<?php echo $aluno['id']; ?>" class="status-label" style="color: <?php echo $colorState; ?>">
                                                    <?php echo $labelState; ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php 
                                    } 
                                } else {
                                    echo "<tr><td colspan='2' style='text-align:center; padding: 30px;'>Nenhum aluno nesta turma.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                </form>
            </div>
        </div>
    </main>
    <script src="tema.js"></script>
</body>
</html>