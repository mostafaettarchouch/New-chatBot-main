import { useState } from 'react'
import { useNavigate } from 'react-router-dom'
import api from '../services/api'

function Login() {
  const [email, setEmail] = useState('')
  const [password, setPassword] = useState('')
  const navigate = useNavigate()

  const handleSubmit = async (e) => {
    e.preventDefault()
    try {
      const response = await api.post('/login', { email, password })
      localStorage.setItem('token', response.data.token)
      window.location.href = '/dashboard'
    } catch (error) {
      console.error('Login error:', error)
      alert('بيانات الدخول غير صحيحة أو هناك مشكلة في الاتصال بالخادم')
    }
  }

  return (
    <div className="card" style={{ maxWidth: 420, margin: 'auto' }}>
      <h2>تسجيل دخول المشرف</h2>
      <form onSubmit={handleSubmit}>
        <label>البريد الإلكتروني</label>
        <input value={email} onChange={(e) => setEmail(e.target.value)} type="email" required />
        <label>كلمة المرور</label>
        <input value={password} onChange={(e) => setPassword(e.target.value)} type="password" required />
        <button type="submit" style={{ width: '100%', background: '#003366', color: 'white' }}>
          تسجيل الدخول
        </button>
      </form>
    </div>
  )
}

export default Login
