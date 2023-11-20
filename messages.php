<?php
require_once 'functions.php';
require_once 'Model/Member.php';
require_once 'Model/Message.php';
require_once 'framework/Configuration.php';
require_once 'framework/Tools.php';

//détermine le destinataire courant
function get_recipient($user){
    if(!isset($_GET["param1"]) || $_GET["param1"]==""){
        return $user;
    } else{
        return Member::get_member_by_pseudo($_GET["param1"]);
    }
}

//supprime le message dont l'id est dans la request.
function delete($user){
    if (isset($_POST['param']) && $_POST['param'] != "")
    {
        $post_id = $_POST['param'];
        $message = Message::get_message($post_id);
        if ($message && ($message->author == $user || $message->recipient == $user))
        {
            $user->delete_message($message);
            $member = $message->recipient;
            redirect("messages.php?param1=".$member->pseudo);
        }
        else{
            Tools::abort("Wrong/missing param or action no permited");
        }
    }
    else{
        Tools::abort("Wrong/missing param or action no permited");   
    }
}

$user = get_user_or_redirect();

if(isset($_GET["action"]) && $_GET["action"] != ""){
    $action = sanitize($_GET["action"]);
    if($action === "delete"){
        delete($user);
    }
}

$recipient = get_recipient($user);

$errors = [];
if (isset($_POST['body'])) {
    $body = $_POST['body'];
    $private = isset($_POST['private']) ? TRUE : FALSE;
    $message = new Message($user, $recipient, $body, $private);
    $errors = $message->validate();
    if(empty($errors)){
        $user->write_message($message);                
    }
}

$messages = $recipient->get_messages();
$web_root = Configuration::get("web_root"); 
?>

<!DOCTYPE html>
<html>
    <head>
        <title><?= $recipient->pseudo ?>'s Messages</title>
        <base href="<?= $web_root ?>"/>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div class="title"><?= $recipient->pseudo ?>'s Messages</div>
        <?php include('view/menu.html'); ?>
        <div class="main">
            <form id="message_form" action="messages.php?param1=<?= $recipient->pseudo ?>" method="post">
                Type here to leave a message:<br>
                <textarea id="body" name="body" rows='3'></textarea><br>
                <input id="private" name="private" type="checkbox">Private message<br>
                <input id="post" type="submit" value="Post">
            </form>
            
            <?php if (count($errors) != 0): ?>
                <div class='errors'>
                    <p>Please correct the following error(s) :</p>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <p>These are <?= $recipient->pseudo ?>'s messages:</p>
            <table id="message_list" class="message_list">
                <tr>
                    <th>Date/Time</th>
                    <th>Author</th>
                    <th>Message</th>
                    <th>Private?</th>
                    <th>Action</th>
                </tr>
                <?php foreach($messages as $message): ?>
                    <?php if(($message->private && ($message->author == $user || $message->recipient == $user)) || !$message->private): ?>
                        <tr>
                            <td><?= $message->date_time ?></td>
                            <td><a href='member/profile/<?= $message->author->pseudo?>'><?= $message->author->pseudo ?></a></td>
                            <td><?= $message->body ?></td>
                            <td><input type='checkbox' disabled <?= ($message->private ? ' checked' : '') ?>></td>
                            <td>
                                <?php if($user == $message->author || $user == $message->recipient): ?>
                                    <form class='link' action='messages.php?action=delete' method='post' >
                                    	<input type='text' name='param' value='<?= $message->post_id ?>' hidden>
                                    	<input type='submit' value='erase'>
                                    </form>   
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endif;?>
                <?php endforeach; ?>               
            </table>
        </div>
    </body>
</html>
