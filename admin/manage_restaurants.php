<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

// Check admin access
requireAdmin();

// Get all restaurants
$stmt = $pdo->query("SELECT * FROM restaurants ORDER BY name");
$restaurants = $stmt->fetchAll();

// Get statistics
$totalRestaurants = count($restaurants);
$stmt = $pdo->query("SELECT COUNT(*) as total FROM reviews");
$totalReviews = $stmt->fetch()['total'];

include '../includes/header.php';
?>

<!-- Manage Restaurants CSS -->
<link rel="stylesheet" href="/rateitup/assets/styles/manage-restaurants.css">

<div class="manage-container">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-store"></i> Manage Restaurants
        </h1>
        <p class="page-subtitle">Kelola daftar restaurant yang tersedia</p>
    </div>

    <!-- Action Buttons -->
    <div class="action-section">
        <a href="add_restaurant.php" class="btn btn-add">
            <i class="fas fa-plus-circle"></i> Tambah Restaurant Baru
        </a>
        <a href="dashboard.php" class="btn btn-back">
            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

    <!-- Alert Messages -->
    <?= getAlert() ?>

    <!-- Table Container -->
    <div class="table-container">
        <!-- Statistics Summary -->
        <div class="stats-summary">
            <div class="stat-card">
                <div class="stat-number"><?= $totalRestaurants ?></div>
                <div class="stat-label">Total Restaurants</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $totalReviews ?></div>
                <div class="stat-label">Total Reviews</div>
            </div>
        </div>

        <!-- Search Box -->
        <div class="search-section">
            <div class="search-box">
                <i class="fas fa-search search-icon"></i>
                <input type="text"
                    class="search-input"
                    id="searchInput"
                    placeholder="Cari restaurant...">
            </div>
        </div>

        <?php if (empty($restaurants)): ?>
            <div class="empty-state">
                <i class="fas fa-store empty-icon"></i>
                <h3 class="empty-title">Belum Ada Restaurant</h3>
                <p class="empty-text">Mulai tambahkan restaurant pertama</p>
                <a href="add_restaurant.php" class="btn btn-add">
                    <i class="fas fa-plus"></i> Tambah Restaurant
                </a>
            </div>
        <?php else: ?>
            <table class="restaurants-table" id="restaurantsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Restaurant</th>
                        <th>Alamat</th>
                        <th>Tanggal Ditambahkan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($restaurants as $restaurant): ?>
                        <tr>
                            <td class="id-cell"><?= $restaurant['id'] ?></td>
                            <td class="restaurant-name"><?= e($restaurant['name']) ?></td>
                            <td class="restaurant-address"><?= e($restaurant['address']) ?></td>
                            <td class="date-cell"><?= date('d/m/Y', strtotime($restaurant['created_at'])) ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="edit_restaurant.php?id=<?= $restaurant['id'] ?>"
                                        class="btn btn-edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="process/delete_restaurant.php?id=<?= $restaurant['id'] ?>"
                                        class="btn btn-delete"
                                        onclick="return confirm('Yakin hapus restaurant ini? Review terkait juga akan terhapus!')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<!-- Search Functionality -->
<script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const table = document.getElementById('restaurantsTable');
        const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

        for (let i = 0; i < rows.length; i++) {
            const name = rows[i].getElementsByClassName('restaurant-name')[0].textContent.toLowerCase();
            const address = rows[i].getElementsByClassName('restaurant-address')[0].textContent.toLowerCase();

            if (name.includes(searchTerm) || address.includes(searchTerm)) {
                rows[i].style.display = '';
            } else {
                rows[i].style.display = 'none';
            }
        }
    });
</script>

<?php include '../includes/footer.php'; ?>