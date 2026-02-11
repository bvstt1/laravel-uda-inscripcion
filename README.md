
[![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

A comprehensive web-based event management platform developed for the Innovation HUB at Universidad de Atacama, designed to modernize and streamline the management of academic and extracurricular activities.

## 📋 Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Technology Stack](#technology-stack)
- [System Architecture](#system-architecture)
- [Installation](#installation)
- [Development](#development)
- [User Stories](#user-stories)
- [Database Schema](#database-schema)
- [Screenshots](#screenshots)
- [Contributors](#contributors)

## 🎯 Overview

The Event Management System replaces manual, paper-based processes with a centralized digital platform that enables:

- **Event Registration**: Digital enrollment system for students and external users
- **Attendance Tracking**: QR code scanning and RUT-based verification
- **Automated Notifications**: Email alerts for event updates and confirmations
- **Report Generation**: Excel export functionality for attendance records
- **Administrative Control**: Complete event lifecycle management
- **Totem Mode**: Standalone attendance kiosk for physical installations

**Development Period**: April 1 - June 30, 2025  
**Developed by**: Yerko Abarza Leiva & Bastián Velásquez Egaña  
**Supervisor**: Sebastián Montalván González

## ✨ Features

### For Administrators
- ✅ Create, edit, and delete events (main events and sub-events)
- ✅ View enrolled participants and attendance records
- ✅ Generate Excel/PDF reports
- ✅ Schedule automatic registration opening
- ✅ Assign colors and images to event categories
- ✅ Manage user accounts
- ✅ Access daily attendance logs

### For Students & External Users
- ✅ Institutional login (RUT or university email)
- ✅ Browse available events
- ✅ Self-registration with personal information
- ✅ Enroll in events
- ✅ Receive email notifications
- ✅ Register attendance via QR code or RUT input
- ✅ Edit or delete account
- ✅ View event details and schedules

### System Features
- ✅ Database integration for student validation
- ✅ Form validation with error messaging
- ✅ Password recovery via email
- ✅ Automated event state management
- ✅ Production-ready deployment configuration

## 🛠 Technology Stack

### Backend
- **Framework**: Laravel 11.x
- **Language**: PHP 8.2+
- **Database**: MySQL/PostgreSQL
- **ORM**: Eloquent
- **Authentication**: Custom multi-role auth system

### Frontend
- **Template Engine**: Blade
- **CSS Framework**: Tailwind CSS
- **JavaScript**: Vanilla JS + QR Scanner Library
- **Icons**: Heroicons / Font Awesome

### DevOps
- **Version Control**: Git
- **Deployment**: Railway / Docker
- **Environment**: Ubuntu 24
- **Web Server**: Nginx / Apache

### Development Tools
- **Package Manager**: Composer (PHP), NPM (Node.js)
- **Local Environment**: XAMPP / Docker
- **Database Management**: phpMyAdmin / TablePlus

## 🏗 System Architecture

```
event-management-system/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── EventController.php
│   │   │   ├── AttendanceController.php
│   │   │   ├── UserController.php
│   │   │   └── AuthController.php
│   │   └── Middleware/
│   ├── Models/
│   │   ├── Event.php
│   │   ├── Category.php
│   │   ├── Student.php
│   │   ├── External.php
│   │   ├── Enrollment.php
│   │   └── Attendance.php
│   └── Mail/
│       ├── EventNotification.php
│       └── EventUpdate.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── views/
│   │   ├── admin/
│   │   ├── student/
│   │   ├── totem/
│   │   └── auth/
│   └── css/
├── routes/
│   └── web.php
├── public/
│   ├── css/
│   ├── js/
│   └── images/
└── tests/
```

## 📦 Installation

### Prerequisites
```bash
- PHP >= 8.2
- Composer
- Node.js >= 18.x
- MySQL >= 8.0 or PostgreSQL >= 14
- Git
```

### Setup Instructions

1. **Clone the repository**
```bash
git clone https://github.com/your-org/event-management-system.git
cd event-management-system
```

2. **Install PHP dependencies**
```bash
composer install
```

3. **Install Node dependencies**
```bash
npm install
```

4. **Environment configuration**
```bash
cp .env.example .env
php artisan key:generate
```

5. **Configure database in `.env`**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=events_db
DB_USERNAME=root
DB_PASSWORD=your_password
```

6. **Run migrations**
```bash
php artisan migrate
```

7. **Seed database (optional)**
```bash
php artisan db:seed
```

8. **Build assets**
```bash
npm run build
```

9. **Start development server**
```bash
php artisan serve
```

Visit `http://localhost:8000`

## 💻 Development

### Project Structure

The project follows Laravel's MVC architecture with clear separation of concerns:

#### Controllers
- `EventController`: Handles CRUD operations for events and sub-events
- `AttendanceController`: Manages QR scanning, RUT verification, and attendance logging
- `UserController`: User account management (students and externals)
- `AuthController`: Custom authentication logic for multiple user types
- `ReportController`: Excel/PDF generation for attendance records

#### Models & Relationships
```php
Event -> hasMany(Event) // parent-child for sub-events
Event -> belongsTo(Category)
Event -> hasMany(Enrollment)
Event -> hasMany(Attendance)

Student -> hasMany(Enrollment)
Student -> hasMany(Attendance)

External -> hasMany(Enrollment)
External -> hasMany(Attendance)
```

#### Key Routes
```php
// Public routes
Route::get('/', 'HomeController@index');
Route::get('/login', 'AuthController@showLogin');
Route::post('/login', 'AuthController@login');
Route::get('/register', 'AuthController@showRegister');
Route::post('/register', 'AuthController@register');

// Student/External routes
Route::middleware(['auth:student,external'])->group(function () {
    Route::get('/events', 'EventController@index');
    Route::post('/events/{id}/enroll', 'EnrollmentController@store');
    Route::delete('/events/{id}/unenroll', 'EnrollmentController@destroy');
});

// Admin routes
Route::middleware(['auth:admin'])->prefix('admin')->group(function () {
    Route::resource('events', EventController::class);
    Route::get('events/{id}/attendance', 'AttendanceController@show');
    Route::get('events/{id}/export', 'ReportController@exportExcel');
});

// Totem routes
Route::prefix('totem')->group(function () {
    Route::get('/events', 'TotemController@showEvents');
    Route::post('/attendance', 'AttendanceController@register');
    Route::get('/free-attendance', 'TotemController@showFreeAttendance');
});
```

### Development Workflow (12-Week Sprint)

#### Week 1: Planning & Setup
- Initial meeting with supervisor
- Product backlog creation (19 user stories)
- Git repository setup
- Role distribution: backend architecture vs. frontend design

#### Week 2: Architecture & Database
- Laravel installation and configuration
- Entity-Relationship diagram design
- Database schema definition
- Prototype creation

#### Week 3: Base Views
- Blade layout creation
- Tailwind CSS component library
- Route structure definition
- Initial controller scaffolding

#### Week 4: Core CRUD & Authentication
- Custom multi-role authentication system (HU1, HU2, HU3)
- Admin vs. Student/External role differentiation
- Event management CRUD
- Code review and quality assurance

#### Week 5: User Registration & Enrollment
- External user registration (HU8, HU12)
- Event enrollment module (HU9, HU11)
- Capacity validation
- First functional prototype review

#### Week 6: QR Code Attendance
- QR code scanning library research and testing
- Camera permission handling
- Real-time QR attendance module (HU13)
- Manual RUT fallback option (HU14)
- Cross-browser and mobile testing

#### Week 7: Totem Event Attendance
- Daily/weekly event totem panel
- Event filtering by status and category
- "Locked event" logic for time-based restrictions
- Full-screen mode optimization
- Advanced form validation (HU17, HU18)

#### Week 8: Free Attendance Totem
- HUB entrance attendance kiosk (HU14)
- Single RUT input interface
- Large button design for touchscreen
- Daily Excel export functionality (HU15)
- SQL query optimization for high-volume logging

#### Week 9: UI Polish & Mobile Optimization
- Color-coded event categories (HU6)
- Locked event visual indicators
- Institutional registration refactoring (HU16)
- Mobile-responsive design refinement
- Style consistency across modules

#### Week 10: Security & Password Recovery
- Automatic hiding of past events (HU9)
- Email-based password recovery (HU17)
- Unit testing for core controllers
- CSRF protection reinforcement
- Role-based permission hardening

#### Week 11: End-to-End Testing
- Complete enrollment → QR attendance → export flow testing
- Totem attendance simulation at HUB
- Email notification queue testing (HU7)
- Typography and spacing final adjustments
- Collaborative bug fixing

#### Week 12: Production Preparation
- QR reader performance testing on various screens
- Railway + Docker deployment setup
- Production load testing
- Final user flow validation
- Supervisor demo and feedback integration

### Development Best Practices

#### Code Standards
```php
// Controller example with clear responsibility
class EventController extends Controller
{
    public function index()
    {
        $events = Event::with('category')
            ->where('status', 'active')
            ->orderBy('start_date')
            ->get();
            
        return view('events.index', compact('events'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'start_date' => 'required|date',
            'capacity' => 'required|integer|min:1',
            'category_id' => 'required|exists:categories,id'
        ]);
        
        $event = Event::create($validated);
        
        return redirect()->route('events.show', $event)
            ->with('success', 'Event created successfully');
    }
}
```

#### Blade Component Reusability
```blade
<!-- resources/views/components/event-card.blade.php -->
<div class="bg-white rounded-lg shadow-md p-6 border-l-4"
     style="border-color: {{ $event->category->color }}">
    <h3 class="text-xl font-bold">{{ $event->title }}</h3>
    <p class="text-gray-600">{{ $event->start_date->format('d/m/Y H:i') }}</p>
    <p class="text-sm text-gray-500">{{ $event->location }}</p>
    
    @if($event->is_locked)
        <span class="badge badge-danger">Event Locked</span>
    @else
        <a href="{{ route('events.enroll', $event) }}" 
           class="btn btn-primary mt-4">
            Enroll Now
        </a>
    @endif
</div>
```

#### Database Migrations
```php
// Example migration for events table
Schema::create('events', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->string('slug')->unique();
    $table->text('description');
    $table->dateTime('start_date');
    $table->dateTime('end_date');
    $table->string('location');
    $table->integer('capacity');
    $table->foreignId('category_id')->constrained();
    $table->foreignId('parent_event_id')->nullable()->constrained('events');
    $table->timestamps();
});
```

### Testing

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature

# Run with coverage
php artisan test --coverage
```

### Building for Production

```bash
# Optimize autoloader
composer install --optimize-autoloader --no-dev

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Build assets
npm run build

# Set proper permissions
chmod -R 755 storage bootstrap/cache
```

## 📖 User Stories

### Completed User Stories (17/19)

| ID | Role | Description | Status |
|----|------|-------------|--------|
| HU1 | Admin | Create main events and sub-events | ✅ Completed |
| HU2 | Admin | Edit, delete, and reschedule events | ✅ Completed |
| HU3 | Admin | View enrolled participants and attendance | ✅ Completed |
| HU4 | Admin | Generate attendance reports in Excel/PDF | ✅ Completed |
| HU5 | Admin | Schedule automatic registration opening | ⚠️ Modified |
| HU6 | Admin | Assign colors or images to categories | ✅ Completed |
| HU7 | Student/Ext | Receive email notifications about events | ✅ Completed |
| HU8 | Student | Log in using RUT or institutional email | ✅ Completed |
| HU9 | Student/Ext | View available events for enrollment | ✅ Completed |
| HU10 | Student | View cumulative attendance percentage | ❌ Discarded |
| HU11 | External | Register by entering personal information | ✅ Completed |
| HU12 | Student/Ext | Enroll in events | ✅ Completed |
| HU13 | Student/Ext | Register attendance via QR code or RUT | ✅ Completed |
| HU14 | Student/Ext | Register daily free attendance at facilities | ✅ Completed |
| HU15 | Admin | Generate daily Excel for free attendance | ✅ Completed |
| HU16 | System/Admin | Integration with institutional database | ⚠️ Modified |
| HU17 | System | Validate records and login with error messages | ✅ Completed |
| HU18 | Student/Ext | Edit or delete user account | ✅ Completed |
| HU19 | System | Publish system on web server | ⚠️ Modified |

### Discarded/Modified Stories
- **HU10**: Cumulative attendance percentage - Deferred to future version
- **HU5**: Modified to manual scheduling due to time constraints
- **HU16**: Adapted to store name/surname directly instead of institutional DB integration
- **HU19**: Modified to test production environment instead of full deployment

## 🗄 Database Schema

### Core Tables

#### `events`
- `id` (PK)
- `title` VARCHAR(255)
- `slug` VARCHAR(255) UNIQUE
- `description` TEXT
- `start_date` DATETIME
- `end_date` DATETIME
- `location` VARCHAR(255)
- `capacity` INT
- `category_id` (FK → categories)
- `parent_event_id` (FK → events, nullable)
- `created_at`, `updated_at`

#### `categories`
- `id` (PK)
- `name` VARCHAR(255)
- `color` VARCHAR(7)
- `image` VARCHAR(255) NULLABLE
- `created_at`, `updated_at`

#### `students`
- `id` (PK)
- `rut` VARCHAR(12) UNIQUE
- `name` VARCHAR(255)
- `surname` VARCHAR(255)
- `email` VARCHAR(255) UNIQUE
- `password` VARCHAR(255)
- `program` VARCHAR(255)
- `created_at`, `updated_at`

#### `externals`
- `id` (PK)
- `rut` VARCHAR(12) UNIQUE
- `name` VARCHAR(255)
- `surname` VARCHAR(255)
- `email` VARCHAR(255) UNIQUE
- `password` VARCHAR(255)
- `institution` VARCHAR(255)
- `position` VARCHAR(255)
- `created_at`, `updated_at`

#### `enrollments`
- `id` (PK)
- `event_id` (FK → events)
- `user_id` INT
- `user_type` ENUM('student', 'external')
- `enrollment_date` DATETIME
- `status` ENUM('active', 'cancelled')
- `created_at`, `updated_at`

#### `attendances`
- `id` (PK)
- `event_id` (FK → events)
- `user_id` INT
- `user_type` ENUM('student', 'external')
- `attendance_date` DATETIME
- `created_at`, `updated_at`

#### `free_attendances`
- `id` (PK)
- `rut` VARCHAR(12)
- `created_at`, `updated_at`

### Entity Relationships

```
Events (1) ──────── (N) Enrollments
Events (1) ──────── (N) Attendances
Events (N) ──────── (1) Categories
Events (1) ──────── (N) Events [self-referential for sub-events]

Students (1) ─────── (N) Enrollments
Students (1) ─────── (N) Attendances

Externals (1) ────── (N) Enrollments
Externals (1) ────── (N) Attendances
```

## 📸 Screenshots

### Student Portal
![Event Listing](docs/images/event-listing.png)
*Browse and filter available events*

### Admin Dashboard
![Admin Panel](docs/images/admin-panel.png)
*Event management and analytics*

### QR Attendance
![QR Scanner](docs/images/qr-scanner.png)
*Contactless attendance registration*

### Totem Mode
![Totem Interface](docs/images/totem.png)
*Standalone kiosk for entrance attendance*

## 👥 Contributors

**Development Team**
- **Yerko Abarza Leiva** - Backend Architecture & Database Design
- **Bastián Velásquez Egaña** - Frontend Development & UI/UX

**Supervision**
- **Sebastián Montalván González** - Technical Supervisor

**Institution**
- Universidad de Atacama
- Faculty of Engineering
- Department of Computer Science and Informatics

## 📄 License

This project was developed as part of a professional internship at Universidad de Atacama. All rights reserved.

## 🙏 Acknowledgments

- Innovation HUB at Universidad de Atacama for providing the opportunity
- Sebastián Montalván González for guidance and supervision
- The Laravel and Tailwind communities for excellent documentation
- All students and staff who participated in testing

---

**Project Duration**: April 1 - June 30, 2025  
**Institution**: Universidad de Atacama  
**Department**: Innovation HUB

For questions or support, please contact the development team through the university's Innovation HUB.
