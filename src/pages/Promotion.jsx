import { useStudents } from '../contexts/StudentContext'

function Promotion() {
  const { students } = useStudents()

  return (
    <section className="section">
      <h2>Les promotions de conception d'application</h2>
      <p className="section-intro">Découvrez le parcours inspirant de nos meilleurs élèves qui ont poursuivi leur chemin vers le succès.</p>

      <div className="cards">
        {students.map(student => (
          <div className="card" key={student.id}>
            <img className="card-img" src={`images/${student.nom.toLowerCase()}-${student.prenom.toLowerCase()}.svg`} alt={`Photo de ${student.nom} ${student.prenom}`} />
            <h3>{student.nom} {student.prenom}</h3>
            <p><strong>Promotion {student.promotion}</strong></p>
            <p>{student.specialite}</p>
            <p className="card-subtitle">{student.entreprise}</p>
          </div>
        ))}
      </div>
    </section>
  )
}

export default Promotion