<?php
header('Content-Type: application/json');

$host = 'localhost';
$dbname = 'autocademy';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    $nome = trim($input['nome'] ?? '');
    $email = trim($input['email'] ?? '');
    $senha = $input['senha'] ?? '';
    $cpf = $input['cpf'] ?? '';
    $data_nascimento = $input['data_nascimento'] ?? '';
    $telefone = $input['telefone'] ?? '';
    $cep = $input['cep'] ?? '';
    $endereco = trim($input['endereco'] ?? '');
    $nome_responsavel_1 = trim($input['nome_responsavel_1'] ?? '');
    $telefone_responsavel_1 = $input['telefone_responsavel_1'] ?? '';
    $nome_responsavel_2 = trim($input['nome_responsavel_2'] ?? '');
    $telefone_responsavel_2 = $input['telefone_responsavel_2'] ?? '';
    
    if (empty($nome) || empty($email) || empty($senha) || empty($cpf) || empty($data_nascimento) || empty($telefone)) {
        echo json_encode([
            'success' => false,
            'message' => 'Por favor, preencha todos os campos obrigatórios!'
        ]);
        exit;
    }
    
    $dominiosValidos = ['@senaimgaluno.com.br', '@docente.edu.br', '@gmail.com'];
    $dominioValido = false;
    foreach ($dominiosValidos as $dominio) {
        if (substr($email, -strlen($dominio)) === $dominio) {
            $dominioValido = true;
            break;
        }
    }
    
    if (!$dominioValido) {
        echo json_encode([
            'success' => false,
            'message' => 'Domínio de email não autorizado!'
        ]);
        exit;
    }
    
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email");
    $stmt->execute(['email' => $email]);
    
    if ($stmt->fetch()) {
        echo json_encode([
            'success' => false,
            'message' => 'Este email já está cadastrado!'
        ]);
        exit;
    }
    
    $senhaHash = password_hash($senha, PASSWORD_BCRYPT);
    
    $sql = "INSERT INTO usuarios (
        nome, email, senha, cpf, data_nascimento, telefone, cep, endereco,
        nome_responsavel_1, telefone_responsavel_1, nome_responsavel_2, telefone_responsavel_2
    ) VALUES (
        :nome, :email, :senha, :cpf, :data_nascimento, :telefone, :cep, :endereco,
        :nome_responsavel_1, :telefone_responsavel_1, :nome_responsavel_2, :telefone_responsavel_2
    )";
    
    $stmt = $pdo->prepare($sql);
    
    $resultado = $stmt->execute([
        'nome' => $nome,
        'email' => $email,
        'senha' => $senhaHash,
        'cpf' => $cpf,
        'data_nascimento' => $data_nascimento,
        'telefone' => $telefone,
        'cep' => $cep,
        'endereco' => $endereco,
        'nome_responsavel_1' => $nome_responsavel_1 ?: null,
        'telefone_responsavel_1' => $telefone_responsavel_1 ?: null,
        'nome_responsavel_2' => $nome_responsavel_2 ?: null,
        'telefone_responsavel_2' => $telefone_responsavel_2 ?: null
    ]);
    
    if ($resultado) {
        echo json_encode([
            'success' => true,
            'message' => 'Cadastro realizado com sucesso!'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Erro ao cadastrar usuário!'
        ]);
    }
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erro no banco: ' . $e->getMessage()
    ]);
}
?>