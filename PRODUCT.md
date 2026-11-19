# Product

<!-- impeccable:product-schema 1 -->

## Platform

web

## Users

- Residents use the platform to report waste concerns, check collection schedules, follow report status, and participate in verified segregation or recycling rewards.
- Barangay staff and officials manage reports, log collection activity, verify resident reward activity, and review local analytics.
- The project scope also describes city or super administrators overseeing barangay profiles and aggregate performance; implementation status is not established by the current pages.

## Product Purpose

BarangayEcoCare is a community reporting and eco-care platform for localized barangay waste management. It aims to connect residents and local staff around reporting concerns, tracking responses, collection information, and verified environmental participation.

## Positioning

Open decision: the specific distinction from other community reporting portals has not been confirmed. Project materials describe an integrated combination of waste reporting, collection schedules, status tracking, and point-based incentives for verified segregation or recycling.

## Operating Context

The project is a responsive web application used in browser on mobile, tablet, or desktop. Resident reporting is intended for on-the-go use; administrative monitoring is described for barangay staff. Online connectivity is required for reporting, schedule syncing, and point redemption.

## Capabilities and Constraints

Project materials describe these intended capabilities: waste concern reporting (including illegal dumping, missed collection, and overflowing bins), report status updates, zone or purok collection schedules, manual collection logging, verified point awards and barangay-issued rewards, and administrative monitoring. The current interface and database are prototype-level evidence; sample figures, schedules, and incentives in the interface are explicitly labeled as sample data.

The documented scope excludes native mobile apps, truck-mounted GPS or IoT tracking, automated AI waste recognition, and direct financial transactions. Pickup status is based on staff logs. The actual barangay or deployment location and any additional policy or accessibility requirements remain undecided.

## Brand Commitments

The project uses the name BarangayEcoCare. The landing page uses the tagline “Clean Barangay, Green Future.” No other binding brand requirements were confirmed.

## Evidence on Hand

- `scoop.md` documents intended scope, roles, functions, and limitations.
- `index.php` contains the public-facing description and sample schedules, rewards, and leaderboard content; these examples are labeled as samples and must not be presented as verified operational data.
- `src/` contains PHP login, signup, resident dashboard, and official dashboard pages. `database/ecocare.sql` contains a users table and sample accounts. These establish implementation evidence for account roles, not proof that every capability in `scoop.md` is implemented.
- No customer testimonials, measured outcomes, or independently verified performance evidence were identified.

## Product Principles

- Connect resident participation with local staff follow-through.
- Make waste concerns and their handling trackable.
- Tie incentives to activity verified by authorized staff.
- Treat sample content as illustrative until operational data is available.

