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

// Update order status
if (isset($_POST['update_status'])) {
    $order_id = (int)$_POST['order_id'];
    $status = trim($_POST['status']);

    if (!empty($status)) {
        $stmt = $pdo->prepare("UPDATE orders SET status = :status WHERE id = :id");
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $order_id);

        if ($stmt->execute()) {
            $message = "Status pesanan berhasil diperbarui!";
        } else {
            $error = "Gagal memperbarui status pesanan.";
        }
    } else {
        $error = "Status harus dipilih.";
    }
}

// Get orders
$stmt = $pdo->query("SELECT o.*, u.name as user_name FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC");
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Set title for the page
$title = 'Manajemen Order';

// Start output buffering to capture content
ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Manajemen Order</h1>
</div>

<?php if ($message): ?>
    <div class="alert alert-success"><?php echo $message; ?></div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID Order</th>
                        <th>Pelanggan</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>#<?php echo $order['id']; ?></td>
                            <td><?php echo htmlspecialchars($order['user_name']); ?></td>
                            <td>Rp <?php echo number_format($order['total_amount'], 0, ',', '.'); ?></td>
                            <td>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                    <select name="status" class="form-select form-select-sm d-inline w-auto">
                                        <option value="Diproses" <?php echo $order['status'] == 'Diproses' ? 'selected' : ''; ?>>Diproses</option>
                                        <option value="Dikirim" <?php echo $order['status'] == 'Dikirim' ? 'selected' : ''; ?>>Dikirim</option>
                                        <option value="Selesai" <?php echo $order['status'] == 'Selesai' ? 'selected' : ''; ?>>Selesai</option>
                                        <option value="Dibatalkan" <?php echo $order['status'] == 'Dibatalkan' ? 'selected' : ''; ?>>Dibatalkan</option>
                                    </select>
                                    <button type="submit" name="update_status" class="btn btn-sm btn-outline-primary ms-1">Ubah</button>
                                </form>
                            </td>
                            <td><?php echo date('d M Y H:i', strtotime($order['created_at'])); ?></td>
                            <td>
                                <a href="admin_order_detail.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-outline-primary">Lihat Detail</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
// Capture the content
$content = ob_get_clean();

// Include the admin layout
include 'includes/admin_layout.php';
?>