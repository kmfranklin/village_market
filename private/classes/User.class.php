<?php

class User extends DatabaseObject
{
  static protected $table_name = "user";
  static protected $primary_key = "user_id";

  static protected $db_columns = [
    'user_id',
    'first_name',
    'last_name',
    'email_address',
    'password_hashed',
    'phone_number',
    'role_id',
    'registration_date',
    'account_status'
  ];

  public $user_id;
  public $first_name;
  public $last_name;
  public $email_address;
  public $password_hashed;
  public $phone_number;
  public $role_id;
  public $registration_date;
  public $account_status;

  public $password;
  public $confirm_password;
  protected $password_required = true;

  const VENDOR = 1;
  const ADMIN = 2;
  const SUPER_ADMIN = 3;

  public function __construct($args = [])
  {
    $this->first_name = $args['first_name'] ?? '';
    $this->last_name = $args['last_name'] ?? '';
    $this->email_address = $args['email_address'] ?? '';
    $this->phone_number = $args['phone_number'] ?? '';
    $this->role_id = isset($args['role_id']) ? (int)$args['role_id'] : self::VENDOR;
    $this->password = $args['password'] ?? '';
    $this->confirm_password = $args['confirm_password'] ?? '';
    $this->registration_date = date('Y-m-d H:i:s');
    $this->account_status = $args['account_status'] ?? 'pending';

    if (!empty($this->password)) {
      $this->hash_password();
    }
  }

  public function is_admin()
  {
    return $this->role_id == self::ADMIN;
  }

  public function is_vendor()
  {
    return $this->role_id == self::VENDOR;
  }

  public function is_super_admin()
  {
    return $this->role_id == self::SUPER_ADMIN;
  }

  public function full_name()
  {
    return "{$this->first_name} {$this->last_name}";
  }

  protected function hash_password()
  {
    $this->password_hashed = password_hash($this->password, PASSWORD_BCRYPT);
  }

  public function verify_password($password)
  {
    return password_verify($password, $this->password_hashed);
  }

  public function create()
  {
    $this->apply_formatting();
    $this->hash_password();
    return parent::create();
  }

  public function update()
  {
    $this->apply_formatting();
    if (!empty($this->password)) {
      $this->hash_password();
    }
    return parent::update();
  }

