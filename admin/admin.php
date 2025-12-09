<?php
session_start();
require_once '../config/functions.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: ../login.php');
    exit;
}

// Get some statistics
$products_count_stmt = $pdo->query("SELECT COUNT(*) FROM products");
$products_count = $products_count_stmt->fetchColumn();

$orders_count_stmt = $pdo->query("SELECT COUNT(*) FROM orders");
$orders_count = $orders_count_stmt->fetchColumn();

$users_count_stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE is_admin = 0");
$users_count = $users_count_stmt->fetchColumn();

$revenue_stmt = $pdo->query("SELECT COALESCE(SUM(total_amount), 0) FROM orders WHERE status != 'Dibatalkan'");
$revenue = $revenue_stmt->fetchColumn();

// Set title for the page
$title = 'Dashboard Admin';

// Start output buffering to capture content
ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Dashboard Admin</h1>
</div>

<p class="text-muted">Selamat datang di panel administrasi Excmorgan</p>

<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Produk</h6>
                        <h3><?php echo $products_count; ?></h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-boxes fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Order</h6>
                        <h3><?php echo $orders_count; ?></h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-shopping-cart fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Pelanggan</h6>
                        <h3><?php echo $users_count; ?></h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card bg-warning text-dark">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Pendapatan</h6>
                        <h3>Rp <?php echo number_format($revenue, 0, ',', '.'); ?></h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-money-bill-wave fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Menu Administrasi</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <a href="admin_products.php" class="card h-100 text-decoration-none text-dark">
                            <div class="card-body text-center">
                                <i class="fas fa-box-open fa-3x text-primary mb-3"></i>
                                <h6>Manajemen Produk</h6>
                                <p class="text-muted small">Tambah, edit, atau hapus produk</p>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-4 mb-3">
                        <a href="admin_orders.php" class="card h-100 text-decoration-none text-dark">
                            <div class="card-body text-center">
                                <i class="fas fa-receipt fa-3x text-success mb-3"></i>
                                <h6>Manajemen Order</h6>
                                <p class="text-muted small">Lihat dan kelola pesanan</p>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-4 mb-3">
                        <a href="admin_contact.php" class="card h-100 text-decoration-none text-dark">
                            <div class="card-body text-center">
                                <i class="fas fa-address-book fa-3x text-info mb-3"></i>
                                <h6>Kontak & Sosial</h6>
                                <p class="text-muted small">Kelola informasi kontak</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="admin_products.php" class="btn btn-primary">Tambah Produk Baru</a>
                    <a href="admin_orders.php" class="btn btn-success">Lihat Order Baru</a>
                    <a href="../logout.php" class="btn btn-secondary">Keluar</a>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Info Sistem</h5>
            </div>
            <div class="card-body">
                <p><strong>Versi PHP:</strong> <?php echo phpversion(); ?></p>
                <p><strong>Database:</strong> MySQL</p>
                <p><strong>Tanggal:</strong> <?php echo date('d M Y H:i:s'); ?></p>
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