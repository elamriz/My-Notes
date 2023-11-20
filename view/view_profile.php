<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?= $member->pseudo ?>'s Profile!</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div class="title"><?= $member->pseudo ?>'s Profile!</div>
        <?php include('menu.html'); ?>
        <div class="main">
            <?php if (!$member->profile): ?>
                No profile string entered yet!
            <?php else: ?>
                <?= $member->profile; ?>
            <?php endif; ?>
            <br><br>
            <?php if (!$member->picture_path): ?>
                No picture loaded yet!
            <?php else: ?>
                <img src='upload/<?= $member->picture_path ?>' width='100' alt="<?= $member->pseudo ?>&apos;s photo!">  
            <?php endif; ?>
            <br>
            <br>
            <a href="messages.php?param1=<?= $member->pseudo ?>">View <?= $member->pseudo ?>'s messages</a>
        </div>
    </body>
</html>