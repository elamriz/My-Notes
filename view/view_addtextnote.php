<?php
// add_textnote.php
// Assurez-vous que $user est défini et est l'instance de l'utilisateur actuel.
if (!isset($user)) {
    throw new Exception("User variable is not set for the view.");
}

$title = ''; // Valeur par défaut pour le titre
$text = ''; // Valeur par défaut pour le texte
?>

<!DOCTYPE html>
<html lang="fr" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <title>Ajouter une note textuelle</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<style>
    .btn-create-note {
        position: fixed;
        /* Fixed position */
        top: 20px;
        /* Distance from the top */
        right: 20px;
        /* Distance from the right */
        z-index: 1000;
        /* Ensure it's above other items */
        background-color: transparent;
        /* Fond transparent */
        border: none;
        /* Supprimer la bordure */
        padding: 0;
        /* Supprimer le rembourrage */
        cursor: pointer;
        /* Curseur de pointeur */
    }

    .btn-create-note img {
        width: 30px;
        /* Largeur de l'image */
        height: 30px;
        /* Hauteur de l'image */
    }

    .bi-arrow-left {
        font-size: 30px;
        position: fixed;
        top: 20px;
        left: 40px;
        /* Déplacer légèrement vers la droite */
        z-index: 1000;
        color: white;

    }

    .container-form {
        margin-top: 100px;
        /* Ajoutez plus de marge pour pousser le formulaire vers le bas */
    }

    #text {
        height: 300px;
        /* Définir une hauteur spécifique pour la zone de texte */
    }
</style>

<body>
    <script src="../lib/jquery-3.7.1.min.js"></script>
    <script>
        var minLengthTitle = <?php echo Configuration::get('title_min_length'); ?>;
        var maxLengthTitle = <?php echo Configuration::get('title_max_length'); ?>;
        var minLengthContent = <?php echo Configuration::get('content_min_length'); ?>;
        var maxLengthContent = <?php echo Configuration::get('content_max_length'); ?>;
    </script>
    <script>
        var baseURL = '/prwb_2324_c08/';
    </script>
    <script src="../lib/validation.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <div><a href="#"><button type="submit" class="btn-create-note">
                <img src="../css/save-icon-14.png" alt="Créer la note" />
            </button></a>
        <a href="javascript:history.back()" class="bi bi-arrow-left">

        </a>
    </div>
    <div class="container container-form">
        <form action="./add_textnote" method="post">
            <div class="mb-3">
                <label for="title" class="form-label">Titre</label>
                <input type="text" class="form-control <?= !empty($errors['title']) ? 'is-invalid' : '' ?>" id="title"
                    name="title" placeholder="Entrez le titre de la note" value="<?= htmlspecialchars($title) ?>"
                    required onchange="validateTitle()" onkeyup="validateTitle()">
                <div class="invalid-feedback">

                    <?php if (!empty($errors['title'])): ?>
                        <div class="invalid-feedback">
                            <?= htmlspecialchars($errors['title']) ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label for="text" class="form-label">Texte</label>
                    <textarea class="form-control <?= !empty($errors['text']) ? 'is-invalid' : '' ?>" id="text"
                        name="text" rows="3"
                        placeholder="Entrez le contenu de la note"><?= htmlspecialchars($text) ?></textarea>
                    <div class="invalid-feedback">

                        <?php if (!empty($errors['text'])): ?>
                            <div class="invalid-feedback">
                                <?= htmlspecialchars($errors['text']) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <button type="submit" class="btn-create-note">
                        <img src="../css/save-icon-14.png" alt="Créer la note" />
                    </button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>