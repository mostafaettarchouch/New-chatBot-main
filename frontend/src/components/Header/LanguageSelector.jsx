function LanguageSelector({ language, setLanguage }) {
  const languages = [
    { code: 'ar', name: 'العربية' },
    { code: 'fr', name: 'Français' },
    { code: 'en', name: 'English' }
  ]

  return (
    <select value={language} onChange={(e) => setLanguage(e.target.value)}>
      {languages.map(lang => (
        <option key={lang.code} value={lang.code}>{lang.name}</option>
      ))}
    </select>
  )
}

export default LanguageSelector