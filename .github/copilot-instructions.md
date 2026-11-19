# Copilot instructions for BarangayEcoCare

## Project overview

BarangayEcoCare is a PHP 8 + MySQL/MariaDB responsive web application. It is designed to run under XAMPP in `C:\xampp\htdocs\CapstoneProject`; pages use relative paths and assume the repository is available at `/CapstoneProject/`.

The implementation is a mixed server-rendered application: PHP handles session/authentication and MySQL access, while CSS and small inline JavaScript/AOS initializers provide the UI. There is no framework, package manifest, build step, or API layer.

The product scope in `PRODUCT.md` and `scoop.md` describes intended waste-reporting, collection, reward, and leaderboard features. Most dashboard values and lists are currently prototype/sample data; do not present or implement them as live operational data unless they are backed by database queries.

## Setup and commands

### Local runtime

Run Apache and MySQL through XAMPP, then open:

- `http://localhost/CapstoneProject/index.php`
- `http://localhost/CapstoneProject/src/login.php`
- `http://localhost/CapstoneProject/src/signup.php`

Import `database/ecocare.sql` into a MariaDB/MySQL database named `ecocare`. The dump is the schema/source of seed accounts for the current prototype.

Database connection assumptions are in `config/db.php`: host `localhost`, user `root`, empty password, and database `ecocare`. OAuth configuration is loaded from environment variables or a repository-root `.env` file by `config/env.php` and exposed through `config/oauth.php`.

Expected OAuth variables:

- `GOOGLE_CLIENT_ID`
- `GOOGLE_CLIENT_SECRET`
- `GOOGLE_REDIRECT_URI` (defaults to the local Google callback)
- `FACEBOOK_APP_ID`
- `FACEBOOK_APP_SECRET`
- `FACEBOOK_REDIRECT_URI` (defaults to the local Facebook callback)

`.env` is ignored by Git. Do not add credentials to PHP source or SQL dumps.

### Validation

There is no Composer, npm, PHPUnit, lint, or test script in the repository. Use the XAMPP PHP binary for syntax validation:

```powershell
C:\xampp\php\php.exe -l C:\xampp\htdocs\CapstoneProject\index.php
C:\xampp\php\php.exe -l C:\xampp\htdocs\CapstoneProject\src\login.php
```

Lint one changed PHP file by replacing the path above. To lint all PHP files under the repository:

```powershell
Get-ChildItem -Recurse -Filter *.php | ForEach-Object {
    C:\xampp\php\php.exe -l $_.FullName
    if ($LASTEXITCODE -ne 0) { exit $LASTEXITCODE }
}
```

There are currently no automated test suites or a single-test command. Validate behavioral changes in-browser under Apache with the relevant login role and database state.

## Architecture

- `index.php` is the public landing page and only currently rendered top-level entry point.
- `src/` contains authentication pages and role-specific dashboards:
  - `login.php` and `signup.php`
  - `resident-dashboard.php`
  - `official-dashboard.php`
  - `collector-dashboard.php`
  - `admin-dashboard.php`
  - `approve_user.php`
  - `logout.php`
- `config/db.php` opens the shared MySQL connection. Include it in pages that access `users` or other database tables.
- `config/env.php` loads `.env`; `config/oauth.php` defines OAuth constants from that environment.
- `connection/` starts and completes Google/Facebook OAuth flows. OAuth callbacks match or create users and set the same session values used by password login.
- `style/` contains one or more shared/page-specific stylesheets. `global.css` defines shared design tokens and controls; dashboard pages use `dashboard.css`, with role-specific overrides where present.
- `aos-init.js` initializes AOS and refreshes animation state when dashboard URL hashes change.
- `database/ecocare.sql` is the only checked-in database schema/seed snapshot. Its `users` table is currently the only table defined in the dump.

## Request, authentication, and routing conventions

- Each PHP page must start or continue a session itself before reading `$_SESSION`.
- Dashboard pages are primarily single documents divided into sections with `id="v1"` through `id="vN"`. Navigation uses hash links, and `dashboard.css` switches sections using `:target` and `:has()`.
- New dashboard navigation must use a matching `#vN` section and add its active-link selector if it should participate in the CSS-only active state.
- Login and OAuth callbacks route to `admin-dashboard.php`, `official-dashboard.php`, `collector-dashboard.php`, or `resident-dashboard.php` according to the lowercase database role.
- Account access uses two fields: `role` selects the destination, while `status` must be `active` for login. Signup creates `Resident` with `pending` status; the admin approval flow changes it to `active` or `inactive`.
- Only `approve_user.php` and the admin dashboard should perform account approval mutations. Keep admin accounts protected from modification.
- Use prepared MySQLi statements for database values. Passwords use `password_hash()` and `password_verify()`.
- Escape values rendered into HTML with `htmlspecialchars()`, and use `mysqli_*` consistently with the existing codebase rather than introducing a second database abstraction.
- OAuth callbacks validate session state before exchanging authorization codes. Preserve that state check and remove the state after successful validation.

## Front-end conventions

- Preserve the shared dark visual language through CSS variables in `style/global.css`: `--bg`, `--card`, `--blue`, `--orange`, `--text`, `--muted`, `--line`, and `--gap`.
- Use existing classes such as `.shell`, `.sidebar`, `.nav`, `.main`, `.view`, `.card`, `.btn`, `.field`, `.badge`, and `.grid` before creating page-specific equivalents.
- Shared layout and component styles belong in `global.css`; dashboard-shell behavior belongs in `dashboard.css`; role/page-specific overrides belong in their existing role stylesheet or the page when it is already using a small local style block.
- Responsive behavior is CSS-driven. Test any layout or dashboard change at mobile, tablet, and desktop widths.
- AOS is a CDN dependency, and `aos-init.js` marks the document ready only after AOS is available. Keep fallback CSS for `[data-aos]` usable if the CDN is unavailable.
- The main brand text is `BarangayEcoCare` with the `<b>` portion styled as the accent name. The tagline is `Clean Barangay · Green Future`.

## Database change conventions

- Update `database/ecocare.sql` when adding or changing schema used by implemented PHP pages.
- Preserve the existing role names and status values when the code consumes them: role values include `Resident`, `Official`, `Collector`, and `Admin`; status values are compared case-insensitively and commonly use `pending`, `active`, and `inactive`.
- Reconcile database column assumptions before adding UI fields. The SQL dump's `users` table does not include every column referenced by later dashboard PHP, for example `purok`.
