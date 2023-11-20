<?php

require_once "framework/Model.php";
require_once "Member.php";

class Message extends Model {

    public function __construct(public Member $author, public Member $recipient, public string $body, public bool $private, public ?int $post_id = NULL, public ?string $date_time = NULL) {

    }
    
    public function validate() : array {
        $errors = [];
        if(!Member::get_member_by_pseudo($this->author->pseudo)){
            $errors[] = "Incorrect author";
        }
        if(!Member::get_member_by_pseudo($this->recipient->pseudo)){
            $errors[] = "Incorrect recipient";
        }
        if(!(strlen($this->body) > 0)){
            $errors[] = "Body must be filled";
        }
        return $errors;
    }

    public static function get_messages(Member $member) : array {
        $query = self::execute("select * from Messages where recipient = :pseudo order by date_time DESC", ["pseudo" => $member->pseudo]);
        $data = $query->fetchAll();
        $messages = [];
        foreach ($data as $row) {
            $messages[] = new Message(Member::get_member_by_pseudo($row['author']), Member::get_member_by_pseudo($row['recipient']), $row['body'], $row['private'], $row['post_id'], $row['date_time']);
        }
        return $messages;
    }

    public static function get_message(int $post_id) : Message|false {
        $query = self::execute("select * from Messages where post_id = :id", ["id" => $post_id]);
        if ($query->rowCount() == 0) {
            return false;
        } else {
            $row = $query->fetch();
            return new Message(Member::get_member_by_pseudo($row['author']), Member::get_member_by_pseudo($row['recipient']), $row['body'], $row['private'], $row['post_id'], $row['date_time']);
        }
    }
   

    //supprimer le message si l'initiateur en a le droit
    //renvoie le message si ok. false sinon.
    public function delete(Member $initiator) : Message|false {
        if ($this->author == $initiator || $this->recipient == $initiator) {
            self::execute('DELETE FROM Messages WHERE post_id = :post_id', ['post_id' => $this->post_id]);
            return $this;
        }
        return false;
    }

    public function persist() : Message|array {
        if($this->post_id == NULL) {
            $errors = $this->validate();
            if(empty($errors)){
                self::execute('INSERT INTO Messages (author, recipient, body, private) VALUES (:author,:recipient,:body,:private)', 
                               ['author' => $this->author->pseudo,
                                'recipient' => $this->recipient->pseudo,
                                'body' => $this->body,
                                'private' => $this->private ? 1 : 0
                               ]);
                $message = self::get_message(self::lastInsertId());
                $this->post_id = $message->post_id;
                $this->date_time = $message->date_time;
                return $this;
            } else {
                return $errors; 
            }
        } else {
            //on ne modifie jamais les messages : pas de "UPDATE" SQL.
            throw new Exception("Not Implemented.");
        }
    }

}
