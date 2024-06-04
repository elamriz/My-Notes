<?php

require_once "framework/Model.php";

class NoteShare extends Model
{
    public ?int $userId;
    public ?int $noteId;
    public bool $editor;



    public function __construct(
        ?int $noteId,
        ?int $user = null,
        bool $editor = false
    ) {
        $this->noteId = $noteId;
        $this->userId = $user;
        $this->editor = $editor;
    }
    // Getters
    public function getUser(): int
    {
        return $this->userId;
    }

    public function getNote(): int
    {
        return $this->noteId;
    }

    public function getEditor(): bool
    {
        return $this->editor;
    }



    // Setters
    public function setUser(int $user): void
    {
        $this->userId = $user;
    }

    public function setNote(int $note): void
    {
        $this->noteId = $note;
    }

    public function setEditor(bool $editor): void
    {
        $this->editor = $editor;
    }

    public static function getSharedNotesByRolesRead(int $idOwner, int $idUser): array
    {
        $query = self::execute("SELECT DISTINCT note_shares.note
                                from note_shares
                                join notes on notes.id = note_shares.note 
                                join users on users.id = notes.owner
                                where editor = 0 
                                and note_shares.user =:iduser
                                and users.id = :idowner", ["iduser" => $idUser, "idowner" => $idOwner]);

        $data = $query->fetchAll();
        $result = [];
        foreach ($data as $row) {
            $result[] = Note::get_note_by_id($row["note"]);
        }
        return $result;
    }

    public static function isUserEditor(int $userId, int $noteId): bool
    {
        $query = self::execute("SELECT COUNT(*) FROM note_shares WHERE note = :noteId AND user = :userId AND editor = 1", [
            'noteId' => $noteId,
            'userId' => $userId
        ]);
        return $query->fetchColumn() > 0;
    }

    public static function getSharedNotesByRolesEdit(int $idOwner, int $idUser): array
    {
        $query = self::execute("SELECT DISTINCT note_shares.note
                                from note_shares
                                join notes on notes.id = note_shares.note 
                                join users on users.id = notes.owner
                                where editor = 1
                                and note_shares.user =:iduser
                                and users.id = :idowner", ["iduser" => $idUser, "idowner" => $idOwner]);

        $data = $query->fetchAll();
        $result = [];
        foreach ($data as $row) {
            $result[] = Note::get_note_by_id($row["note"]);
        }
        return $result;
    }
    public static function getSharingUsersWithCurrentUser($currentUserId)
    {
        $query = self::execute(
            "SELECT DISTINCT u.id, u.full_name
            FROM users u
            JOIN note_shares ns ON ns.user = u.id
            WHERE ns.note IN (
                SELECT note FROM note_shares WHERE user = :currentUserId
            )
            AND u.id != :currentUserId
            ORDER BY u.full_name ASC", 
            ["currentUserId" => $currentUserId]
        );
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addShare()
    {
        self::execute(
            "INSERT INTO note_shares(note, user , editor) VALUES(:noteId, :userId, :editor)",
            ["noteId" => $this->noteId, "userId" => $this->userId, "editor" => $this->editor]
        );
    }

    public function deleteShare()
    {
        self::execute("DELETE FROM note_shares WHERE note = :noteId AND user = :userId AND editor = :editor", [
            "noteId" => $this->noteId,
            "userId" => $this->userId,
            "editor" => $this->editor ? '1' : '0' // Assurez-vous que cela correspond au type de colonne dans votre base de données
        ]);
    }
    public function changePermission()
    {
        $this->editor = !$this->editor;
        // Met à jour la base de données
        self::execute(
            "UPDATE note_shares SET editor = :editor WHERE note = :noteId AND user = :userId",
            [
                "noteId" => $this->noteId,
                "userId" => $this->userId,
                "editor" => $this->editor ? '1' : '0'
            ]
        );
    }
}
