<?php
// ARQUIVO: ajax_buscar_alunos.php
session_start();

// 1. Conexão (Mesma lógica do seu sistema)
if (file_exists('conexao.php')) {
    include 'conexao.php';
} elseif (file_exists('../config/db.php')) {
    include '../config/db.php';
} else {
    die("Erro: Arquivo de conexão não encontrado.");
}

if (isset($conexao) && !isset($conn)) $conn = $conexao;

// 2. Recebe o ID da turma via AJAX
$id_curso_selecionado = isset($_GET['turma']) ? intval($_GET['turma']) : 0;

// 3. Query (Busca os alunos dessa turma específica)
$sql_alunos = "
    SELECT u.id, u.nome, u.foto_perfil, n.prova1, n.prova2, n.trabalho 
    FROM usuarios u
    JOIN inscricoes i ON u.id = i.id_usuario
    LEFT JOIN notas n ON u.id = n.id_usuario AND n.id_curso = $id_curso_selecionado
    WHERE i.id_curso = $id_curso_selecionado AND u.email LIKE '%@senaimgaluno%'
    ORDER BY u.nome ASC
";

$result_alunos = $conn->query($sql_alunos);

// 4. Gera apenas as linhas da tabela (TR)
if ($result_alunos && $result_alunos->num_rows > 0) {
    while($aluno = $result_alunos->fetch_assoc()) {
        
        $iniciais = strtoupper(substr($aluno['nome'], 0, 2));
        
        // Cálculos
        $p1 = $aluno['prova1'] ?? 0;
        $p2 = $aluno['prova2'] ?? 0;
        $tb = $aluno['trabalho'] ?? 0;
        $media = ($p1 + $p2 + $tb) / 3;
        
        // Formatação
        $media_formatada = number_format($media, 1, ',', '.');
        $status_class = ($media >= 6) ? 'pass' : 'fail';
        $status_text = ($media >= 6) ? 'Aprovado' : 'Reprovado';
        
        // Se tudo for zero, mostra traço
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
    }
} else {
    echo "<tr><td colspan='6' style='text-align:center; padding: 30px;'>Nenhum aluno inscrito nesta turma.</td></tr>";
}
?>