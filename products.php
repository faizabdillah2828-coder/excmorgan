<?php
session_start();
require_once 'config/functions.php';

// Set title for the page
$title = 'Produk';

// Handle search
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
$products = [];

if (!empty($search_query)) {
    $products = searchProducts($search_query);
} else {
    $products = getAllProducts();
}

// Start output buffering to capture content
ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Semua Produk</h1>
    <?php if (!empty($search_query)): ?>
        <p class="text-muted">Hasil pencarian untuk: "<?php echo htmlspecialchars($search_query); ?>"</p>
    <?php endif; ?>
</div>

<!-- Product Grid -->
<div class="row">
    <?php if (count($products) > 0): ?>
        <?php foreach ($products as $product): ?>
            <div class="col-md-3 col-6 mb-4">
                <div class="card product-card h-100">
                    <div class="card-img-wrapper">
                        <img src="<?php echo htmlspecialchars(getImageUrl($product['image_url'])); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>" style="height: 200px; object-fit: cover;">
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                        <p class="card-text">Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></p>
                        <div class="mt-auto">
                            <a href="product_detail.php?id=<?php echo $product['id']; ?>" class="btn btn-outline-primary w-100">Lihat Detail</a>
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <a href="cart.php?action=add&product_id=<?php echo $product['id']; ?>&quantity=1" class="btn btn-primary w-100 mt-2">
                                    <i class="fas fa-shopping-cart"></i> Tambah
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12 text-center py-5">
            <i class="fas fa-search fa-3x text-muted mb-3"></i>
            <h3>Produk tidak ditemukan</h3>
            <p class="text-muted">Coba kata kunci lain atau lihat produk-produk kami yang lain.</p>
            <a href="products.php" class="btn btn-primary">Lihat Semua Produk</a>
        </div>
    <?php endif; ?>
</div>

<?php
// Capture the content
$content = ob_get_clean();

// Include the layout
include 'includes/layout.php';
?>