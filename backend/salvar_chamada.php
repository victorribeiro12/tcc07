<?php
session_start();

// --- CORREÇÃO DA CONEXÃO (BLOCO DE SEGURANÇA) ---
// 1. Define caminhos possíveis para o arquivo de conexão
$caminhos = [
    '../html/conexao.php',  // Caminho padrão (irmão da pasta backend)
    '../conexao.php',       // Caminho alternativo
    'conexao.php'           // Mesmo diretório
];

$arquivo_encontrado = false;

// 2. Tenta incluir o primeiro que achar
foreach ($caminhos as $caminho) {
    if (file_exists($caminho)) {
        include $caminho;
        $arquivo_encontrado = true;
        break;
    }
}

// 3. Backup: Se não achou arquivo, tenta conectar manualmente
if (!$arquivo_encontrado) {
    // Ajuste aqui se sua senha do banco não for vazia
    $conn = new mysqli('localhost', 'root', '', 'autocademy');
}

// 4. Padroniza a variável ($conexao vira $conn)
if (isset($conexao) && !isset($conn)) {
    $conn = $conexao;
}

// 5. Verifica se a conexão está VIVA. Se não, para tudo.
if (!isset($conn) || $conn->connect_error) {
    die("Erro Crítico no Backend: Não foi possível conectar ao banco de dados. " . ($conn->connect_error ?? 'Variável $conn não definida.'));
}
// --- FIM DA CORREÇÃO ---


// --- LÓGICA DE SALVAR CHAMADA ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Recebe os dados do formulário
    $id_curso = isset($_POST['id_curso']) ? intval($_POST['id_curso']) : 0;
    $data_aula = isset($_POST['data_aula']) ? $_POST['data_aula'] : date('Y-m-d');
    
    // Array com IDs dos alunos marcados como presentes
    // Se o checkbox não for marcado, o ID não vem nesse array
    $presentes = $_POST['presenca'] ?? []; 
    
    // Validar se tem curso selecionado
    if ($id_curso > 0) {
        
        // 1. Busca todos os alunos matriculados nessa turma (para saber quem faltou)
        $sql_todos = "SELECT id_usuario FROM inscricoes WHERE id_curso = $id_curso";
        $result = $conn->query($sql_todos);

        if ($result) {
            // Prepara a query de inserção/atualização (UPSERT)
            $stmt = $conn->prepare("INSERT INTO frequencia (id_curso, id_usuario, data_aula, presente) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE presente = VALUES(presente)");
            
            while ($row = $result->fetch_assoc()) {
                $id_aluno = $row['id_usuario'];
                
                // Lógica principal:
                // Verifica se o ID do aluno está na lista de checkboxes ($presentes)
                // Se estiver = 1 (Presente), Se não estiver = 0 (Falta)
                $status = in_array($id_aluno, $presentes) ? 1 : 0;
                
                // s = string, i = int
                // Tipos: id_curso(int), id_aluno(int), data(string), status(int)
                $stmt->bind_param("iisi", $id_curso, $id_aluno, $data_aula, $status);
                $stmt->execute();
            }
            $stmt->close();
        }

        // Redireciona de volta com sucesso
        header("Location: ../html/diario_classe.php?turma=$id_curso&data=$data_aula&status=sucesso");
        exit;
    } else {
        // Erro: ID do curso inválido
        echo "Erro: ID do curso não fornecido.";
        exit;
    }
} else {
    // Se tentar abrir o arquivo direto sem POST
    header("Location: ../html/diario_classe.php");
    exit;
}
?>