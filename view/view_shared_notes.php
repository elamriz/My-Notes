<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<?php $pageTitle = "My Shared Notes"; ?>  <!--  une variable pour le titre dans la navbar -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Shared Notes</title>
    <!-- Bootstrap CSS and Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>

<body>

    <!-- Ici, ajoutez votre navigation bar et autres composants si nécessaire -->
    <?php include "navbar.php"; ?>
    <div class="container mt-5">
        <h2 class="mb-4">Notes Shared to you by <?= $userShare->get_fullName() ?> as Editor</h2>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach ($sharedAsEditor as $note) : ?>
                <div class="col">
                    <div class="card h-100">
                        <div class="card-header"><?= htmlspecialchars($note->title) ?></div>
                        <div class="card-body">
                            <?php if ($note instanceof TextNote) : ?>
                                <p class="card-text"><?= nl2br(htmlspecialchars($note->content)) ?></p>
                            <?php elseif ($note instanceof CheckListNote) : ?>
                                <ul class="list-group list-group-flush">
                                    <?php foreach ($note->getItems() as $item) : ?>
                                        <li class="list-group-item">
                                            <input class="form-check-input me-1" type="checkbox" <?= $item->checked ? 'checked' : '' ?> disabled>
                                            <?= htmlspecialchars($item->content) ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer">
                            <a href="<?php echo $web_root; ?>/Notes/show_note/<?= $note->id ?>" class="stretched-link"></a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <h2 class="mb-4 mt-5">Notes Shared to you by <?= $userShare->get_fullName() ?> as Reader</h2>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach ($sharedAsReader as $note) : ?>
                <div class="col">
                    <div class="card h-100">
                        <div class="card-header"><?= htmlspecialchars($note->title) ?></div>
                        <div class="card-body">
                            <?php if ($note instanceof TextNote) : ?>
                                <p class="card-text"><?= nl2br(htmlspecialchars($note->content)) ?></p>
                            <?php elseif ($note instanceof CheckListNote) : ?>
                                <ul class="list-group list-group-flush">
                                    <?php foreach ($note->getItems() as $item) : ?>
                                        <li class="list-group-item">
                                            <input class="form-check-input me-1" type="checkbox" <?= $item->checked ? 'checked' : '' ?> disabled>
                                            <?= htmlspecialchars($item->content) ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer">
                            <a href="<?php echo $web_root; ?>/Notes/show_note/<?= $note->id ?>" class="stretched-link"></a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>