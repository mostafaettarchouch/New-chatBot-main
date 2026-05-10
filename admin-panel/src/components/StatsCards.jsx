function StatsCards({ stats }) {
  return (
    <div className="card" style={{ display: 'grid', gridTemplateColumns: 'repeat(3, minmax(180px, 1fr))', gap: '16px' }}>
      <div className="card">
        <h3>إجمالي الأسئلة</h3>
        <p>{stats.total_questions}</p>
      </div>
      <div className="card">
        <h3>الأسئلة غير المجابة</h3>
        <p>{stats.total_unanswered}</p>
      </div>
      <div className="card">
        <h3>عدد الإجراءات</h3>
        <p>{stats.total_procedures}</p>
      </div>
    </div>
  )
}

export default StatsCards
