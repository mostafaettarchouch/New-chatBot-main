import { useState, useEffect } from 'react'

function useLanguage() {
  const [language, setLanguage] = useState(() => {
    return localStorage.getItem('language') || 'ar'
  })

  useEffect(() => {
    localStorage.setItem('language', language)
    document.documentElement.lang = language
    document.documentElement.dir = language === 'ar' ? 'rtl' : 'ltr'
  }, [language])

  return { language, setLanguage }
}

export { useLanguage }