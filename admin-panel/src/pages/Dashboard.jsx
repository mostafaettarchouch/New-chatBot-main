import { useEffect, useState } from 'react'
import api from '../services/api'
import StatsCards from '../components/StatsCards'

function Dashboard() {
  const [stats, setStats] = useState(null)

  useEffect(() => {
    api.get('/admin/dashboard').then((response) => setStats(response.data)).catch(() => setStats(null))
  }, [])

  return (
    <div>
      <h2>لوحة المعلومات</h2>
      {stats ? (
        <>
          <StatsCards stats={stats} />
          <div className="card">
            <h3 className="section-title">الأسئلة حسب اللغة</h3>
            <ul>
              {stats.questions_by_language.map((item, index) => (
                <li key={index}>{item.language}: {item.count}</li>
              ))}
            </ul>
          </div>
          <div className="card">
            <h3 className="section-title">أكثر الإجراءات المطلوبة</h3>
            <ol>
              {stats.most_asked_procedures.map((item, index) => (
                <li key={index}>{item.procedure_title}: {item.count}</li>
              ))}
            </ol>
          </div>
        </>
      ) : (
        <p>جارٍ تحميل البيانات...</p>
      )}
    </div>
  )
}

export default Dashboard
