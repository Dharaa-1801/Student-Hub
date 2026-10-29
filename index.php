<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/page_session.php';

startSecureSession();
$currentUser = getCurrentUser($pdo);
$isLoggedIn = $currentUser !== null;
$displayName = $isLoggedIn
    ? htmlspecialchars($currentUser['fullname'], ENT_QUOTES, 'UTF-8')
    : '';
$displayEmail = $isLoggedIn
    ? htmlspecialchars($currentUser['email'], ENT_QUOTES, 'UTF-8')
    : '';
$displayRole = $isLoggedIn
    ? htmlspecialchars(ucfirst($currentUser['role']), ENT_QUOTES, 'UTF-8')
    : '';
$displayPhone = $isLoggedIn
    ? htmlspecialchars((string) ($currentUser['phone'] ?? 'Not provided'), ENT_QUOTES, 'UTF-8')
    : '';
$displayCourse = $isLoggedIn
    ? htmlspecialchars((string) ($currentUser['course'] ?? 'Not provided'), ENT_QUOTES, 'UTF-8')
    : '';
$displayYear = $isLoggedIn
    ? htmlspecialchars((string) ($currentUser['academic_year'] ?? 'Not provided'), ENT_QUOTES, 'UTF-8')
    : '';
$displayGender = $isLoggedIn
    ? htmlspecialchars((string) ($currentUser['gender'] ?? 'Not provided'), ENT_QUOTES, 'UTF-8')
    : '';
$displayCreatedAt = $isLoggedIn
    ? htmlspecialchars($currentUser['created_at'], ENT_QUOTES, 'UTF-8')
    : '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Hub Portal - Home</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Custom CSS File -->

     
    <link rel="stylesheet" href="style.css?v=20261029">
    <script src="script.js?v=20261022" defer></script>
</head>

<body class="bg-slate-50 text-slate-900 font-sans min-h-screen flex flex-col">

    <!-- Accessibility Skip Link -->
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-2 focus:left-2 bg-cyan-500 text-purple-950 p-2 rounded font-bold z-50">
        Skip to main content
    </a>

    <!-- Header Section -->
    <?php renderPortalHeader(); ?>

    <!-- Main Content Area -->
    <main id="main-content" class="flex-1 w-full max-w-6xl mx-auto px-4 py-6">
        <?php if ($isLoggedIn): ?>
            <section style="background-color: #ecfeff; border: 1px solid #06b6d4; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                <h2 style="color: #3b0764; margin-bottom: 0.5rem;">Welcome, <?= $displayName ?>!</h2>
                <p><strong>Email:</strong> <?= $displayEmail ?></p>
                <p><strong>Phone:</strong> <?= $displayPhone ?></p>
                <p><strong>Course:</strong> <?= $displayCourse ?></p>
                <p><strong>Academic Year:</strong> <?= $displayYear ?></p>
                <p><strong>Gender:</strong> <?= $displayGender ?></p>
                <p><strong>Role:</strong> <?= $displayRole ?></p>
                <p><strong>Registered:</strong> <?= $displayCreatedAt ?></p>
                <a href="<?= $displayRole === 'Admin' ? 'admin_dashboard.php' : 'student_dashboard.php' ?>"
                   style="display: inline-block; margin-top: 0.75rem; padding: 0.5rem 1rem; background: #06b6d4; color: #172554; text-decoration: none; border-radius: 4px; font-weight: bold;">
                    Open Dashboard
                </a>
            </section>
        <?php endif; ?>
        <div style="background-color: #e6f0fa; border: 1px solid #bce8f1; color: #31708f; padding: 12px; border-radius: 6px; margin-bottom: 1.5rem; width: 100%; grid-column: 1 / -1;">
        <strong>Welcome to StudentHub!</strong> Check out your dashboard for the latest notices and academic updates.
    </div>
        
        <!-- Welcome Banner -->
        <section class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm mb-6">
            <h2 class="text-purple-950 text-2xl font-bold mb-2">Welcome to Student Hub Portal</h2>
            <p class="text-slate-600 text-sm mb-4">Your one-stop academic platform for schedules, coursework, announcements, and portal services.</p>
            <hr class="border-t-2 border-cyan-500 my-4">
            
            <!-- Quick Actions Button Group -->
            <div class="flex flex-wrap gap-3">
                <a href="dashboard.php" class="bg-cyan-500 text-purple-950 font-bold px-4 py-2 rounded-md text-sm hover:bg-cyan-600 transition">View Dashboard</a>
                <a href="profile.php" class="bg-purple-900 text-white font-bold px-4 py-2 rounded-md text-sm hover:bg-purple-800 transition">View Profile</a>
                <a href="academics.php" class="bg-slate-200 text-slate-800 font-bold px-4 py-2 rounded-md text-sm hover:bg-slate-300 transition">Academics</a>
            </div>
        </section>
           <!-- Practical 4 Extra Interactive Components -->
