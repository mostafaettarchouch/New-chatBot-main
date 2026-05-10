import { useEffect, useState } from 'react'

function ProcedureForm({ procedure, onSave, onCancel }) {
  const [form, setForm] = useState({
    title: '',
    description: '',
    summary: '',
    steps: [],
    keywords: [],
  })

  useEffect(() => {
    if (procedure) {
      setForm({
        title: procedure.title || '',
        description: procedure.description || '',
        summary: procedure.summary || '',
        steps: procedure.steps || [],
        keywords: procedure.keywords || [],
      })
    }
  }, [procedure])

  const updateField = (field, value) => {
    setForm((prev) => ({ ...prev, [field]: value }))
  }

  const updateStep = (index, value) => {
    const next = [...form.steps]
    next[index] = value
    setForm((prev) => ({ ...prev, steps: next }))
  }

  const updateKeyword = (index, value) => {
    const next = [...form.keywords]
    next[index] = value
    setForm((prev) => ({ ...prev, keywords: next }))
  }

  const addStep = () => {
    setForm((prev) => ({ ...prev, steps: [...prev.steps, { step_number: prev.steps.length + 1, title: '', description: '' }] }))
  }

  const addKeyword = () => {
    setForm((prev) => ({ ...prev, keywords: [...prev.keywords, ''] }))
  }

  const handleSubmit = (e) => {
    e.preventDefault()
    onSave(form)
  }

  return (
    <div className="card">
      <h3>{procedure.id ? 'تعديل إجراء' : 'إضافة إجراء جديد'}</h3>
      <form onSubmit={handleSubmit}>
        <label>العنوان</label>
        <input value={form.title} onChange={(e) => updateField('title', e.target.value)} required />
        <label>الوصف الكامل</label>
        <textarea rows="4" value={form.description} onChange={(e) => updateField('description', e.target.value)} required />
        <label>الملخص المختصر</label>
        <textarea rows="2" value={form.summary} onChange={(e) => updateField('summary', e.target.value)} />

        <div className="form-row">
          <div>
            <h4>الخطوات</h4>
            {form.steps.map((step, index) => (
              <div key={index} style={{ marginBottom: 12 }}>
                <label>عنوان الخطوة {index + 1}</label>
                <input value={step.title} onChange={(e) => updateStep(index, { ...step, title: e.target.value })} required />
                <label>تفاصيل الخطوة</label>
                <textarea rows="2" value={step.description} onChange={(e) => updateStep(index, { ...step, description: e.target.value })} required />
              </div>
            ))}
            <button type="button" onClick={addStep} style={{ background: '#003366', color: 'white' }}>
              إضافة خطوة
            </button>
          </div>

          <div>
            <h4>الكلمات المفتاحية</h4>
            {form.keywords.map((keyword, index) => (
              <input key={index} value={keyword} onChange={(e) => updateKeyword(index, e.target.value)} placeholder="كلمة مفتاحية" required />
            ))}
            <button type="button" onClick={addKeyword} style={{ background: '#003366', color: 'white' }}>
              إضافة كلمة
            </button>
          </div>
        </div>

        <div style={{ display: 'flex', gap: 12, marginTop: 16 }}>
          <button type="submit" style={{ background: '#007bff', color: 'white', border: 'none', padding: '10px 16px', borderRadius: '8px' }}>
            حفظ
          </button>
          <button type="button" onClick={onCancel} style={{ background: '#6c757d', color: 'white', border: 'none', padding: '10px 16px', borderRadius: '8px' }}>
            إلغاء
          </button>
        </div>
      </form>
    </div>
  )
}

export default ProcedureForm
