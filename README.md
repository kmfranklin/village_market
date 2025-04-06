# Village Market Web Application

The Village Market Web Application is a full-stack platform designed to support a local farmers' market. Vendors can register their businesses, list their products, and manage attendance. Admins oversee vendor approvals, product listings, and homepage content, while the general public can browse products and vendor info through a searchable, responsive interface.

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

---

## Features

### Authentication

- Single login system with role-based redirection (Vendor, Admin, Super Admin)
- Session-based access control using a standardized `Session` class
- Personalized dashboards and logout links based on user role

---

### Vendor Dashboard

- Submit business profile and upload a logo
- Add, edit, and manage products and pricing
- Select weekly market attendance dates via Flatpickr calendar

---

### Admin Dashboard

- Approve or reject vendor applications
- View and manage all vendors, products, and listings
- Override vendor product listings when necessary
- Manage homepage content (hours, contact, hero image, announcements)
- [In progress] Filter vendors by date and override attendance calendar

---

### Product Management

**Vendors:**

- Add/edit/delete products with names, descriptions, and categories
- Upload product images
- Set pricing per unit (e.g., per pound, dozen)

**Admins:**

- Manage all vendor product listings
- Suspend, restore, or edit product details
- Add new products and assign products to vendors from the admin panel

---

### Vendor Management

- Add vendors from the admin dashboard
- Edit vendor business and contact info
- Approve or reject pending vendor registrations
- Suspend or permanently delete vendor accounts

---

### Homepage CMS

- Edit market location, hours, contact email/phone, and mailing address
- Upload and manage hero images via Cloudinary
- Add optional homepage announcements

---

### Search & Filter Features

- Public-facing product listing supports keyword search, category/vendor filtering, and alphabetical sorting
- Vendor and Admin dashboards support filtering products by category and searching by name
- JavaScript-powered filtering with PHP fallback for accessibility

---

### Image & Asset Management

- Product and vendor images uploaded to Cloudinary
- Images automatically resized and optimized for web delivery
- Secure deletion from Cloudinary when a product is removed
- SCSS compiled with Sass; JS bundled with ESBuild
- All assets minified for faster performance

---

### Forgot Password System

- Password reset via secure, time-limited token (1 hour)
- Email delivery handled by PHPMailer (SMTP)
- Token validated before allowing password update

### Accessibility & UI

- Semantic HTML5 elements for screen reader support
- Proper use of labels, ARIA landmarks, and keyboard-accessible components
- Fully responsive, mobile-first layout using Bootstrap
- Automatic alt text added if not provided by users during image uploads

---

## Database Schema

The application uses a normalized relational schema structured to 3NF. It includes:

- Proper foreign key relationships
- Normalized address and state data
- Bridge tables for flexible product pricing and vendor attendance

View the live diagram:
[dbdiagram.io - Village Market Schema](https://dbdiagram.io/d/Village-Market-6779b24a5406798ef74936ae)

Or refer to `sql/village_market.sql` for the complete schema.

---

## Dependencies

All dependencies are managed via **Composer** and **NPM**.

- **[Bootstrap (via CDN)](https://getbootstrap.com/)** - layout and responsive utilities
- **[Sass (SCSS)](https://sass-lang.com/)** - compiled to `main.min.css`
- **[ESBuild](https://esbuild.github.io/)** - minifies and bundles JS to `main.min.js`
- **[PHPMailer](https://github.com/PHPMailer/PHPMailer)** - sends password reset emails
- **[Dotenv (vlucas/phpdotenv)](https://github.com/vlucas/phpdotenv)** - manages `.env` credentials
- **[Cloudinary](https://cloudinary.com/)** - handles image hosting and optimization
- **[Flatpickr](https://flatpickr.js.org)** - vendor calendar date picker

---

## Installation & Setup

### Prerequisites

- PHP 8+
- MySQL/MariaDB
- Apache (XAMPP preferred)
- Composer (to manage dependencies)
- A Cloudinary account and API key

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

7. Open http://localhost/village_market in a browser to start the application

---

## Development & Deployment Notes

### JavaScript & SCSS Build Process

- **SCSS files** are stored in `assets/scss`, and Sass compiles them into `assets/scss/main.min.css`.
- **JavaScript files** are stored in `assets/scripts`, and ESBuild bundles them into `assets/scripts/main.min.js`.
- The `main.min.css` and `main.min.js` files are not committed to Git; they are generated at build time.
- Run the following before deployment to ensure the latest minified files are included:

  - Compile SCSS:

  ```
  npm run sass
  ```

  - Bundle JS:

  ```
  npm run build-js
  ```

- For deployment, make sure the minified files are uploaded manually or generated on the server.

---

## Future Development Plans

### Authentication Enhancements

- "Remember Me" functionality using secure tokens
- In-dashboard password change support

### Vendor Tools

- Show list of selected attendance dates and products available
- Attendance reminder notification (must select dates available for next month by the last week of current month)

### Admin Tools

- Override vendor attendance data
- Filter vendors by attendance date
- Notifications system (pending vendors, support requests, etc.)

### Public Marketplace

- Filterable vendor directory
- Browse by date to see which vendors will be at the next market
