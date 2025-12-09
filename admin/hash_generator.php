<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generator Hash Password - Excmorgan</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: 1px solid rgba(0, 0, 0, 0.125);
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white text-center">
                        <h4><i class="fas fa-lock"></i> Generator Hash Password</h4>
                        <p class="mb-0">Untuk keperluan administrasi Excmorgan</p>
                    </div>
                    <div class="card-body">
                        <form id="hashForm">
                            <div class="mb-3">
                                <label for="password" class="form-label">Masukkan Password</label>
                                <input type="password" class="form-control" id="password" placeholder="Masukkan password yang ingin di-hash" required>
                                <div class="form-text">Password akan di-hash menggunakan password_hash() PHP dengan algoritma PASSWORD_DEFAULT</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="confirmPassword" class="form-label">Konfirmasi Password</label>
                                <input type="password" class="form-control" id="confirmPassword" placeholder="Konfirmasi password yang sama" required>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-calculator me-2"></i>Generate Hash
                                </button>
                            </div>
                        </form>
                        
                        <div id="result" class="mt-4 d-none">
                            <h5>Hasil Hash:</h5>
                            <div class="alert alert-info">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div id="hashResult" class="text-break"></div>
                                    <button class="btn btn-outline-secondary btn-sm" id="copyBtn">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>CATATAN PENTING:</strong> Simpan hash ini dengan aman dan jangan bagikan kepada siapapun.
                            </div>
                        </div>
                        
                        <div id="error" class="mt-4 d-none">
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <span id="errorMessage"></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card mt-4">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Panduan Penggunaan</h5>
                    </div>
                    <div class="card-body">
                        <ol>
                            <li>Masukkan password yang ingin Anda hash</li>
                            <li>Konfirmasi password harus sama dengan password pertama</li>
                            <li>Klik "Generate Hash" untuk membuat hash</li>
                            <li>Salin hasil hash untuk digunakan dalam database</li>
                        </ol>
                        <p class="mb-0 text-muted">
                            <i class="fas fa-shield-alt me-2 text-primary"></i>
                            Password di-hash menggunakan algoritma bcrypt yang aman secara default di PHP
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.getElementById('hashForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const errorDiv = document.getElementById('error');
            const resultDiv = document.getElementById('result');
            
            // Reset error and result
            errorDiv.classList.add('d-none');
            resultDiv.classList.add('d-none');
            
            // Validate passwords match
            if (password !== confirmPassword) {
                document.getElementById('errorMessage').textContent = 'Password dan konfirmasi password tidak cocok!';
                errorDiv.classList.remove('d-none');
                return;
            }
            
            // Validate password length
            if (password.length < 6) {
                document.getElementById('errorMessage').textContent = 'Password minimal 6 karakter!';
                errorDiv.classList.remove('d-none');
                return;
            }
            
            // Create a temporary form to submit to PHP
            const form = document.createElement('form');
            form.method = 'POST';
            form.style.display = 'none';
            
            const passwordInput = document.createElement('input');
            passwordInput.type = 'hidden';
            passwordInput.name = 'password';
            passwordInput.value = password;
            form.appendChild(passwordInput);
            
            document.body.appendChild(form);
            form.submit();
        });
        
        // Copy to clipboard functionality
        document.getElementById('copyBtn').addEventListener('click', function() {
            const hashText = document.getElementById('hashResult').textContent;
            navigator.clipboard.writeText(hashText).then(function() {
                // Show feedback
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-check"></i>';
                setTimeout(() => {
                    this.innerHTML = originalText;
                }, 2000);
            });
        });
        
        // If there's a hash in the URL (from server response), display it
        <?php
        if (isset($_POST['password'])) {
            $password = $_POST['password'];
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            echo "
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('hashResult').textContent = '$hashed_password';
                document.getElementById('result').classList.remove('d-none');
            });
            ";
        }
        ?>
    </script>
</body>
</html>