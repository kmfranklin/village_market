<?php

/**
 * Class User
 *
 * Represents a user in the Farmers Market application, including vendors, admins, and super admins.
 *
 * @package FarmersMarket
 */
class User extends DatabaseObject
{
  static protected $table_name = "user";
  static protected $db_columns = [
    'user_id',
    'first_name',
    'last_name',
    'email_address',
    'password_hashed',
    'phone_number',
    'role_id',
    'registration_date',
    'is_active'
  ];

  public $user_id;
  public $first_name;
  public $last_name;
  public $email_address;
  public $password_hashed;
  public $phone_number;
  protected $role_id;
  public $registration_date;
  public $is_active;

  public $password;
  public $confirm_password;
  protected $password_required = true;

  // Role constants
  const VENDOR = 1;
  const ADMIN = 2;
  const SUPER_ADMIN = 3;

  /**
   * User constructor.
   *
   * Initializes a new user object with optional property values.
   *
   * @param array $args Associative array of property values.
   */
  public function __construct($args = [])
  {
    $this->first_name = $args['first_name'] ?? '';
    $this->last_name = $args['last_name'] ?? '';
    $this->email_address = $args['email_address'] ?? '';
    $this->phone_number = $args['phone_number'] ?? '';
    $this->role_id = $args['role_id'] ?? self::VENDOR; // Default role: Vendor
    $this->password = $args['password'] ?? '';
    $this->confirm_password = $args['confirm_password'] ?? '';
    $this->is_active = $args['is_active'] ?? 1; // Default: active
  }

  /**
   * Check if the user is an Admin.
   *
   * @return bool True if the user is an Admin, false otherwise.
   */
  public function is_admin()
  {
    return $this->role_id == self::ADMIN;
  }

  /**
   * Check if the user is a Vendor.
   *
   * @return bool True if the user is a Vendor, false otherwise.
   */
  public function is_vendor()
  {
    return $this->role_id == self::VENDOR;
  }

  /**
   * Check if the user is a Super Admin.
   *
   * @return bool True if the user is a Super Admin, false otherwise.
   */
  public function is_super_admin()
  {
    return $this->role_id == self::SUPER_ADMIN;
  }

  /**
   * Get the user's full name.
   *
   * @return string The user's full name in "First Last" format.
   */
  public function full_name()
  {
    return $this->first_name . " " . $this->last_name;
  }

  /**
   * Hash the user's password and store it in the password_hashed property.
   *
   * @return void
   */
  protected function set_hashed_password()
  {
    $this->password_hashed = password_hash($this->password, PASSWORD_BCRYPT);
  }

  /**
   * Verify if a provided password matches the stored hashed password.
   *
   * @param string $password The plain-text password to verify.
   * @return bool True if the password matches, false otherwise.
   */
  public function verify_password($password)
  {
    return password_verify($password, $this->password_hashed);
  }

  /**
   * Create a new user record in the database.
   *
   * Hashes the password before creating the user.
   *
   * @return bool True if the record was created successfully, false otherwise.
   */
  public function create()
  {
    $this->set_hashed_password();
    return parent::create();
  }

  /**
   * Update an existing user record in the database.
   *
   * Updates the password hash only if a new password is provided.
   *
   * @return bool True if the record was updated successfully, false otherwise.
   */
  public function update()
  {
    if (!empty($this->password)) {
      $this->set_hashed_password();
    } else {
      $this->password_required = false;
    }
    return parent::update();
  }

  /**
   * Validate the user's properties.
   *
   * Performs validation checks for required fields and formats.
   *
   * @return array List of validation errors, if any.
   */
  protected function validate()
  {
    $this->errors = [];

    // Validate First Name
    if (is_blank($this->first_name)) {
      $this->errors[] = "First name cannot be blank.";
    }

    // Validate Last Name
    if (is_blank($this->last_name)) {
      $this->errors[] = "Last name cannot be blank.";
    }

    // Validate Email Address
    if (is_blank($this->email_address)) {
      $this->errors[] = "Email cannot be blank.";
    } elseif (!has_valid_email_format($this->email_address)) {
      $this->errors[] = "Email must be a valid format.";
    }

    // Validate Password (if required)
    if ($this->password_required) {
      if (is_blank($this->password)) {
        $this->errors[] = "Password cannot be blank.";
      } elseif (!has_length($this->password, ['min' => 12])) {
        $this->errors[] = "Password must contain 12 or more characters.";
      } elseif (!preg_match('/[A-Z]/', $this->password)) {
        $this->errors[] = "Password must contain at least 1 uppercase letter.";
      } elseif (!preg_match('/[a-z]/', $this->password)) {
        $this->errors[] = "Password must contain at least 1 lowercase letter.";
      } elseif (!preg_match('/[0-9]/', $this->password)) {
        $this->errors[] = "Password must contain at least 1 number.";
      } elseif (!preg_match('/[^A-Za-z0-9\s]/', $this->password)) {
        $this->errors[] = "Password must contain at least 1 special character.";
      }

      // Validate Confirm Password
      if (is_blank($this->confirm_password)) {
        $this->errors[] = "Confirm password cannot be blank.";
      } elseif ($this->password !== $this->confirm_password) {
        $this->errors[] = "Password and confirm password must match.";
      }
    }

    return $this->errors;
  }

  /**
   * Find a user by their email address.
   *
   * @param string $email The email address to search for.
   * @return User|false The user object if found, false otherwise.
   */
  static public function find_by_email($email)
  {
    $sql = "SELECT * FROM " . static::$table_name . " ";
    $sql .= "WHERE email_address='" . self::$database->escape_string($email) . "' LIMIT 1";
    $obj_array = static::find_by_sql($sql);
    return !empty($obj_array) ? array_shift($obj_array) : false;
  }
}
