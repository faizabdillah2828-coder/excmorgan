<?php
session_start();
require_once 'config/functions.php';

// If user is already logged in, redirect to home
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    if (!empty($email) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['is_admin'] = $user['is_admin'];
            
            // Determine where to redirect
            if (isset($_GET['redirect'])) {
                $redirect = $_GET['redirect'];
                // Make sure the redirect is safe (within the site)
                if (strpos($redirect, '..') === false && strpos($redirect, 'http') !== 0) {
                    header('Location: ' . $redirect);
                    exit;
                }
            }
            
            header('Location: index.php');
            exit;
        } else {
            $error = "Email atau password salah.";
        }
    } else {
        $error = "Silakan lengkapi email dan password.";
    }
}

// Set title for the page
$title = 'Masuk';

// Start output buffering to capture content
ob_start();
?>

<div class="row justify-content-center mt-5">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header text-center">
                <h3>Masuk ke Akun</h3>
            </div>
            <div class="card-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" class="form-control">
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control">
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Masuk</button>
                    </div>
                </form>
                
                <div class="text-center mt-3">
                    <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Capture the content
$content = ob_get_clean();

// Include the layout
include 'includes/layout.php';
?>