<?php
session_start();
require_once 'config/functions.php';

// Set title for the page
$title = 'Kontak';

// Get site settings
$site_settings = getSiteSettings();

// Start output buffering to capture content
ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Kontak Kami</h1>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Formulir Kontak</h5>
                
                <form id="contactForm">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="name">
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email">
                    </div>

                    <div class="mb-3">
                        <label for="message" class="form-label">Pesan</label>
                        <textarea class="form-control" id="message" rows="5"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Kirim Pesan</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Informasi Kontak</h5>
                
                <div class="list-group list-group-flush">
                    <?php if (isset($site_settings['contact_email'])): ?>
                        <div class="list-group-item">
                            <i class="fas fa-envelope text-primary me-2"></i>
                            <strong>Email:</strong>
                            <a href="mailto:<?php echo $site_settings['contact_email']; ?>" class="text-decoration-none ms-2">
                                <?php echo $site_settings['contact_email']; ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($site_settings['contact_phone'])): ?>
                        <div class="list-group-item">
                            <i class="fas fa-phone text-success me-2"></i>
                            <strong>Telepon:</strong>
                            <a href="tel:<?php echo $site_settings['contact_phone']; ?>" class="text-decoration-none ms-2">
                                <?php echo $site_settings['contact_phone']; ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($site_settings['address'])): ?>
                        <div class="list-group-item">
                            <i class="fas fa-map-marker-alt text-danger me-2"></i>
                            <strong>Alamat:</strong>
                            <span class="ms-2"><?php echo $site_settings['address']; ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body text-center">
                <h5 class="card-title">Media Sosial</h5>
                
                <div class="d-flex justify-content-center gap-3">
                    <?php if (isset($site_settings['instagram'])): ?>
                        <a href="<?php echo $site_settings['instagram']; ?>" target="_blank" class="btn btn-outline-danger">
                            <i class="fab fa-instagram"></i> Instagram
                        </a>
                    <?php endif; ?>
                    
                    <?php if (isset($site_settings['tiktok'])): ?>
                        <a href="<?php echo $site_settings['tiktok']; ?>" target="_blank" class="btn btn-outline-dark">
                            <i class="fab fa-tiktok"></i> TikTok
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();

    // Get form values
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const message = document.getElementById('message').value;

    // Basic validation
    if (!name || !email || !message) {
        alert('Silakan lengkapi semua field.');
        return;
    }

    // Show loading state
    const submitBtn = document.querySelector('#contactForm button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mengirim...';
    submitBtn.disabled = true;

    // Send form data using fetch
    fetch('process_contact.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'name=' + encodeURIComponent(name) +
              '&email=' + encodeURIComponent(email) +
              '&message=' + encodeURIComponent(message)
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert(data.message);
            document.getElementById('contactForm').reset();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        alert('Terjadi kesalahan saat mengirim pesan. Silakan coba lagi.');
        console.error('Error:', error);
    })
    .finally(() => {
        // Reset button state
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});
</script>

<?php
// Capture the content
$content = ob_get_clean();

// Include the layout
include 'includes/layout.php';
?>