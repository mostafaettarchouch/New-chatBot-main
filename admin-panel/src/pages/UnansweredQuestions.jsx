import { useEffect, useState } from 'react'
import api from '../services/api'

function UnansweredQuestions() {
  const [questions, setQuestions] = useState([])

  useEffect(() => {
    fetchQuestions()
  }, [])

  const fetchQuestions = async () => {
    const response = await api.get('/admin/questions')
    setQuestions(response.data)
  }

  const resolveQuestion = async (question) => {
    const notes = prompt('أضف ملاحظة إدارية')
    if (notes !== null) {
      await api.patch(`/admin/questions/${question.id}/resolve`, { admin_notes: notes })
      fetchQuestions()
    }
  }

  const convertQuestion = async (question) => {
    const title = prompt('عنوان الإجراء الجديد')
    const description = prompt('وصف الإجراء الجديد')
    if (!title || !description) {
      return
    }

    await api.post(`/admin/questions/${question.id}/convert`, {
      legal_category_id: 1,
      language_id: question.language_id,
      title,
      description,
      summary: 'إجراء جديد تم إنشاؤه من سؤال لم يتم الرد عليه.',
      steps: [],
      keywords: [],
      admin_notes: 'تم تحويل السؤال إلى إجراء',
    })
    fetchQuestions()
  }

  return (
    <div>
      <h2>الأسئلة غير المجابة</h2>
      <div className="table-container">
        <table>
          <thead>
            <tr>
              <th>السؤال</th>
              <th>اللغة</th>
              <th>تاريخ الطلب</th>
              <th>الإجراءات</th>
            </tr>
          </thead>
          <tbody>
            {questions.map((question) => (
              <tr key={question.id}>
                <td>{question.question_text}</td>
                <td>{question.language?.name}</td>
                <td>{new Date(question.asked_at).toLocaleString('ar-EG')}</td>
                <td className="action-buttons">
                  <button className="resolve" onClick={() => resolveQuestion(question)}>
                    حل
                  </button>
                  <button className="convert" onClick={() => convertQuestion(question)}>
                    تحويل إلى إجراء
                  </button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  )
}

export default UnansweredQuestions
