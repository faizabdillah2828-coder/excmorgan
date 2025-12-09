<?php
session_start();
require_once 'config/functions.php';

// Get product ID from URL
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($product_id <= 0) {
    header('Location: products.php');
    exit;
}

// Get product details
$product = getProductById($product_id);

if (!$product) {
    header('Location: products.php');
    exit;
}

// Set title for the page
$title = $product['name'];

// Start output buffering to capture content
ob_start();
?>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body text-center">
                <img src="<?php echo htmlspecialchars(getImageUrl($product['image_url'])); ?>" class="img-fluid rounded" alt="<?php echo htmlspecialchars($product['name']); ?>">
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <h1><?php echo htmlspecialchars($product['name']); ?></h1>
        <div class="mb-3">
            <h3 class="text-primary">Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></h3>
        </div>
        
        <div class="mb-4">
            <h5>Deskripsi</h5>
            <p><?php echo htmlspecialchars($product['description']); ?></p>
        </div>
        
        <div class="mb-4">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="cart.php?action=add&product_id=<?php echo $product['id']; ?>&quantity=1" class="btn btn-primary btn-lg w-100">
                    <i class="fas fa-shopping-cart"></i> Tambah ke Keranjang
                </a>
                
                <a href="cart.php" class="btn btn-outline-primary btn-lg w-100 mt-2">
                    Lihat Keranjang
                </a>
            <?php else: ?>
                <p class="text-muted mb-3">Silakan <a href="login.php">masuk</a> untuk menambahkan produk ke keranjang.</p>
                <a href="login.php" class="btn btn-primary btn-lg w-100">
                    Masuk untuk Belanja
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Related Products -->
<div class="mt-5">
    <h3>Produk Lainnya</h3>
    <div class="row">
        <?php 
        $related_products = getProducts(4);
        foreach ($related_products as $related_product):
            if ($related_product['id'] == $product['id']) continue;
        ?>
            <div class="col-md-3 col-6 mb-4">
                <div class="card h-100">
                    <div class="card-img-wrapper">
                        <img src="<?php echo htmlspecialchars(getImageUrl($related_product['image_url'])); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($related_product['name']); ?>" style="height: 150px; object-fit: cover;">
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title"><?php echo htmlspecialchars($related_product['name']); ?></h6>
                        <p class="card-text">Rp <?php echo number_format($related_product['price'], 0, ',', '.'); ?></p>
                        <a href="product_detail.php?id=<?php echo $related_product['id']; ?>" class="btn btn-outline-primary mt-auto">Lihat Detail</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php
// Capture the content
$content = ob_get_clean();

// Include the layout
include 'includes/layout.php';
?>