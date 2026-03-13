import { Routes, Route } from 'react-router-dom'
import { StudentProvider } from './contexts/StudentContext'
import Header from './components/Header'
import Footer from './components/Footer'
import Home from './pages/Home'
import Promotion from './pages/Promotion'
import About from './pages/About'
import Admin from './pages/Admin'

function App() {
  return (
    <StudentProvider>
      <Header />
      <Routes>
        <Route path="/" element={<Home />} />
        <Route path="/promotion" element={<Promotion />} />
        <Route path="/about" element={<About />} />
        <Route path="/admin" element={<Admin />} />
      </Routes>
      <Footer />
    </StudentProvider>
  )
}

export default App