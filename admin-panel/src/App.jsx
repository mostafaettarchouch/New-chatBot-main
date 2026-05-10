import { Routes, Route, Link, Navigate } from 'react-router-dom'
import Login from './pages/Login'
import Dashboard from './pages/Dashboard'
import Procedures from './pages/Procedures'
import UnansweredQuestions from './pages/UnansweredQuestions'
import './App.css'

function App() {
  const isAuthenticated = () => !!localStorage.getItem('token')

  return (
    <div className="admin-shell">
      <header className="admin-header">
        <div className="logo">لوحة إدارة المساعد القانوني</div>
        {isAuthenticated() && (
          <nav>
            <Link to="/dashboard">لوحة المعلومات</Link>
            <Link to="/procedures">الإجراءات</Link>
            <Link to="/questions">الأسئلة غير المجابة</Link>
            <button onClick={() => { localStorage.removeItem('token'); window.location.href = '/login'; }} style={{background: 'transparent', color: 'white', border: '1px solid white', cursor: 'pointer', padding: '5px 10px', marginLeft: '10px'}}>تسجيل الخروج</button>
          </nav>
        )}
      </header>
      <main className="admin-main">
        <Routes>
          <Route path="/login" element={<Login />} />
          <Route path="/dashboard" element={isAuthenticated() ? <Dashboard /> : <Navigate to="/login" />} />
          <Route path="/procedures" element={isAuthenticated() ? <Procedures /> : <Navigate to="/login" />} />
          <Route path="/questions" element={isAuthenticated() ? <UnansweredQuestions /> : <Navigate to="/login" />} />
          <Route path="/" element={isAuthenticated() ? <Navigate to="/dashboard" /> : <Navigate to="/login" />} />
        </Routes>
      </main>
    </div>
  )
}

export default App
