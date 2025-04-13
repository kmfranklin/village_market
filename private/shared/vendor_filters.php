<?php

/**
 * Generate a dynamic SQL query to retrieve filtered vendor data.
 *
 * @param mysqli $database The database connection object.
 * @return string The fully constructed SQL query.
 */
function get_filtered_vendors_query($database)
{
  $search_term = $_GET['search'] ?? '';
  $market_date_id = $_GET['market_date_id'] ?? '';
  $sort = $_GET['sort'] ?? '';

  $joins = "
    FROM vendor
    JOIN user ON vendor.user_id = user.user_id
    JOIN product ON vendor.vendor_id = product.vendor_id
  ";

  if (!empty($market_date_id)) {
    $joins .= "
      JOIN market_attendance ON vendor.vendor_id = market_attendance.vendor_id
    ";
  }

  $where = "
    WHERE user.account_status = 'active'
    AND product.is_active = 1
  ";

  if (!empty($market_date_id)) {
    $where .= " AND market_attendance.market_date_id = " . (int)$market_date_id;
    $where .= " AND market_attendance.is_confirmed = 1";
  }

  if (!empty($search_term)) {
    $safe_term = $database->real_escape_string($search_term);
    $where .= " AND (
      vendor.business_name LIKE '%{$safe_term}%'
      OR vendor.business_description LIKE '%{$safe_term}%'
    )";
  }

  $order_by = "ORDER BY vendor.business_name ASC";
  if ($sort === 'name_desc') {
    $order_by = "ORDER BY vendor.business_name DESC";
  }

  $sql = "
    SELECT DISTINCT vendor.*, state.state_abbreviation
    $joins
    LEFT JOIN state ON vendor.state_id = state.state_id
    $where
    $order_by
  ";

  return $sql;
}

/**
 * Retrieve dropdown results for vendor filters.
 *
 * @param mysqli $database The database connection object.
 * @return array {
 *   @type mysqli_result $market_date_result
 * }
 */
function get_vendor_filter_dropdowns($database)
{
  $market_date_result = $database->query("
    SELECT * FROM market_date
    WHERE is_active = 1 AND market_date >= CURDATE()
    ORDER BY market_date ASC
    LIMIT 6
  ");

  return [
    'market_date_result' => $market_date_result
  ];
}
