<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?= $member->pseudo ?>'s Profile</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div class="title">Update Your Profile</div>
        <?php include('menu.html'); ?>
        <div class="main">
            <form method='post' action='member/edit_profile' enctype='multipart/form-data'>
                <p>Enter or edit your details and/or upload an image.</p>
                <textarea name='profile' cols='50' rows='3'><?= $member->profile ?></textarea><br><br>

                Image: <input type='file' name='image' accept="image/x-png, image/gif, image/jpeg"><br><br>
                <?php if ($member->picture_path): ?>
                    <img src='upload/<?= $member->picture_path ?>' width="100" alt="Profile image"><br><br>
                <?php endif; ?>
                <input type='submit' value='Save Profile'>
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
            <?php elseif (strlen($success) != 0): ?>
                <p><span class='success'><?= $success ?></span></p>
            <?php endif; ?>


        </div>
    </body>
</html>

