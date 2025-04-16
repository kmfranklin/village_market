<?php

/**
 * Generate a dynamic SQL query to retrieve filtered product data.
 *
 * This function builds a query based on optional GET parameters:
 * - search term (product name)
 * - vendor_id
 * - category_id
 * - sort order
 *
 * It also accepts user context via `$options`, which determines
 * if the request is from an admin or a vendor, and adjusts the
 * query accordingly to enforce access control and filtering.
 *
 * For public pages, it restricts output to active products
 * from vendors whose associated user account is 'active'.
 *
 * @param mysqli $database The database connection object.
 * @param array $options {
 *   @type bool $is_admin Whether the current user is an admin.
 *   @type int|null $current_vendor_id If set, restrict to this vendor's products.
 * }
 * @return string The fully constructed SQL query.
 */
function get_filtered_products_query($database, $options = [])
{
  $search_term = $_GET['search'] ?? '';
  $vendor_id = $_GET['vendor_id'] ?? '';
  $category_id = $_GET['category_id'] ?? '';
  $sort = $_GET['sort'] ?? '';
  $is_admin = $options['is_admin'] ?? false;
  $current_vendor_id = $options['current_vendor_id'] ?? null;
  $next_market_date_id = $options['next_market_date_id'] ?? null;

  $sql = "SELECT p.*, c.category_name, v.business_name
        FROM product p
        JOIN category c ON p.category_id = c.category_id
        JOIN vendor v ON p.vendor_id = v.vendor_id
        JOIN market_attendance ma ON v.vendor_id = ma.vendor_id";

  // Only join users if we need to check their account_status
  if (!$is_admin && !$current_vendor_id) {
    $sql .= " JOIN user u ON v.user_id = u.user_id";
  }

  $sql .= " WHERE 1=1";

  // Restrict to active products
  $sql .= " AND p.is_active = 1";

  // Show only products from vendors confirmed for the next market date
  $sql .= " AND ma.is_confirmed = 1";
  if ($next_market_date_id !== null) {
    $sql .= " AND ma.market_date_id = '" . $database->real_escape_string($next_market_date_id) . "'";
  }

  // For public-facing pages, hide products from suspended/pending vendors
  if (!$is_admin && !$current_vendor_id) {
    $sql .= " AND u.account_status = 'active'";
  }

  // Admin filtering by vendor
  if ($is_admin && !empty($vendor_id)) {
    $sql .= " AND p.vendor_id = '" . $database->real_escape_string($vendor_id) . "'";
  }

  // Vendor viewing their own products
  if (!$is_admin && $current_vendor_id) {
    $sql .= " AND p.vendor_id = '" . $database->real_escape_string($current_vendor_id) . "'";
  }

  // Search
  if (!empty($search_term)) {
    $sql .= " AND p.product_name LIKE '%" . $database->real_escape_string($search_term) . "%'";
  }

  // Category filter
  if (!empty($category_id)) {
    $sql .= " AND p.category_id = '" . $database->real_escape_string($category_id) . "'";
  }

  // Sort
  switch ($sort) {
    case 'name_asc':
      $sql .= " ORDER BY p.product_name ASC";
      break;
    case 'name_desc':
      $sql .= " ORDER BY p.product_name DESC";
      break;
    default:
      $sql .= " ORDER BY p.product_name ASC";
      break;
  }

  return $sql;
}

/**
 * Retrieve filter dropdown options for product list filtering.
 *
 * Returns two MySQLi result sets:
 * - All product categories
 * - All vendors whose associated user account is 'active'
 *
 * @param mysqli $database The database connection object.
 * @return array {
 *   @type mysqli_result $category_result Result set of all categories.
 *   @type mysqli_result $vendor_result Result set of active vendors.
 * }
 */
function get_filter_dropdowns($database)
{
  $category_sql = "SELECT * FROM category ORDER BY category_name ASC";
  $category_result = $database->query($category_sql);

  $vendor_sql = "
    SELECT v.* 
    FROM vendor v
    JOIN user u ON v.user_id = u.user_id
    JOIN product p ON v.vendor_id = p.vendor_id
    JOIN market_attendance ma ON v.vendor_id = ma.vendor_id
    WHERE u.account_status = 'active'
      AND p.is_active = 1
      AND ma.is_confirmed = 1
    GROUP BY v.vendor_id
    ORDER BY v.business_name ASC
  ";
  $vendor_result = $database->query($vendor_sql);

  return [
    'category_result' => $category_result,
    'vendor_result' => $vendor_result
  ];
}
