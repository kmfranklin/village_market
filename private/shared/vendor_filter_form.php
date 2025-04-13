<?php
$search_term = $_GET['search'] ?? '';
$market_date_id = $_GET['market_date_id'] ?? '';
?>

<form method="GET" id="vendor-filter-form" class="row gy-2 gx-3 align-items-center mb-4">
  <!-- Search Input -->
  <div class="col-md-3">
    <label for="search" class="form-label visually-hidden">Search</label>
    <input type="text" name="search" id="search" class="form-control"
      value="<?= h($search_term) ?>" placeholder="Search by vendor name...">
  </div>

  <!-- Market Date Dropdown -->
  <div class="col-md-3">
    <label for="market_date_id" class="form-label visually-hidden">Market Date</label>
    <select name="market_date_id" id="market_date_id" class="form-select">
      <option value="">Any Date</option>
      <?php while ($date = $market_date_result->fetch_assoc()): ?>
        <option value="<?= h($date['market_date_id']) ?>"
          <?= ($market_date_id == $date['market_date_id']) ? 'selected' : '' ?>>
          <?= h(date("F j, Y", strtotime($date['market_date']))) ?>
        </option>
      <?php endwhile; ?>
    </select>
  </div>

  <!-- Sort Dropdown -->
  <div class="col-md-3 d-flex align-items-center gap-2">
    <div class="flex-grow-1">
      <label for="sort" class="form-label visually-hidden">Sort By</label>
      <select name="sort" id="sort" class="form-select">
        <option value="">Default</option>
        <option value="name_asc" <?= ($_GET['sort'] ?? '') === 'name_asc' ? 'selected' : '' ?>>Name (A-Z)</option>
        <option value="name_desc" <?= ($_GET['sort'] ?? '') === 'name_desc' ? 'selected' : '' ?>>Name (Z-A)</option>
      </select>
    </div>

    <div id="apply-button-wrapper">
      <button type="submit" class="btn btn-primary">Apply</button>
    </div>

    <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-outline-secondary">Clear</a>
  </div>

</form>
