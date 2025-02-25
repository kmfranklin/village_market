# Village Market Web Application

The Village Market Web Application is a platform for managing a local farmers' market. Vendors can register their businesses, list their products, and manage their attendance at the market, while administrators can manage vendors and products, make changes to the homepage, and approve new vendors.

---

## Features Implemented

### User Authentication System

- Role-based access control (RBAC) for Vendors, Admins, and Super Admins
- Secure session handling with login & logout functionality
- Redirects users based on role to the correct dashboard
- Dynamic, personalized dashboards for Admins and Vendors
- Standardized session management with `Session` class

### User and Vendor Registration

- Validated forms prevent incorrect/incomplete submissions
- MySQL transactions ensure data consistency (no orphaned user accounts)
- Standardized data formatting before storing (emails lowercase, names title case)

### Vendor Management System (Admin Feature)

- **Add Vendors** – Admins can register vendors from the Admin panel
- **Edit Vendor Information** – Business details and contact information can be updated
- **Approve or Reject Vendor Registrations** – Control access to the system
- **Suspend Vendors** – Temporarily limit access to the application
- **Restore Vendors** – Reactivate suspended vendors or permanently delete them

### Forgot Password System

- Password recovery via email-based reset link
- Secure token-based password reset system
- Tokens expire after one hour for security
- Uses PHPMailer for email handling

### Accessibility and UI Features

- Structural HTML elements for better screen reader support
- Proper use of form labels, ARIA landmarks, and accessible keyboard navigation

---

## Dependencies

This project uses the following external libraries:

- **[PHPMailer](https://github.com/PHPMailer/PHPMailer)**

  - Sends password reset emails securely.
  - Configured with SMTP authentication.

- **[Dotenv (vlucas/phpdotenv)](https://github.com/vlucas/phpdotenv)**
  - Stores and loads environment variables securely.
  - Keeps SMTP credentials and database secrets out of version control.

All dependencies are installed via Composer.

---

## Database Schema

The database schema follows the structure defined in [dbdiagram.io](https://dbdiagram.io/d/Village-Market-6779b24a5406798ef74936ae).  
Alternatively, refer to `sql/village_market.sql` for the full schema setup.

---

## Installation & Setup

### Prerequisites

- PHP 8+
- MySQL/MariaDB
- Apache (XAMPP preferred)
- Composer (to manage dependencies)

### Getting Started

1. Ensure XAMPP is installed and running (Apache & MySQL).
2. Place the project in the `htdocs` folder.
3. Import `village_market.sql` using phpMyAdmin (includes `CREATE DATABASE` and `USE DATABASE` statements).
4. Open `http://localhost/village_market` in a browser.

---

## Future Development Plans

### Authentication Enhancements

- "Remember Me" functionality with secure authentication tokens
- Change Password option for logged-in users

### Vendor Dashboard

- Business profile management
- Product management
- Market attendance tracking

### Admin Dashboard

- Ability to manage users, vendors, and products
- Homepage content management system (CMS)

### Public Marketplace

- Filterable and searchable list of vendors and products
- Open to general public for browsing market details
