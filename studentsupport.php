<?php require_once __DIR__ . '/page_session.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Support - Student Hub Portal</title>
 <link rel="stylesheet" href="style.css?v=20261029">
 <script src="script.js" defer></script>
</head>

<body>

    <!-- Skip Link for Keyboard Accessibility -->
    <a href="#main-content">Skip to main content</a>

    <!-- Header Section -->
    <?php renderPortalHeader(); ?>

    <!-- Main Content Area -->
    <main id="main-content" class="support-page">
<div style="background-color: #e6f0fa; border: 1px solid #bce8f1; color: #31708f; padding: 12px; border-radius: 6px; margin-bottom: 1.5rem; width: 100%; grid-column: 1 / -1;">
        <strong>Welcome to StudentHub!</strong> Check out your dashboard for the latest notices and academic updates.
    </div>
        <!-- Intermediate Extension: Breadcrumb Navigation -->
        <nav aria-label="Breadcrumb">
            <p align="left">
                <a href="index.php">Home</a> &gt; <b>Student Support</b>
            </p>
        </nav>
        <nav class="page-quick-nav" aria-label="Support page navigation">
        <strong>Quick access</strong>
        <a href="#services-heading">Support services</a>
        <a href="#request-heading">Raise a request</a>
        <a href="#faq-heading">FAQs</a>
        <a href="#contact-details-heading">Contact</a>
        </nav>

        <section aria-labelledby="support-page-title">
            <div align="center">
                <h2 id="support-page-title">Student Support</h2>
                <p>We're here to help you with academic and campus-related issues.</p>
            </div>
            <hr>

            <!-- Support Services Table Section -->
            <h2 id="services-heading" align="center">Support Services</h2>

            <table border="1" width="100%" cellpadding="10" aria-labelledby="services-heading">
                <thead>
                    <tr>
                        <th scope="col">Service</th>
                        <th scope="col">Description</th>
                        <th scope="col">Availability</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Academic Support</strong></td>
                        <td>Help with subjects, attendance and examinations.</td>
                        <td>Monday - Friday</td>
                    </tr>
                    <tr>
                        <td><strong>Technical Support</strong></td>
                        <td>Portal login and technical issues.</td>
                        <td>24 Ã— 7</td>
                    </tr>
                    <tr>
                        <td><strong>Library Support</strong></td>
                        <td>Book issue, renewal and digital library access.</td>
                        <td>Working Hours</td>
                    </tr>
                    <tr>
                        <td><strong>Scholarship Help</strong></td>
                        <td>Guidance regarding scholarship applications.</td>
                        <td>Monday - Saturday</td>
                    </tr>
                    <tr>
                        <td><strong>Career Guidance</strong></td>
                        <td>Placement and career counselling.</td>
                        <td>By Appointment</td>
                    </tr>
                </tbody>
            </table>
        </section>

        <!-- Support Request Form Section -->
        <section aria-labelledby="request-heading">
            <h2 id="request-heading" align="center">Raise a Support Request</h2>

            <form action="#" method="post">
                <table border="1" width="60%" align="center" cellpadding="10">
                    <thead>
                        <tr>
                            <th colspan="2">Support Form</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><label for="student-name">Student Name</label></td>
                            <td><input type="text" id="student-name" name="student_name" size="35" required aria-required="true" autocomplete="name"></td>
                        </tr>
                        <tr>
                            <td><label for="enrollment-no">Enrollment Number</label></td>
                            <td><input type="text" id="enrollment-no" name="enrollment_no" size="35" required aria-required="true"></td>
                        </tr>
                        <tr>
                            <td><label for="student-email">Email</label></td>
                            <td><input type="email" id="student-email" name="student_email" size="35" required aria-required="true" autocomplete="email"></td>
                        </tr>
                        <tr>
                            <td><label for="support-category">Support Category</label></td>
                            <td>
                                <select id="support-category" name="support_category" required aria-required="true">
                                    <option value="">-- Select Category --</option>
                                    <option value="academic">Academic Support</option>
                                    <option value="technical">Technical Support</option>
                                    <option value="library">Library Support</option>
                                    <option value="scholarship">Scholarship</option>
                                    <option value="career">Career Guidance</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="issue-description">Describe Your Issue</label></td>
                            <td>
                                <textarea id="issue-description" name="issue_description" rows="5" cols="35" required aria-required="true"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" align="center">
                                <input type="submit" value="Submit">
                                <input type="reset" value="Clear">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </section>

        <!-- FAQ Table Section -->
        <section aria-labelledby="faq-heading">
            <h2 id="faq-heading" align="center">Frequently Asked Questions</h2>

            <table border="1" width="90%" align="center" cellpadding="10" aria-labelledby="faq-heading">
                <thead>
                    <tr>
                        <th scope="col" width="40%">Question</th>
                        <th scope="col">Answer</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>I forgot my portal password.</strong></td>
                        <td>Contact the administrator or use the password reset option.</td>
                    </tr>
                    <tr>
                        <td><strong>How can I download certificates?</strong></td>
                        <td>Go to the Certificates page after logging in.</td>
                    </tr>
                    <tr>
                        <td><strong>Where can I find study materials?</strong></td>
                        <td>Open the Academics section and select Study Materials.</td>
                    </tr>
                    <tr>
                        <td><strong>How do I contact faculty?</strong></td>
                        <td>Use the Contact Us page or visit the department office.</td>
                    </tr>
                </tbody>
            </table>
        </section>

        <!-- Support Contact Details -->
        <aside aria-labelledby="contact-details-heading">
            <fieldset>
                <legend><b id="contact-details-heading">Support Contact</b></legend>
                <ul>
                    <li>Help Desk: <a href="tel:+919876543210">+91 98765 43210</a></li>
                    <li>Email: <a href="mailto:support@studenthub.edu">support@studenthub.edu</a></li>
                    <li>Working Hours: <time datetime="09:00">9:00 AM</time> - <time datetime="17:00">5:00 PM</time></li>
                    <li>Student Help Desk, Ground Floor</li>
                </ul>
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