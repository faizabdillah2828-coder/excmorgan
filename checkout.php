<?php
session_start();
require_once 'config/functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?redirect=checkout.php');
    exit;
}

// Get cart items
$cart_items = getCartItems($_SESSION['user_id']);

// If cart is empty, redirect to cart page
if (empty($cart_items)) {
    header('Location: cart.php');
    exit;
}

// Calculate total amount
$total_amount = 0;
foreach ($cart_items as $item) {
    $total_amount += $item['price'] * $item['quantity'];
}
$shipping_cost = 25000;
$final_total = $total_amount + $shipping_cost;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $shipping_address = trim($_POST['shipping_address']);
    $payment_method = trim($_POST['payment_method']);
    
    if (!empty($shipping_address) && !empty($payment_method)) {
        $order_id = createOrder($_SESSION['user_id'], $shipping_address, $payment_method);
        
        if ($order_id) {
            $message = "Pesanan berhasil dibuat! Nomor pesanan: #{$order_id}";
            // Clear cart items after successful order
            $cart_items = []; // Update cart items to show empty cart
        } else {
            $error = "Gagal membuat pesanan. Silakan coba lagi.";
        }
    } else {
        $error = "Silakan lengkapi alamat pengiriman dan pilih metode pembayaran.";
    }
}

// Set title for the page
$title = 'Checkout';

// Start output buffering to capture content
ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Checkout</h1>
</div>

<?php if (empty($cart_items)): ?>
    <div class="card text-center p-5">
        <i class="fas fa-check-circle fa-5x text-success mb-4"></i>
        <h3>Pesanan Berhasil!</h3>
        <p class="text-muted"><?php echo $message; ?></p>
        <a href="index.php" class="btn btn-primary">Kembali ke Beranda</a>
    </div>
<?php else: ?>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h3>Alamat Pengiriman</h3>
                    
                    <form method="POST">
                        <div class="mb-3">
                            <label for="shipping_address" class="form-label">Alamat Lengkap</label>
                            <textarea name="shipping_address" id="shipping_address" rows="4" class="form-control" placeholder="Contoh: Jl. Contoh Alamat No. 123, Jakarta Selatan"><?php echo isset($_POST['shipping_address']) ? htmlspecialchars($_POST['shipping_address']) : ''; ?></textarea>
                        </div>
                        
                        <h3 class="mt-4">Metode Pembayaran</h3>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <input type="radio" name="payment_method" value="Transfer Bank" class="form-check-input" required>
                                        <i class="fas fa-university fa-2x text-primary mb-2"></i>
                                        <h6>Transfer Bank</h6>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <input type="radio" name="payment_method" value="COD" class="form-check-input" required>
                                        <i class="fas fa-cash-register fa-2x text-success mb-2"></i>
                                        <h6>Bayar di Tempat (COD)</h6>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <input type="radio" name="payment_method" value="E-Wallet" class="form-check-input" required>
                                        <i class="fas fa-wallet fa-2x text-info mb-2"></i>
                                        <h6>E-Wallet</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            Bayar Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Ringkasan Pesanan</h5>
                </div>
                <div class="card-body">
                    <?php foreach ($cart_items as $item): ?>
                        <div class="d-flex justify-content-between mb-2">
                            <div>
                                <h6><?php echo htmlspecialchars($item['name']); ?></h6>
                                <small class="text-muted"><?php echo $item['quantity']; ?> x Rp <?php echo number_format($item['price'], 0, ',', '.'); ?></small>
                            </div>
                            <span>Rp <?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?></span>
                        </div>
                    <?php endforeach; ?>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span>Rp <?php echo number_format($total_amount, 0, ',', '.'); ?></span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Biaya Pengiriman</span>
                        <span>Rp <?php echo number_format($shipping_cost, 0, ',', '.'); ?></span>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between fw-bold fs-5">
                        <span>Total</span>
                        <span>Rp <?php echo number_format($final_total, 0, ',', '.'); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php
// Capture the content
$content = ob_get_clean();

// Include the layout
include 'includes/layout.php';
?>