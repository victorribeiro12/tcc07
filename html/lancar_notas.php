<?php
session_start();

// --- CORREÇÃO DA CONEXÃO ---
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

if (!isset($conn) || $conn->connect_error) {
    die("Erro de Conexão: Não foi possível conectar ao banco de dados.");
}

$nome_prof = $_SESSION['nome_usuario'] ?? 'Professor';

// Turma inicial (padrão 1 se não vier na URL)
$id_curso_selecionado = isset($_GET['turma']) ? intval($_GET['turma']) : 1;

// Busca cursos para o Dropdown
$sql_cursos = "SELECT * FROM cursos";
$result_cursos = $conn->query($sql_cursos);

// Busca alunos INICIAIS (para carregar a página na primeira vez)
// Nota: A lógica duplicada aqui é necessária para o primeiro carregamento (SSR)
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
    
    <script>
        function mudarTurma(select) {
            var idTurma = select.value;
            var tbody = document.getElementById('corpo-tabela');
            var inputHidden = document.getElementById('input-id-curso');

            // 1. Atualiza o ID do curso no formulário (para quando clicar em Salvar)
            inputHidden.value = idTurma;

            // 2. Mostra mensagem de carregamento
            tbody.innerHTML = '<tr><td colspan="6" style="text-align:center; padding:20px; color: var(--text-secondary);"><i class="fa-solid fa-spinner fa-spin"></i> Carregando alunos...</td></tr>';

            // 3. Busca os dados no arquivo auxiliar
            fetch('ajax_buscar_alunos.php?turma=' + idTurma)
                .then(response => response.text())
                .then(html => {
                    // Substitui o conteúdo da tabela
                    tbody.innerHTML = html;
                })
                .catch(error => {
                    console.error('Erro:', error);
                    tbody.innerHTML = '<tr><td colspan="6" style="text-align:center; color:red;">Erro ao carregar turma. Tente novamente.</td></tr>';
                });
        }
    </script>
    <style>
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
        display: inline-flex; /* Mantém alinhado */
        align-items: center;
        gap: 10px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-save:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(229, 57, 53, 0.5);
        filter: brightness(1.1);
    }

    .btn-save:active {
        transform: scale(0.98);
        box-shadow: 0 2px 10px rgba(229, 57, 53, 0.3);
    }

    /* Ajuste para o ícone ficar no tamanho certo */
    .btn-save i {
        font-size: 1.1rem;
    }
</style>
</head>

<body class="">

    <aside class="sidebar">
        <div class="logo-area">
            <img src="../imagens/logo.png" alt="Logo">
            <span class="logo-text">Autocademy <small style="font-size: 0.6rem; color: var(--accent-color);">DOCENTE</small></span>
        </div>
        <a href="dashboard_professor.php" class="nav-link"><i class="fa-solid fa-chalkboard-user"></i><span class="nav-text">Painel</span></a>
       
        
        <a href="lancar_notas.php" class="nav-link active"><i class="fa-solid fa-file-pen"></i><span class="nav-text">Lançar Notas</span></a>
        
<a href="../html/diario_classe.php" class="nav-link">
            <img src="../imagens/iconagenda.png" alt="Config">
            <span class="nav-text">Diário das Turmas</span>
        </a>
        <a href="turmas_professor.php" class="nav-link"><i class="fa-solid fa-users"></i><span class="nav-text">Minhas Turmas</span></a>
        
        <a href="chatprof.html" class="nav-link"><i class="fa-regular fa-comments"></i><span class="nav-text">Mensagens</span></a>
    

        <div class="spacer"></div>

        <a href="configpro.php" class="nav-link"><img src="../imagens/icons8-configurações-150.png"><span class="nav-text">Configurações</span></a>
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
                
                <form action="../backend/salva_notas.php" method="POST">
                    
                    <input type="hidden" id="input-id-curso" name="id_curso" value="<?php echo $id_curso_selecionado; ?>">

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h1 class="page-title" style="margin-bottom: 0;">Notas da Classe</h1>
                        
                        <button type="submit" class="btn-save">
                            <i class="fa-solid fa-floppy-disk"></i> Salvar Notas
                        </button>
                    </div>

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
                            <tbody id="corpo-tabela">
                                <?php
                                // Carregamento inicial (PHP Padrão)
                                if ($result_alunos && $result_alunos->num_rows > 0) {
                                    while($aluno = $result_alunos->fetch_assoc()) {
                                        $iniciais = strtoupper(substr($aluno['nome'], 0, 2));
                                        
                                        $p1 = $aluno['prova1'] ?? 0;
                                        $p2 = $aluno['prova2'] ?? 0;
                                        $tb = $aluno['trabalho'] ?? 0;
                                        $media = ($p1 + $p2 + $tb) / 3;
                                        $media_formatada = number_format($media, 1, ',', '.');
                                        
                                        $status_class = ($media >= 6) ? 'pass' : 'fail';
                                        $status_text = ($media >= 6) ? 'Aprovado' : 'Reprovado';
                                        
                                        if ($p1 == 0 && $p2 == 0 && $tb == 0) {
                                            $status_class = ''; $status_text = '-'; $media_formatada = '-';
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
                                            <td style="text-align: center;">
                                                <input type="number" step="0.1" min="0" max="10" class="grade-input" 
                                                    name="notas[<?php echo $aluno['id']; ?>][p1]" value="<?php echo $aluno['prova1']; ?>">
                                            </td>
                                            <td style="text-align: center;">
                                                <input type="number" step="0.1" min="0" max="10" class="grade-input" 
                                                    name="notas[<?php echo $aluno['id']; ?>][p2]" value="<?php echo $aluno['prova2']; ?>">
                                            </td>
                                            <td style="text-align: center;">
                                                <input type="number" step="0.1" min="0" max="10" class="grade-input" 
                                                    name="notas[<?php echo $aluno['id']; ?>][tb]" value="<?php echo $aluno['trabalho']; ?>">
                                            </td>
                                            <td style="text-align: center; font-weight: bold; color: var(--accent-color);">
                                                <?php echo $media_formatada; ?>
                                            </td>
                                            <td style="text-align: center;">
                                                <span class="status-pill <?php echo $status_class; ?>"><?php echo $status_text; ?></span>
                                            </td>
                                        </tr>
                                    <?php 
                                    } 
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
    <script src="../javascript/notas1.js"></script>
    <script src="tema.js"></script>
</body>
</html>