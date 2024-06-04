<!DOCTYPE html>
<html lang="fr" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirmer la suppression</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <!-- Custom styles -->
    <style>
        .icon-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%; /* Adjust height as needed */
        }

        .bi-file-earmark-x {
            font-size: 10rem; /* Adjust size as needed */
            color: red;
        }
    </style>
</head>
<body>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Confirmer la suppression</div>
                    <div class="card-body">
                    <p>Êtes-vous sûr de vouloir supprimer cette note ?</p>
                        <div class="icon-container">
                            <i class="bi bi-file-earmark-x"></i>
                        </div>
                        
                        <form action="./../delete_note/<?php echo $note->id; ?>" method="post">
                            <input type="hidden" name="note_id" value="<?php echo $note->id; ?>">
                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-danger">Confirmer</button>
                                <a href="./../archives" class="btn btn-secondary">Annuler</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha
