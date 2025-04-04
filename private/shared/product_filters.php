<?php
function get_filtered_products_query($database, $options = [])
{
  $search_term = $_GET['search'] ?? '';
  $vendor_id = $_GET['vendor_id'] ?? '';
  $category_id = $_GET['category_id'] ?? '';
  $is_admin = $options['is_admin'] ?? false;
  $current_vendor_id = $options['current_vendor_id'] ?? null;

  $sql = "SELECT p.*, c.category_name, v.business_name, 
            GROUP_CONCAT(
                CONCAT(ppu.price_unit_id, ':', ppu.price, ':', pu.unit_name)
                ORDER BY pu.unit_name 
                SEPARATOR '|'
            ) as price_units
            FROM product p
            JOIN category c ON p.category_id = c.category_id
            JOIN vendor v ON p.vendor_id = v.vendor_id
            LEFT JOIN product_price_unit ppu ON p.product_id = ppu.product_id
            LEFT JOIN price_unit pu ON ppu.price_unit_id = pu.price_unit_id
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

  $sql .= " GROUP BY p.product_id";
  $sql .= " ORDER BY p.product_name ASC";

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
