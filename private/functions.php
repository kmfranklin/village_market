<?php

/**
 * Builds a fully qualified URL path.
 * 
 * @param string $script_path Relative path to file.
 * @return string Full URL.
 */
function url_for($script_path)
{
  // Ensure WWW_ROOT does not end with '/'
  $root = rtrim(WWW_ROOT, '/');

  // Ensure $script_path does start with '/'
  $path = ltrim($script_path, '/');

  return $root . '/' . $path;
}

/**
 * Encodes a string for use in a URL.
 * 
 * @param string $string
 * @return string
 */
function u($string = "")
{
  return urlencode($string);
}

/**
 * Encodes a string using raw URL encoding.
 *
 * @param string $string
 * @return string
 */
function raw_u($string = "")
{
  return rawurlencode($string);
}

/**
 * Encodes a string using raw URL encoding.
 *
 * @param string $string
 * @return string
 */
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

/**
 * Redirects the browser to a new location.
 *
 * @param string $location URL to redirect to.
 */
function redirect_to($location)
{
  header("Location: " . $location);
  exit;
}

/**
 * Checks if the current request method is POST.
 *
 * @return bool True if POST request, false otherwise.
 */
function is_post_request()
{
  return $_SERVER['REQUEST_METHOD'] == 'POST';
}

/**
 * Checks if the current request method is GET.
 *
 * @return bool True if GET request, false otherwise.
 */
function is_get_request()
{
  return $_SERVER['REQUEST_METHOD'] == 'GET';
}

/**
 * Dynamically includes the correct header based on user role.
 *
 * @param Session $session The current session object.
 */
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

/**
 * Retrieves all states from the database, ordered by name.
 *
 * @global mysqli $database
 * @return array List of states.
 */
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

/**
 * Fetches the next available market date.
 * If no future market dates exist, it generates new ones.
 *
 * @return string|null - The next upcoming market date or null if not found.
 */
function get_next_market_date()
{
  global $database;

  // Query for the next available market date
  $sql = "SELECT market_date FROM market_date WHERE market_date >= CURDATE() ORDER BY market_date ASC LIMIT 1";
  $result = $database->query($sql);
  $next_market = $result->fetch_assoc();

  // If no upcoming date exists, generate new dates
  if (!$next_market) {
    generate_market_dates();
    // Re-run the query after inserting new dates
    $result = $database->query($sql);
    $next_market = $result->fetch_assoc();
  }

  return $next_market ? $next_market['market_date'] : null;
}

/**
 * Returns the ID of the next upcoming market date.
 *
 * @return int|null Market date ID or null if not found.
 */
function get_next_market_date_id()
{
  global $database;

  $sql = "SELECT market_date_id FROM market_date WHERE market_date > CURDATE() ORDER BY market_date ASC LIMIT 1";
  $result = $database->query($sql);
  $next_market = $result->fetch_assoc();

  if (!$next_market) {
    generate_market_dates();
    $result = $database->query($sql);
    $next_market = $result->fetch_assoc();
  }

  return $next_market ? $next_market['market_date_id'] : null;
}

/**
 * Generates market dates for the next 6 months if none exist.
 * Adjust the day based on your actual market schedule.
 */
function generate_market_dates($months_ahead = 6)
{
  global $database;

  $num_weeks = $months_ahead * 4; // Approximate weekly markets for the next 6 months
  $today = new DateTime();
  $today->modify('next Saturday'); // Adjust this for your actual market day

  for ($i = 0; $i < $num_weeks; $i++) {
    $market_date = $today->format('Y-m-d');

    // Check if this date already exists
    $sql = "SELECT COUNT(*) AS count FROM market_date WHERE market_date = ?";
    $stmt = $database->prepare($sql);
    $stmt->bind_param("s", $market_date);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    if ($row['count'] == 0) {
      // Insert the new market date
      $insert_sql = "INSERT INTO market_date (market_date, is_active) VALUES (?, 1)";
      $stmt = $database->prepare($insert_sql);
      $stmt->bind_param("s", $market_date);
      $stmt->execute();
      $stmt->close();
    }

    // Move to the next scheduled market day
    $today->modify('+7 days');
  }
}

/**
 * Retrieves the full site mailing address from homepage content.
 *
 * @global mysqli $database
 * @return array Associative array with address, city, state, and zip.
 */
function get_site_address()
{
  global $database;

  $sql = "SELECT hc.contact_mailing_address, hc.contact_city, hc.contact_zip, 
                   s.state_abbreviation AS contact_state
            FROM homepage_content hc
            LEFT JOIN state s ON hc.contact_state = s.state_id
            LIMIT 1";

  $result = $database->query($sql);
  if (!$result) {
    die("Database query failed: " . $database->error);
  }

  return $result->fetch_assoc() ?: [];
}

/**
 * Optimizes a Cloudinary image URL for WebP format and size constraints.
 * If the input is not a Cloudinary URL, it is returned as-is.
 * If no image URL is provided, a default placeholder is returned.
 *
 * @param string $image_url The original image URL.
 * @param int|null $width Optional width for transformation.
 * @param int|null $height Optional height for transformation.
 * @return array Associative array with 'url', 'width', and 'height'.
 */
function get_cloudinary_image($image_url, $width = null, $height = null)
{
  $cloudinary_base = "https://res.cloudinary.com/dykbjvtfu/image/upload/";
  $transformations = "f_webp,q_auto";

  if (strpos($image_url, $cloudinary_base) === 0) {
    $optimized_url = str_replace("/upload/", "/upload/{$transformations}/", $image_url);

    if ($width && $height) {
      $optimized_url = str_replace("/upload/{$transformations}/", "/upload/{$transformations},w_{$width},h_{$height},c_fill/", $optimized_url);
    } elseif ($width) {
      $optimized_url = str_replace("/upload/{$transformations}/", "/upload/{$transformations},w_{$width}/", $optimized_url);
    }
  } else {
    $optimized_url = $image_url;
  }

  // Fallback placeholder for missing images (sets width & height too)
  if (empty($image_url)) {
    $optimized_url = "https://res.cloudinary.com/dykbjvtfu/image/upload/f_auto,q_auto,w_300,h_200/default-placeholder.jpg";
  }

  return [
    'url' => $optimized_url,
    'width' => $width ?: 300,
    'height' => $height ?: 200
  ];
}

/**
 * Fetches the Cloudinary image URL for the current homepage hero image.
 * Falls back to a default image if no hero image is set.
 *
 * @global mysqli $database
 * @return string URL of the homepage hero image.
 */
function get_homepage_hero_image_url()
{
  global $database;

  $sql = "SELECT hero_image_id FROM homepage_content LIMIT 1";
  $result = $database->query($sql);

  if ($row = $result->fetch_assoc()) {
    $image_id = $row['hero_image_id'];

    if ($image_id) {
      $img_sql = "SELECT image_url FROM cms_image WHERE image_id = ?";
      $stmt = $database->prepare($img_sql);
      $stmt->bind_param("i", $image_id);
      $stmt->execute();
      $img_result = $stmt->get_result();
      if ($img_row = $img_result->fetch_assoc()) {
        return $img_row['image_url'];
      }
    }
  }

  // Fallback default image
  return url_for('/assets/images/default_hero.webp');
}
