# <span id="unity-care-clinic-v3">🏥 Unity Care Clinic V3</span>

> **Advanced Medical Appointment & Prescription Management System**  
> Built with PHP 8 OOP, Repository Pattern, PDO, and Role-Based Access Control (RBAC)

[![PHP Version](https://img.shields.io/badge/PHP-8.0%2B-blue)](https://www.php.net/)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)
[![Status](https://img.shields.io/badge/Status-In%20Development-yellow)](https://github.com)

---

## 📋 Table of Contents

-   [Overview](#overview)
-   [Features](#features)
-   [Tech Stack](#tech-stack)
-   [Installation](#installation)
-   [Database Schema](#database-schema)
-   [Project Structure](#project-structure)
-   [Test Accounts](#test-accounts)
-   [User Stories](#user-stories)
-   [Security Features](#security-features)
-   [Architecture](#architecture)
-   [Documentation](#documentation)
-   [Development Timeline](#development-timeline)
-   [Contributors](#contributors)

---

<section id="overview">

## 🎯 Overview

Unity Care Clinic V3 is a comprehensive medical management system that handles the complete patient journey: from appointment booking to medical consultations and prescription management. The system implements a robust authentication system with three user roles (Admin, Doctor, Patient), each with specific permissions and interfaces.

### Key Objectives

-   ✅ Implement web authentication with PHP `$_SESSION`
-   ✅ Role-Based Access Control (RBAC) system
-   ✅ Medical appointment management
-   ✅ Prescription and medication tracking
-   ✅ XSS and CSRF protection
-   ✅ Enhanced dashboard statistics
-   ✅ Repository Pattern architecture
-   ✅ Consolidated OOP with inheritance

---

</section>

<section id="features">

## ✨ Features

### 🔐 Authentication & Authorization

-   Secure login with email and password
-   Password hashing using `password_hash()` and `password_verify()`
-   Session-based authentication
-   Role-based access control (Admin, Doctor, Patient)
-   Automatic redirection for unauthorized access

### 📅 Appointment Management

-   Patient appointment booking
-   Doctor appointment scheduling
-   Appointment cancellation (Patient/Doctor)
-   Status tracking (scheduled, done, cancelled)
-   **Bonus:** Smart time slot suggestions via AJAX

### 💊 Prescription System

-   Doctor prescription creation
-   Patient prescription history
-   Medication catalog management
-   Dosage instructions tracking

### 👨‍💼 Admin Dashboard

-   User management (CRUD)
-   Department management (CRUD)
-   Medication catalog management (CRUD)
-   Comprehensive statistics:
    -   Appointments by status
    -   Appointments by doctor
    -   Monthly trends
    -   Most prescribed medications

### 🛡️ Security Features

-   **XSS Protection:** `htmlspecialchars()` on all outputs
-   **CSRF Protection:** Tokens on all forms
-   **SQL Injection Prevention:** PDO prepared statements
-   **Password Security:** Bcrypt hashing
-   **Session Security:** Proper session management
-   **Environment Variables:** Sensitive data in `.env`

---

</section>

<section id="tech-stack">

## 🛠️ Tech Stack

| Category               | Technology                             |
| ---------------------- | -------------------------------------- |
| **Language**           | PHP 8.0+                               |
| **Database**           | MySQL 8.0+                             |
| **Frontend**           | HTML5, TailwindCSS, Vanilla JavaScript |
| **Architecture**       | OOP + Repository Pattern               |
| **Security**           | PDO, CSRF Tokens, Password Hashing     |
| **Version Control**    | Git & GitHub                           |
| **Project Management** | Jira                                   |
| **Package Manager**    | Composer                               |

---

</section>

<section id="installation">

## 📦 Installation

### Prerequisites

-   PHP 8.0 or higher
-   MySQL 8.0 or higher
-   Composer
-   Node.js & npm (for TailwindCSS)

### Step 1: Clone the Repository

```bash
git clone https://github.com/sami-regragui-work/unity-care-clinic-v3.git
cd unity-care-clinic-v3
```

### Step 2: Install Dependencies

```bash
# Install Composer dependencies
composer install

# Install npm dependencies (TailwindCSS)
npm install
```

### Step 3: Database Setup

```bash
# Create database and run schema
mysql -u root -p

# In MySQL console:
source assets/sql/ddl.sql
source assets/sql/dml.sql
```

### Step 4: Configure Environment

```bash
# Copy environment template
cp .env.example .env

# Edit with your credentials
nano .env
```

**.env**

```env
# Database Configuration
DB_HOST=localhost
DB_NAME=UCCV3
DB_USER=root
DB_PASSWORD=your_password
DB_CHARSET=utf8mb4

# Application Configuration
APP_ENV=development
APP_DEBUG=true
APP_URL=http://localhost:8000

# Security
SESSION_LIFETIME=3600
SESSION_SECURE=false
SESSION_HTTPONLY=true

# Timezone
TIMEZONE=UTC
```

### Step 5: Build Frontend Assets

```bash
# Build TailwindCSS
npm run build

# Or watch for changes during development
npm run watch
```

### Step 6: Start the Server

```bash
# Using PHP built-in server
php -S localhost:8000

# OR using browser-sync for live reload
npm run dev
```

### Step 7: Access the Application

Open your browser and navigate to:

```
http://localhost:8000
```

---

</section>

<section id="database-schema">

## 🗄️ Database Schema

### Entity Relationship Diagram (ERD)

```
users (1) ----< (1) doctors
users (1) ----< (1) patients
departments (1) ----< (0..*) doctors
doctors (1) ----< (0..*) appointments
patients (1) ----< (0..*) appointments
doctors (1) ----< (0..*) prescriptions
patients (1) ----< (0..*) prescriptions
medications (1) ----< (0..*) prescriptions
```

### Key Tables

| Table           | Description              | Key Fields                                                   |
| --------------- | ------------------------ | ------------------------------------------------------------ |
| `users`         | Base table for all users | id (PK), role, email, password_hash                          |
| `doctors`       | Doctor-specific data     | user_id (PK, FK), specialization, department_id              |
| `patients`      | Patient-specific data    | user_id (PK, FK), gender, date_of_birth                      |
| `departments`   | Hospital departments     | id (PK), name, location                                      |
| `appointments`  | Appointment bookings     | id (PK), doctor_id (FK), patient_id (FK), status             |
| `prescriptions` | Medical prescriptions    | id (PK), doctor_id (FK), patient_id (FK), medication_id (FK) |
| `medications`   | Medication catalog       | id (PK), name, instructions                                  |

---

</section>

<section id="project-structure">

## 📁 Project Structure

```
unity-care-clinic-v3/
├── assets/
│   ├── conf/
│   │   ├── bs-config.js           # BrowserSync configuration
│   │   └── tailwind.config.js     # TailwindCSS configuration
│   │
│   ├── css/
│   │   ├── tw.css                 # TailwindCSS input file
│   │   └── custom.css             # Custom styles
│   │
│   ├── js/
│   │   ├── auth.js                # Authentication logic
│   │   ├── appointments.js        # Appointment management
│   │   ├── prescriptions.js       # Prescription management
│   │   ├── dashboard.js           # Dashboard interactions
│   │   ├── crud.js                # Generic CRUD operations
│   │   └── sidebar.js             # Sidebar navigation
│   │
│   ├── media/
│   │   ├── erd.png                # Entity Relationship Diagram
│   │   ├── erd.dbml               # ERD source (dbdiagram.io)
│   │   ├── usecase-diagram.png    # Use Case Diagram
│   │   └── class-diagram.png      # Class Diagram
│   │
│   ├── php/
│   │   ├── class/
│   │   │   ├── parent/
│   │   │   │   └── abstract/
│   │   │   │       ├── BaseModel.php      # Abstract base for all models
│   │   │   │       └── User.php           # Abstract user class
│   │   │   │
│   │   │   ├── config/
│   │   │   │   └── Database.php           # PDO Singleton
│   │   │   │
│   │   │   ├── composition/
│   │   │   │   ├── Validator.php          # Input validation
│   │   │   │   ├── Session.php            # Session management
│   │   │   │   └── CSRFToken.php          # CSRF protection
│   │   │   │
│   │   │   ├── repository/
│   │   │   │   ├── UserRepository.php
│   │   │   │   ├── DoctorRepository.php
│   │   │   │   ├── PatientRepository.php
│   │   │   │   ├── AppointmentRepository.php
│   │   │   │   ├── PrescriptionRepository.php
│   │   │   │   ├── MedicationRepository.php
│   │   │   │   └── DepartmentRepository.php
│   │   │   │
│   │   │   ├── Admin.php
│   │   │   ├── Doctor.php
│   │   │   ├── Patient.php
│   │   │   ├── Department.php
│   │   │   ├── Appointment.php
│   │   │   ├── Medication.php
│   │   │   └── Prescription.php
│   │   │
│   │   ├── action/
│   │   │   ├── auth/
│   │   │   │   ├── login.php
│   │   │   │   ├── logout.php
│   │   │   │   └── register.php
│   │   │   │
│   │   │   ├── appointments/
│   │   │   │   ├── create.php
│   │   │   │   ├── cancel.php
│   │   │   │   ├── markDone.php
│   │   │   │   └── getAvailableSlots.php
│   │   │   │
│   │   │   ├── prescriptions/
│   │   │   │   ├── create.php
│   │   │   │   └── list.php
│   │   │   │
│   │   │   └── admin/
│   │   │       ├── users/
│   │   │       │   ├── create.php
│   │   │       │   ├── update.php
│   │   │       │   └── delete.php
│   │   │       ├── departments/
│   │   │       │   ├── create.php
│   │   │       │   ├── update.php
│   │   │       │   └── delete.php
│   │   │       └── medications/
│   │   │           ├── create.php
│   │   │           ├── update.php
│   │   │           └── delete.php
│   │   │
│   │   ├── component/
│   │   │   ├── sidebar.php            # Navigation sidebar
│   │   │   ├── header.php             # Page header
│   │   │   └── footer.php             # Page footer
│   │   │
│   │   ├── section/
│   │   │   ├── auth/
│   │   │   │   ├── login.php
│   │   │   │   └── register.php
│   │   │   │
│   │   │   ├── admin/
│   │   │   │   ├── dashboard.php
│   │   │   │   ├── users.php
│   │   │   │   ├── departments.php
│   │   │   │   ├── medications.php
│   │   │   │   └── appointments.php
│   │   │   │
│   │   │   ├── doctor/
│   │   │   │   ├── dashboard.php
│   │   │   │   ├── appointments.php
│   │   │   │   └── prescriptions.php
│   │   │   │
│   │   │   └── patient/
│   │   │       ├── dashboard.php
│   │   │       ├── book-appointment.php
│   │   │       ├── my-appointments.php
│   │   │       └── prescriptions.php
│   │   │
│   │   └── middleware/
│   │       ├── auth.php                # Authentication check
│   │       └── rbac.php                # Role-based access control
│   │
│   └── sql/
│       ├── ddl.sql                     # Database schema
│       ├── dml.sql                     # Seed data
│       └── crud-templates.sql          # Query templates
│
├── .env                                # Environment variables (NOT in git)
├── .env.example                        # Environment template
├── .gitignore
├── composer.json                       # PHP dependencies
├── composer.lock
├── package.json                        # Node dependencies
├── package-lock.json
├── index.php                           # Application entry point
└── README.md
```

---

</section>

<section id="test-accounts">

## 👥 Test Accounts

Use these accounts to test different user roles:

### 🔴 Admin Account

```
Email:    admin@unity.care
Password: Admin@2025
```

**Capabilities:**

-   Manage all users (Create, Read, Update, Delete)
-   Manage departments (CRUD)
-   Manage medications catalog (CRUD)
-   View all appointments with filters
-   Dashboard statistics
-   Full system access

### 🟢 Doctor Account

```
Email:    doctor@unity.care
Password: Doctor@2025
```

**Capabilities:**

-   View my appointments
-   Mark appointments as done
-   Cancel appointments
-   Create prescriptions for patients
-   View my patients list
-   View my statistics

### 🔵 Patient Account

```
Email:    patient@unity.care
Password: Patient@2025
```

**Capabilities:**

-   Book appointments with doctors
-   View my appointments
-   Cancel my appointments
-   View prescription history
-   View available time slots

---

</section>

<section id="user-stories">

## 📖 User Stories

### Authentication

-   **US01:** As a user, I can login with my email and password
-   **US02:** As a logged-in user, I can logout
-   **US03:** As a user, I am redirected if I try to access an unauthorized page

### Appointments

-   **US04:** As a Patient, I can book an appointment with a doctor
-   **US05:** As a Doctor, I can see my appointments list
-   **US06:** As a Patient or Doctor, I can cancel my appointment
-   **US07:** As a Doctor, I can mark an appointment as done

### Prescriptions

-   **US08:** As a Doctor, I can create a prescription for a patient
-   **US09:** As a Patient, I can see my prescription history

### Administration

-   **US10:** As an Admin, I can manage the medications catalog
-   **US11:** As an Admin, I can see all appointments with filters

### Security

-   **US12:** As a user, my forms are protected against CSRF and displayed data against XSS

### Bonus

-   **US13:** As a Patient, I see only available time slots when booking (AJAX)

---

</section>

<section id="security-features">

## 🛡️ Security Features

### XSS Prevention

```php
// All outputs are escaped
echo htmlspecialchars($user->getUsername(), ENT_QUOTES, 'UTF-8');
```

### CSRF Protection

```php
// In forms
<input type="hidden" name="csrf_token" value="<?= CSRFToken::generate() ?>">

// In action files
if (!CSRFToken::validate($_POST['csrf_token'])) {
    throw new SecurityException('Invalid CSRF token');
}
```

### SQL Injection Prevention

```php
// Repository pattern with PDO prepared statements
$stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
$stmt->execute(['email' => $email]);
```

### Password Security

```php
// Hashing
$hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

// Verification
if (password_verify($password, $user->getPasswordHash())) {
    // Login successful
}
```

### Environment Variables

```php
// Load from .env file
$dbHost = $_ENV['DB_HOST'] ?? 'localhost';
$dbName = $_ENV['DB_NAME'] ?? 'UCCV3';
```

---

</section>

<section id="architecture">

## 🏗️ Architecture

### Repository Pattern

The application uses the **Repository Pattern** to separate data access logic from business logic:

```
Controller/Action File
    ↓
Repository (Data Access)
    ↓
Model (Business Logic)
    ↓
Database
```

#### Example: UserRepository

```php
class UserRepository {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findByEmail(string $email): ?User {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) return null;

        // Factory pattern to create appropriate user type
        return $this->createUserFromData($data);
    }

    public function save(User $user): bool {
        // Insert or update logic
    }
}
```

### Class Hierarchy

```
BaseModel (abstract)
    ↑
User (abstract)
    ↑
    ├── Admin
    ├── Doctor
    └── Patient

BaseModel (abstract)
    ↑
    ├── Department
    ├── Appointment
    ├── Medication
    └── Prescription
```

### Design Patterns Used

-   ✅ **Singleton:** Database connection
-   ✅ **Repository:** Data access abstraction
-   ✅ **Abstract Factory:** BaseModel for common operations
-   ✅ **Strategy:** Different behaviors for Admin/Doctor/Patient
-   ✅ **Composition:** Validator, Session, CSRFToken as utilities

---

</section>

<section id="documentation">

## 📚 Documentation

### UML Diagrams

All diagrams are available in `/assets/media/`:

-   **ERD (Entity Relationship Diagram):** `erd.png` - Database structure
-   **Use Case Diagram:** `usecase-diagram.png` - System functionality
-   **Class Diagram:** `class-diagram.png` - OOP architecture
-   **ERD Source:** `erd.dbml` - Edit on dbdiagram.io

---

</section>

<section id="development-timeline">

## 📅 Development Timeline

| Sprint       | Duration | Focus                        | Story Points |
| ------------ | -------- | ---------------------------- | ------------ |
| **Sprint 0** | Day 1    | Planning + Diagrams          | 8            |
| **Sprint 1** | Days 2-3 | DB + Core OOP + Repositories | 22           |
| **Sprint 2** | Days 4-5 | Auth + Appointments UI       | 27           |
| **Sprint 3** | Day 6    | Admin Dashboard + Stats      | 8            |
| **Sprint 4** | Days 7-8 | Prescriptions + Security     | 26           |
| **Sprint 5** | Day 9    | Deployment + Presentation    | 3.5          |

**Total Story Points:** 94.5

### Sprint Breakdown

#### Sprint 0: Planning (Day 1)

-   ✅ Jira board setup
-   ✅ GitHub repository
-   ✅ ERD creation
-   ✅ Use Case diagram
-   ✅ Class diagram
-   ✅ README documentation

#### Sprint 1: Foundation (Days 2-3)

-   ⏳ SQL schema + seed data
-   ⏳ PDO Singleton (Database class)
-   ⏳ `.env` configuration
-   ⏳ BaseModel abstract class
-   ⏳ User abstract class
-   ⏳ Admin/Doctor/Patient classes
-   ⏳ All repositories
-   ⏳ Validator utility

#### Sprint 2: Authentication & Appointments (Days 4-5)

-   ⏳ Login/Logout functionality
-   ⏳ Session management
-   ⏳ RBAC middleware
-   ⏳ Appointment class + repository
-   ⏳ Book appointment UI (Patient)
-   ⏳ Appointments list (Doctor)
-   ⏳ Cancel appointment functionality

#### Sprint 3: Admin Features (Day 6)

-   ⏳ Admin dashboard with statistics
-   ⏳ View all appointments with filters
-   ⏳ User management UI

#### Sprint 4: Prescriptions & Security (Days 7-8)

-   ⏳ Medication class + repository
-   ⏳ Prescription class + repository
-   ⏳ Doctor: Create prescription UI
-   ⏳ Patient: View prescriptions UI
-   ⏳ Admin: Medications catalog CRUD
-   ⏳ XSS protection implementation
-   ⏳ CSRF tokens on all forms
-   ⏳ Security audit

#### Sprint 5: Deployment (Day 9)

-   ⏳ Hosting deployment
-   ⏳ Final Git push + tags
-   ⏳ Jira completion
-   ⏳ Presentation preparation

---

</section>

<section id="future-enhancements">

## 🚀 Future Enhancements

-   [ ] REST API for mobile integration
-   [ ] Email notifications
-   [ ] SMS reminders
-   [ ] Patient medical history
-   [ ] File upload for documents
-   [ ] Multi-language support
-   [ ] Dark mode
-   [ ] Appointment rescheduling
-   [ ] Doctor ratings
-   [ ] Insurance integration
-   [ ] Real-time chat (Doctor-Patient)
-   [ ] Video consultations

---

</section>

<section id="contributing">

## 🤝 Contributing

This is an educational project for **Simplonline - Développeur Web et Web Mobile [2023]**.

---

</section>

<section id="contributors">

## 👨‍💻 Contributors

-   **Sami Regragui** - Full Stack Developer
    -   GitHub: [@sami-regragui-work](https://github.com/sami-regragui-work)
    -   Email: sami.regragui.work@protonmail.com

---

</section>

<section id="license">

## 📄 License

For education purpose and portfolio only

---

</section>

<section id="acknowledgments">

## 🙏 Acknowledgments

-   **Simplonline** for the project brief and guidance
-   **Unity Care Clinic** team for requirements
-   PHP and MySQL communities for documentation

---

</section>

<section id="support">

<!-- ## 📞 Support

For questions or issues:

-   📧 Email: support@unity.care
-   🐛 Issues: [GitHub Issues](https://github.com/sami-regragui-work/unity-care-clinic-v3/issues)
-   📖 Jira: [Project Board](https://your-jira-instance.atlassian.net)

--- -->

</section>

<div align="center">

**Made with ❤️ for Unity Care Clinic**

[⬆ Back to Top](#unity-care-clinic-v3)

</div>
