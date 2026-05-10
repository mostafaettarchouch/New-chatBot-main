function Suggestions({ onSelect }) {
  const suggestions = [
    'كيف أقدم طلب جواز سفر جديد؟',
    'ما هي إجراءات تسجيل الزواج؟',
    'كيف أستعلم عن وثائق الإقامة؟',
  ]

  return (
    <div className="suggestions">
      {suggestions.map((suggestion, index) => (
        <button key={index} type="button" onClick={() => onSelect(suggestion)}>
          {suggestion}
        </button>
      ))}
    </div>
  )
}

export default Suggestions
