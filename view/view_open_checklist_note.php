<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Notes</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>

<body>



<?php
    // Vérifier si l'utilisateur est le propriétaire de la note
    if($note->owner === $user->id) {
        // L'utilisateur est l'auteur de la note
        if($note->isArchived()) {
            include('view/archived_note_nav.php');
        } else {
            include('view/standard_note_nav.php');
        }
    } else {
        // Vérifier si l'utilisateur est un éditeur de la note
        $sharedNotesAsEditor = NoteShare::getSharedNotesByRolesEdit($note->owner, $user->id);
        $isEditorOfNote = false;
        foreach($sharedNotesAsEditor as $sharedNote) {
            if($sharedNote->id === $note->id) {
                $isEditorOfNote = true;
                break;
            }
        }

        if($isEditorOfNote) {
            include('view/view_as_editor_nav.php');
        } else {
            include('view/view_as_reader_nav.php');
        }
    }
?>


    <!-- Checklist Items -->
    <label for="titleInput" class="form-label">Items</label>

    <?php
        if (isset($items) && is_array($items)) {
            usort($items, function($a, $b) {
                return $a->checked <=> $b->checked;
            });
        }
    ?>


    <?php if (isset($items) && is_array($items)): ?>
        <?php foreach ($items as $item): ?>
            <!-- Form for Each Checklist Item -->
            <form action="/prwb_2324_c08/Notes/check_or_uncheck_item" method="post">
            <!-- Hidden Inputs -->
            
            <input type="hidden" name="item_id" value="<?= $item->id ?>">
            <input type="hidden" name="note_id" value="<?= $note->id ?>">

            <div class="input-group mb-3">

                    <!-- Button Addon -->
                    <button class="btn btn-primary" type="submit" id="button-addon<?= $item->id ?>">
                        <?php if ($item->checked): ?>
                            <i class="bi bi-check-square-fill"></i> <!-- Icon for checked -->
                        <?php else: ?>
                            <i class="bi bi-square"></i> <!-- Icon for unchecked -->
                        <?php endif; ?>
                    </button>

                    <!-- Readonly Textbox for the Item -->
                    <input type="text" class="form-control" 
                       value="<?= html_entity_decode($item->content) ?>" 
                       readonly 
                       style="<?= $item->checked ? 'text-decoration: line-through;' : '' ?>">
            </div>
            </form>
        <?php endforeach; ?>
    <?php endif; ?>





</body>
</html>
