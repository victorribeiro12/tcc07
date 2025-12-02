<?php
session_start();

// --- 1. CONEXÃO DIRETA (SEM INCLUIR ARQUIVOS EXTERNOS) ---
// Isso elimina o erro de "Variável inexistente" pois criamos ela aqui mesmo.
$host = 'localhost';
$usuario = 'root';
$senha = '';        // Geralmente vazio no XAMPP/Laragon. Se tiver senha, coloque aqui.
$banco = 'autocademy';

$conn = new mysqli($host, $usuario, $senha, $banco);

// Verifica se conectou mesmo
if ($conn->connect_error) {
    die("Erro Crítico de Conexão: " . $conn->connect_error);
}

// Garante suporte a acentos (utf8)
$conn->set_charset("utf8mb4");

// --- 2. RECEBE OS DADOS DO FORMULÁRIO ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Pega o ID do curso e as notas
    $id_curso = isset($_POST['id_curso']) ? intval($_POST['id_curso']) : 0;
    $notas_recebidas = $_POST['notas'] ?? []; 

    // Verifica se tem dados para salvar
    if ($id_curso > 0 && count($notas_recebidas) > 0) {
        
        // Prepara a query (Inserir ou Atualizar)
        $sql = "INSERT INTO notas (id_usuario, id_curso, prova1, prova2, trabalho) 
                VALUES (?, ?, ?, ?, ?) 
                ON DUPLICATE KEY UPDATE 
                prova1 = VALUES(prova1), 
                prova2 = VALUES(prova2), 
                trabalho = VALUES(trabalho)";
        
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Erro na preparação da query: " . $conn->error);
        }

        foreach ($notas_recebidas as $id_aluno => $campos) {
            
            // Tratamento dos valores (Troca vírgula por ponto, evita erro se vazio)
            $p1 = isset($campos['p1']) && $campos['p1'] !== '' ? floatval(str_replace(',', '.', $campos['p1'])) : 0.0;
            $p2 = isset($campos['p2']) && $campos['p2'] !== '' ? floatval(str_replace(',', '.', $campos['p2'])) : 0.0;
            $tb = isset($campos['tb']) && $campos['tb'] !== '' ? floatval(str_replace(',', '.', $campos['tb'])) : 0.0;
            $id_aluno = intval($id_aluno);

            // Vincula e executa
            $stmt->bind_param("iiddd", $id_aluno, $id_curso, $p1, $p2, $tb);
            
            if (!$stmt->execute()) {
                // Se der erro em um aluno específico, mostra o erro (para debug)
                echo "Erro ao salvar aluno ID $id_aluno: " . $stmt->error;
            }
        }

        $stmt->close();
        $conn->close();

        // --- 3. SUCESSO! ---
        // Redireciona de volta para a tabela de notas
        header("Location: ../html/lancar_notas.php?turma=$id_curso&status=sucesso");
        exit;

    } else {
        // Se chegou aqui, é porque o array de notas estava vazio ou ID do curso era 0
        die("Erro: Nenhum dado de nota foi recebido. Verifique se digitou as notas.");
    }

} else {
    // Se tentar acessar o arquivo direto pelo navegador sem clicar em Salvar
    header("Location: ../html/lancar_notas.php");
    exit;
}
?>