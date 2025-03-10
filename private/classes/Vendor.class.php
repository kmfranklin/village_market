<?php

class Vendor extends DatabaseObject
{
  static protected $table_name = "vendor";
  static protected $primary_key = "vendor_id";
  static protected $db_columns = [
    'vendor_id',
    'user_id',
    'business_name',
    'business_description',
    'street_address',
    'city',
    'state_id',
    'zip_code',
    'business_phone_number',
    'business_email_address',
    'business_image_url',
    'business_logo_url',
  ];

  public $vendor_id;
  public $user_id;
  public $business_name;
  public $business_description;
  public $street_address;
  public $city;
  public $state_id;
  public $zip_code;
  public $business_phone_number;
  public $business_email_address;
  public $business_image_url;
  public $business_logo_url;

  public function __construct($args = [])
  {
    $this->user_id = $args['user_id'] ?? '';
    $this->business_name = ucwords(strtolower(trim($args['business_name'] ?? '')));
    $this->business_description = trim($args['business_description'] ?? '');
    $this->street_address = ucwords(strtolower(trim($args['street_address'] ?? '')));
    $this->city = ucwords(strtolower(trim($args['city'] ?? '')));
    $this->state_id = isset($args['state_id']) ? (int) $args['state_id'] : null;
    $this->zip_code = trim($args['zip_code'] ?? '');
    $this->business_phone_number = trim($args['business_phone_number'] ?? '');
    $this->business_email_address = strtolower(trim($args['business_email_address'] ?? ''));
    $this->business_image_url = trim($args['business_image_url'] ?? '');
    $this->business_logo_url = trim($args['business_logo_url'] ?? '');
  }

  public function validate()
  {
    $this->errors = [];

    if (is_blank($this->business_name)) {
      $this->errors[] = "Business name cannot be blank.";
    }

    if (is_blank($this->street_address)) {
      $this->errors[] = "Street address cannot be blank.";
    }

    if (is_blank($this->city)) {
      $this->errors[] = "City cannot be blank.";
    }

    if (is_blank($this->state_id)) {
      $this->errors[] = "State selection is required.";
    }

    if (is_blank($this->zip_code)) {
      $this->errors[] = "ZIP Code cannot be blank.";
    } elseif (!preg_match('/^\d{5}(-\d{4})?$/', $this->zip_code)) {
      $this->errors[] = "ZIP Code must be in a valid format (e.g., 12345 or 12345-6789).";
    }

    if (is_blank($this->business_phone_number)) {
      $this->errors[] = "Business phone number cannot be blank.";
    } elseif (!preg_match('/^\(?\d{3}\)?[-.\s]?\d{3}[-.\s]?\d{4}$/', $this->business_phone_number)) {
      $this->errors[] = "Phone number must be in a valid format (e.g., 555-555-5555).";
    }

    if (is_blank($this->business_email_address)) {
      $this->errors[] = "Business email cannot be blank.";
    } elseif (!has_valid_email_format($this->business_email_address)) {
      $this->errors[] = "Business email must be a valid format.";
    } elseif (self::business_email_exists($this->business_email_address, $this->vendor_id)) {
      $this->errors[] = "Business email already exists.";
    }

    return $this->errors;
  }

  public function merge_attributes($args = [])
  {
    foreach ($args as $key => $value) {
      if (property_exists($this, $key) && $key !== 'user_id') {
        $this->$key = $value;
      }
    }
  }

  public static function find_by_email($email)
  {
    $sql = "SELECT * FROM " . static::$table_name . " WHERE LOWER(business_email_address) = LOWER('" . self::$database->escape_string($email) . "') LIMIT 1";
    $obj_array = static::find_by_sql($sql);
    return !empty($obj_array) ? array_shift($obj_array) : false;
  }

  public static function find_by_user_id($user_id)
  {
    $sql = "SELECT * FROM vendor WHERE user_id = ?";
    $params = [$user_id];
    $result = static::find_by_sql($sql, $params);
    return !empty($result) ? array_shift($result) : false;
  }

  public static function find_vendors_by_status($status)
  {
    return User::find_by_status($status, User::VENDOR);
  }

  public static function business_email_exists($email, $exclude_vendor_id = null)
  {
    $sql = "SELECT * FROM vendor WHERE LOWER(business_email_address) = LOWER('" . self::$database->escape_string($email) . "')";

    if ($exclude_vendor_id) {
      $sql .= " AND vendor_id != '" . self::$database->escape_string($exclude_vendor_id) . "' ";
    }

    $sql .= "LIMIT 1";
    $obj_array = static::find_by_sql($sql);
    return !empty($obj_array) ? array_shift($obj_array) : false;
  }

  public function delete()
  {
    // Start a transaction
    self::$database->begin_transaction();

    try {
      error_log("DEBUG: Attempting to delete Vendor ID: {$this->vendor_id}");

      // Delete all product price unit entries for this vendor’s products
      $price_unit_sql = "DELETE FROM product_price_unit WHERE product_id IN (SELECT product_id FROM product WHERE vendor_id = ?)";
      $price_unit_stmt = self::$database->prepare($price_unit_sql);
      if (!$price_unit_stmt) {
        throw new Exception("Product price unit deletion failed: " . self::$database->error);
      }
      $price_unit_stmt->bind_param("i", $this->vendor_id);
      $price_unit_stmt->execute();
      error_log("DEBUG: Product price units deleted for Vendor ID: {$this->vendor_id}");
      $price_unit_stmt->close();

      // Delete all products owned by this vendor
      $product_sql = "DELETE FROM product WHERE vendor_id = ?";
      $product_stmt = self::$database->prepare($product_sql);
      if (!$product_stmt) {
        throw new Exception("Product deletion failed: " . self::$database->error);
      }
      $product_stmt->bind_param("i", $this->vendor_id);
      $product_stmt->execute();
      error_log("DEBUG: Products deleted for Vendor ID: {$this->vendor_id}");
      $product_stmt->close();

      // Delete the vendor record
      $vendor_sql = "DELETE FROM vendor WHERE vendor_id = ?";
      $vendor_stmt = self::$database->prepare($vendor_sql);
      if (!$vendor_stmt) {
        throw new Exception("Vendor deletion failed: " . self::$database->error);
      }
      $vendor_stmt->bind_param("i", $this->vendor_id);
      $vendor_stmt->execute();
      error_log("DEBUG: Vendor deleted: {$this->vendor_id}");
      $vendor_stmt->close();

      // Finally, delete the associated user account
      $user_sql = "DELETE FROM user WHERE user_id = ?";
      $user_stmt = self::$database->prepare($user_sql);
      if (!$user_stmt) {
        throw new Exception("User deletion failed: " . self::$database->error);
      }
      $user_stmt->bind_param("i", $this->user_id);
      $user_stmt->execute();
      error_log("DEBUG: User deleted for Vendor ID: {$this->vendor_id}, User ID: {$this->user_id}");
      $user_stmt->close();

      // Commit transaction if everything is successful
      self::$database->commit();
      return true;
    } catch (Exception $e) {
      self::$database->rollback(); // Rollback changes if any part fails
      error_log("ERROR: " . $e->getMessage()); // Log the exact error
      return false;
    }
  }
}
