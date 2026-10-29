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
    $role = (string) ($_SESSION['role'] ?? '');
    $dashboardPage = (($_SESSION['role'] ?? '') === 'admin')
        ? 'admin_dashboard.php'
        : 'student_dashboard.php';
    $links = [
        'Workspace' => [
            'index.php' => 'Home',
            'dashboard.php' => 'Dashboard',
            'profile.php' => 'Profile',
        ],
        'Academics' => [
            'academics.php' => 'Academics',
            'classschedule.php' => 'Class Schedule',
            'studymaterials.php' => 'Study Materials',
            'certificates.php' => 'Certificates',
        ],
        'Campus' => [
            'notice.php' => 'Notices',
            'events.php' => 'Events',
            'studentsupport.php' => 'Support',
            'contact.php' => 'Contact Us',
            'feedback.php' => 'Feedback',
        ],
    ];
    if ($role === 'admin') {
        $links['Administration'] = [
            'admin_dashboard.php' => 'Admin Dashboard',
            'admin_management.php' => 'Control Center',
            'student_management.php' => 'Student Management',
        ];
    }

    echo '<header class="portal-sidebar" role="banner">'
        . '<div class="header-container">'
        . '<a class="brand" href="index.php"><span class="brand-mark">SH</span><span><strong>STUDENT HUB</strong><small>Student portal</small></span></a>'
        . '<button id="menu-toggle" class="hamburger-btn" aria-label="Toggle navigation" aria-controls="nav-list" aria-expanded="false">&#9776;</button>'
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

    echo '<nav aria-label="Main Navigation">'
        . '<div class="nav-tools">'
        . '<label class="sr-only" for="nav-search">Search portal pages</label>'
        . '<input id="nav-search" class="nav-search" type="search" placeholder="Search pages..." autocomplete="off">'
        . '<span class="nav-shortcut" aria-hidden="true">/</span>'
        . '</div><ul class="nav-list" id="nav-list">';

    foreach ($links as $section => $sectionLinks) {
        $sectionId = 'nav-' . strtolower(preg_replace('/[^a-z0-9]+/i', '-', $section));
        echo '<li class="nav-section-label"><button type="button" class="nav-section-toggle"'
            . ' aria-expanded="true" aria-controls="' . $sectionId . '">'
            . htmlspecialchars($section, ENT_QUOTES, 'UTF-8') . '<span aria-hidden="true">⌄</span></button></li>';
        foreach ($sectionLinks as $href => $label) {
            $current = ($href === $currentPage ||
                ($href === 'dashboard.php' && $currentPage === $dashboardPage))
                ? ' aria-current="page"'
                : '';
            echo '<li class="nav-item" data-nav-group="' . $sectionId . '"><a href="' . $href . '"' . $current . '>'
                . $label . '</a></li>';
        }
    }

    if ($isLoggedIn) {
        echo '<li class="nav-section-label"><button type="button" class="nav-section-toggle"'
            . ' aria-expanded="true" aria-controls="nav-account">Account<span aria-hidden="true">⌄</span></button></li>';
        echo '<li class="nav-item" data-nav-group="nav-account"><a href="logout.php">Sign out</a></li>';
    } else {
        echo '<li class="nav-section-label"><button type="button" class="nav-section-toggle"'
            . ' aria-expanded="true" aria-controls="nav-account">Account<span aria-hidden="true">⌄</span></button></li>';
        echo '<li class="nav-item" data-nav-group="nav-account"><a href="login.php"'
            . ($currentPage === 'login.php' ? ' aria-current="page"' : '') . '>Login</a></li>';
    }

    echo '<li class="nav-footer"><button id="theme-toggle" class="theme-toggle">Dark mode</button></li>'
        . '</ul></nav></header><button class="back-to-top" type="button" aria-label="Back to top">↑</button>';
}

function renderPortalFooter(): void
{
    echo '<footer class="portal-footer"><p>Student Hub Portal &copy; ' . date('Y') . '</p></footer>';
}
