<?php
session_start();
require_once '../config/db.php'; 

if (!isset($_SESSION['nome_usuario'])) {
    header("Location: login.php");
    exit;
}

$email_user = "Carregando...";
$telefone_user = "Carregando...";

try {
    if (isset($_SESSION['id_usuario'])) {
        $id_usuario = $_SESSION['id_usuario'];
        
        $stmt = $pdo->prepare("SELECT nome, email, telefone FROM usuarios WHERE id = ?");
        $stmt->execute([$id_usuario]);
        $dados_user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($dados_user) {
            $email_user = $dados_user['email'];
            $telefone_user = !empty($dados_user['telefone']) ? $dados_user['telefone'] : 'Não cadastrado';
        }
    }
} catch (Exception $e) {
    $email_user = "Erro";
    $telefone_user = "Erro";
}

$pasta_imagens = "img/";
$foto_padrao = $pasta_imagens . "padrao.png"; 
$foto_exibir = $foto_padrao;

if (isset($_SESSION['foto_usuario']) && !empty($_SESSION['foto_usuario'])) {
    $caminho_foto_usuario = $pasta_imagens . $_SESSION['foto_usuario'];
    if (file_exists($caminho_foto_usuario)) {
        $foto_exibir = $caminho_foto_usuario;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - Autocademy</title>
    <link rel="stylesheet" href="css/config.css">
</head>
<body>
    <header>
        <div class="topbar-left">
            <div class="logo">
                <img src="img/logo.png" alt="Logo" width="50" height="50">
                <span>Autocademy</span>
            </div>
            
            <a href="index.php" class="active">
                <img src="img/iconhome.png" alt="Início" height="40" width="40" onerror="this.style.display='none'">
                <span>Início</span>
            </a>
            <a href="#"> 
                <img src="img/icons8-livros-96.png" alt="Matérias" height="40" width="40" onerror="this.style.display='none'">
                <span>Matérias</span>
            </a>
            <a href="#"> 
                <img src="img/iconhistorico.png" alt="Histórico" height="40" width="40" onerror="this.style.display='none'">
                <span>Histórico</span>
            </a>
            <div style="flex-grow: 1;"></div>
            <a href="#"> 
                <img src="img/icons8-configurações-150.png" alt="Config" height="40" width="40" onerror="this.style.display='none'">
                <span>Configurações</span>
            </a>
        </div>
    </header>
     
    <?php if(isset($_GET['msg'])): ?>
        <div class="alerta sucesso" onclick="this.style.display='none'"><?php echo htmlspecialchars($_GET['msg']); ?></div>
    <?php endif; ?>
    <?php if(isset($_GET['erro'])): ?>
        <div class="alerta erro" onclick="this.style.display='none'"><?php echo htmlspecialchars($_GET['erro']); ?></div>
    <?php endif; ?>

    <div class="main-container">
        
        <div class="perfil-card">
            
            <div class="perfil-header">
                <img src="<?php echo $foto_exibir . '?' . time(); ?>" alt="Foto" class="foto-perfil-header">
                
                <div class="perfil-info-basica">
                    <h2><?php echo htmlspecialchars($_SESSION['nome_usuario']); ?></h2>
                    
                    <form action="backend/upload_foto.php" method="POST" enctype="multipart/form-data" id="form-upload-foto">
                        <label for="nova_foto" class="btn-link-foto">Alterar foto</label>
                        <input type="file" name="nova_foto" id="nova_foto" accept="image/*" style="display: none;" onchange="document.getElementById('form-upload-foto').submit()">
                    </form>
                </div>
            </div>

            <div class="perfil-formulario">
                
                <div class="campo-grupo">
                    <label>E-mail</label>
                    <input type="text" value="<?php echo htmlspecialchars($email_user); ?>" readonly>
                </div>

                <div class="campo-grupo">
                    <label>Telefone</label>
                    <input type="text" value="<?php echo htmlspecialchars($telefone_user); ?>" readonly>
                </div>

                <div class="campo-grupo">
                    <label>Senha</label>
                    <div class="input-com-botao">
                        <input type="password" value="********" readonly>
                        <button type="button" class="btn-acao-modal" onclick="toggleAreaSenha()">Mudar Senha</button>
                    </div>
                </div>

  
                <div id="areaTrocaSenha" class="area-troca-senha">
                    <h4 style="margin-top: 0; color: #ecececff;">Confirmar Alteração</h4>
                    
                    <form action="backend/processa_senha.php" method="POST" class="form-interno">
                        <div class="campo-grupo">
                            <label>Confirme seu E-mail:</label>
                            <input type="email" name="email_confirma" required placeholder="Ex: joao@email.com">
                        </div>
                        
                        <div class="linha-dupla">
                            <div class="campo-grupo">
                                <label>Senha Atual:</label>
                                <input type="password" name="senha_atual" required>
                            </div>
                            <div class="campo-grupo">
                                <label>Nova Senha:</label>
                                <input type="password" name="nova_senha" required>
                            </div>
                        </div>
                        
                        <div class="botoes-acao-senha">
                            <button type="button" class="btn-cancelar-simples" onclick="toggleAreaSenha()">Cancelar</button>
                            <button type="submit" class="btn-salvar-senha">Salvar Nova Senha</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script>
        function toggleAreaSenha() {
            var area = document.getElementById("areaTrocaSenha");

            area.classList.toggle("aberto");
        }
        
        const urlParams = new URLSearchParams(window.location.search);
        if(urlParams.has('erro')) {
            setTimeout(() => {
                toggleAreaSenha();
            }, 100);
        }
    </script>
</body>
</html>