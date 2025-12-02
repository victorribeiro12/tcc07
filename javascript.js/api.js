
// ============================================
// CONFIGURAÇÃO DO PUBNUB
// ============================================
// IMPORTANTE: Substitua com suas chaves do PubNub
const PUBNUB_CONFIG = {
    publishKey: 'pub-c-3022c5c1-1dcf-42f0-ac05-491726056f75',
    subscribeKey: 'sub-c-dc7b23b6-bf21-457c-a8ea-3ad46197424f'
};

// Canais de comunicação
const CHAT_CHANNEL = 'chat-aluno-instrutor-autocademy';

// ============================================
// VARIÁVEIS GLOBAIS
// ============================================
let pubnub;
let currentUser = {
    type: null,      // 'aluno' ou 'instrutor'
    name: '',
    avatar: '',
    id: ''
};
let typingTimeout;

// ============================================
// INICIALIZAÇÃO
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    // Detectar tipo de usuário pela página
    detectarTipoUsuario();
    
    // Verificar configuração
    if (PUBNUB_CONFIG.publishKey === 'sua-publish-key-aqui') {
        console.error('⚠️ CONFIGURE AS CHAVES DO PUBNUB!');
        mostrarAlerta('Configure as chaves do PubNub no arquivo chat.js');
        return;
    }
    
    // Inicializar PubNub
    inicializarPubNub();
    
    // Configurar event listeners
    configurarEventListeners();
    
    // Carregar histórico
    carregarHistorico();
});

// ============================================
// DETECTAR TIPO DE USUÁRIO
// ============================================
function detectarTipoUsuario() {
    const url = window.location.pathname;
    const header = document.querySelector('.top-header h2');
    
    if (url.includes('chatprof') || (header && header.textContent.includes('Instrutor'))) {
        currentUser.type = 'instrutor';
        currentUser.name = 'Prof. João Silva';
        currentUser.avatar = 'JS';
        currentUser.id = 'instrutor_joao_silva';
    } else {
        currentUser.type = 'aluno';
        currentUser.name = 'Aluno Senai';
        currentUser.avatar = 'AS';
        currentUser.id = 'aluno_senai';
    }
    
    console.log('Usuário detectado:', currentUser);
}

// ============================================
// INICIALIZAR PUBNUB
// ============================================
function inicializarPubNub() {
    try {
        pubnub = new PubNub({
            publishKey: PUBNUB_CONFIG.publishKey,
            subscribeKey: PUBNUB_CONFIG.subscribeKey,
            uuid: currentUser.id,
            ssl: true
        });

        // Configurar listeners
        pubnub.addListener({
            message: function(event) {
                if (event.message.type === 'message') {
                    receberMensagem(event.message);
                } else if (event.message.type === 'typing') {
                    mostrarIndicadorDigitacao(event.message);
                }
            },
            presence: function(event) {
                atualizarStatusOnline(event);
            },
            status: function(statusEvent) {
                if (statusEvent.category === "PNConnectedCategory") {
                    console.log('✓ Conectado ao PubNub');
                }
            }
        });

        // Inscrever no canal
        pubnub.subscribe({
            channels: [CHAT_CHANNEL],
            withPresence: true
        });

        console.log('✓ PubNub inicializado com sucesso');
    } catch (error) {
        console.error('Erro ao inicializar PubNub:', error);
        mostrarAlerta('Erro ao conectar ao chat. Verifique sua conexão.');
    }
}

// ============================================
// CONFIGURAR EVENT LISTENERS
// ============================================
function configurarEventListeners() {
    const messageInput = document.querySelector('.message-input');
    const sendBtn = document.querySelector('.send-btn');
    
    if (sendBtn) {
        sendBtn.addEventListener('click', enviarMensagem);
    }
    
    if (messageInput) {
        messageInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                enviarMensagem();
            }
        });
        
        // Indicador de digitação
        messageInput.addEventListener('input', function() {
            if (this.value.trim().length > 0) {
                enviarIndicadorDigitacao();
            }
        });
    }
}

// ============================================
// ENVIAR MENSAGEM
// ============================================
function enviarMensagem() {
    const messageInput = document.querySelector('.message-input');
    const texto = messageInput.value.trim();
    
    if (!texto) return;
    
    const mensagem = {
        type: 'message',
        sender: currentUser.type,
        senderName: currentUser.name,
        senderAvatar: currentUser.avatar,
        senderId: currentUser.id,
        text: texto,
        timestamp: new Date().toISOString()
    };
    
    // Publicar mensagem
    pubnub.publish({
        channel: CHAT_CHANNEL,
        message: mensagem
    }, function(status, response) {
        if (!status.error) {
            messageInput.value = '';
            console.log('✓ Mensagem enviada');
        } else {
            console.error('Erro ao enviar mensagem:', status);
            mostrarAlerta('Erro ao enviar mensagem. Tente novamente.');
        }
    });
}

