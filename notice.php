<?php require_once __DIR__ . '/page_session.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notices - Student Hub Portal</title>
 <link rel="stylesheet" href="style.css?v=20261029">
 <script src="script.js" defer></script>
</head>

<body>

    <a href="#main-content">Skip to main content</a>

    <?php renderPortalHeader(); ?>
    <br>

    <main id="main-content">
<div style="background-color: #e6f0fa; border: 1px solid #bce8f1; color: #31708f; padding: 12px; border-radius: 6px; margin-bottom: 1.5rem; width: 100%; grid-column: 1 / -1;">
        <strong>Welcome to StudentHub!</strong> Check out your dashboard for the latest notices and academic updates.
    </div>
        <nav aria-label="Breadcrumb">
            <p align="left">
                <a href="index.php">Home</a> &gt; <b>Notice Board</b>
            </p>
        </nav>

        <section aria-labelledby="notice-title">
            <div align="center">
                <h1 id="notice-title">Latest Circulars &amp; Announcements</h1>
                <p>Stay updated with official administrative and departmental updates.</p>
            </div>
            <hr>
        </section>

        <br>

        <section aria-labelledby="circulars-heading">
            <h2 id="circulars-heading" align="center">Recent Circulars</h2>

            <table border="1" width="100%" cellpadding="10">
                <thead>
                    <tr>
                        <th scope="col" width="15%">Publish Date</th>
                        <th scope="col" width="20%">Notice ID</th>
                        <th scope="col">Subject Title</th>
                        <th scope="col" width="15%">Attachment</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><time datetime="2026-08-01">01 Aug 2026</time></td>
                        <td>GTU/EXAM/S26/01</td>
                        <td><b>Mid-Semester Exam Schedule (Sem 3)</b></td>
                        <td align="center"><a href="#" aria-label="Download Mid Sem Exam Schedule PDF">PDF Link</a></td>
                    </tr>
                    <tr>
                        <td><time datetime="2026-07-28">28 Jul 2026</time></td>
                        <td>CSE/DEPT/2026/09</td>
                        <td><b>OOP Practical Assignment Deadline Extension</b></td>
                        <td align="center"><a href="#" aria-label="Download Assignment Circular">PDF Link</a></td>
                    </tr>
                    <tr>
                        <td><time datetime="2026-07-15">15 Jul 2026</time></td>
                        <td>ADMIN/SCHOLAR/04</td>
                        <td><b>Digital Gujarat Scholarship Application Guidance</b></td>
                        <td align="center"><a href="#" aria-label="Download Scholarship Guidelines">PDF Link</a></td>
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