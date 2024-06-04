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

  .nav-link:not(:last-child) {
    margin-right: 1rem; /* Spacing between buttons */
  }

  /* New styles for form inside navbar */
  .nav-form {
    display: inline-flex; /* Allows for alignment and spacing similar to nav-link */
    align-items: center; /* Align items vertically */
    margin-right: 1rem; /* Consistent spacing with other nav items */
  }

  .nav-form button {
    background: none;
    border: none;
    color: inherit;
    cursor: pointer;
    display: flex; /* Ensures the button content aligns properly */
    align-items: center; /* Centers the icon vertically */
    padding: .5rem; /* Adjust this value based on your alignment needs */
  }
</style>
</head>

<body>

<nav class="navbar navbar-expand navbar-custom">
  <div class="container-fluid">
    <!-- Left aligned return button -->
    <a class="navbar-brand" href="<?php echo $web_root; ?>notes/">
      <i class="bi bi-arrow-left"></i> <!-- Left-pointing arrow -->
    </a>

    <!-- Right aligned icon buttons -->
    <div class="navbar-nav">
      <a class="nav-link" href="<?php echo $web_root; ?>Notes/share/<?php echo $note->id; ?>">
        <i class="bi bi-share"></i> <!-- Share/connect icon -->
      </a>
      <a class="nav-link" href="<?php echo $web_root; ?>notes/manage_labels/<?php echo $note->id; ?>">
    <i class="bi bi-tags-fill"></i> <!-- Icone de libellés -->
</a>


      <!-- Pin/Unpin Form -->
      <form class="nav-form" action="<?php echo $web_root; ?>Notes/pin_or_unpin_note" method="post">
        <input type="hidden" name="noteId" value="<?php echo $note->id; ?>">
        <button type="submit" style="background:none;border:none;color:inherit;padding:0;margin:0;">
          <?php if ($note->pinned) { ?>
            <i class="bi bi-pin-fill"></i> <!-- Filled pin icon -->
          <?php } else { ?>
            <i class="bi bi-pin"></i> <!-- Pin or thumbtack -->
          <?php } ?>
        </button>
      </form>

      <form class="nav-form" action="<?php echo $web_root; ?>Notes/archive_note" method="post">
        <input type="hidden" name="noteId" value="<?php echo $note->id; ?>">
        <button type="submit" class="btn btn-link">
          <i class="bi bi-download"></i>
        </button>
      </form>
      <a class="nav-link" href="<?php echo $web_root; ?>Notes/<?php echo ($note instanceof TextNote) ? 'edit_note/' : 'editchecklistnote/'; ?><?php echo $note->id; ?>">
        <i class="bi bi-pencil"></i> <!-- Pencil icon for editing -->
      </a>
    </div>
    
</nav>


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
        <input type="text" class="form-control" id="titleInput" placeholder="Enter title" value="<?= htmlspecialchars($note->title) ?>" readonly>
      </div>
    </form> <!-- End of Main Form -->