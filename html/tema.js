  // Mapeamento dos valores de cor (do seu HTML) para os NOMES das classes CSS
    const temasMap = {
        '#3b3b3b': 'padrao',      // Sistema (Padrão - Não precisa de classe)
        '#f0f0f0': 'claro',       // Tema Claro (SENAI)
        '#55cffa': 'azul-ceu',
        '#8AF2D1': 'verde-agua',
        '#F4C0E0': 'rosa-claro',
        '#E3008C': 'magenta',
        '#FFB900': 'amarelo',
        '#0078D4': 'azul-edge',
        '#8E41B5': 'roxo',
        '#D13438': 'vermelho',
        '#107C10': 'verde'
    };
    
    // Função principal para aplicar o tema em qualquer página
    function aplicarTema(temaSalvo) {
        // Encontra o nome da CLASSE (ex: 'claro') a partir do valor da cor (#f0f0f0)
        const nomeClasse = temasMap[temaSalvo] || 'padrao';
        const elementoPrincipal = document.body; // Recomendado usar o body
        
        // 1. Remove classes de tema existentes para evitar conflito
        for (const key in temasMap) {
            elementoPrincipal.classList.remove(`tema-${temasMap[key]}`);
        }
        
        // 2. Adiciona a classe do novo tema (se não for o padrão)
        if (nomeClasse !== 'padrao') {
            elementoPrincipal.classList.add(`tema-${nomeClasse}`);
        }
    }

    // --- EXECUÇÃO AO CARREGAR A PÁGINA ---
    const temaSalvo = localStorage.getItem('temaPainel');
    if (temaSalvo) {
        aplicarTema(temaSalvo);
    }
    
    // Se você usa botões ou swatches para trocar o tema, adicione o listener aqui
    document.addEventListener('DOMContentLoaded', () => {
        const swatches = document.querySelectorAll('.color-swatches .swatch');

        swatches.forEach(swatch => {
            swatch.addEventListener('click', function() {
                const corSelecionada = this.getAttribute('data-color');
                
                // 1. Salva a cor no localStorage
                localStorage.setItem('temaPainel', corSelecionada);
                
                // 2. Aplica o tema na página atual
                aplicarTema(corSelecionada);

                // 3. (Opcional) Remove a classe 'active' de todos e adiciona no selecionado
                swatches.forEach(s => s.classList.remove('active'));
                this.classList.add('active');
            });
        });
    });