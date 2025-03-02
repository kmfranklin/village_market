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

<fieldset>
  <legend>Product Information</legend>
  <dl>
    <dt><label for="product_name">Product Name</label></dt>
    <dd><input type="text" name="product[product_name]" id="product_name" value="<?php echo h($product->product_name); ?>" required /></dd>

    <dt><label for="product_description">Description</label></dt>
    <dd>
      <textarea name="product[product_description]" id="product_description" required><?php echo h($product->product_description); ?></textarea>
    </dd>

    <dt><label for="category_id">Category</label></dt>
    <dd>
      <select name="product[category_id]" id="category_id" required>
        <option value="">Select Category</option>
        <?php foreach (Product::get_categories() as $category) { ?>
          <option value="<?php echo h($category['category_id']); ?>"
            <?php if ($product->category_id == $category['category_id']) echo 'selected'; ?>>
            <?php echo h($category['category_name']); ?>
          </option>
        <?php } ?>
      </select>
    </dd>

    <dt>Price & Units</dt>
    <dd>
      <p>Select price units and enter corresponding prices:</p>
      <?php
      $unit_groups = [
        'Count' => ['dozen', 'half dozen', 'each'],
        'Weight' => ['pound', 'ounce'],
        'Volume' => ['gallon', 'quart', 'pint', 'cup'],
        'Other' => ['bushel', 'bundle']
      ];

      foreach ($unit_groups as $group_label => $unit_names) {
        echo "<strong>$group_label:</strong><br>";

        foreach ($units as $unit) {
          if (in_array(strtolower($unit->unit_name), $unit_names)) {
            $checked = isset($existing_prices[$unit->price_unit_id]) ? "checked" : "";
            $price_value = $existing_prices[$unit->price_unit_id] ?? "";
            $price_display = $checked ? "block" : "none";

            echo "<label>
                    <input type='checkbox' name='product_price_unit[{$unit->price_unit_id}][selected]' value='1' $checked 
                    onchange='togglePriceInput(this, \"price_{$unit->price_unit_id}\")'> " . h($unit->unit_name) . "
                  </label>";
            echo "<input type='number' step='0.01' name='product_price_unit[{$unit->price_unit_id}][price]' 
                    id='price_{$unit->price_unit_id}' style='display: $price_display;' 
                    placeholder='Enter price' value='" . h($price_value) . "'><br>";
          }
        }
      }
      ?>
    </dd>

    <dt>Product Image</dt>
    <dd>
      <?php if (!empty($product->product_image_url)) { ?>
        <img src="<?php echo h($product->product_image_url); ?>" width="200" alt="Product Image">
        <br>
        <label>
          <input type="checkbox" name="delete_image" value="1"> Remove Image
        </label>
        <input type="hidden" name="existing_product_image" value="<?php echo h($product->product_image_url); ?>">
      <?php } else { ?>
        <p>No image uploaded.</p>
      <?php } ?>
    </dd>

    <dt><label for="product_image">Upload New Image</label></dt>
    <dd>
      <input type="file" name="product_image" id="product_image">
    </dd>

    <dt><label for="is_active">Product Availability</label></dt>
    <dd>
      <input type="checkbox" name="product[is_active]" id="is_active" value="1"
        <?php if ($product->is_active) echo 'checked'; ?>>
      <label for="is_active">Mark as Available for Sale</label>
    </dd>
  </dl>
</fieldset>
