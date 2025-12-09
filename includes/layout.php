<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title . ' - Excmorgan' : 'Excmorgan - E-commerce Fashion'; ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('https://via.placeholder.com/1200x400/4a5568/ffffff?text=Excmorgan') no-repeat center center;
            background-size: cover;
            height: 400px;
            display: flex;
            align-items: center;
        }
        .product-card {
            transition: transform 0.2s;
        }
        .product-card:hover {
            transform: translateY(-5px);
        }
        .cart-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            font-size: 0.7rem;
        }
        footer {
            background-color: #343a40;
            color: white;
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">Excmorgan</a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="products.php">Produk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="history.php">Sejarah</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Kontak</a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <form class="d-flex" method="GET" action="products.php">
                            <input class="form-control me-2" type="search" name="search" placeholder="Cari produk..." 
                                   value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                            <button class="btn btn-outline-success" type="submit">Cari</button>
                        </form>
                    </li>
                    
                    <li class="nav-item me-3">
                        <a href="cart.php" class="nav-link position-relative">
                            <i class="fas fa-shopping-cart fa-lg"></i>
                            <?php 
                            if (isset($_SESSION['user_id'])) {
                                $cart_items = getCartItems($_SESSION['user_id']);
                                $cart_count = count($cart_items);
                                if ($cart_count > 0) {
                                    echo "<span class='badge bg-danger cart-badge'>{$cart_count}</span>";
                                }
                            }
                            ?>
                        </a>
                    </li>
                    
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="account.php">Profil Saya</a></li>
                                <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="admin/admin.php">Admin Panel</a></li>
                                <?php endif; ?>
                                <li><a class="dropdown-item" href="logout.php">Keluar</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a href="login.php" class="btn btn-outline-primary">Masuk</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container mt-4">
        <?php if (isset($message)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <!-- Halaman konten akan dimasukkan di sini -->
        <?php echo $content; ?>
    </main>

    <!-- Footer -->
    <footer class="py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-3 mb-4">
                    <h5>Excmorgan</h5>
                    <p>E-commerce fashion terpercaya dengan koleksi terbaru dan kualitas terbaik.</p>
                </div>
                
                <div class="col-md-3 mb-4">
                    <h5>Tautan</h5>
                    <ul class="list-unstyled">
                        <li><a href="index.php" class="text-white">Beranda</a></li>
                        <li><a href="products.php" class="text-white">Produk</a></li>
                        <li><a href="history.php" class="text-white">Sejarah</a></li>
                        <li><a href="contact.php" class="text-white">Kontak</a></li>
                    </ul>
                </div>
                
                <div class="col-md-3 mb-4">
                    <h5>Kontak</h5>
                    <?php 
                    $site_settings = getSiteSettings();
                    if (isset($site_settings['contact_email'])): ?>
                        <p><i class="fas fa-envelope me-2"></i> <?php echo $site_settings['contact_email']; ?></p>
                    <?php endif; ?>
                    <?php if (isset($site_settings['contact_phone'])): ?>
                        <p><i class="fas fa-phone me-2"></i> <?php echo $site_settings['contact_phone']; ?></p>
                    <?php endif; ?>
                    <?php if (isset($site_settings['address'])): ?>
                        <p><i class="fas fa-map-marker-alt me-2"></i> <?php echo $site_settings['address']; ?></p>
                    <?php endif; ?>
                </div>
                
                <div class="col-md-3 mb-4">
                    <h5>Media Sosial</h5>
                    <div class="d-flex">
                        <?php if (isset($site_settings['instagram'])): ?>
                            <a href="<?php echo $site_settings['instagram']; ?>" target="_blank" class="text-white me-3">
                                <i class="fab fa-instagram fa-2x"></i>
                            </a>
                        <?php endif; ?>
                        <?php if (isset($site_settings['tiktok'])): ?>
                            <a href="<?php echo $site_settings['tiktok']; ?>" target="_blank" class="text-white">
                                <i class="fab fa-tiktok fa-2x"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <hr class="my-4">
            
            <div class="text-center">
                <p>&copy; <?php echo date('Y'); ?> Excmorgan. Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script>
        // Mobile menu toggle handled by Bootstrap
        document.addEventListener('DOMContentLoaded', function() {
            // Any custom JavaScript can go here
        });
    </script>
</body>
</html>