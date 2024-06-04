<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <title>Changer de Mot de Passe - Google Keep</title>
    <base href="<?= $web_root ?>">
    <!-- Inclure Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <style>
        .header {
            margin-bottom: 40px;
        }
        .list-group-item {
            background-color: transparent;
            border: none;
        }
        .list-group-item:hover {
            background-color: #343a40;
        }
        .list-group-item a {
            color: #f8f9fa;
            text-decoration: none;
        }
        .form-container {
            max-width: 500px;
            margin: auto;
        }
    </style>
</head>

<body>
<nav class="navbar navbar-expand navbar-custom">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?php echo $web_root; ?>Main/Settings">
            <i class="bi bi-arrow-left"></i>
        </a>
    </div>
</nav>
    <div class="container mt-5">
        <div class="header text-center">
            <h1 class="text-white mb-3">Changer de Mot de Passe</h1>
            <p class="text-muted">Sécurisez votre compte en mettant à jour votre mot de passe</p>
        </div>
    
        <div class="form-container">
                <?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
            <form action="<?php echo $web_root; ?>Main/change_password" method="post">
                <div class="form-group mb-3">
                    <label for="currentPassword" class="text-white">Mot de passe actuel</label>
                    <input type="password" class="form-control" id="currentPassword" name="currentPassword" required>
                </div>
                <div class="form-group mb-3">
                    <label for="newPassword" class="text-white">Nouveau mot de passe</label>
                    <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                </div>
                <div class="form-group mb-3">
                    <label for="confirmNewPassword" class="text-white">Confirmer le nouveau mot de passe</label>
                    <input type="password" class="form-control" id="confirmNewPassword" name="confirmNewPassword" required>
                </div>
                <button type="submit" class="btn btn-primary">Changer de mot de passe</button>
            </form>
        </div>
    </div>

    <!-- Scripts Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
