<?php
function get_filtered_products_query($database, $options = [])
{
  $search_term = $_GET['search'] ?? '';
  $vendor_id = $_GET['vendor_id'] ?? '';
  $category_id = $_GET['category_id'] ?? '';
  $sort = $_GET['sort'] ?? '';
  $is_admin = $options['is_admin'] ?? false;
  $current_vendor_id = $options['current_vendor_id'] ?? null;

  $sql = "SELECT p.*, c.category_name, v.business_name
            FROM product p
            JOIN category c ON p.category_id = c.category_id
            JOIN vendor v ON p.vendor_id = v.vendor_id
            WHERE 1=1";

  // Filter by vendor access
  if (!$is_admin && $current_vendor_id) {
    $sql .= " AND p.vendor_id = '" . $database->real_escape_string($current_vendor_id) . "'";
  } elseif ($is_admin && !empty($vendor_id)) {
    $sql .= " AND p.vendor_id = '" . $database->real_escape_string($vendor_id) . "'";
  }

  if (!empty($search_term)) {
    $sql .= " AND p.product_name LIKE '%" . $database->real_escape_string($search_term) . "%'";
  }

  if (!empty($category_id)) {
    $sql .= " AND p.category_id = '" . $database->real_escape_string($category_id) . "'";
  }

  // Add sorting logic
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

function get_filter_dropdowns($database)
{
  $category_sql = "SELECT * FROM category ORDER BY category_name ASC";
  $category_result = $database->query($category_sql);

  $vendor_sql = "SELECT * FROM vendor ORDER BY business_name ASC";
  $vendor_result = $database->query($vendor_sql);

  return [
    'category_result' => $category_result,
    'vendor_result' => $vendor_result
  ];
}
