<?php

require_once PRIVATE_PATH . '/config.php';

use Cloudinary\Api\Upload\UploadApi;

class ImageUploader
{
  /**
   * Upload an image to Cloudinary.
   *
   * @param array $file The uploaded file array from $_FILES.
   * @param string $folder The target folder in Cloudinary (e.g., 'product_images/', 'hero_images/').
   * @param string $prefix The prefix for the public ID (e.g., 'product_', 'hero_').
   * @return array Result of the upload with success status and message.
   */
  public static function upload($file, $folder, $prefix)
  {
    $allowed_types = ['jpg', 'jpeg', 'png'];
    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($file_ext, $allowed_types)) {
      return ['success' => false, 'message' => "Invalid file type. Only JPG and PNG allowed."];
    }

    try {
      // Upload to Cloudinary
      $upload = (new UploadApi())->upload($file['tmp_name'], [
        'folder' => $folder,
        'public_id' => uniqid($prefix),
        'overwrite' => true
      ]);

      return ['success' => true, 'url' => $upload['secure_url']];
    } catch (Exception $e) {
      return ['success' => false, 'message' => "Cloudinary error: " . $e->getMessage()];
    }
  }
}
