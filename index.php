<?php
session_start();
require_once 'config/functions.php';

// Set title for the page
$title = 'Beranda';

// Get latest and best seller products
$latest_products = getProducts(4);
$best_seller_products = getBestSellerProducts(4);

// Get site settings
$site_settings = getSiteSettings();

// Start output buffering to capture content
ob_start();
?>

<!-- Hero Section -->
<div class="hero-section text-white text-center rounded">
    <div class="container">
        <h1 class="display-4 fw-bold">Temukan Gaya Terbaikmu</h1>
        <p class="lead">Koleksi fashion terbaru dan terlengkap untuk pria dan wanita dengan kualitas terbaik dan harga terjangkau.</p>
        <a href="products.php" class="btn btn-primary btn-lg mt-3">Belanja Sekarang</a>
    </div>
</div>

<!-- Latest Products -->
<div class="my-5">
    <h2 class="text-center mb-4">Produk Terbaru</h2>
    <div class="row">
        <?php foreach ($latest_products as $product): ?>
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
    </div>
    <div class="text-center mt-4">
        <a href="products.php" class="btn btn-primary">Lihat Semua Produk</a>
    </div>
</div>

<!-- Best Seller Products -->
<div class="my-5">
    <h2 class="text-center mb-4">Best Seller</h2>
    <div class="row">
        <?php foreach ($best_seller_products as $product): ?>
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
    </div>
</div>

<!-- Contact Section -->
<div class="card my-5">
    <div class="card-body text-center">
        <h5 class="card-title">Hubungi Kami</h5>
        <p class="card-text">Punya pertanyaan atau ingin tahu lebih lanjut tentang produk kami? Silakan hubungi kami melalui halaman kontak.</p>
        <a href="contact.php" class="btn btn-primary">Hubungi Kami</a>
    </div>
</div>

<?php
// Capture the content
$content = ob_get_clean();

// Include the layout
include 'includes/layout.php';
?>