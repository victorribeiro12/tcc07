<?php
// 1. INICIA A SESSÃO
session_start();

// 2. TERNÁRIO
$nome_a_exibir = isset($_SESSION['nome_usuario']) ? $_SESSION['nome_usuario'] : 'Convidado';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Autocademy</title>
    <link rel="stylesheet" href="../css/style4.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    
    <aside class="sidebar">
        <div class="logo-area">
            <img src="../imagens/logo.png" alt="Logo">
            <span class="logo-text">Autocademy</span>
        </div>

        <a href="../html/dashboard.php" class="nav-link active">
            <img src="../imagens/iconhome.png" alt="Início" >
            <span class="nav-text">Início</span>
        </a>

        <a href="../html/materias.php" class="nav-link">
            <img src="../imagens/icons8-livros-96.png" alt="Matérias">
            <span class="nav-text">Matérias</span>
        </a>
         <a href="../html/chat.html" class="nav-link">
            <img src="../imagens/chat.png" alt="Chat">
            <span class="nav-text">Chat</span>
        </a>

        <a href="../html/historic.php" class="nav-link">
            <img src="../imagens/iconhistorico.png" alt="Histórico">
            <span class="nav-text">Histórico</span>
        </a>
        
        <div class="spacer"></div>

        <a href="../html/config.html" class="nav-link">
            <img src="../imagens/icons8-configurações-150.png" alt="Config">
            <span class="nav-text">Configurações</span>
        </a>
    </aside>

    <main class="main-wrapper">

        <header class="top-header">
            <div class="search-box">
                <input type="text" placeholder="Buscar matéria..." class="search-input">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
            </div>

        <div class="user-profile">
                <div class="avatar-circle">
                    <i class="fa-solid fa-user"></i>
                </div>
                <span>Olá, <strong>victor</strong></span>
            
            </div>
        </header>

        <div class="scroll-content">
            <div class="container">

                <div class="hero-banner">
                    <img src="../img/Captura de tela 2025-11-28 013711.png" alt="Banner" class="hero-image">
                    <div class="hero-text">Bem-vindo à Autocademy</div>
                </div>

                <div class="filters-area" style="display: flex; gap: 10px; margin-bottom: 30px; flex-wrap: wrap;">
                    <button class="filter-btn active" data-filter="all">Todos</button>
                    <button class="filter-btn" data-filter="design">Design</button>
                    <button class="filter-btn" data-filter="programacao">Programação</button>
                    <button class="filter-btn" data-filter="matematica">Matemática</button>
                    <button class="filter-btn" data-filter="avisos">Avisos</button>
                </div>

                <div class="content-grid">
                    
                    <article class="card materia-card" data-name="Introdução ao Design">
                        <div class="card-img-wrapper">
                            <img src="https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?q=80&w=1964&auto=format&fit=crop">
                        </div>
                        <div class="card-content">
                            <h2 class="card-title">Introdução ao Design</h2>
                            <p class="card-desc">Aprenda os conceitos fundamentais visualmente.</p>
                        </div>
                    </article>

                    <article class="card materia-card" data-name="Matemática Avançada">
                        <div class="card-img-wrapper">
                            <img src="https://images.unsplash.com/photo-1509062522246-3755977927d7?q=80&w=2000&auto=format&fit=crop">
                        </div>
                        <div class="card-content">
                            <h2 class="card-title">Matemática Avançada</h2>
                            <p class="card-desc">Cálculo diferencial e integral para engenharia.</p>
                        </div>
                    </article>

                    <article class="card materia-card" data-name="Programação Web">
                        <div class="card-img-wrapper">
                            <img src="https://images.unsplash.com/photo-1461749280684-dccba630e2f6?q=80&w=2000&auto=format&fit=crop">
                        </div>
                        <div class="card-content">
                            <h2 class="card-title">Programação Web</h2>
                            <p class="card-desc">Domine HTML, CSS e PHP Moderno.</p>
                        </div>
                    </article>

                    <article class="card materia-card" data-name="Avisos Importantes">
                        <div class="card-content">
                            <h2 class="card-title">Avisos Importantes</h2>
                            <ul style="padding-left: 20px; color: var(--text-secondary);">
                                <li>Entrega do TCC dia 20</li>
                                <li>Prova de Cálculo dia 25</li>
                                <li>Atualizar perfil</li>
                            </ul>
                        </div>
                    </article>

                    <article class="card materia-card" data-name="Histórico">
                        <div class="card-img-wrapper">
                            <img src="https://images.unsplash.com/photo-1461749280684-dccba630e2f6?q=80&w=2000&auto=format&fit=crop">
                        </div>
                        <div class="card-content">
                            <h2 class="card-title">Histórico</h2>
                            <p class="card-desc">Venha ver como esta sua frequencia de aulas</p>
                        </div>
                    </article>
                    
                    <article class="card materia-card" data-name="Programação Web 2">
                        <div class="card-img-wrapper">
                            <img src="https://images.unsplash.com/photo-1461749280684-dccba630e2f6?q=80&w=2000&auto=format&fit=crop">
                        </div>
                        <div class="card-content">
                            <h2 class="card-title">Programação Web 2</h2>
                            <p class="card-desc">Domine HTML, CSS e PHP Moderno.</p>
                        </div>
                    </article>
                    
                </div>
            </div>
        </div>
    </main>

    <script src="./tema.js"></script>
    
    <script>
        const links = document.querySelectorAll('.nav-link');
        links.forEach(link => {
            link.addEventListener('click', function() {
                links.forEach(l => l.classList.remove('active'));
                this.classList.add('active');
            });
        });
        document.addEventListener('DOMContentLoaded', () => {
            const cards = document.querySelectorAll('.materia-card');
            const buttons = document.querySelectorAll('.filter-btn');

            // 1. Dicionário de Palavras-Chave
            const keywords = {
                'design': ['design', 'visual', 'arte'],
                'programacao': ['programação', 'web', 'php', 'html', 'css', 'código'],
                'matematica': ['matemática', 'cálculo', 'álgebra', 'números'],
                'avisos': ['avisos', 'importantes', 'histórico']
            };
            cards.forEach(card => {
                let nome = card.getAttribute('data-name');
                if(nome) {
                    nome = nome.toLowerCase();
                    let categoriaFinal = 'outros';

                    for (const [categoria, palavras] of Object.entries(keywords)) {
                        if (palavras.some(palavra => nome.includes(palavra))) {
                            categoriaFinal = categoria;
                            break;
                        }
                    }
                    card.setAttribute('data-category', categoriaFinal);
                }
            });
            buttons.forEach(btn => {
                btn.addEventListener('click', () => {
                    buttons.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');

                    const filtro = btn.getAttribute('data-filter');

                    cards.forEach(card => {
                        const cat = card.getAttribute('data-category');
                        if (filtro === 'all' || filtro === cat) {
                            card.style.display = 'flex'; 
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>