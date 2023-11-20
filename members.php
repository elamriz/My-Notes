<?php
require_once 'functions.php';
require_once 'Model/Member.php';
require_once 'framework/Tools.php';
require_once 'framework/Configuration.php';


$members = Member::get_members();
$web_root = Configuration::get("web_root");
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Members</title>
        <meta charset="UTF-8">
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div class="title">Members</div>
        <?php include('view/menu.html'); ?>
        <div class="main">
            <ul>
                <?php foreach ($members as $member): ?>
                    <li><a href='member/profile/<?= $member->pseudo ?>'><?= $member->pseudo ?></a>                        
                <?php endforeach; ?>
            </ul>
        </div>
    </body>
</html>