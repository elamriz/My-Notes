<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Shared Notes</title>
    <!-- Bootstrap CSS and Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<style>
    .bi-arrow-left {
        font-size: 30px;
        position: fixed;
        top: 20px;
        left: 40px;
        /* Déplacer légèrement vers la droite */
        z-index: 1000;
        color: white;

    }
</style>

<body>
    <div class="container mt-5">
        <a href="<?php echo $web_root; ?>notes/show_note/<?php echo $note->id; ?>" class="bi bi-arrow-left"></a>
        <h2>Shares:</h2>
        <?php if (!empty($resultsOfSharedUsers) && !empty($permission)) : ?>
            <?php foreach ($resultsOfSharedUsers as $index => $sharedUser) : ?>

                <div class="input-group mb-3">
                    <input type="text" class="form-control" value="<?= htmlspecialchars($sharedUser->getFullName()) ?> (<?= $permission[$index] ? "editor" : "reader" ?>)" readonly>
                    <form action="<?= $web_root ?>Notes/togglePermission" method="post">
                        <input type="hidden" name="note_id" value="<?= $note->getId(); ?>">
                        <input type="hidden" name="user_id" value="<?= $sharedUser->get_id(); ?>">
                        <input type="hidden" name="editor" value="<?= $permission[$index] ? '1' : '0'; ?>">
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-yin-yang"></i>
                        </button>
                    </form>
                    <form action="<?= $web_root ?>Notes/deleteShare" method="post">
                        <input type="hidden" name="note_id" value="<?= $note->getId(); ?>">
                        <input type="hidden" name="user_id" value="<?= $sharedUser->get_id(); ?>">
                        <input type="hidden" name="editor" value="<?= $permission[$index] ? '1' : '0'; ?>">
                        <button class="btn btn-danger" type="submit">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>

                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p class="text-muted">This note is not shared yet.</p>
        <?php endif; ?>
        <!-- Sélecteur d'utilisateur et de permission avec bouton d'ajout -->
        <div class="input-group mb-3">
            <form action="<?= $web_root ?>Notes/addShare" method="post" class="input-group">
                <select class="form-select" id="userSelect" name="user_id" style="margin-right: -1px;">
                    <option value="">-User-</option>
                    <?php foreach ($usersToShareWith as $user) : ?>
                        <option value="<?= htmlspecialchars($user->id) ?>"><?= htmlspecialchars($user->full_name) ?></option>
                    <?php endforeach; ?>
                </select>

                <!-- Sélecteur de permission -->
                <select class="form-select" id="permissionSelect" name="permission" style="margin-right: -1px; margin-left: -1px;">
                    <option value="editor">Editor</option>
                    <option value="reader">Reader</option>
                </select>
                <input type="hidden" name="note_id" value="<?= htmlspecialchars($note->getId() ?? ''); ?>">

                <!-- Bouton de partage -->
                <button type="submit" class="btn btn-primary" style="margin-left: -1px;">+</button>
            </form>
        </div>

    </div>


    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>