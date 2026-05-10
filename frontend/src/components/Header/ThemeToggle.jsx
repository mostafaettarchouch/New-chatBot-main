function ThemeToggle({ theme, toggleTheme }) {
  return (
    <button onClick={toggleTheme}>
      {theme === 'light' ? '🌙' : '☀️'}
    </button>
  )
}

export default ThemeToggle