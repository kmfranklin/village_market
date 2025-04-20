# Village Market Web Application

A full-stack web application for managing a local farmers market, built as a final project for WEB-289 (Internet Technologies Project). The app provides distinct views and functionality for the general public, vendors, admins, and super admins.

## Live Site

[The Village Market](https://villagemarkethub.com)

## Table of Contents

- [Features](#features)
  - [Authentication](#authentication)
  - [Vendor Dashboard](#vendor-dashboard)
  - [Admin Dashboard](#admin-dashboard)
  - [Product Management](#product-management)
  - [Vendor Management](#vendor-management)
  - [Homepage CMS](#homepage-cms)
  - [Search & Filter Features](#search--filter-features)
  - [Image & Asset Management](#image--asset-management)
  - [Forgot Password System](#forgot-password-system)
  - [Accessibility & UI](#accessibility--ui)
- [Database Schema](#database-schema)
- [Dependencies](#dependencies)
- [Installation & Setup](#installation--setup)
- [Development & Deployment Notes](#development--deployment-notes)
- [Future Development Plans](#future-development-plans)
- [Legal / Disclaimers](#legal--disclaimers)

---

## Features

### Authentication

- Single login system with role-based redirection (`Vendor`, `Admin`, `Super Admin`)
- Session-based access control using a standardized `Session` class
- Personalized dashboards and logout links based on user role
- Server-side and client-side validation using PHP and JavaScript

---

### Vendor Dashboard

- Register and manage business profile (logo, description, contact info)
- Add, edit, and delete products and price units
- Upload product images with automatic optimization
- Select weekly attendance dates via an interactive Flatpickr calendar
- View selected dates in a read-only dashboard calendar

---

### Admin Dashboard

- Review and approve/reject vendor registrations
- Edit or override vendor attendance and product listings
- Manage homepage content (hero image, hours, contact, announcements)
- Add, suspend, restore, or delete vendor accounts and products

---

### Product Management

**Vendor Tools:**

- Add/edit/delete products with name, description, and category
- Upload product images hosted via Cloudinary
- Assign single or multiple price units as needed (e.g., per pound, per dozen)

**Admin Tools:**

- View and manage all vendor product listings
- Suspend, restore, or edit product details
- Add new products on behalf of vendors

---

### Vendor Management

- Add/edit vendor details
- Approve/reject new vendor applications
- Suspend or delete vendors
- Role-based logic ensures only Admins/Super Admins can perform these actions

---

### Homepage CMS

- Editable via admin panel
  - Market hours, location, contact info
  - Optional announcements
  - Hero image gallery using Cloudinary
- Admins can upload new images or select from existing assets

---

### Search & Filter Features

- Search vendors and products by keyword
- Filter products by vendor and category
- Sort vendors/products alphabetically
- Dynamic filtering with JavaScript, with full PHP fallback
- JavaScript pagination system for public facing pages

---

### Public Marketplace

- Browse all active vendors (with attendance intent set) and their public profiles
- Search/filter functionality on both vendor and product listings
- Full accessible and mobile-friendly display

### Image & Asset Management

- Cloudinary integration for:
  - Product images
  - Vendor logos
  - Vendor profile and homepage hero images
- Client-side image compression using Canvas API
- Automatic resizing and optimization before upload
- Deletion handled securely through the Cloudinary API
- All JS and SCSS assets are compiled and minified for performance

---

### Forgot Password System

- Password reset via time-sensitive token (1 hour)
- Email delivery handled by PHPMailer (SMTP)
- Token validated before allowing password update

### Accessibility & UI

- WCAG 2.1 AA color contrast and keyboard navigation compliance
- Semantic HTML5 and ARIA roles for screen reader support
- Labels and `aria-*` attributes on form fields
- Sticky forms with real-time error/confirmation messaging
- Mobile-first responsive layout (Bootstrap 5)
- Custom 404 page with market-themed messaging

---

## Database Schema

The application uses a fully normalized relational schema in 3NF with:

- Clear one-to-one, one-to-many, and many-to-many relationships
- Foreign key constraints and optimized join tables

**Visual Schema:**
[dbdiagram.io - Village Market Schema](https://dbdiagram.io/d/Village-Market-6779b24a5406798ef74936ae)

**SQL Dump:**
See `sql/village_market.sql`

---

## Dependencies

All libraries and packages are managed via **Composer** and **NPM**.

**Front-End & UI**

- **[Bootstrap (via CDN)](https://getbootstrap.com/)** - layout and responsive utilities
- **[Flatpickr](https://flatpickr.js.org)** - vendor calendar date picker
- **[Sass (SCSS)](https://sass-lang.com/)** - compiled to `main.min.css`
- **[ESBuild](https://esbuild.github.io/)** - minifies and bundles JS to `main.min.js`

**Back-End & Infrastructure**

- **[PHP 8+](https://www.php.net/)** - server-side scripting language used to build all back-end logic
- **[MySQL](https://www.mysql.com/)** - relational database system used to store application data
- **[Composer](https://getcomposer.org/)** - dependency manager for PHP libraries
- **[Dotenv (vlucas/phpdotenv)](https://github.com/vlucas/phpdotenv)** - manages `.env` credentials
- **[PHPMailer](https://github.com/PHPMailer/PHPMailer)** - sends password reset emails via SMTP
- **[Cloudinary SDK](https://cloudinary.com/)** - handles image upload, optimization, and delivery
- **[Google reCAPTCHA v2](https://www.google.com/recaptcha/about/)** - prevents bot submissions on public forms

---

## Installation & Setup

### Prerequisites

- PHP 8+
- MySQL/MariaDB
- Apache (XAMPP preferred)
- Composer & Node.js
- Cloudinary account and API key
- Google reCAPTCHA v2 keys

### Getting Started

1. Ensure XAMPP is installed and running (Apache & MySQL)

2. Clone the repository and move it into htdocs:

```
git clone https://github.com/kmfranklin/village_market.git
```

3. Install dependencies via **Composer**:

```
cd village_market
composer install
```

4. Install Project Dependencies:

```
npm install
```

5. Import village_market.sql using phpMyAdmin (includes CREATE DATABASE and USE DATABASE statements)

6. Configure Cloudinary

- Create a .env file in the root directory
- Add your Cloudinary API credentials:

```
CLOUDINARY_CLOUD_NAME=your_cloud_name
CLOUDINARY_API_KEY=your_api_key
CLOUDINARY_API_SECRET=your_api_secret
```

7. Configure Google reCAPTCHA v2

- Add your reCAPTCHA site and secret keys to your .env file:

```
RECAPTCHA_SITE_KEY=your_site_key
RECAPTCHA_SECRET_KEY=your_secret_key
```

8. Open http://localhost/village_market in a browser to start the application

---

## Development & Deployment Notes

- Minified CSS and JavaScript are not committed to the repository. They must be generated before deployment using the NPM scripts below.

```
npm run sass      # Compiles SCSS to main.min.css
npm run build-js  # Bundles and minifies JS to main.min.js
```

- Environment-specific values (DB, SMTP, Cloudinary) are stored in `.env`
- The app is fully functional on both localhost and live hosting

---

## Future Development Plans

### Authentication Enhancements

- Optional "Remember Me" with token-based auth

### Vendor Tools

- View attendance/product history

### Admin Tools

- Filter vendors by attendance date
- Dashboard alert system for pending market approvals, reminders to update CMS (seasonal announcements, event images, etc.), and more

---

## Legal / Disclaimers

This application was developed as a student project for the WEB-289 Internet Technologies Project course at A-B Tech.

For more information about data use and policies, please refer to the following pages:

- [Terms of Service](https://villagemarkethub.com/terms.php)
- [Privacy Policy](https://villagemarkethub.com/privacy.php)
