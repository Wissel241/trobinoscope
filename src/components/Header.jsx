import { Link } from 'react-router-dom'

function Header() {
  return (
    <header>
      <nav>
        <Link to="/">Accueil</Link>
        <Link to="/promotion">Promotion</Link>
        <Link to="/about">À propos</Link>
        <Link to="/admin">Administration</Link>
      </nav>
    </header>
  )
}

export default Header