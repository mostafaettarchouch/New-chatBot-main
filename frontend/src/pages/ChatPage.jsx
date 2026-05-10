import { useState } from 'react'
import ChatWindow from '../components/Chat/ChatWindow'

function ChatPage() {
  const [showTracker, setShowTracker] = useState(false)

  return (
    <div className="chat-page">
      <header className="page-header">
        <div>
          <h1>المساعد القانوني المغربي</h1>
          <p>اسأل عن الإجراءات القانونية واحصل على إجابات من قاعدة بيانات محلية.</p>
        </div>
      </header>

      <main>
        <ChatWindow />
        <button className="tracker-button" onClick={() => setShowTracker(!showTracker)}>
          {showTracker ? 'إخفاء تتبع القضيتي' : 'تتبع قضيتي'}
        </button>
        {showTracker && (
          <div className="iframe-container">
            <iframe
              src="https://www.mahakim.ma/#/suivi/dossier-suivi"
              title="تتبع القضيتي"
              frameBorder="0"
              loading="lazy"
            />
          </div>
        )}
      </main>
    </div>
  )
}

export default ChatPage