<section style="margin-top: 2rem;">
    <h2></h2>
    
    <!-- 1. FAQ Open/Close (Accordion) -->
    <div style="margin-bottom: 1.5rem;">
        <h2>Frequently Asked Questions (FAQ)</h2>
        <br>
        <details style="background: var(--bg-card); padding: 10px; border: 1px solid var(--border-color); border-radius: 6px; margin-bottom: 5px; cursor: pointer;">
            <summary style="font-weight: bold;">How do I check my semester attendance?</summary>
            <p style="margin-top: 8px; color: var(--text-muted);">You can check your attendance directly on the Dashboard under the Academic Progress section.</p>
        </details>
        <details style="background: var(--bg-card); padding: 10px; border: 1px solid var(--border-color); border-radius: 6px; cursor: pointer;">
            <summary style="font-weight: bold;">How to reset my portal password?</summary>
            <p style="margin-top: 8px; color: var(--text-muted);">Click on 'Forgot Password' on the login page or contact the IT support desk.</p>
        </details>
    </div>

    <!-- 2. Modal Popup Trigger -->
    <div style="margin-bottom: 1.5rem;">
        <h3></h3>
        <button id="open-modal-btn" class="btn">Open Notice Modal</button>
    </div>

    <!-- Modal Box Structure -->
    <div id="demo-modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000; justify-content:center; align-items:center;">
        <div style="background:var(--bg-card); padding:20px; border-radius:8px; width:90%; max-width:400px; text-align:center; box-shadow:0 4px 10px rgba(0,0,0,0.2);">
            <h3>Important Notice</h3>
            <p style="margin: 10px 0;">Mid-semester exams start next month. Prepare well!</p>
            <button id="close-modal-btn" class="btn" style="background:#64748b; color:white;">Close</button>
        </div>
    </div>

    <!-- 3. Image / Content Slider -->
    <div>
        <h3></h3>
        <div id="slider-container" style="background:var(--primary-dark); color:white; padding:20px; border-radius:8px; text-align:center; min-height:100px; display:flex; flex-direction:column; justify-content:center; align-items:center;">
            <p id="slide-text" style="font-size: 1.1rem; font-weight: bold;">Welcome to the Annual Tech Fest 2026!</p>
            <div style="margin-top: 10px;">
                <button id="prev-slide" class="btn btn-sm" style="padding: 5px 10px; font-size: 0.8rem;">Previous</button>
                <button id="next-slide" class="btn btn-sm" style="padding: 5px 10px; font-size: 0.8rem;">Next</button>
            </div>
        </div>
    </div>
</section>
        <section aria-labelledby="vision-heading">
            <h2 id="vision-heading">Mission &amp; Vision</h2>
            <p>The <b>Student Hub Portal</b> was created to bridge the communication gap between students, faculty members, and college administration. It serves as a single digital platform where students can view their academic performance, request administrative documents, and download curriculum resources without delay.</p>
        </section>

        <!-- Features / Grid Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            
            <!-- Card 1 -->
            <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm hover:-translate-y-1 hover:shadow-md transition">
                <h3 class="text-purple-950 text-lg font-bold mb-2">Academic Resources</h3>
                <p class="text-slate-600 text-xs leading-relaxed">Access syllabus, lecture notes, class schedules, and course materials instantly.</p>
            </div>

            <!-- Card 2 -->
            <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm hover:-translate-y-1 hover:shadow-md transition">
                <h3 class="text-purple-950 text-lg font-bold mb-2">Latest Notices</h3>
                <p class="text-slate-600 text-xs leading-relaxed">Stay updated with official college circulars, exam dates, and announcements.</p>
            </div>

            <!-- Card 3 -->
            <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm hover:-translate-y-1 hover:shadow-md transition">
                <h3 class="text-purple-950 text-lg font-bold mb-2">Student Support</h3>
                <p class="text-slate-600 text-xs leading-relaxed">Submit feedback, request certificates, or get assistance with portal queries.</p>
            </div>

        </div>
<?php renderPortalFooter(); ?>
</body>
</html>