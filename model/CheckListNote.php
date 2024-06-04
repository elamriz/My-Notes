<?php
require_once "Note.php";

class CheckListNote extends Note
{
    public $type = 'checklist';
    // Assuming no additional properties beyond those in Note

    public function __construct(
        string $title,
        int $owner,
        bool $pinned,
        bool $archived,
        float $weight,
        ?int $id = null,
        ?DateTime $created_at = null,
        ?DateTime $edited_at = null
    ) {
        // Call the parent constructor to set the common properties.
        parent::__construct(
            title: $title,
            owner: $owner,
            pinned: $pinned,
            archived: $archived,
            weight: $weight,
            id: $id,
            created_at: $created_at,
            edited_at: $edited_at
        );
    }
    public function getId()
    {
        return $this->id;
    }

    public function getItems(): array
    {
        $items = [];
        try {
            $sql = 'SELECT * FROM checklist_note_items WHERE checklist_note = :id ORDER BY id ASC';
            $stmt = self::execute($sql, ['id' => $this->id]);
            while ($row = $stmt->fetch()) {
                $items[] = new CheckListNoteItem(
                    checklist_note_id: $row['checklist_note'],
                    content: $row['content'],
                    checked: $row['checked'],
                    id: $row['id']
                );
            }
        } catch (PDOException $e) {
            // Log error message
            error_log('PDOException in getItems: ' . $e->getMessage());
        }
        return $items;
    }

    // Implement the save method as required by the abstract parent class.

    public function persist(): CheckListNote
    {
        parent::persist(); // Persist the common note attributes

        if ($this->id) {
            // Check if this checklist note already exists in the checklist_notes table
            $sql = 'SELECT COUNT(*) FROM checklist_notes WHERE id = :id';
            $exists = self::execute($sql, ['id' => $this->id])->fetchColumn() > 0;

            if (!$exists) {
                // Insert into checklist_notes table since it doesn't exist
                $sql = 'INSERT INTO checklist_notes (id) VALUES (:id)';
                self::execute($sql, ['id' => $this->id]);
            }
        }

        return $this;
    }


    // Cette méthode doit être définie dans votre classe CheckListNote pour vérifier l'existence d'une note
    public static function exists($id)
    {
        $sql = 'SELECT COUNT(*) FROM checklist_notes WHERE id = :id';
        $stmt = self::execute($sql, ['id' => $id]);
        return $stmt->fetchColumn() > 0;
    }

    // Additional CheckListNote-specific methods...
}
