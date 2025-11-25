<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Autocademy</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <main class="main-login-cadastro">



        <div class="form-wrapper">
            <h2>Cadastro</h2>
        
            <form action="backend/processa_cadastro.php" method="post">
                <label for="nome">Nome Completo</label>
                <input type="text" id="nome" name="nome" required placeholder="Digite seu nome:">

                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" required placeholder="Digite seu e-mail:">

                <label for="telefone">Telefone</label>
                <input type="text" id="telefone" name="telefone" required placeholder="Digite seu número:">

                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" required placeholder="Crie uma senha:">

                <button type="submit">Cadastrar</button>

                <p>Já tem uma conta? <a href="login.php">Entre aqui!</a></p>
            </form>

           
        </div>
    </main>
    
</body>
</html>