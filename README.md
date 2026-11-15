# Blood Bank & Donation Management System (BDMS)

[![Agile Scrum Methodology](https://img.shields.io/badge/Methodology-Agile%20Scrum-brightgreen.svg)](#agile-scrum-methodology)
[![PHP](https://img.shields.io/badge/Language-PHP%208.x-blue.svg)](https://www.php.net)
[![MySQL](https://img.shields.io/badge/Database-MySQL-orange.svg)](https://www.mysql.com)
[![Bootstrap](https://img.shields.io/badge/Frontend-Bootstrap%204%20%7C%20CSS3-purple.svg)](https://getbootstrap.com)

A comprehensive web application engineered to bridge the critical gap between voluntary blood donors, patients, and hospital blood banks. Built iteratively adhering to **Agile Scrum Methodology** across 5 distinct sprints.

---

## Agile Scrum Methodology

The project was executed following iterative Agile sprints, emphasizing continuous integration, modular design, and test-driven refinements.

### Sprint Breakdown & Product Backlog

| Sprint | Focus Area | Key User Stories & Deliverables | Status |
|---|---|---|---|
| **Sprint 1** | Architecture & Database Setup | Database schema design, mysqli connection wrapper, security helpers, project scaffolding. | Done |
| **Sprint 2** | UI Framework & Informational Pages | Responsive navbar & footer, custom theme styling, About Us, Why Donate Blood guide, Contact module. | Done |
| **Sprint 3** | Donor & Request Workflows | Donor registration form, MySQL persistence layer, Blood Request search by blood group & city, hero banner. | Done |
| **Sprint 4** | Admin Portal & Security | Role authentication, session management, dashboard metrics, donor management, query resolver, WYSIWYG CMS. | Done |
| **Sprint 5** | DevOps, Testing & Server Automation | Automated DB import, local PHP runner, home server daily push automation, responsive UI polish. | Done |

---

## System Architecture & Features

### 1. Public Portal (Donors & Patients)
- **Donor Registration:** Multi-field validated form capturing donor personal info, blood group, age, and location.
- **Need Blood Search:** Real-time search engine matching patients with compatible donors by blood group.
- **Awareness & Education:** Interactive guides covering donor eligibility, blood facts, and donation benefits.
- **Contact Inquiries:** Direct contact form persisting queries for administrative follow-up.

### 2. Admin Management Dashboard
- **Authentication & Sessions:** Protected administration area with secure credentials and session timeout.
- **Live Metrics Dashboard:** Visual cards displaying donor count, query count, and blood inventory statistics.
- **Donor Registry Control:** View, add, edit, and delete registered donor entries.
- **Inquiry Management:** Review pending contact inquiries, mark resolved, or clear old records.
- **Content Management System (CMS):** In-place rich-text editor for dynamic page content.

---

## Getting Started

### Prerequisites
- PHP 7.4+ or PHP 8.x
- MySQL Server (or XAMPP / WAMP)
- Git

### 1. Database Setup
Ensure MySQL is running, then run the automated import batch script:
```bash
import_database.bat
```
*Or manually via MySQL CLI:*
```bash
mysql -u root -p blood_bank_database < sql/blood_bank_database.sql
```

### 2. Configure Database Connection
If your MySQL root user has a password, edit line 2 in `conn.php` and `admin/conn.php`:
```php
$conn = mysqli_connect("localhost", "root", "YOUR_PASSWORD", "blood_donation");
```

### 3. Launch the Application
Start the built-in development server:
```bash
start_server.bat
```
*Or via command prompt:*
```bash
php -S localhost:8000
```

Access the application in your browser:
- **User Portal:** [http://localhost:8000/](http://localhost:8000/)
- **Admin Panel:** [http://localhost:8000/admin/](http://localhost:8000/admin/)

---

## Automated Home Server Daily Integration (Cron Job)
This repository includes automated maintenance scripts designed to run on a home server (Linux/Ubuntu, Raspberry Pi, or Windows):
- `cron_daily_pusher.py`: Automated daily runner performing health checks, log updates, and `git push`.
- `cron_daily_pusher.sh`: Cron wrapper for Linux/Raspberry Pi.
- `cron_daily_pusher.bat`: Task Scheduler wrapper for Windows Server.

---

## Author
**Vaibhav Suthar**  
- GitHub: [@vaibhavs2711](https://github.com/vaibhavs2711)  
- Email: vaibhavsuthar2711@gmail.com  
- Academic Context: Semester 7 - Agile & Scrum Methodology Laboratory
