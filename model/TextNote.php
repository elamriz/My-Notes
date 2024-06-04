<?php
require_once "Note.php";

class TextNote extends Note
{
    public string $content;
    public static $db;

    public function __construct(
        string $title,
        int $owner,
        bool $pinned,
        bool $archived,
        float $weight,
        string $content,
        ?int $id = null,
        ?DateTime $created_at = null,
        ?DateTime $edited_at = null
    ) {
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

        $this->content = $content;
    }

    // function isPinned and not archived pour ne pas montrer  les notes archivés meme si pinned
    public function isPinned(): bool
    {
        return $this->pinned && !$this->archived;
    }


    public function isArchived(): bool
    {
        return $this->archived;
    }
    public function getId()
    {
        return $this->id;
    }
    public function get_id(): ?int
    {
        return $this->id;
    }

    public function save(): void
    {
        parent::save(); // Appel de la méthode save() de la classe parente

        error_log("Saving TextNote"); // Pour le débogage

        try {
            $sql = 'INSERT INTO text_notes (note, content) VALUES (:note, :content)';
            $stmt = self::execute($sql, ['note' => $this->id, 'content' => $this->content]);
            error_log("TextNote saved with ID: " . $this->id); // Confirmation que la note a été enregistrée
        } catch (PDOException $e) {
            error_log('PDOException in save: ' . $e->getMessage());
        }
    }


    public function persist(): TextNote
    {
        parent::persist(); // First, call parent's persist method
        // Now handle the saving of TextNote specific fields
        try {
            $sql = 'INSERT INTO text_notes (note, content) VALUES (:note, :content)';
            self::execute($sql, ['note' => $this->id, 'content' => $this->content]);
        } catch (PDOException $e) {
            // Log error message
            error_log('PDOException in persist: ' . $e->getMessage());
        }
        return $this;
    }

    //persist method as required by the abstract parent class. in order to save the content of the note
    public function persistAdd(): TextNote
    {
        parent::persist(); // First, call parent's persist method
        if ($this->id === null) {
            throw new Exception("L'ID de la note n'a pas été défini.");
        }
        // Après l'appel à parent::persist(), $this->id devrait être défini.
        // Insérez ou mettez à jour la partie text_note.
        $this->persistTextNote();

        return $this;
    }

    public function getTruncatedContent(): string {
        $itemMaxLength = Configuration::get('item_max_length', 50);
        if (mb_strlen($this->content) > $itemMaxLength) {
            return mb_substr($this->content, 0, $itemMaxLength) . '...';
        }
        return $this->content;
    }

    public function persistTextNote()
    {
        // Vérifiez si l'ID de la note est défini
        if ($this->id !== null) {
            // Insérez ou mettez à jour dans la table `text_notes`
            $sql = "INSERT INTO text_notes (id, content) VALUES (:id, :content) ON DUPLICATE KEY UPDATE content = :content";
            self::execute($sql, ['id' => $this->id, 'content' => $this->content]);
        }
    }
}
