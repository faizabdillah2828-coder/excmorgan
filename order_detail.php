<?php
session_start();
require_once 'config/functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?redirect=order_detail.php');
    exit;
}

// Get order ID from URL
$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($order_id <= 0) {
    header('Location: account.php');
    exit;
}

// Get order details
$order_details = getOrderDetails($order_id);

if (empty($order_details)) {
    header('Location: account.php');
    exit;
}

// Get the first row to get order info (all rows have the same order info)
$order_info = $order_details[0];

// Check if this order belongs to the current user
if ($order_info['user_id'] != $_SESSION['user_id']) {
    header('Location: account.php');
    exit;
}

// Set title for the page
$title = 'Detail Pesanan #' . $order_id;

// Start output buffering to capture content
ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Detail Pesanan #<?php echo $order_id; ?></h1>
</div>

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
                <div class="mb-3">
                    <strong>ID Pesanan:</strong> #<?php echo $order_info['id']; ?>
                </div>
                
                <div class="mb-3">
                    <strong>Tanggal:</strong> <?php echo date('d M Y H:i', strtotime($order_info['created_at'])); ?>
                </div>
                
                <div class="mb-3">
                    <strong>Status:</strong> 
                    <span class="badge 
                        <?php 
                        switch($order_info['status']) {
                            case 'Diproses': echo 'bg-warning'; break;
                            case 'Dikirim': echo 'bg-info'; break;
                            case 'Selesai': echo 'bg-success'; break;
                            case 'Dibatalkan': echo 'bg-danger'; break;
                            default: echo 'bg-secondary';
                        }
                        ?>">
                        <?php echo $order_info['status']; ?>
                    </span>
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

// Include the layout
include 'includes/layout.php';
?>