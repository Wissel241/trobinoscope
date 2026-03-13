<?php
require_once __DIR__ . '/../config/config.php';

class Admin {
    private $conn;
    
    public function __construct() {
        $this->conn = getDBConnection();
    }
    
    public function getStats() {
        $stats = [];
        
        // Nombre total d'utilisateurs
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM utilisateurs");
        $stmt->execute();
        $stats['users'] = $stmt->get_result()->fetch_assoc()['total'];
        
        // Nombre total de formations
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM formations");
        $stmt->execute();
        $stats['formations'] = $stmt->get_result()->fetch_assoc()['total'];
        
        // Nombre total d'avis
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM avis");
        $stmt->execute();
        $stats['avis'] = $stmt->get_result()->fetch_assoc()['total'];
        
        return $stats;
    }
    
    public function getLatestUsers($limit = 5) {
        $stmt = $this->conn->prepare("
            SELECT id, nom, prenom, email, date_inscription 
            FROM utilisateurs 
            ORDER BY date_inscription DESC 
            LIMIT ?
        ");
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getLatestFormations($limit = 5) {
        $stmt = $this->conn->prepare("
            SELECT id, titre, prix, duree, date_creation 
            FROM formations 
            ORDER BY date_creation DESC 
            LIMIT ?
        ");
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getAllFormations() {
        $stmt = $this->conn->prepare("SELECT * FROM formations ORDER BY titre");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getAllAvis() {
        $stmt = $this->conn->prepare("
            SELECT a.*, u.nom, u.prenom, f.titre 
            FROM avis a 
            JOIN utilisateurs u ON a.utilisateur_id = u.id 
            JOIN formations f ON a.formation_id = f.id 
            ORDER BY a.date_avis DESC
        ");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getAllUsers() {
        $stmt = $this->conn->prepare("SELECT * FROM utilisateurs ORDER BY nom, prenom");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    public function deleteFormation($id) {
        $stmt = $this->conn->prepare("UPDATE formations SET is_active = 0 WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            return ['success' => true];
        } else {
            return ['success' => false, 'errors' => ["Erreur lors de la suppression de la formation"]];
        }
    }
    
    public function deleteUser($id) {
        $stmt = $this->conn->prepare("UPDATE utilisateurs SET is_active = 0 WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            return ['success' => true];
        } else {
            return ['success' => false, 'errors' => ["Erreur lors de la suppression de l'utilisateur"]];
        }
    }
    
    public function deleteAvis($id) {
        $stmt = $this->conn->prepare("DELETE FROM avis WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            return ['success' => true];
        } else {
            return ['success' => false, 'errors' => ["Erreur lors de la suppression de l'avis"]];
        }
    }
    
    public function addStudent($nom, $prenom, $promotion, $specialite, $entreprise) {
        $stmt = $this->conn->prepare("INSERT INTO students (nom, prenom, promotion, specialite, entreprise) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $nom, $prenom, $promotion, $specialite, $entreprise);
        
        if ($stmt->execute()) {
            return ['success' => true];
        } else {
            return ['success' => false, 'errors' => ["Erreur lors de l'ajout de l'étudiant"]];
        }
    }
    
    public function deleteStudent($id) {
        $stmt = $this->conn->prepare("DELETE FROM students WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            return ['success' => true];
        } else {
            return ['success' => false, 'errors' => ["Erreur lors de la suppression de l'étudiant"]];
        }
    }
    
    public function getAllStudents() {
        $stmt = $this->conn->prepare("SELECT * FROM students ORDER BY nom, prenom");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}

$admin = new Admin();

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_student'])) {
        $result = $admin->addStudent($_POST['nom'], $_POST['prenom'], $_POST['promotion'], $_POST['specialite'], $_POST['entreprise']);
        $message = $result['success'] ? 'Étudiant ajouté avec succès.' : implode('<br>', $result['errors']);
    } elseif (isset($_POST['delete_student'])) {
        $result = $admin->deleteStudent($_POST['student_id']);
        $message = $result['success'] ? 'Étudiant supprimé avec succès.' : implode('<br>', $result['errors']);
    }
}

$students = $admin->getAllStudents();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Gestion des Étudiants</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <nav>
            <a href="index.html">Accueil</a>
            <a href="promotion.html">Promotion</a>
            <a href="apropos.html">À propos</a>
            <a href="administrateur.php">Administration</a>
        </nav>
    </header>

    <section class="section">
        <h2>Gestion des Étudiants</h2>
        <?php if ($message): ?>
            <p style="color: green;"><?php echo $message; ?></p>
        <?php endif; ?>

        <div class="admin-panel">
            <div class="panel">
                <h3>Ajouter un Étudiant</h3>
                <form method="POST">
                    <label for="nom">Nom:</label>
                    <input type="text" id="nom" name="nom" required>
                    
                    <label for="prenom">Prénom:</label>
                    <input type="text" id="prenom" name="prenom" required>
                    
                    <label for="promotion">Promotion:</label>
                    <input type="text" id="promotion" name="promotion" required>
                    
                    <label for="specialite">Spécialité:</label>
                    <input type="text" id="specialite" name="specialite" required>
                    
                    <label for="entreprise">Entreprise:</label>
                    <input type="text" id="entreprise" name="entreprise" required>
                    
                    <button type="submit" name="add_student" class="btn">Ajouter</button>
                </form>
            </div>

            <div class="panel">
                <h3>Liste des Étudiants</h3>
                <?php if (empty($students)): ?>
                    <p>Aucun étudiant trouvé.</p>
                <?php else: ?>
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
                            <?php foreach ($students as $student): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($student['nom']); ?></td>
                                    <td><?php echo htmlspecialchars($student['prenom']); ?></td>
                                    <td><?php echo htmlspecialchars($student['promotion']); ?></td>
                                    <td><?php echo htmlspecialchars($student['specialite']); ?></td>
                                    <td><?php echo htmlspecialchars($student['entreprise']); ?></td>
                                    <td>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
                                            <button type="submit" name="delete_student" class="btn" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet étudiant ?');">Supprimer</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <footer>
        <p>© 2026 Site de l'école</p>
    </footer>
</body>
</html>
