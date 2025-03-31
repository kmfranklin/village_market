<?php
require_once('../../private/initialize.php');

$page_title = 'All Products';
include_header($session, $page_title);
echo display_session_message();

// Get filter parameters
$search_term = $_GET['search'] ?? '';
$vendor_id = $_GET['vendor_id'] ?? '';
$category_id = $_GET['category_id'] ?? '';

// Build product query
$sql = "
  SELECT 
    p.product_id,
    p.product_name,
    p.product_description,
    p.product_image_url,
    v.vendor_id,
    v.business_name,
    c.category_id,
    c.category_name
  FROM product p
  JOIN vendor v ON p.vendor_id = v.vendor_id
  JOIN user u ON v.user_id = u.user_id
  JOIN category c ON p.category_id = c.category_id
  WHERE p.is_active = 1
    AND u.account_status = 'active'
    AND u.role_id = 1
";

if (!empty($search_term)) {
  $sql .= " AND p.product_name LIKE '%" . $database->real_escape_string($search_term) . "%'";
}

if (!empty($vendor_id)) {
  $sql .= " AND v.vendor_id = '" . $database->real_escape_string($vendor_id) . "'";
}

if (!empty($category_id)) {
  $sql .= " AND c.category_id = '" . $database->real_escape_string($category_id) . "'";
}

$sql .= " ORDER BY p.product_name ASC";

$product_result = $database->query($sql);
if (!$product_result) {
  die("Product query failed: " . $database->error);
}

// Vendor dropdown
$vendor_sql = "
  SELECT v.vendor_id, v.business_name
  FROM vendor v
  JOIN user u ON v.user_id = u.user_id
  WHERE u.account_status = 'active'
    AND u.role_id = 1
  ORDER BY v.business_name ASC
";

$vendor_result = $database->query($vendor_sql);
if (!$vendor_result) {
  die("Vendor query failed: " . $database->error);
}

// Category dropdown
$category_sql = "
  SELECT category_id, category_name
  FROM category
  ORDER BY category_name ASC
";

$category_result = $database->query($category_sql);
if (!$category_result) {
  die("Category query failed: " . $database->error);
}
?>

<main class="container my-4">
  <h1 class="mb-4">Browse All Products</h1>

  <form method="GET" id="product-filter-form" class="row gy-2 gx-3 align-items-center mb-4">
    <div class="col-md-3">
      <label for="search" class="form-label visually-hidden">Search</label>
      <input type="text"
        name="search"
        id="search"
        class="form-control"
        placeholder="Search by product name..."
        value="<?= htmlspecialchars($search_term) ?>">
    </div>

    <div class="col-md-3">
      <label for="category_id" class="form-label visually-hidden">Filter by Category</label>
      <select name="category_id" id="category_id" class="form-select">
        <option value="">All Categories</option>
        <?php while ($category = $category_result->fetch_assoc()): ?>
          <option value="<?= $category['category_id'] ?>"
            <?= ($category_id == $category['category_id']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($category['category_name']) ?>
          </option>
        <?php endwhile; ?>
      </select>
    </div>

    <div class="col-md-3">
      <label for="vendor_id" class="form-label visually-hidden">Filter by Vendor</label>
      <select name="vendor_id" id="vendor_id" class="form-select">
        <option value="">All Vendors</option>
        <?php while ($vendor = $vendor_result->fetch_assoc()): ?>
          <option value="<?= $vendor['vendor_id'] ?>"
            <?= ($vendor_id == $vendor['vendor_id']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($vendor['business_name']) ?>
          </option>
        <?php endwhile; ?>
      </select>
    </div>

    <div class="col-md-3 d-flex gap-2">
      <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
      <a href="<?= url_for('/products/index.php'); ?>" class="btn btn-outline-secondary w-100">
        <i class="bi bi-x-circle"></i> Clear
      </a>
    </div>
  </form>

  <section id="products" class="container my-5">
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
      <?php foreach ($product_result as $product): ?>
        <div class="col"
          data-name="<?= htmlspecialchars(strtolower($product['product_name'])) ?>"
          data-vendor="<?= $product['vendor_id'] ?>"
          data-category="<?= $product['category_id'] ?>">
          <div class="card h-100 shadow-sm d-flex flex-column">
            <?php
            $image_url = !empty($product['product_image_url'])
              ? htmlspecialchars($product['product_image_url'])
              : url_for('/assets/images/product_placeholder.webp');
            ?>
            <img src="<?= $image_url ?>"
              class="card-img-top"
              alt="<?= htmlspecialchars($product['product_name']) ?>"
              onerror="this.onerror=null;this.src='<?= url_for('/assets/images/product_placeholder.png'); ?>';">

            <div class="card-body homepage-product-card">
              <h5 class="card-title text-capitalize">
                <?= htmlspecialchars($product['product_name']) ?>
              </h5>
              <p class="card-text">
                <?= htmlspecialchars(substr($product['product_description'], 0, 100)) ?>
              </p>
              <p class="text-muted small">
                Category: <strong><?= htmlspecialchars($product['category_name']); ?></strong><br>
                Sold by: <strong><?= htmlspecialchars($product['business_name']); ?></strong>
              </p>
              <a href="view.php?id=<?= $product['product_id'] ?>" class="btn btn-primary w-100">View Product</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <p id="no-results" class="text-center text-muted mt-5" style="display: none;">
      No matching products found. Try adjusting your filters.
    </p>
  </section>
</main>

<?php include(SHARED_PATH . '/footer.php'); ?>
