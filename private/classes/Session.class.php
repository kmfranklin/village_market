<?php

/**
 * Session management class.
 *
 * Handles user authentication, session data, and role-based access control.
 * Supports vendors and admins, distinguishing them using `role_id`.
 *
 * @package FarmersMarket
 */
class Session
{
  /**
   * The ID of the logged-in user.
   * @var int|null
   */
  private $user_id;

  /**
   * The username of the logged-in user.
   * @var string|null
   */
  public $username;

  /**
   * The timestamp of the last login.
   * @var int|null
   */
  private $last_login;

  /**
   * The role ID of the logged-in user (1 = vendor, 2 = admin, etc.).
   * @var int|null
   */
  public $role_id;

  /**
   * The first name of the logged-in user.
   * @var string|null
   */
  public $first_name;

  /**
   * Maximum login session duration (24 hours).
   */
  public const MAX_LOGIN_AGE = 86400;

  /**
   * Start the session and check stored login details.
   */
  public function __construct()
  {
    session_start();
    $this->check_stored_login();
  }

  /**
   * Log in a user by storing session variables.
   *
   * @param object $user User object (Vendor or Admin)
   * @return bool True on success
   */
  public function login($user)
  {
    if ($user) {
      session_regenerate_id();
      $this->user_id = $_SESSION['user_id'] = $user->user_id;
      $this->username = $_SESSION['username'] = $user->username;
      $this->last_login = $_SESSION['last_login'] = time();
      $this->role_id = $_SESSION['role_id'] = $user->role_id;
      $this->first_name = $_SESSION['first_name'] = $user->first_name;
    }
    return true;
  }

  /**
   * Check if a user is logged in.
   *
   * @return bool True if logged in, false otherwise.
   */
  public function is_logged_in()
  {
    return isset($this->user_id) && $this->last_login_is_recent();
  }

  /**
   * Log out the current user by unsetting session variables.
   *
   * @return bool True on success.
   */
  public function logout()
  {
    unset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['last_login'], $_SESSION['role_id']);
    unset($this->user_id, $this->username, $this->last_login, $this->role_id);
    return true;
  }

  /**
   * Restore session variables from stored session data.
   */
  private function check_stored_login()
  {
    if (isset($_SESSION['user_id'])) {
      $this->user_id = $_SESSION['user_id'];
      $this->username = $_SESSION['username'];
      $this->last_login = $_SESSION['last_login'];
      $this->role_id = $_SESSION['role_id'];
      $this->first_name = $_SESSION['first_name'];
    }
  }

  /**
   * Check if the last login timestamp is within the allowed session duration.
   *
   * @return bool True if session is still valid, false if expired.
   */
  private function last_login_is_recent()
  {
    if (!isset($this->last_login)) {
      return false;
    }
    return ($this->last_login + self::MAX_LOGIN_AGE) >= time();
  }

  /**
   * Store a temporary message in session data.
   *
   * @param string $msg Optional message to store.
   * @return string Stored message or empty string.
   */
  public function message($msg = "")
  {
    if (!empty($msg)) {
      $_SESSION['message'] = $msg;
      return true;
    }
    return $_SESSION['message'] ?? '';
  }

  /**
   * Clear the stored session message.
   */
  public function clear_message()
  {
    unset($_SESSION['message']);
  }

  /**
   * Check if the logged-in user is an admin.
   *
   * @return bool True if the user has the admin role.
   */
  public function is_admin()
  {
    return isset($this->role_id) && (int)$this->role_id === 2; // Assuming '2' is the admin role_id
  }

  /**
   * Check if the logged-in user is a vendor.
   *
   * @return bool True if the user has the vendor role.
   */
  public function is_vendor()
  {
    return isset($this->role_id) && (int)$this->role_id === 1; // Assuming '1' is the vendor role_id
  }
}
