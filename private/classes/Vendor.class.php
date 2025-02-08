<?php

class Vendor extends DatabaseObject
{
  static protected $table_name = "vendor";
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
    'is_active'
  ];

  protected $vendor_id;
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
  public $is_active;

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
    $this->is_active = $args['is_active'] ?? 1;
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
    } elseif (self::find_by_email($this->business_email_address)) {
      $this->errors[] = "Business email already exists.";
    }

    return $this->errors;
  }

  public static function find_pending_vendors()
  {
    $sql = "SELECT * FROM " . static::$table_name . " WHERE is_active = 0";
    return static::find_by_sql($sql);
  }

  public static function find_by_email($email)
  {
    $sql = "SELECT * FROM " . static::$table_name . " WHERE LOWER(business_email_address) = LOWER('" . self::$database->escape_string($email) . "') LIMIT 1";
    $obj_array = static::find_by_sql($sql);
    return !empty($obj_array) ? array_shift($obj_array) : false;
  }
}
