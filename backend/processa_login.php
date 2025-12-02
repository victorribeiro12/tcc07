<?php
session_start();
require_once '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $senha_postada = $_POST['senha'];

    if (empty($email) || empty($senha_postada)) {
        header("Location: ../login.php?erro=Preencha e-mail e senha.");
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($senha_postada, $usuario['senha'])) {
            
            // --- NOVA LÓGICA DE DOMÍNIO ---
            
            // 1. Separa o que vem depois do @
            $partes_email = explode('@', $email);
            $dominio = end($partes_email); // Pega a última parte (ex: senaimgaluno.com.br)
            
            // Normaliza para letras minúsculas para evitar erros (Ex: Aluno.com)
            $dominio = strtolower($dominio);

            // 2. Define para onde vai baseado no domínio
            $redirecionamento = "";

            if ($dominio === 'senaimgaluno.com.br') {
                $redirecionamento = "../dashboard.php";
            } elseif ($dominio === 'docente.edu.br') {
                $redirecionamento = "../dashboard_professor.php";
            } else {
                // SE O DOMÍNIO NÃO FOR NENHUM DESSES, BLOQUEIA
                header("Location: ../login.php?erro=Acesso negado: Domínio de e-mail não autorizado para esta plataforma.");
                exit;
            }

            // 3. Se passou no filtro, salva a sessão
            $_SESSION['loggedin'] = true;
            $_SESSION['id_usuario'] = $usuario['id'];
            $_SESSION['nome_usuario'] = $usuario['nome'];
            $_SESSION['foto_usuario'] = $usuario['foto_perfil'];
            // Opcional: Salvar o tipo de acesso na sessão caso precise usar no front
            $_SESSION['tipo_acesso'] = ($dominio === 'docente.edu.br') ? 'professor' : 'aluno';

            // 4. Redireciona para a página correta definida acima
            header("Location: " . $redirecionamento);
            exit;
            
        } else {
            header("Location: ../login.php?erro=E-mail ou senha inválidos.");
            exit;
        }

    } catch (PDOException $e) {
        header("Location: ../login.php?erro=Erro no banco de dados.");
        exit;
    }
} else {
    header("Location: ../login.php");
    exit;
}
?>