<?php
require_once 'framework/Configuration.php';

$web_root = Configuration::get("web_root"); 
?>


<!DOCTYPE html>
<html>
    <head>
        <title>Your Friends</title>
        <base href="<?= $web_root ?>"/>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div class="title">Your Friends</div>
        <?php include('view/menu.html'); ?>
        <div class="main">

        </div>
    </body>
</html>