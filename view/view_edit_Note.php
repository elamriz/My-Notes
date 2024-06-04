<?php
// Vérifiez que la note à éditer est passée à la vue
if (!isset($note)) {
    throw new Exception("La note à éditer n'est pas définie.");
}
?>

<!DOCTYPE html>
<html lang="fr" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <base href="<?= $web_root ?>" />

    <title>Éditer la Note</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<style>
    .btn-create-note {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1000;
        background-color: transparent;
        border: none;
        padding: 0;
        cursor: pointer;
    }

    .btn-create-note img {
        width: 30px;
        height: 30px;
    }

    .bi-arrow-left {
        font-size: 30px;
        position: fixed;
        top: 20px;
        left: 40px;
        z-index: 1000;
        color: white;
    }

    .container-form {
        margin-top: 100px;
    }

    #text {
        height: 300px;
    }
</style>

<body>
    <script src="lib/jquery-3.7.1.min.js"></script>
    <script>
        var minLengthTitle = <?php echo Configuration::get('title_min_length'); ?>;
        var maxLengthTitle = <?php echo Configuration::get('title_max_length'); ?>;
        var minLengthContent = <?php echo Configuration::get('content_min_length'); ?>;
        var maxLengthContent = <?php echo Configuration::get('content_max_length'); ?>;
    </script>
    <script>
        var baseURL = '<?php echo $web_root; ?>';
    </script>
    <script src="lib/validation.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <div>
        <button type="submit" class="btn-create-note" form="editTextNoteForm">
            <img src="css/save-icon-14.png" />
        </button>
        <a id="backButton" href="javascript:void(0)" class="bi bi-arrow-left"></a>
    </div>

    <div class="container container-form">
        <form id="editTextNoteForm" action="notes/save_edited_note" method="post">
            <input type="hidden" name="id" value="<?= htmlspecialchars($note->id) ?>">

            <div class="mb-3 <?= !empty($errors['title']) ? 'is-invalid' : '' ?>">
                <input type="text" class="form-control" id="title" name="title"
                    value="<?= html_entity_decode($note->title); ?>" required
                    onchange="validateTitle()" onkeyup="validateTitle()"
                    data-original="<?= html_entity_decode($note->title); ?>">
                <div class="invalid-feedback">
                    <?= !empty($errors['title']) ? html_entity_decode($errors['title']) : '' ?>
                </div>
            </div>

            <div class="mb-3 <?= !empty($errors['text']) ? 'is-invalid' : '' ?>">
                <label for="text" class="form-label">Contenu</label>
                <textarea class="form-control" id="text" name="text" rows="5"
                    onchange="validateContent()" onkeyup="validateContent()"
                    required><?= htmlspecialchars($note->content) ?></textarea>
                <div class="invalid-feedback">
                    <?= !empty($errors['text']) ? htmlspecialchars($errors['text']) : '' ?>
                </div>
            </div>

            <button type="submit" class="btn-create-note">
                <img src="css/save-icon-14.png" />
            </button>
        </form>
    </div>

    <!-- Fenêtre modale pour avertissement avant de quitter -->
    <div class="modal fade" id="unsavedChangesModal" tabindex="-1" role="dialog" aria-labelledby="unsavedChangesModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="unsavedChangesModalLabel">Modifications non enregistrées</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Des modifications ont été effectuées. Êtes-vous sûr de vouloir quitter sans enregistrer ?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="cancelButton" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" id="confirmExitButton">Quitter sans enregistrer</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo $web_root; ?>JS/modal.js"></script>
</body>

</html>
