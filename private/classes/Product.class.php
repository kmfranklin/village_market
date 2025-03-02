<?php

use Cloudinary\Api\Upload\UploadApi;

class Product extends DatabaseObject
{
  static protected $table_name = "product";
  static protected $primary_key = "product_id";

  static protected $db_columns = [
    'product_id',
    'product_name',
    'product_description',
    'vendor_id',
    'product_image_url',
    'date_added',
    'is_active',
    'category_id'
  ];

  public $product_id;
  public $product_name;
  public $product_description;
  public $vendor_id;
  public $product_image_url;
  public $date_added;
  public $is_active;
  public $category_id;

  public function __construct($args = [])
  {
    $this->product_name = $args['product_name'] ?? '';
    $this->product_description = $args['product_description'] ?? '';
    $this->vendor_id = $args['vendor_id'] ?? '';
    $this->product_image_url = $args['product_image_url'] ?? '';
    $this->date_added = $args['date_added'] ?? date('Y-m-d H:i:s');
    $this->is_active = $args['is_active'] ?? 1;
    $this->category_id = $args['category_id'] ?? '';
  }

  /**
   * Get all products for a specific vendor.
   *
   * @param int $vendor_id The vendor's user ID.
   * @return array List of products.
   */

  static public function find_by_vendor($vendor_id)
  {
    $sql = "SELECT * FROM " . static::$table_name . " WHERE vendor_id = ?";
    return static::find_by_sql($sql, [$vendor_id]);
  }

  /**
   * Validate product input before saving.
   *
   * @return array List of validation errors.
   */

  protected function validate()
  {
    $this->errors = [];

    if (empty($this->product_name)) {
      $this->errors[] = "Product name cannot be blank.";
    }

    if (empty($this->product_description)) {
      $this->errors[] = "Product description cannot be blank.";
    }

    if (!is_numeric($this->vendor_id)) {
      $this->errors[] = "Invalid vendor ID.";
    }

    return $this->errors;
  }

  public static function get_categories()
  {
    if (!isset(self::$database)) {
      throw new Exception("Database connection is not set.");
    }

    $sql = "SELECT * FROM category ORDER BY category_name ASC";
    $stmt = self::$database->prepare($sql);

    if (!$stmt) {
      throw new Exception("Database query failed: " . self::$database->error);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result) {
      return [];
    }

    $categories = [];
    while ($row = $result->fetch_assoc()) {
      $categories[] = $row;
    }

    $stmt->close();
    return $categories;
  }

  /**
   * Get the category name for this product.
   *
   * @return string The category name or 'Unknown' if not found.
   */
  public function get_category_name()
  {
    $category = Category::find_by_id($this->category_id);
    return $category ? $category->category_name : 'Unknown';
  }



  public function upload_image($file)
  {
    require_once PRIVATE_PATH . '/cloudinary_config.php';

    $allowed_types = ['jpg', 'jpeg', 'png'];
    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($file_ext, $allowed_types)) {
      return ['success' => false, 'message' => "Invalid file type. Only JPG and PNG allowed."];
    }

    try {
      // Upload to Cloudinary
      $upload = (new UploadApi())->upload($file['tmp_name'], [
        'folder' => 'product_images/',
        'public_id' => uniqid('product_'),
        'overwrite' => true
      ]);

      // Save URL to database
      $this->product_image_url = $upload['secure_url'];

      return ['success' => true, 'message' => "Image uploaded successfully."];
    } catch (Exception $e) {
      return ['success' => false, 'message' => "Cloudinary error: " . $e->getMessage()];
    }
  }



  public function delete()
  {
    global $database;

    // Delete any related price units (product_price_unit)
    $sql = "DELETE FROM product_price_unit WHERE product_id = ?";
    $stmt = $database->prepare($sql);
    if (!$stmt) {
      error_log("ERROR: Failed to prepare price unit deletion statement.");
      return false;
    }
    $stmt->bind_param("i", $this->product_id);
    if (!$stmt->execute()) {
      error_log("ERROR: Failed to delete price units for product ID {$this->product_id}");
      return false;
    }
    $stmt->close();

    // Delete the product itself
    $sql = "DELETE FROM product WHERE product_id = ?";
    $stmt = $database->prepare($sql);
    if (!$stmt) {
      error_log("ERROR: Failed to prepare product deletion statement.");
      return false;
    }
    $stmt->bind_param("i", $this->product_id);
    if ($stmt->execute()) {
      $stmt->close();
      return true;
    } else {
      error_log("ERROR: Could not delete product ID {$this->product_id}");
      return false;
    }
  }
}
