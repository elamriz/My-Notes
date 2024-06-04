<nav class="navbar fixed-top navbar-dark bg-dark">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="#">
            <img src="<?php echo $web_root; ?>/css/logo.png" alt="Logo" style="height: 50px; margin-right: 10px;"> <!-- Adjust the height as needed -->
            <?php echo htmlspecialchars($pageTitle); ?>

        </a>
        <div class="offcanvas offcanvas-start custom-offcanvas" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header">
                <!-- Use the Bootstrap class for text color -->
                <h5 class="offcanvas-title text-warning" id="offcanvasNavbarLabel">Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                    <li class="nav-item active">
                        <a class="nav-link" href="<?php echo $web_root; ?>Notes/">My notes <span class="sr-only"></span></a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="<?php echo $web_root; ?>Notes/search">Search <span class="sr-only"></span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $web_root; ?>Notes/archives">My archives</a>


                    <li class="nav-item">
                        <?php foreach ($sharedNotes as $usershared) : ?>
                            <a class="nav-link" href="<?php echo $web_root; ?>Notes/shared/<?= $usershared->get_id(); ?>">
                                Shared By <?= $usershared->get_fullName(); ?>
                            </a>

                        <?php endforeach; ?>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $web_root; ?>Main/settings">Settings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $web_root; ?>Main/logout">Logout</a>
                    </li>
                    <!-- Add more menu items here as needed -->
                </ul>
                <!-- Optional: Add a search form or other elements here -->
            </div>
        </div>
    </div>
</nav>
<br>