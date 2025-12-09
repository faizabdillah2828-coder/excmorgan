<?php
session_start();
require_once 'config/functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?redirect=cart.php');
    exit;
}

// Handle cart actions
if (isset($_GET['action']) && $_GET['action'] === 'add' && isset($_GET['product_id'])) {
    $product_id = (int)$_GET['product_id'];
    $quantity = isset($_GET['quantity']) ? (int)$_GET['quantity'] : 1;
    
    if ($product_id > 0) {
        addProductToCart($_SESSION['user_id'], $product_id, $quantity);
        $message = "Produk berhasil ditambahkan ke keranjang!";
    }
}

if (isset($_POST['update_quantity']) && isset($_POST['cart_item_id']) && isset($_POST['quantity'])) {
    $cart_item_id = (int)$_POST['cart_item_id'];
    $quantity = (int)$_POST['quantity'];
    
    if ($quantity > 0) {
        updateCartItemQuantity($cart_item_id, $quantity);
        $message = "Jumlah produk berhasil diperbarui!";
    } else {
        removeCartItem($cart_item_id);
        $message = "Produk berhasil dihapus dari keranjang!";
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'remove' && isset($_GET['cart_item_id'])) {
    $cart_item_id = (int)$_GET['cart_item_id'];
    removeCartItem($cart_item_id);
    $message = "Produk berhasil dihapus dari keranjang!";
}

// Get cart items
$cart_items = getCartItems($_SESSION['user_id']);
$total_amount = 0;

foreach ($cart_items as $item) {
    $total_amount += $item['price'] * $item['quantity'];
}

// Set title for the page
$title = 'Keranjang';

// Start output buffering to capture content
ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Keranjang Belanja</h1>
</div>

<?php if (count($cart_items) > 0): ?>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <?php foreach ($cart_items as $item): ?>
                        <div class="row align-items-center mb-4 border-bottom pb-4">
                            <div class="col-md-2">
                                <img src="<?php echo htmlspecialchars(getImageUrl($item['image_url'])); ?>" class="img-fluid rounded" alt="<?php echo htmlspecialchars($item['name']); ?>">
                            </div>
                            
                            <div class="col-md-4">
                                <h5><?php echo htmlspecialchars($item['name']); ?></h5>
                                <p class="text-muted">Rp <?php echo number_format($item['price'], 0, ',', '.'); ?></p>
                            </div>
                            
                            <div class="col-md-3">
                                <form method="POST" class="d-flex align-items-center">
                                    <input type="hidden" name="cart_item_id" value="<?php echo $item['cart_item_id']; ?>">
                                    <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" class="form-control me-2">
                                    <button type="submit" name="update_quantity" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-sync"></i>
                                    </button>
                                </form>
                            </div>
                            
                            <div class="col-md-2 text-end">
                                <p class="fw-bold">Rp <?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?></p>
                            </div>
                            
                            <div class="col-md-1 text-center">
                                <a href="cart.php?action=remove&cart_item_id=<?php echo $item['cart_item_id']; ?>" class="btn btn-outline-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Ringkasan Pesanan</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span>Rp <?php echo number_format($total_amount, 0, ',', '.'); ?></span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Estimasi Pengiriman</span>
                        <span>Rp 25.000</span>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Total</span>
                        <span>Rp <?php echo number_format($total_amount + 25000, 0, ',', '.'); ?></span>
                    </div>
                    
                    <a href="checkout.php" class="btn btn-primary w-100 mt-4">
                        Lanjutkan ke Checkout
                    </a>
                    
                    <a href="products.php" class="btn btn-outline-secondary w-100 mt-2">
                        Lanjutkan Belanja
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="card text-center p-5">
        <i class="fas fa-shopping-cart fa-5x text-muted mb-4"></i>
        <h3>Keranjang Kosong</h3>
        <p class="text-muted">Keranjang belanja Anda masih kosong. Ayo tambahkan produk-produk menarik!</p>
        <a href="products.php" class="btn btn-primary">Belanja Sekarang</a>
    </div>
<?php endif; ?>

<?php
// Capture the content
$content = ob_get_clean();

// Include the layout
include 'includes/layout.php';
?>