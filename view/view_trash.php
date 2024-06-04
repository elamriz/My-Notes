<!-- view/trash.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Trash</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Corbeille</h1>
        <?php if (count($archivedNotes) > 0): ?>
            <div class="list-group">
                <?php foreach ($archivedNotes as $note): ?>
                    <div class="list-group-item">
                        <h5><?= htmlspecialchars($note->title) ?></h5>
                        <div>
                            <form method="post" action="<?= $web_root ?>notes/restoreFromTrash" class="d-inline">
                                <input type="hidden" name="noteId" value="<?= $note->id ?>">
                                <button type="submit" class="btn btn-success">Restaurer</button>
                            </form>
                            <form method="post" action="<?= $web_root ?>notes/deleteNotePermanently" class="d-inline">
                                <input type="hidden" name="noteId" value="<?= $note->id ?>">
                                <button type="submit" class="btn btn-danger">Supprimer définitivement</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Aucune note dans la corbeille.</p>
        <?php endif; ?>
        <a href="<?= $web_root ?>notes" class="btn btn-primary mt-3">Retour</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
