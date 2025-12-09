<?php
session_start();
require_once '../config/functions.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: ../login.php');
    exit;
}

// Handle deletion of contact messages
if (isset($_GET['delete_message'])) {
    $id = (int)$_GET['delete_message'];
    $stmt = $pdo->prepare("DELETE FROM contact_messages WHERE id = :id");
    $stmt->bindParam(':id', $id);
    
    if ($stmt->execute()) {
        $message = "Pesan berhasil dihapus!";
    } else {
        $error = "Gagal menghapus pesan.";
    }
}

// Get all contact messages
$stmt = $pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Set title for the page
$title = 'Pesan Kontak';

// Start output buffering to capture content
ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Pesan Kontak</h1>
</div>

<?php if (isset($message)): ?>
    <div class="alert alert-success"><?php echo $message; ?></div>
<?php endif; ?>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<?php if (count($messages) > 0): ?>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Pesan</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($messages as $msg): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($msg['name']); ?></td>
                                <td><?php echo htmlspecialchars($msg['email']); ?></td>
                                <td class="text-truncate" style="max-width: 300px;" title="<?php echo htmlspecialchars($msg['message']); ?>">
                                    <?php echo htmlspecialchars($msg['message']); ?>
                                </td>
                                <td><?php echo date('d M Y H:i', strtotime($msg['created_at'])); ?></td>
                                <td>
                                    <a href="?delete_message=<?php echo $msg['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin ingin menghapus pesan ini?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="card text-center p-5">
        <i class="fas fa-envelope-open-text fa-3x text-muted mb-4"></i>
        <h4>Belum Ada Pesan</h4>
        <p class="text-muted">Tidak ada pesan kontak yang diterima.</p>
    </div>
<?php endif; ?>

<?php
// Capture the content
$content = ob_get_clean();

// Include the admin layout
include 'includes/admin_layout.php';
?>