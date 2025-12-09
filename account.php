<?php
session_start();
require_once 'config/functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?redirect=account.php');
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

// Get user orders
$orders = getUserOrders($user_id);

// Set title for the page
$title = 'Akun Saya';

// Start output buffering to capture content
ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Profil Akun</h1>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-user fa-5x text-muted"></i>
                </div>
                <h4><?php echo htmlspecialchars($user['name']); ?></h4>
                <p class="text-muted"><?php echo htmlspecialchars($user['email']); ?></p>
                
                <div class="d-grid gap-2">
                    <a href="edit_profile.php" class="btn btn-primary">Edit Profil</a>
                    <a href="logout.php" class="btn btn-outline-secondary">Keluar</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Riwayat Pesanan</h5>
            </div>
            <div class="card-body">
                <?php if (count($orders) > 0): ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID Pesanan</th>
                                    <th>Tanggal</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td>#<?php echo $order['id']; ?></td>
                                        <td><?php echo date('d M Y', strtotime($order['created_at'])); ?></td>
                                        <td>Rp <?php echo number_format($order['total_amount'], 0, ',', '.'); ?></td>
                                        <td>
                                            <span class="badge 
                                                <?php 
                                                switch($order['status']) {
                                                    case 'Diproses': echo 'bg-warning'; break;
                                                    case 'Dikirim': echo 'bg-info'; break;
                                                    case 'Selesai': echo 'bg-success'; break;
                                                    case 'Dibatalkan': echo 'bg-danger'; break;
                                                    default: echo 'bg-secondary';
                                                }
                                                ?>">
                                                <?php echo $order['status']; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="order_detail.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-outline-primary">Lihat Detail</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p>Anda belum memiliki riwayat pesanan.</p>
                <?php endif; ?>
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