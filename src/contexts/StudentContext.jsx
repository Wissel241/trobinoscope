import { createContext, useContext, useState, useEffect } from 'react'

const StudentContext = createContext()

export function StudentProvider({ children }) {
  const [students, setStudents] = useState([])

  useEffect(() => {
    const saved = localStorage.getItem('students')
    if (saved) {
      setStudents(JSON.parse(saved))
    } else {
      const initial = [
        { id: 1, nom: 'MENGUE', prenom: 'alpha', promotion: '2026', specialite: 'developpeur web', entreprise: 'Bourse d\'excellence' },
        { id: 2, nom: 'SEP', prenom: 'tresor', promotion: '2026', specialite: 'Ingénieur informatique', entreprise: 'Google France' },
        { id: 3, nom: 'LYNA', prenom: 'raoul', promotion: '2026', specialite: 'Designer graphique', entreprise: 'Agence créative parisienne' },
        { id: 4, nom: 'OTOGO', prenom: 'tresor', promotion: '2026', specialite: 'analyste data', entreprise: 'McKinsey & Company' }
      ]
      setStudents(initial)
      localStorage.setItem('students', JSON.stringify(initial))
    }
  }, [])

  const saveStudents = (newStudents) => {
    setStudents(newStudents)
    localStorage.setItem('students', JSON.stringify(newStudents))
  }

  return (
    <StudentContext.Provider value={{ students, saveStudents }}>
      {children}
    </StudentContext.Provider>
  )
}

export function useStudents() {
  return useContext(StudentContext)
}