<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';

startSecureSession();

if (empty($_SESSION['user_id'])) {
    restoreRememberedSession($pdo);
}

if (!empty($_SESSION['user_id']) &&
    (!isset($_SESSION['last_activity']) ||
        time() - (int) $_SESSION['last_activity'] >= SESSION_TIMEOUT)) {
    $_SESSION = [];
    session_destroy();
    clearRememberMeCookie();
    header('Location: login.php?error=' . urlencode('Your session expired. Please log in again.'));
    exit();
}

$currentUser = getCurrentUser($pdo);

if ($currentUser !== null) {
    $_SESSION['fullname'] = $currentUser['fullname'];
    $_SESSION['email'] = $currentUser['email'];
    $_SESSION['phone'] = $currentUser['phone'];
    $_SESSION['course'] = $currentUser['course'];
    $_SESSION['academic_year'] = $currentUser['academic_year'];
    $_SESSION['gender'] = $currentUser['gender'];
    $_SESSION['profile_photo'] = $currentUser['profile_photo'];
    $_SESSION['role'] = $currentUser['role'];
    $_SESSION['last_activity'] = time();
}

function renderSessionBar(): void
{
    if (empty($_SESSION['user_id'])) {
        return;
    }

    $name = htmlspecialchars((string) ($_SESSION['fullname'] ?? ''), ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars((string) ($_SESSION['email'] ?? ''), ENT_QUOTES, 'UTF-8');
    $phone = htmlspecialchars((string) ($_SESSION['phone'] ?? 'Not provided'), ENT_QUOTES, 'UTF-8');
    $course = htmlspecialchars((string) ($_SESSION['course'] ?? 'Not provided'), ENT_QUOTES, 'UTF-8');
    $year = htmlspecialchars((string) ($_SESSION['academic_year'] ?? 'Not provided'), ENT_QUOTES, 'UTF-8');
    $gender = htmlspecialchars((string) ($_SESSION['gender'] ?? 'Not provided'), ENT_QUOTES, 'UTF-8');
    $role = htmlspecialchars(ucfirst((string) ($_SESSION['role'] ?? '')), ENT_QUOTES, 'UTF-8');
    $dashboard = $role === 'Admin' ? 'admin_dashboard.php' : 'student_dashboard.php';

    echo '<div style="background:#ecfeff;border:1px solid #06b6d4;padding:8px 12px;text-align:right;">'
        . '<strong>' . $name . '</strong> (' . $role . ') | ' . $email
        . ' | <a href="' . $dashboard . '">Dashboard</a>'
        . ' | <a href="logout.php">Logout</a></div>';
}

function renderPortalHeader(): void
{
    $currentPage = basename($_SERVER['PHP_SELF']);
    $isLoggedIn = !empty($_SESSION['user_id']);
    $dashboardPage = (($_SESSION['role'] ?? '') === 'admin')
        ? 'admin_dashboard.php'
        : 'student_dashboard.php';
    $links = [
        'index.php' => 'Home',
        'dashboard.php' => 'Dashboard',
        'profile.php' => 'Profile',
        'about.php' => 'About',
        'academics.php' => 'Academics',
        'classschedule.php' => 'Class Schedule',
        'studymaterials.php' => 'Study Materials',
        'certificates.php' => 'Certificates',
        'notice.php' => 'Notices',
        'events.php' => 'Events',
        'studentsupport.php' => 'Support',
        'contact.php' => 'Contact Us',
        'feedback.php' => 'Feedback',
    ];

    echo '<header class="portal-sidebar" role="banner">'
        . '<div class="header-container">'
        . '<a class="brand" href="index.php"><span class="brand-mark">SH</span><span><strong>STUDENT HUB</strong><small>Student portal</small></span></a>'
        . '<button id="menu-toggle" class="hamburger-btn" aria-label="Toggle Navigation">&#9776;</button>'
        . '</div>';

    if ($isLoggedIn) {
        $name = htmlspecialchars((string) ($_SESSION['fullname'] ?? ''), ENT_QUOTES, 'UTF-8');
        $email = htmlspecialchars((string) ($_SESSION['email'] ?? ''), ENT_QUOTES, 'UTF-8');
        $phone = htmlspecialchars((string) ($_SESSION['phone'] ?? 'Not provided'), ENT_QUOTES, 'UTF-8');
        $course = htmlspecialchars((string) ($_SESSION['course'] ?? 'Not provided'), ENT_QUOTES, 'UTF-8');
        $year = htmlspecialchars((string) ($_SESSION['academic_year'] ?? 'Not provided'), ENT_QUOTES, 'UTF-8');
        $gender = htmlspecialchars((string) ($_SESSION['gender'] ?? 'Not provided'), ENT_QUOTES, 'UTF-8');
        $role = htmlspecialchars(ucfirst((string) ($_SESSION['role'] ?? '')), ENT_QUOTES, 'UTF-8');
        echo '<div class="session-summary">'
            . '<span class="user-avatar">' . strtoupper(substr($name, 0, 1)) . '</span>'
            . '<span class="user-details"><strong>' . $name . '</strong><small>' . $role . ' &middot; ' . $email . '</small></span>'
            . '</div>';
    }

    echo '<nav aria-label="Main Navigation"><ul class="nav-list" id="nav-list">';

    foreach ($links as $href => $label) {
        $current = ($href === $currentPage ||
            ($href === 'dashboard.php' && $currentPage === $dashboardPage))
            ? ' aria-current="page"'
            : '';
        echo '<li><a href="' . $href . '"' . $current . '>' . $label . '</a></li>';
    }

    if ($isLoggedIn) {
        echo '<li><a href="logout.php">Logout</a></li>';
        if (($_SESSION['role'] ?? '') === 'admin') {
            echo '<li><a href="admin_management.php"' .
                ($currentPage === 'admin_management.php' ? ' aria-current="page"' : '') .
                '>Admin Control</a></li>';
            echo '<li><a href="student_management.php"' .
                ($currentPage === 'student_management.php' ? ' aria-current="page"' : '') .
                '>Student Management</a></li>';
        }
    } else {
        echo '<li><a href="login.php"' . ($currentPage === 'login.php' ? ' aria-current="page"' : '') . '>Login</a></li>';
    }

    echo '<li><button id="theme-toggle" class="theme-toggle">Dark mode</button></li>'
        . '</ul></nav></header>';
}

function renderPortalFooter(): void
{
    echo '<footer><p>Student Hub Portal &copy; ' . date('Y') . '</p></footer>';
}
