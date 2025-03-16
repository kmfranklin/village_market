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

### Product Management System

**Vendors:**

- **Add Products** - Vendors can add new products with images, descriptions, categories, and pricing
- **Edit Products** - Modify existing product details, including images and availability status
- **Delete Products** - Remove products they no longer sell
- **Manage Pricing and Units** - Vendors can specify product prices per unit (e.g., per pound, per dozen)

**Admins:**

- **Manage All Products** - Admins can view, edit, and delete products from any vendor
- **Add Products** - Admins can add new products, and assign them to specific vendors via dropdown menu
- **Suspend or Restore Products** - Control availability of products at the market
- **Override Vendor Listings** - Admins can update incorrect product details when necessary

### Vendor Management System (Admin Feature)

- **Add Vendors** – Admins can register vendors from the Admin panel
- **Edit Vendor Information** – Business details and contact information can be updated
- **Approve or Reject Vendor Registrations** – Control access to the system
- **Suspend Vendors** – Temporarily limit access to the application
- **Restore Vendors** – Reactivate suspended vendors or permanently delete them

### Homepage Management CMS (Admin Feature)

- **Market Info** - Admins can edit information about the market, such as hours and contact info
- **Hero Images** - Add and/or change the hero image displayed on the homepage
- **Announcements** - Optional announcement to be displayed on the homepage

### Cloudinary Integration for Image Management

- **Image Uploads** - Product images are stored securely using Cloudinary
- **Automatic Image Optimization** - Images are automatically resized and optimized for web delivery
- **Cloud Storage Delivery** - No local storage required, reducing server load
- **Secure Deletion** - Removing a product also removes its image from Cloudinary

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

- **[Bootstrap (via CDN)](https://getbootstrap.com/)**

  - Provides responsive grid layouts, buttons, form styles, and modal functionality.

- **[Sass (SCSS)](https://sass-lang.com/)**

  - All custom styles written in SCSS and compiled into a single minified CSS file.
  - Uses `sass` to watch and compile SCSS into `public/assets/scss/main.min.css`.

- **[PHPMailer](https://github.com/PHPMailer/PHPMailer)**

  - Sends password reset emails securely.
  - Configured with SMTP authentication.

- **[Dotenv (vlucas/phpdotenv)](https://github.com/vlucas/phpdotenv)**

  - Stores and loads environment variables securely.
  - Keeps SMTP credentials and database secrets out of version control.

- **[Cloudinary](https://cloudinary.com/)**

  - Handles image uploads, transformations, and delivery

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
- A Cloudinary account and API key

### Getting Started

1. Ensure XAMPP is installed and running (Apache & MySQL)

2. Clone the repository and move it into `htdocs`:

```
git clone https://github.com/kmfranklin/village_market.git
```

3. Install dependencies via **Composer**:

```
cd village_market
composer install
```

4. Install **Sass (SCSS) Compiler**:

```
npm install
```

5. Compile SCSS into a minified CSS file:

```
npm run sass
```

To automatically watch for SCSS changes while developing:

```
npm run sass:watch
```

6. Import `village_market.sql` using phpMyAdmin (includes `CREATE DATABASE` and `USE DATABASE` statements)

7. Configure Cloudinary

- Create a `.env` file in the root directory
- Add your Cloudinary API credentials:

```
CLOUDINARY_CLOUD_NAME=your_cloud_name
CLOUDINARY_API_KEY=your_api_key
CLOUDINARY_API_SECRET=your_api_secret
```

8. Open `http://localhost/village_market` in a browser to start the application

---

## Future Development Plans

### Authentication Enhancements

- "Remember Me" functionality with secure authentication tokens
- Change Password option for logged-in users

### Vendor Dashboard

- Business profile management
- Market attendance tracking

### Admin Dashboard

- Ability to manage users, vendors, and products
- Homepage content management system (CMS)

### Public Marketplace

- Filterable and searchable list of vendors and products
- Open to general public for browsing market details
