<?php
$formSubmitted = isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST';
$validFields = $validFields ?? [];
?>
<!DOCTYPE html>
<html lang="fr" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <base href="<?= $web_root ?>" />
    <title>Éditer Note Checklist</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <script src="lib/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/validation.js"></script>
    <script src="JS/modal.js"></script>
    <script>
        var minLengthTitle = <?php echo Configuration::get('title_min_length'); ?>;
        var maxLengthTitle = <?php echo Configuration::get('title_max_length'); ?>;
        var minLengthItem = <?php echo Configuration::get('item_min_length'); ?>;
        var maxLengthItem = <?php echo Configuration::get('item_max_length'); ?>;
        var baseURL = '<?php echo $web_root; ?>';
    </script>
    <style>
        .btn-delete,
        .btn-add {
            color: white;
        }

        .btn-delete {
            background-color: #dc3545;
        }

        .btn-add {
            background-color: #0d6efd;
        }

        .mb-3 {
            margin-bottom: 1rem;
        }

        .input-group .form-control {
            flex: 1 0 auto;
        }

        .input-group .btn {
            flex-shrink: 0;
        }

        .input-group .invalid-feedback {
            width: 100%;
            position: absolute;
            bottom: -10px;
            left: 0;
            font-size: 0.875em;
        }

        .input-group {
            position: relative;
            margin-bottom: 40px;
            padding-bottom: 1rem;
        }

        .invalid-feedback:empty {
            display: none !important;
        }

        .invalid-feedback {
            display: block !important;
        }

        .disabled {
            pointer-events: none;
            opacity: 0.5;
            filter: blur(1px);
        }

        .deleted-item {
            text-decoration: line-through;
            color: #aaa;
        }

        .navbar-custom {
            position: relative;
        }

        .save-button-container {
            position: absolute;
            top: 0;
            right: 0;
            margin: 10px;
        }

        .save-button-container .btn-link {
            font-size: 1.5rem;
            color: white;
            margin-right: 10px;
            margin-top: 5px;
        }

        .save-button-container .btn-link:hover {
            color: #f0f0f0;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand navbar-custom">
        <div class="container-fluid">
            <a id="backButton" class="navbar-brand" href="#">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div class="save-button-container">
                <button type="submit" class="btn btn-link" form="checklisteditForm">
                    <i class="bi bi-floppy2-fill"></i>
                </button>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
        <form id="checklisteditForm" action="notes/save_edited_checklistnote" method="post">
            <input type="hidden" name="id" value="<?php echo $note->id; ?>">
            <?php if (isset($note)): ?>
                <label for="title">Title</label>
                <div class="mb-3 <?php echo !empty($errors['title']) ? 'is-invalid' : ''; ?>">
                    <input type="text" class="form-control" id="title" name="title"
                        value="<?php echo html_entity_decode($note->title); ?>" required onchange="validateTitle()"
                        onkeyup="validateTitle()" data-original="<?php echo html_entity_decode($note->title); ?>">
                    <div class="invalid-feedback"></div>
                    <?php if (!empty($errors['title'])): ?>
                        <div class="invalid-feedback">
                            <?php echo html_entity_decode($errors['title']); ?>
                        </div>
                    <?php endif; ?>
                </div>
                <label for="items">Items</label>
                <?php foreach ($note->getItems() as $index => $item): ?>
                    <div class="input-group mb-3">
                        <div class="input-group-text bg-secondary">
                            <i class="bi <?php echo $item->checked ? 'bi-check-circle-fill' : 'bi-circle'; ?>"></i>
                        </div>
                        <input type="text"
                            class="form-control item-control <?php echo !empty($errors['items'][$item->id]) ? 'is-invalid' : ''; ?>"
                            name="items[<?php echo $item->id; ?>]" value="<?php echo html_entity_decode($item->content); ?>"
                            required onkeyup="validateItem(this)"
                            data-original="<?php echo html_entity_decode($item->content); ?>">
                        <div class="invalid-feedback"></div>
                        <?php if (!empty($errors['items'][$item->id])): ?>
                            <div class="invalid-feedback">
                                <?php echo html_entity_decode($errors['items'][$item->id]); ?>
                            </div>
                        <?php endif; ?>
                        <input type="hidden" name="note_id" value="<?php echo $note->id; ?>">
                        <input type="hidden" name="item_id" value="<?php echo $item->id; ?>">
                        <button class="btn btn-delete" type="button"
                            onclick="deleteItem(<?php echo $item->id; ?>, <?php echo $note->id; ?>)"><i
                                class="bi bi-dash-lg"></i></button>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            <?php if (isset($_SESSION['checklist_items'][$note->id])): ?>
                <?php foreach ($_SESSION['checklist_items'][$note->id] as $tempItemId => $item): ?>
                    <div class="input-group mb-3" novalidate>
                        <div class="input-group-text bg-secondary">
                            <i class="bi bi-circle"></i>
                        </div>
                        <input type="text" class="form-control" value="<?php echo html_entity_decode($item); ?>">
                        <input type="hidden" name="note_id" value="<?php echo $note->id; ?>">
                        <input type="hidden" name="temp_item_id" value="<?php echo $tempItemId; ?>">
                        <button class="btn btn-delete" type="submit"><i class="bi bi-dash-lg"></i></button>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </form>
        <label for="newItem">New Item</label>
        <form action="notes/add_checklist_item" method="post">
            <input type="hidden" name="note_id" value="<?php echo $note->id; ?>">
            <div class="input-group mb-3">
                <input type="text" class="form-control item-control" name="new_item" required
                    onkeyup="validateItem(this)">
                <div class="invalid-feedback"></div>
                <button class="btn btn-add" type="submit"><i class="bi bi-plus-lg"></i></button>
            </div>
        </form>
        <script>
            function deleteItem(itemId, noteId) {
                var form = document.createElement('form');
                form.method = 'post';
                form.action = 'notes/delete_checklist_item';

                var inputItemId = document.createElement('input');
                inputItemId.type = 'hidden';
                inputItemId.name = 'item_id';
                inputItemId.value = itemId;
                form.appendChild(inputItemId);

                var inputNoteId = document.createElement('input');
                inputNoteId.type = 'hidden';
                inputNoteId.name = 'note_id';
                inputNoteId.value = noteId;
                form.appendChild(inputNoteId);

                document.body.appendChild(form);
                form.submit();
            }
        </script>
    </div>

    <!-- Modal HTML -->
    <div class="modal fade" id="unsavedChangesModal" tabindex="-1" aria-labelledby="unsavedChangesModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="unsavedChangesModalLabel">Unsaved Changes</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    You have unsaved changes. Do you really want to leave without saving?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmExitButton">Leave</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
