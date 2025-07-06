<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

// Check admin access
requireAdmin();

// Get statistics
$stmt = $pdo->query("SELECT COUNT(*) as total FROM reviews WHERE status = 'pending'");
$pendingCount = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT COUNT(*) as total FROM reviews WHERE status = 'approved'");
$approvedCount = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT COUNT(*) as total FROM restaurants");
$restaurantCount = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT COUNT(*) as total FROM check_ins WHERE status = 'pending'");
$pendingCheckins = $stmt->fetch()['total'];

// Get pending reviews
$stmt = $pdo->query("
    SELECT r.*, u.username, res.name as restaurant_name 
    FROM reviews r 
    JOIN users u ON r.user_id = u.id 
    JOIN restaurants res ON r.restaurant_id = res.id 
    WHERE r.status = 'pending' 
    ORDER BY r.created_at DESC
");
$pendingReviews = $stmt->fetchAll();

// Get all reviews
$stmt = $pdo->query("
    SELECT r.*, u.username, res.name as restaurant_name 
    FROM reviews r 
    JOIN users u ON r.user_id = u.id 
    JOIN restaurants res ON r.restaurant_id = res.id 
    ORDER BY r.created_at DESC
    LIMIT 50
");
$allReviews = $stmt->fetchAll();

include '../includes/header.php';
?>

<!-- Admin Dashboard CSS -->
<link rel="stylesheet" href="/rateitup/assets/styles/admin-dashboard.css">

<div class="admin-container">
    <!-- Admin Header -->
    <div class="admin-header">
        <h1 class="admin-title">
            <i class="fas fa-crown"></i> Admin Dashboard
        </h1>
        <p class="admin-subtitle">Kelola review, restaurant, dan check-in</p>
    </div>

    <!-- Quick Statistics -->
    <div class="quick-stats">
        <div class="stat-box">
            <i class="fas fa-clock stat-box-icon"></i>
            <div class="stat-box-number"><?= $pendingCount ?></div>
            <div class="stat-box-label">Pending Reviews</div>
        </div>

        <div class="stat-box">
            <i class="fas fa-check-circle stat-box-icon"></i>
            <div class="stat-box-number"><?= $approvedCount ?></div>
            <div class="stat-box-label">Approved Reviews</div>
        </div>

        <div class="stat-box">
            <i class="fas fa-store stat-box-icon"></i>
            <div class="stat-box-number"><?= $restaurantCount ?></div>
            <div class="stat-box-label">Total Restaurants</div>
        </div>

        <div class="stat-box">
            <i class="fas fa-calendar-check stat-box-icon"></i>
            <div class="stat-box-number"><?= $pendingCheckins ?></div>
            <div class="stat-box-label">Pending Check-ins</div>
        </div>
    </div>

    <!-- Alert Messages -->
    <?= getAlert() ?>

    <!-- Admin Actions -->
    <div class="admin-actions">
        <a href="manage_restaurants.php" class="btn btn-restaurants">
            <i class="fas fa-store"></i> Manage Restaurants
        </a>
        <a href="manage_checkins.php" class="btn btn-checkins">
            <i class="fas fa-calendar-check"></i> Manage Check-ins
        </a>
    </div>

    <!-- Pending Reviews Section -->
    <div class="section-card">
        <h2 class="section-header">
            <i class="fas fa-hourglass-half"></i>
            Reviews Pending
            <?php if ($pendingCount > 0): ?>
                <span class="pending-count"><?= $pendingCount ?></span>
            <?php endif; ?>
        </h2>

        <?php if (empty($pendingReviews)): ?>
            <div class="empty-pending">
                <i class="fas fa-check-circle"></i>
                <p>Tidak ada review yang perlu di-moderasi.</p>
            </div>
        <?php else: ?>
            <div style="overflow-x: auto;">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th><i class="fas fa-user"></i> User</th>
                            <th><i class="fas fa-store"></i> Restaurant</th>
                            <th><i class="fas fa-star"></i> Rating</th>
                            <th><i class="fas fa-comment"></i> Review</th>
                            <th><i class="fas fa-calendar"></i> Tanggal</th>
                            <th><i class="fas fa-cogs"></i> Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pendingReviews as $review): ?>
                            <tr>
                                <td>
                                    <div class="user-name">
                                        <i class="fas fa-user-circle"></i>
                                        <?= e($review['username']) ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="restaurant-name">
                                        <?= e($review['restaurant_name']) ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="rating">
                                        <?= displayStars($review['rating']) ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="review-text">
                                        <?= e($review['comment']) ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="date-text">
                                        <?= date('d/m/Y H:i', strtotime($review['created_at'])) ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="table-actions">
                                        <a href="process/approve.php?id=<?= $review['id'] ?>"
                                            class="btn btn-success btn-sm">
                                            <i class="fas fa-check"></i> Approve
                                        </a>
                                        <a href="process/reject.php?id=<?= $review['id'] ?>"
                                            class="btn btn-warning btn-sm">
                                            <i class="fas fa-times"></i> Reject
                                        </a>
                                        <a href="process/delete.php?id=<?= $review['id'] ?>"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Yakin hapus review ini?')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- All Reviews Section -->
    <div class="section-card">
        <h2 class="section-header">
            <i class="fas fa-list"></i> Semua Reviews
        </h2>

        <div style="overflow-x: auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th><i class="fas fa-user"></i> User</th>
                        <th><i class="fas fa-store"></i> Restaurant</th>
                        <th><i class="fas fa-star"></i> Rating</th>
                        <th><i class="fas fa-comment"></i> Review</th>
                        <th><i class="fas fa-info-circle"></i> Status</th>
                        <th><i class="fas fa-calendar"></i> Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($allReviews as $review): ?>
                        <tr>
                            <td>
                                <div class="user-name">
                                    <i class="fas fa-user-circle"></i>
                                    <?= e($review['username']) ?>
                                </div>
                            </td>
                            <td>
                                <div class="restaurant-name">
                                    <?= e($review['restaurant_name']) ?>
                                </div>
                            </td>
                            <td>
                                <div class="rating">
                                    <?= displayStars($review['rating']) ?>
                                </div>
                            </td>
                            <td>
                                <div class="review-text">
                                    <?= e(substr($review['comment'], 0, 100)) ?>...
                                </div>
                            </td>
                            <td>
                                <?php if ($review['status'] == 'pending'): ?>
                                    <span class="badge badge-pending">
                                        <i class="fas fa-clock"></i> Pending
                                    </span>
                                <?php elseif ($review['status'] == 'approved'): ?>
                                    <span class="badge badge-approved">
                                        <i class="fas fa-check"></i> Approved
                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-rejected">
                                        <i class="fas fa-times"></i> Rejected
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="date-text">
                                    <?= date('d/m/Y', strtotime($review['created_at'])) ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>