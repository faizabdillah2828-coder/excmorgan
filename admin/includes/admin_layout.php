<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title . ' - Admin Excmorgan' : 'Admin Excmorgan'; ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            min-height: 100vh;
            background-color: #343a40;
            color: white;
            padding-top: 1rem;
        }
        .sidebar a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
        }
        .sidebar a:hover {
            color: white;
        }
        .sidebar .nav-link {
            padding: 0.75rem 1.5rem;
            margin-bottom: 0.25rem;
        }
        .sidebar .nav-link.active {
            background-color: #495057;
            color: white;
        }
        .sidebar .nav-link i {
            width: 20px;
            margin-right: 10px;
        }
        .main-content {
            padding: 2rem 0;
        }
        .admin-header {
            background-color: white;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            padding: 1rem 0;
        }
        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <h4 class="logo">Excmorgan Admin</h4>
                    </div>
                    
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'admin.php') ? 'active' : ''; ?>" href="admin.php">
                                <i class="fas fa-tachometer-alt"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'admin_products.php') ? 'active' : ''; ?>" href="admin_products.php">
                                <i class="fas fa-boxes"></i>
                                Manajemen Produk
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'admin_orders.php') ? 'active' : ''; ?>" href="admin_orders.php">
                                <i class="fas fa-receipt"></i>
                                Manajemen Order
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'admin_contact.php') ? 'active' : ''; ?>" href="admin_contact.php">
                                <i class="fas fa-address-book"></i>
                                Pengaturan Kontak
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'admin_contact_messages.php') ? 'active' : ''; ?>" href="admin_contact_messages.php">
                                <i class="fas fa-envelope"></i>
                                Pesan Kontak
                            </a>
                        </li>
                        <li class="nav-item mt-4">
                            <a class="nav-link" href="../logout.php">
                                <i class="fas fa-sign-out-alt"></i>
                                Keluar
                            </a>
                        </li>
                    </ul>
                    
                    <div class="text-center mt-5 text-muted">
                        <small>Excmorgan Admin Panel v1.0</small>
                    </div>
                </div>
            </nav>
            
            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="admin-header">
                    <div class="container-fluid">
                        <div class="d-flex justify-content-between align-items-center">
                            <h1 class="h3 mb-0"><?php echo isset($title) ? $title : 'Dashboard'; ?></h1>
                            <div class="d-flex align-items-center">
                                <span class="me-3">Admin: <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Tidak Diketahui'); ?></span>
                                <a href="../" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-external-link-alt me-1"></i>Lihat Situs
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="main-content">
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
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Any custom JavaScript for admin panel can go here
        });
    </script>
</body>
</html>