function QuestionsTable({ questions, onResolve }) {
  const handleResolve = (question) => {
    const notes = prompt('Admin notes:')
    if (notes !== null) {
      onResolve(question.id, notes)
    }
  }

  return (
    <table className="questions-table">
      <thead>
        <tr>
          <th>Question</th>
          <th>Language</th>
          <th>Asked At</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        {questions.map(q => (
          <tr key={q.id}>
            <td>{q.question_text}</td>
            <td>{q.language?.name}</td>
            <td>{q.asked_at}</td>
            <td>
              <button onClick={() => handleResolve(q)}>Resolve</button>
            </td>
          </tr>
        ))}
      </tbody>
    </table>
  )
}

export default QuestionsTable