<?php

require_once "framework/Model.php";
require_once "Message.php";

class Member extends Model {



    public function __construct(public string $pseudo, public string $hashed_password, public ?string $profile = null, public ?string $picture_path = null) {

    }

    public function write_message(Message $message) : Message|array {
        return $message->persist();
    }

    public function delete_message(Message $message) : Message|false {
        return $message->delete($this);
    }

    public function get_messages() : array {
        return Message::get_messages($this);
    }

    public function get_other_members_and_relationships() : array {
        $query = self::execute("SELECT pseudo,
                     (SELECT count(*) 
                      FROM Follows 
                      WHERE follower=:user and followee=Members.pseudo) as follower,
                     (SELECT count(*) 
                      FROM Follows 
                      WHERE followee=:user and follower=Members.pseudo) as followee
              FROM Members 
              WHERE pseudo <> :user 
              ORDER BY pseudo ASC", ["user" => $this->pseudo]);
        return $query->fetchAll();
    }

    public function follow(Member $followee) : void {
        if (!$this->follows($followee))
            self::add_follower($this->pseudo, $followee->pseudo);
    }

    public function unfollow(Member $followee) : void {
        self::delete_follower($this->pseudo, $followee->pseudo);
    }

    public function follows(Member $followee) : bool {
        $query = self::execute("SELECT count(*) FROM Follows where follower=:follower and followee=:followee", 
                               ["follower"=>$this->pseudo, "followee"=>$followee->pseudo]);
        $data = $query->fetch(); // un seul résultat au maximum
        return ((int)$data[0]) > 0;
    }

    private static function add_follower(string $user_pseudo, string $followee_pseudo) : bool {
        self::execute("INSERT INTO Follows VALUES (:user,:other)", ["user"=>$user_pseudo, "other"=>$followee_pseudo]);
        return true;
    }

    private static function delete_follower(string $user_pseudo, string $followee_pseudo) : bool {
        self::execute("DELETE FROM Follows WHERE follower = :user AND followee = :other", ["user"=>$user_pseudo, "other"=>$followee_pseudo]);
        return true;
    }

    public function persist() : Member {
        if(self::get_member_by_pseudo($this->pseudo))
            self::execute("UPDATE Members SET password=:password, picture_path=:picture, profile=:profile WHERE pseudo=:pseudo ", 
                          ["picture"=>$this->picture_path, "profile"=>$this->profile, "pseudo"=>$this->pseudo, "password"=>$this->hashed_password]);
        else
            self::execute("INSERT INTO Members(pseudo,password,profile,picture_path) VALUES(:pseudo,:password,:profile,:picture_path)", 
                          ["pseudo"=>$this->pseudo, "password"=>$this->hashed_password, "picture_path"=>$this->picture_path, "profile"=>$this->profile]);
        return $this;
    }

    public static function get_member_by_pseudo(string $pseudo) : Member|false {
        $query = self::execute("SELECT * FROM Members where pseudo = :pseudo", ["pseudo"=>$pseudo]);
        $data = $query->fetch(); // un seul résultat au maximum
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return new Member($data["pseudo"], $data["password"], $data["profile"], $data["picture_path"]);
        }
    }

    public static function get_members() : array {
        $query = self::execute("SELECT * FROM Members", []);
        $data = $query->fetchAll();
        $results = [];
        foreach ($data as $row) {
            $results[] = new Member($row["pseudo"], $row["password"], $row["profile"], $row["picture_path"]);
        }
        return $results;
    }
    
    private static function validate_password(string $password) : array {
        $errors = [];
        if (strlen($password) < 8 || strlen($password) > 16) {
            $errors[] = "Password length must be between 8 and 16.";
        } if (!((preg_match("/[A-Z]/", $password)) && preg_match("/\d/", $password) && preg_match("/['\";:,.\/?!\\-]/", $password))) {
            $errors[] = "Password must contain one uppercase letter, one number and one punctuation mark.";
        }
        return $errors;
    }
    
    public static function validate_passwords(string $password, string $password_confirm) : array {
        $errors = Member::validate_password($password);
        if ($password != $password_confirm) {
            $errors[] = "You have to enter twice the same password.";
        }
        return $errors;
    }
    
    public static function validate_unicity(string $pseudo) : array {
        $errors = [];
        $member = self::get_member_by_pseudo($pseudo);
        if ($member) {
            $errors[] = "This user already exists.";
        } 
        return $errors;
    }

    private static function check_password(string $clear_password, string $hash) : bool {
        return $hash === Tools::my_hash($clear_password);
    }

    public function validate() : array {
        $errors = [];
        if (!strlen($this->pseudo) > 0) {
            $errors[] = "Pseudo is required.";
        } if (!(strlen($this->pseudo) >= 3 && strlen($this->pseudo) <= 16)) {
            $errors[] = "Pseudo length must be between 3 and 16.";
        } if (!(preg_match("/^[a-zA-Z][a-zA-Z0-9]*$/", $this->pseudo))) {
            $errors[] = "Pseudo must start by a letter and must contain only letters and numbers.";
        }
        return $errors;
    }
    
    public static function validate_login(string $pseudo, string $password) : array {
        $errors = [];
        $member = Member::get_member_by_pseudo($pseudo);
        if ($member) {
            if (!self::check_password($password, $member->hashed_password)) {
                $errors[] = "Wrong password. Please try again.";
            }
        } else {
            $errors[] = "Can't find a member with the pseudo '$pseudo'. Please sign up.";
        }
        return $errors;
    }

    public static function validate_photo(array $file) : array {
        $errors = [];
        if (isset($file['name']) && $file['name'] != '') {
            if ($file['error'] == 0) {
                $valid_types = ["image/gif", "image/jpeg", "image/png"];
                if (!in_array($_FILES['image']['type'], $valid_types)) {
                    $errors[] = "Unsupported image format : gif, jpg/jpeg or png.";
                }
            } else {
                $errors[] = "Error while uploading file.";
            }
        }
        return $errors;
    }

    //pre : validate_photo($file) returns true
    public function generate_photo_name() : string {
        //note : time() est utilisé pour que la nouvelle image n'aie pas
        //       le meme nom afin d'éviter que le navigateur affiche
        //       une ancienne image présente dans le cache
        if ($_FILES['image']['type'] == "image/gif") {
            $saveTo = $this->pseudo . time() . ".gif";
        } else if ($_FILES['image']['type'] == "image/jpeg") {
            $saveTo = $this->pseudo . time() . ".jpg";
        } else if ($_FILES['image']['type'] == "image/png") {
            $saveTo = $this->pseudo . time() . ".png";
        }
        return $saveTo;
    }

}
