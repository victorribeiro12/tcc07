<?php
session_start();
if (!isset($_SESSION['nome_usuario'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mudar Senha - Autocademy</title>
    <link rel="stylesheet" href="css/config.css">
</head>
<body>
    <header>
        <div class="topbar-left">
            <div class="logo">
                <img src="img/logo.png" alt="Logo" width="50" height="50">
                <span>Autocademy</span>
            </div>
            <a href="perfil.php">Voltar ao Perfil</a>
        </div>
    </header>

    <div class="container-senha">
        <div class="box-senha">
            <h2>Alterar Senha</h2>
            <p style="margin-bottom: 20px; color: #666; font-size: 0.9em;">
                Por segurança, confirme seu e-mail e senha atual.
            </p>

            <?php if(isset($_GET['erro'])): ?>
                <div class="erro-msg">
                    <?php echo htmlspecialchars($_GET['erro']); ?>
                </div>
            <?php endif; ?>

            <form action="backend/processa_senha.php" method="POST">
                
                <div class="campo-form">
                    <label for="email_confirma">Confirme seu E-mail:</label>
                    <input type="email" name="email_confirma" required placeholder="Digite seu e-mail cadastrado">
                </div>

                <div class="campo-form">
                    <label for="senha_atual">Senha Atual:</label>
                    <input type="password" name="senha_atual" required placeholder="Sua senha antiga">
                </div>

                <div class="campo-form">
                    <label for="nova_senha">Nova Senha:</label>
                    <input type="password" name="nova_senha" required placeholder="Crie uma nova senha">
                </div>

                <button type="submit" class="btn-confirmar">Salvar Nova Senha</button>
            </form>

            <a href="perfil.php" class="btn-voltar">Cancelar e voltar</a>
        </div>
    </div>
</body>
</html>