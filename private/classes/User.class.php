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
    'username',
    'role_id',
    'hashed_password',
    'registration_date',
    'is_active'
  ];

  public $user_id;
  public $first_name;
  public $last_name;
  public $email_address;
  public $username;
  public $role_id;
  protected $hashed_password;
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
   * @param array $args Associative array of property values.
   */
  public function __construct($args = [])
  {
    $this->first_name = $args['first_name'] ?? '';
    $this->last_name = $args['last_name'] ?? '';
    $this->email_address = $args['email_address'] ?? '';
    $this->username = $args['username'] ?? '';
    $this->role_id = $args['role_id'] ?? self::VENDOR; // Default role is Vendor
    $this->password = $args['password'] ?? '';
    $this->confirm_password = $args['confirm_password'] ?? '';
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
   * @return string The user's full name.
   */
  public function full_name()
  {
    return $this->first_name . " " . $this->last_name;
  }

  /**
   * Hash the user's password and store it in the hashed_password property.
   *
   * @return void
   */
  protected function set_hashed_password()
  {
    $this->hashed_password = password_hash($this->password, PASSWORD_BCRYPT);
  }

  /**
   * Verify if a provided password matches the stored hashed password.
   *
   * @param string $password The plain text password to verify.
   * @return bool True if the password matches, false otherwise.
   */
  public function verify_password($password)
  {
    return password_verify($password, $this->hashed_password);
  }

  /**
   * Create a new user record in the database.
   *
   * @return bool True if the record was created, false otherwise.
   */
  public function create()
  {
    $this->set_hashed_password();
    return parent::create();
  }

  /**
   * Update an existing user record in the database.
   *
   * @return bool True if the record was updated, false otherwise.
   */
  public function update()
  {
    if ($this->password != '') {
      $this->set_hashed_password();
    } else {
      $this->password_required = false;
    }
    return parent::update();
  }

  /**
   * Validate the user's properties.
   *
   * @return array List of validation errors, if any.
   */
  protected function validate()
  {
    $this->errors = [];

    if (is_blank($this->first_name)) {
      $this->errors[] = "First name cannot be blank.";
    } elseif (!has_length($this->first_name, ['min' => 2, 'max' => 255])) {
      $this->errors[] = "First name must be between 2 and 255 characters.";
    }

    if (is_blank($this->last_name)) {
      $this->errors[] = "Last name cannot be blank.";
    } elseif (!has_length($this->last_name, ['min' => 2, 'max' => 255])) {
      $this->errors[] = "Last name must be between 2 and 255 characters.";
    }

    if (is_blank($this->email_address)) {
      $this->errors[] = "Email cannot be blank.";
    } elseif (!has_length($this->email_address, ['max' => 255])) {
      $this->errors[] = "Email must be less than 255 characters.";
    } elseif (!has_valid_email_format($this->email_address)) {
      $this->errors[] = "Email must be a valid format.";
    }

    if (is_blank($this->username)) {
      $this->errors[] = "Username cannot be blank.";
    } elseif (!has_length($this->username, ['min' => 8, 'max' => 255])) {
      $this->errors[] = "Username must be between 8 and 255 characters.";
    } elseif (!has_unique_username($this->username, $this->user_id ?? 0)) {
      $this->errors[] = "Username not allowed. Try another.";
    }

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
        $this->errors[] = "Password must contain at least 1 symbol.";
      }

      if (is_blank($this->confirm_password)) {
        $this->errors[] = "Confirm password cannot be blank.";
      } elseif ($this->password !== $this->confirm_password) {
        $this->errors[] = "Password and confirm password must match.";
      }
    }

    return $this->errors;
  }

  /**
   * Find a user by their username.
   *
   * @param string $username The username to search for.
   * @return User|false The user object if found, false otherwise.
   */
  static public function find_by_username($username)
  {
    $sql = "SELECT * FROM " . static::$table_name . " ";
    $sql .= "WHERE username='" . self::$database->escape_string($username) . "'";
    $obj_array = static::find_by_sql($sql);
    return !empty($obj_array) ? array_shift($obj_array) : false;
  }
}
