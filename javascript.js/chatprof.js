document.addEventListener('DOMContentLoaded', () => {
    
    // --- 1. DADOS SIMULADOS (MOCK DATA) para o Instrutor ---
    
    const currentUser = { 
        nome: "Prof. João Silva", 
        iniciais: "JS",
        tipo: "instrutor" 
    };

    // Estrutura de dados para GERENCIAR MULTIPLOS CHATS
    const chatsData = [
        {
            id: 1,
            aluno: "Aluno Senai",
            matricula: "12345",
            turma: "T2024A",
            progresso: "65%",
            ultimoAcesso: "1 hora atrás",
            status: "pendente", // Novo status para o instrutor
            sla: "3h 15min", // Tempo de espera
            iniciais: "AS",
            // Mensagens específicas deste chat
            messages: [
                { autor: "Aluno Senai", iniciais: "AS", texto: "Bom dia, professor! Estou com dificuldade no cálculo de momento de inércia do Módulo 5.", hora: "10:30", tipo: "recebida" },
                { autor: currentUser.nome, iniciais: "JS", texto: "Olá! Qual parte específica está confusa? Você verificou a seção 5.2 sobre a Tabela de Inércias?", hora: "10:32", tipo: "enviada" },
                { autor: "Aluno Senai", iniciais: "AS", texto: "Sim, mas os exemplos não batem com o exercício do quiz...", hora: "14:00", tipo: "recebida" }
            ],
            notasInternas: "Aluno demonstrou dificuldade em quizzes anteriores sobre o mesmo tema."
        },
        {
            id: 2,
            aluno: "Maria Pereira",
            matricula: "67890",
            turma: "T2023B",
            progresso: "90%",
            ultimoAcesso: "2 dias atrás",
            status: "resolvido",
            sla: "0",
            iniciais: "MP",
            messages: [
                { autor: "Maria Pereira", iniciais: "MP", texto: "Obrigada pela ajuda com o TCC! Consegui finalizar.", hora: "Ontem", tipo: "recebida" }
            ],
            notasInternas: "Concluído. Próximo passo é a defesa oral."
        }
    ];

    let activeChatId = 1; // Começa com o primeiro chat ativo
    let activeChat = chatsData.find(c => c.id === activeChatId);

    const quickReplies = [
        "Sua dúvida foi resolvida? Por favor, confirme para que possamos arquivar o chat.",
        "Verifique o material complementar do Módulo 5 que enviei por email.",
        "Estou verificando a questão, aguarde um momento."
    ];

    // --- 2. SELETORES DO DOM ---
    const msgContainer = document.getElementById('msgContainer');
    const messageInput = document.querySelector('.message-input');
    const sendBtn = document.querySelector('.send-btn');
    const participantsList = document.querySelector('.participants-list');
    const studentContextSidebar = document.querySelector('.student-context-sidebar');
    const resolveBtn = document.querySelector('.resolve-btn');
    const quickReplyBtn = document.querySelector('.quick-reply-btn');
    const statusFilter = document.querySelector('.status-filter');
    const internalNotesInput = document.querySelector('.internal-notes input');
    const chatAreaHeader = document.querySelector('.chat-area-header .chat-target-info');


    // --- 3. FUNÇÕES DE RENDERIZAÇÃO ---

    /** Renderiza a lista de conversas do instrutor (Coluna 1) */
    function renderChatList(chats = chatsData) {
        participantsList.innerHTML = '';
        
        chats.forEach(chat => {
            const item = document.createElement('div');
            item.className = `participant-item ${chat.id === activeChatId ? 'active-chat' : ''} ${chat.status === 'resolvido' ? 'resolved-chat' : ''}`;
            item.setAttribute('data-chat-id', chat.id);
            item.addEventListener('click', () => switchChat(chat.id));

            const urgencyTag = chat.status === 'pendente' && chat.sla !== '0' 
                ? `<div class="participant-role urgency-tag">**EM ESPERA: ${chat.sla}**</div>`
                : `<div class="participant-role">${chat.status === 'resolvido' ? 'Resolvido' : 'Em andamento'}</div>`;

            item.innerHTML = `
                <div class="participant-avatar" style="background: ${chat.id === 1 ? '#3498db' : '#e74c3c'};">
                    <span>${chat.iniciais}</span>
                </div>
                <div class="participant-info">
                    <div class="participant-name">${chat.aluno}</div>
                    ${urgencyTag}
                </div>
                ${chat.status === 'pendente' ? '<span class="unread-count">!</span>' : ''}
            `;
            participantsList.appendChild(item);
        });
    }

    /** Renderiza as mensagens do chat ativo (Coluna 2) */
    function renderMessages() {
        if (!activeChat) return;

        msgContainer.innerHTML = '';
        
        activeChat.messages.forEach(msg => {
            const isOwn = msg.tipo === 'enviada';
            const initials = isOwn ? currentUser.iniciais : msg.iniciais;
            
            const msgDiv = document.createElement('div');
            msgDiv.className = `message ${isOwn ? 'own' : ''}`;

            const avatarDiv = document.createElement('div');
            avatarDiv.className = 'participant-avatar';
            // Usa as iniciais do autor da mensagem
            avatarDiv.textContent = initials; 

            // Aplica estilos diferentes para instrutor/aluno
            if (isOwn) {
                avatarDiv.style.background = '#007bff'; // Cor Instrutor
                avatarDiv.innerHTML = '<i class="fa-solid fa-graduation-cap"></i>'; // Ícone Instrutor
            } else {
                avatarDiv.style.background = '#3498db'; // Cor Aluno
            }
            
            const contentDiv = document.createElement('div');
            const bubble = document.createElement('div');
            bubble.className = 'message-bubble';
            bubble.textContent = msg.texto;

            const timeSpan = document.createElement('span');
            timeSpan.className = 'message-time';
            timeSpan.textContent = msg.hora;

            contentDiv.appendChild(bubble);
            contentDiv.appendChild(timeSpan);
            
            // Reverte a ordem do avatar e conteúdo se for mensagem 'own' (CSS flexbox já deve fazer a maior parte)
            if (isOwn) {
                msgDiv.appendChild(contentDiv);
                msgDiv.appendChild(avatarDiv);
            } else {
                msgDiv.appendChild(avatarDiv);
                msgDiv.appendChild(contentDiv);
            }

            msgContainer.appendChild(msgDiv);
        });

        // Atualiza cabeçalho do chat
        chatAreaHeader.innerHTML = `
            <h2>${activeChat.aluno}</h2>
            <span style="font-size: 0.85rem; color: var(--text-secondary);">Matrícula: ${activeChat.matricula} | Turma: ${activeChat.turma}</span>
        `;
        
        // Rola para o final
        msgContainer.scrollTop = msgContainer.scrollHeight;
    }

    /** Renderiza o painel de contexto do aluno (Coluna 3) */
    function renderStudentContext() {
        if (!activeChat) return;

        studentContextSidebar.innerHTML = `
            <h3>Detalhes do Aluno</h3>
            <div class="student-info-block">
                <h4><i class="fa-solid fa-user-graduate"></i> Dados Principais</h4>
                <p><strong>Nome:</strong> ${activeChat.aluno}</p>
                <p><strong>Matrícula:</strong> ${activeChat.matricula}</p>
                <p><strong>Turma:</strong> ${activeChat.turma}</p>
                <p><strong>Último Acesso:</strong> ${activeChat.ultimoAcesso}</p>
            </div>

            <div class="student-info-block">
                <h4><i class="fa-solid fa-chart-line"></i> Progresso no Curso</h4>
                <p><strong>Conclusão:</strong> ${activeChat.progresso}</p>
                <p><strong>Status do Chat:</strong> <span style="font-weight: bold; color: ${activeChat.status === 'resolvido' ? '#2ecc71' : '#f39c12'};">${activeChat.status.toUpperCase()}</span></p>
            </div>

            <div class="student-info-block">
                <h4><i class="fa-solid fa-note-sticky"></i> Anotações Internas</h4>
                <input type="text" class="internal-notes-input" placeholder="Anotação Interna (Não visível para o aluno)" value="${activeChat.notasInternas}">
            </div>
            
            <div style="text-align: center; margin-top: 10px;">
                <button class="resolve-btn ${activeChat.status === 'resolvido' ? 'disabled' : ''}" ${activeChat.status === 'resolvido' ? 'disabled' : ''} onclick="handleResolveChat(${activeChat.id})">
                    <i class="fa-solid fa-check-circle"></i> ${activeChat.status === 'resolvido' ? 'Chat Resolvido' : 'Marcar como Resolvido'}
                </button>
            </div>
        `;
        
        // Reconecta o input de anotações internas após renderizar
        document.querySelector('.internal-notes-input').addEventListener('change', updateInternalNotes);
    }
    
    // --- 4. FUNÇÕES DE LÓGICA E EVENTOS ---

    /** Troca o chat ativo (seleciona um aluno diferente) */
    function switchChat(chatId) {
        if (activeChatId === chatId) return; // Se já estiver ativo, não faz nada

        activeChatId = chatId;
        activeChat = chatsData.find(c => c.id === chatId);
        
        renderChatList(); // Atualiza a lista para destacar o item
        renderMessages();
        renderStudentContext();
        
        // Atualiza o placeholder do input de mensagem
        messageInput.placeholder = `Digite sua resposta para ${activeChat.aluno}...`;
    }

    /** Lógica de envio de mensagem (para o chat ativo) */
    function sendMessage() {
        const text = messageInput.value.trim();
        
        if (text !== "" && activeChat) {
            const now = new Date();
            const hora = now.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });

            activeChat.messages.push({
                autor: currentUser.nome,
                iniciais: currentUser.iniciais,
                texto: text,
                hora: hora,
                tipo: "enviada"
            });
            
            messageInput.value = '';
            renderMessages();
        }
    }

    /** Abre um menu modal para Respostas Prontas */
    function openQuickReplyMenu() {
        const menu = document.createElement('div');
        menu.className = 'quick-reply-menu';
        
        quickReplies.forEach(reply => {
            const item = document.createElement('div');
            item.textContent = reply;
            item.className = 'quick-reply-item';
            item.addEventListener('click', () => {
                messageInput.value = reply; // Coloca a resposta no campo de input
                menu.remove();
            });
            menu.appendChild(item);
        });

        // Posiciona o menu ao lado do botão
        const rect = quickReplyBtn.getBoundingClientRect();
        menu.style.top = `${rect.top - 5 - menu.offsetHeight}px`; // Acima do botão
        menu.style.left = `${rect.left}px`;

        document.body.appendChild(menu);
        // Fecha o menu se clicar fora
        setTimeout(() => {
            document.addEventListener('click', function closeMenu(e) {
                if (!menu.contains(e.target) && e.target !== quickReplyBtn) {
                    menu.remove();
                    document.removeEventListener('click', closeMenu);
                }
            });
        }, 10);
    }

    /** Lida com o filtro da lista de chats */
    function filterChats() {
        const filterValue = statusFilter.value;
        let filteredChats = chatsData;

        if (filterValue !== 'all') {
            if (filterValue === 'urgent') {
                // Simulação de filtro urgente: SLA > 3h
                filteredChats = chatsData.filter(chat => chat.status === 'pendente' && chat.sla.includes('h') && parseInt(chat.sla) >= 3);
            } else {
                filteredChats = chatsData.filter(chat => chat.status === filterValue);
            }
        }

        renderChatList(filteredChats);
    }

    /** Marca o chat como resolvido (simulação) */
    window.handleResolveChat = function(chatId) {
        const chat = chatsData.find(c => c.id === chatId);
        if (chat && chat.status !== 'resolvido') {
            chat.status = 'resolvido';
            chat.sla = '0';
            alert(`Chat com ${chat.aluno} marcado como RESOLVIDO.`);
            renderChatList();
            renderStudentContext(); // Para atualizar o botão
            filterChats(); // Re-filtra a lista, se necessário
        }
    }
    
    /** Atualiza o campo de anotações internas */
    function updateInternalNotes(e) {
        if (activeChat) {
            activeChat.notasInternas = e.target.value;
        }
    }


    // --- 5. EVENTOS ---
    
    sendBtn.addEventListener('click', sendMessage);

    messageInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });

    quickReplyBtn.addEventListener('click', openQuickReplyMenu);
    
    statusFilter.addEventListener('change', filterChats);

    // --- INICIALIZAÇÃO ---
    renderChatList();
    renderMessages();
    renderStudentContext();
});