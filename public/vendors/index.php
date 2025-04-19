<?php
require_once('../../private/initialize.php');
$page_title = 'Browse Vendors';
require_once(SHARED_PATH . '/include_header.php');

echo display_session_message();

// Filters
$market_date_id = $_GET['market_date_id'] ?? '';
$search_term = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? '';

// Step 1: Get vendor_ids for selected date
$filtered_vendor_ids = [];

if (!empty($market_date_id)) {
  $date_safe = (int)$market_date_id;
  $id_result = $database->query("
    SELECT vendor_id
    FROM market_attendance
    WHERE market_date_id = {$date_safe}
      AND is_confirmed = 1
  ");
  while ($row = $id_result->fetch_assoc()) {
    $filtered_vendor_ids[] = (int)$row['vendor_id'];
  }
}

// Step 2: Base WHERE clause
$where = "WHERE u.account_status = 'active' AND p.is_active = 1";
$vendor_id_clause = '';

if (!empty($filtered_vendor_ids)) {
  $escaped_ids = implode(',', array_map('intval', $filtered_vendor_ids));
  $vendor_id_clause = " AND v.vendor_id IN ($escaped_ids)";
} elseif (!empty($market_date_id)) {
  $vendor_id_clause = " AND 0"; // selected date but no vendors = no results
}

// Search filter
if (!empty($search_term)) {
  $safe_term = $database->escape_string($search_term);
  $where .= " AND (
    v.business_name LIKE '%{$safe_term}%'
    OR v.business_description LIKE '%{$safe_term}%'
  )";
}

// Sort
$order_by = "ORDER BY v.business_name ASC";
if ($sort === 'name_desc') {
  $order_by = "ORDER BY v.business_name DESC";
}

// Step 3: Get all vendor data (no pagination)
$sql = "
  SELECT v.*, s.state_abbreviation,
    GROUP_CONCAT(DISTINCT ma.market_date_id) AS market_date_ids
  FROM vendor v
  JOIN user u ON v.user_id = u.user_id
  JOIN product p ON v.vendor_id = p.vendor_id
  LEFT JOIN market_attendance ma ON v.vendor_id = ma.vendor_id AND ma.is_confirmed = 1
  LEFT JOIN state s ON v.state_id = s.state_id
  $where $vendor_id_clause
  GROUP BY v.vendor_id
  $order_by
";

$vendor_result = $database->query($sql);

// Get market dates for filter dropdown
$market_date_result = $database->query("
  SELECT * FROM market_date
  WHERE is_active = 1 AND market_date >= CURDATE()
  ORDER BY market_date ASC
  LIMIT 6
");
?>

<div class="container my-5">
  <h1 class="mb-4">Browse All Vendors</h1>

  <?php
  $is_public_page = true;
  include(SHARED_PATH . '/vendor_filter_form.php');
  ?>

  <section id="vendors" class="container my-5">
    <div id="vendor-card-container" class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
      <?php if ($vendor_result->num_rows === 0): ?>
        <p class="text-muted text-center">No vendors found.</p>
      <?php else: ?>
        <?php while ($vendor = $vendor_result->fetch_assoc()): ?>
          <div class="col vendor-card"
            data-name="<?= h(strtolower($vendor['business_name'])) ?>"
            data-dates="<?= h(trim($vendor['market_date_ids'] ?? '')) ?>">
            <div class="card h-100 shadow-sm d-flex flex-column">
              <?php
              $logo_url = !empty($vendor['business_logo_url'])
                ? h($vendor['business_logo_url'])
                : url_for('/assets/images/vendor_placeholder.png');
              ?>
              <img src="<?= $logo_url ?>"
                class="card-img-top vendor-logo"
                alt="<?= h($vendor['business_name']) ?> Logo"
                onerror="this.onerror=null;this.src='<?= url_for('/assets/images/vendor_placeholder.png'); ?>';">

              <div class="card-body d-flex flex-column homepage-product-card">
                <h5 class="card-title text-capitalize"><?= h($vendor['business_name']) ?></h5>
                <?php
                $desc = $vendor['business_description'];
                $truncated = strlen($desc) > 100 ? substr($desc, 0, 100) . '…' : $desc;
                ?>
                <p class="card-text"><?= h($truncated) ?></p>
                <p class="text-muted small">
                  Location: <?= h($vendor['city']) ?>, <?= h($vendor['state_abbreviation']) ?>
                </p>
                <a href="view.php?id=<?= h($vendor['user_id']) ?>" class="btn btn-primary mt-auto w-100">
                  View Profile
                </a>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      <?php endif; ?>
    </div>

    <nav id="vendor-pagination" class="pagination justify-content-center mt-4"></nav>

    <p id="no-results" class="text-center text-muted mt-5">
      No matching vendors found. Try adjusting your filters.
    </p>
  </section>
</div>
</main>

<?php include(SHARED_PATH . '/footer.php'); ?>
