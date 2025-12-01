<?php
session_start();

// --- 1. CONEXÃO ROBUSTA ---
// Garante que o banco seja encontrado
if (file_exists('conexao.php')) {
    include 'conexao.php';
} elseif (file_exists('../config/db.php')) {
    include '../config/db.php';
} else {
    die("Erro Crítico: Arquivo de conexão com o banco não encontrado.");
}

if (isset($conexao) && !isset($conn)) {
    $conn = $conexao;
}

// --- 2. LÓGICA DE SELEÇÃO DA TURMA ---
// Se tiver ?turma=5 na URL, usa o 5. Se não, usa o curso ID 1 como padrão.
$id_curso_selecionado = isset($_GET['turma']) ? intval($_GET['turma']) : 1;

$nome_prof = $_SESSION['nome_usuario'] ?? 'Professor';

// --- 3. BUSCAS NO BANCO ---

// A) Busca TODOS os cursos para preencher o Menu Dropdown
$sql_cursos = "SELECT * FROM cursos";
$result_cursos = $conn->query($sql_cursos);

// B) Busca APENAS os alunos inscritos na turma selecionada
// O LEFT JOIN na tabela 'notas' garante que tragamos a nota se ela existir, ou NULL se não existir.
$sql_alunos = "
    SELECT u.id, u.nome, u.foto_perfil, n.prova1, n.prova2, n.trabalho 
    FROM usuarios u
    JOIN inscricoes i ON u.id = i.id_usuario
    LEFT JOIN notas n ON u.id = n.id_usuario AND n.id_curso = $id_curso_selecionado
    WHERE i.id_curso = $id_curso_selecionado AND u.email LIKE '%@senaimgaluno%'
    ORDER BY u.nome ASC
";
$result_alunos = $conn->query($sql_alunos);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lançamento de Notas - Autocademy</title>
    
    <link rel="stylesheet" href="../css/style4.css">
    <link rel="stylesheet" href="../css/notas.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- SCRIPT DE TROCA DE TURMA -->
    <!-- Ao mudar o select, ele recarrega a página passando o novo ID na URL -->
    <script>
        function mudarTurma(select) {
            const idTurma = select.value;
            window.location.href = 'lancar_notas.php?turma=' + idTurma;
        }
    </script>
</head>

