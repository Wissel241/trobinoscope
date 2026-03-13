import { useState } from 'react'
import { useStudents } from '../contexts/StudentContext'

function Admin() {
  const { students, saveStudents } = useStudents()
  const [form, setForm] = useState({ nom: '', prenom: '', promotion: '', specialite: '', entreprise: '' })
  const [message, setMessage] = useState('')

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value })
  }

  const handleAdd = (e) => {
    e.preventDefault()
    if (!form.nom || !form.prenom || !form.promotion || !form.specialite || !form.entreprise) {
      setMessage('Tous les champs sont requis.')
      return
    }
    const newStudent = { ...form, id: Date.now() }
    const updated = [...students, newStudent]
    saveStudents(updated)
    setForm({ nom: '', prenom: '', promotion: '', specialite: '', entreprise: '' })
    setMessage('Étudiant ajouté avec succès.')
  }

  const handleDelete = (id) => {
    if (window.confirm('Êtes-vous sûr de vouloir supprimer cet étudiant ?')) {
      const updated = students.filter(student => student.id !== id)
      saveStudents(updated)
      setMessage('Étudiant supprimé avec succès.')
    }
  }

  return (
    <section className="section">
      <h2>Gestion des Étudiants</h2>
      {message && <p style={{ color: 'green' }}>{message}</p>}

      <div className="admin-panel">
        <div className="panel">
          <h3>Ajouter un Étudiant</h3>
          <form onSubmit={handleAdd}>
            <label>Nom:</label>
            <input type="text" name="nom" value={form.nom} onChange={handleChange} required />

            <label>Prénom:</label>
            <input type="text" name="prenom" value={form.prenom} onChange={handleChange} required />

            <label>Promotion:</label>
            <input type="text" name="promotion" value={form.promotion} onChange={handleChange} required />

            <label>Spécialité:</label>
            <input type="text" name="specialite" value={form.specialite} onChange={handleChange} required />

            <label>Entreprise:</label>
            <input type="text" name="entreprise" value={form.entreprise} onChange={handleChange} required />

            <button type="submit" className="btn">Ajouter</button>
          </form>
        </div>

        <div className="panel">
          <h3>Liste des Étudiants</h3>
          {students.length === 0 ? (
            <p>Aucun étudiant trouvé.</p>
          ) : (
            <table>
              <thead>
                <tr>
                  <th>Nom</th>
                  <th>Prénom</th>
                  <th>Promotion</th>
                  <th>Spécialité</th>
                  <th>Entreprise</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                {students.map(student => (
                  <tr key={student.id}>
                    <td>{student.nom}</td>
                    <td>{student.prenom}</td>
                    <td>{student.promotion}</td>
                    <td>{student.specialite}</td>
                    <td>{student.entreprise}</td>
                    <td>
                      <button className="btn" onClick={() => handleDelete(student.id)}>Supprimer</button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          )}
        </div>
      </div>
    </section>
  )
}

export default Admin