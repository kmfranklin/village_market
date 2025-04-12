<?php

require_once __DIR__ . '/../libraries/vendor/autoload.php';

use Cloudinary\Configuration\Configuration;
use Dotenv\Dotenv;

// Load environment variables from .env file
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Cloudinary configuration
Configuration::instance([
  'cloud_name' => $_ENV['CLOUDINARY_CLOUD_NAME'],
  'api_key'    => $_ENV['CLOUDINARY_API_KEY'],
  'api_secret' => $_ENV['CLOUDINARY_API_SECRET'],
  'secure'     => true
]);

// reCAPTCHA configuration
define('RECAPTCHA_SITE_KEY', $_ENV['RECAPTCHA_SITE_KEY']);
define('RECAPTCHA_SECRET_KEY', $_ENV['RECAPTCHA_SECRET_KEY']);
