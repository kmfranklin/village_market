<?php

function url_for($script_path)
{
  if ($script_path[0] != '/') {
    $script_path = "/" . $script_path;
  }
  return WWW_ROOT . $script_path;
}

function u($string = "")
{
  return urlencode($string);
}

function raw_u($string = "")
{
  return rawurlencode($string);
}

function h($string = "")
{
  return htmlspecialchars($string);
}

function error_404()
{
  header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
  exit();
}

function error_500()
{
  header($_SERVER["SERVER_PROTOCOL"] . " 500 Internal Server Error");
  exit();
}

function redirect_to($location)
{
  header("Location: " . $location);
  exit;
}

function is_post_request()
{
  return $_SERVER['REQUEST_METHOD'] == 'POST';
}

function is_get_request()
{
  return $_SERVER['REQUEST_METHOD'] == 'GET';
}

function include_header($session)
{
  if ($session->is_super_admin()) {
    include(SHARED_PATH . '/admin_header.php');
  } elseif ($session->is_admin()) {
    include(SHARED_PATH . '/admin_header.php');
  } elseif ($session->is_vendor()) {
    include(SHARED_PATH . '/vendor_header.php');
  } else {
    include(SHARED_PATH . '/public_header.php');
  }
}

function get_states()
{
  global $database;
  $sql = "SELECT * FROM state ORDER BY state_name ASC";
  $result = $database->query($sql);
  $states = [];

  if ($result) {
    while ($row = $result->fetch_assoc()) {
      $states[] = $row;
    }
    $result->free();
  } else {
    die("Database query failed: " . $database->error);
  }

  return $states;
}

function display_delete_modal($entity_type, $delete_url, $vendor_id, $user_id, $entity_name)
{
  include(SHARED_PATH . '/delete_modal.php');
}
