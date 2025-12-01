<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar - Autocademy</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <main class="main-login-cadastro">
        <div class="form-wrapper">
            <h2>Entrar</h2>

                        <form action="../backend/processa_login.php" method="post" id="loginForm"> 
                
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" required placeholder="Digite seu e-mail:">

                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" required placeholder="Digite sua senha:">

                <button type="submit">Enviar</button>

                <p>Não tem uma conta? <a href="cadastro.php">Crie uma aqui!</a></p>
            </form>
        </div>
    </main>
    
        <script>
        // Mapeamento de Domínio para Página de Destino
        // Note que adicionei o caminho "../backend/" para manter a consistência com o original
        const destinoPorDominio = {
            '@senaimgaluno.com.br': '../html/dashboard.php',
            '@docente.edu.br': '../html/dashboard_professor.php',
            '@gmail.com': '../html/paginavisitante.php' 
        };

        // URL padrão (fallback) se o domínio não for mapeado
        const urlPadrao = '../backend/processa_login.php'; 

        const form = document.getElementById('loginForm');
        const emailInput = document.getElementById('email');

        form.addEventListener('submit', function(event) {
            // 1. Impede o envio padrão do formulário
            event.preventDefault(); 
            
            const email = emailInput.value.toLowerCase();
            let novoDestino = urlPadrao;

            // 2. Verifica o domínio no e-mail
            for (const dominio in destinoPorDominio) {
                if (email.endsWith(dominio)) {
                    novoDestino = destinoPorDominio[dominio];
                    break;
                }
            }

            // 3. Altera o atributo 'action' e envia
            form.action = novoDestino;
            form.submit();
        });
    </script>
</body>
</html>