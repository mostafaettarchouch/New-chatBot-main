import { useState } from 'react'

function ChatInput({ onSend, disabled }) {
  const [input, setInput] = useState('')

  const handleSubmit = (event) => {
    event.preventDefault()
    const trimmed = input.trim()
    if (!trimmed) {
      return
    }
    onSend(trimmed)
    setInput('')
  }

  return (
    <form className="chat-input" onSubmit={handleSubmit}>
      <input
        value={input}
        onChange={(e) => setInput(e.target.value)}
        placeholder="اكتب سؤالك هنا..."
        disabled={disabled}
      />
      <button type="submit" disabled={disabled || !input.trim()}>
        إرسال
      </button>
    </form>
  )
}

export default ChatInput
