<?php
require_once 'config/database.php';

// Ambil semua review yang approved dengan info restaurant dan user
$stmt = $pdo->query("
    SELECT r.*, u.username, res.name as restaurant_name, res.address 
    FROM reviews r 
    JOIN users u ON r.user_id = u.id 
    JOIN restaurants res ON r.restaurant_id = res.id 
    WHERE r.status = 'approved' 
    ORDER BY r.created_at DESC
    LIMIT 9
");
$reviews = $stmt->fetchAll();

// Ambil statistik
$stmt = $pdo->query("SELECT COUNT(*) as total FROM reviews WHERE status = 'approved'");
$totalReviews = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT COUNT(*) as total FROM restaurants");
$totalRestaurants = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT COUNT(*) as total FROM users WHERE role = 'user'");
$totalUsers = $stmt->fetch()['total'];
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rate It Up - Review Kuliner Nganjuk</title>

    <!-- CSS Files -->
    <link rel="stylesheet" href="/rateitup/assets/styles/styles.css">
    <link rel="stylesheet" href="/rateitup/assets/styles/public.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="public-navbar">
        <div class="navbar-content">
            <a href="/rateitup/" class="logo">
                <i class="fas fa-utensils"></i> Rate It Up
            </a>
            <div class="nav-buttons">
                <a href="auth/login.php" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i> Login
                </a>
                <a href="auth/register.php" class="btn btn-success">
                    <i class="fas fa-user-plus"></i> Daftar
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <h1 class="hero-title">Review Kuliner Nganjuk</h1>
        <p class="hero-subtitle">
            Platform review tempat makan terpercaya di Nganjuk.<br>
            Temukan tempat kuliner terbaik berdasarkan review dari pengguna!
        </p>

        <div class="stats-container">
            <div class="stat-box">
                <h3 class="stat-number"><?= $totalRestaurants ?></h3>
                <p class="stat-label">Restaurants</p>
            </div>
            <div class="stat-box">
                <h3 class="stat-number"><?= $totalReviews ?></h3>
                <p class="stat-label">Reviews</p>
            </div>
            <div class="stat-box">
                <h3 class="stat-number"><?= $totalUsers ?></h3>
                <p class="stat-label">Members</p>
            </div>
        </div>
    </section>

    <!-- Reviews Section -->
    <section class="reviews-section">
        <h2 class="section-title">
            <i class="fas fa-star"></i> Review Terbaru
        </h2>

        <?php if (empty($reviews)): ?>
            <div class="empty-state">
                <i class="fas fa-comments empty-icon"></i>
                <h3 class="empty-title">Belum Ada Review</h3>
                <p class="empty-text">Jadilah yang pertama memberikan review!</p>
                <a href="auth/register.php" class="btn btn-primary">
                    <i class="fas fa-rocket"></i> Mulai Sekarang
                </a>
            </div>
        <?php else: ?>
            <div class="reviews-grid">
                <?php foreach ($reviews as $review): ?>
                    <div class="review-card">
                        <div class="restaurant-info">
                            <h3 class="restaurant-name"><?= htmlspecialchars($review['restaurant_name']) ?></h3>
                            <p class="restaurant-address">
                                <i class="fas fa-map-marker-alt"></i>
                                <?= htmlspecialchars($review['address']) ?>
                            </p>
                        </div>

                        <div class="rating-display">
                            <span class="stars">
                                <?php
                                for ($i = 1; $i <= 5; $i++) {
                                    echo $i <= $review['rating'] ? '★' : '☆';
                                }
                                ?>
                            </span>
                            <span class="rating-number"><?= $review['rating'] ?>/5</span>
                        </div>

                        <div class="review-content">
                            <p class="review-text"><?= htmlspecialchars($review['comment']) ?></p>
                        </div>

                        <div class="review-meta">
                            <span class="reviewer">
                                <i class="fas fa-user-circle"></i>
                                <?= htmlspecialchars($review['username']) ?>
                            </span>
                            <span class="review-date">
                                <i class="fas fa-calendar"></i>
                                <?= date('d M Y', strtotime($review['created_at'])) ?>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <h2 class="cta-title">Punya Pengalaman Kuliner?</h2>
        <p class="cta-subtitle">Bagikan review Anda dan bantu orang lain menemukan tempat makan terbaik!</p>
        <a href="auth/register.php" class="cta-button">
            <i class="fas fa-rocket"></i> Daftar Gratis Sekarang
        </a>
    </section>

    <!-- Footer -->
    <footer class="public-footer">
        <p>&copy; Rate It Up - Platform Review Kuliner Nganjuk</p>
        <div class="footer-links">
            <a href="#"><i class="fas fa-info-circle"></i> Tentang</a>
            <a href="#"><i class="fas fa-envelope"></i> Kontak</a>
            <a href="#"><i class="fas fa-shield-alt"></i> Privasi</a>
            <a href="auth/login.php"><i class="fas fa-sign-in-alt"></i> Login</a>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="/rateitup/assets/js/script.js"></script>
</body>

</html>