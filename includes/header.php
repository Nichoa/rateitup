<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rate It Up - Review Kuliner</title>

    <link rel="stylesheet" href="/rateitup/assets/styles/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <?php if (isLoggedIn()): ?>
        <nav>
            <div class="nav-container">
                <div class="nav-left">
                    <a href="/rateitup/" class="nav-brand">
                        <i class="fas fa-utensils"></i> Rate It Up
                    </a>
                    <?php if (isAdmin()): ?>
                        <a href="/rateitup/admin/dashboard.php">Dashboard Admin</a>
                    <?php else: ?>
                        <a href="/rateitup/user/dashboard.php">My Dashboard</a>
                        <a href="/rateitup/user/add_review.php">Add Review</a>
                        <a href="/rateitup/user/my_checkins.php">My Check-ins</a>
                    <?php endif; ?>
                </div>

                <div class="nav-right">
                    <span class="user-greeting">Hi, <?= e($_SESSION['username']) ?></span>
                    <a href="/rateitup/auth/logout.php" class="btn btn-danger btn-sm">Logout</a>
                </div>
            </div>
        </nav>
    <?php endif; ?>

    <div class="container main-content">