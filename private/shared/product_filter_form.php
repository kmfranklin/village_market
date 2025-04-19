<?php
$search_term = $_GET['search'] ?? '';
$vendor_id = $_GET['vendor_id'] ?? '';
$category_id = $_GET['category_id'] ?? '';
$is_admin = $is_admin ?? false;
?>

<form method="GET" id="product-filter-form" class="row gy-2 gx-3 align-items-center mb-4">
  <!-- Search Input -->
  <div class="col-md-3">
    <label for="search" class="form-label visually-hidden">Search</label>
    <input type="text" name="search" id="search" class="form-control"
      value="<?= h($_GET['search'] ?? '') ?>" placeholder="Search by product name...">
  </div>

  <!-- Category Dropdown -->
  <div class="col-md-3">
    <label for="category_id" class="form-label visually-hidden">Category</label>
    <select name="category_id" id="category_id" class="form-select">
      <option value="">All Categories</option>
      <?php while ($category = $category_result->fetch_assoc()): ?>
        <option value="<?= h($category['category_id']) ?>"
          <?= (isset($_GET['category_id']) && $_GET['category_id'] == $category['category_id']) ? 'selected' : '' ?>>
          <?= h($category['category_name']) ?>
        </option>
      <?php endwhile; ?>
    </select>
  </div>

  <!-- Vendor Dropdown -->
  <?php if (isset($is_public_page) || (isset($is_admin) && $is_admin)): ?>
    <div class="col-md-3">
      <label for="vendor_id" class="form-label visually-hidden">Vendor</label>
      <select name="vendor_id" id="vendor_id" class="form-select">
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

  <!-- Sort Dropdown -->
  <div class="col-md-3 d-flex align-items-center gap-2">
    <div class="flex-grow-1">
      <label for="sort" class="form-label visually-hidden">Sort By</label>
      <select name="sort" id="sort" class="form-select">
        <option value="">Default</option>
        <option value="name_asc" <?= (isset($_GET['sort']) && $_GET['sort'] === 'name_asc') ? 'selected' : '' ?>>Name (A-Z)</option>
        <option value="name_desc" <?= (isset($_GET['sort']) && $_GET['sort'] === 'name_desc') ? 'selected' : '' ?>>Name (Z-A)</option>
      </select>
    </div>
    <button type="submit" class="btn btn-primary" id="apply-button">Apply</button>
    <a href="<?= h($_SERVER['PHP_SELF']) ?>" class="btn btn-outline-secondary">Clear</a>
  </div>
</form>
