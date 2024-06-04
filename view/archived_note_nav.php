<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>Navigation Bar</title>
<!-- Link to Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Link to Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
<style>
  .navbar-custom {
    padding: 1px;
  }
  .navbar-custom .bi {
    color: white; /* White icons */
    font-size: 1.2rem; /* Icon size */
  }
  /* Add custom spacing if needed */
  .nav-link:not(:last-child) {
    margin-right: 1rem; /* Spacing between buttons */
  }
</style>
</head>
<body>

<nav class="navbar navbar-expand navbar-custom">
  <div class="container-fluid">
    <!-- Left aligned return button -->
    <a class="navbar-brand" href="<?php echo $web_root; ?>notes/archives">
      <i class="bi bi-arrow-left"></i> <!-- Left-pointing arrow -->
    </a>

    <!-- Right aligned icon buttons -->
    <div class="navbar-nav">
    <?php
        $noteType = $note instanceof TextNote ? 'text' : ($note instanceof CheckListNote ? 'checklist' : 'unknown');
      ?>
    <button type="button" class="deleteNote bi bi-file-earmark-x" style="color:red;" data-bs-toggle="modal" data-bs-target="#deleteConfirmationModal" data-note-id="<?= $note->id ?>" data-note-type="<?= $note->type ?>"></button>
<!-- Modale de confirmation de suppression -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir supprimer cette note ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Supprimer</button>
            </div>
        </div>
    </div>
</div>

<!-- Modales de résultat pour succès et erreur -->
<div class="modal fade" id="resultModal" tabindex="-1" aria-labelledby="resultModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resultModalLabel">Résultat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="resultMessage">
               la note a bien ete supprimer
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>


      <form class="nav-form" action="<?php echo $web_root; ?>Notes/archive_note" method="post">
        <input type="hidden" name="noteId" value="<?php echo $note->id; ?>">
        <button type="submit" class="btn btn-link">
          <i class="bi bi-download"></i>
        </button>
      </form>
    </div>
  </div>
</nav>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="<?php echo $web_root; ?>JS/modal.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

<div class="container mt-3">

<?php include('utils/util_dates.php'); ?>
<div class="container py-2">
    <!-- Main Form for Title -->
    <form>
        <!-- Title Field -->
        <div class="mb-3">
            <label for="titleInput" class="form-label">Title</label>
            <input type="text" class="form-control" id="titleInput" 
                   placeholder="Enter title" value="<?= htmlspecialchars($note->title) ?>" readonly>
        </div>
    </form> <!-- End of Main Form -->