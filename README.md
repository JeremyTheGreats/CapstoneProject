# BarangayEcoCare: Integrated Waste, Rewards & Reporting Portal

A web-based community solid waste management system designed for barangay residents, waste collectors, barangay officials, and administrators.

## Features
- **Multi-Role Access Control:** Dedicated dashboards and workflows for Residents, Collectors, Barangay Officials, and Admins.
- **Authentication & Account Approval:** Local email/password registration and login, Google & Facebook OAuth 2.0 integration, and admin account approval workflow.
- **Waste Incident Reporting:** Residents can submit waste reports with category, location details, descriptions, and photo evidence.
- **Collection Schedules:** Scheduled waste pickups organized by area/purok, waste type, and assigned collector.
- **Rewards & Leaderboard:** Point transactions earned from verified reports and eco-activities, redeemable for community rewards.

## Tech Stack
- **Frontend:** HTML5, CSS3, JavaScript
- **Backend:** PHP
- **Database:** MySQL / MariaDB
- **Local Server:** XAMPP (Apache & MariaDB)
- **Authentication:** Google OAuth 2.0, Facebook Login

## Project Structure
```text
CapstoneProject/
├── config/          # Database, environment loader, and OAuth configuration
├── connection/      # OAuth login and callback handlers
├── database/        # SQL schema and database dump (ecocare.sql)
├── images/          # Static assets and uploaded media
├── src/             # Role-based dashboards, login, signup, and approval pages
├── style/           # Stylesheets for dashboards and authentication pages
├── .env.example     # Sample environment variables template
└── .gitignore       # Ignored local secrets and system files
