<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar - Autocademy</title>
    <link rel="stylesheet" href="./css/cadastro.css">
</head>
<body>
    <main class="main-login-cadastro">



        <div class="form-wrapper">
            <h2>Entrar</h2>

            <form action="backend/processa_login.php" method="post">
                
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" required placeholder="Digite seu e-mail:">

                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" required placeholder="Digite sua senha:">

                <button type="submit">Enviar</button>

                <p>Não tem uma conta? <a href="cadastro.php">Crie uma aqui!</a></p>
            </form>


        </div>
    </main>
    
</body>
</html>