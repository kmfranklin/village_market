<?php
$search_term = $_GET['search'] ?? '';
$vendor_id = $_GET['vendor_id'] ?? '';
$category_id = $_GET['category_id'] ?? '';
$is_admin = $is_admin ?? false;
?>

<form method="GET" id="product-filter-form" class="row gy-2 gx-3 align-items-center mb-4">
  <div class="col-md-3">
    <input type="text"
      name="search"
      class="form-control"
      placeholder="Search by product name..."
      value="<?= h($_GET['search'] ?? '') ?>">
  </div>

  <div class="col-md-3">
    <select name="category_id" class="form-select">
      <option value="">All Categories</option>
      <?php while ($category = $category_result->fetch_assoc()): ?>
        <option value="<?= h($category['category_id']) ?>"
          <?= (isset($_GET['category_id']) && $_GET['category_id'] == $category['category_id']) ? 'selected' : '' ?>>
          <?= h($category['category_name']) ?>
        </option>
      <?php endwhile; ?>
    </select>
  </div>

  <?php if (isset($is_public_page) || (isset($is_admin) && $is_admin)): ?>
    <div class="col-md-3">
      <select name="vendor_id" class="form-select">
        <option value="">All Vendors</option>
        <?php while ($vendor = $vendor_result->fetch_assoc()): ?>
          <option value="<?= h($vendor['vendor_id']) ?>"
            <?= (isset($_GET['vendor_id']) && $_GET['vendor_id'] == $vendor['vendor_id']) ? 'selected' : '' ?>>
            <?= h($vendor['business_name']) ?>
          </option>
        <?php endwhile; ?>
      </select>
    </div>
  <?php endif; ?>

  <div class="col-md-3">
    <div class="d-flex gap-2">
      <button type="submit" class="btn btn-primary" data-filter-submit>Apply Filters</button>
      <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-outline-secondary">Clear</a>
    </div>
  </div>
</form>
