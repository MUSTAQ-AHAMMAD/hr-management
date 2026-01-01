# HR Management System

A comprehensive Laravel-based HR Management System for managing employee onboarding and exit clearance processes.

## Features

### 1. **User Management**
- Multi-role authentication system (Super Admin, Admin, Department Users)
- Role-based permissions using Spatie Laravel Permission
- Secure login and registration functionality

### 2. **Employee Onboarding Requests**
- Department-specific task assignments
- Workflow tracking and status updates
- Tasks include:
  - IT: Laptop provision, SIM card, email account setup
  - Admin: Employee ID creation, workspace assignment
  - Finance: Payroll setup

### 3. **Employee Exit Clearance**
- Multi-department clearance validation
- Asset return tracking
- Financial dues clearance
- PDF-based clearance report generation
- Document storage and management

### 4. **Email Integration**
- Automated email notifications during onboarding workflows
- Exit clearance workflow notifications
- Stage-based email triggers

### 5. **Notification Service**
- In-app alerts for tasks and pending workflows
- Real-time status updates
- Notification center with read/unread tracking

### 6. **Professional UI/UX**
- Blue color scheme (Navy, Cobalt, Light Blue) with White/Light Grey
- Responsive layout for web and mobile
- TailwindCSS-based modern design
- Intuitive navigation and dashboard

### 7. **Analytics and Reports**
- Comprehensive dashboard with statistics
- Onboarding and exit timeline tracking
- Department performance metrics
- Visual representations with graphs

## Technology Stack

- **Backend**: Laravel 12
- **Database**: MySQL/SQLite
- **Frontend**: TailwindCSS 4.0, Blade Templates
- **Authentication**: Laravel Breeze
- **Permissions**: Spatie Laravel Permission
- **PDF Generation**: DomPDF
- **Email**: Laravel Mail
- **Real-time**: Pusher (for web notifications)

## Installation

### Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js and npm
- MySQL database (or use SQLite for testing)

### Setup Instructions

1. **Clone the repository**
```bash
git clone https://github.com/MUSTAQ-AHAMMAD/hr-management.git
cd hr-management
```

2. **Install PHP dependencies**
```bash
composer install
```

3. **Install Node dependencies**
```bash
npm install
```

4. **Environment Configuration**
```bash
cp .env.example .env
php artisan key:generate
```

5. **Configure Database**

Edit `.env` file and set your database credentials:

For MySQL:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hr_management
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

Or use SQLite (default for testing):
```env
DB_CONNECTION=sqlite
```

6. **Run Migrations and Seeders**
```bash
php artisan migrate:fresh --seed
```

This will create all necessary tables and seed the database with:
- 5 Departments (IT, Admin, Finance, HR, Operations)
- 3 Roles (Super Admin, Admin, Department User)
- Sample users with credentials
- Default onboarding and exit tasks

7. **Build Assets**
```bash
npm run build
```

For development with hot reload:
```bash
npm run dev
```

8. **Run the Application**
```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

## Default Login Credentials

| Role | Email | Password |
|------|-------|----------|
| Super Admin | admin@hrmanagement.com | password |
| Admin | hr@hrmanagement.com | password |
| IT Department User | it@hrmanagement.com | password |
| Finance Department User | finance@hrmanagement.com | password |

## Usage

### For Super Admin / Admin

1. **Dashboard**: View overall statistics and recent activities
2. **Employees**: Manage employee records
3. **Onboarding**: Create and manage onboarding requests
4. **Exit Clearance**: Initiate and track exit clearance processes
5. **Departments**: Manage departments and their configurations
6. **Users**: Manage system users and roles
7. **Tasks**: Configure department-specific tasks

### For Department Users

1. **Dashboard**: View assigned tasks and statistics
2. **My Tasks**: View and update status of assigned tasks
3. **Onboarding**: Process department-specific onboarding tasks
4. **Exit Clearance**: Complete exit clearance tasks for their department

## Key Modules

### Onboarding Workflow

1. HR creates an onboarding request for a new employee
2. System automatically assigns department-specific tasks
3. Department users receive notifications
4. Each department completes their tasks (IT provides laptop, Admin creates ID, etc.)
5. Request marked as complete when all tasks are done

### Exit Clearance Workflow

1. HR initiates exit clearance request
2. Department-specific exit tasks are assigned
3. IT collects assets (laptop, SIM card)
4. Finance clears dues
5. Admin collects ID cards
6. PDF clearance certificate is generated upon completion
7. Documents are stored for record-keeping

## Permissions

The system uses role-based permissions:

- **Super Admin**: Full system access
- **Admin**: Manage employees, requests, departments, and reports
- **Department User**: View and process assigned tasks

## Email Configuration

Configure email settings in `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=hr@hrmanagement.com
MAIL_FROM_NAME="HR Management"
```

## Pusher Configuration (Optional)

For real-time notifications, configure Pusher in `.env`:

```env
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your_app_id
PUSHER_APP_KEY=your_app_key
PUSHER_APP_SECRET=your_app_secret
PUSHER_APP_CLUSTER=your_cluster
```

## Development

### Running Tests

```bash
php artisan test
```

### Code Style

This project follows PSR-12 coding standards. Use Laravel Pint for formatting:

```bash
./vendor/bin/pint
```

## Project Structure

```
hr-management/
├── app/
│   ├── Http/Controllers/     # All controllers
│   ├── Models/               # Eloquent models
│   └── View/Components/      # Blade components
├── database/
│   ├── migrations/           # Database migrations
│   └── seeders/              # Database seeders
├── resources/
│   ├── css/                  # Stylesheets
│   ├── js/                   # JavaScript files
│   └── views/                # Blade templates
├── routes/
│   ├── web.php              # Web routes
│   └── auth.php             # Authentication routes
└── public/                   # Public assets
```

## Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For issues, questions, or suggestions, please open an issue on GitHub or contact the development team.

## Changelog

### Version 1.0.1 (January 2026)
- **Fixed**: Exit clearance PDF generation now properly enabled after all department tasks are completed
  - Issue: PDF generation button was not appearing after all departments cleared the exit request
  - Solution: Updated status handling to properly set 'cleared' status when all tasks are completed
  - Added comprehensive test coverage for exit clearance workflow
  - Tests verify: status transitions, PDF generation prevention, and PDF generation success

### Version 1.0.0 (Initial Release)
- Complete onboarding and exit clearance workflow
- Multi-role authentication system
- Department and task management
- Dashboard with analytics
- Email and in-app notifications
- PDF report generation
- Professional blue-themed UI
