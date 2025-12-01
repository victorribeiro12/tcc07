<?php
session_start();

require_once '../config/db.php';

// Mapa de redirecionamento por tipo de usuário (pode ser movido para um arquivo de config)
const PAGINAS_PERFIL = [
    'aluno'     => '../html/dashboard.php', // Mapeia pelo tipo, não pelo domínio
    'instrutor' => '../html/dashboard_professor.php',
    'default'   => '../index.php',
];

// Mapeamento por domínio (substitui o redirecionamento por tipo quando houver correspondência)
// Adicione ou altere os domínios conforme necessário. Use somente o host (ex: 'instituicao.edu.br').
const DOMINIOS_PAGINAS = [
    // Exemplo: 'instituicao.edu.br' => '../html/dashboard_professor.php',
];
// Se o usuário já está logado, redireciona para a página principal
if (isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}

$erro_login = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = strtolower(trim($_POST['email'] ?? ''));
    $senha = trim($_POST['senha'] ?? '');

    if (empty($email) || empty($senha)) {
        $erro_login = "E-mail e senha são obrigatórios.";
    } else {
        // Usa a conexão PDO definida em config/db.php (variável $pdo)
        try {
            $sql = "SELECT id, nome, senha, tipo_usuario FROM usuarios WHERE email = :email LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':email' => $email]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verifica se o usuário existe e se a senha está correta
            if ($usuario && password_verify($senha, $usuario['senha'])) {
                // Login bem-sucedido: armazena dados na sessão
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nome'] = $usuario['nome'];
                $_SESSION['usuario_tipo'] = $usuario['tipo_usuario']; // Campo 'tipo_usuario' do DB

                // Regenera o ID da sessão para prevenir session fixation
                session_regenerate_id(true);

                // Determina destino por domínio do e-mail primeiro, senão por tipo de usuário
                $dominio = '';
                if (strpos($email, '@') !== false) {
                    $dominio = substr(strrchr($email, '@'), 1);
                }

                if (!empty($dominio) && isset(DOMINIOS_PAGINAS[$dominio])) {
                    $pagina_destino = DOMINIOS_PAGINAS[$dominio];
                } else {
                    $pagina_destino = PAGINAS_PERFIL[$usuario['tipo_usuario']] ?? PAGINAS_PERFIL['default'];
                }

                header("Location: " . $pagina_destino);
                exit();
            } else {
                $erro_login = "E-mail ou senha inválidos.";
            }
        } catch (PDOException $e) {
            error_log("Erro de login (PDO): " . $e->getMessage());
            $erro_login = "Ocorreu um erro no servidor. Tente novamente.";
        }
    }
}
// Se houver erro, exibe a mensagem de erro (ou redireciona de volta para o formulário de login)
if (!empty($erro_login)) {
    // Normalmente, você redirecionaria de volta para o login.php com o erro via SESSION ou GET
    echo "Erro de Login: " . $erro_login . " <a href='../login.php'>Voltar</a>";
}
?>