  public function validate()
  {
    $this->errors = [];

    // Trim and sanitize key fields
    $this->first_name = ucwords(strtolower(trim($this->first_name)));
    $this->last_name = ucwords(strtolower(trim($this->last_name)));
    $this->email_address = strtolower(trim($this->email_address));
    $this->phone_number = trim($this->phone_number);

    // First name
    if (is_blank($this->first_name)) {
      $this->errors['first_name'] = "First name cannot be blank.";
    } elseif (!preg_match('/^[a-zA-Z\s\-\'\.]+$/', $this->first_name)) {
      $this->errors['first_name'] = "First name contains invalid characters.";
    }

    // Last name
    if (is_blank($this->last_name)) {
      $this->errors['last_name'] = "Last name cannot be blank.";
    } elseif (!preg_match('/^[a-zA-Z\s\-\'\.]+$/', $this->last_name)) {
      $this->errors['last_name'] = "Last name contains invalid characters.";
    }

    // Email
    if (is_blank($this->email_address)) {
      $this->errors['email_address'] = "Email cannot be blank.";
    } elseif (!has_valid_email_format($this->email_address)) {
      $this->errors['email_address'] = "Email must be a valid format.";
    } elseif ($this->email_exists($this->email_address, $this->user_id)) {
      $this->errors['email_address'] = "This email address is already registered.";
    }

    // Phone number (US-style format check)
    if (is_blank($this->phone_number)) {
      $this->errors['phone_number'] = "Phone number cannot be blank.";
    } elseif (!preg_match('/^\(?\d{3}\)?[-.\s]?\d{3}[-.\s]?\d{4}$/', $this->phone_number)) {
      $this->errors['phone_number'] = "Phone number must be a valid format (XXXXXXXXXX or XXX-XXX-XXXX).";
    }

    // Passwords are required for new users
    if (empty($this->user_id)) {
      if (is_blank($this->password)) {
        $this->errors['password'] = "Password cannot be blank.";
      } elseif (!has_length($this->password, ['min' => 12])) {
        $this->errors['password'] = "Password must contain at least 12 characters.";
      } elseif (!preg_match('/[A-Z]/', $this->password)) {
        $this->errors['password'] = "Password must contain at least 1 uppercase letter.";
      } elseif (!preg_match('/[a-z]/', $this->password)) {
        $this->errors['password'] = "Password must contain at least 1 lowercase letter.";
      } elseif (!preg_match('/[0-9]/', $this->password)) {
        $this->errors['password'] = "Password must contain at least 1 number.";
      } elseif (!preg_match('/[^A-Za-z0-9\s]/', $this->password)) {
        $this->errors['password'] = "Password must contain at least 1 special character.";
      }

      if (is_blank($this->confirm_password)) {
        $this->errors['confirm_password'] = "Confirm password cannot be blank.";
      } elseif ($this->password !== $this->confirm_password) {
        $this->errors['confirm_password'] = "Password and confirm password must match.";
      }
    }

    // If password is set during edit, validate it
    if (!empty($this->user_id) && !empty($this->password)) {
      if (!has_length($this->password, ['min' => 12])) {
        $this->errors['password'] = "Password must contain at least 12 characters.";
      } elseif (!preg_match('/[A-Z]/', $this->password)) {
        $this->errors['password'] = "Password must contain at least 1 uppercase letter.";
      } elseif (!preg_match('/[a-z]/', $this->password)) {
        $this->errors['password'] = "Password must contain at least 1 lowercase letter.";
      } elseif (!preg_match('/[0-9]/', $this->password)) {
        $this->errors['password'] = "Password must contain at least 1 number.";
      } elseif (!preg_match('/[^A-Za-z0-9\s]/', $this->password)) {
        $this->errors['password'] = "Password must contain at least 1 special character.";
      }

      if (is_blank($this->confirm_password)) {
        $this->errors['confirm_password'] = "Confirm password cannot be blank.";
      } elseif ($this->password !== $this->confirm_password) {
        $this->errors['confirm_password'] = "Password and confirm password must match.";
      }
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

  public static function find_by_id($user_id)
  {
    if (is_null($user_id)) {
      return false;
    }

    $sql = "SELECT * FROM " . static::$table_name . " ";
    $sql .= "WHERE user_id = '" . self::$database->escape_string($user_id) . "' ";
    $sql .= "LIMIT 1";
    $obj_array = static::find_by_sql($sql);
    return !empty($obj_array) ? array_shift($obj_array) : false;
  }

  static public function find_by_email($email)
  {
    $sql = "SELECT * FROM " . static::$table_name . " ";
    $sql .= "WHERE LOWER(email_address) = LOWER('" . self::$database->escape_string($email) . "') ";
    $sql .= "LIMIT 1";
    $obj_array = static::find_by_sql($sql);
    return !empty($obj_array) ? array_shift($obj_array) : false;
  }

  /**
   * Finds users by account status, optionally filtering by role
   */
  public static function find_by_status($status, $role_id = null)
  {
    $sql = "SELECT * FROM " . static::$table_name . " ";
    $sql .= "WHERE account_status = '" . self::$database->escape_string($status) . "'";

    if (!is_null($role_id)) {
      $sql .= " AND role_id = " . self::$database->escape_string($role_id) . " ";
    }
    return static::find_by_sql($sql);
  }

  /**
   * Approves a vendor by changing their status to active
   */
  public function approve_vendor()
  {
    if ($this->role_id == self::VENDOR && $this->account_status == 'pending') {
      $this->account_status = 'active';
      $this->password_required = false;
      return $this->update();
    }
    return false;
  }

  /**
   * Rejects a vendor by changing their status to rejected
   */
  public function reject_vendor()
  {
    if ($this->role_id == self::VENDOR && $this->account_status == 'pending') {
      $this->account_status = 'rejected';
      $this->password_required = false;
      return $this->update();
    }
    return false;
  }

  /**
   * Suspends a vendor by changing their status to 'suspended'
   */
  public function suspend_vendor()
  {
    if ($this->role_id == self::VENDOR && $this->account_status == 'active') {
      $this->account_status = 'suspended';
      return $this->update();
    }
    return false;
  }

  /**
   * Restores a suspended vendor by changing their status back to 'active'
   */
  public function restore_vendor()
  {
    if ($this->role_id == self::VENDOR && $this->account_status == 'suspended') {
      $this->account_status = 'active';
      return $this->update();
    }
    return false;
  }

  public static function email_exists($email, $exclude_user_id = null)
  {
    $sql = "SELECT * FROM " . static::$table_name . " ";
    $sql .= "WHERE LOWER(email_address) = LOWER('" . self::$database->escape_string($email) . "') ";

    if ($exclude_user_id) {
      $sql .= "AND user_id != '" . self::$database->escape_string($exclude_user_id) . "' ";
    }

    $sql .= "LIMIT 1";
    $obj_array = static::find_by_sql($sql);
    return !empty($obj_array) ? array_shift($obj_array) : false;
  }

  /**
   * Normalizes phone number to XXXXXXXXXX format before storing.
   */
  private function normalize_phone_number($phone)
  {
    return preg_replace('/\D/', '', $phone);
  }

  /**
   * Applies formatting to text fields before saving.
   */
  private function apply_formatting()
  {
    $this->first_name = ucwords(strtolower(trim($this->first_name)));
    $this->last_name = ucwords(strtolower(trim($this->last_name)));
    $this->email_address = strtolower(trim($this->email_address));
    $this->phone_number = $this->normalize_phone_number($this->phone_number);
  }
}