<body class="">

    <aside class="sidebar">
        <div class="logo-area">
            <img src="../imagens/logo.png" alt="Logo">
            <span class="logo-text">Autocademy <small style="font-size: 0.6rem; color: var(--accent-color);">DOCENTE</small></span>
        </div>
        <a href="dashboard_professor.php" class="nav-link"><i class="fa-solid fa-chalkboard-user"></i><span class="nav-text">Painel</span></a>
        <a href="turmas_professor.php" class="nav-link"><i class="fa-solid fa-users"></i><span class="nav-text">Minhas Turmas</span></a>
        <a href="lancar_notas.php" class="nav-link active"><i class="fa-solid fa-file-pen"></i><span class="nav-text">Lançar Notas</span></a>
        <a href="chat.html" class="nav-link"><i class="fa-regular fa-comments"></i><span class="nav-text">Mensagens</span></a>
        <div class="spacer"></div>
        <a href="config.html" class="nav-link"><img src="../imagens/icons8-configurações-150.png"><span class="nav-text">Configurações</span></a>
    </aside>

    <main class="main-wrapper">
        <header class="top-header">
            <div class="search-box">
                <input type="text" placeholder="Buscar aluno..." class="search-input">
                <i class="fa-solid fa-magnifying-glass" style="color: var(--text-secondary)"></i>
            </div>
            <div class="user-profile">
                <span><?php echo $nome_prof; ?></span>
                <div class="avatar-circle" style="background-color: var(--accent-color);"><i class="fa-solid fa-chalkboard-teacher"></i></div>
            </div>
        </header>

        <div class="scroll-content">
            <div class="container notes-container">
                
                <form action="../backend/salvar_notas.php" method="POST">
                    <!-- Envia o ID da turma atual para o backend saber onde salvar -->
                    <input type="hidden" name="id_curso" value="<?php echo $id_curso_selecionado; ?>">

                    <div class="page-header-row">
                        <h1 class="page-title-notes">Diário de Classe</h1>
                        <button type="submit" class="btn-save-notes">
                            <i class="fa-solid fa-floppy-disk"></i> Salvar Notas
                        </button>
                    </div>

                    <?php if (isset($_GET['status']) && $_GET['status'] == 'sucesso'): ?>
                        <div class="success-message">
                            <i class="fa-solid fa-circle-check"></i> Notas atualizadas com sucesso!
                        </div>
                    <?php endif; ?>

                    <!-- SELETOR DE TURMA -->
                    <div class="class-selector-wrapper">
                        <select class="class-selector" onchange="mudarTurma(this)">
                            <?php 
                            if ($result_cursos) {
                                while($curso = $result_cursos->fetch_assoc()) {
                                    // Se o ID desse curso for igual ao da URL, marca como selecionado
                                    $selected = ($curso['id'] == $id_curso_selecionado) ? 'selected' : '';
                                    echo "<option value='{$curso['id']}' $selected>{$curso['nome']}</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="table-card">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Aluno</th>
                                    <th style="width: 100px; text-align: center;">Prova 1</th>
                                    <th style="width: 100px; text-align: center;">Prova 2</th>
                                    <th style="width: 100px; text-align: center;">Trabalho</th>
                                    <th style="width: 100px; text-align: center;">Média</th>
                                    <th style="width: 120px; text-align: center;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result_alunos && $result_alunos->num_rows > 0) {
                                    while($aluno = $result_alunos->fetch_assoc()) {
                                        
                                        $iniciais = strtoupper(substr($aluno['nome'], 0, 2));
                                        
                                        // Busca notas (se não tiver no banco, usa vazio/zero)
                                        $p1 = $aluno['prova1'] ?? '';
                                        $p2 = $aluno['prova2'] ?? '';
                                        $tb = $aluno['trabalho'] ?? '';
                                        
                                        // Cálculo de Média (Visual)
                                        // Considera 0 para cálculo se estiver vazio
                                        $val_p1 = $p1 !== '' ? $p1 : 0;
                                        $val_p2 = $p2 !== '' ? $p2 : 0;
                                        $val_tb = $tb !== '' ? $tb : 0;
                                        
                                        $media = ($val_p1 + $val_p2 + $val_tb) / 3;
                                        $media_formatada = number_format($media, 1, ',', '.');
                                        
                                        $status_class = ($media >= 6) ? 'pass' : 'fail';
                                        $status_text = ($media >= 6) ? 'Aprovado' : 'Reprovado';
                                        
                                        // Se não tiver nenhuma nota lançada, mostra traço
                                        if ($p1 === '' && $p2 === '' && $tb === '') {
                                            $status_class = ''; 
                                            $status_text = '-';
                                            $media_formatada = '-';
                                        }
                                ?>
                                
                                <tr>
                                    <td>
                                        <div class="student-cell">
                                            <div class="table-avatar"><?php echo $iniciais; ?></div>
                                            <div class="student-info">
                                                <strong><?php echo $aluno['nome']; ?></strong>
                                                <small>ID: <?php echo $aluno['id']; ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td style="text-align: center;">
                                        <input type="number" step="0.1" min="0" max="10" class="grade-input" 
                                               name="notas[<?php echo $aluno['id']; ?>][p1]" 
                                               value="<?php echo $p1; ?>">
                                    </td>
                                    <td style="text-align: center;">
                                        <input type="number" step="0.1" min="0" max="10" class="grade-input" 
                                               name="notas[<?php echo $aluno['id']; ?>][p2]" 
                                               value="<?php echo $p2; ?>">
                                    </td>
                                    <td style="text-align: center;">
                                        <input type="number" step="0.1" min="0" max="10" class="grade-input" 
                                               name="notas[<?php echo $aluno['id']; ?>][tb]" 
                                               value="<?php echo $tb; ?>">
                                    </td>
                                    
                                    <td style="text-align: center;" class="average-cell">
                                        <?php echo $media_formatada; ?>
                                    </td>
                                    <td style="text-align: center;">
                                        <span class="status-pill <?php echo $status_class; ?>"><?php echo $status_text; ?></span>
                                    </td>
                                </tr>

                                <?php 
                                    } 
                                } else {
                                    echo "<tr><td colspan='6' style='text-align:center; padding: 40px; color: var(--text-secondary);'>Nenhum aluno encontrado nesta turma.</td></tr>";
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
    
    <!-- Script para atualizar média enquanto digita -->
    <script src="../js/calculo_notas.js"></script>
</body>
</html>