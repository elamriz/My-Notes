<?php $config = parse_ini_file("config/dev.ini", true);  ?>
<!DOCTYPE html>
<html lang="fr" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="<?= $web_root ?>"/>
    <title>Éditer Note Checklist</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <script src="lib/jquery-3.7.1.min.js" type="text/javascript"></script>
    <script src="lib/jquery-ui.min.js" type="text/javascript"></script>
    <script src="script/save.js" type="text/javascript"></script>
    <script>
    var validationRules = {
        titleMinLength: <?= $config['Rules']['title_min_length']; ?>,
        titleMaxLength: <?= $config['Rules']['title_max_length']; ?>,
        itemMinLength: <?= $config['Rules']['itemMinLength']; ?>,
        itemMaxLength: <?= $config['Rules']['itemMaxLength']; ?>
    };
    </script>
<script src="script/validation.js" type="text/javascript"></script>
</head>
<body class="bg-dark text-white">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="Note/index">
                <i class="bi bi-chevron-left"></i>
        </a>
        <button form="editCheckListNoteForm" type="submit" class="btn">
            <i class="bi bi-floppy-fill"></i>
        </button>
    </div>
</nav>

<div class="container mt-4">
    <div class=" text-white">
        <div class="card-header">
       <!-- <p class="text-muted">
            Created <?= isset($createdTimeAgo) ? $createdTimeAgo : 'N/A'; ?>.
            <?php if (isset($editedTimeAgo)): ?>
                Edited <?= $editedTimeAgo; ?>.
            <?php endif; ?>
        </p> -->

        </div>
        <div class="card-body">
        <h5 class="card-title">Title</h5>
            <form id="editCheckListNoteForm" action="note/save_checklist_note" method="post">
            <input type="text" class="form-control <?= isset($errors['title']) ? 'is-invalid' : ''; ?>" id="title"name="title" 
               value="<?= htmlspecialchars($note->title); ?>" required
               data-min-length="<?= $config['Rules']['title_min_length']; ?>" 
               data-max-length="<?= $config['Rules']['title_max_length']; ?>">
               <?php if (isset($errors['title'])): ?>
                <div class="invalid-feedback">
                    <?= htmlspecialchars($errors['title']); ?>
                </div>
                <?php endif; ?>
                <input type="hidden" name="note_id" value="<?= $note->id; ?>">
                <h5 class="card-title mt-4 mb-2">Items</h5>
                <?php foreach ($note->getItems() as $item): ?>
                    <div class="input-group mb-3">
                    <input type="text" id = "item<?= $item->id ?>" name="item<?= $item->id ?>" class="form-control item" value="<?= htmlspecialchars($item->content); ?>"
                    data-min-length="<?= $config['Rules']['itemMinLength']; ?>" 
                    data-max-length="<?= $config['Rules']['itemMaxLength']; ?>">
            
                    
                    <button class="btn btn-danger" form="formDelete<?= $item->id ?>" type="submit">
                        <i class="bi bi-dash-lg"></i>
                    </button>
                </div>    
 
                    
                    
                <?php endforeach; ?>

            </form> 
            <?php foreach ($note->getItems() as $item): ?>
                 <!-- la partie permettant de supprimer un item -->
                <form id="formDelete<?= $item->id ?>" action="Note/delete_checklist_item/<?= $note->id ?>" method="post" style="display:none;">                  
                            <input type="hidden" name="note_id" value="<?= $note->id ?>">
                            <input type="hidden" name="item_id" value="<?= $item->id ?>">
                            <input type="hidden" name="redirect_url" value="<?= $web_root; ?>note/editCheckListNote/<?= $note->id ?>">                
                </form>
                <?php endforeach; ?> 
               <!-- la partie permettant d'ajouter un nouvel item -->
                <form id="editCheckListNoteForm" action="note/add_checklist_item" method="post">
                    <input type="hidden" name="note_id" value="<?= $note->id; ?>">
                    <div class="input-group mb-3">
                        <input type="text" name="new_item" class="form-control" placeholder="New item">
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-plus-lg"></i>
                        </button>
                    </div>
                </form>
        </div>
    </div>
</div>

<div class="modal fade" id="unsavedChangesModal" tabindex="-1" aria-labelledby="unsavedChangesModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="unsavedChangesModalLabel">Unsaved changes !</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to leave this form ? <p>Changes you made will not be saved.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="leavePageButton">Leave Page</button>
      </div>
    </div>
  </div>
</div>
<!-- Bootstrap JS (Bundle includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>