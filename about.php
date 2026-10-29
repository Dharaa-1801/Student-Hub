<?php require_once __DIR__ . '/page_session.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Student Hub Portal</title>
 <link rel="stylesheet" href="style.css?v=20261029">
<script src="script.js" defer></script>
    
</head>

<body>

    <a href="#main-content">Skip to main content</a>

    <?php renderPortalHeader(); ?>
    <br>

 <main> <nav aria-label="Breadcrumb">
    <div style="background-color: #e6f0fa; border: 1px solid #bce8f1; color: #31708f; padding: 12px; border-radius: 6px; margin-bottom: 1.5rem; width: 100%; grid-column: 1 / -1;">
        <strong>Welcome to StudentHub!</strong> Check out your dashboard for the latest notices and academic updates.
    </div>
            <p align="left">
                <a href="index.php">Home</a> &gt; <b>About Us</b>
            </p>
        </nav>

        <section aria-labelledby="about-title">
            <div align="center">
                <h1 id="about-title">About Student Hub Portal</h1>
                <p>Designing modern, accessible, and transparent digital solutions for engineering education.</p>
            </div>
            <hr>
        </section>

        <br>

        <section aria-labelledby="features-heading">
            <h2 id="features-heading">Core System Capabilities</h2>
            <table border="1" width="100%" cellpadding="10">
                <thead>
                    <tr>
                        <th scope="col" width="30%">Feature Module</th>
                        <th scope="col">Purpose &amp; Description</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><b>Academic Management</b></td>
                        <td>Displays subject-wise syllabus, weekly lecture schedules, lab practical slots, and faculty references.</td>
                    </tr>
                    <tr>
                        <td><b>E-Certificate Engine</b></td>
                        <td>Enables paperless requests for Bonafide Certificates, Fee Receipts, and conduct documentation.</td>
                    </tr>
                    <tr>
                        <td><b>Accessibility Compliant</b></td>
                        <td>Built strictly following WCAG 2.1 Web Accessibility Guidelines, keyboard navigation, and screen reader compatibility.</td>
                    </tr>
                    <tr>
                        <td><b>Instant Notification Feed</b></td>
                        <td>Publishes circulars regarding exam schedules, GTU notices, and department events in real-time.</td>
                    </tr>
                </tbody>
            </table>
        </section>

    </main>

    <br>
    <hr>

    <footer role="contentinfo">
        <div align="center">
            <h3>Student Hub Portal Â© 2026</h3>
        </div>
    </footer>

<?php renderPortalFooter(); ?>
</body>

</html>