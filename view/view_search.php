<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <?php $pageTitle = "Search My Notes"; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Search Notes</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        .card-text {
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            /* Limit to 3 lines */
            -webkit-box-orient: vertical;
        }

        .checkbox-item {
            display: none;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            width: 100%;
            /* Adjust the width as needed */
        }

        .checkbox-item:nth-child(-n+3) {
            display: block;
        }

        /* Cache tous les éléments après le troisième */
        .checkbox-item:nth-child(n+4) {
            display: none;
        }

        /* Ajoute les points de suspension après le troisième élément */
        .checkbox-item:nth-child(3)::after {
            content: '...';
            display: block;
            position: relative;
            right: 0;
            bottom: 0;
        }
        .stretched-link {
            display: block;
            color: inherit;
            text-decoration: none;
            width: 100%;
            height: 100%;
            position: relative;
            z-index: 1;
        }

        .stretched-link::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
        }

        .label-badge {
            display: inline-block;
            padding: 0.25em 0.4em;
            font-size: 75%;
            font-weight: 700;
            line-height: 1;
            color: #fff;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.375rem;
            background-color: #6c757d;
            margin-right: 0.25em;
        }

        /* Hide the search button when JavaScript is enabled */
        .js-enabled #search-button {
            display: none;
        }
    </style>
</head>
<body>
    <?php include('navbar.php'); ?>
    <div class="container">
        <h1>Search Notes by Label</h1>
        <form method="post" action="./searchNotesByLabel" class="row g-3" id="label-form">
            <?php
            if (!empty($labels)) {
                foreach ($labels as $label) {
                    $checked = !empty($selectedLabels) && in_array($label, $selectedLabels) ? 'checked' : '';
                    echo '<div class="col-auto">';
                    echo '<div class="form-check">';
                    echo '<input class="form-check-input" type="checkbox" name="labels[]" value="' . htmlspecialchars($label) . '" id="label_' . htmlspecialchars($label) . '" ' . $checked . '>';
                    echo '<label class="form-check-label" for="label_' . htmlspecialchars($label) . '">' . htmlspecialchars($label) . '</label>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p>No labels found</p>';
            }
            ?>
            <!-- Search button for non-JavaScript users -->
            <div class="col-12">
                <button type="submit" id="search-button" class="btn btn-primary">Search</button>
            </div>
        </form>

        <div id="notes-container">
            <?php if (!empty($selectedLabels)): ?>
                <?php if (!empty($notes)): ?>
                    <!-- Display notes in cards -->
                    <h2 class="mt-4">Search Results</h2>
                    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-2 g-md-2 g-lg-3">
                        <?php foreach ($notes as $note): ?>
                            <div class="col-6 col-md-4 mb-3" data-id="<?= $note->id ?>">
                                <div class="card h-100" style="max-width: 18rem;">
                                    <div class="card-header"><?= htmlspecialchars($note->title) ?></div>
                                    <a href="<?php echo $web_root; ?>notes/show_note/<?= $note->id ?>" class="stretched-link">
                                        <div class="card-body">
                                            <?php if ($note instanceof TextNote): ?>
                                                <p class="card-text"><?= nl2br(htmlspecialchars($note->getTruncatedContent())) ?></p>
                                            <?php elseif ($note instanceof CheckListNote): ?>
                                                <ul class="list-group list-group-flush">
                                                    <?php foreach ($note->getItems() as $item): ?>
                                                        <div class="checkbox-item">
                                                            <input class="form-check-input me-1" type="checkbox" <?= $item->checked ? 'checked' : '' ?> disabled>
                                                            <?= htmlspecialchars($item->content) ?>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php endif; ?>
                                            <?php foreach ($note->labels as $label): ?>
                                                <span class="label-badge"><?= htmlspecialchars($label) ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>No notes found for selected labels.</p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@2.9.2/dist/js/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('body').addClass('js-enabled');
            // Add change event listener to checkboxes
            $('.form-check-input').change(function() {
                // Get the form data
                var formData = $('#label-form').serialize();

                // Send AJAX request to searchNotesByLabel
                $.ajax({
                    url: './searchNotesByLabel',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        // Update the notes container with the response
                        $('#notes-container').html($(response).find('#notes-container').html());
                    },
                    error: function() {
                        console.error('An error occurred during the AJAX request');
                    }
                });
            });
        });
    </script>
</body>
</html>
