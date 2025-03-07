<?php

class MarketManager
{
  /**
   * Uploads a hero image using the ImageUploader class.
   *
   * @param array $file The uploaded file from $_FILES
   * @return array Success status and secure URL or error message
   */
  public static function upload_hero_image($file)
  {
    $upload_result = ImageUploader::upload($file, 'hero_images/', 'hero_');

    if ($upload_result['success']) {
      return [
        'success' => true,
        'url' => $upload_result['url'] // Return URL for database insertion
      ];
    } else {
      return [
        'success' => false,
        'message' => $upload_result['message']
      ];
    }
  }
}
