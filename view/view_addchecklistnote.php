<?php
$formSubmitted = isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST';
$validFields = $validFields ?? [];
?>
<!DOCTYPE html>
<html lang="fr" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>Ajouter une Note Checklist</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<style>
    .navbar-custom {
        padding: 1px;
    }

    .navbar-custom .bi {
        color: white;
        /* Icônes blanches */
        font-size: 1.2rem;
        /* Taille de l'icône */
    }

    .nav-link:not(:last-child) {
        margin-right: 1rem;
        /* Espacement entre les boutons */
    }

    .is-invalid {
        border-color: #dc3545;
    }

    .is-valid {
        border-color: #28a745;
    }

    .invalid-feedback {
        display: block;
        color: #dc3545;
    }

    .input-group-text {
        background: transparent;
        border: none;
    }

    /* Ajoutez cette règle pour cacher les icônes par défaut */
    .input-group-text .bi {
        display: none;
    }

    /* Affichez l'icône seulement si le champ est validé ou invalidé après la soumission */
    .is-valid+.input-group-append .bi,
    .is-invalid+.input-group-append .bi {
        display: inline-block;
    }
</style>

<body>
    
    <script src="../lib/jquery-3.7.1.min.js"></script>
    <script>
        var minLengthTitle = <?php echo Configuration::get('title_min_length'); ?>;
        var maxLengthTitle = <?php echo Configuration::get('title_max_length'); ?>;
        var minLengthItem = <?php echo Configuration::get('item_min_length'); ?>;
        var maxLengthItem = <?php echo Configuration::get('item_max_length'); ?>;
    </script>
     <script>
        var baseURL = '/prwb_2324_c08/';
    </script>
    <script src="../lib/validation.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <nav class="navbar navbar-expand navbar-custom">
        <div class="container-fluid">
            <!-- Bouton de retour aligné à gauche -->
            <a class="navbar-brand" href="javascript:history.back()">
                <i class="bi bi-arrow-left"></i> <!-- Flèche pointant vers la gauche -->
            </a>
            <a onclick="document.getElementById('checklistForm').submit(); return false;" style="cursor: pointer;">
                <i class="bi bi-floppy2-fill"></i>
            </a>

        </div>
    </nav>

    <!-- Formulaire pour ajouter une note checklist -->
    <div class="container mt-5">
        <form id="checklistForm" action="./add_checklistnote" method="post">

            <!-- Titre de la checklist -->
            <div class="mb-3">
                <label for="title" class="form-label">Titre</label>
                <?php $title = $title ?? ''; ?>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($title ?? ''); ?>" required onchange="validateTitle()"
                        onkeyup="validateTitle()">
                    <div class="invalid-feedback">
                    </div>
                <?php if (!empty($validFields['title'])): ?>

                <?php endif; ?>
            </div>

            <!-- Champs pour les éléments de la checklist -->
            <label class="form-label">Éléments</label>
            <?php for ($i = 1; $i <= 5; $i++): ?>
    <?php
    // Ensure the array key exists in $validFields
    $validKey = "item$i";
    if (!array_key_exists($validKey, $validFields)) {
        $validFields[$validKey] = false; // Initialize the key to false if it doesn't exist
    }
    ?>
    <div class="input-group mb-3 has-validation">
        <span class="input-group-text">.</span>
        <input type="text" name="item<?= $i ?>"
            class="form-control <?php if ($formSubmitted && !empty($errors["item$i"]))
                echo 'is-invalid';
            elseif ($formSubmitted && $validFields[$validKey])
                echo 'is-valid'; ?>"
            value="<?php echo htmlspecialchars($items[$i - 1] ?? ''); ?>" required onkeyup="validateItem(this)">
        <div class="invalid-feedback">
            <?php echo htmlspecialchars($errors["item$i"] ?? ''); ?>
        </div>
        <?php if ($validFields[$validKey]): ?>
            <!-- Your code to display the validation icon -->
        <?php endif; ?>
    </div>
<?php endfor; ?>

        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>