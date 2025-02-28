<?php
if (!isset($product)) {
  $product = new Product();
}

// Fetch all predefined price units
$units = PriceUnit::find_all();
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

    <!-- Price Units: Multi-Selection -->
    <dt>Price & Units</dt>
    <dd>
      <p>Select price units and enter corresponding prices:</p>

      <!-- Count-Based Units -->
      <strong>Count:</strong><br>
      <?php
      $count_units = ['dozen', 'half dozen', 'each'];
      foreach ($units as $unit) {
        if (in_array(strtolower($unit->unit_name), $count_units)) {
          echo "<label><input type='checkbox' name='product_price_unit[{$unit->price_unit_id}][selected]' value='1' 
        onchange='togglePriceInput(this, \"price_{$unit->price_unit_id}\")'> " . h($unit->unit_name) . "</label>";
          echo "<input type='number' step='0.01' name='product_price_unit[{$unit->price_unit_id}][price]' 
        id='price_{$unit->price_unit_id}' style='display:none;' placeholder='Enter price'><br>";
        }
      }
      ?>

      <!-- Weight-Based Units -->
      <strong>Weight:</strong><br>
      <?php
      $weight_units = ['pound', 'ounce'];
      foreach ($units as $unit) {
        if (in_array(strtolower($unit->unit_name), $weight_units)) {
          echo "<label><input type='checkbox' name='product_price_unit[{$unit->price_unit_id}][selected]' value='1' 
        onchange='togglePriceInput(this, \"price_{$unit->price_unit_id}\")'> " . h($unit->unit_name) . "</label>";
          echo "<input type='number' step='0.01' name='product_price_unit[{$unit->price_unit_id}][price]' 
        id='price_{$unit->price_unit_id}' style='display:none;' placeholder='Enter price'><br>";
        }
      }
      ?>

      <!-- Volume-Based Units -->
      <strong>Volume:</strong><br>
      <?php
      $volume_units = ['gallon', 'quart', 'pint', 'cup'];
      foreach ($units as $unit) {
        if (in_array(strtolower($unit->unit_name), $volume_units)) {
          echo "<label><input type='checkbox' name='product_price_unit[{$unit->price_unit_id}][selected]' value='1' 
        onchange='togglePriceInput(this, \"price_{$unit->price_unit_id}\")'> " . h($unit->unit_name) . "</label>";
          echo "<input type='number' step='0.01' name='product_price_unit[{$unit->price_unit_id}][price]' 
        id='price_{$unit->price_unit_id}' style='display:none;' placeholder='Enter price'><br>";
        }
      }
      ?>

      <!-- Other Units -->
      <strong>Other:</strong><br>
      <?php
      $other_units = ['bushel', 'bundle'];
      foreach ($units as $unit) {
        if (in_array(strtolower($unit->unit_name), $other_units)) {
          echo "<label><input type='checkbox' name='product_price_unit[{$unit->price_unit_id}][selected]' value='1' 
        onchange='togglePriceInput(this, \"price_{$unit->price_unit_id}\")'> " . h($unit->unit_name) . "</label>";
          echo "<input type='number' step='0.01' name='product_price_unit[{$unit->price_unit_id}][price]' 
        id='price_{$unit->price_unit_id}' style='display:none;' placeholder='Enter price'><br>";
        }
      }
      ?>
    </dd>


    <dt><label for="product_image">Product Image</label></dt>
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
