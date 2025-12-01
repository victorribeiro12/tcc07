    <?php
session_start();

// --- CORREÇÃO DA CONEXÃO (ROBUSTA) ---
// 1. Tenta carregar o arquivo de conexão (conexao.php ou db.php)
if (file_exists('conexao.php')) {
    include 'conexao.php';
} elseif (file_exists('../config/db.php')) {
    include '../config/db.php';
} else {
    // Para tudo se não achar o arquivo
    die("Erro Crítico: Arquivo de conexão com o banco não encontrado. Verifique se 'conexao.php' está na pasta html.");
}

// 2. Garante compatibilidade de variáveis ($conexao vs $conn)
if (isset($conexao) && !isset($conn)) {
    $conn = $conexao;
}

// 3. Verifica se a conexão está ativa antes de continuar
if (!isset($conn) || $conn->connect_error) {
    die("Erro de Conexão: Não foi possível conectar ao banco de dados.");
}
// --- FIM DA CORREÇÃO ---

$nome_prof = $_SESSION['nome_usuario'] ?? 'Professor';

// 1. Verifica qual turma foi selecionada (Pega o ID da URL ou usa 1 como padrão)
$id_curso_selecionado = isset($_GET['turma']) ? intval($_GET['turma']) : 1;

// 2. Busca lista de cursos para o "Select" (dropdown)
$sql_cursos = "SELECT * FROM cursos";
$result_cursos = $conn->query($sql_cursos);

// 3. Busca ALUNOS e NOTAS dessa turma específica
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/notas.css">    
    <!-- Script para mudar a turma ao selecionar no dropdown -->
    <script>
        function mudarTurma(select) {
            window.location.href = 'lancar_notas.php?turma=' + select.value;
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
            <div class="container">
                
                <!-- FORMULÁRIO GERAL (Envolve toda a tabela) -->
                <form action="../backend/salvar_notas.php" method="POST">
                    <!-- Campo oculto para enviar o ID do curso -->
                    <input type="hidden" name="id_curso" value="<?php echo $id_curso_selecionado; ?>">

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h1 class="page-title" style="margin-bottom: 0;">Diário de Classe</h1>
                        
                        <!-- BOTÃO DE SALVAR -->
                        <button type="submit" class="btn-save">
                            <i class="fa-solid fa-floppy-disk"></i> Salvar Notas
                        </button>
                    </div>

                    <!-- SELETOR DE TURMA -->
                    <select class="class-selector" onchange="mudarTurma(this)">
                        <?php 
                        if ($result_cursos) {
                            while($curso = $result_cursos->fetch_assoc()) {
                                $selected = ($curso['id'] == $id_curso_selecionado) ? 'selected' : '';
                                echo "<option value='{$curso['id']}' $selected>{$curso['nome']}</option>";
                            }
                        }
                        ?>
                    </select>

                    <?php if (isset($_GET['status']) && $_GET['status'] == 'sucesso'): ?>
                        <div style="background: rgba(16, 185, 129, 0.2); color: #10b981; padding: 10px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #10b981;">
                            <i class="fa-solid fa-check-circle"></i> Notas salvas com sucesso!
                        </div>
                    <?php endif; ?>

                    <!-- TABELA DE NOTAS -->
                    <div class="settings-card" style="padding: 0; border: none;">
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
                                        
                                        // Pega as iniciais do nome para o avatar
                                        $iniciais = strtoupper(substr($aluno['nome'], 0, 2));
                                        
                                        // Calcula média para exibir (visual apenas)
                                        $p1 = $aluno['prova1'] ?? 0;
                                        $p2 = $aluno['prova2'] ?? 0;
                                        $tb = $aluno['trabalho'] ?? 0;
                                        
                                        // Média simples (soma / 3). Se faltar nota, considera 0.
                                        $media = ($p1 + $p2 + $tb) / 3;
                                        $media_formatada = number_format($media, 1, ',', '.');
                                        
                                        // Status
                                        $status_class = ($media >= 6) ? 'pass' : 'fail';
                                        $status_text = ($media >= 6) ? 'Aprovado' : 'Reprovado';
                                        if ($p1 == 0 && $p2 == 0 && $tb == 0) {
                                            $status_class = ''; 
                                            $status_text = '-';
                                            $media_formatada = '-';
                                        }
                                ?>
                                
                                <tr>
                                    <td>
                                        <div class="student-cell">
                                            <div class="table-avatar"><?php echo $iniciais; ?></div>
                                            <div>
                                                <strong><?php echo $aluno['nome']; ?></strong><br>
                                                <small style="color: var(--text-secondary);">ID: <?php echo $aluno['id']; ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <!-- INPUTS DE NOTA (ARRAY) -->
                                    <!-- Name format: notas[ID_ALUNO][TIPO] -->
                                    <td style="text-align: center;">
                                        <input type="number" step="0.1" min="0" max="10" class="grade-input" 
                                               name="notas[<?php echo $aluno['id']; ?>][p1]" 
                                               value="<?php echo $aluno['prova1']; ?>">
                                    </td>
                                    <td style="text-align: center;">
                                        <input type="number" step="0.1" min="0" max="10" class="grade-input" 
                                               name="notas[<?php echo $aluno['id']; ?>][p2]" 
                                               value="<?php echo $aluno['prova2']; ?>">
                                    </td>
                                    <td style="text-align: center;">
                                        <input type="number" step="0.1" min="0" max="10" class="grade-input" 
                                               name="notas[<?php echo $aluno['id']; ?>][tb]" 
                                               value="<?php echo $aluno['trabalho']; ?>">
                                    </td>
                                    
                                    <td style="text-align: center; font-weight: bold; color: var(--accent-color);">
                                        <?php echo $media_formatada; ?>
                                    </td>
                                    <td style="text-align: center;">
                                        <span class="status-pill <?php echo $status_class; ?>"><?php echo $status_text; ?></span>
                                    </td>
                                </tr>

                                <?php 
                                    } // Fim While
                                } else {
                                    echo "<tr><td colspan='6' style='text-align:center; padding: 30px;'>Nenhum aluno inscrito nesta turma ainda.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </form>

            </div>
        </div>
    </main>
<script src="../javascript.js/notas1.js"></script>
    <script src="tema.js"></script>
</body>
</html>