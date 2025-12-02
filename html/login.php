<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar - Autocademy</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>

        .msg-erro {
            background-color: #ffdddd;
            color: #d8000c;
            border: 1px solid #d8000c;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            font-size: 14px;
            text-align: center;
        }
    </style>
</head>
<body>
    <main class="main-login-cadastro">

        <div class="form-wrapper">
            <h2>Entrar</h2>

            <!-- Bloco que exibe o erro se houver -->
            <?php if(isset($_GET['erro'])): ?>
                <div class="msg-erro">
                    <?php echo htmlspecialchars($_GET['erro']); ?>
                </div>
            <?php endif; ?>

            <form action="backend/processa_login.php" method="post">
                
                <label for="email">E-mail Institucional</label>
                <input type="email" id="email" name="email" required placeholder="Digite seu e-mail (@senaimgaluno ou @docente)">

                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" required placeholder="Digite sua senha:">

                <button type="submit">Acessar Plataforma</button>

                <!-- Link de cadastro removido conforme solicitado -->
            </form>

        </div>
    </main>
    
</body>
</html>