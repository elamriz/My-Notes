<?php
require_once 'functions.php';
require_once 'Model/Member.php';
require_once 'framework/Configuration.php';

$pseudo = '';
$password = '';
$password_confirm = '';
$errors = [];


if(isset($_POST['pseudo']) && isset($_POST['password']) && isset($_POST['password_confirm'])){
    $pseudo = trim($_POST['pseudo']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];
    
    $member = new Member($pseudo, my_hash($password));
    $errors = Member::validate_unicity($pseudo);
    $errors = array_merge($errors, $member->validate());
    $errors = array_merge($errors, Member::validate_passwords($password, $password_confirm));

    if (count($errors) == 0) { 
        $member->persist(); //sauve l'utilisateur
        log_user($member);
    }
}

$web_root = Configuration::get("web_root"); 
?>


<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Sign Up</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div class="title">Sign Up</div>
        <div class="menu">
            <a href="index.php">Home</a>
        </div>
        <div class="main">
            Please enter your details to sign up :
            <br><br>
            <form id="signupForm" action="signup.php" method="post">
                <table>
                    <tr>
                        <td>Pseudo:</td>
                        <td><input id="pseudo" name="pseudo" type="text" size="16" value="<?= $pseudo ?>"></td>
                    </tr>
                    <tr>
                        <td>Password:</td>
                        <td><input id="password" name="password" type="password" size="16" value="<?= $password ?>"></td>
                    </tr>
                    <tr>
                        <td>Confirm Password:</td>
                        <td><input id="password_confirm" name="password_confirm" type="password" size="16" value="<?= $password_confirm ?>"></td>
                    </tr>
                </table>
                <input type="submit" value="Sign Up">
            </form>
            <?php if(count($errors)!=0): ?>
                <div class='errors'>
                    <br><br><p>Please correct the following error(s) :</p>
                    <ul>
                    <?php foreach($errors as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </body>
</html>
