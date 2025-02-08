# Village Market Web Application

This project is a web application for managing a local farmers' market. Vendors can register their businesses, list their products, and manage their attendance at the market. Administrators can manage vendors and products, make changes to the homepage, and approve new vendors.

## Features Implemented

- User and Vendor registration with validation
- MySQL transactions to prevent partial registrations (orphaned Users, if Vendor registration fails)
- Standardized data formatting before storing (emails lowercase; names title case)
- Accessibility features (landmarks, form labels, structural elements)

## Database Schema

The database schema follows the structure defined in [dbdiagram.io](https://dbdiagram.io/d/Village-Market-6779b24a5406798ef74936ae).
Alternatively, refer to `sql/village_market.sql` for the full schema setup.

## Installation & Setup

### Prerequisites

- PHP 8+
- MySQL/MariaDB
- Apache (XAMPP preferred)

### Getting Started

1. Ensure XAMPP is installed and running (Apache & MySQL).
2. Place the project in the `htdocs` folder.
3. Import `village_market.sql` using phpMyAdmin (includes CREATE and USE DATABASE statements).
4. Open `http://localhost/village_market` in a browser.

## Future Development Plans

- User login & authentication system, with RBAC implementation
- Vendor dashboard for business, attendance, & product management
- Market attendance tracking
- Admin dashboard for user/vendor approval and management and overall market management
- Filterable/searchable view of products and vendors, open to general public
