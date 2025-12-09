<?php
session_start();
require_once 'config/functions.php';

// Set title for the page
$title = 'Sejarah';

// Start output buffering to capture content
ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Sejarah Excmorgan</h1>
</div>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <p>Excmorgan didirikan pada tahun 2020 dengan visi untuk menyediakan fashion berkualitas tinggi dengan harga terjangkau. Berawal dari sebuah toko kecil di Jakarta, kami telah berkembang menjadi salah satu e-commerce fashion terpercaya di Indonesia.</p>
                
                <p class="mt-3">Kami percaya bahwa fashion bukan hanya tentang penampilan, tetapi juga tentang ekspresi diri dan kepercayaan diri. Oleh karena itu, kami selalu berusaha menyediakan koleksi yang lengkap dan terkini untuk memenuhi kebutuhan fashion setiap pelanggan.</p>
                
                <p class="mt-3">Sejak awal berdiri, Excmorgan berkomitmen untuk menggunakan bahan berkualitas dan proses produksi yang ramah lingkungan. Kami bekerja sama dengan desainer lokal berbakat untuk menciptakan koleksi yang unik dan trendi.</p>
                
                <p class="mt-3">Kini, Excmorgan telah melayani ribuan pelanggan di seluruh Indonesia dengan berbagai pilihan fashion untuk pria dan wanita. Kami terus berinovasi untuk memberikan pengalaman berbelanja online yang menyenangkan dan memuaskan.</p>
            </div>
            
            <div class="col-md-4">
                <img src="https://via.placeholder.com/400x300/4a5568/ffffff?text=Excmorgan+Logo" class="img-fluid rounded" alt="Excmorgan">
            </div>
        </div>
        
        <hr class="my-4">
        
        <h4>Nilai-nilai Kami</h4>
        
        <div class="row mt-4">
            <div class="col-md-4 text-center mb-4">
                <i class="fas fa-star text-warning fa-3x mb-3"></i>
                <h5>Kualitas</h5>
                <p class="text-muted">Kami hanya menyediakan produk dengan kualitas terbaik</p>
            </div>
            
            <div class="col-md-4 text-center mb-4">
                <i class="fas fa-tags text-primary fa-3x mb-3"></i>
                <h5>Tren Terkini</h5>
                <p class="text-muted">Koleksi fashion terbaru yang selalu update dengan tren</p>
            </div>
            
            <div class="col-md-4 text-center mb-4">
                <i class="fas fa-gem text-success fa-3x mb-3"></i>
                <h5>Terjangkau</h5>
                <p class="text-muted">Harga terbaik untuk fashion berkualitas</p>
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