// ============================================
// RECEBER MENSAGEM
// ============================================
function receberMensagem(mensagem) {
    const container = document.getElementById('msgContainer');
    if (!container) return;
    
    // Não exibir mensagens próprias novamente (já aparecem via histórico)
    if (mensagem.senderId === currentUser.id) return;
    
    const messageDiv = criarElementoMensagem(mensagem);
    container.appendChild(messageDiv);
    
    // Scroll automático
    container.scrollTop = container.scrollHeight;
    
    // Tocar som de notificação (opcional)
    tocarSomNotificacao();
}

// ============================================
// CRIAR ELEMENTO DE MENSAGEM
// ============================================
function criarElementoMensagem(mensagem) {
    const messageDiv = document.createElement('div');
    const isOwn = mensagem.senderId === currentUser.id;
    messageDiv.className = `message ${isOwn ? 'own' : ''}`;
    
    const time = new Date(mensagem.timestamp).toLocaleTimeString('pt-BR', {
        hour: '2-digit',
        minute: '2-digit'
    });
    
    if (isOwn) {
        messageDiv.innerHTML = `
            <div>
                <div class="message-bubble">
                    ${escapeHtml(mensagem.text)}
                </div>
                <span class="message-time">${time}</span>
            </div>
            <div class="participant-avatar" style="background-color: ${currentUser.type === 'aluno' ? '#3498db' : '#007bff'};">
                ${mensagem.senderAvatar}
            </div>
        `;
    } else {
        messageDiv.innerHTML = `
            <div class="participant-avatar">
                ${mensagem.senderAvatar}
            </div>
            <div>
                <div class="message-bubble">
                    ${escapeHtml(mensagem.text)}
                </div>
                <span class="message-time">${time}</span>
            </div>
        `;
    }
    
    return messageDiv;
}

// ============================================
// CARREGAR HISTÓRICO
// ============================================
function carregarHistorico() {
    pubnub.fetchMessages({
        channels: [CHAT_CHANNEL],
        count: 50
    }, function(status, response) {
        if (status.error) {
            console.error('Erro ao carregar histórico:', status);
            return;
        }
        
        const container = document.getElementById('msgContainer');
        if (!container) return;
        
        // Limpar mensagens de exemplo
        container.innerHTML = '';
        
        if (response && response.channels[CHAT_CHANNEL]) {
            const mensagens = response.channels[CHAT_CHANNEL];
            
            mensagens.forEach(item => {
                if (item.message.type === 'message') {
                    const messageDiv = criarElementoMensagem(item.message);
                    container.appendChild(messageDiv);
                }
            });
            
            // Scroll para o final
            container.scrollTop = container.scrollHeight;
            
            console.log(`✓ ${mensagens.length} mensagens carregadas`);
        }
    });
}

// ============================================
// INDICADOR DE DIGITAÇÃO
// ============================================
function enviarIndicadorDigitacao() {
    clearTimeout(typingTimeout);
    
    pubnub.publish({
        channel: CHAT_CHANNEL,
        message: {
            type: 'typing',
            sender: currentUser.type,
            senderId: currentUser.id
        }
    });
    
    typingTimeout = setTimeout(() => {}, 3000);
}

function mostrarIndicadorDigitacao(dados) {
    if (dados.senderId === currentUser.id) return;
    
    const chatHeader = document.querySelector('.chat-area-header');
    if (!chatHeader) return;
    
    // Remover indicador antigo se existir
    let indicator = document.querySelector('.typing-indicator-temp');
    if (!indicator) {
        indicator = document.createElement('div');
        indicator.className = 'typing-indicator-temp';
        indicator.style.cssText = 'font-size: 0.8rem; color: #888; margin-top: 5px;';
        chatHeader.appendChild(indicator);
    }
    
    indicator.textContent = `${dados.sender === 'aluno' ? 'Aluno' : 'Professor'} está digitando...`;
    
    // Remover após 3 segundos
    clearTimeout(window.typingDisplayTimeout);
    window.typingDisplayTimeout = setTimeout(() => {
        if (indicator && indicator.parentNode) {
            indicator.remove();
        }
    }, 3000);
}

// ============================================
// ATUALIZAR STATUS ONLINE
// ============================================
function atualizarStatusOnline(event) {
    console.log('Presença:', event);
    
    const statusIndicator = document.querySelector('.status-indicator');
    if (!statusIndicator) return;
    
    if (event.action === 'join' || event.action === 'state-change') {
        statusIndicator.classList.remove('status-offline');
        statusIndicator.classList.add('status-online');
    } else if (event.action === 'leave' || event.action === 'timeout') {
        statusIndicator.classList.remove('status-online');
        statusIndicator.classList.add('status-offline');
    }
}

// ============================================
// FUNÇÕES AUXILIARES
// ============================================
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function mostrarAlerta(mensagem) {
    alert(mensagem);
}

function tocarSomNotificacao() {
    // Opcional: adicionar som de notificação
    // const audio = new Audio('../sounds/notification.mp3');
    // audio.play().catch(e => console.log('Erro ao tocar som:', e));
}

// ============================================
// EXPORTAR FUNÇÕES (se necessário)
// ============================================
window.chatPubNub = {
    enviarMensagem,
    carregarHistorico
};

console.log('✓ Chat.js carregado - Tipo de usuário:', currentUser.type);