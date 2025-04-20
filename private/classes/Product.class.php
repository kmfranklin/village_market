<?php

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

  /**
   * Retrieve all product categories from the database.
   * 
   * @return array List of categories with keys matching DB columns.
   * @throws Exception If database is not set or query fails.
   */
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

  /**
   * Uploads a product image using ImageUploader (Cloudinary).
   * 
   * @param array $file $_FILES-style associative array.
   * @return bool True if upload was successful, false otherwise.
   */
  public function upload_image($file)
  {
    // Use ImageUploader to upload the image to Cloudinary
    $upload_result = ImageUploader::upload($file, 'product_images/', 'product_');

    if ($upload_result['success']) {
      $this->product_image_url = $upload_result['url']; // Save URL to object
      return [
        'success' => true,
        'message' => "Image uploaded successfully.",
        'url' => $upload_result['url'] // Return it for use in edit.php
      ];
    } else {
      return [
        'success' => false,
        'message' => $upload_result['message']
      ];
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

  public static function build_basic_product_query($options = [])
  {
    global $database;

    $is_admin = $options['is_admin'] ?? false;
    $current_vendor_id = $options['current_vendor_id'] ?? null;
    $search_term = $_GET['search'] ?? '';
    $category_id = $_GET['category_id'] ?? '';
    $vendor_id = $_GET['vendor_id'] ?? '';
    $sort = $_GET['sort'] ?? '';

    $sql = "SELECT p.*, c.category_name, v.business_name
            FROM product p
            JOIN category c ON p.category_id = c.category_id
            JOIN vendor v ON p.vendor_id = v.vendor_id";

    $sql .= " WHERE 1=1";

    if (!$is_admin && $current_vendor_id) {
      $sql .= " AND p.vendor_id = '" . $database->real_escape_string($current_vendor_id) . "'";
    }

    if ($is_admin && !empty($vendor_id)) {
      $sql .= " AND p.vendor_id = '" . $database->real_escape_string($vendor_id) . "'";
    }

    if (!empty($search_term)) {
      $sql .= " AND p.product_name LIKE '%" . $database->real_escape_string($search_term) . "%'";
    }

    if (!empty($category_id)) {
      $sql .= " AND p.category_id = '" . $database->real_escape_string($category_id) . "'";
    }

    switch ($sort) {
      case 'name_asc':
        $sql .= " ORDER BY p.product_name ASC";
        break;
      case 'name_desc':
        $sql .= " ORDER BY p.product_name DESC";
        break;
      default:
        $sql .= " ORDER BY p.product_name ASC";
        break;
    }

    return $sql;
  }
}
