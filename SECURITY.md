<!--TODO: Review this for production-->

# Security Policy

FoodFusion is a content + social platform for recipes, video shows, chefs, and community. See platform overview in SYSTEM_DESIGN.md:

- Security and privacy notes: [SYSTEM_DESIGN.md](SYSTEM_DESIGN.md)

## Supported Versions

- main branch: actively supported (security fixes).
- Tagged releases (if any): latest minor only.

Report issues even if you are unsure about version impact.

## Reporting a Vulnerability

- Preferred: Open a private advisory via “Security” → “Report a vulnerability” on the repository.
- Alternative: Email <security@foodfusion.local> with details and a proof-of-concept if possible.
- Do not open public issues for vulnerabilities or share details publicly until coordinated disclosure is completed.

Please include:

- Affected URL(s), component(s), and reproduction steps.
- Impact assessment (data exposure, privilege escalation, RCE, etc.).
- Environment (OS, browser, PHP version).
- Logs or screenshots (redact secrets).

We will:

- Acknowledge within 5 business days.
- Provide a remediation plan or status within 10 business days.
- Coordinate a disclosure timeline and assign CVE (if applicable).

## Scope

In scope:

- Application code and configuration in this repository.
- Server-rendered pages and API endpoints.
- Authentication and session handling: [config/auth.php](config/auth.php), [app/Models/User.php](app/Models/User.php).

Out of scope (unless impact is demonstrated on production-like setups):

- Development artifacts and default dev settings.
- Third-party services independently hosted and configured (e.g., CDN, email providers) unless misused by our app.
- Clickjacking on non-production, rate-limit bypass requiring user cooperation, or issues requiring rooted/local compromise.

## Security Requirements (Policy)

- Authentication
  - Unique credentials per user, password hashing (bcrypt/argon2) is enforced by Laravel; see [app/Models/User.php](app/Models/User.php).
  - Login throttling/lockout policy: after 3 failed login attempts, lockout for 3 minutes. This must be enforced via middleware/rate limiting.
- Sessions & Cookies
  - HTTPS-only in production; secure cookies; consider SameSite=strict and HttpOnly.
  - Use server-side session storage; rotation on privilege changes.
- Secrets & Configuration
  - Never commit secrets. Use environment variables (.env). Ensure `APP_KEY` is set (not empty).
  - Disable debug in production (`APP_DEBUG=false`), set `APP_ENV=production`.
- Data Protection
  - PII minimized and encrypted at rest where applicable.
  - Passwords never logged or stored in plaintext.
- Input Validation & Output Encoding
  - Validate and sanitize all user input; use framework validation and Blade auto-escaping.
- Dependency Security
  - Composer/NPM dependencies updated regularly.
  - Run `composer audit` and `npm audit` in CI; remediate high/critical issues quickly.
- Transport Security
  - Enforce HTTPS and HSTS at the edge where possible.
- Logging & Monitoring
  - Security-related events are logged; avoid logging secrets. Application logs: storage/logs/.
- Content Security
  - Prefer CSP and strict referrer policy; avoid inline scripts when possible.
- Backups & Recovery
  - Regular secure backups with restricted access; test restore procedures.

## Developer Guidance

- Configuration
  - Authentication defaults and guards: [config/auth.php](config/auth.php)
  - Bootstrap and routing entrypoints: [bootstrap/app.php](bootstrap/app.php), [public/index.php](public/index.php)
- Environment
  - Production: `APP_ENV=production`, `APP_DEBUG=false`, HTTPS terminated at the load balancer or server.
- Reviews
  - Security review required for features handling authentication, file uploads, or direct database access.

## Coordinated Disclosure

We appreciate responsible disclosure. We ask researchers to:

- Provide sufficient detail to reproduce the issue.
- Allow a reasonable time for remediation before public disclosure.
- Avoid privacy violations, data destruction, or service disruption during testing.

Thank you for helping keep FoodFusion users
