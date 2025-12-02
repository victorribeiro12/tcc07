<?php
session_start();

// --- CONEXÃO ROBUSTA ---
// Tenta achar a conexão onde quer que ela esteja
if (file_exists('conexao.php')) include 'conexao.php';
elseif (file_exists('../config/db.php')) include '../config/db.php';
else {
    // Fallback de conexão
    $conn = new mysqli('localhost', 'root', '', 'autocademy');
}

// Garante compatibilidade
if (isset($conexao) && !isset($conn)) $conn = $conexao;

// Recupera dados da sessão (se não tiver, usa dados fictícios para visualização)
$nome_prof = $_SESSION['nome_usuario'] ?? 'Professor';
$email_prof = $_SESSION['email_usuario'] ?? 'professor@senaimgaluno.br';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurações - Docente</title>
    
    <link rel="stylesheet" href="../css/style4.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Layout Grid Responsivo */
        .config-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
            padding-bottom: 40px;
        }

        /* Cartões de Configuração */
        .settings-card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 30px;
            box-shadow: var(--shadow);
            transition: transform 0.2s, border-color 0.2s, box-shadow 0.2s;
        }
        
        .settings-card:hover {
            border-color: var(--accent-color);
            box-shadow: var(--shadow-hover);
        }

        /* Cabeçalho do Card */
        .card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border-color);
        }

        .card-header i {
            font-size: 1.4rem;
            color: var(--accent-color);
            background: rgba(229, 57, 53, 0.1);
            padding: 10px;
            border-radius: 10px;
        }

        .card-header h3 {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
        }

        /* Área da Foto de Perfil */
        .profile-upload-area {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 25px;
        }

        .large-avatar {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent-color), #b71c1c);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            font-weight: 700;
            border: 4px solid var(--bg-body);
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        }

        .upload-actions {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        /* Botão de Upload Customizado */
        .btn-upload {
            background-color: var(--bg-hover);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 0.9rem;
            cursor: pointer;
            transition: 0.2s;
            text-align: center;
        }
        .btn-upload:hover { background-color: var(--border-color); }
        .btn-upload input { display: none; }

        /* Inputs do Formulário */
        .form-group { margin-bottom: 20px; }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-secondary);
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            background-color: var(--bg-input);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            color: var(--text-primary);
            font-size: 0.95rem;
            outline: none;
            transition: 0.2s;
        }
        
        .form-input:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(229, 57, 53, 0.15);
        }

        /* Botões de Tema */
        .theme-selector {
            display: flex;
            gap: 15px;
        }

        .theme-option {
            flex: 1;
            background-color: var(--bg-input);
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 20px;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            transition: 0.2s;
        }

        .theme-option i { font-size: 1.8rem; color: var(--text-secondary); }
        .theme-option span { font-size: 0.9rem; font-weight: 600; color: var(--text-secondary); }

        .theme-option:hover { transform: translateY(-3px); background-color: var(--bg-hover); }

        .theme-option.active {
            border-color: var(--accent-color);
            background-color: rgba(229, 57, 53, 0.05);
        }
        .theme-option.active i, .theme-option.active span { color: var(--accent-color); }

        /* Botão Salvar (Estilo Premium) */
        .btn-submit {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--accent-color), #c62828);
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: 0.2s;
            box-shadow: 0 4px 15px rgba(229, 57, 53, 0.3);
            margin-top: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(229, 57, 53, 0.4);
            filter: brightness(1.1);
        }
    </style>
</head>

