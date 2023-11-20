<?php

require_once 'Model/Member.php';

session_start();

function redirect($url="index.php", $statusCode = 303)
{
    header('Location: '.$url, true, $statusCode);
    die();
}

function user_logged(){
    if (!isset($_SESSION['user'])) {
        return false;
    } else {
        return true;
    }
}

function get_user_or_redirect()
{
    if (!user_logged()) {
        redirect();
    } else {
        $user = $_SESSION['user'];
    }
    return $user;
}

function sanitize($var)
{
    $var = stripslashes($var);
    $var = strip_tags($var);
    $var = htmlspecialchars($var);
    return $var;
}

//connecte l'utilisateur donné et redirige vers la page d'acceuil
function log_user($member){
    $_SESSION["user"] = $member;
    redirect();
    //see http://codingexplained.com/coding/php/solving-concurrent-request-blocking-in-php
    session_write_close(); 
}

function my_hash($password)
{
    $prefix_salt = "vJemLnU3";
    $suffix_salt = "QUaLtRs7";
    return md5($prefix_salt.$password.$suffix_salt);        
}

?>