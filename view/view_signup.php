<!DOCTYPE html>
<html lang="en">
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <base href="<?= $web_root ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body>
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-sm-8 col-md-6 col-lg-4">
                    <div class="card my-5">
                        <div class="card-body">
                            <h2 class="card-title text-center mb-4">Sign Up</h2>
                            <!-- Error messages -->
                            <?php if (!empty($errors)): ?>
                                <div class="alert alert-danger" role="alert">
                                    <?php foreach ($errors as $error): ?>
                                        <p><?php echo htmlspecialchars($error); ?></p>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <form action="main/signup" method="post">
                            <div class="mb-3">
                                <label for="mail" class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                                    <input type="email" class="form-control" id="mail" name="mail" placeholder="Email" value="<?php echo isset($mail) ? htmlspecialchars($mail) : ''; ?>" required>
                                </label>
                            </div>

                            <div class="mb-3">
                                <label for="full_name" class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                                    <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Full Name" value="<?php echo isset($full_name) ? htmlspecialchars($full_name) : ''; ?>" required>
                                </label>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                                </label>
                            </div>

                            <div class="mb-3">
                                <label for="password_confirm" class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                    <input type="password" class="form-control" id="password_confirm" name="password_confirm" placeholder="Confirm Password" required>
                                </label>
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary">Sign Up</button>
                            </div>
                            <div class="d-grid">
                                <a class="btn btn-outline-danger" href="main/login">Cancel</a>
                            </div>
                        </form>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
