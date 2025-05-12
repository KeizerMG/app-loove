<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'Loove - Dating App' ?></title>
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@700&display=swap" rel="stylesheet">
</head>
<body>
    <header class="site-header">
        <div class="container">
            <div class="logo">
                <a href="<?= APP_URL ?>/">
                    <h1>Loove</h1>
                </a>
            </div>
            
            <nav class="main-nav">
                <ul>
                    <li><a href="<?= APP_URL ?>/">Home</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="<?= APP_URL ?>/discover">Discover</a></li>
                        <li><a href="<?= APP_URL ?>/matches">Matches</a></li>
                        <li><a href="<?= APP_URL ?>/messages">Messages</a></li>
                        <li class="dropdown">
                            <a href="<?= APP_URL ?>/profile">Profile</a>
                        </li>
                        <li><a href="<?= APP_URL ?>/logout">Logout</a></li>
                    <?php else: ?>
                        <li><a href="<?= APP_URL ?>/login">Login</a></li>
                        <li><a href="<?= APP_URL ?>/register">Register</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    
    <main>
        <?= $content ?>
    </main>
    
    <footer class="site-footer">
        <div class="container">
            <p>&copy; <?= date('Y') ?> Loove Dating App. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
