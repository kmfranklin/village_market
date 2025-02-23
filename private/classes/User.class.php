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
    $this->hash_password();
    return parent::create();
  }

  public function update()
  {
    if (!empty($this->password)) {
      $this->hash_password();
    }
    return parent::update();
  }

  public function validate()
  {
    $this->errors = [];

    if (is_blank($this->first_name)) {
      $this->errors[] = "First name cannot be blank.";
    }

    if (is_blank($this->last_name)) {
      $this->errors[] = "Last name cannot be blank.";
    }

    if (is_blank($this->email_address)) {
      $this->errors[] = "Email cannot be blank.";
    } elseif (!has_valid_email_format($this->email_address)) {
      $this->errors[] = "Email must be a valid format.";
    } elseif ($this->email_exists($this->email_address, $this->user_id)) {
      $this->errors[] = "This email address is already registered.";
    }

    if ($this->password_required && empty($this->user_id)) {
      if (is_blank($this->password)) {
        $this->errors[] = "Password cannot be blank.";
      } elseif (!has_length($this->password, ['min' => 12])) {
        $this->errors[] = "Password must contain at least 12 characters.";
      } elseif (!preg_match('/[A-Z]/', $this->password)) {
        $this->errors[] = "Password must contain at least 1 uppercase letter.";
      } elseif (!preg_match('/[a-z]/', $this->password)) {
        $this->errors[] = "Password must contain at least 1 lowercase letter.";
      } elseif (!preg_match('/[0-9]/', $this->password)) {
        $this->errors[] = "Password must contain at least 1 number.";
      } elseif (!preg_match('/[^A-Za-z0-9\s]/', $this->password)) {
        $this->errors[] = "Password must contain at least 1 special character.";
      }
      if (is_blank($this->confirm_password)) {
        $this->errors[] = "Confirm password cannot be blank.";
      } elseif ($this->password !== $this->confirm_password) {
        $this->errors[] = "Password and confirm password must match.";
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
}
