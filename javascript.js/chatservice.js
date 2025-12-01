import { io } from "socket.io-client";

/**
 * Serviço profissional para gerenciar conexão de Chat via Socket.io.
 * Implementa padrão Singleton e tratamento de eventos.
 */
class ChatService {
  constructor(url) {
    this.socket = null;
    this.url = url;
    this.callbacks = {}; // Armazena funções para executar quando eventos ocorrem
  }

  /**
   * Inicia a conexão com o servidor.
   * @param {string} username - Nome do usuário
   * @param {string} room - Sala de chat (ex: 'suporte', 'geral')
   */
  connect(username, room) {
    if (this.socket) return; // Evita múltiplas conexões

    this.socket = io(this.url, {
      transports: ["websocket"], // Força WebSocket para melhor performance
      reconnectionAttempts: 5,   // Tenta reconectar 5 vezes se cair
      query: { username, room }  // Envia dados iniciais no handshake
    });

    this._setupListeners();
  }

  /**
   * Configura os ouvintes de eventos internos do Socket.io
   * @private
   */
  _setupListeners() {
    this.socket.on("connect", () => {
      console.log(`✅ Conectado ao chat! ID: ${this.socket.id}`);
      if (this.callbacks.onConnect) this.callbacks.onConnect();
    });

    this.socket.on("receive_message", (data) => {
      // Quando receber mensagem, chama a função definida pelo front-end
      if (this.callbacks.onMessage) this.callbacks.onMessage(data);
    });

    this.socket.on("connect_error", (err) => {
      console.error("❌ Erro de conexão:", err.message);
    });
  }

  /**
   * Envia uma mensagem para a sala.
   * @param {string} message - Texto da mensagem
   */
  sendMessage(message) {
    if (!this.socket) throw new Error("Chat não conectado.");
    
    const payload = {
      text: message,
      timestamp: new Date().toISOString()
    };

    // Emite o evento 'send_message' para o servidor
    this.socket.emit("send_message", payload);
  }

  /**
   * Define o que fazer quando uma mensagem chega.
   * @param {Function} callback - Função que recebe o objeto da mensagem
   */
  onMessageReceived(callback) {
    this.callbacks.onMessage = callback;
  }

  /**
   * Encerra a conexão de forma limpa.
   */
  disconnect() {
    if (this.socket) {
      this.socket.disconnect();
      this.socket = null;
      console.log("🔌 Desconectado.");
    }
  }
}

export default ChatService;