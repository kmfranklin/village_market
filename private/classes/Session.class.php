<?php

class Session
{
  private $user_id;
  private $last_login;
  public $role_id;
  public $first_name;
  public const MAX_LOGIN_AGE = 86400;

  public function __construct()
  {
    session_start();
    $this->check_stored_login();
  }

  public function login($user)
  {
    if ($user) {
      session_regenerate_id(true);
      $this->user_id = $_SESSION['user_id'] = $user->user_id;
      $this->first_name = $_SESSION['first_name'] = $user->first_name;
      $this->role_id = $_SESSION['role_id'] = $user->role_id; // Ensure role_id is stored
      $this->last_login = $_SESSION['last_login'] = time();
      echo "Session login successful! User role ID: " . $this->role_id . "<br>";
    }
    return true;
  }

  public function is_logged_in()
  {
    return isset($this->user_id) && $this->last_login_is_recent();
  }

  public function logout()
  {
    unset($_SESSION['user_id'], $_SESSION['first_name'], $_SESSION['last_login'], $_SESSION['role_id']);
    unset($this->user_id, $this->first_name, $this->last_login, $this->role_id);
    session_destroy();
    return true;
  }

  private function check_stored_login()
  {
    if (isset($_SESSION['user_id'])) {
      $this->user_id = $_SESSION['user_id'];
      $this->last_login = $_SESSION['last_login'];
      $this->role_id = $_SESSION['role_id'];
      $this->first_name = $_SESSION['first_name'];
    }
  }

  private function last_login_is_recent()
  {
    if (!isset($this->last_login)) {
      return false;
    }
    return ($this->last_login + self::MAX_LOGIN_AGE) >= time();
  }

  public function message($msg = "")
  {
    if (!empty($msg)) {
      $_SESSION['message'] = $msg;
      return true;
    }
    return $_SESSION['message'] ?? '';
  }

  public function clear_message()
  {
    unset($_SESSION['message']);
  }

  public function is_super_admin()
  {
    return isset($this->role_id) && (int)$this->role_id === 3;
  }

  public function is_admin()
  {
    return isset($this->role_id) && (int)$this->role_id === 2;
  }

  public function is_vendor()
  {
    return isset($this->role_id) && (int)$this->role_id === 1;
  }

  public function get_user_id()
  {
    return $this->user_id;
  }
}
