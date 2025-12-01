<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php?erro=Voce precisa estar logado para se inscrever.");
    exit;
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id_curso = $_GET['id'];
$id_usuario = $_SESSION['id_usuario'];

try {
    $stmt_curso = $pdo->prepare("SELECT nome FROM cursos WHERE id = ?");
    $stmt_curso->execute([$id_curso]);
    $curso = $stmt_curso->fetch(PDO::FETCH_ASSOC);

    if (!$curso) {
        header("Location: index.php?erro=Curso nao encontrado");
        exit;
    }

    $stmt_usuario = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt_usuario->execute([$id_usuario]);
    $usuario = $stmt_usuario->fetch(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erro ao buscar dados: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscrição - <?php echo htmlspecialchars($curso['nome'] ?? 'Curso'); ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <main class="main-login-cadastro">
        <img src="img/image.png" alt="Logo Fundo" class="form-bg-logo">
        
        <div class="form-wrapper form-inscricao">
            <h2>Inscrição<br><span style="color: rgba(255, 82, 82, 0.7);"><?php echo htmlspecialchars($curso['nome'] ?? 'Curso'); ?></span></h2>
        
            <form action="backend/processa_inscricao.php" method="post">
                <input type="hidden" name="id_curso" value="<?php echo $id_curso; ?>">

                <div class="form-row">
                    <div class="form-group full-width">
                        <label for="nome">Nome Completo</label>
                        <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($usuario['nome'] ?? ''); ?>" required placeholder="Digite seu nome:">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email'] ?? ''); ?>" required placeholder="Digite seu e-mail:">
                    </div>
                    <div class="form-group">
                        <label for="telefone">Telefone</label>
                        <input type="text" id="telefone" name="telefone" value="<?php echo htmlspecialchars($usuario['telefone'] ?? ''); ?>" required placeholder="Digite seu número:">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="endereco">Endereço</label>
                        <input type="text" id="endereco" name="endereco" value="<?php echo htmlspecialchars($usuario['endereco'] ?? ''); ?>" placeholder="Digite seu endereço:">
                    </div>
                    <div class="form-group">
                        <label for="cep">CEP</label>
                        <input type="text" id="cep" name="cep" value="<?php echo htmlspecialchars($usuario['cep'] ?? ''); ?>" placeholder="Digite seu CEP:">
                    </div>
                    <div class="form-group">
                        <label for="cpf">CPF</label>
                        <input type="text" id="cpf" name="cpf" value="<?php echo htmlspecialchars($usuario['cpf'] ?? ''); ?>" placeholder="Digite seu CPF:">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="data_nascimento">Data de Nascimento</label>
                        <input type="date" id="data_nascimento" name="data_nascimento" value="<?php echo htmlspecialchars($usuario['data_nascimento'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="nome_responsavel_1">Nome do Responsável 1</label>
                        <input type="text" id="nome_responsavel_1" name="nome_responsavel_1" value="<?php echo htmlspecialchars($usuario['nome_responsavel_1'] ?? ''); ?>" placeholder="Digite o nome do responsável:">
                    </div>
                    <div class="form-group">
                        <label for="nome_responsavel_2">Nome do Responsável 2</label>
                        <input type="text" id="nome_responsavel_2" name="nome_responsavel_2" value="<?php echo htmlspecialchars($usuario['nome_responsavel_2'] ?? ''); ?>" placeholder="Digite o nome do responsável:">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="telefone_responsavel_1">Telefone do Responsável 1</label>
                        <input type="text" id="telefone_responsavel_1" name="telefone_responsavel_1" value="<?php echo htmlspecialchars($usuario['telefone_responsavel_1'] ?? ''); ?>" placeholder="Digite o telefone do responsável:">
                    </div>
                    <div class="form-group">
                        <label for="telefone_responsavel_2">Telefone do Responsável 2</label>
                        <input type="text" id="telefone_responsavel_2" name="telefone_responsavel_2" value="<?php echo htmlspecialchars($usuario['telefone_responsavel_2'] ?? ''); ?>" placeholder="Digite o telefone do responsável:">
                    </div>
                </div>

                <button type="submit" style="background-color: rgb(196, 40, 1)">Inscrever-se</button>
            </form>


        </div>
    </main>
</body>
</html>