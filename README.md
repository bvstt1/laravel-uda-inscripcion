# 📅 Event System (Sistema de Eventos) - UDA Innovation HUB

> A centralized web platform for managing academic and extracurricular activities at the University of Atacama.

## 📖 Overview

The **Event System** is a web platform developed to optimize the institutional management of events at the **University of Atacama**. It replaces manual processes with a digital, centralized solution for registration, user administration, and attendance control using **QR codes** and **RUT verification**.

The project features a specialized **"Totem" module**, designed for on-site kiosks to track daily attendance efficiently.

---

## ✨ Key Features

### 👤 User Roles
* **Administrator:** Full control over events, users, and reporting.
* **Student/External:** Public interface for event enrollment and attendance tracking.
* **System:** Automated background validations.

### 🚀 Core Functionalities
* **Event Management:** Create, edit, and reschedule events with custom categories.
* **Smart Registration:**
    * **QR Code Scanner:** Real-time camera integration for attendance.
    * **RUT Entry:** Manual backup for users without devices.
    * **Totem Mode:** Full-screen interface for high-traffic entry points.
* **Analytics:** Exportable Excel/PDF reports for daily and weekly attendance.
* **Notifications:** Automated emails for enrollment confirmation.

---

## 🛠️ Tech Stack & Tools

* **Backend:** [Laravel](https://laravel.com/) (PHP)
* **Frontend:** [Blade Templates](https://laravel.com/docs/blade) & [Tailwind CSS](https://tailwindcss.com/)
* **Database:** MySQL
* **Infrastructure:** Docker (Laravel Sail) & Railway (Staging)
* **Version Control:** Git & GitHub

---

## 📅 Development Journey

The project was developed over a **12-week period (April - June 2025)** using an **Agile/Scrum-inspired methodology**.

### Phase 1: Foundation (Weeks 1-3)
* **Setup:** Requirement gathering (HU1-HU19), Git workflow definition, and environment configuration (Docker/XAMPP).
* **Architecture:** Database modeling (ERD) and frontend scaffolding using **Tailwind CSS**.
* **Result:** A functional skeleton with defined routes and layouts.

### Phase 2: Core Logic (Weeks 4-5)
* **Authentication:** Implemented custom auth logic to separate *Administrators* from *Students/External* users.
* **CRUD Operations:** Built the core event management system (Create, Read, Update, Delete).
* **Enrollment:** Developed logic for event capacity handling and user registration.

### Phase 3: Advanced Features & IoT (Weeks 6-9)
* [cite_start]**QR Integration:** Researched and implemented browser-based QR scanning libraries for real-time attendance[cite: 51].
* [cite_start]**Totem Interface:** Designed a "Kiosk Mode" optimized for large touchscreens, allowing rapid RUT entry and attendance tracking at the HUB entrance[cite: 55, 60].
* **UX Improvements:** Added color-coded categories and mobile responsiveness.

### Phase 4: Testing & Deployment (Weeks 10-12)
* [cite_start]**Automation:** Automated hiding of past events and password recovery systems[cite: 70].
* **QA:** Conducted End-to-End (E2E) testing on QR flows and Excel exports.
* [cite_start]**Deployment:** Finalized **Docker** configuration and performed load testing on **Railway** to simulate production environments[cite: 79].

---

## ⚙️ Installation & Deployment

### Prerequisites
* [Docker Desktop](https://www.docker.com/products/docker-desktop)
* [Git](https://git-scm.com/)

### 1. Clone the Repository
```bash
git clone [https://github.com/bvstt1/laravel-uda-inscripcion.git](https://github.com/bvstt1/laravel-uda-inscripcion.git)
cd laravel-uda-inscripcion
