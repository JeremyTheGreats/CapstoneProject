# Scope and Limitations: BarangayEcoCare

`BarangayEcoCare: Integrated Waste, Rewards & Reporting Portal` is a responsive web-based community waste management, engagement, and administrative monitoring platform designed for localized barangay operations.

---

## 1. System Scope (In-Scope)

### 1.1 Responsive Platform & User Roles
* **Responsive Architecture:** Accessible via standard web browsers (Google Chrome, Safari, Mozilla Firefox, Microsoft Edge) across **Mobile Devices, Tablets, and Desktop Computers**, featuring adaptive UI layouts tailored for on-the-go resident reporting and desktop administrative monitoring.
* **Resident User:** Can report waste concerns, view zone collection schedules, earn and redeem reward points, and check the community leaderboard on mobile or desktop browsers.
* **Barangay Staff / Official:** Can update report resolution statuses, log garbage pickup completions per zone/purok, verify resident reward redemptions, and view administrative analytics.
* **City / Super Admin:** Can manage barangay profiles, oversee platform-wide reports, adjust global reward parameters, and view aggregated performance leaderboards.

### 1.2 Functional Modules

| Module | Features & Technical Scope |
| :--- | :--- |
| **Waste Concern Reporting** | • Submission of waste issues (illegal dumping, missed collection, overflowing bins).<br>• Geotagging integration using interactive maps and browser-based device GPS coordinates (Latitude/Longitude).<br>• Camera/Photo evidence upload with client-side image compression.<br>• Real-time status updates (*Pending*, *In Progress*, *Resolved*). |
| **Collection Scheduling & Tracking** | • Zone and purok-level garbage pickup schedule viewer for residents.<br>• Log-in feature for collection staff to mark collection status (*Scheduled*, *In Progress*, *Completed*).<br>• Status indicator showing the latest pickup date per purok. |
| **Gamified Rewards & Incentives** | • Digital point ledger for residents participating in verified recycling drives or proper segregation programs.<br>• On-site point scanning/issuance via web camera QR scanner by authorized barangay staff.<br>• Redeemable catalog for barangay-issued perks, discounts, or physical vouchers.<br>• Unique QR-based redemption voucher generation. |
| **Barangay Cleanliness Leaderboard** | • Automated calculation of compliance and efficiency rankings based on normalized metrics:<br>&nbsp;&nbsp;– Report Resolution Rate (%)<br>&nbsp;&nbsp;– Segregation & Recycling Participation Rate (%)<br>• Visual ranking table displaying top-performing zones and barangays. |
| **Admin Dashboard & Analytics** | • Centralized overview displaying total active reports, resolution speed, active user counts, and reward distribution.<br>• Report verification and dispatch management tools.<br>• CRUD operations for schedules, rewards catalog, and user accounts. |
| **System Usability Evaluation (UAT)** | • Integration of evaluation metrics using the System Usability Scale (SUS) to assess platform usability, responsiveness, and effectiveness among barangay officials and resident-participants. |

---

## 2. Limitations (Out-of-Scope)

* **No Native Mobile Apps:** The system is strictly a responsive Web Application (Web App) accessed through web browsers. It is not distributed as native Android (.apk) or iOS (.ipa) applications via Google Play Store or Apple App Store.
* **No Physical IoT Hardware Fleet Tracking:** The system does not include physical GPS hardware trackers or IoT sensors mounted on garbage trucks. Pickup status relies on staff manual logs via the web portal.
* **No AI Automatic Waste Segregation Recognition:** Photo uploads for segregation proof rely on staff verification (e.g., during drop-off/recycling drives or spot checks) rather than computer-vision/AI automated classification.
* **No Direct Financial Transactions:** The rewards engine does not integrate with banking APIs, credit cards, or e-wallets (e.g., GCash, PayMaya). Points are exclusively redeemable for barangay-issued perks, paper vouchers, or municipal discount stubs.
* **Network Requirement:** Reporting, schedule syncing, and point redemptions require an active internet connection (cellular data or Wi-Fi) to communicate with the backend server database.