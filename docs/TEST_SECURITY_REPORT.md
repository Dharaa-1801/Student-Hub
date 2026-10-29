# StudentHub Final Test, Security, and Accessibility Report

## Scope

This report covers the final StudentHub integration: authentication, RBAC,
database operations, event management, uploads, navigation, audit logging,
responsive UI, and deployment readiness.

## Automated checks

| Check | Result |
|---|---|
| PHP syntax validation for all project PHP files | Pass |
| JavaScript syntax validation for `script.js` | Pass |
| SQL uses prepared statements for user-controlled writes | Pass |
| Worktree diff whitespace check | Pass except for an existing SQL trailing-space warning |
| Database runtime check | Run with MySQL started; unavailable when MySQL is stopped |

Suggested commands:

```powershell
Get-ChildItem -Path .. -File -Filter '*.php' | ForEach-Object { php -l $_.FullName }
node --check script.js
```

## Functional test cases

| ID | Test | Expected result |
|---|---|---|
| F-01 | Register with valid details | Student record is created with a password hash and student role |
| F-02 | Register with duplicate email | Registration is rejected |
| F-03 | Login with valid student credentials | Student dashboard opens |
| F-04 | Login with valid administrator credentials | Admin dashboard opens |
| F-05 | Open admin page as a student | Styled HTTP 403 response is returned |
| F-06 | Open protected page while logged out | User is redirected to login |
| F-07 | Use remember-me login | Session can be restored using a hashed token |
| F-08 | Create, update, and delete an event as admin | Database changes and audit entries are created |
| F-09 | Submit attendance, timetable, material, and certificate actions | Data is saved and action is audited |
| F-10 | Export student records | CSV download contains the filtered student records |
| F-11 | Log out | Session and remember token are cleared |
| F-12 | Open a long page on mobile | Navigation collapses and content remains usable |

## Security checklist

- Passwords use `password_hash()` and `password_verify()`.
- SQL writes use PDO prepared statements.
- PDO emulated prepares are disabled.
- Session IDs are regenerated after login.
- Session cookies use `HttpOnly`, `SameSite=Lax`, and HTTPS-aware `Secure`.
- Inactive users are rejected on every authenticated request.
- Admin routes use server-side role checks; hidden menu links are not the only control.
- Uploads are MIME-checked, size-limited, randomly named, and restricted from PHP execution.
- Database credentials can be supplied through environment variables.
- Generic authentication errors avoid revealing whether an email exists.
- Response headers include `nosniff`, `SAMEORIGIN`, and a strict referrer policy.
- Audit logs capture administrator actions, entity information, IP address, and time.

Before public deployment, also configure HTTPS, a dedicated least-privilege
database account, regular database backups, rate limiting at the web server,
and a real CSRF token for every state-changing HTML form.

## Accessibility checklist

- Skip-to-content links are available.
- Navigation has labels and active-page state.
- Mobile menu exposes `aria-expanded` and `aria-controls`.
- Tables use header rows and scoped headers where provided.
- Forms use associated labels and browser validation.
- Keyboard focus styles are preserved.
- Color is not the only indicator for status.
- Layout remains usable on narrow screens with responsive tables and forms.

## Demo evidence to submit

Capture screenshots of:

1. Student dashboard after login.
2. Admin statistics and latest registrations.
3. Admin menu and control center.
4. Student attempting an admin page and receiving HTTP 403.
5. Audit log entries after an administrator action.
6. Mobile navigation and a responsive page.
