<?php
require_once __DIR__ . '/page_session.php';
$publishedMaterials = $pdo->query(
    'SELECT subject_code, subject_name, material_type, title, resource_url
     FROM study_materials ORDER BY created_at DESC'
)->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Study Materials - Student Hub Portal</title>
    <link rel="stylesheet" href="style.css?v=20261029">
    <script src="script.js" defer></script>
</head>

<body>

    <!-- Skip Link for Keyboard Accessibility -->
    <a href="#main-content">Skip to main content</a>

    <!-- Header Section -->
   <?php renderPortalHeader(); ?>

    <br>

    <!-- Main Content Area -->
    <main id="main-content">
<div style="background-color: #e6f0fa; border: 1px solid #bce8f1; color: #31708f; padding: 12px; border-radius: 6px; margin-bottom: 1.5rem; width: 100%; grid-column: 1 / -1;">
        <strong>Welcome to StudentHub!</strong> Check out your dashboard for the latest notices and academic updates.
    </div>
        <!-- Intermediate Extension: Breadcrumb Navigation -->
        <nav aria-label="Breadcrumb">
            <p align="left">
                <a href="index.php">Home</a> &gt; <a href="academics.php">Academics</a> &gt; <b>Study Materials</b>
            </p>
        </nav>

        <section aria-labelledby="study-materials-title">
            <div align="center">
                <h2 id="study-materials-title">Semester 3 Study Materials</h2>
                <p>Download notes, presentations, assignments and previous papers.</p>
            </div>
            <hr>

            <!-- Available Study Materials Table Section -->
            <h2 id="available-materials-heading" align="center">Available Study Materials</h2>

            <table border="1" width="100%" cellpadding="10" aria-labelledby="available-materials-heading">
                <thead>
                    <tr>
                        <th scope="col">Subject Code</th>
                        <th scope="col">Subject Name</th>
                        <th scope="col">Notes</th>
                        <th scope="col">PPT</th>
                        <th scope="col">Assignments</th>
                        <th scope="col">Previous Papers</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td align="center">CEUE203</td>
                        <td>Object Oriented Programming</td>
                        <td><a href="#" aria-label="Download Object Oriented Programming Notes">Download</a></td>
                        <td><a href="#" aria-label="Download Object Oriented Programming PPT">Download</a></td>
                        <td><a href="#" aria-label="Download Object Oriented Programming Assignments">Download</a></td>
                        <td><a href="#" aria-label="Download Object Oriented Programming Previous Papers">Download</a></td>
                    </tr>
                    <tr>
                        <td align="center">MSUD203</td>
                        <td>Discrete Mathematics</td>
                        <td><a href="#" aria-label="Download Discrete Mathematics Notes">Download</a></td>
                        <td><a href="#" aria-label="Download Discrete Mathematics PPT">Download</a></td>
                        <td><a href="#" aria-label="Download Discrete Mathematics Assignments">Download</a></td>
                        <td><a href="#" aria-label="Download Discrete Mathematics Previous Papers">Download</a></td>
                    </tr>
                    <tr>
                        <td align="center">CSUC201</td>
                        <td>Fundamentals of Data Structures and Algorithms</td>
                        <td><a href="#" aria-label="Download Fundamentals of Data Structures and Algorithms Notes">Download</a></td>
                        <td><a href="#" aria-label="Download Fundamentals of Data Structures and Algorithms PPT">Download</a></td>
                        <td><a href="#" aria-label="Download Fundamentals of Data Structures and Algorithms Assignments">Download</a></td>
                        <td><a href="#" aria-label="Download Fundamentals of Data Structures and Algorithms Previous Papers">Download</a></td>
                    </tr>
                    <tr>
                        <td align="center">ITUC201</td>
                        <td>Fundamentals of Computer Networks</td>
                        <td><a href="#" aria-label="Download Fundamentals of Computer Networks Notes">Download</a></td>
                        <td><a href="#" aria-label="Download Fundamentals of Computer Networks PPT">Download</a></td>
                        <td><a href="#" aria-label="Download Fundamentals of Computer Networks Assignments">Download</a></td>
                        <td><a href="#" aria-label="Download Fundamentals of Computer Networks Previous Papers">Download</a></td>
                    </tr>
                    <tr>
                        <td align="center">ITUE203</td>
                        <td>Web Development Frameworks</td>
                        <td><a href="#" aria-label="Download Web Development Frameworks Notes">Download</a></td>
                        <td><a href="#" aria-label="Download Web Development Frameworks PPT">Download</a></td>
                        <td><a href="#" aria-label="Download Web Development Frameworks Assignments">Download</a></td>
                        <td><a href="#" aria-label="Download Web Development Frameworks Previous Papers">Download</a></td>
                    </tr>
                </tbody>
            </table>
        </section>

        <br>

        <!-- Recommended Reference Books Table Section -->
        <section aria-labelledby="reference-books-heading">
            <h2 id="reference-books-heading" align="center">Recommended Reference Books</h2>

            <table border="1" width="100%" cellpadding="10" aria-labelledby="reference-books-heading">
                <thead>
                    <tr>
                        <th scope="col" width="35%">Subject</th>
                        <th scope="col">Reference Book</th>
                        <th scope="col">Author</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Object Oriented Programming</td>
                        <td>Java: The Complete Reference</td>
                        <td>Herbert Schildt</td>
                    </tr>
                    <tr>
                        <td>Discrete Mathematics</td>
                        <td>Discrete Mathematics and Its Applications</td>
                        <td>Kenneth H. Rosen</td>
                    </tr>
                    <tr>
                        <td>Fundamentals of Data Structures and Algorithms</td>
                        <td>Data Structures Using C</td>
                        <td>Reema Thareja</td>
                    </tr>
                    <tr>
                        <td>Fundamentals of Computer Networks</td>
                        <td>Computer Networking</td>
                        <td>Kurose &amp; Ross</td>
                    </tr>
                    <tr>
                        <td>Web Development Frameworks</td>
                        <td>Learning Web Design</td>
                        <td>Jennifer Robbins</td>
                    </tr>
                </tbody>
            </table>
        </section>

        <?php if ($publishedMaterials): ?>
        <section aria-labelledby="published-materials-heading">
            <h2 id="published-materials-heading" align="center">Materials Published by Administration</h2>
            <div class="admin-table-wrap"><table><thead><tr><th>Subject</th><th>Type</th><th>Title</th><th>Resource</th></tr></thead><tbody>
            <?php foreach ($publishedMaterials as $material): ?>
                <tr><td><?= htmlspecialchars($material['subject_code'] . ' - ' . $material['subject_name']) ?></td><td><?= htmlspecialchars($material['material_type']) ?></td><td><?= htmlspecialchars($material['title']) ?></td><td><a href="<?= htmlspecialchars($material['resource_url']) ?>" target="_blank" rel="noopener">Open resource</a></td></tr>
            <?php endforeach; ?>
            </tbody></table></div>
        </section>
        <?php endif; ?>

        <br>

        <!-- Online Learning Resources Section -->
        <section aria-labelledby="learning-resources-heading">
            <h2 id="learning-resources-heading" align="center">Online Learning Resources</h2>
            <div align="center">
                <ul style="display: inline-block; text-align: left;">
                    <li>Lecture Notes</li>
                    <li>Video Tutorials</li>
                    <li>Assignments</li>
                    <li>Previous Year Question Papers</li>
                    <li>Practical Manuals</li>
                    <li>E-Books</li>
                    <li>Quiz Materials</li>
                </ul>
            </div>
        </section>

        <br>

        <!-- Study Tips Section -->
        <aside aria-labelledby="study-tips-heading">
            <fieldset>
                <legend><b id="study-tips-heading">Study Tips</b></legend>
                <ul>
                    <li>Study every subject regularly instead of studying only before exams.</li>
                    <li>Complete assignments before the deadline.</li>
                    <li>Practice programming daily for Object Oriented Programming.</li>
                    <li>Revise Data Structures and Algorithms with coding examples.</li>
                    <li>Prepare short notes for quick revision.</li>
                    <li>Solve previous year question papers.</li>
                    <li>Attend all lectures and practical sessions.</li>
                </ul>
            </fieldset>
        </aside>

        <br>

        <!-- Important Note Section -->
        <aside aria-labelledby="important-note-heading">
            <fieldset>
                <legend><b id="important-note-heading">Important Note</b></legend>
                <p>
                    All study materials are provided only for educational purposes.
                    Students are advised to use the latest notes uploaded by faculty members.
                </p>
            </fieldset>
        </aside>

    </main>

    <br>
    <hr>

    <!-- Footer Section -->
    <footer role="contentinfo">
        <div align="center">
            <h3>Student Hub Portal Â© 2026</h3>
        </div>
    </footer>

<?php renderPortalFooter(); ?>
</body>

</html>