<body>

    <aside class="sidebar">
        <div class="logo-area">
            <img src="../imagens/logo.png" alt="Logo">
            <span class="logo-text">Autocademy <small style="font-size: 0.6rem; color: var(--accent-color);">DOCENTE</small></span>
        </div>
        <a href="dashboard_professor.php" class="nav-link"><i class="fa-solid fa-chalkboard-user"></i><span class="nav-text">Painel</span></a>
        <a href="turmas_professor.php" class="nav-link"><i class="fa-solid fa-users"></i><span class="nav-text">Minhas Turmas</span></a>
        <a href="lancar_notas.php" class="nav-link"><i class="fa-solid fa-file-pen"></i><span class="nav-text">Lançar Notas</span></a>
        <a href="diario_classe.php" class="nav-link"><i class="fa-solid fa-calendar-check"></i><span class="nav-text">Diário / Chamada</span></a>
        <a href="chat.html" class="nav-link"><i class="fa-regular fa-comments"></i><span class="nav-text">Mensagens</span></a>
        <div class="spacer"></div>
        <a href="config_instrutor.php" class="nav-link active"><i class="fa-solid fa-gear"></i><span class="nav-text">Configurações</span></a>
    </aside>

    <main class="main-wrapper">
        <header class="top-header">
            <div class="search-box" style="visibility: hidden;"></div> <div class="user-profile">
                <span><?php echo $nome_prof; ?></span>
                <div class="avatar-circle" style="background-color: var(--accent-color);"><i class="fa-solid fa-chalkboard-teacher"></i></div>
            </div>
        </header>

        <div class="scroll-content">
            <div class="container">
                
                <h1 class="page-title">Minhas Configurações</h1>
                
                <div class="config-grid">

                    <div class="settings-card">
                        <div class="card-header">
                            <i class="fa-regular fa-id-card"></i>
                            <h3>Dados Pessoais</h3>
                        </div>

                        <form action="../backend/salvar_perfil_prof.php" method="POST" enctype="multipart/form-data">
                            
                            <div class="profile-upload-area">
                                <div class="large-avatar">
                                    <?php echo strtoupper(substr($nome_prof, 0, 2)); ?>
                                </div>
                                <div class="upload-actions">
                                    <label class="btn-upload">
                                        <i class="fa-solid fa-camera"></i> Escolher foto
                                        <input type="file" name="foto_perfil" accept="image/*">
                                    </label>
                                    <small style="color: var(--text-secondary); font-size: 0.75rem;">JPG ou PNG, Max 2MB</small>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Nome Completo</label>
                                <input type="text" name="nome" class="form-input" value="<?php echo $nome_prof; ?>">
                            </div>

                            <div class="form-group">
                                <label class="form-label">E-mail Institucional</label>
                                <input type="email" name="email" class="form-input" value="<?php echo $email_prof; ?>">
                            </div>

                            <button type="submit" class="btn-submit">
                                <i class="fa-solid fa-floppy-disk"></i> Salvar Perfil
                            </button>
                        </form>
                    </div>

                    <div class="settings-card">
                        <div class="card-header">
                            <i class="fa-solid fa-palette"></i>
                            <h3>Aparência do Sistema</h3>
                        </div>

                        <p style="color: var(--text-secondary); margin-bottom: 20px; font-size: 0.9rem;">
                            Personalize como você visualiza a plataforma. O tema escuro é recomendado para ambientes com pouca luz.
                        </p>

                        <div class="theme-selector">
                            <div class="theme-option active" id="opt-dark" onclick="setTheme('dark')">
                                <i class="fa-solid fa-moon"></i>
                                <span>Modo Escuro</span>
                            </div>
                            
                            <div class="theme-option" id="opt-light" onclick="setTheme('light')">
                                <i class="fa-regular fa-sun"></i>
                                <span>Modo Claro</span>
                            </div>
                        </div>
                    </div>

                    <div class="settings-card">
                        <div class="card-header">
                            <i class="fa-solid fa-shield-halved"></i>
                            <h3>Segurança</h3>
                        </div>

                        <form action="../backend/alterar_senha_prof.php" method="POST">
                            <div class="form-group">
                                <label class="form-label">Senha Atual</label>
                                <input type="password" name="senha_atual" class="form-input" placeholder="••••••••">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Nova Senha</label>
                                <input type="password" name="nova_senha" class="form-input" placeholder="Mínimo 6 caracteres">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Confirmar Nova Senha</label>
                                <input type="password" name="confirma_senha" class="form-input" placeholder="Repita a nova senha">
                            </div>

                            <button type="submit" class="btn-submit" style="background: linear-gradient(135deg, #424242, #212121);">
                                <i class="fa-solid fa-lock"></i> Atualizar Senha
                            </button>
                        </form>
                    </div>

                </div> </div>
        </div>
    </main>

    <script src="tema.js"></script> 
    <script>
        const body = document.body;
        const optDark = document.getElementById('opt-dark');
        const optLight = document.getElementById('opt-light');
        const currentTheme = localStorage.getItem('theme');

        // Carrega tema salvo
        if (currentTheme === 'light') {
            body.classList.add('tema-claro');
            updateButtons('light');
        } else {
            updateButtons('dark');
        }

        function setTheme(theme) {
            if (theme === 'light') {
                body.classList.add('tema-claro');
                localStorage.setItem('theme', 'light');
                updateButtons('light');
            } else {
                body.classList.remove('tema-claro');
                localStorage.setItem('theme', 'dark');
                updateButtons('dark');
            }
        }

        function updateButtons(theme) {
            if (theme === 'light') {
                optLight.classList.add('active');
                optDark.classList.remove('active');
            } else {
                optDark.classList.add('active');
                optLight.classList.remove('active');
            }
        }
    </script>
</body>
</html>