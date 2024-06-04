<?php
require_once 'model/Note.php'; 
if(isset($_POST['order'])) {
  $newOrder = explode(',', $_POST['order']);
}
?>

<!DOCTYPE html>
<?php $pageTitle = "My Notes"; ?>  <!--  une variable pour le titre dans la navbar -->
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Notes</title>

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
            /* Ensures it behaves like a block element */
            color: inherit;
            /* Maintains the text color */
            text-decoration: none;
            /* Removes underline */
            width: 100%;
            /* Ensures it covers the full width */
            height: 100%;
            /* Ensures it covers the full height */
            position: relative;
            /* Adjust as necessary */
            z-index: 1;
            /* Brings the link to the front */
        }

        .stretched-link::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
        }

        .bi-card-checklist {

            color: #F1C40F;
            /* Couleur du texte en noir pour un bon contraste */
            font-size: 35px;
            /* Augmente la taille du texte (et du bouton) */
            padding: 10px 15px;
            /* Espacement intérieur pour augmenter la taille du bouton */
            border: none;
            /* Supprime la bordure par défaut */
            border-radius: 5px;
            /* Arrondit les coins du bouton */
            cursor: pointer;
            /* Change le curseur en main lors du survol */

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
    </style>
</head>

<body>



    <?php include "navbar.php"; ?>

  


    <div class="container mt-5">
        <!-- Bouton pour créer une nouvelle note -->
        <div class="fixed-bottom d-flex justify-content-end p-3">

            <a href="./show_addchecklistnote" class=" bi-card-checklist" style="border-radius: 40px; padding: 10px 20px;"></a>

            <form action="./show_addtextnote" method="post">
                <input type="hidden" name="title" value="Nouvelle Note">
                <input type="hidden" name="text" value="Contenu de la note">
                
                <button type="submit" class="btn" style="border-radius: 40px; padding: 10px 20px;">
                    <img src="../css/icons8-add-file-48.png" alt="Ajouter">
                </button>
            </form>
        </div>


        <!-- Pinned Notes -->
        <?php if (!empty($pinnedNotes)) : ?>
            
            <h2 class="mb-4">Pinned</h2>
            <div class="notes-container pinned-notes row row-cols-2 row-cols-md-3 row-cols-lg-5 g-2 g-md-2 g-lg-3 " data-pinned="true">
          
                <?php foreach ($pinnedNotes as $note) : ?>
                    
                    <div class="col-6 col-md-4 mb-3" data-id="<?= $note->id ?>">
                        <div class="card h-100" style="max-width: 18rem;">
                            <div class="card-header"><?= htmlspecialchars($note->title) ?></div>
                            <a href="./show_note/<?= $note->id ?>" class="stretched-link">
                                <div class="card-body">
                                    <?php if ($note instanceof TextNote) : ?>
                                        <p class="card-text"><?= nl2br(htmlspecialchars($note->getTruncatedContent())) ?></p>
                                    <?php elseif ($note instanceof CheckListNote) : ?>
                                        <ul class="list-group list-group-flush">
                                            <?php foreach ($note->getItems() as $item) : ?>
                                                <div class="checkbox-item">

                                                    <input class="form-check-input me-1" type="checkbox" <?= $item->checked ? 'checked' : '' ?> disabled>
                                                    <?= htmlspecialchars($item->content) ?>
                                                </div>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                    <?php foreach ($note->labels as $label) : ?>
                                        <span class="label-badge"><?= htmlspecialchars($label) ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </a>
                            <div class="card-footer">
                                <!-- Display Move Left Button if not at extreme left -->
                                <?php if ($note->getPreviousNote() !== null) : ?>
                                    <form action="./moveNoteLeft" method="post" class="float-start">
                                        <input type="hidden" name="noteId" value="<?= $note->id ?>">
                                     
                                        <button type="submit" class="btn btn-link text-light-blue">
                                            <i class="bi bi-caret-left-fill"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>



                                <!-- Display Move Right Button if not at extreme right -->
                                <?php if ($note->getNextNote() !== null) : ?>
                                    <form action="./moveNoteRight" method="post" class="float-end">
                                        <input type="hidden" name="noteId" value="<?= $note->id ?>">
                                  
                                        <button type="submit" class="btn btn-link text-light-blue">
                                            <i class="bi bi-caret-right-fill"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
           
        <?php endif; ?>

       


        <?php if (!empty($otherNotes)) : ?>
            <h2 class="mb-4">Others</h2>

            <div  class="notes-container other-notes row row-cols-2 row-cols-md-3 row-cols-lg-5 g-2 g-md-2 g-lg-3" data-pinned="false">
           
                <?php foreach ($otherNotes as $note) : ?>
                    
                    <?php if (!$note->isArchived()) : ?>
                        <div class="col-6 col-md-4 mb-3" data-id="<?= $note->id ?>">
                            <div class="card h-100" style="max-width: 18rem;">
                                <div class="card-header"><?= htmlspecialchars($note->title) ?></div>

                                <a href="./show_note/<?= $note->id ?>" class="stretched-link">
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

                                <div class="card-footer">
                                    <!-- Display Move Left Button if not at extreme left -->
                                    <?php if ($note->getPreviousNote() !== null) : ?>
                                        <form action="./moveNoteLeft" method="post" class="float-start">
                                            <input type="hidden" name="noteId" value="<?= $note->id ?>">
                                           
                                            <button type="submit" class="btn btn-link text-light-blue">
                                                <i class="bi bi-caret-left-fill"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>



                                    <!-- Display Move Right Button if not at extreme right -->
                                    <?php if ($note->getNextNote() !== null) : ?>
                                        <form action="./moveNoteRight" method="post" class="float-end">
                                            <input type="hidden" name="noteId" value="<?= $note->id ?>">
                                           
                                            <button type="submit" class="btn btn-link text-light-blue">
                                                <i class="bi bi-caret-right-fill"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>

                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            
        <?php elseif (empty($pinnedNotes)) : ?>
            <p>No notes found.</p>
        <?php endif; ?>
    </div>
    
    
    

    <script>
</script>
<!-- Dernière version de jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Dernière version de jQuery UI -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

<!-- Dernière version de jQuery UI Touch Punch -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>

<!-- Dernière version de Popper.js (nécessaire pour Bootstrap 5.x) -->
<script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.min.js"></script>

<!-- Dernière version de Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script> 


<script src="../JS/note-management.js"></script>
</body>

</html>