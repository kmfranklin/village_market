<?php

/**
 * Generate a full URL for a script.
 *
 * @param string $script_path The relative script path.
 * @return string Full URL including WWW_ROOT.
 */
function url_for($script_path)
{
  if ($script_path[0] != '/') {
    $script_path = "/" . $script_path;
  }
  return WWW_ROOT . $script_path;
}

/**
 * Encode a string for use in a URL query.
 *
 * @param string $string The string to encode.
 * @return string URL-encoded string.
 */
function u($string = "")
{
  return urlencode($string);
}

/**
 * Encode a string for use in a URL path.
 *
 * @param string $string The string to encode.
 * @return string Raw URL-encoded string.
 */
function raw_u($string = "")
{
  return rawurlencode($string);
}

/**
 * Convert special characters to HTML entities.
 *
 * @param string $string The string to escape.
 * @return string Escaped HTML string.
 */
function h($string = "")
{
  return htmlspecialchars($string);
}

/**
 * Send a 404 Not Found response and exit.
 *
 * @return void
 */
function error_404()
{
  header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
  exit();
}

/**
 * Send a 500 Internal Server Error response and exit.
 *
 * @return void
 */
function error_500()
{
  header($_SERVER["SERVER_PROTOCOL"] . " 500 Internal Server Error");
  exit();
}

/**
 * Redirect to another page.
 *
 * @param string $location The URL to redirect to.
 * @return void
 */
function redirect_to($location)
{
  header("Location: " . $location);
  exit;
}

/**
 * Check if the request is a POST request.
 *
 * @return bool True if POST, false otherwise.
 */
function is_post_request()
{
  return $_SERVER['REQUEST_METHOD'] == 'POST';
}

/**
 * Check if the request is a GET request.
 *
 * @return bool True if GET, false otherwise.
 */
function is_get_request()
{
  return $_SERVER['REQUEST_METHOD'] == 'GET';
}
