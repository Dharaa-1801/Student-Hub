# StudentHub Portal

> A complete PHP/MySQL student portal for student services, administrator
> monitoring, role-based access control, and audit traceability.

## What this project demonstrates

StudentHub combines the major requirements of the final practical in one
deployable application:

- Student registration and secure login
- Student and administrator roles
- Server-side protection for administrator pages
- Dynamic student, event, registration, and activity statistics
- Event creation, editing, deletion, and poster uploads
- Attendance and timetable management
- Study material and certificate management
- Student CRUD, search, status management, and CSV export
- Audit logging for important administrator actions
- Responsive purple portal UI with searchable navigation and mobile menu
- Deployment-ready SQL dump and security documentation

## Languages and technologies

| Technology | Usage in StudentHub |
|---|---|
| **HTML5** | Page structure, forms, tables, semantic landmarks, labels, and accessibility attributes |
| **CSS3** | Purple responsive portal layout, dashboard cards, tables, mobile navigation, dark mode, and visual states |
| **JavaScript** | Menu toggles, page search, theme switching, modal/slider interactions, client-side validation, and event API actions |
| **PHP** | Authentication, sessions, RBAC, page rendering, form processing, uploads, dashboard queries, and server-side validation |
| **MySQL** | Users, roles, events, registrations, attendance, timetable, materials, certificates, remember tokens, and audit logs |
| **PDO** | Prepared database queries and safe MySQL access |
| **Apache/XAMPP** | Local web-server and PHP runtime for testing and demonstration |
| **Bootstrap-style UI patterns** | Responsive cards, buttons, forms, tables, alerts, and mobile-friendly layouts |

The portal is built with PHP-rendered HTML templates. Therefore, HTML is used
throughout the `.php` pages even though the files have a `.php` extension.

### GitHub language display

GitHub calculates its language bar from source-code bytes, not from the
technology list above. HTML inside PHP templates may be classified partly as
PHP, so the GitHub percentages can differ from the actual feature usage. The
important implementation stack for this project is:

```text
PHP + HTML5 + CSS3 + JavaScript + MySQL
```

## Screenshots and testing evidence

Screenshots are kept separately in [`docs/screenshots`](./screenshots) so the
README remains readable and the evidence is easy to submit. Capture the
screenshots after starting Apache and MySQL, then save them with these names:

| File | What it proves | Test procedure |
|---|---|---|
| `01-home.png` | Portal home and navigation | Open the portal while logged out |
| `02-student-login.png` | Student authentication | Log in with an active student account |
| `03-student-dashboard.png` | Student workspace | Verify dashboard cards and profile summary |
| `04-admin-dashboard.png` | Dynamic admin statistics | Log in as admin and verify users, students, events, and registrations |
| `05-admin-management.png` | Administrative controls | Open Control Center and show management sections |
| `06-rbac-forbidden.png` | RBAC enforcement | As a student, open `admin_dashboard.php` and capture the HTTP 403 page |
| `07-audit-log.png` | Audit traceability | Perform an admin action and show the new audit-log row |
| `08-mobile-navigation.png` | Responsive accessibility | Resize to mobile width and open the hamburger menu |

Example Markdown for adding a captured image:

```markdown
![Admin dashboard showing live statistics](./screenshots/04-admin-dashboard.png)
```

Do not use screenshots containing real passwords, production credentials,
private student information, or browser session tokens. The complete evidence
checklist is in [`TEST_SECURITY_REPORT.md`](./TEST_SECURITY_REPORT.md).

## Requirements

- PHP 8.1 or later with `PDO_MySQL`, `fileinfo`, and `GD`/image support
- MySQL 8 or MariaDB 10.4 or later
- Apache (XAMPP works for local demonstration)
- A browser with JavaScript enabled

## Local setup with XAMPP

1. Copy the project folder into `C:\xampp\htdocs\student login`.
2. Start Apache and MySQL in XAMPP.
3. Open phpMyAdmin and import [`studenthub.sql`](../database/studenthub.sql).
4. Confirm the local database settings in [`db.php`](../db.php), or set the
   `STUDENTHUB_DB_*` environment variables described in
   [`.env.example`](../config/.env.example).
5. Confirm that `uploads/profiles` and `uploads/event-posters` are writable by
   Apache.
6. Open `http://localhost/student%20login/`.

The SQL file contains demo accounts and sample events. Change demo passwords
and remove demo data before any public deployment.

### Demo accounts

The imported SQL includes demonstration accounts so the practical can be
tested immediately. Use the credentials from the imported database only for
local testing. Change or remove these accounts before deployment.

The administrator account must have `role = 'admin'`; normal accounts must
have `role = 'student'`. The application checks this role on the server for
every protected route.

## Deployment

1. Create a dedicated MySQL user with access only to `studenthub_db`.
2. Set `STUDENTHUB_DB_HOST`, `STUDENTHUB_DB_PORT`, `STUDENTHUB_DB_NAME`,
   `STUDENTHUB_DB_USER`, and `STUDENTHUB_DB_PASSWORD` in the hosting
   environment. Do not commit a real `.env` file.
3. Import [`studenthub.sql`](../database/studenthub.sql) and verify the schema.
4. Deploy behind HTTPS. The application automatically marks session cookies
   secure when HTTPS is detected.
5. Keep directory listing disabled and preserve the upload protection in
   [`uploads/.htaccess`](./uploads/.htaccess).
6. Change the seeded administrator password before launch.
7. Verify login, student access, administrator access, event management,
   uploads, audit logs, and logout on the deployed host.

## Main routes

- `index.php` - portal home
- `dashboard.php` - role-aware dashboard redirect
- `student_dashboard.php` - student workspace
- `admin_dashboard.php` - administrator statistics and audit overview
- `admin_management.php` - attendance, timetable, events, materials, and certificates
- `student_management.php` - administrator student CRUD and export
- `api.php` - authenticated event API

## Important folders

| Folder | Purpose |
|---|---|
| [`config/`](../config) | Environment configuration template |
| [`database/`](../database) | Schema, deployment dump, and legacy SQL |
| [`docs/`](.) | Setup, structure, and final test documentation |
| [`uploads/`](../uploads) | Profile and event poster files |

Runtime PHP pages intentionally remain in the project root so existing local
URLs and internal links continue to work.

## Testing walkthrough

1. Import the SQL dump and start Apache/MySQL.
2. Open the home page and test the public navigation.
3. Register a new student and verify the account is created.
4. Log in as a student and open the student dashboard.
5. Try opening `admin_dashboard.php` as a student; confirm HTTP 403.
6. Log in as an administrator and confirm the statistics load from MySQL.
7. Create or update an event, then check the audit-log table.
8. Test student search, status update, CSV export, and logout.
9. Test mobile navigation, keyboard focus, and long-page scrolling.
10. Capture the evidence listed in the screenshot table above.

## Troubleshooting

- **Database service unavailable:** start MySQL and verify the values in
  [`db.php`](../db.php) or the `STUDENTHUB_DB_*` environment variables.
- **Uploads fail:** grant Apache write permission to
  `uploads/profiles` and `uploads/event-posters`.
- **Old CSS appears:** refresh with `Ctrl+F5`; pages use a versioned stylesheet.
- **Admin page redirects or returns 403:** verify the account is active and its
  database role is `admin`.
- **Audit rows are missing:** confirm that the `audit_logs` table exists and
  that the current database user can insert rows.

## Test checklist

See [`TEST_SECURITY_REPORT.md`](./TEST_SECURITY_REPORT.md) for the final
testing, security, and accessibility checklist used for Practical 15.
