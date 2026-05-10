function MessageBubble({ message }) {
  return (
    <div className={`message ${message.sender}`}>
      <p>{message.text}</p>
      {message.procedure && (
        <div className="procedure-card">
          <h3>{message.procedure.title}</h3>
          <p>{message.procedure.description}</p>
          {message.steps && message.steps.length > 0 && (
            <ol>
              {message.steps.map((step) => (
                <li key={step.id}>
                  <strong>{step.title}</strong>
                  <p>{step.description}</p>
                </li>
              ))}
            </ol>
          )}
        </div>
      )}
    </div>
  )
}

export default MessageBubble
