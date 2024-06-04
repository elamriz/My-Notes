<?php

require_once "framework/Model.php";

class User extends Model
{




    public function __construct(
        public string $mail,
        public string $hashed_password,
        public string $full_name,
        public string $role = 'user',
        public ?int $id = null  // Optional parameter moved to the end
    ) {
    }

    public function get_id(): ?int
    {
        return $this->id;
    }
    public function get_fullName(): ?string
    {
        return $this->full_name;
    }

    public function get_mail(): string
    {
        return $this->mail;
    }

    public function get_notes(): array
    {
        $query = self::execute("SELECT * FROM notes WHERE owner = :owner", ["owner" => $this->id]);
        $data = $query->fetchAll();
        $notes = [];
        foreach ($data as $row) {
            $notes[] = new TextNote($row["title"], $row["owner"], $row["pinned"], $row["archived"], $row["weight"], $row["id"], new DateTime($row["created_at"]), new DateTime($row["edited_at"]));
        }
        return $notes;
    }


    public function persist(): User
    {
        if ($this->id) {
            self::execute(
                "UPDATE users SET mail=:mail, hashed_password=:hashed_password, full_name=:full_name, role=:role WHERE id=:id",
                ["id" => $this->id, "mail" => $this->mail, "hashed_password" => $this->hashed_password, "full_name" => $this->full_name, "role" => $this->role]
            );
        } else {
            self::execute(
                "INSERT INTO users(mail, hashed_password, full_name, role) VALUES(:mail, :hashed_password, :full_name, :role)",
                ["mail" => $this->mail, "hashed_password" => $this->hashed_password, "full_name" => $this->full_name, "role" => $this->role]
            );
            $this->id = self::lastInsertId();
        }
        return $this;
    }

    public static function get_user_by_mail(string $mail): User|false
    {
        $query = self::execute("SELECT * FROM users where mail = :mail", ["mail" => $mail]);

        $data = $query->fetch();
        if ($query->rowCount() == 0) {
            return false;
        } else {
            // Ensure that $data["id"] is cast to an integer
            return new User($data["mail"], $data["hashed_password"], $data["full_name"], $data["role"], intval($data["id"]));
        }
    }
    public static function get_user_by_id(int $id): User|false
    {
        $query = self::execute("SELECT * FROM users where id = :id", ["id" => $id]);

        $data = $query->fetch();
        if ($query->rowCount() == 0) {
            return false;
        } else {
            // Ensure that $data["id"] is cast to an integer
            return new User($data["mail"], $data["hashed_password"], $data["full_name"], $data["role"], intval($data["id"]));
        }
    }



    private static function validate_password(string $password): array
    {
        $errors = [];
        if (strlen($password) < 8 || strlen($password) > 16) {
            $errors[] = "Password length must be between 8 and 16.";
        }
        if (!((preg_match("/[A-Z]/", $password)) && preg_match("/\d/", $password) && preg_match("/['\";:,.\/?!\\-]/", $password))) {
            $errors[] = "Password must contain one uppercase letter, one number and one punctuation mark.";
        }
        return $errors;
    }

    public static function validate_passwords(string $password, string $password_confirm): array
    {
        $errors = User::validate_password($password);
        if ($password != $password_confirm) {
            $errors[] = "You have to enter twice the same password.";
        }
        return $errors;
    }

    public static function validate_email_unicity(string $mail): array
    {
        $errors = [];
        $user = self::get_user_by_mail($mail); // 

        if ($user) {
            $errors[] = "A user with this email already exists.";
        }
        return $errors;
    }

    public static function validate_full_name(string $full_name): array
    {
        $errors = [];
        if (!strlen($full_name) > 0) {
            $errors[] = "Full name is required.";
        }
        if (!(strlen($full_name) >= 3 && strlen($full_name) <= 16)) {
            $errors[] = "Full name length must be between 3 and 16.";
        }
        if (!(preg_match("/^[a-zA-Z][a-zA-Z0-9]*$/", $full_name))) {
            $errors[] = "Full name must start by a letter and must contain only letters and numbers.";
        }
        return $errors;
    }



    public static function check_password(string $clear_password, string $hash): bool
    {
        return $hash === Tools::my_hash($clear_password);
    }

    public static function verifyPassword(string $clear_password, string $hashed_password): bool {
        return password_verify($clear_password, $hashed_password);
    }

    public function validate(): array
    {
        $errors = [];


        // Assuming you now use $mail and $full_name instead of $pseudo

        if (is_null($this->mail) || strlen($this->mail) == 0) {
            $errors[] = "Email is required.";
        }
        if (is_null($this->full_name) || strlen($this->full_name) == 0) {
            $errors[] = "Full name is required.";
        }

        if (is_null($this->hashed_password) || strlen($this->hashed_password) == 0) {
            $errors[] = "Password is required.";
        }


        return $errors;
    }


    public static function validate_login(string $mail, string $password): array
    {
        $errors = [];
        $user = User::get_user_by_mail($mail);
        if ($user) {
            if (!self::check_password($password, $user->hashed_password)) {
                $errors[] = "Wrong password. Please try again.";
            }
        } else {
            $errors[] = "Can't find a user with the email '$mail'. Please sign up.";
        }
        return $errors;
    }



    public function getFullName(): string
    {
        return $this->full_name;
    }




    public function setFullName(string $newFullName){
        $errors = [];


       if (empty($newFullName)) {
            $errors[] = "Le nom complet est requis.";
        } elseif (strlen($newFullName) < 3 || strlen($newFullName) > 16) {
            $errors[] = "Le nom complet doit être entre 3 et 16 caractères.";
        } elseif (!preg_match("/^[a-zA-Z][a-zA-Z0-9]*$/", $newFullName)) {
            $errors[] = "Le nom complet doit commencer par une lettre et ne contenir que des lettres et des chiffres.";
        }
        
        if (empty($errors)) {
            // Mettre à jour le nom complet dans l'objet et dans la base de données
            $this->full_name = $newFullName;
            self::execute("UPDATE users SET full_name = :full_name WHERE id = :id", ["full_name" => $this->full_name, "id" => $this->id]);
        }
        return $errors;
    }
   



    public static function getAllUsersExceptCurrent($currentUserId)
    {
        $sql = "SELECT * FROM users WHERE id != :currentUserId";
        $query = self::execute($sql, ['currentUserId' => $currentUserId]);
        $data = $query->fetchAll();
        $users = [];
        foreach ($data as $row) {
            $users[] = new User($row['mail'], $row['hashed_password'], $row['full_name'], $row["role"], $row['id']);
        }
        return $users;
    }
}
