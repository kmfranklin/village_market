<?php
if (!isset($product)) {
  $product = new Product();
}

// Fetch all predefined price units
$units = PriceUnit::find_all();

// Fetch existing price units for this product
$existing_price_units = ProductPriceUnit::find_by_product_id($product->product_id);
$existing_prices = [];
foreach ($existing_price_units as $unit) {
  $existing_prices[$unit->price_unit_id] = $unit->price;
}

// Define unit groups
$unit_groups = [
  'Count' => ['dozen', 'half dozen', 'each'],
  'Weight' => ['pound', 'ounce'],
  'Volume' => ['gallon', 'quart', 'pint', 'cup'],
  'Other' => ['bushel', 'bundle']
];
?>

<div class="container">
  <fieldset>
    <legend class="h5 mb-3">Product Information</legend>

    <div class="row">
      <!-- Left Column: Product Details -->
      <div class="col-md-4">
        <div class="mb-3">
          <!-- Product Name -->
          <label for="product_name" class="form-label">Product Name</label>
          <input type="text" id="product_name" name="product[product_name]"
            class="form-control" value="<?php echo h($_POST['product']['product_name'] ?? $product->product_name ?? ''); ?>" required>

          <!-- Description -->
          <label for="product_description" class="form-label mt-3">Description</label>
          <textarea id="product_description" name="product[product_description]" class="form-control" required><?php echo h($_POST['product']['product_description'] ?? $product->product_description ?? ''); ?></textarea>

          <!-- Category -->
          <label for="category_id" class="form-label mt-3">Category</label>
          <select id="category_id" name="product[category_id]" class="form-select" required>
            <option value="">Select Category</option>
            <?php foreach (Category::find_all() as $category) { ?>
              <option value="<?php echo h($category->category_id); ?>"
                <?php echo (isset($_POST['product']['category_id']) && $_POST['product']['category_id'] == $category->category_id) ||
                  (isset($product->category_id) && $product->category_id == $category->category_id) ? 'selected' : ''; ?>>
                <?php echo h($category->category_name); ?>
              </option>
            <?php } ?>
          </select>

        </div>
      </div>

      <!-- Middle Column: Price & Units -->
      <div class="col-md-4">
        <h5>Price & Units</h5>
        <p>Select the unit(s) you sell this product by. After selecting, enter a price for each.</p>

        <!-- Error message placement for accessibility -->
        <?php if (!empty($errors) && in_array("At least one price must be set.", $errors)) { ?>
          <div class="alert alert-danger" role="alert">
            At least one price must be set.
          </div>
        <?php } ?>

        <!-- "+ Add Unit" Button -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUnitModal">
          + Add Unit
        </button>

        <!-- Selected Units List (Populated Dynamically) -->
        <div id="selectedUnitsContainer" class="mt-3">
          <?php
          $selected_prices = $_POST['product_price_unit'] ?? $existing_prices ?? [];

          if (!empty($selected_prices)) {
            foreach ($selected_prices as $unit_id => $price_data) {
              $unit = PriceUnit::find_by_id($unit_id);
              $price = $price_data['price'] ?? $price_data; // Handles both array or direct price storage

              if ($unit) {
                echo "<div class='selected-unit d-flex align-items-center mb-2' data-unit-id='{$unit_id}'>
          <span class='me-2'>" . h($unit->unit_name) . "</span>
          <input type='number' step='0.01' name='product_price_unit[{$unit_id}][price]'
                 class='form-control form-control-sm' placeholder='Enter price' value='" . h($price) . "' required>
          <button type='button' class='btn btn-danger btn-sm ms-2 remove-unit'>&times;</button>
        </div>";
              }
            }
          }
          ?>
        </div>
      </div>


      <!-- Right Column: Image Upload & Product Status -->
      <div class="col-md-4">
        <h5>Add an Image</h5>
        <div class="card p-3 shadow-sm">
          <label class="form-label">Current Image</label>
          <?php if (!empty($product->product_image_url)) { ?>
            <img src="<?php echo h($product->product_image_url); ?>" class="img-fluid rounded mb-2" alt="Product Image">
            <div class="d-grid gap-2">
              <button type="submit" name="delete_image" class="btn btn-outline-danger">Remove Image</button>
            </div>
          <?php } else { ?>
            <p class="text-muted">No image uploaded.</p>
          <?php } ?>
          <label class="form-label mt-3">Upload New Image</label>
          <input type="file" name="product_image" class="form-control">
        </div>

        <div class="form-check mt-3">
          <input type="checkbox" name="product[is_active]" id="is_active" class="form-check-input" value="1"
            <?php if ($product->is_active) echo 'checked'; ?>>
          <label for="is_active" class="form-check-label">Mark as Available for Sale</label>
        </div>
      </div>
    </div>
  </fieldset>
</div>

<!-- Ensure this is outside the .container but inside <body> -->
<div class="modal fade" id="addUnitModal" tabindex="-1" aria-labelledby="addUnitModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addUnitModalLabel">Select a Price Unit</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <p>Select one or more units to add pricing for your product.</p>

        <div id="unitList">
          <?php foreach ($unit_groups as $group_label => $unit_names) { ?>
            <h6 class="mt-3"><?php echo h($group_label); ?></h6>
            <div class="d-flex flex-wrap gap-2">
              <?php foreach ($units as $unit) {
                if (in_array(strtolower($unit->unit_name), $unit_names)) {
                  echo "<button type='button' class='btn btn-outline-primary unit-btn' 
                   data-unit-id='{$unit->price_unit_id}' 
                   data-unit-name='" . h($unit->unit_name) . "'>" . h($unit->unit_name) . "</button>";
                }
              } ?>
            </div>
          <?php } ?>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="confirmUnitSelection">Add Selected Units</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>

    </div>
  </div>
</div>
