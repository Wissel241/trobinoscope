import { Link } from 'react-router-dom'

function Home() {
  return (
    <>
      <section className="hero">
        <h1>Bienvenue sur le site de notre école</h1>
        <p>Un lieu d'apprentissage, de réussite et d'avenir.</p>
        <Link className="btn" to="/about">Découvrir l'école</Link>
      </section>

      <section className="section">
        <h2>🎓 Notre mission</h2>
        <p>Former les élèves dans un environnement moderne et motivant, en développant leurs compétences académiques et personnelles.</p>
      </section>

      <section className="section">
        <h2>Pourquoi nous choisir ?</h2>
        <div className="cards">
          <div className="card">
            <h3>Excellente pédagogie</h3>
            <p>Des enseignants qualifiés et des méthodes innovantes pour votre succès</p>
          </div>
          <div className="card">
            <h3>Technologie</h3>
            <p>Infrastructure moderne et ressources numériques de pointe</p>
          </div>
          <div className="card">
            <h3>Résultats</h3>
            <p>Taux de réussite élevé et insertion professionnelle garantie</p>
          </div>
        </div>
      </section>
    </>
  )
}

export default Home