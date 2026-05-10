async function sendMessage() {
    const input = document.getElementById('chat-input');
    const message = input.value.trim();
    if (!message) return;

    appendMessage(message, 'user');
    input.value = '';

    const loadingId = 'loading-' + Date.now();
    appendLoading(loadingId);

    try {
        const response = await fetch('api.php?action=chat', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ message })
        });
        const data = await response.json();
        
        removeLoading(loadingId);
        if (data.response) {
            appendMessage(data.response, 'bot', data.title);
        }
    } catch (error) {
        removeLoading(loadingId);
        appendMessage('حدث خطأ في الاتصال بالخادم.', 'bot');
    }
}

function quickQuery(text) {
    document.getElementById('chat-input').value = text;
    sendMessage();
}

function appendMessage(text, sender, title = null) {
    const window = document.getElementById('chat-window');
    const wrapper = document.createElement('div');
    wrapper.className = `message-wrapper ${sender} animate-in`;
    
    let content = '';
    if (title) content += `<b style="display:block;margin-bottom:5px;color:#003366">${title}</b>`;
    content += text;

    wrapper.innerHTML = `
        <div class="message">${content}</div>
        <span class="time">${new Date().toLocaleTimeString('ar-MA', {hour:'2-digit', minute:'2-digit'})}</span>
    `;
    window.appendChild(wrapper);
    window.scrollTop = window.scrollHeight;
}

function appendLoading(id) {
    const window = document.getElementById('chat-window');
    const loader = document.createElement('div');
    loader.id = id;
    loader.className = 'message-wrapper bot';
    loader.innerHTML = '<div class="message"><i class="fas fa-ellipsis-h fa-beat"></i> جاري البحث...</div>';
    window.appendChild(loader);
    window.scrollTop = window.scrollHeight;
}

function removeLoading(id) {
    const el = document.getElementById(id);
    if (el) el.remove();
}

document.getElementById('chat-input').addEventListener('keypress', (e) => {
    if (e.key === 'Enter') sendMessage();
});
