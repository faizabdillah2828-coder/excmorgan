<?php
session_start();
require_once 'config/functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?redirect=edit_profile.php');
    exit;
}

// Get user information
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$stmt->bindParam(':id', $user_id);
$stmt->execute();
$user = $stmt->fetch();

if (!$user) {
    header('Location: login.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validate input
    if (empty($name) || empty($email)) {
        $error = "Nama dan email wajib diisi.";
    } else {
        // Check if email is already used by another user
        $check_email = $pdo->prepare("SELECT id FROM users WHERE email = :email AND id != :id");
        $check_email->bindParam(':email', $email);
        $check_email->bindParam(':id', $user_id);
        $check_email->execute();

        if ($check_email->rowCount() > 0) {
            $error = "Email sudah digunakan oleh pengguna lain.";
        } else {
            // Update user information
            if (!empty($password)) {
                // Hash the new password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $update_stmt = $pdo->prepare("UPDATE users SET name = :name, email = :email, password = :password WHERE id = :id");
                $update_stmt->bindParam(':password', $hashed_password);
            } else {
                // Update without changing password
                $update_stmt = $pdo->prepare("UPDATE users SET name = :name, email = :email WHERE id = :id");
            }

            $update_stmt->bindParam(':name', $name);
            $update_stmt->bindParam(':email', $email);
            $update_stmt->bindParam(':id', $user_id);

            if ($update_stmt->execute()) {
                $message = "Profil berhasil diperbarui!";

                // Update session data
                $user['name'] = $name;
                $user['email'] = $email;
                $_SESSION['user_name'] = $name;
                $_SESSION['user_email'] = $email;
            } else {
                $error = "Gagal memperbarui profil. Silakan coba lagi.";
            }
        }
    }
}

// Set title for the page
$title = 'Edit Profil';

// Start output buffering to capture content
ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Edit Profil</h1>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password Baru (kosongkan jika tidak ingin diubah)</label>
                        <input type="password" name="password" id="password" class="form-control">
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="account.php" class="btn btn-outline-secondary me-md-2">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
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