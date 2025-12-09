<?php
session_start();
require_once '../config/functions.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: ../login.php');
    exit;
}

// Handle form submissions
$message = '';
$error = '';

// Add new product
if (isset($_POST['add_product'])) {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = (float)$_POST['price'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    // Handle image upload
    $image_url = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $uploaded_image = uploadProductImage($_FILES['image']);
        if ($uploaded_image) {
            $image_url = $uploaded_image;
        } else {
            $error = "Gagal mengupload gambar. Pastikan file adalah JPG, PNG, atau GIF dengan ukuran maksimal 5MB.";
        }
    } else {
        $error = "Gambar produk wajib diupload.";
    }

    if (!empty($name) && $price > 0 && empty($error)) {
        $stmt = $pdo->prepare("INSERT INTO products (name, description, price, image_url, is_active) VALUES (:name, :description, :price, :image_url, :is_active)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':image_url', $image_url);
        $stmt->bindParam(':is_active', $is_active);

        if ($stmt->execute()) {
            $message = "Produk berhasil ditambahkan!";
        } else {
            $error = "Gagal menambahkan produk.";
        }
    } else {
        if (empty($name) || $price <= 0) {
            $error = "Nama dan harga wajib diisi.";
        }
    }
}

// Update product
if (isset($_POST['update_product'])) {
    $id = (int)$_POST['id'];
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = (float)$_POST['price'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    // Get current image URL from database
    $stmt = $pdo->prepare("SELECT image_url FROM products WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $current_product = $stmt->fetch();
    $image_url = $current_product['image_url'];

    // Handle image upload for update
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $uploaded_image = uploadProductImage($_FILES['image']);
        if ($uploaded_image) {
            // Delete old image if it exists and is not from placeholder
            if ($current_product['image_url'] && strpos($current_product['image_url'], 'placeholder') === false) {
                $old_image_path = __DIR__ . '/../' . $current_product['image_url'];
                if (file_exists($old_image_path)) {
                    unlink($old_image_path);
                }
            }
            $image_url = $uploaded_image;
        } else {
            $error = "Gagal mengupload gambar. Pastikan file adalah JPG, PNG, atau GIF dengan ukuran maksimal 5MB.";
        }
    }

    if (!empty($name) && $price > 0 && empty($error)) {
        $stmt = $pdo->prepare("UPDATE products SET name = :name, description = :description, price = :price, image_url = :image_url, is_active = :is_active WHERE id = :id");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':image_url', $image_url);
        $stmt->bindParam(':is_active', $is_active);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            $message = "Produk berhasil diperbarui!";
        } else {
            $error = "Gagal memperbarui produk.";
        }
    } else {
        if (empty($name) || $price <= 0) {
            $error = "Nama dan harga wajib diisi.";
        }
    }
}

// Delete product
if (isset($_GET['delete_product'])) {
    $id = (int)$_GET['delete_product'];

    // Get image path before deleting the product
    $getImageStmt = $pdo->prepare("SELECT image_url FROM products WHERE id = :id");
    $getImageStmt->bindParam(':id', $id);
    $getImageStmt->execute();
    $product = $getImageStmt->fetch();

    $stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        // Delete the image file if it exists
        if ($product && !empty($product['image_url'])) {
            deleteProductImage($product['image_url']);
        }
        $message = "Produk berhasil dihapus!";
    } else {
        $error = "Gagal menghapus produk.";
    }
}

// Get products
$stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Set title for the page
$title = 'Manajemen Produk';

// Start output buffering to capture content
ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Manajemen Produk</h1>
</div>

<?php if ($message): ?>
    <div class="alert alert-success"><?php echo $message; ?></div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Tambah Produk Baru</h5>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Produk</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea name="description" id="description" rows="3" class="form-control"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Harga</label>
                        <input type="number" step="0.01" name="price" id="price" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Upload Gambar Produk</label>
                        <input type="file" name="image" id="image" class="form-control" accept="image/*" required>
                        <div class="form-text">Upload gambar produk (JPG, PNG, GIF, maksimal 5MB)</div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" value="1" checked class="form-check-input" id="is_active">
                            <label class="form-check-label" for="is_active">Aktif</label>
                        </div>
                    </div>

                    <button type="submit" name="add_product" class="btn btn-primary">Tambah Produk</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>Daftar Produk</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Gambar</th>
                                <th>Nama</th>
                                <th>Harga</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td>
                                        <?php if ($product['image_url']): ?>
                                            <img src="../<?php echo htmlspecialchars(getImageUrl($product['image_url'])); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                                    <td>Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></td>
                                    <td>
                                        <span class="badge <?php echo $product['is_active'] ? 'bg-success' : 'bg-danger'; ?>">
                                            <?php echo $product['is_active'] ? 'Aktif' : 'Tidak Aktif'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $product['id']; ?>">Edit</button>
                                        <a href="?delete_product=<?php echo $product['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin ingin menghapus produk ini?')">Hapus</a>
                                    </td>
                                </tr>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editModal<?php echo $product['id']; ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Produk</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form method="POST" enctype="multipart/form-data">
                                                <div class="modal-body">
                                                    <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                                                    <div class="mb-3">
                                                        <label for="edit_name_<?php echo $product['id']; ?>" class="form-label">Nama Produk</label>
                                                        <input type="text" name="name" id="edit_name_<?php echo $product['id']; ?>" value="<?php echo htmlspecialchars($product['name']); ?>" class="form-control" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="edit_description_<?php echo $product['id']; ?>" class="form-label">Deskripsi</label>
                                                        <textarea name="description" id="edit_description_<?php echo $product['id']; ?>" rows="3" class="form-control"><?php echo htmlspecialchars($product['description']); ?></textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="edit_price_<?php echo $product['id']; ?>" class="form-label">Harga</label>
                                                        <input type="number" step="0.01" name="price" id="edit_price_<?php echo $product['id']; ?>" value="<?php echo $product['price']; ?>" class="form-control" required>
                                                    </div>
                                                    <?php if ($product['image_url']): ?>
                                                        <div class="mb-3 text-center">
                                                            <img src="../<?php echo htmlspecialchars(getImageUrl($product['image_url'])); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="mb-3">
                                                        <label for="edit_image_<?php echo $product['id']; ?>" class="form-label">Upload Gambar Produk Baru</label>
                                                        <input type="file" name="image" id="edit_image_<?php echo $product['id']; ?>" class="form-control" accept="image/*">
                                                        <div class="form-text">Opsional: Upload gambar produk baru (JPG, PNG, GIF, maksimal 5MB)</div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <div class="form-check">
                                                            <input type="checkbox" name="is_active" value="1" <?php echo $product['is_active'] ? 'checked' : ''; ?> class="form-check-input" id="edit_is_active_<?php echo $product['id']; ?>">
                                                            <label class="form-check-label" for="edit_is_active_<?php echo $product['id']; ?>">Aktif</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" name="update_product" class="btn btn-primary">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Capture the content
$content = ob_get_clean();

// Include the admin layout
include 'includes/admin_layout.php';
?>