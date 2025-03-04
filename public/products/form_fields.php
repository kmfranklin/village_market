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
?>

<div class="container">
  <fieldset>
    <legend class="h4 mb-3">Product Information</legend>

    <div class="row">
      <!-- Left Column: Product Details -->
      <div class="col-md-4">
        <div class="mb-3">
          <label for="product_name" class="form-label">Product Name</label>
          <input type="text" name="product[product_name]" id="product_name" class="form-control"
            value="<?php echo h($product->product_name); ?>" required />
        </div>

        <div class="mb-3">
          <label for="product_description" class="form-label">Description</label>
          <textarea name="product[product_description]" id="product_description" class="form-control"
            required><?php echo h($product->product_description); ?></textarea>
        </div>

        <div class="mb-3">
          <label for="category_id" class="form-label">Category</label>
          <select name="product[category_id]" id="category_id" class="form-select" required>
            <option value="">Select Category</option>
            <?php foreach (Product::get_categories() as $category) { ?>
              <option value="<?php echo h($category['category_id']); ?>"
                <?php if ($product->category_id == $category['category_id']) echo 'selected'; ?>>
                <?php echo h($category['category_name']); ?>
              </option>
            <?php } ?>
          </select>
        </div>
      </div>

      <!-- Middle Column: Price & Units -->
      <div class="col-md-4">
        <h5>Price & Units</h5>
        <p>Select price units and enter corresponding prices:</p>

        <div class="row">
          <?php
          $unit_groups = [
            'Count' => ['dozen', 'half dozen', 'each'],
            'Weight' => ['pound', 'ounce'],
            'Volume' => ['gallon', 'quart', 'pint', 'cup'],
            'Other' => ['bushel', 'bundle']
          ];

          foreach ($unit_groups as $group_label => $unit_names) {
            echo "<div class='col-md-6'><strong>$group_label:</strong><br>";

            foreach ($units as $unit) {
              if (in_array(strtolower($unit->unit_name), $unit_names)) {
                $checked = isset($existing_prices[$unit->price_unit_id]) ? "checked" : "";
                $price_value = $existing_prices[$unit->price_unit_id] ?? "";
                $price_display = $checked ? "block" : "none";

                echo "<div class='form-check'>
                        <input class='form-check-input' type='checkbox' name='product_price_unit[{$unit->price_unit_id}][selected]'
                          value='1' $checked onchange='togglePriceInput(this, \"price_{$unit->price_unit_id}\")'>
                        <label class='form-check-label'>" . h($unit->unit_name) . "</label>
                      </div>";
                echo "<input type='number' step='0.01' name='product_price_unit[{$unit->price_unit_id}][price]'
                        id='price_{$unit->price_unit_id}' class='form-control mb-2' style='display: $price_display;'
                        placeholder='Enter price' value='" . h($price_value) . "'>";
              }
            }

            echo "</div>";
          }
          ?>
        </div>
      </div>

      <!-- Right Column: Image Upload & Product Status -->
      <div class="col-md-4">
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
