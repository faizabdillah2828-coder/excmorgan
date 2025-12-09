<?php
session_start();
require_once '../config/functions.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: ../login.php');
    exit;
}

// Get order ID from URL
$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($order_id <= 0) {
    header('Location: admin_orders.php');
    exit;
}

// Get order details
$order_details = getOrderDetails($order_id);

if (empty($order_details)) {
    header('Location: admin_orders.php');
    exit;
}

// Get the first row to get order info (all rows have the same order info)
$order_info = $order_details[0];

// Handle form submissions
$message = '';
$error = '';

// Update order status
if (isset($_POST['update_status'])) {
    $status = trim($_POST['status']);

    if (!empty($status)) {
        $stmt = $pdo->prepare("UPDATE orders SET status = :status WHERE id = :id");
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $order_id);

        if ($stmt->execute()) {
            $message = "Status pesanan berhasil diperbarui!";
            $order_info['status'] = $status; // Update current status
        } else {
            $error = "Gagal memperbarui status pesanan.";
        }
    } else {
        $error = "Status harus dipilih.";
    }
}

// Get user information
$stmt = $pdo->prepare("SELECT name FROM users WHERE id = :id");
$stmt->bindParam(':id', $order_info['user_id']);
$stmt->execute();
$user = $stmt->fetch();

// Set title for the page
$title = 'Detail Order #' . $order_id;

// Start output buffering to capture content
ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Detail Order #<?php echo $order_id; ?></h1>
</div>

<?php if ($message): ?>
    <div class="alert alert-success"><?php echo $message; ?></div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Item Pesanan</h5>
            </div>
            <div class="card-body">
                <?php foreach ($order_details as $item): ?>
                    <div class="row align-items-center mb-3 pb-3 border-bottom">
                        <div class="col-md-2">
                            <img src="<?php echo htmlspecialchars(getImageUrl($item['image_url'])); ?>" class="img-fluid rounded" alt="<?php echo htmlspecialchars($item['product_name']); ?>">
                        </div>

                        <div class="col-md-4">
                            <h6><?php echo htmlspecialchars($item['product_name']); ?></h6>
                        </div>

                        <div class="col-md-2">
                            <p>Rp <?php echo number_format($item['product_price'], 0, ',', '.'); ?></p>
                        </div>

                        <div class="col-md-2">
                            <p><?php echo $item['quantity']; ?> x</p>
                        </div>

                        <div class="col-md-2 text-end">
                            <p class="fw-bold">Rp <?php echo number_format($item['total_price'], 0, ',', '.'); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Informasi Pesanan</h5>
            </div>
            <div class="card-body">
                <form method="POST" class="mb-4">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="Diproses" <?php echo $order_info['status'] == 'Diproses' ? 'selected' : ''; ?>>Diproses</option>
                            <option value="Dikirim" <?php echo $order_info['status'] == 'Dikirim' ? 'selected' : ''; ?>>Dikirim</option>
                            <option value="Selesai" <?php echo $order_info['status'] == 'Selesai' ? 'selected' : ''; ?>>Selesai</option>
                            <option value="Dibatalkan" <?php echo $order_info['status'] == 'Dibatalkan' ? 'selected' : ''; ?>>Dibatalkan</option>
                        </select>
                    </div>
                    <button type="submit" name="update_status" class="btn btn-primary w-100">Perbarui Status</button>
                </form>

                <div class="mb-3">
                    <strong>ID Pesanan:</strong> #<?php echo $order_info['id']; ?>
                </div>

                <div class="mb-3">
                    <strong>Pelanggan:</strong> <?php echo htmlspecialchars($user['name'] ?? 'Tidak ditemukan'); ?>
                </div>

                <div class="mb-3">
                    <strong>Tanggal:</strong> <?php echo date('d M Y H:i', strtotime($order_info['created_at'])); ?>
                </div>

                <div class="mb-3">
                    <strong>Metode Pembayaran:</strong> <?php echo htmlspecialchars($order_info['payment_method']); ?>
                </div>

                <div class="mb-4">
                    <strong>Total:</strong> Rp <?php echo number_format($order_info['total_amount'], 0, ',', '.'); ?>
                </div>

                <div>
                    <strong>Alamat Pengiriman:</strong>
                    <p class="text-muted"><?php echo htmlspecialchars($order_info['shipping_address']); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Capture the content
$content = ob_get_clean();

// Include the admin layout
include 'includes/admin_layout.php';
?>