# StudentHub project structure

The project keeps public PHP URLs in the root so existing links and XAMPP
bookmarks continue to work. Supporting files are grouped by purpose:

```text
student login/
├── config/
│   └── .env.example             Deployment configuration template
├── database/
│   ├── schema.sql                Main schema and demo data
│   ├── studenthub.sql            Importable deployment dump
│   └── user_registration.sql     Legacy registration schema
├── docs/
│   ├── README.md                Setup and deployment guide
│   ├── PROJECT_STRUCTURE.md     This file
│   └── TEST_SECURITY_REPORT.md  Final practical evidence checklist
├── uploads/
│   ├── event-posters/
│   ├── profiles/
│   └── .htaccess                 Upload execution protection
├── admin_*.php                   Administrator workspace and tools
├── *_dashboard.php               Student/admin dashboards
├── auth.php, page_session.php    Authentication and shared layout
├── db.php                        PDO database bootstrap
├── audit.php                     Audit-log helper
├── style.css, script.js          Shared UI and client behavior
└── api.php                       Authenticated event API
```

Keep credentials outside the repository. Copy
[`config/.env.example`](../config/.env.example) into the hosting environment
configuration and set production values there.

