import { useState } from 'react'
import MessageBubble from './MessageBubble'
import ChatInput from './ChatInput'
import Suggestions from './Suggestions'
import api from '../../services/api'

function ChatWindow() {
  const [messages, setMessages] = useState([
    { text: 'مرحباً بك! اطرح سؤالك القانوني وسأحاول مساعدتك.', sender: 'bot' },
  ])
  const [loading, setLoading] = useState(false)

  const sendMessage = async (message) => {
    setMessages((prev) => [...prev, { text: message, sender: 'user' }])
    setLoading(true)

    try {
      const response = await api.post('/chat/send', { message })
      const botMessage = {
        text: response.data.response,
        sender: 'bot',
        procedure: response.data.procedure,
        steps: response.data.steps,
        matched: response.data.matched,
      }
      setMessages((prev) => [...prev, botMessage])
    } catch (error) {
      setMessages((prev) => [...prev, { text: 'حدث خطأ، يرجى المحاولة مرة أخرى.', sender: 'bot' }])
    } finally {
      setLoading(false)
    }
  }

  return (
    <section className="chat-window">
      <div className="messages">
        {messages.map((message, index) => (
          <MessageBubble key={index} message={message} />
        ))}
        {loading && <div className="loading">جارٍ البحث...</div>}
      </div>
      <Suggestions onSelect={sendMessage} />
      <ChatInput onSend={sendMessage} disabled={loading} />
    </section>
  )
}

export default ChatWindow
