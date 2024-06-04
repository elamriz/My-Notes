<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Labels</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        body {
            
            color: white;
        }
        .navbar-custom {
            padding: 1px;
        }
        .navbar-custom .bi {
            color: white; /* Icônes blanches */
            font-size: 1.2rem; /* Taille de l'icône */
        }
        .container {
            color: white; /* Couleur du texte */
        }
        .btn-delete {
            background-color: #dc3545; /* Couleur de fond pour les boutons de suppression */
            color: white;
        }
        .btn-add {
            background-color: #0d6efd; /* Couleur de fond pour le bouton d'ajout */
            color: white;
        }
        .input-group .form-control {
            background-color: #2a2a2a;
            color: white; /* Couleur du texte pour les champs de saisie */
            border-color: #495057;
        }
        .input-group-text {
            background-color: transparent;
            border: none;
        }
        .input-group + .input-group {
            margin-top: 10px; /* Ajoute de l'espace entre les groupes d'input */
        }
        .list-group-item {
            background-color: #2a2a2a;
            border: 1px solid #495057;
            color: white;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand navbar-custom">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?php echo $web_root; ?>notes/show_note/<?php echo $note->id; ?>">
            <i class="bi bi-arrow-left"></i> <!-- Left-pointing arrow -->
        </a>
    </div>
</nav>
<div class="container mt-5">
    <h1 class="mb-4">Manage Labels for Note: <?= htmlspecialchars($note->title) ?></h1>
    <?php if (!empty($success)): ?>
        <div class="alert alert-success" role="alert">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>
    <ul class="list-group">
        <?php if (!empty($labels)): ?>
            <?php foreach ($labels as $label): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?= htmlspecialchars($label) ?>
                    <form action="<?= $web_root ?>notes/remove_label" method="post" style="display:inline;">
                        <input type="hidden" name="note_id" value="<?= $note->id ?>">
                        <input type="hidden" name="label" value="<?= htmlspecialchars($label) ?>">
                        <button type="submit" class="btn btn-delete btn-sm"><i class="bi bi-dash-lg"></i></button>
                    </form>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <li class="list-group-item">No labels found.</li>
        <?php endif; ?>
    </ul>
    <form action="<?= $web_root ?>notes/add_label" method="post" class="mt-3">
        <input type="hidden" name="note_id" value="<?= $note->id ?>">
        <div class="input-group">
            <input type="text" name="label" class="form-control" list="availableLabels" placeholder="New Label" required>
            <datalist id="availableLabels">
                <?php foreach ($availableLabels as $availableLabel): ?>
                    <option value="<?= htmlspecialchars($availableLabel) ?>"></option>
                <?php endforeach; ?>
            </datalist>
            <button type="submit" class="btn btn-primary"><i class="bi bi-plus-lg"></i></button>
        </div>
        <?php if (!empty($errors)): ?>
            <div class="text-danger mt-2">
                <?php foreach ($errors as $error): ?>
                    <div><?= htmlspecialchars($error) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </form>
    <a href="<?= $web_root ?>notes/" class="btn btn-secondary mt-3">Back to Notes</a>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
