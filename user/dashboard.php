<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

// Check login
requireLogin();

// Get user's reviews
$stmt = $pdo->prepare("
    SELECT r.*, res.name as restaurant_name 
    FROM reviews r 
    JOIN restaurants res ON r.restaurant_id = res.id 
    WHERE r.user_id = ? 
    ORDER BY r.created_at DESC
");
$stmt->execute([$_SESSION['user_id']]);
$reviews = $stmt->fetchAll();

// Get statistics
$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM reviews WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$totalReviews = $stmt->fetch()['total'];

$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM reviews WHERE user_id = ? AND status = 'approved'");
$stmt->execute([$_SESSION['user_id']]);
$approvedReviews = $stmt->fetch()['total'];

$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM check_ins WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$totalCheckins = $stmt->fetch()['total'];

include '../includes/header.php';
?>

<link rel="stylesheet" href="/rateitup/assets/styles/user-dashboard.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">


<div class="dashboard-container">
    <div class="dashboard-header">
        <h1 class="dashboard-title">
            <i class="fas fa-home"></i> My Dashboard
        </h1>
        <p class="dashboard-subtitle">Kelola review dan check-in restoran favorit Anda</p>
    </div>

    <div class="stats-container">
        <div class="stat-card fade-in">
            <div class="stat-icon">
                <i class="fas fa-utensils"></i>
            </div>
            <div class="stat-number"><?= $totalReviews ?></div>
            <div class="stat-label">Total Reviews</div>
        </div>

        <div class="stat-card fade-in">
            <div class="stat-icon">
                <i class="fas fa-award"></i>
            </div>
            <div class="stat-number"><?= $approvedReviews ?></div>
            <div class="stat-label">Approved Reviews</div>
        </div>

        <div class="stat-card fade-in">
            <div class="stat-icon">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="stat-number"><?= $totalCheckins ?></div>
            <div class="stat-label">Check-ins</div>
        </div>
    </div>

    <?= getAlert() ?>

    <div class="action-section">
        <div class="action-buttons">
            <a href="add_review.php" class="btn btn-add-review">
                <i class="fas fa-plus-circle"></i> Tambah Review Baru
            </a>
            <a href="my_checkins.php" class="btn btn-my-checkins">
                <i class="fas fa-list-alt"></i> My Check-ins
            </a>
            <a href="add_checkin.php" class="btn btn-checkin">
                <i class="fas fa-calendar-check"></i> Check-in Restaurant
            </a>
        </div>
    </div>

    <div class="reviews-section fade-in">
        <h2 class="section-header">
            <i class="fas fa-star"></i> My Reviews
        </h2>

        <?php if (empty($reviews)): ?>
            <div class="empty-state">
                <i class="fas fa-comments empty-icon"></i>
                <h3 class="empty-title">Belum Ada Review</h3>
                <p class="empty-text">
                    Anda belum memiliki review. Mulai bagikan pengalaman kuliner Anda!
                </p>
                <a href="add_review.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Mulai Review Sekarang
                </a>
            </div>
        <?php else: ?>
            <div class="table-wrapper">
                <table class="reviews-table">
                    <thead>
                        <tr>
                            <th><i class="fas fa-store"></i>Restaurant</th>
                            <th><i class="fas fa-star"></i>Rating</th>
                            <th><i class="fas fa-comment"></i>Review</th>
                            <th><i class="fas fa-info-circle"></i>Status</th>
                            <th><i class="fas fa-calendar"></i>Tanggal</th>
                            <th><i class="fas fa-cogs"></i>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reviews as $review): ?>
                            <tr>
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
                                        <?= e(substr($review['comment'], 0, 80)) ?>...
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
                                        <i class="fas fa-calendar-alt"></i>
                                        <?= date('d/m/Y', strtotime($review['created_at'])) ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="table-actions">
                                        <a href="edit_review.php?id=<?= $review['id'] ?>"
                                            class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i> Edit
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
</div>

<?php include '../includes/footer.php'; ?>