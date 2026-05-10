import { useEffect, useState } from 'react'
import ProcedureForm from '../components/ProcedureForm'
import api from '../services/api'

function Procedures() {
  const [procedures, setProcedures] = useState([])
  const [editing, setEditing] = useState(null)

  useEffect(() => {
    fetchProcedures()
  }, [])

  const fetchProcedures = async () => {
    const response = await api.get('/admin/procedures')
    setProcedures(response.data)
  }

  const handleSave = async (procedure) => {
    const payload = {
      legal_category_id: 1,
      language_id: 1,
      title: procedure.title,
      description: procedure.description,
      summary: procedure.summary,
      steps: procedure.steps,
      keywords: procedure.keywords.map((keyword) => ({ keyword, weight: 1 })),
    }

    if (editing && editing.id) {
      await api.put(`/admin/procedures/${editing.id}`, payload)
    } else {
      await api.post('/admin/procedures', payload)
    }

    setEditing(null)
    fetchProcedures()
  }

  const handleDelete = async (id) => {
    if (confirm('هل ترغب في حذف هذا الإجراء؟')) {
      await api.delete(`/admin/procedures/${id}`)
      fetchProcedures()
    }
  }

  const editProcedure = (proc) => {
    setEditing({
      ...proc,
      steps: (proc.procedureSteps || proc.procedure_steps || []).map((step) => ({
        step_number: step.step_number || step.stepNumber || 0,
        title: step.title,
        description: step.description,
      })),
      keywords: (proc.keywords || []).map((keyword) => keyword.keyword || keyword),
    })
  }

  return (
    <div>
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
        <h2>إدارة الإجراءات</h2>
        <button onClick={() => setEditing({ title: '', description: '', summary: '', steps: [], keywords: [] })} style={{ background: '#003366', color: 'white', border: 'none', padding: '10px 16px', borderRadius: '8px' }}>
          إضافة إجراء جديد
        </button>
      </div>

      {editing && <ProcedureForm procedure={editing} onSave={handleSave} onCancel={() => setEditing(null)} />}

      <div className="table-container">
        <table>
          <thead>
            <tr>
              <th>العنوان</th>
              <th>الملخص</th>
              <th>الإجراءات</th>
            </tr>
          </thead>
          <tbody>
            {procedures.map((proc) => (
              <tr key={proc.id}>
                <td>{proc.title}</td>
                <td>{proc.summary}</td>
                <td className="action-buttons">
                  <button onClick={() => editProcedure(proc)}>
                    تعديل
                  </button>
                  <button className="delete" onClick={() => handleDelete(proc.id)}>
                    حذف
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

export default Procedures
