<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'model/User.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';

class ControllerMain extends Controller
{

    // Redirect to notes if logged in, otherwise show the home view.
    public function index(): void
    {
        if ($this->user_logged()) {
            $this->redirect("notes", "index");
        } else {
            (new View("login"))->show();
        }
    }


    // Handle user login
    public function login(): void
    {
        // Check if the user is already logged in
        if ($this->user_logged()) {
            // Redirect to the notes page
            $this->redirect("notes", "index");
        } else {
            // Proceed with normal login process
            $mail = '';
            $password = '';
            $errors = [];
            if (isset($_POST['mail']) && isset($_POST['password'])) {
                $mail = $_POST['mail'];
                $password = $_POST['password'];

                $errors = User::validate_login($mail, $password);
                if (empty($errors)) {
                    $this->log_user(User::get_user_by_mail($mail), "notes", "index");
                }
            }
            (new View("login"))->show(["mail" => $mail, "password" => $password, "errors" => $errors]);
        }
    }

    // Handle user logout
    public function logout(): void
    {
        $this->logout_user();
        $this->redirect("main", "login");
    }

    public function logout_user(): void
    {
        unset($_SESSION['user']);
    }

    // Handle user signup
    public function signup(): void
    {
        // Check if the user is already logged in
        if ($this->user_logged()) {
            // Redirect to the notes page
            $this->redirect("notes", "index");
        } else {
            // Proceed with normal signup process
            $mail = '';
            $full_name = '';
            $password = '';
            $password_confirm = '';
            $errors = [];

            if (isset($_POST['mail']) && isset($_POST['full_name']) && isset($_POST['password']) && isset($_POST['password_confirm'])) {
                $mail = trim($_POST['mail']);
                $full_name = trim($_POST['full_name']);
                $password = $_POST['password'];
                $password_confirm = $_POST['password_confirm'];


                // Updated User instantiation with full_name and a default role (if you have roles)
                $user = new User($mail, Tools::my_hash($password), $full_name, 'user');
                $errors = User::validate_email_unicity($mail);            // You may need to update the validate method in the User class or create a new one for mail and full name
                $errors = array_merge($errors, $user->validate());
                $errors = array_merge($errors, User::validate_passwords($password, $password_confirm));

                if (count($errors) == 0) {
                    $user->persist();
                    $this->log_user($user, "notes", "index");
                }
            }

            (new View("signup"))->show([
                "mail" => $mail, "full_name" => $full_name, "password" => $password,
                "password_confirm" => $password_confirm, "errors" => $errors
            ]);
        }
    }
    public function settings(): void
    {
        $user = $this->get_user_or_redirect();
        if (!$this->user_logged()) {
            $this->redirect("settings", "index");
        } else {
            (new View("settings"))->show(array("user" => $user));
        }
    }

    public function edit_profile(): void {
        $user = $this->get_user_or_redirect();
        $errors = [];
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newFullName = $_POST['fullName'] ?? '';
            $errors = $user->setFullName($newFullName);
    
            if (empty($errors)) {
                $this->redirect('Main', 'settings');
            } else {
                // Si des erreurs sont présentes, afficher à nouveau le formulaire avec des erreurs
                (new View("edit_profile"))->show(["user" => $user, "errors" => $errors]);
            }
        } else {
            (new View("edit_profile"))->show(["user" => $user]);
        }
    }

    public function edit_email(): void {
        $user = $this->get_user_or_redirect(); 
        $errors = [];
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newEmail = $_POST['newEmail'] ?? '';
    
            if (empty($newEmail)) {
                $errors[] = "The new email address is required.";
            } elseif (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "The new email address is not valid.";
            } else {
                $existingUser = User::get_user_by_mail($newEmail);
                if ($existingUser != null && $existingUser->id != $user->id) {
                    $errors[] = "This email address is already in use.";
                }
            }
    
            if (empty($errors)) {
                $user->mail = $newEmail;
                $user->persist(); 
    
                $this->redirect("main", "settings");
            }
        }
    
        (new View("edit_email"))->show(["user" => $user, "errors" => $errors]);
    }
    


    public function change_password(): void {
        $user = $this->get_user_or_redirect(); // Assurez-vous que l'utilisateur est connecté
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupération des données soumises
            $currentPassword = $_POST['password'] ?? '';
            $newPassword = $_POST['newPassword'] ?? '';
            $confirmNewPassword = $_POST['confirmNewPassword'] ?? '';

            if (empty($newPassword)) {
                $errors[] = "Le nouveau mot de passe est requis.";
            } else {
                if (strlen($newPassword) < 8) {
                    $errors[] = "Le mot de passe doit contenir au moins 8 caractères.";
                }
                if (!preg_match('/[A-Z]/', $newPassword)) {
                    $errors[] = "Le mot de passe doit contenir au moins une majuscule.";
                }
                if (!preg_match('/\d/', $newPassword)) {
                    $errors[] = "Le mot de passe doit contenir au moins un chiffre.";
                }
                if (!preg_match('/[^\da-zA-Z]/', $newPassword)) {
                    $errors[] = "Le mot de passe doit contenir au moins un caractère spécial.";
                }
            }
            // Vérifier si le mot de passe actuel est correct
            if (password_verify($currentPassword, $user->hashed_password)) {
                $errors[] = "Le mot de passe actuel est incorrect.";
            }
            // Vérifier si le nouveau mot de passe et la confirmation correspondent
            if ($newPassword !== $confirmNewPassword) {
                $errors[] = "Le nouveau mot de passe et la confirmation ne correspondent pas.";
            }

    
            // Vous pouvez également ajouter des validations supplémentaires pour le nouveau mot de passe ici
    
            // Si aucune erreur, mettre à jour le mot de passe
            if (empty($errors)) {
                $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT); // Hacher le nouveau mot de passe
                $user->hashed_password = $hashedNewPassword;
                $user->persist(); // Mettre à jour le mot de passe dans la base de données
    
                // Redirection vers une page de confirmation ou de paramètres
                $this->redirect("main", "settings");
            }
        }
    
        (new View("change_password"))->show(["user" => $user, "errors" => $errors]);

    }
    
}
