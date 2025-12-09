<?php
session_start();
require_once '../config/functions.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: ../login.php');
    exit;
}

// Handle form submissions
$message = '';
$error = '';

// Update site settings
if (isset($_POST['update_settings'])) {
    $contact_email = trim($_POST['contact_email']);
    $contact_phone = trim($_POST['contact_phone']);
    $address = trim($_POST['address']);
    $instagram = trim($_POST['instagram']);
    $tiktok = trim($_POST['tiktok']);
    $contact_form_recipient = trim($_POST['contact_form_recipient']);

    try {
        // Update each setting individually
        $stmt = $pdo->prepare("UPDATE site_settings SET value = :value WHERE key_name = :key_name");

        $stmt->bindParam(':value', $contact_email);
        $stmt->bindValue(':key_name', 'contact_email');
        $stmt->execute();

        $stmt->bindParam(':value', $contact_phone);
        $stmt->bindValue(':key_name', 'contact_phone');
        $stmt->execute();

        $stmt->bindParam(':value', $address);
        $stmt->bindValue(':key_name', 'address');
        $stmt->execute();

        $stmt->bindParam(':value', $instagram);
        $stmt->bindValue(':key_name', 'instagram');
        $stmt->execute();

        $stmt->bindParam(':value', $tiktok);
        $stmt->bindValue(':key_name', 'tiktok');
        $stmt->execute();

        $stmt->bindParam(':value', $contact_form_recipient);
        $stmt->bindValue(':key_name', 'contact_form_recipient');
        $stmt->execute();

        $message = "Pengaturan kontak berhasil diperbarui!";
    } catch (Exception $e) {
        $error = "Gagal memperbarui pengaturan: " . $e->getMessage();
    }
}

// Get current site settings
$stmt = $pdo->query("SELECT key_name, value FROM site_settings");
$settings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

// Set default values if not set
$contact_email = $settings['contact_email'] ?? '';
$contact_phone = $settings['contact_phone'] ?? '';
$address = $settings['address'] ?? '';
$instagram = $settings['instagram'] ?? '';
$tiktok = $settings['tiktok'] ?? '';
$contact_form_recipient = $settings['contact_form_recipient'] ?? '';

// Set title for the page
$title = 'Pengaturan Kontak & Sosial';

// Start output buffering to capture content
ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Pengaturan Kontak & Sosial</h1>
</div>

<?php if ($message): ?>
    <div class="alert alert-success"><?php echo $message; ?></div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="contact_email" class="form-label">Email Kontak</label>
                    <input type="email" name="contact_email" id="contact_email" value="<?php echo htmlspecialchars($contact_email); ?>" class="form-control">
                </div>

                <div class="col-md-6 mb-3">
                    <label for="contact_phone" class="form-label">Nomor Telepon</label>
                    <input type="text" name="contact_phone" id="contact_phone" value="<?php echo htmlspecialchars($contact_phone); ?>" class="form-control">
                </div>

                <div class="col-md-12 mb-3">
                    <label for="address" class="form-label">Alamat</label>
                    <textarea name="address" id="address" rows="3" class="form-control"><?php echo htmlspecialchars($address); ?></textarea>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="instagram" class="form-label">URL Instagram</label>
                    <input type="url" name="instagram" id="instagram" value="<?php echo htmlspecialchars($instagram); ?>" class="form-control">
                </div>

                <div class="col-md-6 mb-3">
                    <label for="tiktok" class="form-label">URL TikTok</label>
                    <input type="url" name="tiktok" id="tiktok" value="<?php echo htmlspecialchars($tiktok); ?>" class="form-control">
                </div>

                <div class="col-md-12 mb-3">
                    <label for="contact_form_recipient" class="form-label">Email Penerima Pesan Kontak</label>
                    <input type="email" name="contact_form_recipient" id="contact_form_recipient" value="<?php echo htmlspecialchars($contact_form_recipient); ?>" class="form-control">
                    <div class="form-text">Email yang akan menerima pesan dari formulir kontak</div>
                </div>
            </div>

            <button type="submit" name="update_settings" class="btn btn-primary">Simpan Pengaturan</button>
        </form>
    </div>
</div>

<?php
// Capture the content
$content = ob_get_clean();

// Include the admin layout
include 'includes/admin_layout.php';
?>