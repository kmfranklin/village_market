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
    'show_email',
    'show_phone',
    'show_address',
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
  public $show_email;
  public $show_phone;
  public $show_address;

  public function __construct($args = [])
  {
    $this->user_id = isset($args['user_id']) ? (int) $args['user_id'] : null;
    $this->business_name = $args['business_name'] ?? '';
    $this->business_description = trim($args['business_description'] ?? '');
    $this->street_address = $args['street_address'] ?? '';
    $this->city = $this->format_text($args['city'] ?? '');
    $this->state_id = isset($args['state_id']) ? (int) $args['state_id'] : null;
    $this->zip_code = trim($args['zip_code'] ?? '');
    $this->business_phone_number = trim($args['business_phone_number'] ?? '');
    $this->business_email_address = strtolower(trim($args['business_email_address'] ?? ''));
    $this->business_image_url = trim($args['business_image_url'] ?? '');
    $this->business_logo_url = trim($args['business_logo_url'] ?? '');
    $this->show_email = isset($args['show_email']) ? (int) $args['show_email'] : 0;
    $this->show_phone = isset($args['show_phone']) ? (int) $args['show_phone'] : 0;
    $this->show_address = isset($args['show_address']) ? (int) $args['show_address'] : 0;
  }

  public function validate()
  {
    $this->errors = [];

    if (is_blank($this->business_name)) {
      $this->errors['business_name'] = "Business name cannot be blank.";
    }

    if (is_blank($this->street_address)) {
      $this->errors['street_address'] = "Street address cannot be blank.";
    }

    if (is_blank($this->city)) {
      $this->errors['city'] = "City cannot be blank.";
    }

    if (is_blank($this->state_id)) {
      $this->errors['state_id'] = "State selection is required.";
    }

    if (is_blank($this->zip_code)) {
      $this->errors['zip_code'] = "ZIP Code cannot be blank.";
    } elseif (!preg_match('/^\d{5}(-\d{4})?$/', $this->zip_code)) {
      $this->errors['zip_code'] = "ZIP Code must be in a valid format (e.g., 12345 or 12345-6789).";
    }

    if (is_blank($this->business_phone_number)) {
      $this->errors['business_phone_number'] = "Business phone number cannot be blank.";
    } elseif (!preg_match('/^\d{10}$|^\d{3}-\d{3}-\d{4}$/', $this->business_phone_number)) {
      $this->errors['business_phone_number'] = "Phone number must be in a valid format (XXXXXXXXXX or XXX-XXX-XXXX).";
    }

    if (is_blank($this->business_email_address)) {
      $this->errors['business_email_address'] = "Business email cannot be blank.";
    } elseif (!has_valid_email_format($this->business_email_address)) {
      $this->errors['business_email_address'] = "Business email must be a valid format.";
    } elseif (self::business_email_exists($this->business_email_address, $this->vendor_id)) {
      $this->errors['business_email_address'] = "Business email already exists.";
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

  /**
   * Finds a vendor by their associated user ID.
   * 
   * @param int $user_id The user ID associated with the vendor.
   * @return Vendor|false Returns the vendor object if found, false otherwise.
   */
  public static function find_by_user_id($user_id)
  {
    $sql = "SELECT * FROM vendor WHERE user_id = ?";
    $params = [$user_id];
    $result = static::find_by_sql($sql, $params);
    return !empty($result) ? array_shift($result) : false;
  }

  /**
   * Retrieves all vendors with a specific account status.
   * 
   * @param string $status Account status to filter by (e.g., 'active', 'suspended').
   * @return array List of vendor User objects.
   */
  public static function find_vendors_by_status($status)
  {
    return User::find_by_status($status, User::VENDOR);
  }

  /**
   * Checks if a vendor email already exists in the database.
   * 
   * @param string $email The business email to check.
   * @param int|null $exclude_vendor_id Optional ID to exclude from the check.
   * @return Vendor|false Returns the vendor object if found, false otherwise.
   */
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

  /**
   * Deletes this vendor and all associated records:
   * - product_price_unit entries (via product_id)
   * - products
   * - market attendance
   * - vendor record
   * - user record
   *
   * @return bool True on success, false if any step fails.
   */
  public function delete()
  {
    self::$database->begin_transaction();

    try {
      $price_unit_sql = "
        DELETE FROM product_price_unit 
        WHERE product_id IN (
          SELECT product_id FROM product WHERE vendor_id = ?
        )
      ";
      $price_unit_stmt = self::$database->prepare($price_unit_sql);
      $price_unit_stmt->bind_param("i", $this->vendor_id);
      $price_unit_stmt->execute();
      $price_unit_stmt->close();

      $product_sql = "DELETE FROM product WHERE vendor_id = ?";
      $product_stmt = self::$database->prepare($product_sql);
      $product_stmt->bind_param("i", $this->vendor_id);
      $product_stmt->execute();
      $product_stmt->close();

      $attendance_sql = "DELETE FROM market_attendance WHERE vendor_id = ?";
      $attendance_stmt = self::$database->prepare($attendance_sql);
      $attendance_stmt->bind_param("i", $this->vendor_id);
      $attendance_stmt->execute();
      $attendance_stmt->close();

      $vendor_sql = "DELETE FROM vendor WHERE vendor_id = ?";
      $vendor_stmt = self::$database->prepare($vendor_sql);
      $vendor_stmt->bind_param("i", $this->vendor_id);
      $vendor_stmt->execute();
      $vendor_stmt->close();

      $user_sql = "DELETE FROM user WHERE user_id = ?";
      $user_stmt = self::$database->prepare($user_sql);
      $user_stmt->bind_param("i", $this->user_id);
      $user_stmt->execute();
      $user_stmt->close();

      self::$database->commit();
      return true;
    } catch (Exception $e) {
      self::$database->rollback();
      return false;
    }
  }

  public function create()
  {
    $this->apply_formatting();
    return parent::create();
  }

  public function update()
  {
    $this->apply_formatting();
    return parent::update();
  }

  /**
   * Apply formatting to text fields before saving to the database.
   */
  private function apply_formatting()
  {
    $this->business_name = $this->format_text(stripslashes($this->business_name));
    $this->street_address = $this->format_text(stripslashes($this->street_address));
    $this->city = $this->format_text($this->city);
    $this->business_phone_number = $this->normalize_phone_number($this->business_phone_number);
  }

  /**
   * Normalizes phone number to XXXXXXXXXX format before storing.
   */
  private function normalize_phone_number($phone)
  {
    // Remove all non-numeric characters
    return preg_replace('/\D/', '', $phone);
  }

  /**
   * Formats text to title case while keeping valid capitalization.
   */
  private function format_text($string)
  {
    return mb_strtoupper(mb_substr($string, 0, 1)) . mb_substr($string, 1);
  }

  /**
   * Formats a numeric phone number (XXXXXXXXXX) into XXX-XXX-XXXX.
   *
   * @param string $number Raw numeric phone string (10 digits).
   * @return string Formatted phone number or original if invalid.
   */
  public static function format_phone($number)
  {
    $number = preg_replace('/\D/', '', $number); // Just in case
    if (strlen($number) === 10) {
      return substr($number, 0, 3) . '-' .
        substr($number, 3, 3) . '-' .
        substr($number, 6);
    }
    return $number; // fallback if not 10 digits
  }
}
