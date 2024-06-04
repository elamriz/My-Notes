<?php
require_once "framework/Model.php";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class CheckListNoteItem extends Model {
    public int $checklist_note_id;
    public string $content;
    public bool $checked;
    public ?int $id;
    private static $instance = null;
    private $error;


    public function __construct(
        int $checklist_note_id,
        string $content,
        bool $checked,
        ?int $id = null
    ) {
        $this->checklist_note_id = $checklist_note_id;
        $this->content = $this->validateContent($content);
        $this->checked = $checked;
        $this->id = $id;
    }

    public static function isContentUnique(int $checklist_note_id, string $content, ?int $item_id = null): bool {
        // Prepare the SQL query to check for existing content in the same checklist note
        $sql = "SELECT COUNT(*) FROM checklist_note_items WHERE checklist_note = :checklist_note_id AND content = :content";
        $params = ['checklist_note_id' => $checklist_note_id, 'content' => $content];
    
        // Exclude the current item if updating
        if ($item_id !== null) {
            $sql .= " AND id != :item_id";
            $params['item_id'] = $item_id;
        }
    
        $stmt = self::execute($sql, $params);
        return $stmt->fetchColumn() == 0;  // Return true if no duplicates found
    }

    function validateContent($content) {
        if (strlen($content) < 1 || strlen($content) > 60) {
            return "Content must be between 1 and 60 characters long.";
        }
        return $content;
    }
  
    public function toggleChecked() {
        $this->checked = !$this->checked;
        Note::updateEditedAt($this->checklist_note_id);


    }
   

    public static  function get_item_by_id(int $id): ?CheckListNoteItem {
        $sql = 'SELECT * FROM checklist_note_items WHERE id = :id';
        $stmt = self::execute($sql, ['id' => $id]);
        if ($stmt && $row = $stmt->fetch()) {
            return new CheckListNoteItem(
                checklist_note_id: $row['checklist_note'],
                content: $row['content'],
                checked: (bool)$row['checked'], // Cast to boolean
                id: $row['id']
            );
        }
        return null;
    }
    public function getError(): ?string {
        return $this->error;
    }

    public function save() {
        $sql = 'INSERT INTO checklist_note_items (checklist_note, content, checked) 
                VALUES (:checklist_note_id, :content, :checked)';
        self::execute($sql, [
            'checklist_note_id' => $this->checklist_note_id,
            'content' => $this->content,
            'checked' => $this->checked,
        ]);
        $this->id = self::connect()->lastInsertId();
    }

    public function update() {
        $sql = 'UPDATE checklist_note_items SET content = :content, checked = :checked WHERE id = :id';
        self::execute($sql, [
            'id' => $this->id,
            'content' => $this->content,
            'checked' => $this->checked ? 1 : 0, // Convert to integer
        ]);
    }
    public static function connect() {
        if (self::$instance === null) {
            try {
                $dsn = 'mysql:host=your_host;dbname=your_db_name;charset=utf8';
                $username = 'your_username';
                $password = 'your_password';
                self::$instance = new PDO($dsn, $username, $password);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                // Gérer l'erreur de connexion ici
                error_log("Erreur de connexion PDO : " . $e->getMessage());
                // Vous pouvez choisir de lever une exception ou gérer l'erreur différemment
                throw $e;
            }
        }
        return self::$instance;
    }
    public function persist() {
        // Convert boolean value to integer
        $checkedInt = $this->checked ? 1 : 0;

        if ($this->id) {
            // Update existing checklist note item
            $sql = 'UPDATE checklist_note_items SET checklist_note = :checklist_note_id, content = :content, checked = :checked WHERE id = :id';
            $stmt = self::execute($sql, [
                'id' => $this->id,
                'checklist_note_id' => $this->checklist_note_id,
                'content' => $this->content,
                'checked' => $checkedInt
            ]);
            error_log("Updated checklist note item rows: " . $stmt->rowCount());
        } else {
            // Insert new checklist note item
            $sql = 'INSERT INTO checklist_note_items (checklist_note, content, checked) VALUES (:checklist_note_id, :content, :checked)';
            $stmt = self::execute($sql, [
                'checklist_note_id' => $this->checklist_note_id,
                'content' => $this->content,
                'checked' => $checkedInt
            ]);
            $this->id = self::connect()->lastInsertId(); // Set the ID of the new checklist note item
            if ($this->id === null) {
                $this->error = "Erreur lors de la récupération de l'ID de CheckListNote après insertion.";
            }
        }
    }
    public function setChecklistNoteId($id) {
        $this->checklist_note_id = $id;
    }

    public function persistAdd() {
        // Convertir la valeur booléenne en entier pour la base de données
        $checkedInt = $this->checked ? 1 : 0;
        
        try {
            // Vérifiez que l'ID de la note de checklist est valide
            if (!$this->checklist_note_id || !CheckListNote::exists($this->checklist_note_id)) {
                throw new Exception("L'ID de CheckListNote n'est pas valide ou n'existe pas.");
            }

            if ($this->id) {
                // Mise à jour d'un élément de checklist existant
                $sql = 'UPDATE checklist_note_items SET checklist_note = :checklist_note_id, content = :content, checked = :checked WHERE id = :id';
                $stmt = self::execute($sql, [
                    'id' => $this->id,
                    'checklist_note_id' => $this->checklist_note_id,
                    'content' => $this->content,
                    'checked' => $checkedInt
                ]);
                
                // Si aucune ligne n'est affectée, log l'erreur
                if ($stmt->rowCount() === 0) {
                    throw new Exception("Aucune mise à jour effectuée, vérifiez l'ID.");
                }
            } else {
                // Insertion d'un nouvel élément de checklist
                $sql = 'INSERT INTO checklist_note_items (checklist_note, content, checked) VALUES (:checklist_note_id, :content, :checked)';
                $stmt = self::execute($sql, [
                    'checklist_note_id' => $this->checklist_note_id,
                    'content' => $this->content,
                    'checked' => $checkedInt
                ]);
                
                // Vérifier si l'insertion a été réussie et récupérer l'ID
                if ($stmt->rowCount() > 0) {
                    $this->id = self::lastInsertId();
                } else {
                    throw new Exception("L'insertion a échoué, aucun élément ajouté.");
                }
            }
        } catch (Exception $e) {
            // Log l'erreur avec l'exception capturée
            error_log("Erreur lors de l'insertion/mise à jour de l'élément : " . $e->getMessage());
            // Vous pouvez choisir de renvoyer l'erreur, de lancer une nouvelle exception ou de gérer autrement
        }
    }
    public function delete() {
        // Assurez-vous que l'ID de l'élément est défini
        if ($this->id === null) {
            throw new Exception("L'ID de l'élément est requis pour la suppression.");
        }

        $sql = 'DELETE FROM checklist_note_items WHERE id = :id';
        $stmt = self::execute($sql, ['id' => $this->id]);

        if ($stmt->rowCount() === 0) {
            throw new Exception("L'élément n'a pas été supprimé ou n'a pas été trouvé.");
        }
    }

    
    

    // Additional CheckListNoteItem-specific methods...
}
